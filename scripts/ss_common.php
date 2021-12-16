<?php

$ssCommon = new SSCommon();
class SSCommon {


    /*
    * common2s.php: functions commonly used by other scripts
    */

    /*
    *function to club error label and
    *http params for which erroroccurred
    */
    function clubError($error_label,$error_field) {

        global $errorField,$errorLabel;

        if ($errorLabel=="") {
                $errorLabel=$error_label;
        }
        else {
                $parts=array($errorLabel,$error_label);
                $errorLabel=implode(",",$parts);
        }

        if ($errorField=="") {
                $errorField=$error_field;
        }
        else {
                $parts=array($errorField,$error_field);
                $errorField = implode(",",$parts);
        }
    }




    /*
    * given a path related to an html page, make it relative to cgi-bin
    */
    function makeRelativeToCgiBin($path) {
        $path=preg_replace("/^(\.\.\/){1,}/", "", $path);
        $path=preg_replace("/^(\.\/)/", "", $path);
        $path= "../" . $path;

        return $path;
    }




    /*
    *function to read email templates
    */
    function readEmailTemplate($filePath,$kind,$compIdent='') {

        global $conf;

        if(!$lines = @file($filePath)) {
            $sysErr="File: \"" . $filePath . "\" could not be read.<br>" .
            "The path is either invalid or right permissions<br>" .
            "have not been set on the file.";
            NOF_throwError(501,array("{1}"=>NOF_mapPath($filePath)));
            exit();
        }
        $flag=1;
        for($i=0;$i<count($lines);$i++) {
            if( preg_match("/\=/",$lines[$i]) && !preg_match("/^\s*\#/", $lines[$i]) ){
                    list($name,$value) = split("=",$lines[$i]);
                    $name = trim($name);
                    $name = $kind . $name;
                    $value = trim($value);
                    $conf[$name] = $value;
                    $flag=0;
            }
            if($flag==1){
                $conf[$name].=$lines[$i];
            }
            if($flag==0){
                $flag=1;
            }
        }
    }





    /*
    * function that returns true if the first line of userdetails.db file
    * is same as the first line of db file meaning that db can be appended.
    * it returns false if the DB file does not exist or if first line of UD file (column headings)
    * does not match first line of DB which means the db has to be created/overwritten
    */
    function checkIfDBmatch($compIdent) {

        global $conf,$firstLineUD;

        if($conf[$compIdent . "dbPath"] == ""){
            NOF_throwError(602,array("{1}"=>$nof_suiteName));
            exit();
        }

        // Check if the DB file does not exist at all, meaning
        // that no users have signed up yet

        if( !file_exists($conf[$compIdent . "dbPath"]) || filesize ($conf[$compIdent . "dbPath"]) == 0 ) {
            return FALSE;
        }

    clearstatcache ();
    // Check if the file is readable.
    if (is_readable($conf[$compIdent . "dbPath"])) {
        $lines = @file($conf[$compIdent . "dbPath"]);
    }
    else {
        NOF_throwError(501,array("{1}"=>NOF_mapPath($conf[$compIdent . "dbPath"]),"{2}"=>dirname($conf[$compIdent . "dbPath"])));
        exit();
    }
    

    
    // Check if the file is empty. For the case the file is empty It
    // means I need to return FALSE to write the csv heading row.
    if (count($lines) == 0) {
        return FALSE;
        exit();
    }
    

    // if the file exist, is readable and it has the heading row, 
    // it means we start to validate the existing heading row.
    
        // Get the first line of the DB file

        

            // If first line of DB path (column headings) is
            // not the same as first line of userdetails.db file
            // meaning that user re-published the site with different
            // signup fields

            if(trim($lines[0])!=trim($firstLineUD)) {
                return FALSE;
            }
            return TRUE;
        
    }




    /*
    *  preprocess a data element before putting it in DB
    */
    function preprocess($arg) {

            //take off leading and trailing spaces
            $arg= trim($arg);

            //delete newline character
            $arg = preg_replace("/\s/"," ",$arg);

            //take off quotes from field
            $arg=str_replace('\"', "", $arg);

      //take off backslashes
      $arg = preg_replace('/(\\\\)/', "" , $arg);

            //take off leading and trailing commas
            //$arg=preg_replace("/\,+$/", "" , $arg);
            //$arg=preg_replace("/^\,+/", "" , $arg);

            //surround with quotes;
            $arg = '"' . $arg . '"';

            return $arg;
    }




    /*
    * function to get first line of DB and
    * then get the column names from that
    * line
    */
    function getExpectedDBFields($compIdent) {

            global $conf,$fieldsDBArray,$firstLineUD;
            /*
            $lines = file($conf["[CONF]userdetailspath"]);
            $firstLineUD = trim($lines[0]);
            */
            $firstLineUD = trim($conf[$compIdent . "dbColumn"]);
            $fieldsDBArray = explode("," , $firstLineUD);
            //array_pop($fieldsDBArray);
            //array_pop($fieldsDBArray);
    }





    /*
    * function to get column position, given its column name
    */
    function getColumnPos($fieldName) {

            global $fieldsDBArray;
            $pos=-1;
            $fieldNameUQ=preg_quote($fieldName);

            for ($i=0;$i<count($fieldsDBArray);$i++) {
                    if( preg_match("/^$fieldNameUQ$/i",$fieldsDBArray[$i]) ){
                            $pos=$i;
                            return $pos;
                    }
            }

            return $pos;
    }





    /*
    * function to get an element from a row of DB given
    * its column number
    */
    function getField($colNum,$rowData) {

            $userDetailsArray = split( "\"\,\"", $rowData );

            return $this->cleanField($userDetailsArray[$colNum]);
    }



    /*
    * function to clean a element obtained from DB off " and \
    */
    function cleanField($field) {

            $field = preg_replace("/\"/" , "" , $field);
      //take off backslashes
      $field = preg_replace('/(\\\\)/', "" , $field);

            $field = trim($field);

            return $field;

    }

    //repalce " with the html code for it; it's used to display the string in an form element value
    function escapeString($str) {
          $str = str_replace('"' , '&quot;' , $str);
          $str = preg_replace('/\r\n/' , '\n' , $str);

          return $str;
}

/*
    * function  to check if email was valid
    */
function toUTF8($string) {
 if ($this->isUTF8($string)) {
     return $string;
 } else {
     return utf8_encode($string);
 }
          }

function isUTF8($string) {
    return (preg_match('/^([\x00-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xec][\x80-\xbf]{2}|\xed[\x80-\x9f][\x80-\xbf]|[\xee-\xef][\x80-\xbf]{2}|f0[\x90-\xbf][\x80-\xbf]{2}|[\xf1-\xf3][\x80-\xbf]{3}|\xf4[\x80-\x8f][\x80-\xbf]{2})*$/',$string) === 1);
    }

function checkIfEmailInvalid($email,$componentId) {
   $latin_a = "AaAaAaCcCcCcCcDdÐdEeEeEeEeEeGgGgGgGgHhHhIiIiIiIiIi??JjKk?LlLlLl??LlNnNnNn???OoOoOoŒœRrRrRrSsSsSsŠšTtTtTtUuUuUuUuUuUuWwYyŸZzZzŽž?";
   $latin_suplement="ŠŽšžŸ¡ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöùúûüýÿ";
   $extraChars = $latin_a.$latin_suplement;
   $email = $this->toUTF8($email);
//   if (preg_match('/^[^@]+@[a-z0-9'.$extraChars.']+(([\.-][a-z0-9'.$extraChars.'])*[a-z0-9'.$extraChars.']*)*\.([a-z]{2,}|[0-9]{1,})$/si', $email) === 1) {
   if (preg_match('/^[^@]+@([a-z'.$extraChars.'0-9]+([\-]+[a-z'.$extraChars.'0-9]+)*\.)+([a-z]{2,}|[0-9]{1,})$/si', $email) === 1) {
        return false;
   }
    $this->clubError($componentId . "email.errorevent.invalidemail.message","email");
   return true;
}

}


class UserLoginInfo {

    var $username;
    var $password;
    var $admin;
    var $dbPath;

    function setUsername($username){
        $this->username = $username;
    }

    function setPassword($password){
        $this->password = $password;
    }

    function setAdmin($admin) {
        $this->admin = $admin;
    }

    function setDbpath($dbPath) {
        $this->dbPath = $dbPath;
    }

    function getUsername(){
        return $this->username;
    }

    function getPassword(){
        return $this->password;
    }

    function getAdmin() {
        return $this->admin;
    }

    function getDbpath() {
        return $this->dbPath;
    }
}

?>
