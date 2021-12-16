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
$nof_langFile = str_replace("/", "", $nof_langFile);

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
$firstLineUD="";
$GPCompIdent="";
$AMCompIdent="";
$SNCompIdent="";


$GPCompIdent = "getpassword" . "."  . GetPostVariable('nof_componentId') . ".";


if (NOF_fileExists('ss_common.php')) {
    require_once('ss_common.php');
} else {
    exit();
}

//read the XML property file
$conf = ss_parseXmlFile($xmlPropertyFile);

$emailPropertiesFile='ss_GetPasswordTemplate_'.$conf[$GPCompIdent.'language'].'.properties';

$ssCommon->readEmailTemplate($emailPropertiesFile,"[EMAIL]");

if ( GetPostVariable('nof_componentId') == '' ) {
    $errorMessage = "Expected POST param not passed: nof_componentId";
    NOF_throwError(800,array("{1}"=>"nof_componentId"));
    exit();
}

$adminID = getAdminID($conf[$GPCompIdent . "dbPath"]);
$AMCompIdent = "admin." . $adminID . ".";


$SNID = getSignupID($conf[$GPCompIdent . "dbPath"]);

if($SNID == -1) {
    $errorMessage = "Signup's ID with the same DB path could not be found.";
    NOF_throwError(602,array("{1}"=>$nof_suiteName,"{2}"=>$conf[$GPCompIdent . "dbPath"]));
    exit();
}

$SNCompIdent = "signup." . $SNID . ".";


if (!file_exists(dirname($conf[$GPCompIdent ."dbPath"]))) {
    NOF_throwError(540,array("{1}"=>NOF_mapPath(dirname($conf[$GPCompIdent ."dbPath"])),"{2}"=>getcwd()));
}

//if (!is_writable(dirname($conf[$GPCompIdent ."dbPath"]))) {
//    NOF_throwError(541,array("{1}"=>NOF_mapPath(dirname($conf[$GPCompIdent ."dbPath"])),"{2}"=>getcwd()));
//}

$ssCommon->getExpectedDBFields($SNCompIdent);

//if email was unfilled
if(checkIfEmailUnfilled()) {
    displayErrorPage($errorLabel,$errorField);
    exit();
}
//if email was invalid
if($ssCommon->checkIfEmailInValid(GetPostVariable('email'),$GPCompIdent)) {
    displayErrorPage($errorLabel,$errorField);
    exit();
}


//check if DB needs to be created/overwritten
if (!$ssCommon->checkIfDBmatch($GPCompIdent)) {
    //open DB file for writing
    if (!$FILE = fopen($conf[$GPCompIdent . "dbPath"], 'wb')) {
        $sysErr = "Cannot create/write to database file.<br>Check path/permissions of database file/directory.";
        NOF_throwError(502,array("{1}"=>NOF_mapPath($conf[$GPCompIdent . "dbPath"]),"{2}"=>dirname($conf[$GPCompIdent . "dbPath"])));
        exit;
    } else {
        //write first line of UD file, to the DB file
        if (!fputs($FILE, "$firstLineUD\n")) {
            $sysErr = "Cannot write to database file.<br>Check path/permissions of database file/directory.";
            NOF_throwError(502,array("{1}"=>NOF_mapPath($conf[$GPCompIdent . "dbPath"]),"{2}"=>dirname($conf[$GPCompIdent . "dbPath"])));
            exit;
        }
        fclose($FILE);
    }
}


lookAndSendPassword();







function lookAndSendPassword() {

    global $conf,$errorLabel,$errorField,$GPCompIdent,$ssCommon;

    $userFoundFlag=FALSE;

        //get column numbers
    $usernameColPos = $ssCommon->getColumnPos("username");
    $passwordColPos = $ssCommon->getColumnPos("password");
    $emailColPos = $ssCommon->getColumnPos("email");

        //get the DB in an array
    if(!$lines = file($conf[$GPCompIdent . "dbPath"])) {
        $sysErr="File \"" . $conf[$GPCompIdent . "dbPath"] . "\" could not be read.<br>" .
                "The path is either invalid or right permissions<br>" .
                "have not been set on the file.";
        NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$GPCompIdent . "dbPath"])));
        exit;
    }
    SetPostVariable("email", trim(GetPostVariable("email")));

    $j=0;
    //look for accounts with the email
    for($i=1; $i < count($lines); $i++) {
        $email = $ssCommon->getField($emailColPos,$lines[$i]);
        if(strtolower($email)==strtolower(GetPostVariable("email"))) {
            $username[$j] = $ssCommon->getField($usernameColPos,$lines[$i]);
            $password[$j] = $ssCommon->getField($passwordColPos,$lines[$i]);
            $j++;
            $userFoundFlag=TRUE;

        }
    }
        //if user was found
    if($userFoundFlag) {
        if(!sendPasswordByEmail($username,$password)) {
            $sysErr="Error while sending confirmation email to your registered email account. Please check the SMTP address in the php.ini file.<br>" ;
            NOF_throwError(201,array("{1}"=>GetPostVariable('email'),"{2}"=>$conf[$GPCompIdent."emailFromAddress"],"{3}"=>$conf[$GPCompIdent."emailServer"].":".$conf[$GPCompIdent."emailServerPort"]));
            exit;
        } else {
            displaySuccessPage();
        }
    } else {
        //if email was not in DB
        $ssCommon->clubError($GPCompIdent . "email.errorevent.emailnotfound.message","email");
        displayErrorPage($errorLabel,$errorField);
        exit();
    }
}





/*
* send user name and password by email
*/
function sendPasswordByEmail($username,$password) {
    global $conf,$GPCompIdent;

    //get email template in a single line
    $tplline = $conf["[EMAIL]Body"];
    $tplline1 = '';
    $usertpl = substr($tplline,strpos($tplline,'{beginiterator}') + 15,strlen($tplline) - strpos($tplline,'{enditerator}'));
    for ($i=0;$i<count($username) && $i<count($password);$i++) {
        $usertpl1 = preg_replace("/\{0\}/" ,$username[$i], $usertpl);
        $usertpl1 = preg_replace("/\{1\}/" , $password[$i], $usertpl1);
        $tplline1 .= $usertpl1;
    }
    $tplline = str_replace($usertpl,$tplline1,$tplline);
    $tplline = str_replace("\\n", "<br>", $tplline);
    $tplline = str_replace("{beginiterator}", "", $tplline);
    $tplline = str_replace("{enditerator}", "", $tplline);
    $tplline = str_replace("\\", "", $tplline);
/*
    if (NOF_fileExists('ss_mailer.php')) {
        include_once("ss_mailer.php");
    } else {
        exit();
    }
*/
    $mail = new PHPMailer();
    $mail->From = $conf[$GPCompIdent."emailFromAddress"];
    $mail->FromName = $conf[$GPCompIdent."emailFromAddress"];
    $mail->Host = $conf[$GPCompIdent."emailServer"];
    $mail->Port = $conf[$GPCompIdent."emailServerPort"];
    $mail->SMTPDebug = false;
    $mail->Mailer = "smtp";
    $mail->Subject = $conf["[EMAIL]Subject"];
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth = false;
    if ( $conf[$GPCompIdent."smtpAuth"] == "true" ) {
        $mail->SMTPAuth = true;
        $mail->Username = $conf[$GPCompIdent."smtpUsername"];
        $mail->Password = $conf[$GPCompIdent."smtpPassword"];
    }
    $mail->SMTPSecure = (isset($conf[$GPCompIdent."smtpSSL"]) && $conf[$GPCompIdent."smtpSSL"] == "true")?'ssl':'';
    $mail->IsHTML = true;
    $mail->AddAddress(GetPostVariable("email"), GetPostVariable("email"));
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
                echo "<!-- ErrorInfo: ".$mail->ErrorInfo."-->";
                return false;
            }
        }
    }


    // done! clean up
    $mail->ClearAddresses();
    $mail->ClearAttachments();
    return true;

}





/*
* display the next page
*/
function displaySuccessPage() {
    global $conf,$GPCompIdent,$errors,$ssCommon;
    if (!$errors) {
        $conf[$GPCompIdent . "nextPage"]=$ssCommon->makeRelativeToCgiBin($conf[$GPCompIdent . "nextPage"]);
        //header ("Location: " . $conf["[GETPASSWORD]label.nextPage"]);
        echo "<html>";
        echo "<head></head>";
        echo "<body></body>";
        echo "<script>";
        echo "top.document.location.href='" .  $conf[$GPCompIdent . "nextPage"] . "';" ;
        echo "</script>";
        echo "</html>";
    }
}





/*
* display the error page
*/
function displayErrorPage($error_label,$error_field) {

    global $conf,$GPCompIdent,$ssCommon;

    $params="email";
    $values=GetPostVariable("email");

    //get the error labels and associated fields in arrays
    $errorLabel = explode(",",$error_label);
    $errorField = explode(",",$error_field);

    $id = str_replace("getpassword.", "", $GPCompIdent);
    $id = str_replace(".", "", $id);

    echo "<HTML>";
    echo "<HEAD><meta http-equiv='Content-Type' content='text/html; charset=UTF-8' /></HEAD>";
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
                    . "' VALUE=\"<ul>"
                    . $errorMessages
                    . "</ul>\">" ;
    for($i=0;$i< count($errorField);$i++) {
            echo "<INPUT TYPE='HIDDEN' NAME='"
                    . $id . "_" . $errorField[$i] . "_errorimg"
                    . "' VALUE=\""
                    . "<IMG SRC='"
                    . $conf[$GPCompIdent . "errorMark"]
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
* check if email was unfilled
*/
function checkIfEmailUnfilled() {
  global $GPCompIdent,$ssCommon;

  if(preg_match("/^\s*$/",GetPostVariable("email")) ) {
      $ssCommon->clubError($GPCompIdent . "email.errorevent.required.message","email");
      return TRUE;
  }

  return FALSE;
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
