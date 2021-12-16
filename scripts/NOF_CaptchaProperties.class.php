<?php
/**
 * @package    NOF_Framework
 * @subpackage NOF_Elements
 * 
 * @author Adrian Pascu <apascu@innovative.ro>
 *
 */

    class NOF_CaptchaProperties{
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
    	var $charFontDir = "zebra";



	/**
	     * Additional property to keep the chars from previous imageChars property
	     * @var string
	     */

	var $codeChars = "";


    }
?>