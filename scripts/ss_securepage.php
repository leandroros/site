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


/*
*global variables
*/
$conf="";



/*
* Make the path relative to some html page, relative to admin page
*/
function makeRelativeToCurrentPageSP($path) {
  global $cgiDir;
  $installDir=preg_replace("/cgi\-bin\/$/" , "" , $cgiDir);
  $path=preg_replace("/^(\.\.\/){1,}/" , "" ,$path);
  $path=preg_replace("/^(\.\/)/" , "" ,$path);
  $path = $installDir . $path;

  return $path;
}

//if (!isset($_SESSION)) {
    //continue session
    if (!isset($_SESSION)) session_start();
//}

//read the XML property file
$conf = ss_parseXmlFile($xmlPropertyFile);

if(!isset($componentid)) {
    $errorMessage = "Expected variable missing: componentid";
    NOF_throwError(800,array("{1}"=>"nof_componentId"));
    exit();
}

if(!isset($compname)) {
    $SPCompIdent = "securepage" . "."  . $componentid . ".";
} else {
    $SPCompIdent = $compname . "."  . $componentid . ".";
}


//if javascript sent something
if ( GetGVariable('NextPage') != '' ) {
    //put the link of the current page in session
    SetSessionVariable( 'NextPage', GetGVariable('NextPage') );
    //NOTE:THIS WAS DISABLED WHILE UPDATING XML!!!!
      //$conf[$SPCompIdent . "nextPage"]=makeRelativeToCurrentPageSP($conf[$SPCompIdent . "nextPage"]);
    //send user to login page
    echo "<html>";
    echo "<head></head>";
    echo "<body></body>";
    echo "<script>";
    echo "top.document.location.href='" . $conf[$SPCompIdent . "accessDeniedPage"] . "'";
    echo "</script>";
    echo "</html>";
    die();
}

$objUserLoginInfo = new UserLoginInfo();
$objUserLoginInfo = unserialize(GetSessionVariable("userinsession"));
if (is_object($objUserLoginInfo)) {
    $ses_dbpath = $objUserLoginInfo->getDbpath();
} else {
    $ses_dbpath = false;
}
//if the user never logged on
if ( GetSessionVariable("userinsession")=='' || ($ses_dbpath!=$conf[$SPCompIdent . "dbPath"])) {
    //redirect user to login page, pass the error and address of this page
    SetSessionVariable('AuthNeededError', stripslashes($conf[$SPCompIdent . "authenticationneededmessage"]));
    SetSessionVariable('NextPageType', "");
    //could not use this cause PHP_SELF gives link of the body and not of the top frame
    //SetSessionVariable( 'NextPage', GetServerVariable('PHP_SELF') );
    //instead we will send the link of the top frame from JS back to this page
    echo "<script type='text/javascript'>\n";
    echo "top.document.location.href='" . GetServerVariable('PHP_SELF') . "?NextPage='" . " + escape(top.document.location);" ;
    echo "</script>\n";
    die();
}

?>

