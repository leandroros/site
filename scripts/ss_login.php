<?php
@session_start();
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

if (!isset($nof_debug)) $nof_debug = 'true';
if ( !file_exists($nof_langFile) ) {
    if($nof_debug == 'true') {
        echo "<p>DEBUGINFO: Some components were left unpublished. Please check your publish settings in Fusion and republish the site.</p>";
    } else {
        echo "<p>An error occured. Please contact the site administrator</p>
              <p>Error code: 103</p>";
    }
}


$nof_resources->addFile($nof_langFile);

if (NOF_fileExists('ss_xmlparser.php')) {
	require_once('ss_xmlparser.php');
} else {
	exit();
}

$xmlPropertyFile=GetPostVariable('nof_scriptInterfaceFile');

$conf="";
$errorLabel="";
$errorField="";
$adminFlag=FALSE;
$fieldsDBArray="";
$firstLineUD="";
$LICompIdent="";
$AMCompIdent="";
$SNCompIdent="";

if (NOF_fileExists('ss_common.php')) {
	require_once('ss_common.php');
} else {
	exit();
}

SetSessionVariable('AuthNeededError',"");

//read the XML property file
$conf = ss_parseXmlFile($xmlPropertyFile);

if ( GetPostVariable('nof_componentId') == '' ) {
	$errorMessage = "Expected POST param not passed: nof_componentId";
	NOF_throwError(800,array("{1}"=>"nof_componentId"));
	exit();
}

$LICompIdent = "login" . "."  . GetPostVariable('nof_componentId') . ".";

$adminID = getAdminID($conf[$LICompIdent . "dbPath"]);
$AMCompIdent = "admin." . $adminID . ".";

$SNID = getSignupID($conf[$LICompIdent . "dbPath"]);
if($SNID == -1) {
	$errorMessage = "Signup's ID with the same DB path could not be found.";
	NOF_throwError(602,array("{1}"=>$nof_suiteName,"{2}"=>$conf[$LICompIdent . "dbPath"]));
	exit();
}
$SNCompIdent = "signup." . $SNID . ".";


if (!file_exists(dirname($conf[$LICompIdent ."dbPath"]))) {
    NOF_throwError(540,array("{1}"=>NOF_mapPath(dirname($conf[$LICompIdent ."dbPath"])),"{2}"=>getcwd()));
}

//if (!is_writable(dirname($conf[$LICompIdent ."dbPath"]))) {
//    NOF_throwError(541,array("{1}"=>NOF_mapPath(dirname($conf[$LICompIdent ."dbPath"])),"{2}"=>getcwd()));
//}

//get the top line of the db file and get the field names
$ssCommon->getExpectedDBFields($SNCompIdent);


//check if username and password were filled
if(checkIfUserPasswdUnFilled()) {
	displayErrorPage($errorLabel,$errorField);
	exit();
}

//check if DB needs to be created/overwritten
if (!$ssCommon->checkIfDBmatch($LICompIdent)) {
	//open DB file for writing
	if (!$FILE = fopen($conf[$LICompIdent ."dbPath"], 'wb')) {
		$sysErr = "Cannot create/write to database file.<br>Check path/permissions of database file/directory.";
		NOF_throwError(502,array("{1}"=>NOF_mapPath($conf[$LICompIdent . "dbPath"]),"{2}"=>dirname($conf[$LICompIdent . "dbPath"])));
		exit;
	} else {
		//write first line of UD file, to the DB file
		if (!fputs($FILE, "$firstLineUD\n")) {
			$sysErr = "Cannot write to database file.<br>Check path/permissions of database file/directory.";
			NOF_throwError(502,array("{1}"=>NOF_mapPath($conf[$LICompIdent . "dbPath"]),"{2}"=>dirname($conf[$LICompIdent . "dbPath"])));
			exit;
		}
		fclose($FILE);
	}
}

//check for username and password match in the database
if(checkIfUserPasswdInvalid()) {
	displayErrorPage($errorLabel,$errorField);
	exit();
}
//if no errors occured then start the session
//and display the next page
else {
	//session_start();
	$userObj = new UserLoginInfo();
	$userObj->setUsername(stripslashes(GetPostVariable("username")));
	$userObj->setPassword(stripslashes(GetPostVariable("password")));
	$userObj->setDbpath($conf[$LICompIdent ."dbPath"]);
	$userObj->setAdmin(FALSE);
	//if user is an admin, record it in the session
	if($adminFlag) {
		$userObj->setAdmin(TRUE);
	}

	SetSessionVariable("userinsession", serialize($userObj));


	//if page user was trying to go to is set in the session
	if(GetSessionVariable('NextPage') != '' ) {
		if($adminFlag && GetSessionVariable("NextPageType") == "admin") {
			echo "<html>";
			echo "<head></head>";
			echo "<body></body>";
			echo "<script>";
			echo "top.document.location.href=unescape('" .   GetSessionVariable("NextPage") . "');" ;
			echo "</script>";
			echo "</html>";

			//header("Location: " .  GetSessionVariable("NextPage"));
		}
		elseif($adminFlag && GetSessionVariable("NextPageType") != "admin") {
			echo "<html>";
			echo "<head></head>";
			echo "<body></body>";
			echo "<script>";
			echo "top.document.location.href=unescape('" .   GetSessionVariable("NextPage") . "');" ;
			echo "</script>";
			echo "</html>";

			//header("Location: " .  GetSessionVariable("NextPage"));
		}

		elseif (!$adminFlag &&  GetSessionVariable("NextPageType") == "admin") {
			echo "<html>";
			echo "<head></head>";
			echo "<body></body>";
			echo "<script>";
			$conf[$LICompIdent . "nextPage"]=$ssCommon->makeRelativeToCgiBin($conf[$LICompIdent . "nextPage"]);
			echo "top.document.location.href=unescape('" .  $conf[$LICompIdent . "nextPage"] . "');" ;
			echo "</script>";
			echo "</html>";


		}
		elseif (!$adminFlag &&  GetSessionVariable("NextPageType") != "admin") {

			echo "<html>";
			echo "<head></head>";
			echo "<body></body>";
			echo "<script>";
			echo "top.document.location.href=unescape('" .   GetSessionVariable("NextPage") . "');" ;
			echo "</script>";
			echo "</html>";


			//header("Location: " .  GetSessionVariable("NextPage"));


		}

	}
	else {
		echo "<html>";
		echo "<head></head>";
		echo "<body></body>";
		echo "<script>";
		$conf[$LICompIdent . "nextPage"]=$ssCommon->makeRelativeToCgiBin($conf[$LICompIdent . "nextPage"]);
		echo "top.document.location.href='" .  $conf[$LICompIdent . "nextPage"] . "';" ;
		echo "</script>";
		echo "</html>";

	}

	SetSessionVariable('NextPage', "");
	SetSessionVariable('NextPageType', "");
	SetSessionVariable('AuthNeededError', "");


}





/*
* function to call the error script so that it can display
* the error page
*/
function displayErrorPage($error_label,$error_field) {

	global $conf,$LICompIdent,$AMCompIdent,$ssCommon;

	$params = "username" . "," . "password";
	$values = GetPostVariable("username") . ",;,;" . GetPostVariable("password");


	//get the error labels and associated fields in arrays
	$errorLabel = explode(",", $error_label);
	$errorField = explode(",", $error_field);

    $id = str_replace("login.", "", $LICompIdent);
    $id = str_replace(".", "", $id);
	echo "<HTML>";
	echo "<HEAD></HEAD>";
	echo "<BODY>";

	echo "<FORM NAME='INV' METHOD='POST' ACTION='" . GetServerVariable('HTTP_REFERER') . "'  target='_self'>" ;
	//for each error
	$errorMessages = '';
	for($i=0;$i< count($errorLabel);$i++) {
		$errorLabelMinusMessage = preg_replace("/\.message$/i","",$errorLabel[$i]);
		$errorMessages =  $errorMessages . "<LI><SPAN CLASS='" . $conf[$errorLabelMinusMessage . ".errorcss"] . "'>"
		. $ssCommon->cleanField($conf[$errorLabel[$i]])
		. "</SPAN>"
		. "</LI>";

	}
	echo "<INPUT TYPE='HIDDEN' NAME='"
	. $id . "_errormessgs"
	. "' VALUE=\""
	. '<UL>'.$errorMessages.'</UL>'
	. "\">" ;

	for($i=0;$i< count($errorField);$i++) {
		echo "<INPUT TYPE='HIDDEN' NAME='"
		. $id . "_" . $errorField[$i] . "_errorimg"
		. "' VALUE=\""
		. "<IMG SRC='"
		. $conf[$LICompIdent . "errorMark"]
		. "' alt=''>"
		. "\">";
	}



	echo "<INPUT TYPE='HIDDEN' NAME='"
	. 'PARAMS'
	. "' VALUE='"
	. encodeData($params)
	. "'>" ;


	echo "<INPUT TYPE='HIDDEN' NAME='"
	. 'VALUES'
	. "' VALUE=\""
	. encodeData($values)
	. "\">" ;

	echo "<INPUT TYPE='HIDDEN' NAME='"
	. 'FORMNAME'
	. "' VALUE='"
    . GetPostVariable('nof_formName')
	. "'>" ;


	echo "</FORM>";
	echo "</BODY>";
	echo "<script type='text/javascript'>";
	echo "document.forms[0].submit();";
	echo "</SCRIPT>";
	echo "</HTML>";
}





/*
* function to check to see if user name and password were filled
*/
function checkIfUserPasswdUnfilled() {
	global $LICompIdent,$ssCommon;

	$errorFlag=FALSE;

	if(preg_match("/^\s*$/",GetPostVariable("username"))) {
		$ssCommon->clubError($LICompIdent . "username.errorevent.required.message","username");
		$errorFlag=TRUE;
	}

	if(preg_match("/^\s*$/",GetPostVariable("password"))) {
		$ssCommon->clubError($LICompIdent . "password.errorevent.required.message","password");
		$errorFlag=TRUE;
	}

	return $errorFlag;
}





/*
* function to see if user name and password were valid
*/
function checkIfUserPasswdInvalid() {

	global $conf,$adminFlag,$fieldsDBArray,$LICompIdent,$AMCompIdent,$ssCommon;

	$userFoundFlag=FALSE;
	$passwordMatchFlag=FALSE;
	$accountEnabledFlag=FALSE;

	$userColPos = $ssCommon->getColumnPos("username");
	$passwordColPos = $ssCommon->getColumnPos("password");
	$validationFlagColPos = count($fieldsDBArray)-1;
	$adminStatColPos = count($fieldsDBArray)-2;


	//check the admin property file to see if this guy is the admin
	if(strtolower(stripslashes(GetPostVariable("username")))==strtolower($conf[$AMCompIdent . "adminuser.username"])) {
		if(stripslashes(GetPostVariable("password"))==$conf[$AMCompIdent . "adminuser.password"]) {
			$adminFlag=TRUE;
			return FALSE;
		}
		else {
			$ssCommon->clubError($LICompIdent . "password.errorevent.invalidpassword.message","password");
			return TRUE;
		}
	}

	//get the db in an array
	if(!$lines = @file($conf[$LICompIdent . "dbPath"])) {
		$sysErr="File \"" . $conf[$LICompIdent . "dbPath"] . "\" could not be read.<br>" .
		"The path is either invalid or right permissions<br>" .
		"have not been set on the file.";
		NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$LICompIdent . "dbPath"]),"{2}"=>dirname($conf[$LICompIdent . "dbPath"])));
		exit;
	}

	//check the db for username and password
	for( $i=1; $i<count($lines); $i++ ) {
		$username  = $ssCommon->getField($userColPos,$lines[$i]);
		$password  = $ssCommon->getField($passwordColPos,$lines[$i]);
		$validationFlag = $ssCommon->getField($validationFlagColPos,$lines[$i]);
		$adminStat   = $ssCommon->getField($adminStatColPos,$lines[$i]);
		SetPostVariable( "username", trim(GetPostVariable("username")) );
		SetPostVariable( "password", trim(GetPostVariable("password")) );
		if ( strtolower($username) == strtolower(stripslashes(GetPostVariable("username"))) ) {
			$userFoundFlag=TRUE;
			if ( $password == stripslashes(GetPostVariable("password")) ) {
				$passwordMatchFlag=TRUE;
				if ( $validationFlag=="true" ) {
					$accountEnabledFlag=TRUE;
					if( $adminStat=="true" ) {
						$adminFlag=TRUE;
					}
				}
			}
			break;
		}
	}
	//take necessary action based on results of search
	if($userFoundFlag && $passwordMatchFlag && $accountEnabledFlag) {
		return FALSE;
	}
	if (!$userFoundFlag) {
		$ssCommon->clubError($LICompIdent . "username.errorevent.invalidusername.message","username");
		return TRUE;
	}
	if ($userFoundFlag && !$passwordMatchFlag) {
		$ssCommon->clubError($LICompIdent . "password.errorevent.invalidpassword.message","password");
		return TRUE;
	}
	if ($userFoundFlag && $passwordMatchFlag && !$accountEnabledFlag) {
		$ssCommon->clubError($LICompIdent . "username.errorevent.accountdisabled.message","username");
		return TRUE;
	}
}





function getAdminID($loginDBPath) {
	global $conf;

	reset($conf);

	while (list($key,$value) = each($conf)) {
        if(preg_match("/^admin\.(\d+)\.dbPath$/",$key,$match)) {
            if($value==$loginDBPath) {
                $id = $match[1];
            }
        }
    }
    if (isset($id)) {
        return $id;
    } else {
        return -1;
    }

}





function getSignupID($loginDBPath) {
    global $conf;

    reset($conf);

    while (list($key,$value) = each($conf)) {
        if(preg_match("/^signup\.(\d+)\.dbPath$/",$key,$match)) {
            if($value==$loginDBPath) {
                $id = $match[1];
            }
        }
    }
    if (isset($id)) {
        return $id;
    } else {
        return -1;
    }



}




?>
