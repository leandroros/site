<?php

//first we define a number of variables to store the data from each element
$name="";
$css = "";
$active="";
$errorname="";
$username="";
$password="";
$message="";
$errorcss="";
$minimumlength="";
$maximumlength="";
$currentPage="";
$nextPage="";
$errorMark="";
$dbPath="";
$accessDeniedPage="";
$unauthorizedmessage="";
$authenticationneededmessage="";
$dbColumn="";
$emailToAddress="";
$emailFromAddress="";
$emailServer="";
$emailServerPort="";
$smtpAuth = "";
$smtpUsername = "";
$smtpPassword = "";
$smtpSSL = "";
$automaticvalidation="";
$notifyOnSignup="";
$id="";
$componentname="";
$language="";

//holds the name of the current element
$CurrentElement="";
$CurrentElementData="";

//array to hold all the xml data
$conf=array();





/*The start Element Handler
*This is where we store the element name, currently being parsed, in $CurrentElement.
*This is also where we get the attribute, if any.
*/
function ss_startElement($parser,$name,$attr){
    $GLOBALS['CurrentElement']=$name;
    if(strcmp($name,"Component")==0){
        $GLOBALS['id']=$attr["id"];
        $GLOBALS['componentname']=$attr["name"];
    }
}

/*
*The end Element Handler
*/
function ss_endElement($parser,$name){
    $GLOBALS['CurrentElementData'] = '';
    
    $errorEventProperties= array(  'active',
        'message',
        'errorcss',
        'minimumlength',
        'maximumlength'
    );

    $adminuserProperties=  array( 'username',
        'password',
    );

    $generalProperties =   array( 'currentPage',
        'nextPage',
        'errorMark',
        'dbPath',
        'accessDeniedPage',
        'automaticvalidation',
        'notifyOnSignup',
        'unauthorizedmessage',
        'authenticationneededmessage',
        'emailToAddress',
        'emailFromAddress',
        'emailServer',
        'emailServerPort',
        'smtpAuth',
        'smtpUsername',
        'smtpPassword',
        'smtpSSL',
        'language'
    );


    /*
    If the element being parsed is an ErrorEvent it means that the
    parser has completed parsing ErrorEvent. We can then store
    the data in our array $conf[ ]
    */
	if($name=="errorevent"){
		foreach($errorEventProperties as $element){
	   		if($element=="minimumlength" && trim($GLOBALS['errorname'])!="short") {
	      		continue;
	     	}
	     	if($element=="maximumlength" && trim($GLOBALS['errorname'])!="long") {
	      		continue;
	     	}
	     	if($element=="maximumsize" && trim($GLOBALS['errorname'])!="filetoolarge") {
	      		continue;
	     	}
	     	if($element=="fileextension" && trim($GLOBALS['errorname'])!="invalidextension") {
	      		continue;
	     	}
	     	$GLOBALS['conf'][trim($GLOBALS['componentname']) . "." .trim($GLOBALS['id']) . "." .trim($GLOBALS['name']) . ".errorevent."  . trim($GLOBALS['errorname']) . "." . trim($element)] = trim($GLOBALS[$element]);
	   }

		//After storing the data we reset our globals to
		//hold a new ErrorEvent

		$GLOBALS['active']   ="";
		$GLOBALS['errorname']   ="";
		$GLOBALS['message']   ="";
		$GLOBALS['errorcss']     ="";
		$GLOBALS['maximumlength']  ="";
		$GLOBALS['minimumlength']  ="";
	}

	if($name=="adminuser") {
		foreach($adminuserProperties as $element) {
			$GLOBALS['conf'][trim($GLOBALS['componentname']) . "." .trim($GLOBALS['id']) . "." . $name . "." . trim($element)] = trim($GLOBALS[$element]);
		}
		$GLOBALS['username']   ="";
		$GLOBALS['password']   ="";
	}

	if($name=="dbColumn") {
		if(!empty($GLOBALS['conf'][trim($GLOBALS['componentname']) . "." .trim($GLOBALS['id']) . "." .trim($name)])) {
			$GLOBALS['conf'][trim($GLOBALS['componentname']) . "." .trim($GLOBALS['id']) . "." .trim($name)] .= "," . trim($GLOBALS[$name]);
		}
		else {
			$GLOBALS['conf'][trim($GLOBALS['componentname']) . "." .trim($GLOBALS['id']) . "." .trim($name)] = trim($GLOBALS[$name]);
		}
		$GLOBALS[$name]="";
	}

        //After parsing a field we reset the rest of the globals.
        if($name=="field"){
          $GLOBALS['name']="";
          $GLOBALS['css']="";
        }
        //After parsing component we reset th rest of globals
        if($name=="Component"){
           $GLOBALS['currentPage']     ="";
		   $GLOBALS['nextPage']     ="";
		   $GLOBALS['errorMark']    ="";
		   $GLOBALS['dbPath']     ="";
		   $GLOBALS['accessDeniedPage']     ="";
		   $GLOBALS['dbColumn']     ="";
		   $GLOBALS['emailToAddress']     ="";
		   $GLOBALS['emailFromAddress']     ="";
		   $GLOBALS['emailServer']     ="";
		   $GLOBALS['emailServerPort']     ="";
		   $GLOBALS['automaticvalidation']   ="";
		   $GLOBALS['unauthorizedmessage']   = "";
		   $GLOBALS['authenticationneededmessage'] = "";
           	$GLOBALS['notifyOnSignup'] = "";
		   $GLOBALS['language'] = "";
           	$GLOBALS['smtpAuth'] = "";
           	$GLOBALS['smtpUsername'] = "";
           	$GLOBALS['smtpPassword'] = "";
           	$GLOBALS['smtpSSL'] = "";
        }

        if(in_array($name,$generalProperties)) {
			$GLOBALS['conf'][trim($GLOBALS['componentname']) . "." .trim($GLOBALS['id']) . "." .trim($name)]=trim($GLOBALS[$name]);
        }

}






/*The character data Handler
*Depending on what the CurrentElement is,
*the handler assigns the value to the appropriate variable
*/
function ss_characterData($parser, $data) {
        $elements = array(  'name',
        'css',
        'active',
        'errorname',
        'errorcss',
        'message',
        'minimumlength',
        'maximumlength',
        'currentPage',
        'nextPage',
        'errorMark',
        'dbPath',
        'accessDeniedPage',
        'emailToAddress',
        'emailFromAddress',
        'emailServer',
        'emailServerPort',
        'smtpAuth',
        'smtpUsername',
        'smtpPassword',
        'smtpSSL',
        'automaticvalidation',
        'notifyOnSignup',
        'unauthorizedmessage',
        'authenticationneededmessage',
        'username',
        'password',
        'dbColumn',
        'language'
        );
        
        $GLOBALS['CurrentElementData'] .= $data;

        foreach ($elements as $element) {
            if ($GLOBALS["CurrentElement"] == $element) {
                $GLOBALS[$element] = $GLOBALS['CurrentElementData'];
            }
        }
 }





/*This is where the actual parsing is going on.
*parseFile() parses the xml document and return an array
*with the data we asked for.
*/
function ss_parseXmlFile($xmlPropertyFile){
    global $conf;

    ///Creating the xml parser
    $xml_parser=xml_parser_create("UTF-8");

    //Register the handlers
    xml_set_element_handler($xml_parser,"ss_startElement","ss_endElement");
    xml_set_character_data_handler($xml_parser,"ss_characterData");

    //Disables case-folding.
    xml_parser_set_option($xml_parser,XML_OPTION_CASE_FOLDING,false);

    //Open the xml file and feed it to the parser in 4k blocks
	if ( ! $dataArr = file($xmlPropertyFile) ) {
        $errorMessage = "Cannot open  XML property file for reading";
        NOF_throwError(601,array("{1}"=>NOF_mapPath($xmlPropertyFile),"{2}"=>getcwd()));
        exit();
	}
	$data = implode("", $dataArr);

/*
    if(!($data=@file_get_contents($xmlPropertyFile))){
        $errorMessage = "Cannot open  XML property file for reading";
        NOF_throwError(601,array("{1}"=>NOF_mapPath($xmlPropertyFile),"{2}"=>getcwd()));
        exit();
    }
*/

    $data = str_replace('<?php exit(); ?>','',$data);
    if(!xml_parse($xml_parser,$data)){
        $errorMessage = "XML error at Line "
                        .  xml_get_current_line_number($xml_parser)
                        . " Column "
                        . xml_get_current_column_number($xml_parser);
        NOF_throwError(600,array("{1}"=>NOF_mapPath($xmlPropertyFile),"{2}"=>getcwd()));
        exit();
    }

    //We free the parser
    xml_parser_free($xml_parser);

    //returns the array
    return $conf;
}



?>
