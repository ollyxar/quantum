<?php

class QCaptcha {
    private $height = 60;
    private $width = 100;
    private $font_size = 17;
    private $letters_amount = 4;
    private $back_letters_amount = 30;
    private $code;
    private $fonts_path = 'system/fonts/';
    private $letters = array(
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w',
        'x', 'y', 'z', '2', '3', '4', '5', '6', '7', '9'
    );
    private $colors = array('10', '30', '50', '70', '90', '110', '130', '150', '170', '190', '210');
    private $image;

    public function __construct() {
        $this->reload();
    }

    private function reload() {
        $src = imagecreatetruecolor($this->width, $this->height);
        $fon = imagecolorallocate($src, 255, 255, 255);
        imagefill($src, 0, 0, $fon);

        $fonts = array();
        $dir = opendir($this->fonts_path);
        while ($fontName = readdir($dir)) {
            if ($fontName != "." && $fontName != "..") {
                $fonts[] = $fontName;
            }
        }
        closedir($dir);

        $code = array();

        for ($i = 0; $i < $this->back_letters_amount; $i++) {
            $color = imagecolorallocatealpha($src, rand(0, 255), rand(0, 255), rand(0, 255), 100);
            $font = $this->fonts_path . $fonts[rand(0, sizeof($fonts) - 1)];
            $letter = $this->letters[rand(0, sizeof($this->letters) - 1)];
            $size = rand($this->font_size - 2, $this->font_size + 2);
            imagettftext($src, $size, rand(0, 45), rand($this->width * 0.1, $this->width - $this->width * 0.1),
                rand($this->height * 0.2, $this->height), $color, $font, $letter);
        }

        for ($i = 0; $i < $this->letters_amount; $i++) {
            $color = imagecolorallocatealpha($src, $this->colors[rand(0, sizeof($this->colors) - 1)],
                $this->colors[rand(0, sizeof($this->colors) - 1)], $this->colors[rand(0, sizeof($this->colors) - 1)],
                rand(20, 40));
            $font = $this->fonts_path . $fonts[rand(0, sizeof($fonts) - 1)];
            $letter = $this->letters[rand(0, sizeof($this->letters) - 1)];
            $size = rand($this->font_size * 2.1 - 2, $this->font_size * 2.1 + 2);
            $x = ($i + 1) * $this->font_size + rand(4, 7);
            $y = (($this->height * 2) / 3) + rand(0, 5);
            $code[] = $letter;
            imagettftext($src, $size, rand(0, 15), $x, $y, $color, $font, $letter);
        }
        $this->code = implode('', $code);
        $this->image = $src;
    }

    public function getContent() {
        ob_start();
        imagegif($this->image);
        $contents =  ob_get_contents();
        ob_end_clean();
        return 'data:image/gif;base64,' . base64_encode($contents);
    }

    public function showImage() {
        header("Content-type: image/gif");
        imagegif($this->image);
    }

    public function getCode() {
        return $this->code;
    }
}