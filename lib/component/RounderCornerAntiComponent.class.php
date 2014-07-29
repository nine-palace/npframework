<?php
/**
 * @author [LT] Laurent Thoulon
 * @version 2.0 [LT] 2009-05-13
 * 
 * Version 2.0 allows you to use cache in an efficient way (no corner reloading)
 * 
 * Class adaptation of Ulrich Mierendorff's imageSmoothArc function
 * http://mierendo.com/software/antialiased_arcs/
 * 
 * Inspired by Iain Campbell's script for rounded corners
 * http://theapothecaryscat.com/im/roundedcorner.phps
 * 
 * r = radius
 * c = color
 * bg = background color
 * b = border size
 * bc = border color
 * l = location (see function outputImage for values to use)
 * 
 * Example :
 * [code]
 * 
 * file 'print.php' :
 * 	<img src="rc.php?l=TL&r=50&b=5"/>
 * 	<img src="<?php echo RoundCorner::getImageUrl(array('l'=>'tl','r'=>50,'b'=>5), true); ?>"/>
 * 
 * file 'rc.php' :
 * 	require_once('RoundCorner.class.php');
 * 	$oRoundCorner = new RoundCorner();
 * 	if(isset($_GET['r'])) $oRoundCorner->setRadius($_GET['r']);
 * 	if(isset($_GET['c'])) $oRoundCorner->setColor($_GET['c']);
 * 	if(isset($_GET['bg'])) $oRoundCorner->setBgColor($_GET['bg']);
 * 	if(isset($_GET['b'])) $oRoundCorner->setBorder($_GET['b']);
 * 	if(isset($_GET['bc'])) $oRoundCorner->setBorderColor($_GET['bc']);
 * 	if(isset($_GET['usecache'])) $oRoundCorner->useCache(true);
 * 	$oRoundCorner->outputImage(isset($_GET['l']) ? $_GET['l']:null) ;
 * 
 * [/code]
 *  * 
 *
 * License for this class:
 *
 * Copyright (c) 2009 Laurent Thoulon
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */

class RoundCornerAnti {
	//File that will be used to generate images in it. Must be relative to the place you want to use it (ex : css file)
	public static $rcFile = 'example.php';
	//Directory that will be used to store generated images. Must be relative to the place you want to use it (ex : css file)
	public static $cacheDir = './rc_cache/';
	public static $defaultValues = array(
		'b' => 0,
		'bc' => '00000000',
		'bg' => 'transparent',
		'c' => 'dddddd00',
		'l' => 'full',
		'r' => 10
	);
	private $iRadius;
	private $aColor;
	private $aBgColor;
	private $iBorder;
	private $aBorderColor;
	private $sUseCache;
	private $sFileName;

	public function RoundCornerAnti($iRadius = null, $aColor = null, $aBgColor = null, $iBorder = null, $aBorderColor = null, $bUseCache = null) {
		$this->setRadius($iRadius);
		$this->setColor($aColor);
		$this->setBgColor($aBgColor);
		$this->setBorder($iBorder);
		$this->setBorderColor($aBorderColor);
		$this->useCache($bUseCache);
	}
	
	//Static Methods	
	public static function getImageUrl($aParams, $sUseCache = false) {
		if($sUseCache) {
			$sFileName = self::getCacheFileName($aParams);
			if(file_exists($sFileName)) return $sFileName;
		}
		$sFileName = self::getFileName($aParams, $sUseCache);
		return $sFileName;
	}
	
	public static function hex2rgba($hex) {
		if(strpos($hex,'#') === 0) $hex = substr($hex,1);
		if($hex == 'transparent') return array(0,0,0,127);
		else {
			if(strlen($hex) == 3 OR strlen($hex) == 4) {
				$oldHex = str_split($hex);
				$hex = '';
				foreach($oldHex as $char) {
					$hex .= $char.$char;
				}
			}
						
			if(strlen($hex) == 6) return array( hexdec(substr($hex, 0, 2)),//R
												hexdec(substr($hex, 2, 2)),//G
												hexdec(substr($hex, 4, 2)),//B
												0);//A
			elseif(strlen($hex) == 8) return array( hexdec(substr($hex, 0, 2)),//R
													hexdec(substr($hex, 2, 2)),//G
													hexdec(substr($hex, 4, 2)),//B
													floor(hexdec(substr($hex,6,2))/2));//A
		}
		return false;
	}
	
	//Methods
	public function setRadius ($iRadius = null) {
		if(is_null($iRadius)) $iRadius = self::$defaultValues['r'];
		$this->iRadius = $iRadius;
	}
	
	public function setColor ($aColor = null) {
		if(is_null($aColor)) $aColor = self::$defaultValues['c'];
		$this->aColor = is_array($aColor) ? $aColor : RoundCornerAnti::hex2rgba($aColor);
	}
	
	public function setBgColor ($aBgColor = null) {
		if(is_null($aBgColor)) $aBgColor = self::$defaultValues['bg'];
		$this->aBgColor = is_array($aBgColor) ? $aBgColor : RoundCornerAnti::hex2rgba($aBgColor);
	}
	
	public function setBorder ($iBorder = null) {
		if(is_null($iBorder)) $iBorder = self::$defaultValues['b'];
		$this->iBorder = $iBorder;
	}
	
	public function setBorderColor ($aBorderColor = null) {
		if(is_null($aBorderColor)) $aBorderColor = self::$defaultValues['bc'];
		$this->aBorderColor = is_array($aBorderColor) ? $aBorderColor : RoundCornerAnti::hex2rgba($aBorderColor);
	}
	
	public function useCache ($sUseCache = false) {
		$this->sUseCache = $sUseCache ? true:false;
	}
	
	public function outputImage($location = null){
		if(is_null($location)) $location = self::$defaultValues['l'];
		$location = strtoupper($location);
		
		switch($location) {
			case 'TR'://Top-right corner
				$start = 0;
				$stop = M_PI/2;
				$h = $this->iRadius;
				$w = $this->iRadius;
				$cX = -2;
				$cY = $this->iRadius+1;
				break;
			case 'TL'://Top-left corner
				$start = M_PI/2;
				$stop = M_PI;
				$h = $this->iRadius;
				$w = $this->iRadius;
				$cX = $this->iRadius;
				$cY = $this->iRadius+1;
				break;
			case 'BL'://Bortom-left corner
				$start = M_PI;
				$stop = 3*M_PI/2;
				$h = $this->iRadius;
				$w = $this->iRadius;
				$cX = $this->iRadius;
				$cY = -1;
				break;
			case 'BR'://Bottom-right corner
				$start = 3*M_PI/2;
				$stop = 2*M_PI;
				$h = $this->iRadius;
				$w = $this->iRadius;
				$cX = -2;
				$cY = -1;
				break;
			case 'TH'://Top half
				$start = 0;
				$stop = M_PI;
				$h = $this->iRadius;
				$w = $this->iRadius*2+2;
				$cX = $this->iRadius;
				$cY = $this->iRadius+1;
				break;
			case 'BH'://Bottom half
				$start = M_PI;
				$stop = 2*M_PI;
				$h = $this->iRadius;
				$w = $this->iRadius*2+2;
				$cX = $this->iRadius;
				$cY = -1;
				break;
			case 'LH'://Left Half
				$start = M_PI/2;
				$stop = 3*M_PI/2;
				$h = $this->iRadius*2+2;
				$w = $this->iRadius;
				$cX = $this->iRadius;
				$cY = $this->iRadius+1;
				break;
			case 'RH'://Right Half
				$start = 3*M_PI/2;
				$stop = M_PI/2;
				$h = $this->iRadius*2+2;
				$w = $this->iRadius;
				$cX = -2;
				$cY = $this->iRadius+1;
				break;
			default://Full
				$start = 0;
				$stop = 2*M_PI;
				$h = $this->iRadius*2+2;
				$w = $this->iRadius*2+2;
				$cX = $this->iRadius;
				$cY = $this->iRadius+1;
				break;
		}
		$img = imageCreateTrueColor( $w , $h );
		//$this->aBgColor
		$color = imagecolorallocatealpha($img,$this->aBgColor[0],$this->aBgColor[1],$this->aBgColor[2],$this->aBgColor[3]);
		//$color = imageColorTransparent($img);
		imagefill( $img, 0, 0, $color );
		
		if($this->iBorder > 0) {
			$this->imageSmoothArc ( $img, $cX, $cY, $this->iRadius*2,$this->iRadius*2, $this->aBorderColor, $start, $stop);
			$this->imageSmoothArc ( $img, $cX, $cY, ($this->iRadius-$this->iBorder)*2,($this->iRadius-$this->iBorder)*2, $this->aColor, $start, $stop);
		}
		else {
			$this->imageSmoothArc ( $img, $cX, $cY, $this->iRadius*2,$this->iRadius*2, $this->aColor, $start, $stop);
		}
		
		
		imageSaveAlpha( $img, true );
		return $img;
// 		if($this->sUseCache) {
// 			$aParams = array(
// 				'r' => $this->iRadius,
// 				'c' => $this->aColor,
// 				'bg' => $this->aBgColor,
// 				'b' => $this->iBorder,
// 				'bc' => $this->aBorderColor,
// 				'l' => $location
// 			);
// 			$sFileName = self::getCacheFileName($aParams);
// 			imagePNG( $img , $sFileName);
// 			header('Location: '.$sFileName);
// 		}
// 		else {
// 			header( 'Content-Type: image/png' );
// 			imagePNG( $img );
// 		}
	}
	
	//Private Methods
	private static function getFileName($aParams, $bUseCache = false) {
		$sArgs = '';
		if(isset($aParams['r'])) {
			if($sArgs != '') $sArgs .= '&';
			$sArgs .= 'r='.$aParams['r'];
		}
		if(isset($aParams['c'])) {
			if($sArgs != '') $sArgs .= '&';
			$sArgs .= 'c='.$aParams['c'];
		}
		if(isset($aParams['bg'])) {
			if($sArgs != '') $sArgs .= '&';
			$sArgs .= 'bg='.$aParams['bg'];
		}
		if(isset($aParams['b'])) {
			if($sArgs != '') $sArgs .= '&';
			$sArgs .= 'b='.$aParams['b'];
		}
		if(isset($aParams['bc'])) {
			if($sArgs != '') $sArgs .= '&';
			$sArgs .= 'bc='.$aParams['bc'];
		}
		if(isset($aParams['l'])) {
			if($sArgs != '') $sArgs .= '&';
			$sArgs .= 'l='.$aParams['l'];
		}
		if($bUseCache) {
			if($sArgs != '') $sArgs .= '&';
			$sArgs .= 'usecache';
		}
		return self::$rcFile.'?'.$sArgs;
	}
	
	/**
	 * @param Array $aParams An associative array containing only the 6 parameters r, c, b, bc, bg and l
	 */
	private static function getCacheFileName ($aParams) {
		$sString = '';
		foreach(self::$defaultValues as $k=>$v) {
			if(!isset($aParams[$k])) $aParams[$k] = $v;
		}
		ksort($aParams);
		foreach($aParams as $k => $vParam) {
			if(($k == 'c' OR $k == 'bc' OR $k == 'bg') AND !is_array($vParam)) $vParam = RoundCornerAnti::hex2rgba($vParam);
			if($sString != '') $sString .= '_';
			if(is_array($vParam)) {
				ksort($vParam);
				$vParam = serialize($vParam);
			}
			$sString .= strtolower($vParam);
		}
		return self::$cacheDir.'rc_'.md5($sString).'.png';
	}

	/**
	 * @author Ulrich Mierendorff
	 * @params $cx 		Center of ellipse, X-coord
	 * @params $cy 		Center of ellipse, Y-coord
	 * @params $w 		Width of ellipse ($w >= 2)
	 * @params $h 		Height of ellipse ($h >= 2 )
	 * @params $color 	Color of ellipse as a four component array with RGBA
	 * @params $start 	Starting angle of the arc: 0, PI/2, PI, PI/2*3, 2*PI,... (0,90°,180°,270°,360°,...)
	 * @params $stop 	Stop	 angle of the arc: 0, PI/2, PI, PI/2*3, 2*PI,... (0,90°,180°,270°,360°,...)
	 * $start _can_ be greater than $stop!
	 * If any value is not in the given range, results are undefined!
	 * This script does not use any special algorithms, everything is completely
	 * written from scratch; see http://de.wikipedia.org/wiki/Ellipse for formulas.
	 * 
	 * Initially created, 06/2006
	 * Rewritten and improved, 04/2007, 07/2007
	 * Optimized circle version: 03/2008
	 * compared to old version:
	 * + Support for transparency added
	 * + Improved quality of edges & antialiasing
	 * 
	 * note: This function does not represent the fastest way to draw elliptical
	 * arcs. It was written without reading any papers on that subject. Better
	 * algorithms may be twice as fast or even more.
	 *
	 * License for this function:
	 *
     * Copyright (c) 2006-2009 Ulrich Mierendorff
     * 
     * Permission is hereby granted, free of charge, to any person obtaining a
     * copy of this software and associated documentation files (the "Software"),
     * to deal in the Software without restriction, including without limitation
     * the rights to use, copy, modify, merge, publish, distribute, sublicense,
     * and/or sell copies of the Software, and to permit persons to whom the
     * Software is furnished to do so, subject to the following conditions:
     * 
     * The above copyright notice and this permission notice shall be included in
     * all copies or substantial portions of the Software.
     * 
     * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
     * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
     * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
     * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
     * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
     * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
     * DEALINGS IN THE SOFTWARE.
	 */
	private function imageSmoothArc ( &$img, $cx, $cy, $w, $h, $color, $start, $stop) {
		while ($start < 0)
			$start += 2*M_PI;
		while ($stop < 0)
			$stop += 2*M_PI;
		
		while ($start > 2*M_PI)
			$start -= 2*M_PI;
		
		while ($stop > 2*M_PI)
			$stop -= 2*M_PI;
		
		
		if ($start > $stop) {
			$this->imageSmoothArc ( $img, $cx, $cy, $w, $h, $color, $start, 2*M_PI);
			$this->imageSmoothArc ( $img, $cx, $cy, $w, $h, $color, 0, $stop);
			return;
		}
		
		$a = 1.0*round ($w/2);
		$b = 1.0*round ($h/2);
		$cx = 1.0*round ($cx);
		$cy = 1.0*round ($cy);
		
		for ($i=0; $i<4;$i++) {
			if ($start < ($i+1)*M_PI/2) {
				if ($start > $i*M_PI/2) {
					if ($stop > ($i+1)*M_PI/2) {
						$this->imageSmoothArcDrawSegment($img, $cx, $cy, $a, $b, $color, $start, ($i+1)*M_PI/2, $i);
					}
					else {
						$this->imageSmoothArcDrawSegment($img, $cx, $cy, $a, $b, $color, $start, $stop, $i);
						break;
					}
				}
				else {
					if ($stop > ($i+1)*M_PI/2) {
						$this->imageSmoothArcDrawSegment($img, $cx, $cy, $a, $b, $color, $i*M_PI/2, ($i+1)*M_PI/2, $i);
					}
					else {
						$this->imageSmoothArcDrawSegment($img, $cx, $cy, $a, $b, $color, $i*M_PI/2, $stop, $i);
						break;
					}
				}
			}
		}
	}

	/**
	 * @author Ulrich Mierendorff
	 * @params $img 	
	 * @params $cx 		
	 * @params $cy 		
	 * @params $a 		
	 * @params $b 		
	 * @params $color 	
	 * @params $start 	
	 * @params $stop 
	 * @params $seg 	
	 *
	 * Originally written from scratch, 06/2006
	 * Rewritten and improved, 04/2007, 07/2007
	 * Optimized circle version: 03/2008
	 * Please do not use THIS function directly. Scroll to imageSmoothArc(...).
	 *
	 * License for this function:
	 *
	 * Copyright (c) 2006-2009 Ulrich Mierendorff
     * 
     * Permission is hereby granted, free of charge, to any person obtaining a
     * copy of this software and associated documentation files (the "Software"),
     * to deal in the Software without restriction, including without limitation
     * the rights to use, copy, modify, merge, publish, distribute, sublicense,
     * and/or sell copies of the Software, and to permit persons to whom the
     * Software is furnished to do so, subject to the following conditions:
     * 
     * The above copyright notice and this permission notice shall be included in
     * all copies or substantial portions of the Software.
     * 
     * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
     * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
     * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
     * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
     * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
     * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
     * DEALINGS IN THE SOFTWARE.
	 */
	private function imageSmoothArcDrawSegment ($img, $cx, $cy, $a, $b, $color, $start, $stop, $seg) {
		$fillColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], $color[3] );
		switch ($seg) {
			case 0: $xp = +1; $yp = -1; $xa = 1; $ya = -1; break;
			case 1: $xp = -1; $yp = -1; $xa = 0; $ya = -1; break;
			case 2: $xp = -1; $yp = +1; $xa = 0; $ya = 0; break;
			case 3: $xp = +1; $yp = +1; $xa = 1; $ya = 0; break;
		}
		for ( $x = 0; $x <= $a; $x += 1 ) {
			$y = $b * sqrt( 1 - ($x*$x)/($a*$a) );
			$error = $y - (int)($y);
			$y = (int)($y);
			$diffColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error );
			imageSetPixel($img, $cx+$xp*$x+$xa, $cy+$yp*($y+1)+$ya, $diffColor);
			imageLine($img, $cx+$xp*$x+$xa, $cy+$yp*$y+$ya , $cx+$xp*$x+$xa, $cy+$ya, $fillColor);
		}
		for ( $y = 0; $y < $b; $y += 1 ) {
			$x = $a * sqrt( 1 - ($y*$y)/($b*$b) );
			$error = $x - (int)($x);
			$x = (int)($x);
			$diffColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error );
			imageSetPixel($img, $cx+$xp*($x+1)+$xa, $cy+$yp*$y+$ya, $diffColor);
		}
	}
}
?>
