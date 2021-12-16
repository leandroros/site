<?php
  // set error reporting
  error_reporting(E_ALL & ~E_NOTICE);

  if ( GetPostVariable('PARAMS') != '' && GetPostVariable('VALUES') != '' && GetPostVariable('FORMNAME') != '' ) {
	echo "<script type='text/javascript'>";

        $params = split("," , decodeData(GetPostVariable('PARAMS')));
        $paramValues = split(",;,;" , decodeData(GetPostVariable('VALUES')));
		$formName = GetPostVariable('FORMNAME');

        for($i=0;$i<count($params);$i++) {
        //delete newline character
        //have no idea the following regexp does not work to match newline
        //$paramValues[$i] = preg_replace("/\n/","",$paramValues[$i]);
        $paramValues[$i] = preg_replace("/\s/"," ",$paramValues[$i]);

	echo (
         "if( document." . $formName . ".elements['" . $params[$i] . "']!=null ) {"	. "\n"
        . " if( !document." . $formName . ".elements['" . $params[$i] . "'].length ) {" . "\n"
        . "   if( document." . $formName . ".elements['" . $params[$i] . "'].type.indexOf('text')!=-1 ) {" . "\n"
        . "    document." . $formName . ".elements['" . $params[$i] . "'].value=" . '"' . $paramValues[$i] . '";'  . "\n"
        . "   }" . "\n"
        . "   if( document." . $formName . ".elements['" . $params[$i] . "'].type=='radio' ) { " . "\n"
        . "    document." . $formName . ".elements['" . $params[$i] . "'].checked=true;"  . "\n"
        . "   }" . "\n"
        . " }" . "\n"
        . " else {" . "\n"
        . "  if( document." . $formName . ".elements['" . $params[$i] . "'][0].type=='radio' ) {" . "\n"
        . "   for(i=0;i<document." . $formName . ".elements['" . $params[$i] . "'].length;i++ ) {" . "\n"
        . "    if(document." . $formName . ".elements['" . $params[$i] . "'][i].value==" . '"' . $paramValues[$i] . '") {' . "\n"
        . "     document." . $formName . ".elements['" . $params[$i] . "'][i].checked=true;" . "\n"
        . "    }" . "\n"
        . "   }" . "\n"
        . "  }" . "\n"
        . " }" . "\n"
        . "}" . "\n"


        . "if(document." . $formName . ".elements['" . $params[$i] . "[]']" . "!=null ) {" . "\n"
        . " if(!document." . $formName . ".elements['" . $params[$i] . "[]']" . ".length ) {" . "\n"
        . "  if(document." . $formName . ".elements['" . $params[$i] . "[]']" . ".type=='checkbox') {" ."\n"
	. "   document." . $formName .".elements['" . $params[$i] . "[]']" . ".checked=true;" . "\n"
	. "  }" ."\n"
	. " }" . "\n"
	. " else {" . "\n"
        . "  if(document." . $formName . ".elements['" . $params[$i] . "[]'][0]". ".type=='checkbox')  {" ."\n"
	. "   for(j=0;j<document." . $formName . ".elements['" . $params[$i] . "[]']" . ".length;j++) {" . "\n"
	. "    if(document." . $formName . ".elements['" . $params[$i] . "[]'][j]" . ".value==" . '"' . $paramValues[$i] . '") {' . "\n"
	. "     document." . $formName . ".elements['" . $params[$i] . "[]'][j]" . ".checked=true;" . "\n"
	. "    }" . "\n"
	. "   }" . "\n"
	. "  }" . "\n"
	. " }" . "\n"
	. "}" . "\n"


	. "if(document." . $formName . ".elements['" . $params[$i] . "[]']" . "!=null ) {" . "\n"
	. " if(document." . $formName . ".elements['" . $params[$i] . "[]']" . ".type!=null ) {" . "\n"
	. "  if(document." . $formName . ".elements['" . $params[$i] . "[]']" . ".type.indexOf('select')!=-1 ) {" . "\n"
	. "   for(i=0;i<document.". $formName . ".elements['" . $params[$i] . "[]']" . ".options.length;i++) {" . "\n"
	. "    if(document." . $formName . ".elements['" . $params[$i] . "[]']" . ".options[i].value==" . '"' . $paramValues[$i] . '") {' . "\n"
	. "     document." . $formName . ".elements['" . $params[$i] . "[]']" . ".options[i].selected=true;" . "\n"
	. "    }". "\n"
	. "   }". "\n"
	. "  }". "\n"
	. " }". "\n"
	. "}". "\n"

	);

        }
        echo "</script>";
  }



?>
