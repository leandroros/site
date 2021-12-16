<?php


function array_fill_custom($iStart, $iLen, $vValue) {
	if (function_exists('array_fill')) {
		return  array_fill($iStart, $iLen, $vValue);	
	}	
	else {	
		$aResult = array();
		for ($iCount = $iStart; $iCount < $iLen + $iStart; $iCount++) {
			$aResult[$iCount] = $vValue;
		}
		return $aResult;
	}
}



/**
 * @package NOF_Framework
 *
 * @author Adrian Pascu <apascu@innovative.ro>
 */

class NOF_CaptchaBMP{
	/**
	 * Image content
	 * @var string
	 */
	var $content     = "";

	/**
	 * BMP header definition
	 * @var array
	 */
	var $bmpHeader   = array();
	
	/**
	 * Characters definition
	 * @var array
	 */
	var $charsMap    = array();

	/**
	 * Character width
	 * @var int
	 */
	var $charWidth   = 0;
	
	/**
	 * Caracter height
	 * @var int
	 */
	var $charHeight  = 0;

	/**
	 * Number of necesarly bytes to add to compleate a dword
	 * @var int
	 */
	var $imageCorrection = 0;

	/**
	 * List with possible letters can apear in captcha image
	 * @var string
	 */
	var $chars       = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

	/**
	 * Text what apear in captcha image
	 * @var string
	 */
	var $imageChars  = "ABCDE";

	/**
	 * Hex code for the background color
	 * @var string
	 */
	var $bgColor     = "000000";
	
	/**
	 * Hex code for the foreground colors
	 * @var array
	 */
	var $fgColors    = array("ffffff");

	/**
	 * Number of blank pixels(spaces) on the top of image
	 * @var int
	 */
	var $spaceTop    = 0;
	
	/**
	 * Number of blank pixels(spaces) on the bottom of image
	 * @var int
	 */
	var $spaceBottom = 0;
	
	/**
	 * Number of blank pixels(spaces) on the left of image
	 * @var int
	 */
	var $spaceLeft   = 0;
	
	/**
	 * Number of blank pixels(spaces) on the right of image
	 * @var int
	 */
	var $spaceRight  = 0;
	
	/**
	 * Number of blank pixels(spaces) between the image chars
	 * @var int
	 */
	var $spaceInner  = 0;

	/**
	 * Location path where the characters definion is
	 * @var string
	 */
	var $charFontDir = "../NOF_Data/charsMap/trebuchet";
	
	/**
	 * Flag what show if the font definition arrays exists and have the same dimensions
	 * @var boolean
	 */
	var $success = false;

	
	/**
	 * 
	 *
	 * @param string $font	Location path where the characters definion is or empty for default definion.
	 */
	function NOF_CaptchaBMP($font=""){
		// BITMAPFILEHEADER
		$this->bmpHeader["bfType"]          = "42 4D";       // *-42 4D
		$this->bmpHeader["bfSize"]          = "56 0A 00 00"; // (width x height x color deep) + 54
		$this->bmpHeader["bfReserved1"]     = "00 00";     	 // *-00 00
		$this->bmpHeader["bfReserved2"]     = "00 00";       // *-00 00
		$this->bmpHeader["bfOffBits"]       = "36 00 00 00"; // *-36 00 00 00
		// BITMAPINFOHEADER
		$this->bmpHeader["biSize"]          = "28 00 00 00"; // *-28 00 00 00
		$this->bmpHeader["biWidth"]         = "20 00 00 00"; //
		$this->bmpHeader["biHeight"]        = "1B 00 00 00"; //
		$this->bmpHeader["biPlanes"]        = "01 00";		 // *-01 00
		$this->bmpHeader["biBitCount"]      = "18 00";       // *-18 00 (24)
		$this->bmpHeader["biCompression"]   = "00 00 00 00"; // *-00 00 00 00
		$this->bmpHeader["biSizeImage"]     = "20 0A 00 00"; // *-00 00 00 00(2592) = with x height x color deep(24)
		$this->bmpHeader["biXPelsPerMeter"] = "12 0B 00 00"; //
		$this->bmpHeader["biYPelsPerMeter"] = "12 0B 00 00"; //
		$this->bmpHeader["biClrUsed"]       = "00 00 00 00"; //
		$this->bmpHeader["biClrImportant"]  = "00 00 00 00"; // *-00 00 00 00
		
		if(strlen($font) == 0){
			$this->success = $this->setFontDir($this->charFontDir);
		}else{
			$this->success = $this->setFontDir($font);
		}
	}
	
	
	/**
	 * Add characters definition for each letter.
	 * The letters sizes(height and width) must be the same between the chars.
	 *
	 * @param string $fontDir	Location path where the characters definion is.
	 */
	function setFontDir($fontDir){
		$this->charFontDir = $fontDir;
		$charWidth = 0;
		$charHeight = 0;
		for($i=0; $i<strlen($this->chars); $i++){
			if(!file_exists($this->charFontDir . "/charMap-" . $this->chars{$i} . ".txt")){
				trigger_error("1|Could not find path charMap for letter\"" . $this->chars{$i} ."\"");
				return false;
			}
			$this->loadChar($this->chars{$i});
			if(!$this->charCheck($this->charsMap[$this->chars{$i}], $this->chars{$i})){
				return false;
			}
		}
		$this->charWidth = strlen($this->charsMap[$this->chars{0}][0]);
		return true;
	}
	
	
	/**
	 * Load the char map file into "charsMap" array
	 *
	 * @param string $charName		Character name
	 */
	function loadChar($charName){
		$charFile = file($this->charFontDir . "/charMap-" . $charName . ".txt");
		foreach($charFile as $line){
			$this->charsMap[$charName][] = trim($line);
		}
	}
	
	
	/**
	 * Characters Map verification
	 *
	 * @param  array   $char		Character definition
	 * @param  string  $charName	Character name
	 * @return boolean
	 */
	function charCheck($char, $charName){
		if(!isset($char)){
			trigger_error("1|CharMap not defined for letter\"" . $charName ."\".");
			return false;
		}
		if(!is_array($char)){
			trigger_error("1|CharMap not defined properly for letter\"" . $charName ."\", is not an array.");
			return false;
		}
		if(count($char) == 0){
			trigger_error("1|CharMap not defined properly for letter\"" . $charName ."\", array is empty.");
			return false;
		}
		if($this->charHeight == 0){
			$this->charHeight = count($char);
		}
		if($this->charHeight != count($char)){
			trigger_error("1|CharMap not defined properly for letter\"" . $charName ."\", diferent height.");
			return false;
		}
		if(!is_string($char[0])){
			trigger_error("1|CharMap not defined properly for letter\"" . $charName ."\", is not an array of strings.");
			return false;
		}
		if(strlen($char[0]) == 0){
			trigger_error("1|CharMap not defined properly for letter\"" . $charName ."\", string is empty.");
			return false;
		}
		if($this->charWidth == 0){
			$this->charWidth = strlen($char[0]);
		}
		$charTotalLength = strlen(implode("", $char));
		if(($charTotalLength/$this->charHeight != $this->charWidth) || ($charTotalLength%$this->charHeight != 0)){
			trigger_error("1|CharMap not defined properly for letter\"" . $charName ."\", diferent width.");
			return false;
		}

		return true;
	}
	
	
	/**
	 * Add a hex code foreground color
	 *
	 * @param  string $color	Color to add
	 * @return boolean			Operation success
	 */
	function addFgColor($color=""){
		if(strlen($color) == 0){
			return false;
		}else{
			if(in_array($color, $this->fgColors)){
				return false;
			}else{
				$this->fgColors[] = $color;
				return true;
			}
		}
	}
	
	
	/**
	 * Remove a color from foreground list
	 *
	 * @param  string $color	Color to remove
	 * @return boolean			Operation success
	 */
	function remFgColor($color=""){
		if(strlen($color) == 0){
			return false;
		}else{
			if(count($this->fgColors) == 1){
				return false;
			}else{
				$colorPos = array_search($color, $this->fgColors);
				if($colorPos === false){
					return false;
				}else{
					unset($this->fgColors[$colorPos]);
					$this->fgColors = array_reverse($this->fgColors);	// reindex the array
					return true;
				}
			}
		}
	}

	
	/**
	 * Return converted to a 'Big Indian' string the 'Little Indian' longint value
	 *
	 * @param  longint $decNr	'Little Indian'	value
	 * @return string			'Big Indian' string
	 */
	function hexStrLongBigIndian($decNr){
		//$littleIndian = pack("L", $decNr);
		//$bigIndian = strtoupper(dechex(implode("", unpack("N*", $littleIndian))));
		$b = array();
		$b[0] = strtoupper(dechex(($decNr & 0x000000FF) >> 0));
		$b[1] = strtoupper(dechex(($decNr & 0x0000FF00) >> 8));
		$b[2] = strtoupper(dechex(($decNr & 0x00FF0000) >> 16));
		$b[3] = strtoupper(substr(dechex(($decNr & 0xFF000000) >> 24), -2));
		for($i=0; $i<count($b); $i++){
			if(strlen($b[$i]) == 1){
				$b[$i] = "0" . $b[$i];
			}
		}

		return implode(" ", $b);
	}


	/**
	 * Return the 'Little Indian' longint value of a 'Big Indian' string
	 *
	 * @param  string	$strLongBigIndian	'Big Indian' string
	 * @return longint						'Little Indian' value
	 */
	function getLongLittleIndian($strLongBigIndian){
		$l = split(" ", $strLongBigIndian);
		$result = 0;
		for($i=0; $i<count($l); $i++){
			$result = $result + (hexdec($l[$i]) * pow(256, $i));
		}
		return $result;
	}


	/**
	 * Set the image file size in BMP header
	 *
	 * @param longint $size		The image file size reprezented on 4 bytes
	 */
	function setBmpFileSize($size=0){	// 4 byte
		if($size == 0){
			$app = 2 * $this->getLongLittleIndian($this->bmpHeader["biHeight"]) + 2;
			$app=0;
			$this->bmpHeader["bfSize"] = $this->hexStrLongBigIndian($this->getLongLittleIndian($this->bmpHeader["biWidth"]) * $this->getLongLittleIndian($this->bmpHeader["biHeight"]) * 3 + 54 + $app);
			
		}else{
			$this->bmpHeader["bfSize"] = $this->hexStrLongBigIndian($size);
		}
	}


	/**
	 * Set the image width in BMP header
	 *
	 * @param longint $width	The image width reprezented on 4 bytes
	 */
	function setBmpWidth($width){
		$this->bmpHeader["biWidth"] = $this->hexStrLongBigIndian($width);
	}


	/**
	 * Set the image height in BMP header
	 *
	 * @param longint $height	The image height reprezented on 4 bytes
	 */
	function setBmpHeight($height){
		$this->bmpHeader["biHeight"] = $this->hexStrLongBigIndian($height);
	}

	
	/**
	 * Set the image size in BMP header
	 *
	 * @param longint $size		The image size reprezented on 4 bytes
	 */
	function setBmpSizeImage($size=0){	// 4 byte
		if($size == 0){
			//$app = 2 * $this->getLongLittleIndian($this->bmpHeader["biHeight"]) + 2;
			//$app = $this->imageCorrection * $this->imageHeight;
			$app=0;

			$this->bmpHeader["biSizeImage"] = $this->hexStrLongBigIndian($this->getLongLittleIndian($this->bmpHeader["biWidth"]) * $this->getLongLittleIndian($this->bmpHeader["biHeight"]) * 3 + $app);
		}else{
			$this->bmpHeader["biSizeImage"] = $this->hexStrLongBigIndian($size);
		}
	}

	
	/**
	 * Construct the BMP image header ready to be used.
	 *
	 * @return string
	 */
	function makeBmpHeader(){
		$result = "";

		$result .= $this->bmpHeader["bfType"]          . " ";
		$result .= $this->bmpHeader["bfSize"]          . " ";
		$result .= $this->bmpHeader["bfReserved1"]     . " ";
		$result .= $this->bmpHeader["bfReserved2"]     . " ";
		$result .= $this->bmpHeader["bfOffBits"]       . " ";
		$result .= $this->bmpHeader["biSize"]          . " ";
		$result .= $this->bmpHeader["biWidth"]         . " ";
		$result .= $this->bmpHeader["biHeight"]        . " ";
		$result .= $this->bmpHeader["biPlanes"]        . " ";
		$result .= $this->bmpHeader["biBitCount"]      . " ";
		$result .= $this->bmpHeader["biCompression"]   . " ";
		$result .= $this->bmpHeader["biSizeImage"]     . " ";
		$result .= $this->bmpHeader["biXPelsPerMeter"] . " ";
		$result .= $this->bmpHeader["biYPelsPerMeter"] . " ";
		$result .= $this->bmpHeader["biClrUsed"]       . " ";
		$result .= $this->bmpHeader["biClrImportant"];

		$arrayHeader = split(" ", $result);
		$result = "";
		foreach ($arrayHeader as $charHeader){
			$result .= chr(hexdec($charHeader));
		}

		return $result;
	}


	/**
	 * Construct the BMP image content ready to be used.
	 *
	 * @return string
	 */
	function makeBmpContent(){
		if(!$this->success){
			return false;
		}
		
		$result = "";
		$imageArray = array();
		
		// insert left spaces
		$arrayLeft = array();
		if($this->spaceLeft > 0){
			$leftChars = "";
			for($i=0; $i<$this->spaceLeft; $i++){
				$leftChars .= "0";
			}
			$arrayLeft = array_fill_custom(0, $this->charHeight, $leftChars);
			$imageArray = $this->applyColors($arrayLeft, $this->bgColor);
		}
		
		// insert inner spaces
		$arrayInner = array();
		if($this->spaceInner > 0){
			$innerChars = "";
			for($i=0; $i<$this->spaceInner; $i++){
				$innerChars .= "0";
			}
			$arrayInner = array_fill_custom(0, $this->charHeight, $innerChars);
			$arrayInner = $this->applyColors($arrayInner, $this->bgColor);
		}
		
		// insert chars
		for($ia=0; $ia<strlen($this->imageChars); $ia++){
			$fgColor = $this->getRandColor();
			
			if($this->spaceInner > 0 && $ia > 0){
				for($j=0; $j<$this->charHeight; $j++){
					$imageArray[$j] .= $arrayInner[$j];
				}
			}
			
			if(count($imageArray) == 0){
				$imageArray = $this->applyColors($this->charsMap[$this->imageChars{$ia}], $fgColor);
			}else{
				for($ja=0; $ja<$this->charHeight; $ja++){
					$imageArray[$ja] .= $this->applyColors($this->charsMap[$this->imageChars{$ia}][$ja], $fgColor);
				}
			}
		}
		
		// insert right spaces
		$arrayRight = array();
		if($this->spaceRight > 0){
			$rightChars = "";
			for($i=0; $i<$this->spaceRight; $i++){
				$rightChars .= "0";
			}
			$arrayRight = array_fill_custom(0, $this->charHeight, $rightChars);
			$arrayRight = $this->applyColors($arrayRight, $this->bgColor);
			
			for($j=0; $j<$this->charHeight; $j++){
				$imageArray[$j] .= $arrayRight[$j];
			}
		}

		// insert top/bottom spaces
		$tbChars = "";
		for($i=0; $i<(strlen($imageArray[0])/3); $i++){
			$tbChars .= "0";
		}
		$arrayTop = array();
		if($this->spaceTop > 0){
			$arrayTop = array_fill_custom(0, $this->spaceTop, $tbChars);
			$arrayTop = $this->applyColors($arrayTop, $this->bgColor);
			$imageArray = array_merge($arrayTop,  $imageArray);
		}
		$arrayBottom = array();
		if($this->spaceBottom > 0){
			$arrayBottom = array_fill_custom(0, $this->spaceBottom, $tbChars);
			$arrayBottom = $this->applyColors($arrayBottom, $this->bgColor);
			$imageArray = array_merge($imageArray, $arrayBottom);
		}
		
		$imageArray = array_reverse($imageArray);

		// verify if image need correction
		$this->imageCorrection = (strlen($imageArray[0])) % 4;
		$this->imageCorrection == 0 ? $this->imageCorrection = 0 : $this->imageCorrection =4 - $this->imageCorrection;
		$endLine = "";
		for($i=0; $i<$this->imageCorrection; $i++){
			$endLine .= chr(0x00);
		}
		$result = implode($endLine, $imageArray);

		return $result . $endLine;
	}

	
	/**
	 * Code the image colors for the background ('0') and foreground ('1') pixels
	 *
	 * @param  array|string $charElement
	 * @param  string 		$fgColor
	 * @return array|string
	 */
	function applyColors($charElement, $fgColor){
		if(is_array($charElement)){
			$result = array();
			for($i=0; $i<count($charElement); $i++){
				$line = "";
				for($j=0; $j<strlen($charElement[$i]); $j++){
					if($charElement[$i]{$j} == "0"){
						$line .= chr(hexdec(substr($this->bgColor, 4, 2))) . chr(hexdec(substr($this->bgColor, 2, 2))) . chr(hexdec(substr($this->bgColor, 0, 2)));
					}else{
						$line .= chr(hexdec(substr($fgColor, 4, 2))) . chr(hexdec(substr($fgColor, 2, 2))) . chr(hexdec(substr($fgColor, 0, 2)));
					}
				}
				array_push($result, $line);
			}
			return $result;
		}else{
			$result = "";
			for($i=0; $i<strlen($charElement); $i++){
				if($charElement{$i} == "0"){
					$result .= chr(hexdec(substr($this->bgColor, 4, 2))) . chr(hexdec(substr($this->bgColor, 2, 2))) . chr(hexdec(substr($this->bgColor, 0, 2)));
				}else{
					$result .= chr(hexdec(substr($fgColor, 4, 2))) . chr(hexdec(substr($fgColor, 2, 2))) . chr(hexdec(substr($fgColor, 0, 2)));
				}
			}
			return $result;
		}
	}

	
	/**
	 * Get a random hex color code from foreground color list
	 *
	 * @return string|false
	 */
	function getRandColor(){
		if(count($this->fgColors) == 0){
			return false;
		}
		$colorPos = rand(0, (count($this->fgColors)-1));
		return $this->fgColors[$colorPos];
	}

	
	/**
	 * Create a random string based on the character set with desired length.
	 *
	 * @param  string $chars
	 * @param  int 	  $numberOfChars
	 * @return string
	 */
	function randomChars($chars, $numberOfChars){
		$result = "";
		for($i=0; $i<$numberOfChars; $i++){
			$pos = rand(0, (strlen($chars)-1));
			$result .= $chars{$pos};
		}
		return $result;
	}


	/**
	 * Calculate the image width, height, size and file size.
	 * Put together the header and image content to be ready for use.
	 *
	 */
	function createImage(){
		if(!$this->success){
			return false;
		}
		
		$imgWidth  = $this->spaceLeft + $this->spaceRight + (strlen($this->imageChars) * $this->charWidth) + ((strlen($this->imageChars) - 1) * $this->spaceInner);
		$imgHeight = $this->spaceTop + $this->spaceBottom + $this->charHeight;

		$this->setBmpWidth($imgWidth);
		$this->setBmpHeight($imgHeight);
		$this->setBmpSizeImage();
		$this->setBmpFileSize();

		$this->content .= $this->makeBmpHeader();
		$this->content .= $this->makeBmpContent();
	}
	
	
	/**
	 * Write the generated image to HTTP response
	 *
	 */
	function genarateSrc(){
		header("Content-type: image/bmp");
		echo $this->content;
	}


	/**
	 * Write the generated image to disk
	 *
	 * @param string $fileName	file path and name
	 */
	function writeImage($fileName=""){
		$handle = fopen($fileName, "w");
		fwrite($handle, $this->content);
		fclose($handle);
	}
}

if(!session_start()) session_start();

include_once("NOF_CaptchaProperties.class.php");
include_once("nof_utils.inc.php");



// get session data 
$SESSION_KEY = "nof_".$_GET['cid']."_CaptchSettings";

$props = unserialize(GetSessionVariable($SESSION_KEY));


// create image

$img = new  NOF_CaptchaBMP($props->charFontDir);

$img->imageChars =  $props->imageChars;	


$img->bgColor     = $props->bgColor;
$img->fgColors    =  $props->fgColors;

$img->spaceTop    = $props->spaceTop;
$img->spaceBottom = $props->spaceBottom;
$img->spaceLeft   = $props->spaceLeft;
$img->spaceRight  = $props->spaceRight;
$img->spaceInner  = $props->spaceInner;


$img->createImage();
$img->genarateSrc();

?>
