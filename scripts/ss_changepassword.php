<?php
error_reporting(0);
@session_start();

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
$cgiDir = "";
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

$xmlPropertyFile=GetPostVariable('nof_scriptInterfaceFile');


$conf="";
$errorLabel="";
$errorField="";
$fieldsDBArray="";
$sendEmailAddy="";
$CPCompIdent="";
$AMCompIdent="";
$SNCompIdent="";


$CPCompIdent = "changepassword" . "."  . GetPostVariable('nof_componentId') . ".";


if (NOF_fileExists('ss_common.php')) {
    require_once('ss_common.php');
} else {
    exit();
}

//read the XML property file
$conf = ss_parseXmlFile($xmlPropertyFile);

$emailPropertiesFile='ss_ChangePasswordTemplate_'.$conf[$CPCompIdent.'language'].'.properties';

$ssCommon->readEmailTemplate($emailPropertiesFile,"[EMAIL]");

if( GetPostVariable('nof_componentId')=='' ) {
    $errorMessage = "Expected POST param not passed: nof_componentId";
    NOF_throwError(800,array("{1}"=>"nof_componentId"));
    exit();
}

$adminID = getAdminID($conf[$CPCompIdent . "dbPath"]);
$AMCompIdent = "admin." . $adminID . ".";


$SNID = getSignupID($conf[$CPCompIdent . "dbPath"]);
if($SNID == -1) {
    $errorMessage = "Signup's ID with the same DB path could not be found.";
    NOF_throwError(602,array("{1}"=>$nof_suiteName,"{2}"=>$conf[$CPCompIdent . "dbPath"]));
    exit();
}

$SNCompIdent = "signup." . $SNID . ".";

//if the required parameters were unfilled
if ( checkIfRequiredUnfilled() ) {
    displayErrorPage($errorLabel,$errorField);
    exit();
}

$errorFlag = false;
//check if field lengths are valid
if( checkIfFieldsLengthInvalid() ) {
    $errorFlag=TRUE;
}

//if new password and retyped new password do not match
if ( checkIfPasswordsInvalid() ) {
    $errorFlag=TRUE;
}

//on error
if( $errorFlag ) {
    displayErrorPage($errorLabel,$errorField);
    exit();
} else {
    //if there was no error
    //get the expected column names of the db
    $ssCommon->getExpectedDBFields($SNCompIdent);
    //check for username and oldpassword in database and
    if(checkIfUserPasswdInvalid()) {
        //if oldpassword did not match one in DB
        displayErrorPage($errorLabel,$errorField);
        exit();
    } else {
        //if password replacement was success
        $objUserLoginInfo = new UserLoginInfo();
        $objUserLoginInfo = unserialize(GetSessionVariable("userinsession"));
        $objUserLoginInfo->setPassword($ssCommon->cleanField($ssCommon->preprocess(GetPostVariable("newPassword"))));
        if($conf["[EMAIL]sendEmail"]=="true") {
            if(!sendAccountEmail()) {
                $sysErr="Error while sending confirmation email to your registered<br>" .
                        "email account. Please check the SMTP address in the php.ini file.<br>" ;
                NOF_throwError(201,array("{1}"=>$sendEmailAddy,"{2}"=>$conf[$CPCompIdent."emailFromAddress"],"{3}"=>$conf[$CPCompIdent."emailServer"].":".$conf[$CPCompIdent."emailServerPort"]));
                exit;
            }
        }
        displaySuccessPage();
        exit();
    }
}


/*
* function to see if all required parameters were filled
*/
function checkIfRequiredUnfilled() {

  global $conf,$CPCompIdent,$ssCommon;

    $errorFoundFlag=FALSE;
    $postArr = GetPostVariable('');
    reset($postArr);

    while (list($field, $value) = each ($postArr)) {
      $property = $CPCompIdent . $field . ".errorevent.required.active";
                //if field was empty and required property for field was set in property file
      if(  preg_match("/^\s*$/",$postArr[$field])
        && $conf[$property]=="true" ) {
           $label= $CPCompIdent . $field . ".errorevent.required.message";
           $ssCommon->clubError($label,$field);
           $errorFoundFlag=TRUE;
      }
    }

    return $errorFoundFlag;
}





/*
* display error page
*/
function displayErrorPage($error_label,$error_field) {

	global $conf,$CPCompIdent,$AMCompIdent,$ssCommon;

    $params =   "oldPassword" . "," . "newPassword" . "," . "retypeNewPassword";
    $values =   $ssCommon->cleanField(GetPostVariable("oldPassword")) . ",;,;" .
		$ssCommon->cleanField(GetPostVariable("newPassword")) . ",;,;" .
		$ssCommon->cleanField(GetPostVariable("retypeNewPassword"));
	//get the error labels and associated fields in arrays
	$errorLabel = explode(",",$error_label);
	$errorField = explode(",",$error_field);
	$id = str_replace("changepassword.", "", $CPCompIdent);
	$id = str_replace(".", "", $id);
	echo "<HTML>";
	echo "<HEAD></HEAD>";
	echo "<BODY>";

	echo "<FORM NAME='INV' METHOD='POST' ACTION='" . GetServerVariable('HTTP_REFERER') . "'  target='_self'>" ;
	//for each error
	for($i=0;$i< count($errorLabel);$i++) {
          $errorLabelMinusMessage = preg_replace("/\.message$/i","",$errorLabel[$i]);
                $errorMessages =  $errorMessages . "<LI><SPAN CLASS='" . $conf[$errorLabelMinusMessage . ".errorcss"] . "'>"
                        . $ssCommon->cleanField($conf[$errorLabel[$i]])
                        . "</SPAN>"
                        . "</LI>";

        }
        echo "<INPUT TYPE='HIDDEN' NAME='"
                        . $id . "_errormessgs"
                        . "' VALUE=\" <ul>"
                        . $errorMessages
                        . "</ul>\">" ;
        for($i=0;$i< count($errorField);$i++) {
                echo "<INPUT TYPE='HIDDEN' NAME='"
                        . $id . "_" . $errorField[$i] . "_errorimg"
                        . "' VALUE=\""
                        . "<IMG SRC='"
                        . $conf[$CPCompIdent . "errorMark"]
                        . "' alt=''>"
                        . "\">";
        }



	echo "<INPUT TYPE='HIDDEN' NAME='"
					. 'PARAMS'
					. "' VALUE='"
					. $params
					. "'>" ;

	echo "<INPUT TYPE='HIDDEN' NAME='"
					. 'VALUES'
					. "' VALUE=\""
					. $values
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
* function to check if http params passed were within their min-max
* limitations specified in the property file
*/
function checkIfFieldsLengthInvalid() {
	global $conf,$CPCompIdent,$ssCommon;
	//set the error found fla to false
	$errorFoundFlag=FALSE;
	//reset the post array
	$postArr = GetPostVariable('');
	reset($postArr);
	//go throuh the posted entities
	while( list($field,$value)= each($postArr) ) {

		$shortErrorEventActiveProperty = $CPCompIdent . $field . ".errorevent.short.active";
		$minimumLengthProperty = $CPCompIdent . $field . ".errorevent.short.minimumlength";

		//if the field is specified as required in the property file
		if ( isset($conf[$shortErrorEventActiveProperty]) ) {
		   if ( GetPostVariable($field)!=''
			 && $conf[$shortErrorEventActiveProperty] == "true"
			 && preg_match("/^[-+]{0,1}\d+$/" ,$conf[$minimumLengthProperty])
			 && strlen(GetPostVariable($field)) < $conf[$minimumLengthProperty] ) {
			 //the label associated with the error messages
			 $label= $CPCompIdent . $field . ".errorevent.short.message" ;
			 //club the error label and error field
			 $ssCommon->clubError($label,$field);
			 //set the error found flag to true
			 $errorFoundFlag=TRUE;

		   }
		}

		$longErrorEventActiveProperty = $CPCompIdent . $field . ".errorevent.long.active";
		$maximumLengthProperty = $CPCompIdent . $field . ".errorevent.long.maximumlength";

		if ( isset($conf[$longErrorEventActiveProperty]) ) {
			if ( GetPostVariable($field)!=''
			 && $conf[$longErrorEventActiveProperty] == "true"
			 && preg_match("/^\+{0,1}\d+$/" ,$conf[$maximumLengthProperty] )
			 && strlen(GetPostVariable($field)) > $conf[$maximumLengthProperty] ) {
			 //the label associated with the error messages
			 $label= $CPCompIdent . $field . ".errorevent.long.message" ;
			 //club the error label and error field
			 $ssCommon->clubError($label,$field);
			 //set the error found flag to true
			 $errorFoundFlag=TRUE;
			}
		}
	}

	return $errorFoundFlag;
}






/*
* function to check if password and retyped password are same
*/
function checkIfPasswordsInvalid() {

  global $conf,$CPCompIdent,$ssCommon;

  if(GetPostVariable("newPassword")!=GetPostVariable("retypeNewPassword")) {
       $ssCommon->clubError($CPCompIdent . "retypeNewPassword.errorevent.notmatch.message" ,"retypeNewPassword");
       return TRUE;
    }


  return FALSE;
}




/*
* display the success page after a successful signup
*/
function displaySuccessPage() {

  global $conf,$CPCompIdent,$ssCommon;

    $conf[$CPCompIdent . "nextPage"]=$ssCommon->makeRelativeToCgiBin($conf[$CPCompIdent . "nextPage"]);
    //header ("Location:" . $conf["[CHANGEPASSWORD]label.nextPage"]);
        echo "<html>";
        echo "<head></head>";
        echo "<body></body>";
        echo "<script>";
        echo "top.document.location.href='" .  $conf[$CPCompIdent . "nextPage"] . "';" ;
        echo "</script>";
        echo "</html>";


}





/*
* function to see if user name and oldpassword are found in DB
* if found the oldpassword will be replaced by newpassword
* else an error will be clubbed
*/
function checkIfUserPasswdInvalid() {
    global $conf,$adminFlag,$sendEmailAddy,$CPCompIdent,$AMCompIdent,$ssCommon;

    //get column positions
    $userColPos = $ssCommon->getColumnPos("username");
    $passwordColPos = $ssCommon->getColumnPos("password");

    //get the DB in an array
    if(!$lines = @file($conf[$CPCompIdent . "dbPath"])) {
        $sysErr="File \"" . $conf[$CPCompIdent . "dbPath"] . "\" could not be read.<br>" .
                "The path is either invalid or right permissions<br>" .
                "have not been set on the file.";
        NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$CPCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
        exit;
    }

    $authFlag=FALSE;
    SetPostVariable("oldPassword", trim(GetPostVariable("oldPassword")));

    //look in db for username from session and oldpassword
    for( $i=1; $i<count($lines); $i++ ) {
        $username = $ssCommon->getField($userColPos,$lines[$i]);
        $password = $ssCommon->getField($passwordColPos,$lines[$i]);
        $objUserLoginInfo = new UserLoginInfo();
        $objUserLoginInfo = unserialize(GetSessionVariable("userinsession"));

        if ( (strtolower($username)== strtolower($objUserLoginInfo->getUsername()) ) && ($password==stripslashes(GetPostVariable("oldPassword")) )  ) {
            $authFlag=TRUE;
            $sendEmailAddy=$ssCommon->getField($ssCommon->getColumnPos("email"),$lines[$i]);
            $userDetailsArray = split( "\"\,\"", $lines[$i] );
            for($j=0;$j<count($userDetailsArray);$j++) {
                if($j==$passwordColPos) {
                    $userDetailsArray[$j]=GetPostVariable("newPassword");
                }
                if(!isset($newEntry)) {
                    $newEntry = $ssCommon->preprocess($ssCommon->cleanField($userDetailsArray[$j]));
                } else {
                    $newEntry = $newEntry . "," . $ssCommon->preprocess($ssCommon->cleanField($userDetailsArray[$j]));
                }
            }
            $lines[$i]=$newEntry ."\n";
            break;
        }
    }

        //if username and old password were found, write into the DB
    if($authFlag) {
        if (!$FILE = @fopen($conf[$CPCompIdent . "dbPath"], 'wb')) {
            $sysErr = "Cannot open database file for writing.<br>" .
                      "Check path/permissions of database file/directory.";
            NOF_throwError(502,array("{1}"=>NOF_mapPath($conf[$CPCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
            exit;
        } else {
            for($i=0;$i<count($lines);$i++) {
                if (!fputs($FILE, $lines[$i]) ){
                    $sysErr = "Cannot write to database file.<br>" .
                              "Check path/permissions of database file/directory.";
                    NOF_throwError(502,array("{1}"=>NOF_mapPath($conf[$CPCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
                    exit;
                }
            }
        }
        return FALSE;
    } else {
        //if username and password were not found
       $ssCommon->clubError($CPCompIdent . "oldPassword.errorevent.invalidpassword.message","oldPassword");
       return TRUE;
    }
}





/*
* send user name and password by email
*/
function sendAccountEmail() {

    global $conf,$sendEmailAddy,$CPCompIdent,$ssCommon;

    //get email template in a single line
    $tplline = $conf["[EMAIL]Body"];
    $objUserLoginInfo = new UserLoginInfo();
    $objUserLoginInfo = unserialize(GetSessionVariable("userinsession"));

    $tplline = preg_replace("/\{0\}/" , $ssCommon->cleanField($ssCommon->preprocess($objUserLoginInfo->getUsername())), $tplline);
    $tplline = preg_replace("/\{1\}/" , $ssCommon->cleanField($ssCommon->preprocess(GetPostVariable("newPassword"))), $tplline);
    $tplline = str_replace("\\n", "<br>", $tplline);
    $tplline = str_replace("\\", "", $tplline);
/*
    if (NOF_fileExists('ss_mailer.php')) {
        include_once("ss_mailer.php");
    } else {
        exit();
    }
*/
    $mail = new PHPMailer();
    $mail->From = $conf[$CPCompIdent."emailFromAddress"];
    $mail->FromName = $conf[$CPCompIdent."emailFromAddress"];
    $mail->Host = $conf[$CPCompIdent."emailServer"];
    $mail->Port = $conf[$CPCompIdent."emailServerPort"];
    $mail->SMTPDebug = false;
    $mail->Mailer = "smtp";
    $mail->Subject = $conf["[EMAIL]Subject"];
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth = false;
    if ( $conf[$CPCompIdent."smtpAuth"] == "true" ) {
        $mail->SMTPAuth = true;
        $mail->Username = $conf[$CPCompIdent."smtpUsername"];
        $mail->Password = $conf[$CPCompIdent."smtpPassword"];
    }
    $mail->SMTPSecure = (isset($conf[$CPCompIdent."smtpSSL"])&&$conf[$CPCompIdent."smtpSSL"]=="true")?'ssl':'';
    $mail->IsHTML = true;
    $mail->AddAddress($sendEmailAddy,$sendEmailAddy);
    $mail->Body = $tplline;
    $mail->AltBody = str_replace('<br>','\n',$tplline);

    // send e-mail
    if (!$mail->Send()) {
        echo "<!-- ErrorInfo(smtp): ".$mail->ErrorInfo."-->";
        if ( $mail->SMTPAuth ) {
            return false;
        } else {
            $mail->Mailer = "mail";
            if (!$mail->Send()) {
                echo "<!-- ErrorInfo(mail): ".$mail->ErrorInfo."-->";
                return false;
            }
        }
    }

    // done! clean up
    $mail->ClearAddresses();
    $mail->ClearAttachments();
    return true;
}

function getAdminID($CPDBPath) {
    global $conf;
    reset($conf);
    while (list($key,$value) = each($conf)) {
        if(preg_match("/^admin\.(\d+)\.dbPath$/",$key,$match)) {
            if($value==$CPDBPath) {
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

function getSignupID($CPDBPath) {
    global $conf;
    reset($conf);
    while (list($key,$value) = each($conf)) {
        if(preg_match("/^signup\.(\d+)\.dbPath$/",$key,$match)) {
            if($value==$CPDBPath) {
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
