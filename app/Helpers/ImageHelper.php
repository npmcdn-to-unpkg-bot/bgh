<?php
namespace App\Helpers;

class ImageHelper
{
    private $path = "";

    function __construct($filePath)
    {
        $this->path = $filePath;
    }

	// presition es cuanto redondeo los colores parecidos y slots la cantidad de colores que quiero que devuelva el array
    public function getColorPalette($slots = 0, $precition = 15)
	{

		$PREVIEW_WIDTH    = 150;  //WE HAVE TO RESIZE THE IMAGE, BECAUSE WE ONLY NEED THE MOST SIGNIFICANT COLORS.
		$PREVIEW_HEIGHT   = 150;
		$size = GetImageSize($this->path);
		$scale=1;
		if ($size[0]>0)
		$scale = min($PREVIEW_WIDTH/$size[0], $PREVIEW_HEIGHT/$size[1]);
		if ($scale < 1)
		{
			$width = floor($scale*$size[0]);
			$height = floor($scale*$size[1]);
		}
		else
		{
			$width = $size[0];
			$height = $size[1];
		}

		$image_resized = imagecreatetruecolor($width, $height);

		if ($size[2]==1)
			$image_orig=imagecreatefromgif($this->path);
		if ($size[2]==2)
			$image_orig=imagecreatefromjpeg($this->path);
		if ($size[2]==3)
			$image_orig=imagecreatefrompng($this->path);

		imagecopyresampled($image_resized, $image_orig, 0, 0, 0, 0, $width, $height, $size[0], $size[1]); //WE NEED NEAREST NEIGHBOR RESIZING, BECAUSE IT DOESN'T ALTER THE COLORS
		$im = $image_resized;
		$imgWidth = imagesx($im);
		$imgHeight = imagesy($im);
		for ($y=0; $y < $imgHeight; $y++)
		{
			for ($x=0; $x < $imgWidth; $x++)
			{
				$index = imagecolorat($im,$x,$y);
				$Colors = imagecolorsforindex($im,$index);
				$Colors['red']=intval((($Colors['red'])+$precition)/32)*32;    //ROUND THE COLORS, TO REDUCE THE NUMBER OF COLORS, SO THE WON'T BE ANY NEARLY DUPLICATE COLORS!
				$Colors['green']=intval((($Colors['green'])+$precition)/32)*32;
				$Colors['blue']=intval((($Colors['blue'])+$precition)/32)*32;
				if ($Colors['red']>=256)
				$Colors['red']=240;
				if ($Colors['green']>=256)
				$Colors['green']=240;
				if ($Colors['blue']>=256)
				$Colors['blue']=240;
				$hexarray[]=substr("0".dechex($Colors['red']),-2).substr("0".dechex($Colors['green']),-2).substr("0".dechex($Colors['blue']),-2);
			}
		}
		$hexarray=array_count_values($hexarray);
		natsort($hexarray);
		$hexarray=array_reverse($hexarray,true);

		if($slots>0){
			$hexarray =array_slice($hexarray,0,$slots, true);
		}

		$res = [];
		foreach ($hexarray as $key => $value) {
			array_push($res, $key);
		}

		return $res;
	}


	public function getDominantColors($samples = 4){

		$colors = $this->getColorPalette($samples);

		$color1 = $colors[0];
		$color1sum = 0;
		$color2 = $colors[1];
		$dif=0;

		for ($i = 0; $i < sizeof($colors); $i++){

			$r = hexdec(substr($colors[$i],0,2));
			$g = hexdec(substr($colors[$i],2,2));
			$b = hexdec(substr($colors[$i],4,2));

			$sum = $r + $g + $b;

			// echo '<br><br><br>$sum: ' . $sum;
			// echo '<br>$dif: ' . $dif;

			// dentro de los samples, tomo el color predominante y el segundo color que sea mas lejano a ese primer dominante
			if($i==0){
				$color1sum=$sum;
				$dif=0;
			}
			else{

				$difact = abs($color1sum-$sum);
				// echo '<br>$difact: ' . $difact;
				if( $difact > $dif){
					$dif = $difact;
					$color2 = $colors[$i];
				}

			}


		}

		return array($color1, $color2);

	}


	public function getOverallBrigthness($samples = 3){

		$colors = $this->getColorPalette($samples);

		$neutral = 0;

		for ($i = 0; $i < sizeof($colors); $i++){

			$r = hexdec(substr($colors[$i],0,2));
			$g = hexdec(substr($colors[$i],2,2));
			$b = hexdec(substr($colors[$i],4,2));

			$sum = $r + $g + $b;

			// differently. That is a green of 128 might be brighter than a red of 128.
			if($sum > 382){
				$neutral++;
			}else{
				$neutral--;
			}

		}

		return $neutral;

	}

}

