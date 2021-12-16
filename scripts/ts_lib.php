<?php
if (strpos(strtolower($GLOBALS['nof_langFile']),'de.properties')) {
    $GLOBALS['nof_locale'] = 'de';
} else {
    $GLOBALS['nof_locale'] = 'en';
}

function tstimgresize($imgroot, $img, $maxw, $maxh)		// img_path, img, maxw, maxh
{
			$size=@GetImageSize($imgroot.$img);
			if($size[0]/$maxw>$size[1]/$maxh){
				if ( $size[0]/$maxw >1 ) { $scale=$size[0]/$maxw; } else { $scale=1; }
			} else {
				if ( $size[1]/$maxh >1 ) { $scale=$size[1]/$maxh; } else { $scale=1; }
			}
			if ($scale==0) $scale=1;
			$wid = (int)$size[0]/$scale ;
			$hei = (int)$size[1]/$scale ;
			return "<img src=\"$imgroot$img\" width=\"$wid\" height=\"$hei\" border=\"0\" alt=\"\">";
}

function tstdisplaypages($offset,$numberoflines,$itemnsnumber,$howmany,$cssStyle){

	echo '<span class="nof_'.$cssStyle.'_text"><span class="nof_'.$cssStyle.'_navigation">'
		.$GLOBALS['nof_resources']->get('ts.Admin.Text.Pages').':&nbsp;';
	$numberofpages=ceil($numberoflines/$itemnsnumber);
	if($offset!=0){
		if(($offset%$itemnsnumber)==0){
			$curentpage=$offset/$itemnsnumber;
		}else{
			$curentpage=floor($offset/$itemnsnumber)+1;
		}
	}else{
		$curentpage=1;
	}
	$aux =floor(($howmany)/2);
	if($curentpage<=$aux+1)	{
				$i = 0;
				} else {
					$i = $curentpage-$aux-1;
				}
			if($numberofpages <= $howmany) {
				$i=0;
			} else
				if(($numberofpages - $curentpage) <= $aux - 1){
					$i = $numberofpages-$howmany;
				}
	$j=1;

	while($j<=$howmany and $i<=$numberofpages-1){
		if(($i+1)==$curentpage){
			$pg=$i+1;
			echo $pg."&nbsp;";
		}else{
			$pg=$i+1;
			$off=$i*$itemnsnumber+1;

			echo '<a class="nof_'.$cssStyle.'_link" href="'.encodeURI(GetServerVariable('PHP_SELF')).'?offset='.$off.
				'" target="_self"><span class="nof_'.$cssStyle.'_navigationLink">'.$pg.'</span></a>&nbsp;';
		}
		$i++;
		$j++;
	}
	echo "(".$numberofpages.")</span></span>";
}

function tstgetdb($filepath,$valid){
	global $fields_array;
	$lines_array=array();
	if(!$FILE = @fopen($filepath, 'r')){
            $sysErr = "Cannot open database file <b>".$filepath."</b> for writing.<br>" .
                   "The directory <b>".dirname($filepath)."</b> should exist and be writable by the web server user.<br>".
                   "Also the file <b>".basename($filepath)."</b> should be writable by the web server user.";
            NOF_throwError(500,array("{1}"=>NOF_mapPath($filepath),"{2}"=>dirname($filepath)));
	}else{
		$fields=fgets($FILE, 1024);
		$fields_array=explode(";:;",$fields);
		$validation_pos="";
		foreach($fields_array as $key => $value){
			if(trim($value)=="validation"){
				$validation_pos=$key;
			}
		}
		while (!feof ($FILE)) {
			$line=fgets($FILE, 2048);
			if($line!=""){
				$arr_line=explode(";:;",$line);
				if($valid==1){
					if(trim($arr_line[$validation_pos])=="true"){
						array_push($lines_array,$arr_line);
					}
				}else{
					array_push($lines_array,$arr_line);
				}
			}
		}
	}
	fclose($FILE);

	$lines_array=array_reverse($lines_array);

	return $lines_array;
}

//deprecated since AU4 de
function tststartElement($parser,$name,$attr){
	global $xmlIdComp;
	global $xmlNameField;

	if(strcmp($name,'Component') == 0){
		$GLOBALS['XML_Component'] = $name;
		$GLOBALS['XML_id'] = $attr['id'];
		$GLOBALS['XML_componentname'] = $attr['name'];
	} else {
		$GLOBALS['XML_Field'] = $name;
		if($GLOBALS['XML_Component'] == 'Component' && $xmlIdComp == $GLOBALS['XML_id'] && $xmlNameField == $name) {
			$GLOBALS['XML_found'] = '1';
		} else {
			$GLOBALS['XML_found'] = '0';
		}
	}
}

//deprecated since AU4 de
function tstendElement($parser,$name){
	global $xmlIdComp;
	global $xmlNameField;
	if(strcmp($name,'Component') == 0){
		$GLOBALS['XML_Component'] = '';
	}
}

//deprecated since AU4 de
function tstcharacterData($parser, $data) {
	global $xml_parser;
    if($GLOBALS['XML_found'] == '1' && $GLOBALS['XML_result'] == ''){
		$GLOBALS['XML_result'] =  $data;
	}
 }

//deprecated since AU4 de
function tstXMLGetPropertyByID($xmlPropertyFile, $p_xmlIdComp, $p_xmlNameField){
	global $xmlIdComp;
	global $xmlNameField;
	global $xml_parser;
	$xmlIdComp = $p_xmlIdComp;
	$xmlNameField = $p_xmlNameField;

	$GLOBALS['XML_Field'] = '';
	$GLOBALS['XML_Component'] = '';
	$GLOBALS['XML_id'] = '';
	$GLOBALS['XML_result'] = '';
	$GLOBALS['XML_found'] = '0';

	$xml_parser=xml_parser_create('ISO-5589-1');

	xml_set_element_handler($xml_parser,"tststartElement","tstendElement");
	xml_set_character_data_handler($xml_parser,"tstcharacterData");

	xml_parser_set_option($xml_parser,XML_OPTION_CASE_FOLDING,false);

	//Open the xml file and feed it to the parser in 4k blocks
   	//if(!($data=@file_get_contents($xmlPropertyFile))){
   	if(!($a_data=@file($xmlPropertyFile))){
            NOF_throwError(601,array("{1}"=>NOF_mapPath($xmlPropertyFile),"{2}"=>dirname($xmlPropertyFile)));
            exit();
   	}
   	$data = "";
   	foreach ($a_data as $a_data_line){
   		$data .= $a_data_line;
   	}
    $data = str_replace('<?php exit(); ?>','',$data);
    if(!@xml_parse($xml_parser, $data)){
              NOF_throwError(600,array("{1}"=>NOF_mapPath($xmlPropertyFile),"{2}"=>dirname($xmlPropertyFile)));
    }

 	//We free the parser
	xml_parser_free($xml_parser);

	//returns the array
	return $GLOBALS['XML_result'];

}


$CurrentTagName = '';
$CurrentTagData = '';
/*
*The start Element Handler
*/
function ts_startElement($parser, $name, $attr){
    $GLOBALS['CurrentTagName'] = '';
    if ( strcmp($name,"Component")==0 ) {
        $GLOBALS['lib_componentid']=$attr["id"];
        $GLOBALS['lib_componentname']=$attr["name"];
    }
}

/*
*The end Element Handler
*/
function ts_endElement($parser,$name){
    if ( $name != 'Component' And $name != 'Components' ) {
        $GLOBALS['conf'][trim($GLOBALS['lib_componentname']) . "." .trim($GLOBALS['lib_componentid']) . "." .trim($name)] = trim($GLOBALS['CurrentTagData']);
    }
    $GLOBALS['CurrentTagName'] = '';
    $GLOBALS['CurrentTagData'] = '';
}


/*The character data Handler
*Depending on what the CurrentElement is,
*the handler assigns the value to the appropriate variable
*/
function ts_characterData($parser, $data) {
    if ( $GLOBALS['CurrentTagName'] != 'Component' And $GLOBALS['CurrentTagName'] != 'Components' ) {
        $GLOBALS['CurrentTagData'] .= $data;
    }
}

/*This is where the actual parsing is going on.
*parseFile() parses the xml document and return an array
*with the data we asked for.
*/
function ts_parseXmlFile($xmlPropertyFile){
    global $conf;

    ///Creating the xml parser
    $xml_parser=xml_parser_create("UTF-8");

    //Register the handlers
    xml_set_element_handler($xml_parser,'ts_startElement','ts_endElement');
    xml_set_character_data_handler($xml_parser,'ts_characterData');

    //Disables case-folding.
    xml_parser_set_option($xml_parser,XML_OPTION_CASE_FOLDING,false);

    //Open the xml file and feed it to the parser in 4k blocks
    if ( ! $dataArr = file($xmlPropertyFile) ) {
        $errorMessage = "Cannot open  XML property file for reading";
        NOF_throwError(601,array("{1}"=>NOF_mapPath($xmlPropertyFile),"{2}"=>getcwd()));
        exit();
    }
    $data = implode("", $dataArr);

    $data = str_replace('<?php exit(); ?>','',$data);
    if (!xml_parse($xml_parser,$data)){
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
