<?php if ( session_id() == "" ) session_start(); ?><?php
error_reporting(0);

if ( !file_exists('nof_utils.inc.php') ) {
	echo "<p>An error occured. Please contact the site administrator</p>
		  <p>Error code: 103</p>";
}
require_once("nof_utils.inc.php");

if ( !file_exists('NOF_CaptchaProperties.class.php') ) {
	echo "<p>An error occured. Please contact the site administrator</p>
		  <p>Error code: 103</p>";
}
require_once("NOF_CaptchaProperties.class.php");

$nof_rootDir = GetPostVariable('nof_rootDir');
$nof_debug = GetPostVariable('nof_debug');
$nof_componentId = GetPostVariable('nof_componentId');
$nof_scriptDir = GetPostVariable('nof_scriptDir');
$nof_scriptInterfaceFile = GetPostVariable('nof_scriptInterfaceFile');
$cgiDir = "";
$nof_suiteName = "SecureSite";
$nof_langFile = str_replace($nof_rootDir.'/'.$nof_scriptDir, "", GetPostVariable('nof_langFile'));
$nof_langFile = str_replace('/', "", $nof_langFile);

if (!isset($nof_debug)) $nof_debug = 'true';
if (!file_exists($nof_langFile) || !file_exists('nof_utils.inc.php')) {
    if($nof_debug == 'true') {
        echo "<p>DEBUGINFO: Some components were left unpublished. Please check your publish settings in Fusion and republish the site.</p>";
    } else {
        echo "<p>An error occured. Please contact the site administrator</p>
              <p>Error code: 103</p>";
    }
}

$nof_resources->addFile($nof_langFile);

$errors = false;

$xmlPropertyFile=GetPostVariable('nof_scriptInterfaceFile');

$conf = "";
$errorField = "";
$errorLabel = "";
$fieldsDBArray = "";
$firstLineUD = "";
$SNCompIdent = "";
$AMCompIdent = "";



//include file with common functions
//put this include always after the
//global variables
if (NOF_fileExists('ss_common.php')) {
    require_once('ss_common.php');
} else {
    exit();
}

if (NOF_fileExists('ss_xmlparser.php')) {
    require_once('ss_xmlparser.php');
} else {
    exit();
}

//start session
//session_start();

$SNCompIdent = "signup" . "."  . GetPostVariable('nof_componentId') . ".";

//read the XML property file
$conf  =  ss_parseXmlFile($xmlPropertyFile);

$errorFlag = FALSE;
$emailPropertiesFile = 'ss_SignupTemplate_'.$conf[$SNCompIdent.'language'].'.properties';
$ssCommon->readEmailTemplate($emailPropertiesFile,"[EMAIL]");

if( GetPostVariable('nof_componentId') == '' ) {
    $errorMessage = "Expected POST param not passed: nof_componentId";
    NOF_throwError(800,array("{1}"=>"nof_componentId"));
    exit();
}

$adminID = getAdminID($conf[$SNCompIdent . "dbPath"]);
$AMCompIdent = "admin." . $adminID . ".";

if ($conf[$SNCompIdent . "dbPath"] != "") {
    $ssCommon->getExpectedDBFields($SNCompIdent);
}

if ( checkIfRequiredUnfilled() ) {
    displayErrorPage($errorLabel,$errorField);
    exit();
}

if (!file_exists(dirname($conf[$SNCompIdent . "dbPath"]))) {
    NOF_throwError(540,array("{1}"=>NOF_mapPath(dirname($conf[$SNCompIdent . "dbPath"])),"{2}"=>getcwd()));
}

//if (!is_writable(dirname($conf[$SNCompIdent . "dbPath"]))) {
//    NOF_throwError(541,array("{1}"=>NOF_mapPath(dirname($conf[$SNCompIdent . "dbPath"])),"{2}"=>getcwd()));
//}

if ($conf[$SNCompIdent . "dbPath"] != "") {
    if (!$ssCommon->checkIfDBmatch($SNCompIdent)) {
        if (!$FILE = @fopen($conf[$SNCompIdent . "dbPath"], 'wb')) {
            $sysErr = "Cannot create/write to database file <b>".$conf[$SNCompIdent . "dbPath"]."</b><br>Check path/permissions of database file/directory.";
            NOF_throwError(500,array("{1}"=>NOF_mapPath($conf[$SNCompIdent . "dbPath"]),"{2}"=>dirname($conf[$SNCompIdent . "dbPath"])));
            exit;
        } else {
            //write first line of UD file, to the DB file
            if (!@fputs($FILE, "$firstLineUD\n")) {
                $sysErr = "Cannot write to database file.<br>Check path/permissions of database file/directory.";
                NOF_throwError(502,array("{1}"=>NOF_mapPath($conf[$SNCompIdent . "dbPath"]),"{2}"=>dirname($conf[$SNCompIdent . "dbPath"])));
                exit;
            }
            fclose($FILE);
        }
    }
    //if the username already exists
    if ( checkIfLoginAlreadyExists() ) {
        $errorFlag = TRUE;
    }
}

//if password and retype password match
if ( checkIfPasswordsInvalid() ) {
    $errorFlag = TRUE;
}
//if fields are not within their length limitations
if( checkIfFieldsLengthInvalid() ) {
    $errorFlag = TRUE;
}
//if email is invalid
if ( $ssCommon->checkIfEmailInValid(GetPostVariable('email'),$SNCompIdent) ) {
    $errorFlag = TRUE;
}

//if there was error
if( $errorFlag ) {
    displayErrorPage($errorLabel,$errorField);
    exit();
} else {
//if no errors were encountered in user input
    if (createAccount()) {
        if($conf["[EMAIL]sendEmail"]=="true") {
            if(!sendAccountEmail()) {
                $sysErr = "Error while sending confirmation email to your registered email account. Please check the SMTP address in the php.ini file.<br>" ;
                NOF_throwError(201,array("{1}"=>GetPostVariable('email'),"{2}"=>$conf[$SNCompIdent."emailFromAddress"],"{3}"=>$conf[$SNCompIdent."emailServer"].":".$conf[$SNCompIdent."emailServerPort"]));
            }
        }
        displaySuccessPage();
    } else {
        echo "Sorry, your account could not be created";
    }
}







/*
* display the success page after a successful signup
*/
function displaySuccessPage() {

    global $conf,$SNCompIdent,$errors,$ssCommon;
    if ($errors) {
        return;
    }
    $conf[$SNCompIdent . "nextPage"] = $ssCommon->makeRelativeToCgiBin($conf[$SNCompIdent . "nextPage"]);
    //header ("Location:" . $conf["[SIGNUP]label.nextPage"]);
    echo "<html>";
    echo "<head></head>";
    echo "<body></body>";
    echo "<script>";
    echo "top.document.location.href = '" .  $conf[$SNCompIdent . "nextPage"] . "';" ;
    echo "</script>";
    echo "</html>";
}





/*
* function to change the keys of an array to lower case
*/
function changeKeyCaseLow($inputArray) {
        reset($inputArray);

  while(list($key,$value) = each($inputArray)) {
            $key = strtolower($key);
            $outputArray[$key] = $value;
        }

        return $outputArray;

}





/*
* function to change the value of an array to lower case
*/
function changeValueCaseLow($inputArray) {
        reset($inputArray);

  while(list($key,$value) = each($inputArray)) {
            $value = strtolower($value);
            $outputArray[$key] = $value;
        }

        return $outputArray;

}





/*
* create an account
*/
function createAccount() {

    global $conf,$requiredForPassRetv,$fieldsDBArray,$firstLineUD,$SNCompIdent,$AMCompIdent,$ssCommon;

    //open DB file for appending
    if (!$FILE = @fopen($conf[$SNCompIdent . "dbPath"], 'ab')) {
        $sysErr = "Cannot open database file for appending.<br>Check path/permissions of database file/directory.";
        NOF_throwError(502,array("{1}"=>NOF_mapPath($conf[$SNCompIdent . "dbPath"]),"{2}"=>dirname($conf[$SNCompIdent . "dbPath"])));
        return false;
    }

    //change keys of $_POST array to lower case
    $postLowCaseArray =  changeKeyCaseLow(GetPostVariable(''));
    //change values of $fieldsDBArray to lower case
    $fieldsDBLowCaseArray =  changeValueCaseLow($fieldsDBArray);

    //for every expected field in the DB get its value from post request
    for($i = 0;$i<count($fieldsDBLowCaseArray)-2;$i++) {
        //check if the param has multiple values (from a checkbox group for example)
        if(gettype($postLowCaseArray[$fieldsDBLowCaseArray[$i]])=="array") {
            $entryAppend = "";
            for($j = 0;$j<count($postLowCaseArray[$fieldsDBLowCaseArray[$i]]);$j++) {
                if($entryAppend=="") {
                    $entryAppend = $postLowCaseArray[$fieldsDBLowCaseArray[$i]][$j];
                } else {
                    //multiple values are clubbed together seperated by commas
                    $entryAppend = $entryAppend . "," . $postLowCaseArray[$fieldsDBLowCaseArray[$i]][$j];
                }
            }
        } else {  //if the param is not multiple valued
            $entryAppend = $postLowCaseArray[$fieldsDBLowCaseArray[$i]];
        }
        if(!isset($entry)) {
            $entry = $ssCommon->preprocess($entryAppend);
        } else {
            $entry = $entry . "," .  $ssCommon->preprocess($entryAppend);
        }
    }


    //set admin stat and validation flag
    $adminStat = "false";
    $adminStat = $ssCommon->preprocess($adminStat);
    if ($conf[$AMCompIdent . "automaticvalidation"]=="false") {
        $validationFlag = "false";
    } else {
        $validationFlag = "true";
    }
    $validationFlag = $ssCommon->preprocess($validationFlag);

    //club the admin stat and validation flag
    $entry = $entry . "," . $adminStat . "," . $validationFlag;

    //append entry to the file
    if (!@fputs($FILE, "$entry\n") && !errorFlag) {
        $sysErr = "Cannot append to database file.<br>Check path/permissions of database file/directory.";
        NOF_throwError(502,array("{1}"=>NOF_mapPath($conf[$SNCompIdent . "dbPath"])));
        return false;
    } else {
        fclose($FILE);
    }

    return true;
}





/*
* function to call the error script to display the error page
*/
function displayErrorPage($error_label,$error_field) {
    global $conf,$SNCompIdent,$AMCompIdent,$ssCommon;

        //make a list of http params and their current values
    $postArr = GetPostVariable('');
    reset($postArr);
    while ( list($key,) = each($postArr) ) {
   //if the param has multiple values (for example a checkbox group)
   if(gettype($postArr[$key])=="array") {
	 $postVar = $postArr[$key];
     for($i = 0;$i<count($postVar);$i++) {
      if(!isset($params)) {
              $params = $key;
          }
             else {
        $params =  $params . "," . $key;
             }
          if(!isset($values)) {
              $values = $postVar[$i];
          }
             else {
        $values =  $values . ",;,;" . $postVAr[$i];
             }
     }
                }
   else { //if param was not multiple valued
      if(!isset($params)) {
              $params = $key;
          }
             else {
        $params =  $params . "," . $key;
             }
          if(!isset($values)) {
              $values = $postArr[$key];
          }
             else {
        $values =  $values . ",;,;" . $postArr[$key];
             }
                }
    }
  //get the error labels and associated fields in arrays
        $errorLabel = explode(",",$error_label);
        $errorField = explode(",",$error_field);

  $id = str_replace("signup.", "", $SNCompIdent);
  $id = str_replace(".", "", $id);

  echo "<HTML>";
  echo "<HEAD><meta http-equiv='Content-Type' content='text/html; charset=UTF-8' /></HEAD>";
  echo "<BODY>";

  echo "<FORM NAME='INV' METHOD='POST' ACTION='" . GetServerVariable('HTTP_REFERER') . "'  target='_self'>" ;
  //for each error
  $errorMessages = '';
  for($i=0;$i< count($errorLabel);$i++) {
	  $errorLabelMinusMessage = preg_replace("/\.message$/i","",$errorLabel[$i]);
	   $errorMessages =  $errorMessages . "<LI><SPAN CLASS='" . $conf[$errorLabelMinusMessage . ".errorcss"]. "'>"
                        . $ssCommon->cleanField($conf[$errorLabel[$i]])
                        . "</SPAN>"
                        . "</LI>";

  }
  echo "<INPUT TYPE='HIDDEN' NAME='"
     . $id."_errormessgs"
     . "' VALUE=\"<ul>"
     . $errorMessages
     . "</ul>\">" ;

        for($i=0;$i< count($errorField);$i++) {
   echo "<INPUT TYPE='HIDDEN' NAME='"
     . $id . "_" . $errorField[$i] . "_errorimg"
     . "' VALUE=\""
     . "<IMG SRC='"
     . $conf[$SNCompIdent . "errorMark"]
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
* function to see if all required parameters were filled
*/
function checkIfRequiredUnfilled() {

  global $conf,$fieldsDBArray,$SNCompIdent,$AMCompIdent,$ssCommon;
    $errorFoundFlag=FALSE;

  for($i=0;$i<count($fieldsDBArray)-2;$i++) {
      $property = $SNCompIdent . $fieldsDBArray[$i] . ".errorevent.required.active";
   //echo $property;
   if( $conf[$property]=="true" ) {
   	$postVar = GetPostVariable($fieldsDBArray[$i]);
    if ( $postVar != '' && gettype($postVar) != "array"
	&& (! preg_match("/^\s*$/",$postVar)) ) {
      continue;
     }
     elseif( isset($postVar[0]) ) {
      continue;
     }
     else {
            $label=$SNCompIdent . $fieldsDBArray[$i] . ".errorevent.required.message" ;
            $ssCommon->clubError($label,$fieldsDBArray[$i]);
            $errorFoundFlag=TRUE;
     }

   }
    }

 // if use captcha, add the field
        if ($conf[$SNCompIdent .   "captcha.errorevent.invalidcode.active"]=='true')  {
		$fieldsToCheck[count($fieldsToCheck)] = 'captcha';
		$label=$SNCompIdent .  "captcha.errorevent.required.message" ;

		if (GetPostVariable('captcha')=='') {
			$ssCommon->clubError($label,'captcha');
			$errorFoundFlag=TRUE;
		}
		else {
			if (!nof_captcha_validate())  {
			$label = $SNCompIdent . 'captcha.errorevent.invalidcode.message';
			$ssCommon->clubError($label,'captcha');
			$errorFoundFlag=TRUE;
			}
		}

	}

    return $errorFoundFlag;
}





/*
* function to check if username is already taken
*/
function checkIfLoginAlreadyExists() {

    global $conf,$SNCompIdent,$AMCompIdent,$ssCommon ;

    //check if DB file exists. if it doesnt probably this is the
    //first user so return false
    if( !file_exists($conf[$SNCompIdent . "dbPath"]) ) {
        return FALSE;
    }
        //get the DB in an array
    if(!$lines = @file($conf[$SNCompIdent . "dbPath"])) {
          $sysErr="File \"" . $conf[$SNCompIdent . "dbPath"] . "\" could not be read.<br>" .
           "The path is either invalid or right permissions<br>" .
           "have not been set on the file.";
          NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$SNCompIdent . "dbPath"]),"{2}"=>dirname($conf[$SNCompIdent . "dbPath"])));
          exit;
    }
    //get column number of username field in the DB
    $userColPos = $ssCommon->getColumnPos("username");
      SetPostVariable("username", $ssCommon->cleanField(trim(GetPostVariable("username"))));

        //check in DB if there an entry for the username already
  for ( $i=1; $i<count($lines); $i++ ) {
       $userName=$ssCommon->getField($userColPos,$lines[$i]);
       if(strtolower($userName)==strtolower(stripslashes(GetPostVariable("username")))) {
           $ssCommon->clubError($SNCompIdent . "username.errorevent.alreadyexists.message","username");
           return TRUE;
       }
    }

        //check also against the name of administrator in the admin property file
        if(strtolower(stripslashes(GetPostVariable("username")))==strtolower(trim($conf[$AMCompIdent . "adminuser.username"]))) {
         $ssCommon->clubError($SNCompIdent . "username.errorevent.alreadyexists.message","username");
         return TRUE;
        }

  return FALSE;
}






/*
* function to check if password and retyped password are same
*/
function checkIfPasswordsInvalid() {

  global $conf,$SNCompIdent,$ssCommon;

  if(GetPostVariable("password")!=GetPostVariable("retypePassword")) {
       $ssCommon->clubError($SNCompIdent . "retypePassword.errorevent.passwordsnotmatch.message" ,"retypePassword");
       return TRUE;
    }

  return FALSE;
}





/*
* check if the field lengths are within their limits
*/
function checkIfFieldsLengthInvalid() {
  global $conf,$SNCompIdent,$ssCommon;
  //set the error found fla to false
  $errorFoundFlag=FALSE;
  //reset the post array
  $postArr = GetPostVariable('');
  reset($postArr);

  //go throuh the posted entities
  while( list($field,$value)= each($postArr) ) {

      $shortErrorEventActiveProperty = $SNCompIdent . $field . ".errorevent.short.active";
      $minimumLengthProperty = $SNCompIdent . $field . ".errorevent.short.minimumlength";

   //if the field is specified as required in the property file
	if ( isset($conf[$shortErrorEventActiveProperty]) ) {
	   if ( $postArr[$field] != ''
		 && $conf[$shortErrorEventActiveProperty] == "true"
		 && preg_match("/^[-+]{0,1}\d+$/" ,$conf[$minimumLengthProperty])
		 && strlen($postArr[$field]) < $conf[$minimumLengthProperty] ) {
		 //the label associated with the error messages
		 $label= $SNCompIdent . $field . ".errorevent.short.message" ;
		 //club the error label and error field
		 $ssCommon->clubError($label,$field);
		 //set the error found flag to true
		 $errorFoundFlag=TRUE;

	   }
	}

      $longErrorEventActiveProperty = $SNCompIdent . $field . ".errorevent.long.active";
      $maximumLengthProperty = $SNCompIdent . $field . ".errorevent.long.maximumlength";

	if ( isset($conf[$longErrorEventActiveProperty]) ) {
	   if( $postArr[$field] != ''
		 && $conf[$longErrorEventActiveProperty] == "true"
		 && preg_match("/^\+{0,1}\d+$/" ,$conf[$maximumLengthProperty] )
		 && strlen($postArr[$field]) > $conf[$maximumLengthProperty] ) {
		 //the label associated with the error messages
		 $label= $SNCompIdent . $field . ".errorevent.long.message" ;
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
* send user name and password by email
*/
function sendAccountEmail() {

    global $conf,$SNCompIdent,$ssCommon,$AMCompIdent;

    $notifyAdmin = true;
    if ( isset($conf[$AMCompIdent . "notifyOnSignup"]) && $conf[$AMCompIdent . "notifyOnSignup"] == "false" )
        $notifyAdmin = false;
    // build email body using template
    $body = $conf["[EMAIL]Body"];
    $to = GetPostVariable("email");

    $body = str_replace('{0}' , $ssCommon->cleanField($ssCommon->preprocess(GetPostVariable("username"))), $body);
    $body = str_replace('{1}' , $ssCommon->cleanField($ssCommon->preprocess(GetPostVariable("password"))), $body);

    $body = str_replace("\\n", "<br>", $body);
    $body = str_replace("\\", "", $body);

    $mail = new PHPMailer();
    $mail->From = $conf[$SNCompIdent."emailFromAddress"];
    $mail->FromName = $conf[$SNCompIdent."emailFromAddress"];
    if ( $notifyAdmin ) 
        $mail->AddBCC($conf[$SNCompIdent."emailToAddress"],"");
    $mail->Host = $conf[$SNCompIdent."emailServer"];
    $mail->Port = $conf[$SNCompIdent."emailServerPort"];
    $mail->SMTPDebug = false;
    $mail->Mailer = "smtp";
    $mail->Subject = $conf["[EMAIL]Subject"];
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth = false;
    if ( $conf[$SNCompIdent."smtpAuth"] == "true" ) {
        $mail->SMTPAuth = true;
        $mail->Username = $conf[$SNCompIdent."smtpUsername"];
        $mail->Password = $conf[$SNCompIdent."smtpPassword"];
    }
    $mail->SMTPSecure = (isset($conf[$SNCompIdent."smtpSSL"]) && $conf[$SNCompIdent."smtpSSL"] == "true")?'ssl':'';
    $mail->IsHTML = true;
    $mail->AddAddress($to,$to);
    $mail->Body = '<html><body>'.$body.'</body></html>';
    $mail->AltBody = str_replace('<br>','\n',$body);
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




?>
