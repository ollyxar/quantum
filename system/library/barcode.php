<?php
class QBarcode {
	public $bar_color = array(0, 0, 0);
	public $bg_color = array(255, 255, 255);
	public $text_color = array(0, 0, 0);
	public $font = 'system/fonts/FreeSansBold.ttf';
	public $genbarcode_loc = "/usr/local/bin/genbarcode";
	public $code;

	public function __construct($code) {
		$this->code = $code;
	}

	private function barcodeGenEANSum($ean) {
		$even = true;
		$esum = 0;
		$osum = 0;
		for ($i = strlen($ean) - 1; $i >= 0; $i--) {
			if ($even) $esum += $ean[$i]; else $osum += $ean[$i];
			$even = !$even;
		}
		return (10 - ((3 * $esum + $osum) % 10)) % 10;
	}

	private function barcodeEncodeEAN($ean, $encoding = "EAN-13") {
		$digits = array(3211, 2221, 2122, 1411, 1132, 1231, 1114, 1312, 1213, 3112);
		$mirror = array("000000", "001011", "001101", "001110", "010011", "011001", "011100", "010101", "010110", "011010");
		$guards = array("9a1a", "1a1a1", "a1a");

		$ean = trim($ean);
		if (preg_match("#[^0-9]#i", $ean)) {
			return array("text" => "Invalid EAN-Code");
		}
		$encoding = strtoupper($encoding);
		if ($encoding == "ISBN") {
			if (!preg_match("#^978#", $ean)) $ean = "978" . $ean;
		}
		if (preg_match("#^978#", $ean)) $encoding = "ISBN";
		if (strlen($ean) < 12 || strlen($ean) > 13) {
			return array("text" => "Invalid $encoding Code (must have 12/13 numbers)");
		}

		$ean = substr($ean, 0, 12);
		$eansum = $this->barcodeGenEANSum($ean);
		$ean .= $eansum;
		$line = $guards[0];
		for ($i = 1; $i < 13; $i++) {
			$str = $digits[$ean[$i]];
			if ($i < 7 && $mirror[$ean[0]][$i - 1] == 1) $line .= strrev($str); else $line .= $str;
			if ($i == 6) $line .= $guards[1];
		}
		$line .= $guards[2];

		/* create text */
		$pos = 0;
		$text = "";
		for ($a = 0; $a < 13; $a++) {
			if ($a > 0) $text .= " ";
			$text .= "$pos:12:{$ean[$a]}";
			if ($a == 0) $pos += 12;
			else if ($a == 6) $pos += 12;
			else $pos += 7;
		}

		return array(
			"encoding" => $encoding,
			"bars" => $line,
			"text" => $text
		);
	}

	private function barcodeImage($text, $bars, $scale = 1, $total_y = 0, $space = ''){
		/* set defaults */
		if ($scale < 1) $scale = 2;
		$total_y = (int)($total_y);
		if ($total_y < 1) $total_y = (int)$scale * 60;
		if (!$space)
			$space = array('top' => 2 * $scale, 'bottom' => 2 * $scale, 'left' => 2 * $scale, 'right' => 2 * $scale);

		/* count total width */
		$xpos = 0;
		$width = true;
		for ($i = 0; $i < strlen($bars); $i++) {
			$val = strtolower($bars[$i]);
			if ($width) {
				$xpos += (int)$val * $scale;
				$width = false;
				continue;
			}
			if (preg_match("#[a-z]#", $val)) {
				/* tall bar */
				$val = ord($val) - ord('a') + 1;
			}
			$xpos += $val * $scale;
			$width = true;
		}

		/* allocate the image */
		$total_x = ($xpos) + $space['right'] + $space['right'];
		$xpos = $space['left'];
		$im = imagecreate($total_x, $total_y);
		/* create two images */
		ImageColorAllocate($im, $this->bg_color[0], $this->bg_color[1], $this->bg_color[2]);
		$col_bar = ImageColorAllocate($im, $this->bar_color[0], $this->bar_color[1], $this->bar_color[2]);
		$col_text = ImageColorAllocate($im, $this->text_color[0], $this->text_color[1], $this->text_color[2]);
		$height = round($total_y - ($scale * 10));
		$height2 = round($total_y - $space['bottom']);


		/* paint the bars */
		$width = true;
		for ($i = 0; $i < strlen($bars); $i++) {
			$val = strtolower($bars[$i]);
			if ($width) {
				$xpos += (int)$val * $scale;
				$width = false;
				continue;
			}
			if (preg_match("#[a-z]#", $val)) {
				/* tall bar */
				$val = ord($val) - ord('a') + 1;
				$h = $height2;
			} else $h = $height;
			imagefilledrectangle($im, $xpos, $space['top'], $xpos + ($val * $scale) - 1, $h, $col_bar);
			$xpos += $val * $scale;
			$width = true;
		}
		/* write out the text */
		global $_SERVER;
		$chars = explode(" ", $text);
		reset($chars);
		while (list($n, $v) = each($chars)) {
			if (trim($v)) {
				$inf = explode(":", $v);
				$fontsize = $scale * ($inf[1] / 1.8);
				$fontheight = $total_y - ($fontsize / 2.7) + 2;
				@imagettftext($im, $fontsize, 0, $space['left'] + ($scale * $inf[0]) + 2,
					$fontheight, $col_text, $this->font, $inf[2]);
			}
		}
		return $im;
	}

	private function barcodeEncodeGenBarcode($code, $encoding){
		/* delete EAN-13 checksum */
		if (preg_match("#^ean$#i", $encoding) && strlen($code) == 13) $code = substr($code, 0, 12);
		if (!$encoding) $encoding = "ANY";
		$encoding = preg_replace("#[|\\\\]#", "_", $encoding);
		$code = preg_replace("#[|\\\\]#", "_", $code);
		$cmd = $this->genbarcode_loc . " " . escapeshellarg($code) . " " . escapeshellarg(strtoupper($encoding));
		$fp = popen($cmd, "r");
		if ($fp) {
			$bars = fgets($fp, 1024);
			$text = fgets($fp, 1024);
			$encoding = fgets($fp, 1024);
			pclose($fp);
		} else return false;
		$ret = array(
			"encoding" => trim($encoding),
			"bars" => trim($bars),
			"text" => trim($text)
		);
		if (!$ret['encoding']) return false;
		if (!$ret['bars']) return false;
		if (!$ret['text']) return false;
		return $ret;
	}

	private function barcodeEncode($code, $encoding){
		if (((preg_match("#^ean$#i", $encoding) && (strlen($code) == 12 || strlen($code) == 13)))
			|| (($encoding) && (preg_match("#^isbn$#i", $encoding)) && ((strlen($code) == 9 || strlen($code) == 10) ||
					(((preg_match("#^978#", $code) && strlen($code) == 12) || (strlen($code) == 13)))))
			|| ((!isset($encoding) || !$encoding || (preg_match("#^ANY$#i", $encoding)))
				&& (preg_match("#^[0-9]{12,13}$#", $code)))
		) {
			/* use built-in EAN-Encoder */
			$bars = $this->barcodeEncodeEAN($code, $encoding);
		} else if (file_exists($this->genbarcode_loc)) {
			/* use genbarcode */
			$bars = $this->barcodeEncodeGenBarcode($code, $encoding);
		} else {
			return false;
		}
		return $bars;
	}

	public function barcodePrint($encoding = "ANY", $scale = 2, $mode = "png"){
		$bars = $this->barcodeEncode($this->code, $encoding);
		if (!$bars) return false;
		if (!$mode) $mode = "png";
		$mode = strtolower($mode);

		$im = $this->barcodeImage($bars['text'], $bars['bars'], $scale, $mode);

		if ($mode == 'jpg' || $mode == 'jpeg') {
			header("Content-Type: image/jpeg; name=\"barcode.jpg\"");
			imagejpeg($im);
		} else if ($mode == 'gif') {
			header("Content-Type: image/gif; name=\"barcode.gif\"");
			imagegif($im);
		} else {
			header("Content-Type: image/png; name=\"barcode.png\"");
			imagepng($im);
		}
		return $bars;
	}

	public function getBarcodeImage($encoding = "ANY", $scale = 2, $mode = "png"){
		$bars = $this->barcodeEncode($this->code, $encoding);
		if (!$bars) return false;
		if (!$mode) $mode = "png";
		$mode = strtolower($mode);
		return $this->barcodeImage($bars['text'], $bars['bars'], $scale, $mode);
	}
}