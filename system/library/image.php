<?php
class QImage {
    private $file;
    private $image;
    private $info;
		
	public function __construct($file) {
		if (file_exists($file)) {
			$this->file = $file;

			$info = getimagesize($file);

			$this->info = array(
            	'width'  => $info[0],
            	'height' => $info[1],
            	'bits'   => $info['bits'],
            	'mime'   => $info['mime']
        	);
        	
        	$this->image = $this->create($file);
            if (!$this->image) {
                exit('Error: Unrecognizable format of ' . $file . '!');
            }
    	} else {
      		exit('Error: Could not load image ' . $file . '!');
    	}
	}
		
	private function create($image) {
		$mime = $this->info['mime'];
		
		if ($mime == 'image/gif') {
			return imagecreatefromgif($image);
		} elseif ($mime == 'image/png') {
			return imagecreatefrompng($image);
		} elseif ($mime == 'image/jpeg') {
			return imagecreatefromjpeg($image);
		} else return false;
    }	
	
    public function save($file, $quality = 90) {
		$info = pathinfo($file);
       
		$extension = strtolower($info['extension']);
   		
		if (is_resource($this->image)) {
			if ($extension == 'jpeg' || $extension == 'jpg') {
				imagejpeg($this->image, $file, $quality);
			} elseif($extension == 'png') {
				imagepng($this->image, $file);
			} elseif($extension == 'gif') {
				imagegif($this->image, $file);
			}
			   
			imagedestroy($this->image);
		}
    }

    public function resize($width = 0, $height = 0, $crop = true) {
        if ($crop) {
            if (!$this->info['width'] || !$this->info['height']) {
                return false;
            }

            $x = $this->info['width'];
            $y = $this->info['height'];

            // old images width will fit
            if (($x / $y) < ($width / $height)) {
                $scale = $width / $x;
                $newX = 0;
                $newY = -($scale * $y - $height) / 2;

                // else old image's height will fit
            } else {
                $scale = $height / $y;
                $newX = -($scale * $x - $width) / 2;
                $newY = 0;
            }

            $image_old = $this->image;
            $this->image = imagecreatetruecolor($width, $height);

            $new_width = ceil($scale * $x);
            $new_height = ceil($scale * $y);

            // now use imagecopyresampled
            imagecopyresampled($this->image, $image_old, $newX, $newY, 0, 0, $new_width, $new_height, $x, $y);

            imagedestroy($image_old);

            $this->info['width'] = $width;
            $this->info['height'] = $height;
        } else {
            if (!$this->info['width'] || !$this->info['height']) {
                return false;
            }

            $default = 'w';

            $scale_w = $width / $this->info['width'];
            $scale_h = $height / $this->info['height'];

            if ($default == 'w') {
                $scale = $scale_w;
            } elseif ($default == 'h') {
                $scale = $scale_h;
            } else {
                $scale = min($scale_w, $scale_h);
            }

            if ($scale == 1 && $scale_h == $scale_w && $this->info['mime'] != 'image/png') {
                return false;
            }

            $new_width = (int)($this->info['width'] * $scale);
            $new_height = (int)($this->info['height'] * $scale);
            $xpos = (int)(($width - $new_width) / 2);
            $ypos = (int)(($height - $new_height) / 2);

            $image_old = $this->image;
            $this->image = imagecreatetruecolor($width, $height);

            if (isset($this->info['mime']) && $this->info['mime'] == 'image/png') {
                imagealphablending($this->image, false);
                imagesavealpha($this->image, true);
                $background = imagecolorallocatealpha($this->image, 255, 255, 255, 127);
                imagecolortransparent($this->image, $background);
            } else {
                $background = imagecolorallocate($this->image, 255, 255, 255);
            }

            imagefilledrectangle($this->image, 0, 0, $width, $height, $background);

            imagecopyresampled($this->image, $image_old, $xpos, $ypos, 0, 0, $new_width, $new_height, $this->info['width'], $this->info['height']);
            imagedestroy($image_old);

            $this->info['width'] = $width;
            $this->info['height'] = $height;
        }
    }

    public function watermark($file, $position = 'bottomright') {
        //$watermark = $this->create($file);
        $watermark = imagecreatefrompng($file);
        
        $watermark_width = imagesx($watermark);
        $watermark_height = imagesy($watermark);
        
        switch($position) {
            case 'topleft':
                $watermark_pos_x = 0;
                $watermark_pos_y = 0;
                break;
            case 'topright':
                $watermark_pos_x = $this->info['width'] - $watermark_width;
                $watermark_pos_y = 0;
                break;
            case 'bottomleft':
                $watermark_pos_x = 0;
                $watermark_pos_y = $this->info['height'] - $watermark_height;
                break;
            case 'center':
				$watermark_pos_x = ($this->info['width']- $watermark_width)/2;
				$watermark_pos_y = ($this->info['height']- $watermark_height)/2;
				break;
            case 'bottomright':
                $watermark_pos_x = $this->info['width'] - $watermark_width;
                $watermark_pos_y = $this->info['height'] - $watermark_height;
                break;
            default:
                $watermark_pos_x = 0;
                $watermark_pos_y = 0;
                break;
        }
        
        //imagecopy($this->image, $watermark, $watermark_pos_x, $watermark_pos_y, 0, 0, 120, 40);
        imagecopy($this->image, $watermark, $watermark_pos_x,
		$watermark_pos_y, 0, 0, $watermark_width, $watermark_height);
        
        imagedestroy($watermark);
    }
    
    public function crop($top_x, $top_y, $bottom_x, $bottom_y) {
        $image_old = $this->image;
        $this->image = imagecreatetruecolor($bottom_x - $top_x, $bottom_y - $top_y);
        
        imagecopy($this->image, $image_old, 0, 0, $top_x, $top_y, $this->info['width'], $this->info['height']);
        imagedestroy($image_old);
        
        $this->info['width'] = $bottom_x - $top_x;
        $this->info['height'] = $bottom_y - $top_y;
    }
    
    public function rotate($degree, $color = 'FFFFFF') {
		$rgb = $this->html2rgb($color);
		
        $this->image = imagerotate($this->image, $degree, imagecolorallocate($this->image, $rgb[0], $rgb[1], $rgb[2]));
        
		$this->info['width'] = imagesx($this->image);
		$this->info['height'] = imagesy($this->image);
    }
	    
	private function html2rgb($color) {
		if ($color[0] == '#') {
			$color = substr($color, 1);
		}
		
		if (strlen($color) == 6) {
			list($r, $g, $b) = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);   
		} elseif (strlen($color) == 3) {
			list($r, $g, $b) = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);    
		} else {
			return false;
		}
		
		$r = hexdec($r); 
		$g = hexdec($g); 
		$b = hexdec($b);    
		
		return array($r, $g, $b);
	}	
}
