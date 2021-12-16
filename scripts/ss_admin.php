    <div style="width:500px;" id="errorshow<?php echo $componentid;?>"></div>
<?php

$errors = false;
$cgiDir = $nof_rootDir ."/". $nof_scriptDir . "/";
$componentid = $nof_componentId;
$xmlPropertyFile   = $cgiDir . $nof_scriptInterfaceFile;


if (NOF_fileExists($cgiDir . 'ss_xmlparser.php')) {
    require_once($cgiDir . 'ss_xmlparser.php');
} else {
    exit();
}

$configFile=$cgiDir . "admin.cfg";
$conf="";
$fieldsDBArray="";
$errorLabel="";
$errorField="";
$firstLineUD="";

$commonPath = $cgiDir . "ss_common.php";
if (NOF_fileExists($commonPath)) {
    require_once($commonPath);
} else {
    exit();
}

$AMCompIdent = "admin" . "."  . $componentid . ".";

echo "<script type='text/javascript' src='" . $cgiDir . "ss_admin.js" .  "'></script>";

//read the configuration file, store contents in the global array $conf
$conf = ss_parseXmlFile($xmlPropertyFile);

$emailPropertiesFile=$cgiDir . 'ss_AdminTemplate_'.$conf[$AMCompIdent.'language'].'.properties';

$ssCommon->readEmailTemplate($emailPropertiesFile,"[EMAIL]");

if(!isset($componentid)) {
    $errorMessage = "Expected variable not passed: $componentid";
    NOF_throwError(800,array("{1}"=>"nof_componentId"));
    exit();
}

//add the cgi-bin path ahead of the userdetails file name
//$conf["[CONF]userdetailspath"]=$cgiDir . $conf["[CONF]userdetailspath"];
$SNID = getSignupID($conf[$AMCompIdent . "dbPath"]);
if($SNID == -1) {
    NOF_throwError(602,array("{1}"=>$nof_suiteName,"{2}"=>$conf[$AMCompIdent . "dbPath"]));
    exit();
}

$SNCompIdent = "signup." . $SNID . ".";


//add the cgi-bin path ahead of the DB file name if its a pure file name without whole path
//(when the file path is just a name it's asssumed that it's in cgi-bin)
if( !preg_match("/^\//" , $conf[$AMCompIdent . 'dbPath'])
   && !preg_match('/[a-z]\:\//i' , $conf[$AMCompIdent . 'dbPath'])
   && !preg_match('/[a-z]\:(\\\\)/i' , $conf[$AMCompIdent . 'dbPath'])   ) {
          $conf[$AMCompIdent . 'dbPath']=$cgiDir . "/" . $conf[$AMCompIdent . 'dbPath'];
}
/*
if( !preg_match("/\//" , $conf[$AMCompIdent . "dbPath"]) && !preg_match('/(\\\\)/' , $conf[$AMCompIdent . "dbPath"])   ) {
      $conf[$AMCompIdent . "dbPath"]=$cgiDir . $conf[$AMCompIdent . "dbPath"];
}
*/


//get the names of fields present in the database
$ssCommon->getExpectedDBFields($SNCompIdent);

//if DB does not exist or if first line of userdetails.db(column headings
//does not match first line of DB meaning that
//the file has to be created/overwritten with new columns from userdetails.db
if( !$ssCommon->checkIfDBmatch($AMCompIdent) ) {
    //open DB file for writing
    if (!$FILE = @fopen($conf[$AMCompIdent . "dbPath"], 'wb')) {
        NOF_throwError(502,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
        exit();
    } else {
        //write first line of UD file, to the DB file
        if (!fputs($FILE, "$firstLineUD\n")) {
        	NOF_throwError(502,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
            exit();
        }
        fclose($FILE);
    }
}

//if no operation was passed (meaning page is loaded the first time)
if( GetPostVariable("operation") == '' && GetPostVariable("pageoperation") == '' )  {
	//display 10 users beginning from 1
	displayUsersList(1);
}
elseif ( GetPostVariable("pageoperation") != '' ) {
    //if the Next button was pressed
    if ( GetPostVariable("pageoperation")=="Next" && GetPostVariable("begin")!='' && GetPostVariable("mode")=="naturalorder" ) {
	//display 10 users from GetPostVariable("begin") + 10
            displayUsersList(GetPostVariable("begin") + 10);
    }
    //if the Next button was pressed
    elseif( GetPostVariable("pageoperation")=="Next" && GetPostVariable("begin")!='' && GetPostVariable("mode")=="validusersonly" ) {
	//display 10 users from GetPostVariable("begin") + 10
            displayValidatedUsersList(GetPostVariable("begin") + 10);
    }
    //if the Next button was pressed
    elseif( GetPostVariable("pageoperation")=="Next" && GetPostVariable("begin")!='' && GetPostVariable("mode")=="invalidusersonly" ) {
	//display 10 users from GetPostVariable("begin") + 10
            displayInvalidatedUsersList(GetPostVariable("begin") + 10);
    }
    //if the Prev button was pressed
    elseif( GetPostVariable("pageoperation")=="Prev" && GetPostVariable("begin")!='' && GetPostVariable("mode")=="naturalorder" ) {
	//display 10 users from GetPostVariable("begin") - 10
            displayUsersList(GetPostVariable("begin") - 10);
    }
    //if the Prev button was pressed
    elseif( GetPostVariable("pageoperation")=="Prev" && GetPostVariable("begin")!='' && GetPostVariable("mode")=="validusersonly") {
	//display 10 users from GetPostVariable("begin") + 10
            displayValidatedUsersList(GetPostVariable("begin") - 10);
    }
    //if the Prev button was pressed
    elseif( GetPostVariable("pageoperation")=="Prev" && GetPostVariable("begin")!='' && GetPostVariable("mode")=="invalidusersonly") {
	//display 10 users from GetPostVariable("begin") + 10
            displayInvalidatedUsersList(GetPostVariable("begin") - 10);
    }
    //if the First button was pressed
    elseif( GetPostVariable("pageoperation")=="First" && GetPostVariable("mode")=="naturalorder" ) {
      //display 10 users from 1
            displayUsersList(1);
    }
    //if the First button was pressed
    elseif( GetPostVariable("pageoperation")=="First" && GetPostVariable("mode")=="validusersonly" ) {
	//display 10 users from 1
            displayValidatedUsersList(1);
    }
    //if the First button was pressed
    elseif( GetPostVariable("pageoperation")=="First" && GetPostVariable("mode")=="invalidusersonly" ) {
	//display 10 users from 1
            displayInvalidatedUsersList(1);
    }
    //if the Last button was pressed
    elseif( GetPostVariable("pageoperation")=="Last" && GetPostVariable("mode")=="naturalorder" ) {
	//display last users
            displayLastUsersList();
    }
    //if the Last button was pressed
    elseif( GetPostVariable("pageoperation")=="Last" && GetPostVariable("mode")=="validusersonly" ) {
	//display last users
            displayValidatedLastUsersList();
    }
    //if the Last button was pressed
    elseif( GetPostVariable("pageoperation")=="Last" && GetPostVariable("mode")=="invalidusersonly" ) {
	//display last users
            displayInvalidatedLastUsersList();
    }
}
//if all users operation was passed
elseif( GetPostVariable("operation")=="Show All Users" )  {
	//display 10 users beginning from 1
	displayUsersList(1);
}
//if Save Validation Flag  button was pressed
elseif( GetPostVariable("operation")=='save' && GetPostVariable("begin")!='' && GetPostVariable("mode")!='' ) {
	//get an array filled with list of users that need to be validated
	$usersToBeValidated = getUsersToBeValidated();
	//get an array filled with list of users that need to be invalidated
	$usersToBeInvalidated = getUsersToBeInvalidated();
	//validate/invalidate 10 users from GetPostVariable("begin") depending on presence/absence from the array
	validateInvalidateUsers(GetPostVariable("begin"),$usersToBeValidated,$usersToBeInvalidated);
	//display 10 users from GetPostVariable("begin")
	if(GetPostVariable("mode")=="naturalorder") {
	  displayUsersList(GetPostVariable("begin"));
	}
	if(GetPostVariable("mode")=="validusersonly") {
		displayValidatedUsersList(GetPostVariable("begin"));
	}
	if(GetPostVariable("mode")=="invalidusersonly") {
		displayInvalidatedUsersList(GetPostVariable("begin"));
	}
}
//if remove user button was pressed
elseif( GetPostVariable("operation")=='delete' && GetPostVariable("begin")!='' && GetPostVariable("mode")!='' ) {
  //the user name selected in the radio group
  if( GetPostVariable("radiogroup")!='' ) {
        $userToBeRemoved = stripslashes(GetPostVariable("radiogroup"));
          //remove the user
          removeUser(GetPostVariable("begin"),$userToBeRemoved);
        }
        //display 10 users from GetPostVariable("begin")
        if(GetPostVariable("mode")=="naturalorder") {
          displayUsersList(GetPostVariable("begin"));
        }
        if(GetPostVariable("mode")=="validusersonly") {
            displayValidatedUsersList(GetPostVariable("begin"));
        }
        if(GetPostVariable("mode")=="invalidusersonly") {
            displayInvalidatedUsersList(GetPostVariable("begin"));
        }
}
//if edit details button was pressed
elseif ( GetPostVariable("operation")=='edit' && GetPostVariable("begin")!='' && GetPostVariable("mode")!='' ) {
  //the user name selected in the radio group
        if( GetPostVariable("radiogroup")!='' ) {
            $userToBeEdited = stripslashes(GetPostVariable("radiogroup"));
          //display details of the selected user
          displayEditUserDetails($userToBeEdited,GetPostVariable("begin"),GetPostVariable("mode"));
        }
}
//if add user button was pressed
elseif ( GetPostVariable("operation")=='add' && GetPostVariable("begin")!='' && GetPostVariable("mode")!='' ) {
          //display add user form
          displayAddUser(GetPostVariable("begin"),GetPostVariable("mode"));
}
//if view details was pressed
elseif( GetPostVariable("operation")=='view' && GetPostVariable("begin")!='' && GetPostVariable("mode")!='') {
  //the user name selected in the radio group
        if( GetPostVariable("radiogroup")!='' ) {
            $userToBeViewed = stripslashes(GetPostVariable("radiogroup"));
          //display details of the selected user
          displayUserDetails($userToBeViewed,GetPostVariable("begin"),GetPostVariable("mode"));
        }

}
//if back to users list was pressed
elseif( GetPostVariable("operation")=='back' && GetPostVariable("begin")!='' && GetPostVariable("mode")!='' ) {
  if(GetPostVariable("mode")=="naturalorder") {
     displayUsersList(GetPostVariable("begin"));
        }
  if(GetPostVariable("mode")=="validusersonly") {
     displayValidatedUsersList(GetPostVariable("begin"));
        }
  if(GetPostVariable("mode")=="invalidusersonly") {
     displayInvalidatedUsersList(GetPostVariable("begin"));
        }
}
//if reset to last was pressed
elseif( GetPostVariable("operation")=='reset' && GetPostVariable("usertobeedited")!='' && GetPostVariable("mode")!='' ) {
    displayEditUserDetails(stripslashes(GetPostVariable("usertobeedited")),GetPostVariable("begin"),GetPostVariable("mode"));
}
//if show validated users was pressed
elseif ( GetPostVariable("operation")=="Show Validated Users" ) {
        //display validated 10 users from 1
        displayValidatedUsersList(1);
}
//if show validated users was pressed
elseif ( GetPostVariable("operation")=="Show Invalidated Users" ) {
        //display validated 10 users from 1
        displayInvalidatedUsersList(1);
}
//if save user details button was pressed
elseif ( GetPostVariable("operation")=='saveuser' && GetPostVariable("usertobeedited")!='' && GetPostVariable("mode")!='' ) {
	$errorFlag=FALSE;

	//if the required parameters were unfilled
	if ( checkIfRequiredUnfilled() ) {
//		displayError($errorLabel,$errorField);
//		displayEditUserDetailsError(stripslashes(GetPostVariable("usertobeedited")),GetPostVariable("begin"),GetPostVariable("mode"),$errorField);
//		exit();
		$errorFlag=TRUE;
	}
	//if the username already exists
	else if ( strtolower(stripslashes(GetPostVariable("usertobeedited")))!=strtolower($ssCommon->cleanField(GetPostVariable("username"))) && checkIfLoginAlreadyExists() ) {
	 $errorFlag=TRUE;
	}
	//if passwords match
	//if ( checkIfPasswordsInvalid() ) {
	//  $errorFlag=TRUE;
	//}
	//if fields are not within their length limitations
	else if( checkIfFieldsLengthInvalid() ) {
	 $errorFlag=TRUE;
	}
	//if email is invalid
	else if ( $ssCommon->checkIfEmailInValid(GetPostVariable('email'),$SNCompIdent) ) {
	 $errorFlag=TRUE;
	}

	if( $errorFlag ) {
	 displayError($errorLabel,$errorField);
	 displayEditUserDetailsError(stripslashes(GetPostVariable("usertobeedited")),GetPostVariable("begin"),GetPostVariable("mode"),$errorField);
//	 exit();
	}
	//if no errors were encountered in user input
	else {
	 changeUserDetails(stripslashes(GetPostVariable("usertobeedited")));
	 displaySuccessMessage($GLOBALS['nof_resources']->get('ss.admin.text.editsuccess'));
	 displayEditUserDetails($ssCommon->cleanField(GetPostVariable("username")),GetPostVariable("begin"),GetPostVariable("mode"));
//	 exit();
	}

}

//if add user button was pressed
elseif (GetPostVariable("operation") == 'adduser' && GetPostVariable("mode")!='' ) {
	 $errorFlag=FALSE;

	 //if the required parameters were unfilled
	 if ( checkIfRequiredUnfilled() ) {
//		displayError($errorLabel,$errorField);
//		 displayAddUserError(GetPostVariable("begin"),GetPostVariable("mode"),$errorField);
//		 exit();
		$errorFlag=TRUE;
	 }
	 //if the username already exists
	 else if ( checkIfLoginAlreadyExists() ) {
		 $errorFlag=TRUE;
	 }
	 //if passwords match
	 //if ( checkIfPasswordsInvalid() ) {
	   //  $errorFlag=TRUE;
	 //}
	 //if fields are not within their length limitations
	 else if( checkIfFieldsLengthInvalid() ) {
		 $errorFlag=TRUE;
	 }
	 //if email is invalid
	 else if ( $ssCommon->checkIfEmailInValid(GetPostVariable('email'),$SNCompIdent) ) {
		 $errorFlag=TRUE;
	 }

	 if( $errorFlag ) {
		 displayError($errorLabel,$errorField);
		 displayAddUserError(GetPostVariable("begin"),GetPostVariable("mode"),$errorField);
//		 exit();
	 }
	 //if no errors were encountered in user input
	 else {
		 addUser();
		 displaySuccessMessage($GLOBALS['nof_resources']->get('ss.admin.text.addsuccess'));
		displayUserDetails($ssCommon->cleanField(GetPostVariable("username")),GetPostVariable("begin"),GetPostVariable("mode"));
//		 exit();
	 }
}


function getUsersToBeValidated() {

	$count = 0;
	$begin = GetPostVariable("begin");
	$usersToBeValidated[0] = "";
	for($i = $begin; $i < $begin + 10; $i++) {
		$param = "validatebox$i";
		if ( GetPostVariable($param)!='' ) {
			$usersToBeValidated[$count] = stripslashes(GetPostVariable($param));
			$count++;
		}
	}
	return $usersToBeValidated;
}

function getUsersToBeInvalidated() {

	$count=0;
	$begin = GetPostVariable("begin");
	$usersToBeInvalidated[0]="";
	for($i = $begin; $i < $begin + 10; $i++) {
		$checkboxParam = "validatebox" . $i;
		$userParam = "user" . $i;
		if ( GetPostVariable($checkboxParam)=='' && GetPostVariable($userParam)!='' ) {
			$usersToBeInvalidated[$count] = stripslashes(GetPostVariable($userParam));
		    $count++;
		}
	}
	return $usersToBeInvalidated;
}

function validateInvalidateUsers($begin, $usersToBeValidated, $usersToBeInvalidated) {

	global $conf,$cgiDir,$AMCompIdent,$ssCommon;
	$enabled = $conf[$AMCompIdent.'language']=='de' ? 'aktiviert' : 'enabled';
	$disabled = $conf[$AMCompIdent.'language']=='de' ? 'deaktiviert' : 'disabled';

	// Get the db in an array
    if(!$lines = @file($conf[$AMCompIdent . "dbPath"])) {
		NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
		exit();
    }

    // Get column position for username
    $userColPos = $ssCommon->getColumnPos("username");
  	$passwordColPos = $ssCommon->getColumnPos("password");
  	$emailColPos = $ssCommon->getColumnPos("email");

    // Validate/invalidate users
    for($i = 1; $i < count($lines); $i++){
        $username = $ssCommon->getField($userColPos,$lines[$i]);
        if(in_array($username,$usersToBeValidated)) {
	     	if(preg_match("/\"false\"$/",$lines[$i])){
	      		$lines[$i] = preg_replace("/\"false\"$/" , '"true"' , $lines[$i]);
				if($conf["[EMAIL]sendEmail"]=="true") {
					$password = $ssCommon->getField($passwordColPos, $lines[$i]);
					$email = $ssCommon->getField($emailColPos, $lines[$i]);
					sendValidationStatusChangeEmail($email, $username, $password, $enabled);
				}
	     	}
        }
        if(in_array($username,$usersToBeInvalidated)) {
            if( preg_match("/\"true\"$/",$lines[$i]) ) {
                $lines[$i] = preg_replace("/\"true\"$/" , '"false"' , $lines[$i]);
                if($conf["[EMAIL]sendEmail"]=="true") {
                    $password = $ssCommon->getField($passwordColPos,$lines[$i]);
                    $email = $ssCommon->getField($emailColPos,$lines[$i]);
                    sendValidationStatusChangeEmail($email,$username,$password,$disabled);
                }
            }
        }
    }

    // Open db file for writing
    if (!$FILE = @fopen($conf[$AMCompIdent . "dbPath"], 'wb')) {
        NOF_throwError(502,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
        exit();
    }
    else{

        // Dump into the db
        for($i=0;$i<count($lines);$i++) {
        if (!fputs($FILE, $lines[$i]) ){
            NOF_throwError(502,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
            exit();
            }
        }
    }
 }


function removeUser($begin,$userToBeRemoved) {
     global $conf,$cgiDir,$AMCompIdent,$ssCommon;

    // Get the db in an array
    if(!$lines = @file($conf[$AMCompIdent . "dbPath"])) {
        NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
        exit();
    }

    // Get the col number of username
    $userColPos = $ssCommon->getColumnPos("username");
    $posToBeRemoved=-1;

    // Search for user to be removed and note his row number
    for($i = 0; $i<count($lines); $i++ ){
        $username = $ssCommon->getField($userColPos,$lines[$i]);
        if($username==$userToBeRemoved){
            $posToBeRemoved=$i;
        }
    }

    // Open db file for writing
    if (!$FILE = @fopen($conf[$AMCompIdent . "dbPath"], 'wb')) {
        NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
        exit();
    }

    // Dump into the file all lines except where the user to be removed was found
    for($i = 0; $i < count($lines); $i++){
        if(($i != $posToBeRemoved) && (!fputs($FILE, $lines[$i]))) {
            NOF_throwError(502,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
            exit();
        }
    }
}


function displayUsersList($begin) {

    global $conf,$fieldsDBArray,$AMCompIdent,$ssCommon;

    // Get the whole database in an array
    if(!$lines = @file($conf[$AMCompIdent . "dbPath"])) {
          NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
          exit();
    }

    if ($begin >= count($lines)) {
        $begin = $begin -10;
    }
    if ($begin < 1 ) {
         $begin = 1;
    }

    // Get column positions of mandatory fields that will be displayed
    $userColPos = $ssCommon->getColumnPos("username");
    $emailColPos = $ssCommon->getColumnPos("email");
    $adminStatusColPos = count($fieldsDBArray)-2;
    $validationFlagColPos = count($fieldsDBArray)-1;

    // Begin form
    echo "<FORM NAME=\"adminform\" TARGET=\"_self\" ACTION=\"" . GetServerVariable('PHP_SELF') . "\"  METHOD=\"POST\">";
    echo "<INPUT TYPE='HIDDEN' NAME='begin' VALUE='" . $begin . "'>";
    echo "<INPUT TYPE='HIDDEN' NAME='mode' VALUE='" . "naturalorder" . "'>";
    echo "<INPUT TYPE='HIDDEN' NAME='pageoperation' value=''>";
    echo "<INPUT TYPE='HIDDEN' NAME='operation' value=''>";
    echo "<TABLE BGCOLOR='#C3C3C3' BORDER='1' CELLPADDING='2' CELLSPACING='5' >";
    echo "<TR BGCOLOR='#E3E3E3'>";
    echo "<TD ALIGN= 'center' COLSPAN='5'>";
    echo "<b>".$GLOBALS['nof_resources']->get('SS.Admin.Title.BrowseUsers')."</b>";
    echo "</TD>";
    echo "</TR>";
    echo "<TR BGCOLOR='#E3E3E3'>";
    echo "<TD COLSPAN='5'>";
    echo "<b>".$GLOBALS['nof_resources']->get('SS.Admin.Text.Show')."</b>";
    echo "<SELECT SIZE=1  NAME=\"showusers\" onChange='modeChanged();' >";
    echo "<OPTION SELECTED VALUE=\"" . "Show All Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.AllUsers");
    echo "<OPTION VALUE=\"" . "Show Validated Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.ActiveUsers");
    echo "<OPTION VALUE=\"" . "Show Invalidated Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.InactiveUsers");
    echo "</SELECT>";
    echo "</TD>";
    echo "</TR>";
    // Print the column names
    echo "<TR BGCOLOR='#E3E3E3'>";
    echo "<td>&nbsp;</td>";
    echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$userColPos])) . "</b></TD>";
    echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$emailColPos])) . "</b></TD>";
    echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$adminStatusColPos])) . "</b></TD>";
    echo "<td align='center' nowrap><b>" . ucfirst(strtolower($fieldsDBArray[$validationFlagColPos])) . "&nbsp;"
         .  "<INPUT TYPE='CHECKBOX' NAME='allbox' VALUE = '1' OnClick='allCheck()' >"
                    .  "</b></TD>";
    echo "</TR>";

    $end = min(count($lines),$begin+10);

    // Display 10 users from beginning
    for( $i=$begin; $i< $end; $i++ ) {
        $username = $ssCommon->getField($userColPos,$lines[$i]);
        //$password = $ssCommon->getField($passwordColPos,$lines[$i]);
        $email = $ssCommon->getField($emailColPos,$lines[$i]);
        $adminStatus = $ssCommon->getField($adminStatusColPos,$lines[$i]);
        $validationFlag = $ssCommon->getField($validationFlagColPos,$lines[$i]);
        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<td>";
        if($i == $begin) {
            echo "<input type='radio' name='radiogroup' value=\"" . $username ."\" CHECKED>";
        }
        else{
            echo "<input type='radio' name='radiogroup' value=\"" . $username ."\">";
        }
        echo "</TD>";
        echo "<td align='left'>";
        echo $username;
        echo "</TD>";
        echo "<td align='left'>";
        echo $email;
        echo "</TD>";
        echo "<td align='center'>";
        echo $adminStatus;
        echo "</TD>";
        echo "<td align='center'>";

        if($validationFlag=="false") {
            echo "<INPUT TYPE=CHECKBOX NAME='" . "validatebox" . $i . "' VALUE=\"" . $username ."\" OnClick='updateAllBox()'>";
        }
        else {
            echo "<INPUT TYPE='CHECKBOX' NAME='" . "validatebox" . $i . "' VALUE=\"" . $username ."\" CHECKED OnClick='updateAllBox()'>";
        }
        echo "<INPUT TYPE='HIDDEN' NAME='" . "user" . $i . "' VALUE=\"" . $username . "\">";
        echo "</TD>";
        echo "</TR>";
    }

    echo "<TR BGCOLOR='#E3E3E3'>";
    echo "<TD ALIGN='center' COLSPAN='4'>";
    echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('add');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Add')."\">&nbsp;&nbsp;";
    if($end<=1) {
        echo "<INPUT TYPE=BUTTON DISABLED VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Edit')."\">&nbsp;&nbsp;";
        echo "<INPUT TYPE=BUTTON DISABLED VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.view')."\">&nbsp;&nbsp;";
        echo "<INPUT TYPE=BUTTON DISABLED VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Delete')."\">";
    }
    else {
        echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('edit');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Edit')."\">&nbsp;&nbsp;";
        echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('view');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.view')."\">&nbsp;&nbsp;";
        echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('delete');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Delete')."\">";
    }
    echo "</TD>";

    if($end<=1) {
        echo "<td ALIGN='center'><INPUT TYPE=BUTTON DISABLED VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Save')."\"></TD>";
    }
    else {
        echo "<td ALIGN='center'><INPUT TYPE=BUTTON ONCLICK =\"changeOp('save');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Save')."\"></TD>";
    }
    echo "</TR>";
    echo "<TR BGCOLOR='#E3E3E3'>";
    echo "<TD ALIGN='center' COLSPAN='5'>";
    echo "<TABLE BORDER='0' CELLSPACING='0' CELLPADDING='2' WIDTH='90%'>";
    echo "<TR>";
    echo "<TD ALIGN='left'>";

    if($begin > 1) {
        echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('First');document.adminform.submit();return false;\" target=\"_self\"> ".$GLOBALS['nof_resources']->get('SS.Admin.Link.FirstPage')."</A>&nbsp;&nbsp;" ;
        echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Prev');document.adminform.submit();return false;\" target=\"_self\"> ".$GLOBALS['nof_resources']->get('SS.Admin.Link.PreviousPage')."</A>" ;
    }
    else {
        echo " ".$GLOBALS['nof_resources']->get('SS.Admin.Link.FirstPage')." &nbsp;&nbsp;" ;
        echo " ".$GLOBALS['nof_resources']->get('SS.Admin.Link.PreviousPage')."" ;
    }

    echo "</TD>";
    echo "<TD ALIGN='right'>";

    if($end < count($lines)) {
        echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Next');document.adminform.submit();return false;\" target=\"_self\">".$GLOBALS['nof_resources']->get('SS.Admin.Link.NextPage')." </A>&nbsp;&nbsp;" ;
        echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Last');document.adminform.submit();return false;\" target=\"_self\">".$GLOBALS['nof_resources']->get('SS.Admin.Link.LastPage')." </A>" ;
    }
    else {
        echo "".$GLOBALS['nof_resources']->get('SS.Admin.Link.NextPage')." &nbsp;&nbsp;";
        echo "".$GLOBALS['nof_resources']->get('SS.Admin.Link.LastPage')." ";
    }

    echo "</TD>";
    echo "</TR>";
    echo "</TABLE>";
    echo "</TD>";
    echo "</TR>";
    echo "</TABLE>";
    echo "</FORM>";
    echo "<script type='text/javascript'>updateAllBox();</script>";
}


function getValidatedUsers($inputArray) {
      global $fieldsDBArray,$ssCommon;
        $validationFlagColPos = count($fieldsDBArray)-1;
        $j=1;
        $outputArray[0]="";
      for( $i=1; $i< count($inputArray); $i++ ) {
                $validationFlag = $ssCommon->getField($validationFlagColPos,$inputArray[$i]);
   if($validationFlag=="true") {
     $outputArray[$j]=$inputArray[$i];
                        $j++;
   }
        }

        return $outputArray;
 }


 function displayValidatedUsersList($begin) {
      global $conf,$fieldsDBArray,$AMCompIdent,$ssCommon;
        //get the whole database in an array
  if(!$lines = @file($conf[$AMCompIdent . "dbPath"])) {
          NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
          exit();

  }
        $linesV = getValidatedUsers($lines);
        if ($begin >= count($linesV)) {
   $begin = $begin -10;
        }
        if ($begin < 1 ) {
          $begin = 1;
        }


        //get column positions of mandatory fields that will be displayed
  $userColPos = $ssCommon->getColumnPos("username");
  $passwordColPos = $ssCommon->getColumnPos("password");
  $emailColPos = $ssCommon->getColumnPos("email");
  $adminStatusColPos = count($fieldsDBArray)-2;
        $validationFlagColPos = count($fieldsDBArray)-1;
        //begin form
      echo "<FORM NAME=\"adminform\"   TARGET=\"_self\" ACTION=\"" . GetServerVariable('PHP_SELF') . "\"  METHOD=\"POST\">";

      echo "<INPUT TYPE=HIDDEN NAME='begin' VALUE='" . $begin . "'>";
      echo "<INPUT TYPE=HIDDEN NAME='mode' VALUE='" . "validusersonly" . "'>";
      echo "<INPUT TYPE='HIDDEN' NAME='pageoperation' value=''>";
      echo "<INPUT TYPE='HIDDEN' NAME='operation' value=''>";



        echo "<TABLE BGCOLOR='#C3C3C3' BORDER='1' CELLPADDING='2' CELLSPACING='5' >";

  echo "<TR BGCOLOR='#E3E3E3'>";
  echo "<TD ALIGN= 'center' COLSPAN='5'>";
  echo "<b>".$GLOBALS['nof_resources']->get('SS.Admin.Title.BrowseUsers')."</b>";
  echo "</TD>";
  echo "</TR>";

      echo "<TR BGCOLOR='#E3E3E3'>";
  echo "<TD COLSPAN='5'>";
  echo "<b>".$GLOBALS['nof_resources']->get('SS.Admin.Text.Show')."</b>";
  echo "<SELECT SIZE=1  NAME=\"showusers\" onChange='modeChanged();' >";
  echo "<OPTION VALUE=\"" . "Show All Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.AllUsers");
  echo "<OPTION SELECTED VALUE=\"" . "Show Validated Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.ActiveUsers");
  echo "<OPTION VALUE=\"" . "Show Invalidated Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.InactiveUsers");
  echo "</SELECT>";
  echo "</TD>";
  echo "</TR>";

  //print the column names
  echo "<TR BGCOLOR='#E3E3E3'>";
  echo "<td>&nbsp;</td>";
  echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$userColPos])) . "</b></TD>";
  echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$emailColPos])) . "</b></TD>";
  echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$adminStatusColPos])) . "</b></TD>";
  echo "<td align='center' nowrap><b>" . ucfirst(strtolower($fieldsDBArray[$validationFlagColPos])) . "&nbsp;"
         .  "<INPUT TYPE='CHECKBOX' NAME='allbox' VALUE = '1' OnClick='allCheck()' >"
                     .  "</b></TD>";
  echo "</TR>";


        $end = min(count($linesV),$begin+10);
        //display 10 users from beginning
      for( $i=$begin; $i< $end; $i++ ) {
                $username = $ssCommon->getField($userColPos,$linesV[$i]);
                $password = $ssCommon->getField($passwordColPos,$linesV[$i]);
                $email = $ssCommon->getField($emailColPos,$linesV[$i]);
                $adminStatus = $ssCommon->getField($adminStatusColPos,$linesV[$i]);
                $validationFlag = $ssCommon->getField($validationFlagColPos,$linesV[$i]);
          echo "<TR BGCOLOR='#E3E3E3'>";
                echo "<td>";
                if($i==$begin) {
                  echo "<input type='radio' name='radiogroup' value=\"" . $username ."\" CHECKED>";
                }
                else {
                  echo "<input type='radio' name='radiogroup' value=\"" . $username ."\">";
                }
                echo "</TD>";
                echo "<td align='left'>";
                echo $username;
                echo "</TD>";
                echo "<td align='left'>";
                echo $email;
                echo "</TD>";
                echo "<td align='center'>";
                echo $adminStatus;
                echo "</TD>";
                echo "<td align='center'>";
            if($validationFlag=="false") {
         echo "<INPUT TYPE='CHECKBOX' NAME='" . "validatebox" . $i . "' VALUE=\"" . $username ."\" OnClick='updateAllBox()'>";

     }
     else {
         echo "<INPUT TYPE='CHECKBOX' NAME='" . "validatebox" . $i . "' VALUE=\"" . $username ."\" CHECKED OnClick='updateAllBox()'>";
     }
                echo "</TD>";
                echo "</TR>";
                echo "<INPUT TYPE='HIDDEN' NAME='" . "user" . $i . "' VALUE=\"" . $username . "\">";

        }


        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<TD ALIGN='center' COLSPAN='4'>";
      echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('add');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Add')."\">&nbsp;&nbsp;";
        if($end<=1) {
        echo "<INPUT TYPE=BUTTON DISABLED VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Edit')."\">&nbsp;&nbsp;";
          echo "<INPUT TYPE=BUTTON DISABLED VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.view')."\">&nbsp;&nbsp;";
        echo "<INPUT TYPE=BUTTON DISABLED VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Delete')."\">";
        }
        else {
        echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('edit');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Edit')."\">&nbsp;&nbsp;";
          echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('view');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.view')."\">&nbsp;&nbsp;";
        echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('delete');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Delete')."\">";
        }

      echo "</TD>";


        if($end<=1) {
   echo "<td ALIGN='center'><INPUT TYPE=BUTTON DISABLED VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Save')."\"></TD>";
  }
        else {
   echo "<td ALIGN='center'><INPUT TYPE=BUTTON ONCLICK =\"changeOp('save');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Save')."\"></TD>";
        }
        echo "</TR>";

        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<TD ALIGN='center' COLSPAN='5'>";
        echo "<TABLE BORDER='0' CELLSPACING='0' CELLPADDING='2' WIDTH='90%'>";
        echo "<TR>";
        echo "<TD ALIGN='left'>";

        if($begin > 1) {
                echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('First');document.adminform.submit();return false;\"> ".$GLOBALS['nof_resources']->get('SS.Admin.Link.FirstPage')."</A>&nbsp;&nbsp;" ;
          echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Prev');document.adminform.submit();return false;\"> ".$GLOBALS['nof_resources']->get('SS.Admin.Link.PreviousPage')."</A>" ;
        }
        else {
                echo " ".$GLOBALS['nof_resources']->get('SS.Admin.Link.FirstPage')." &nbsp;&nbsp;" ;
          echo " ".$GLOBALS['nof_resources']->get('SS.Admin.Link.PreviousPage')."" ;
        }

      echo "</TD>";
        echo "<TD ALIGN='right'>";

      if($end < count($linesV)) {
          echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Next');document.adminform.submit();return false;\">".$GLOBALS['nof_resources']->get('SS.Admin.Link.NextPage')." </A>&nbsp;&nbsp;" ;
          echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Last');document.adminform.submit();return false;\">".$GLOBALS['nof_resources']->get('SS.Admin.Link.LastPage')." </A>" ;
        }
        else {
                echo "".$GLOBALS['nof_resources']->get('SS.Admin.Link.NextPage')." &nbsp;&nbsp;";
          echo "".$GLOBALS['nof_resources']->get('SS.Admin.Link.LastPage')." ";
        }

  echo "</TD>";
  echo "</TR>";
  echo "</TABLE>";
        echo "</TD>";
        echo "</TR>";
        echo "</TABLE>";
        echo "</FORM>";
        echo "<script type='text/javascript'>updateAllBox();</script>";

 }


 function getInvalidatedUsers($inputArray) {
      global $fieldsDBArray,$ssCommon;
        $validationFlagColPos = count($fieldsDBArray)-1;
        $j=1;
        $outputArray[0]="";

      for( $i=1; $i< count($inputArray); $i++ ) {
                $validationFlag = $ssCommon->getField($validationFlagColPos,$inputArray[$i]);
   if($validationFlag=="false") {
     $outputArray[$j]=$inputArray[$i];
                        $j++;
   }
        }

        return $outputArray;
 }


 function displayInvalidatedUsersList($begin) {
      global $conf,$fieldsDBArray,$AMCompIdent,$ssCommon;
        //get the whole database in an array
  if(!$lines = @file($conf[$AMCompIdent . "dbPath"])) {
          NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
          exit();
  }
        $linesV = getInvalidatedUsers($lines);
        if ($begin >= count($linesV)) {
   $begin = $begin -10;
        }

        if ($begin < 1 ) {
          $begin = 1;
        }

        //get column positions of mandatory fields that will be displayed
        $userColPos = $ssCommon->getColumnPos("username");
        $passwordColPos = $ssCommon->getColumnPos("password");
        $emailColPos = $ssCommon->getColumnPos("email");
        $adminStatusColPos = count($fieldsDBArray)-2;
        $validationFlagColPos = count($fieldsDBArray)-1;

        //begin form
      echo "<FORM NAME=\"adminform\"   TARGET=\"_self\" ACTION=\"" . GetServerVariable('PHP_SELF') . "\"  METHOD=\"POST\">";

      echo "<INPUT TYPE=HIDDEN NAME='begin' VALUE='" . $begin . "'>";
      echo "<INPUT TYPE=HIDDEN NAME='mode' VALUE='" . "invalidusersonly" . "'>";
      echo "<INPUT TYPE='HIDDEN' NAME='pageoperation' value=''>";
      echo "<INPUT TYPE='HIDDEN' NAME='operation' value=''>";

      echo "<TABLE BGCOLOR='#C3C3C3' BORDER='1' CELLPADDING='2' CELLSPACING='5'  >";

  echo "<TR BGCOLOR='#E3E3E3'>";
  echo "<TD ALIGN= 'center' COLSPAN='5'>";
  echo "<b>".$GLOBALS['nof_resources']->get('SS.Admin.Title.BrowseUsers')."</b>";
  echo "</TD>";
  echo "</TR>";

        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<TD COLSPAN='5'>";
        echo "<b>".$GLOBALS['nof_resources']->get('SS.Admin.Text.Show')."</b>";
  echo "<SELECT SIZE=1  NAME=\"showusers\" onChange='modeChanged();' >";
        echo "<OPTION  VALUE=\"" . "Show All Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.AllUsers");
        echo "<OPTION VALUE=\"" . "Show Validated Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.ActiveUsers");
        echo "<OPTION SELECTED VALUE=\"" . "Show Invalidated Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.InactiveUsers");
        echo "</SELECT>";
        echo "</TD>";
        echo "</TR>";

        //print the column names
        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<td>&nbsp;</td>";
        echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$userColPos])) . "</b></TD>";
        echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$emailColPos])) . "</b></TD>";
        echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$adminStatusColPos])) . "</b></TD>";
        echo "<td align='center' nowrap><b>" . ucfirst(strtolower($fieldsDBArray[$validationFlagColPos])) . "&nbsp;"
             .  "<INPUT TYPE='CHECKBOX' NAME='allbox' VALUE = '1' OnClick='allCheck()' >"
                        .  "</b></TD>";
  echo "</TR>";

        $end = min(count($linesV),$begin+10);
        //display 10 users from beginning
      for( $i=$begin; $i< $end; $i++ ) {
                $username = $ssCommon->getField($userColPos,$linesV[$i]);
                $email = $ssCommon->getField($emailColPos,$linesV[$i]);
                $adminStatus = $ssCommon->getField($adminStatusColPos,$linesV[$i]);
                $validationFlag = $ssCommon->getField($validationFlagColPos,$linesV[$i]);
          echo "<TR BGCOLOR='#E3E3E3'>";
                echo "<td>";
                if($i==$begin) {
                  echo "<input type='radio' name='radiogroup' value=\"" . $username ."\" CHECKED>";
                }
                else {
                  echo "<input type='radio' name='radiogroup' value=\"" . $username ."\">";
                }
                echo "</TD>";
                echo "<td align='left'>";
                echo $username;
                echo "</TD>";
                echo "<td align='left'>";
                echo $email;
                echo "</TD>";
                echo "<td align='center'>";
                echo $adminStatus;
                echo "</TD>";
                echo "<td align='center'>";
            if($validationFlag=="false") {
         echo "<INPUT TYPE='CHECKBOX' NAME='" . "validatebox" . $i . "' VALUE=\"" . $username ."\" OnClick='updateAllBox()'>";

     }
     else {
         echo "<INPUT TYPE='CHECKBOX' NAME='" . "validatebox" . $i . "' VALUE=\"" . $username ."\" CHECKED OnClick='updateAllBox()'>";
     }
                echo "</TD>";
                echo "</TR>";
                echo "<INPUT TYPE='HIDDEN' NAME='" . "user" . $i . "' VALUE=\"" . $username . "\">";

        }


        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<TD ALIGN='center' COLSPAN='4'>";
      echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('add');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Add')."\">&nbsp;&nbsp;";
        if($end<=1) {
        echo "<INPUT TYPE=BUTTON DISABLED VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Edit')."\">&nbsp;&nbsp;";
          echo "<INPUT TYPE=BUTTON DISABLED VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.view')."\">&nbsp;&nbsp;";
        echo "<INPUT TYPE=BUTTON DISABLED VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Delete')."\">";
        }
        else {
        echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('edit');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Edit')."\">&nbsp;&nbsp;";
          echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('view');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.view')."\">&nbsp;&nbsp;";
        echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('delete');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Delete')."\">";
        }

      echo "</TD>";


        if($end<=1) {
   echo "<td ALIGN='center'><INPUT TYPE=BUTTON DISABLED VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Save')."\"></TD>";
  }
        else {
   echo "<td ALIGN='center'><INPUT TYPE=BUTTON ONCLICK =\"changeOp('save');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Save')."\"></TD>";
        }
        echo "</TR>";

        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<TD ALIGN='center' COLSPAN='5'>";
        echo "<TABLE BORDER='0' CELLSPACING='0' CELLPADDING='2' WIDTH='90%'>";
        echo "<TR>";
        echo "<TD ALIGN='left'>";

        if($begin > 1) {
                echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('First');document.adminform.submit();return false;\"> ".$GLOBALS['nof_resources']->get('SS.Admin.Link.FirstPage')."</A>&nbsp;&nbsp;" ;
          echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Prev');document.adminform.submit();return false;\"> ".$GLOBALS['nof_resources']->get('SS.Admin.Link.PreviousPage')."</A>" ;
        }
        else {
                echo " ".$GLOBALS['nof_resources']->get('SS.Admin.Link.FirstPage')." &nbsp;&nbsp;" ;
          echo " ".$GLOBALS['nof_resources']->get('SS.Admin.Link.PreviousPage')."" ;
        }

      echo "</TD>";
        echo "<TD ALIGN='right'>";

      if($end < count($linesV)) {
          echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Next');document.adminform.submit();return false;\">".$GLOBALS['nof_resources']->get('SS.Admin.Link.NextPage')." </A>&nbsp;&nbsp;" ;
          echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Last');document.adminform.submit();return false;\">".$GLOBALS['nof_resources']->get('SS.Admin.Link.LastPage')." </A>" ;
        }
        else {
                echo "".$GLOBALS['nof_resources']->get('SS.Admin.Link.NextPage')." &nbsp;&nbsp;";
          echo "".$GLOBALS['nof_resources']->get('SS.Admin.Link.LastPage')." ";
        }

  echo "</TD>";
  echo "</TR>";
  echo "</TABLE>";
        echo "</TD>";
        echo "</TR>";
        echo "</TABLE>";
        echo "</FORM>";
        echo "<script type='text/javascript'>updateAllBox();</script>";

 }








 function displayLastUsersList() {
      global $conf,$fieldsDBArray,$AMCompIdent,$ssCommon;
        //get the whole database in an array
  if(!$lines = @file($conf[$AMCompIdent . "dbPath"])) {
          NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
          exit();
  }
        $begin=count($lines) - (count($lines)-1)%10;
        if ($begin < 1 ) {
          $begin = 1;
        }
        if ($begin >= count($lines)) {
   $begin = $begin -10;
        }

        //get column positions of mandatory fields that will be displayed
        $userColPos = $ssCommon->getColumnPos("username");
        $emailColPos = $ssCommon->getColumnPos("email");
        $adminStatusColPos = count($fieldsDBArray)-2;
        $validationFlagColPos = count($fieldsDBArray)-1;


        //begin form
      echo "<FORM NAME=\"adminform\"   TARGET=\"_self\" ACTION=\"" . GetServerVariable('PHP_SELF') . "\"  METHOD=\"POST\">";

      echo "<INPUT TYPE=HIDDEN NAME='begin' VALUE='" . $begin . "'>";
      echo "<INPUT TYPE=HIDDEN NAME='mode' VALUE='" . "naturalorder" . "'>";
      echo "<INPUT TYPE='HIDDEN' NAME='pageoperation' value=''>";
      echo "<INPUT TYPE='HIDDEN' NAME='operation' value=''>";


      echo "<TABLE BGCOLOR='#C3C3C3' BORDER='1' CELLPADDING='2' CELLSPACING='5'  >";

  echo "<TR BGCOLOR='#E3E3E3'>";
  echo "<TD ALIGN= 'center' COLSPAN='5'>";
  echo "<b>".$GLOBALS['nof_resources']->get('SS.Admin.Title.BrowseUsers')."</b>";
  echo "</TD>";
  echo "</TR>";

        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<TD COLSPAN='5'>";
        echo "<b>".$GLOBALS['nof_resources']->get('SS.Admin.Text.Show')."</b>";
  echo "<SELECT SIZE=1  NAME=\"showusers\" onChange='modeChanged();' >";
        echo "<OPTION SELECTED VALUE=\"" . "Show All Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.AllUsers");
        echo "<OPTION VALUE=\"" . "Show Validated Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.ActiveUsers");
        echo "<OPTION VALUE=\"" . "Show Invalidated Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.InactiveUsers");
        echo "</SELECT>";
        echo "</TD>";
        echo "</TR>";

        //print the column names
        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<td>&nbsp;</td>";
        echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$userColPos])) . "</b></TD>";
        echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$emailColPos])) . "</b></TD>";
        echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$adminStatusColPos])) . "</b></TD>";
        echo "<td align='center' nowrap><b>" . ucfirst(strtolower($fieldsDBArray[$validationFlagColPos])) . "&nbsp;"
             .  "<INPUT TYPE='CHECKBOX' NAME='allbox' VALUE = '1' OnClick='allCheck()' >"
                        .  "</b></TD>";
  echo "</TR>";

        $end = min(count($lines),$begin+10);
        //display 10 users from beginning
      for( $i=$begin; $i< $end; $i++ ) {
                $username = $ssCommon->getField($userColPos,$lines[$i]);
                $email = $ssCommon->getField($emailColPos,$lines[$i]);
                $adminStatus = $ssCommon->getField($adminStatusColPos,$lines[$i]);
                $validationFlag = $ssCommon->getField($validationFlagColPos,$lines[$i]);
          echo "<TR BGCOLOR='#E3E3E3'>";
                echo "<td>";
                if($i==$begin) {
                  echo "<input type='radio' name='radiogroup' value=\"" . $username ."\" CHECKED>";
                }
                else {
                  echo "<input type='radio' name='radiogroup' value=\"" . $username ."\">";
                }
                echo "</TD>";
                echo "<td align='left'>";
                echo $username;
                echo "</TD>";
                echo "<td align='left'>";
                echo $email;
                echo "</TD>";
                echo "<td align='center'>";
                echo $adminStatus;
                echo "</TD>";
                echo "<td align='center'>";
            if($validationFlag=="false") {
         echo "<INPUT TYPE='CHECKBOX' NAME='" . "validatebox" . $i . "' VALUE=\"" . $username ."\" OnClick='updateAllBox()'>";

     }
     else {
         echo "<INPUT TYPE='CHECKBOX' NAME='" . "validatebox" . $i . "' VALUE=\"" . $username ."\" CHECKED OnClick='updateAllBox()'>";
     }
                echo "</TD>";
                echo "</TR>";
                echo "<INPUT TYPE='HIDDEN' NAME='" . "user" . $i . "' VALUE=\"" . $username . "\">";

        }

        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<TD ALIGN='center' COLSPAN='4'>";
      echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('add');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Add')."\">&nbsp;&nbsp;";
  echo "<INPUT TYPE=BUTTON  ONCLICK =\"changeOp('edit');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Edit')."\">&nbsp;&nbsp;";
      echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('view');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.view')."\">&nbsp;&nbsp;";
      echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('delete');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Delete')."\">";
      echo "<td ALIGN='center'><INPUT TYPE=BUTTON ONCLICK =\"changeOp('save');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Save')."\"></TD>";
  echo "</TR>";

        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<TD ALIGN='center' COLSPAN='5'>";
        echo "<TABLE BORDER='0' CELLSPACING='0' CELLPADDING='2' WIDTH='90%'>";
        echo "<TR>";
        echo "<TD ALIGN='left'>";

        if($begin > 1) {
                echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('First');document.adminform.submit();return false;\"> ".$GLOBALS['nof_resources']->get('SS.Admin.Link.FirstPage')."</A>&nbsp;&nbsp;" ;
          echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Prev');document.adminform.submit();return false;\"> ".$GLOBALS['nof_resources']->get('SS.Admin.Link.PreviousPage')."</A>" ;
        }
        else {
                echo " ".$GLOBALS['nof_resources']->get('SS.Admin.Link.FirstPage')." &nbsp;&nbsp;" ;
          echo " ".$GLOBALS['nof_resources']->get('SS.Admin.Link.PreviousPage')."" ;
        }

      echo "</TD>";
        echo "<TD ALIGN='right'>";

      if($end < count($lines)) {
          echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Next');document.adminform.submit();return false;\">".$GLOBALS['nof_resources']->get('SS.Admin.Link.NextPage')." </A>&nbsp;&nbsp;" ;
          echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Last');document.adminform.submit();return false;\">".$GLOBALS['nof_resources']->get('SS.Admin.Link.LastPage')." </A>" ;
        }
        else {
                echo "".$GLOBALS['nof_resources']->get('SS.Admin.Link.NextPage')." &nbsp;&nbsp;";
          echo "".$GLOBALS['nof_resources']->get('SS.Admin.Link.LastPage')." ";
        }

  echo "</TD>";
  echo "</TR>";
  echo "</TABLE>";
        echo "</TD>";
        echo "</TR>";
        echo "</TABLE>";
        echo "</FORM>";
        echo "<script type='text/javascript'>updateAllBox();</script>";



 }


 function displayValidatedLastUsersList() {
      global $conf,$fieldsDBArray,$AMCompIdent,$ssCommon;
        //get the whole database in an array
  if(!$lines = file($conf[$AMCompIdent . "dbPath"])) {
          NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
          exit();
  }
        $linesV = getValidatedUsers($lines);
        $begin=count($linesV) - (count($linesV)-1)%10;
        if ($begin < 1 ) {
          $begin = 1;
        }
        if ($begin >= count($linesV)) {
   $begin = $begin -10;
        }

                //get column positions of mandatory fields that will be displayed
  $userColPos = $ssCommon->getColumnPos("username");
  $emailColPos = $ssCommon->getColumnPos("email");
  $adminStatusColPos = count($fieldsDBArray)-2;
        $validationFlagColPos = count($fieldsDBArray)-1;

        //begin form
      echo "<FORM NAME=\"adminform\"   TARGET=\"_self\" ACTION=\"" . GetServerVariable('PHP_SELF') . "\"  METHOD=\"POST\">";

      echo "<INPUT TYPE=HIDDEN NAME='begin' VALUE='" . $begin . "'>";
      echo "<INPUT TYPE=HIDDEN NAME='mode' VALUE='" . "validusersonly" . "'>";
      echo "<INPUT TYPE='HIDDEN' NAME='pageoperation' value=''>";
      echo "<INPUT TYPE='HIDDEN' NAME='operation' value=''>";

      echo "<TABLE BGCOLOR='#C3C3C3' BORDER='1' CELLPADDING='2' CELLSPACING='5'  >";

  echo "<TR BGCOLOR='#E3E3E3'>";
  echo "<TD ALIGN= 'center' COLSPAN='5'>";
  echo "<b>".$GLOBALS['nof_resources']->get('SS.Admin.Title.BrowseUsers')."</b>";
  echo "</TD>";
  echo "</TR>";

        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<TD COLSPAN='5'>";
        echo "<b>".$GLOBALS['nof_resources']->get('SS.Admin.Text.Show')."</b>";
  echo "<SELECT SIZE=1  NAME=\"showusers\" onChange='modeChanged();' >";
        echo "<OPTION VALUE=\"" . "Show All Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.AllUsers");
        echo "<OPTION SELECTED VALUE=\"" . "Show Validated Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.ActiveUsers");
        echo "<OPTION VALUE=\"" . "Show Invalidated Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.InactiveUsers");
        echo "</SELECT>";
        echo "</TD>";
        echo "</TR>";

        //print the column names
        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<td>&nbsp;</td>";
        echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$userColPos])) . "</b></TD>";
        echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$emailColPos])) . "</b></TD>";
        echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$adminStatusColPos])) . "</b></TD>";
        echo "<td align='center' nowrap><b>" . ucfirst(strtolower($fieldsDBArray[$validationFlagColPos])) . "&nbsp;"
             .  "<INPUT TYPE='CHECKBOX' NAME='allbox' VALUE = '1' OnClick='allCheck()' >"
                        .  "</b></TD>";
  echo "</TR>";

        $end = min(count($linesV),$begin+10);
        //display 10 users from beginning
      for( $i=$begin; $i< $end; $i++ ) {
                $username = $ssCommon->getField($userColPos,$linesV[$i]);
                $email = $ssCommon->getField($emailColPos,$linesV[$i]);
                $adminStatus = $ssCommon->getField($adminStatusColPos,$linesV[$i]);
                $validationFlag = $ssCommon->getField($validationFlagColPos,$linesV[$i]);
          echo "<TR BGCOLOR='#E3E3E3'>";
                echo "<td>";
                if($i==$begin) {
                  echo "<input type='radio' name='radiogroup' value=\"" . $username ."\" CHECKED>";
                }
                else {
                  echo "<input type='radio' name='radiogroup' value=\"" . $username ."\">";
                }
                echo "</TD>";
                echo "<td align='left'>";
                echo $username;
                echo "</TD>";
                echo "<td align='left'>";
                echo $email;
                echo "</TD>";
                echo "<td align='center'>";
                echo $adminStatus;
                echo "</TD>";
                echo "<td align='center'>";
            if($validationFlag=="false") {
         echo "<INPUT TYPE='CHECKBOX' NAME='" . "validatebox" . $i . "' VALUE=\"" . $username ."\" OnClick='updateAllBox()'>";

     }
     else {
         echo "<INPUT TYPE='CHECKBOX' NAME='" . "validatebox" . $i . "' VALUE=\"" . $username ."\" CHECKED OnClick='updateAllBox()'>";
     }
                echo "</TD>";
                echo "</TR>";
                echo "<INPUT TYPE='HIDDEN' NAME='" . "user" . $i . "' VALUE=\"" . $username . "\">";

        }
        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<TD ALIGN='center' COLSPAN='4'>";
      echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('add');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Add')."\">&nbsp;&nbsp;";
  echo "<INPUT TYPE=BUTTON  ONCLICK =\"changeOp('edit');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Edit')."\">&nbsp;&nbsp;";
      echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('view');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.view')."\">&nbsp;&nbsp;";
      echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('delete');document.adminform.submit();\"  VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Delete')."\">";
      echo "<td ALIGN='center'><INPUT TYPE=BUTTON ONCLICK =\"changeOp('save');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Save')."\"></TD>";
  echo "</TR>";

        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<TD ALIGN='center' COLSPAN='5'>";
        echo "<TABLE BORDER='0' CELLSPACING='0' CELLPADDING='2' WIDTH='90%'>";
        echo "<TR>";
        echo "<TD ALIGN='left'>";

        if($begin > 1) {
                echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('First');document.adminform.submit();return false;\"> ".$GLOBALS['nof_resources']->get('SS.Admin.Link.FirstPage')."</A>&nbsp;&nbsp;" ;
          echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Prev');document.adminform.submit();return false;\"> ".$GLOBALS['nof_resources']->get('SS.Admin.Link.PreviousPage')."</A>" ;
        }
        else {
                echo " ".$GLOBALS['nof_resources']->get('SS.Admin.Link.FirstPage')." &nbsp;&nbsp;" ;
          echo " ".$GLOBALS['nof_resources']->get('SS.Admin.Link.PreviousPage')."" ;
        }

      echo "</TD>";
        echo "<TD ALIGN='right'>";

      if($end < count($linesV)) {
          echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Next');document.adminform.submit();return false;\">".$GLOBALS['nof_resources']->get('SS.Admin.Link.NextPage')." </A>&nbsp;&nbsp;" ;
          echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Last');document.adminform.submit();return false;\">".$GLOBALS['nof_resources']->get('SS.Admin.Link.LastPage')." </A>" ;
        }
        else {
                echo "".$GLOBALS['nof_resources']->get('SS.Admin.Link.NextPage')." &nbsp;&nbsp;";
          echo "".$GLOBALS['nof_resources']->get('SS.Admin.Link.LastPage')." ";
        }

  echo "</TD>";
  echo "</TR>";
  echo "</TABLE>";
        echo "</TD>";
        echo "</TR>";
        echo "</TABLE>";
        echo "</FORM>";
        echo "<script type='text/javascript'>updateAllBox();</script>";
 }


 function displayInvalidatedLastUsersList() {
      global $conf,$fieldsDBArray,$AMCompIdent,$ssCommon;
        //get the whole database in an array
  if(!$lines = file($conf[$AMCompIdent . "dbPath"])) {
          NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
          exit();
  }
        $linesV = getInvalidatedUsers($lines);
        $begin=count($linesV) - (count($linesV)-1)%10;
        if ($begin < 1 ) {
          $begin = 1;
        }
        if ($begin >= count($linesV)) {
   $begin = $begin -10;
        }

        //get column positions of mandatory fields that will be displayed
  $userColPos = $ssCommon->getColumnPos("username");
  $passwordColPos = $ssCommon->getColumnPos("password");
  $emailColPos = $ssCommon->getColumnPos("email");
  $adminStatusColPos = count($fieldsDBArray)-2;
  $validationFlagColPos = count($fieldsDBArray)-1;

        //begin form
      echo "<FORM NAME=\"adminform\"   TARGET=\"_self\" ACTION=\"" . GetServerVariable('PHP_SELF') . "\"  METHOD=\"POST\">";

      echo "<INPUT TYPE=HIDDEN NAME='begin' VALUE='" . $begin . "'>";
      echo "<INPUT TYPE=HIDDEN NAME='mode' VALUE='" . "invalidusersonly" . "'>";
      echo "<INPUT TYPE='HIDDEN' NAME='pageoperation' value=''>";
      echo "<INPUT TYPE='HIDDEN' NAME='operation' value=''>";

      echo "<TABLE BGCOLOR='#C3C3C3' BORDER='1' CELLPADDING='2' CELLSPACING='5'  >";

  echo "<TR BGCOLOR='#E3E3E3'>";
  echo "<TD ALIGN= 'center' COLSPAN='5'>";
  echo "<b>".$GLOBALS['nof_resources']->get('SS.Admin.Title.BrowseUsers')."</b>";
  echo "</TD>";
  echo "</TR>";

        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<TD COLSPAN='5'>";
        echo "<b>".$GLOBALS['nof_resources']->get('SS.Admin.Text.Show')."</b>";
  echo "<SELECT SIZE=1  NAME=\"showusers\" onChange='modeChanged();' >";
        echo "<OPTION VALUE=\"" . "Show All Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.AllUsers");
        echo "<OPTION VALUE=\"" . "Show Validated Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.ActiveUsers");
        echo "<OPTION SELECTED VALUE=\"" . "Show Invalidated Users" . "\">" . $GLOBALS['nof_resources']->get("SS.Admin.Text.InactiveUsers");
        echo "</SELECT>";
        echo "</TD>";
        echo "</TR>";

        //print the column names
        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<td>&nbsp;</td>";
        echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$userColPos])) . "</b></TD>";
        echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$emailColPos])) . "</b></TD>";
        echo "<td align='center'><b>" . ucfirst(strtolower($fieldsDBArray[$adminStatusColPos])) . "</b></TD>";
        echo "<td align='center' nowrap><b>" . ucfirst(strtolower($fieldsDBArray[$validationFlagColPos])) . "&nbsp;"
             .  "<INPUT TYPE='CHECKBOX' NAME='allbox' VALUE = '1' OnClick='allCheck()' >"
                        .  "</b></TD>";
  echo "</TR>";


        $end = min(count($linesV),$begin+10);
        //display 10 users from beginning
      for( $i=$begin; $i< $end; $i++ ) {
                $username = $ssCommon->getField($userColPos,$linesV[$i]);
                $email = $ssCommon->getField($emailColPos,$linesV[$i]);
                $adminStatus = $ssCommon->getField($adminStatusColPos,$linesV[$i]);
                $validationFlag = $ssCommon->getField($validationFlagColPos,$linesV[$i]);
          echo "<TR BGCOLOR='#E3E3E3'>";
                echo "<td>";
                if($i==$begin) {
                  echo "<input type='radio' name='radiogroup' value=\"" . $username ."\" CHECKED>";
                }
                else {
                  echo "<input type='radio' name='radiogroup' value=\"" . $username ."\">";
                }
                echo "</TD>";
                echo "<td align='left'>";
                echo $username;
                echo "</TD>";
                echo "<td align='left'>";
                echo $email;
                echo "</TD>";
                echo "<td align='center'>";
                echo $adminStatus;
                echo "</TD>";
                echo "<td align='center'>";
            if($validationFlag=="false") {
         echo "<INPUT TYPE='CHECKBOX' NAME='" . "validatebox" . $i . "' VALUE=\"" . $username ."\" OnClick='updateAllBox()'>";

     }
     else {
         echo "<INPUT TYPE='CHECKBOX' NAME='" . "validatebox" . $i . "' VALUE=\"" . $username ."\" CHECKED OnClick='updateAllBox()'>";
     }
                echo "</TD>";
                echo "</TR>";
                echo "<INPUT TYPE='HIDDEN' NAME='" . "user" . $i . "' VALUE=\"" . $username . "\">";

        }
        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<TD ALIGN='center' COLSPAN='4'>";
      echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('add');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Add')."\">&nbsp;&nbsp;";
  echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('edit');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Edit')."\">&nbsp;&nbsp;";
      echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('view');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.view')."\">&nbsp;&nbsp;";
      echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('delete');document.adminform.submit();\"  VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Delete')."\">";
      echo "<td ALIGN='center'><INPUT TYPE=BUTTON ONCLICK =\"changeOp('save');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Save')."\"></TD>";
  echo "</TR>";

        echo "<TR BGCOLOR='#E3E3E3'>";
        echo "<TD ALIGN='center' COLSPAN='5'>";
        echo "<TABLE BORDER='0' CELLSPACING='0' CELLPADDING='2' WIDTH='90%'>";
        echo "<TR>";
        echo "<TD ALIGN='left'>";

        if($begin > 1) {
                echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('First');document.adminform.submit();return false;\"> ".$GLOBALS['nof_resources']->get('SS.Admin.Link.FirstPage')."</A>&nbsp;&nbsp;" ;
          echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Prev');document.adminform.submit();return false;\"> ".$GLOBALS['nof_resources']->get('SS.Admin.Link.PreviousPage')."</A>" ;
        }
        else {
                echo " ".$GLOBALS['nof_resources']->get('SS.Admin.Link.FirstPage')." &nbsp;&nbsp;" ;
          echo " ".$GLOBALS['nof_resources']->get('SS.Admin.Link.PreviousPage')."" ;
        }

      echo "</TD>";
        echo "<TD ALIGN='right'>";

      if($end < count($linesV)) {
          echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Next');document.adminform.submit();return false;\">".$GLOBALS['nof_resources']->get('SS.Admin.Link.NextPage')." </A>&nbsp;&nbsp;" ;
          echo "<A HREF=\"javascript: void(0)\" onClick=\"changePage('Last');document.adminform.submit();return false;\">".$GLOBALS['nof_resources']->get('SS.Admin.Link.LastPage')." </A>" ;
        }
        else {
                echo "".$GLOBALS['nof_resources']->get('SS.Admin.Link.NextPage')." &nbsp;&nbsp;";
          echo "".$GLOBALS['nof_resources']->get('SS.Admin.Link.LastPage')." ";
        }

  echo "</TD>";
  echo "</TR>";
  echo "</TABLE>";
        echo "</TD>";
        echo "</TR>";
        echo "</TABLE>";
        echo "</FORM>";
        echo "<script type='text/javascript'>updateAllBox();</script>";




 }




function displayEditUserDetails($userToBeEdited,$begin,$mode) {

  global $conf,$cgiDir,$fieldsDBArray,$AMCompIdent,$ssCommon;

        //get the db in an array
    if(!$lines = @file($conf[$AMCompIdent . "dbPath"])) {
          NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
          exit();
        }

        //begin form
      echo "<FORM NAME=\"adminform\"   TARGET=\"_self\" ACTION=\"" . GetServerVariable('PHP_SELF') . "\"  METHOD=\"POST\">";

        //get column number of username
        $userColPos = $ssCommon->getColumnPos("username");

        //look for the user in the db array and get his details
    for( $i=1; $i<count($lines); $i++ ) {
                $username=$ssCommon->getField($userColPos,$lines[$i]);
          if($username==$userToBeEdited) {
                        $userDetailsArray=split( "\"\,\"", substr($lines[$i], 1, strlen($lines[$i])-3) );
          }
    }

        //begin table
      echo "<TABLE BGCOLOR='#C3C3C3' BORDER=1 CELLPADDING=0  CELLSPACING=5  WIDTH='100%'>";

      echo "<TR BGCOLOR='#E3E3E3'>";
  echo "<TD ALIGN= 'left' COLSPAN='2'>";
  echo "<b>".$GLOBALS['nof_resources']->get('SS.Admin.Title.Edituser').": </b> $userToBeEdited";
  echo "</TD>";
  echo "</TR>";

        //printout his details
        for($i=0;$i<count($fieldsDBArray);$i++) {
      echo "<TR BGCOLOR='#E3E3E3'>";
            echo "<td><b>" . ucfirst(strtolower($fieldsDBArray[$i])) . "</b></td>";
                if($i < count($fieldsDBArray)-2 ) {
       echo "<td><INPUT TYPE='TEXT' SIZE=30 NAME='" . $fieldsDBArray[$i] . "'"
                                                    . "VALUE=\"" . $ssCommon->escapeString($userDetailsArray[$i]) . "\"></td>";
                }
                else {  //display admin stat and validation flag only as select boxes
     echo "<td><SELECT SIZE=1 NAME='" . $fieldsDBArray[$i] . "'>";
                        if($ssCommon->escapeString($userDetailsArray[$i])=="false") {
                          echo "<OPTION SELECTED VALUE=\"" . "false" . "\">" . "false"."</OPTION>";
                          echo "<OPTION VALUE=\"" . "true" . "\">" . "true"."</OPTION>";
                        }
                        else {
                          echo "<OPTION SELECTED VALUE=\"" . "true" . "\">" . "true"."</OPTION>";
                          echo "<OPTION VALUE=\"" . "false" . "\">" . "false"."</OPTION>";

                        }
                        echo "</SELECT></TD>";
                }
   echo "</tr>";
        }


        echo "<TR BGCOLOR='#E3E3E3'>";
    echo "<td ALIGN='CENTER' COLSPAN='2'>";
    echo "<INPUT TYPE='HIDDEN' NAME='operation' value=''>";
    echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('saveuser');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Saveuser')."\">&nbsp;&nbsp;";
    echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('reset');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Reset')."\">&nbsp;&nbsp;";
    echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('back');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Back')."\">&nbsp;&nbsp;";
    echo "</TD>";
        echo "</TR>";




        echo "<tr><td><INPUT TYPE=HIDDEN NAME='begin' VALUE='" . $begin . "'>";
        echo "<INPUT TYPE=HIDDEN NAME='mode' VALUE='" . $mode . "'>";
        echo "<INPUT TYPE=HIDDEN NAME='usertobeedited' VALUE=\"" . $userToBeEdited . "\"></td></tr>";

    echo "</TABLE>";
    echo "</FORM>";

}



function displayAddUser($begin,$mode) {

  global $conf,$cgiDir,$fieldsDBArray;


        //begin form
      echo "<FORM NAME=\"adminform\"   TARGET=\"_self\" ACTION=\"" . GetServerVariable('PHP_SELF') . "\"  METHOD=\"POST\">";


        //begin table
      echo "<TABLE BGCOLOR='#C3C3C3' BORDER='1' CELLPADDING='0'  CELLSPACING='5'   WIDTH='100%'>";

      echo "<TR BGCOLOR='#E3E3E3'>";
  echo "<TD ALIGN= 'center' COLSPAN='2'>";
  echo "<b>".$GLOBALS['nof_resources']->get('SS.Admin.Title.Adduser')."</b>";
  echo "</TD>";
  echo "</TR>";

        //printout his details
        for($i=0;$i<count($fieldsDBArray);$i++) {
      echo "<TR BGCOLOR='#E3E3E3'>";
            echo "<td><b>" . ucfirst(strtolower($fieldsDBArray[$i])) . "</b></td>";
                if($i < count($fieldsDBArray)-2 ) {
       echo "<td><INPUT TYPE='TEXT' SIZE='30' NAME='" . $fieldsDBArray[$i] . "'"
                                                    . "VALUE=''></td>";
                }
                else {  //display admin stat and validation flag only as select boxes
     echo "<td><SELECT SIZE=1 NAME='" . $fieldsDBArray[$i] . "'>";
                          echo "<OPTION SELECTED VALUE=\"" . "false" . "\">" . "false"."</OPTION>";
                          echo "<OPTION VALUE=\"" . "true" . "\">" . "true"."</OPTION>";
                        echo "</SELECT></TD>";
                }
   echo "</tr>";
        }


        echo "<TR BGCOLOR='#E3E3E3'>";
    echo "<TD ALIGN='CENTER' COLSPAN='2'>";
    echo "<INPUT TYPE='HIDDEN' NAME='operation' value=''>";
    echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('adduser');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Adduser')."\">&nbsp;&nbsp;&nbsp;&nbsp;";
    echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('back');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Back')."\">&nbsp;&nbsp;&nbsp;&nbsp;";
    echo "</TD>";
        echo "</TR>";



        echo "<tr><td><INPUT TYPE=HIDDEN NAME='begin' VALUE='" . $begin . "'>";
        echo "<INPUT TYPE=HIDDEN NAME='mode' VALUE='" . $mode . "'></td></tr>";

    echo "</TABLE>";
    echo "</FORM>";

}


function displayAddUserError($begin,$mode,$errorField) {

	global $conf,$cgiDir,$fieldsDBArray,$ssCommon;

	$errorFields = explode(",",$errorField);


	//begin form
	echo "<FORM NAME=\"adminform\"   TARGET=\"_self\" ACTION=\"" . GetServerVariable('PHP_SELF') . "\"  METHOD=\"POST\">";


	//begin table
	echo "<TABLE BGCOLOR=#C3C3C3 BORDER=1 CELLPADDING=0  CELLSPACING=5  WIDTH=100%>";

	echo "<TR BGCOLOR='#E3E3E3'>";
	echo "<TD ALIGN= 'center' COLSPAN='3'>";
	echo "<b>".$GLOBALS['nof_resources']->get('SS.Admin.Title.Adduser')."</b>";
	echo "</TD>";
	echo "</TR>";

	//printout his details
	for($i=0;$i<count($fieldsDBArray);$i++) {
		echo "<TR BGCOLOR='#E3E3E3'>";
		echo "<td><b>" . ucfirst(strtolower($fieldsDBArray[$i])) . "</b></td>";

		if(in_array($fieldsDBArray[$i],$errorFields)) {
			echo "<td><b>&nbsp;<font color='red' >!</font>&nbsp;</b></td>";
		}
		else {
			echo "<td>&nbsp;&nbsp;&nbsp;</td>";
		}
		if($i < count($fieldsDBArray)-2 ) {
			echo "<td><INPUT TYPE='TEXT' SIZE='30' NAME='" . $fieldsDBArray[$i] . "'"
				. "VALUE=\"" . $ssCommon->escapeString(stripslashes(GetPostVariable($fieldsDBArray[$i]))) ."\"></td>";
		}
		else {  //display admin stat and validation flag only as select boxes
			echo "<td><SELECT SIZE=1 NAME='" . $fieldsDBArray[$i] . "'>";
			echo "<OPTION VALUE=\"" . "false" . "\"";
			if (GetPostVariable($fieldsDBArray[$i]) == 'false') echo " selected";
			echo ">" . "false"."</OPTION>";
			echo "<OPTION VALUE=\"" . "true" . "\"";
			if (GetPostVariable($fieldsDBArray[$i]) == 'true') echo " selected";
			echo">" . "true"."</OPTION>";
			echo "</SELECT></TD>";
		}
		echo "</tr>";
	}



	echo "<TR BGCOLOR='#E3E3E3'>";
    echo "<td ALIGN='CENTER' COLSPAN='3'>";
    echo "<INPUT TYPE='HIDDEN' NAME='operation' value=''>";
    echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('adduser');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Adduser')."\">&nbsp;&nbsp;&nbsp;";
    echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('back');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Back')."\">&nbsp;&nbsp;&nbsp;";
    ECHO "</TD>";
        echo "</TR>";


        echo "<tr><td><INPUT TYPE=HIDDEN NAME='begin' VALUE='" . $begin . "'>";
        echo "<INPUT TYPE=HIDDEN NAME='mode' VALUE='" . $mode . "'></td></tr>";

    echo "</TABLE>";
    echo "</FORM>";
}

function displayEditUserDetailsError($userToBeEdited,$begin,$mode,$errorField) {

	global $conf,$cgiDir,$fieldsDBArray,$AMCompIdent,$ssCommon;

	$errorFields = explode(",",$errorField);

	//get the db in an array
    if(!$lines = @file($conf[$AMCompIdent . "dbPath"])) {
          NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
          exit();
    }

	//begin form
	echo "<FORM NAME=\"adminform\"   TARGET=\"_self\" ACTION=\"" . GetServerVariable('PHP_SELF') . "\"  METHOD=\"POST\">";

	//begin table
	echo "<TABLE BGCOLOR='#C3C3C3' BORDER='1' CELLPADDING='0'  CELLSPACING='5'  WIDTH='100%'>";

	echo "<TR BGCOLOR='#E3E3E3'>";
	echo "<TD ALIGN= 'left' COLSPAN='3'>";
	echo "<b>".$GLOBALS['nof_resources']->get('SS.Admin.Title.Edituser').":</b> $userToBeEdited";
	echo "</TD>";
	echo "</TR>";

	//get column number of username
	$userColPos = $ssCommon->getColumnPos("username");

	//look for the user in the db array and get his details
    for( $i=1; $i<count($lines); $i++ ) {
		$username=$ssCommon->getField($userColPos,$lines[$i]);
		if($username==$userToBeEdited) {
			$userDetailsArray=split( "\"\,\"", $lines[$i] );
		}
    }


	//printout his details
	for($i=0;$i<count($fieldsDBArray);$i++) {
		echo "<TR BGCOLOR='#E3E3E3'>";
		echo "<td><b>" . ucfirst(strtolower($fieldsDBArray[$i])) . "</b></td>";

		if(in_array($fieldsDBArray[$i],$errorFields)) {
			echo "<td><b>&nbsp;<font color='red' >!</font>&nbsp;</b></td>";
		}
		else {
			echo "<td>&nbsp;&nbsp;&nbsp;</td>";
		}

		if($i < count($fieldsDBArray)-2 ) {
			echo "<td><INPUT TYPE='TEXT' SIZE='30' NAME='" . $fieldsDBArray[$i] . "'"
				. "VALUE=\"" . $ssCommon->escapeString(stripslashes(GetPostVariable($fieldsDBArray[$i]))) . "\"></td>";
		}
		else {  //display admin stat and validation flag only as select boxes
			echo "<td><SELECT SIZE=1 NAME='" . $fieldsDBArray[$i] . "'>";
			if($ssCommon->escapeString($userDetailsArray[$i])=="false") {
				echo "<OPTION SELECTED VALUE=\"" . "false" . "\">" . "false"."</OPTION>";
				echo "<OPTION VALUE=\"" . "true" . "\">" . "true"."</OPTION>";
			}
			else {
				echo "<OPTION SELECTED VALUE=\"" . "true" . "\">" . "true"."</OPTION>";
				echo "<OPTION VALUE=\"" . "false" . "\">" . "false"."</OPTION>";
			}
			echo "</SELECT></TD>";
		}
		echo "</tr>";
	}


	echo "<TR  BGCOLOR='#E3E3E3'>";
    echo "<TD ALIGN='CENTER' COLSPAN='3'>";
    echo "<INPUT TYPE='HIDDEN' NAME='operation' value=''>";
    echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('saveuser');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Saveuser')."\">&nbsp;&nbsp;&nbsp;";
    echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('reset');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.admin.button.Reset')."\">&nbsp;&nbsp;&nbsp;";
    echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('back');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Back')."\">&nbsp;&nbsp;&nbsp;";
    echo "</TD>";
        echo "</TR>";



        echo "<tr><td><INPUT TYPE=HIDDEN NAME='begin' VALUE='" . $begin . "'>";
        echo "<INPUT TYPE=HIDDEN NAME='mode' VALUE='" . $mode . "'>";
        echo "<INPUT TYPE=HIDDEN NAME='usertobeedited' VALUE=\"" . $userToBeEdited . "\"></td></tr>";
    echo "</TABLE>";
    echo "</FORM>";
}





function displayUserDetails($userToBeViewed,$begin,$mode) {

  global $conf,$cgiDir,$fieldsDBArray,$AMCompIdent,$ssCommon;

        //get the db in an array
    if(!$lines = @file($conf[$AMCompIdent . "dbPath"])) {
          NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
          exit();
    }

        //begin form
      echo "<FORM NAME=\"adminform\"   TARGET=\"_self\" ACTION=\"" . GetServerVariable('PHP_SELF') . "\"  METHOD=\"POST\">";

      //begin table
  echo "<TABLE BGCOLOR='#C3C3C3' BORDER='1' CELLPADDING='0'  CELLSPACING='5'  WIDTH='100%'>";

  echo "<TR BGCOLOR='#E3E3E3'>";
  echo "<TD ALIGN= 'left' COLSPAN='2'>";
  echo "<b>".$GLOBALS['nof_resources']->get('SS.Admin.Title.ViewUser').":</b> $userToBeViewed";
  echo "</TD>";
  echo "</TR>";

        //get column number of username
        $userColPos = $ssCommon->getColumnPos("username");

        //look for the user in the db array and get his details
    for( $i=1; $i<count($lines); $i++ ) {
                $username=$ssCommon->getField($userColPos,$lines[$i]);
          if($username==$userToBeViewed) {
                        $userDetailsArray = split( "\"\,\"", substr($lines[$i], 1, strlen($lines[$i])-3) );
          }
    }


        //printout his details
        for($i=0;$i<count($fieldsDBArray);$i++) {
      echo "<TR BGCOLOR='#E3E3E3'>";
            echo "<td><b>" . ucfirst(strtolower($fieldsDBArray[$i])) . "</b></td>";

                if($i < count($fieldsDBArray)-2 ) {
                                //display fields,disable editing of all fields
       //echo "<td><INPUT TYPE='TEXT' SIZE='30' DISABLED NAME='" . $fieldsDBArray[$i] . "'"
                                //                    . "VALUE=\"" . $ssCommon->cleanField($userDetailsArray[$i]) . "\"></td>";
      echo "<td>" . $userDetailsArray[$i] . "</td>";
                }
                else {  //display admin stat and validation flag only as disabled select boxes
     echo "<td><SELECT DISABLED SIZE=1 NAME='" . $fieldsDBArray[$i] . "'>";
                        if($userDetailsArray[$i]=="false") {
                          echo "<OPTION SELECTED VALUE=\"" . "false" . "\">" . "false"."</OPTION>";
                          echo "<OPTION VALUE=\"" . "true" . "\">" . "true"."</OPTION>";
                        }
                        else {
                          echo "<OPTION SELECTED VALUE=\"" . "true" . "\">" . "true"."</OPTION>";
                          echo "<OPTION VALUE=\"" . "false" . "\">" . "false"."</OPTION>";

                        }
                        echo "</SELECT></TD>";
                }
   echo "</tr>";
        }


    echo "<TR BGCOLOR='#E3E3E3'>";
    echo "<td ALIGN='CENTER' COLSPAN='2'>";
    echo "<INPUT TYPE='HIDDEN' NAME='operation' value=''>";
    echo "<INPUT TYPE=BUTTON ONCLICK =\"changeOp('back');document.adminform.submit();\" VALUE=\"".$GLOBALS['nof_resources']->get('SS.Admin.Button.Back')."\">";
    echo "</TD>";
    echo "</TR>";

        echo "<tr><td><INPUT TYPE=HIDDEN NAME='begin' VALUE='" . $begin . "'>";
        echo "<INPUT TYPE=HIDDEN NAME='mode' VALUE='" . $mode . "'></td></tr>";

    echo "</TABLE>";
    echo "</FORM>";

}





/*
* create an account
*/
function addUser() {
  global $conf,$requiredForPassRetv,$fieldsDBArray,$AMCompIdent,$ssCommon;


    if (!$FILE = @fopen($conf[$AMCompIdent . "dbPath"], 'ab')) {
        NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
        exit();
    }

  for($i=0;$i<count($fieldsDBArray);$i++) {
        if(!isset($entry)) {
                $entry = $ssCommon->preprocess(GetPostVariable($fieldsDBArray[$i]));
        }
        else {
                $entry = $entry . "," .  $ssCommon->preprocess(GetPostVariable($fieldsDBArray[$i]));
        }

  }


    if (!fputs($FILE, "$entry\n")) {
        NOF_throwError(502,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
        exit();
    }

  fclose($FILE);


}




function changeUserDetails($usernameToBeChanged) {

    global $conf,$fieldsDBArray,$AMCompIdent,$ssCommon;

    //get the db in an array
    if(!$lines = @file($conf[$AMCompIdent . "dbPath"])) {
        NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
        exit();
    }

        //make the changed row entry
    $entry="";
    for($i=0;$i<count($fieldsDBArray);$i++) {
          if($entry=="") {
                  $entry = $ssCommon->preprocess(GetPostVariable($fieldsDBArray[$i]));
          }
          else {
                  $entry = $entry . "," .  $ssCommon->preprocess(GetPostVariable($fieldsDBArray[$i]));
          }

    }

        //get the username colum position
    $usernameColPos = $ssCommon->getColumnPos("username");
        //look for the username in the db and change the entry
    for( $i=1; $i<count($lines); $i++ ) {
   $username=$ssCommon->getField($usernameColPos,$lines[$i]);
          if($username==$usernameToBeChanged) {
     $lines[$i]=$entry . "\n";
          }
    }

        //open db file for writing
    if (!$FILE = @fopen($conf[$AMCompIdent . "dbPath"], 'wb')) {
        NOF_throwError(502,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
        exit();
    } else {
        //dump into the db
        for($i=0;$i<count($lines);$i++) {
            if (!fputs($FILE, $lines[$i]) ){
                NOF_throwError(502,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
                exit();
            }
        }
        fclose($FILE);
    }
}




/*
* function to see if all required parameters were filled
*/
function checkIfRequiredUnfilled() {
  global $conf,$SNCompIdent,$ssCommon;

  $errorFoundFlag=FALSE;
  $postArr = GetPostVariable('');
  reset($postArr);
  while (list($field, $value) = each ($postArr)) {
   $property = $SNCompIdent . $field . ".errorevent.required.active";
   if(  preg_match("/^\s*$/",$postArr[$field]) && $conf[$property]=="true" ) {
      $label=$SNCompIdent . $field . ".errorevent.required.message" ;
      $ssCommon->clubError($label,$field);
      $errorFoundFlag=TRUE;
   }
  }

  return $errorFoundFlag;
}




/*
* function to check if username is already taken
*/
function checkIfLoginAlreadyExists() {
  global $conf,$AMCompIdent,$SNCompIdent,$ssCommon;

    if(!$lines = @file($conf[$AMCompIdent . "dbPath"])) {
        NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$AMCompIdent . "dbPath"]),"{2}"=>NOF_mapPath(getcwd())));
        exit();
    }

  $userColPos = $ssCommon->getColumnPos("username");
  for ( $i=1; $i<count($lines); $i++ ) {
    $userName=$ssCommon->getField($userColPos,$lines[$i]);
    SetPostVariable( "username", $ssCommon->cleanField(trim(GetPostVariable("username"))) );
    if(strtolower($userName)==strtolower(GetPostVariable("username"))) {
      $ssCommon->clubError($SNCompIdent . "username.errorevent.alreadyexists.message","username");
      return TRUE;
    }
  }
  return FALSE;

}




function checkIfFieldsLengthInvalid() {
        global $conf,$SNCompIdent,$ssCommon;

        $postArr = GetPostVariable('');
        reset($postArr);
        $errorFoundFlag=FALSE;
        while( list($field,$value)= each($postArr) ) {
                $property=$SNCompIdent . $field . ".errorevent.short.active" ;
                if ( isset($conf[$property]) ) {
                    if ($conf[$property]=="true" ) {
                        $property=$SNCompIdent . $field . ".errorevent.short.minimumlength";
                        if( preg_match("/^[-+]{0,1}\d+$/" ,$conf[$property] ) ) {
                                if( strlen($postArr[$field]) < $conf[$property] ) {
                                        $ssCommon->clubError($SNCompIdent . $field . ".errorevent.short.message", $field);
                                        $errorFoundFlag=TRUE;
                                }
                        }
                    }
                }
                $property=$SNCompIdent . $field . ".errorevent.long.active" ;
                if ( isset($conf[$property]) ) {
                        $property=$SNCompIdent .  $field . ".errorevent.long.maximumlength";
                        if( preg_match("/^\+{0,1}\d+$/" ,$conf[$property] ) ) {
                                if( strlen($postArr[$field]) > $conf[$property] ) {
                                        $ssCommon->clubError($SNCompIdent . $field . ".errorevent.long.message", $field);
                                        $errorFoundFlag=TRUE;
                                }
                        }
                }
        }
        return $errorFoundFlag;
}




function displayError($errorLabel,$errorField) {
         global $conf;
        //get the error labels and associated fields in arrays
        $errorLabels = explode(",",$errorLabel);
        //for each error
		echo '<ul>';
        for($i=0;$i< count($errorLabels);$i++) {
			//$property = "[SIGNUP]" . $errorLabels[$i];
			$property = $errorLabels[$i];
			echo "<li><font color='red'>" . stripslashes($conf[$property]) . "</font></li>";
  		}
		echo '</ul>';
}




function displaySuccessMessage($messg) {
        echo "<TABLE BORDER='0'  WIDTH='100%'>";
  echo "<TR><TD NOWRAP><font color='blue'>"  . stripslashes($messg) . "</font></TD></TR>";
        echo "</TABLE>";

}



function sendValidationStatusChangeEmail($email,$username,$password,$status) {

  global $conf,$cgiDir,$AMCompIdent;

    //get email template in a single line
    $tplline = $conf["[EMAIL]Body"];

    $tplline = preg_replace("/\{0\}/" , $username, $tplline);
    $tplline = preg_replace("/\{1\}/" , $password, $tplline);
    $tplline = preg_replace("/\{2\}/" , $status, $tplline);

    $tplline = str_replace("\\n", "<br>", $tplline);
    $tplline = str_replace("\\", "", $tplline);
/*
    if (NOF_fileExists($cgiDir . 'ss_mailer.php')) {
        include_once($cgiDir . "ss_mailer.php");
    } else {
        exit();
    }
*/
    $mail = new PHPMailer();
    $mail->From = $conf[$AMCompIdent."emailFromAddress"];
    $mail->FromName = $conf[$AMCompIdent."emailFromAddress"];
    $mail->Host = $conf[$AMCompIdent."emailServer"];
    $mail->Port = $conf[$AMCompIdent."emailServerPort"];
    $mail->SMTPDebug = false;
    $mail->SMTPAuth = false;
    if ( $conf[$AMCompIdent."smtpAuth"] == "true" ) {
        $mail->SMTPAuth = true;
        $mail->Username = $conf[$AMCompIdent."smtpUsername"];
        $mail->Password = $conf[$AMCompIdent."smtpPassword"];
    }
    $mail->SMTPSecure = (isset($conf[$AMCompIdent."smtpSSL"])&&$conf[$AMCompIdent."smtpSSL"]=="true")?'ssl':'';
    $mail->Mailer = "smtp";
    $mail->Subject = $conf["[EMAIL]Subject"];
    $mail->CharSet = "UTF-8";
    $mail->IsHTML = true;
    $mail->AddAddress($email,$email);
    $mail->Body = $tplline;
    $mail->AltBody = str_replace('<br>','\n',$tplline);

    // send e-mail
    if (!$mail->Send()) {
        echo "<!-- ErrorInfo(smtp): ".$mail->ErrorInfo."-->";
        if ( $mail->SMTPAuth ) {
            NOF_throwError(201,array("{1}"=>$email,"{2}"=>$conf[$AMCompIdent."emailFromAddress"],"{3}"=>$conf[$AMCompIdent."emailServer"].":".$conf[$AMCompIdent."emailServerPort"]));
            exit();
        } else {
            $mail->Mailer = "mail";
            if (!$mail->Send()) {
                echo "<!-- ErrorInfo: ".$mail->ErrorInfo."-->";
                NOF_throwError(201,array("{1}"=>$email,"{2}"=>$conf[$AMCompIdent."emailFromAddress"],"{3}"=>$conf[$AMCompIdent."emailServer"].":".$conf[$AMCompIdent."emailServerPort"]));
                exit();
            }
        }
    }


    // done! clean up
    $mail->ClearAddresses();
    $mail->ClearAttachments();
    return true;

}

function getSignupID($AMDBPath) {
    global $conf;

    reset($conf);

    while (list($key,$value) = each($conf)) {
      if(preg_match("/^signup\.(\d+)\.dbPath$/",$key,$match)) {
       if($value==$AMDBPath) {
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
