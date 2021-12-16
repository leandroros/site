<?php

$errors = false;
$cgiDir = $nof_rootDir ."/". $nof_scriptDir . "/";
$componentid = $nof_componentId;
$xmlPropertyFile   = $cgiDir . $nof_scriptInterfaceFile;
$compname = $nof_componentName;


if (NOF_fileExists($cgiDir . 'ss_xmlparser.php')) {
    require_once($cgiDir . 'ss_xmlparser.php');
} else {
    exit();
}

if (NOF_fileExists($cgiDir . 'ss_common.php')) {
    require_once($cgiDir . 'ss_common.php');
} else {
    exit();
}

// set error reporting
error_reporting(0);


/*
*global variables
*/
$conf="";



/*
* Make a path thats relative to some other html page, relative
* to admin page
*/
function makeRelativeToCurrentPageSA($path) {
  global $cgiDir;

  $installDir=preg_replace("/cgi\-bin\/$/" , "" , $cgiDir);
  $path=preg_replace("/^(\.\.\/){1,}/" , "" ,$path);
  $path=preg_replace("/^(\.\/)/" , "" ,$path);
  $path = $installDir . $path;

  return $path;
}





//continue the session
@session_start();

//read the XML property file
$conf = ss_parseXmlFile($xmlPropertyFile);

if(!isset($nof_componentId)) {
    $errorMessage = "Expected variable not found: componentid";
    NOF_throwError(800,array("{1}"=>"nof_componentId"));
    exit();
}

if(!isset($compname)) {
    $SPCompIdent = "securepage" . "."  . $nof_componentId . ".";
} else {
    $SPCompIdent = $compname . "."  . $nof_componentId . ".";
}


//if javascript sent something
if( GetGVariable('NextPage') != '' ) {
    //put the link of the current page in session
    SetSessionVariable( 'NextPage', GetGVariable('NextPage') );
    //NOTE:THIS WAS DISABLED WHILE UPDATING FOR XML!!!!
    //$conf[$SPCompIdent. "nextPage"]=makeRelativeToCurrentPageSA($conf[$SPCompIdent. "nextPage"]);
    //send user to login page
    echo "<html>";
    echo "<head></head>";
    echo "<body></body>";
    echo "<script>";
    echo "top.document.location.href='" . $conf[$SPCompIdent. "accessDeniedPage"] . "'";
    echo "</script>";
    echo "</html>";
    die();
}


//if the user did not logged on or user is not an admin
$objUserLoginInfo = new UserLoginInfo();
if ( GetSessionVariable("userinsession") != '' ) {
	$objUserLoginInfo = unserialize(GetSessionVariable("userinsession"));
    $session = true;
} else {
    $session = false;
}
if (!$session || !($objUserLoginInfo->getAdmin()) || ($objUserLoginInfo->getDbpath()!=$conf[$SPCompIdent . "dbPath"])) {
    //if user logged on but is not an admin
    if($session && ($objUserLoginInfo->getDbpath()==$conf[$SPCompIdent . "dbPath"])) {
        //redirect to login page, pass the error and also address of current page
        SetSessionVariable("AuthNeededError", stripslashes($conf[$SPCompIdent. "unauthorizedmessage"]));
        SetSessionVariable("NextPageType", "admin");
        //$_SESSION["NextPage"] =  $_SERVER['PHP_SELF'];
        //could not use this cause PHP_SELF gives link of the body and not of the top frame
        //$_SESSION['NextPage']      =  $_SERVER['PHP_SELF'] ;
        //instead we will send the link of the top frame from JS back to this page
        echo "<script type='text/javascript'>\n";
        echo "top.document.location.href='" . GetSessionVariable('PHP_SELF') . "?NextPage='" . " + top.document.location;" ;
        echo "</script>\n";

        die();
    } else {
        //redirect to login page, pass the error and also address of current page
        SetSessionVariable("AuthNeededError", stripslashes($conf[$SPCompIdent . "authenticationneededmessage"]));
        SetSessionVariable("NextPageType", "admin");
        //could not use this cause PHP_SELF gives link of the body and not of the top frame
        //$_SESSION['NextPage']      =  $_SERVER['PHP_SELF'] ;
        //instead we will send the link of the top frame from JS back to this page
        echo "<script type='text/javascript'>\n";
        echo "top.document.location.href='" . GetServerVariable('PHP_SELF') . "?NextPage='" . " + top.document.location;" ;
        echo "</script>\n";

        die();
    }
}

?>

