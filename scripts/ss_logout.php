<?php
error_reporting(0);

if ( !file_exists('nof_utils.inc.php') ) {
	echo "<p>An error occured. Please contact the site administrator</p>
		  <p>Error code: 103</p>";
}
require_once("nof_utils.inc.php");

$nof_rootDir = GetPostVariable('nof_rootDir');
$nof_debug = GetPostVariable('nof_debug');
$nof_componentId = GetPostVariable('nof_componentId');
$nof_scriptDir = GetPostVariable('nof_scriptDir');
$nof_scriptInterfaceFile = GetPostVariable('nof_scriptInterfaceFile');
$nof_suiteName = "SecureSite";
$nof_langFile = str_replace($nof_rootDir.'/'.$nof_scriptDir, "", GetPostVariable('nof_langFile'));
$nof_langFile = str_replace('/', "", $nof_langFile);

if ( !isset($nof_debug) ) $nof_debug = 'true';
if ( !file_exists($nof_langFile) ) {
    if($nof_debug == 'true') {
        echo "<p>DEBUGINFO: Some components were left unpublished. Please check your publish settings in Fusion and republish the site.</p>";
    } else {
        echo "<p>An error occured. Please contact the site administrator</p>
              <p>Error code: 103</p>";
    }
}

$nof_resources->addFile($nof_langFile);

$errors = false;

if (NOF_fileExists('ss_xmlparser.php')) {
    require_once('ss_xmlparser.php');
} else {
    exit();
}

/*
* global variables
*/
$conf="";
$LOCompIdent="";

$xmlPropertyFile=GetPostVariable('nof_scriptInterfaceFile');



//include file with common functions
//put this include always after the
//global variables
if (NOF_fileExists('ss_common.php')) {
    require_once('ss_common.php');
} else {
    exit();
}



global $logoutPropertiesFile,$conf,$LOCompIdent,$xmlPropertyFile;

//Destroy the session
@session_start();
//session_destroy();
UnsetSession("userinsession");

//read the XML property file
$conf = ss_parseXmlFile($xmlPropertyFile);

$LOCompIdent = "logout" . "."  . GetPostVariable('nof_componentId') . ".";

if ( GetPostVariable('nof_componentId')=='' ) {
    $errorMessage = "Expected POST param not passed: nof_componentId";
    NOF_throwError(800,array("{1}"=>"nof_componentId"));
    exit();
}

//make the path relative to the cgi-bin directory
$conf[$LOCompIdent . "nextPage"]=$ssCommon->makeRelativeToCgiBin($conf[$LOCompIdent . "nextPage"]);

//display the logout success page
//header("Location: " . $conf["[LOGOUT]label.nextPage"]);
echo "<html>";
echo "<head></head>";
echo "<body></body>";
echo "<script>";
echo "top.location.href='" . $conf[$LOCompIdent . "nextPage"] . "'" ;
echo "</script>";


?>
