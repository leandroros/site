<?php
$cgiDir = $nof_rootDir ."/". $nof_scriptDir . "/";
$componentid = $nof_componentId;
$xml_file  = $nof_scriptInterfaceFile;

// predefine variable values
$myVars['text']        = '';
$myVars['link']        = '';
$myVars['tstact']      = '';
$myVars['description'] = '';
$myVars['firstname']   = '';
$myVars['lastname']    = '';
$myVars['title']       = '';
$myVars['email']       = '';
$myVars['emailonpage'] = '';

$frmFirstName         = '';
$frmLastName         = '';
$frmTitle             = '';
$frmDescription        = '';
$frmEmail            = '';
$frmEmailOnPage        = '';

  // working variables initialisation
function t_add_InitParameterVariables($array, &$target) {
    if (!is_array($array)) {
        return FALSE;
    }
    $is_magic_quotes = get_magic_quotes_gpc();
    foreach ($array AS $key => $value) {
        if (is_array($value)) {
            unset($target[$key]);
            t_add_InitParameterVariables($value, $target[$key]);
        } else if ($is_magic_quotes) {
            $target[$key] = stripslashes($value);
        } else {
            $target[$key] = $value;
        }
    }
    return TRUE;
}

//if(!empty($_GET)) t_add_InitParameterVariables($_GET, $myVars);
//if(!empty($_POST)) t_add_InitParameterVariables($_POST, $myVars);
if(count(GetGVariable("")) > 0)    t_add_InitParameterVariables(GetGVariable(""),    $myVars);
if(count(GetPostVariable("")) > 0) t_add_InitParameterVariables(GetPostVariable(""), $myVars);

if (NOF_fileExists($cgiDir . "NOF_CaptchaProperties.class.php")) {
    include_once($cgiDir."NOF_CaptchaProperties.class.php");
} else {
    exit();
}

if (NOF_fileExists($cgiDir . "ts_lib.php")) {
    include_once($cgiDir."ts_lib.php");
} else {
    exit();
}

$conf = ts_parseXmlFile($cgiDir.$xml_file);

$titletext="";
if($myVars['text'] == ""){
    $titletext=$GLOBALS['nof_resources']->get('ts.AddForm.Title');
}else{
    $titletext=$myVars['text'];
}

$linkback=$myVars['link'];

$dbPath = $conf['add.' . $componentid . '.dbPath'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'dbPath');
$firstnamelabel = $conf['add.' . $componentid . '.firstnamelabel'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'firstnamelabel');
$lastnamelabel = $conf['add.' . $componentid . '.lastnamelabel'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'lastnamelabel');
$titlelabel = $conf['add.' . $componentid . '.titlelabel'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'titlelabel');
$descrlabel = $conf['add.' . $componentid . '.descrlabel'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'descrlabel');
$captchalabel = $conf['add.' . $componentid . '.captchalabel'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'captchalabel');
$email = $conf['add.' . $componentid . '.email'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'email');
$emailServer = $conf['add.' . $componentid . '.emailServer'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'emailServer');
$emailServerPort = $conf['add.' . $componentid . '.emailServerPort'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'emailServerPort');
$smtpAuth = $conf['add.' . $componentid . '.smtpAuth'];
$smtpUsername = $conf['add.' . $componentid . '.smtpUsername'];
$smtpPassword = $conf['add.' . $componentid . '.smtpPassword'];
$smtpSSL = (isset($conf['add.' . $componentid . '.smtpSSL']) && $conf['add.' . $componentid . '.smtpSSL'] == "true")?'ssl':'';
$emailFromAddress = $conf['add.' . $componentid . '.emailFromAddress'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'emailFromAddress');
$emaillabel = $conf['add.' . $componentid . '.emaillabel'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'emaillabel');
$emailonpagelabel = $conf['add.' . $componentid . '.emailonpage'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'emailonpage');
$picture = $conf['add.' . $componentid . '.picture'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'picture');
$picturelabel = $conf['add.' . $componentid . '.picturelabel'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'picturelabel');
$captchlabel = $conf['add.' . $componentid . '.captchalabel'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'captchalabel');
$requiretext = $conf['add.' . $componentid . '.requiretext'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'requiretext');
$language = $conf['add.' . $componentid . '.language'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'language');

$notify = $conf['add.' . $componentid . '.notify'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'notify');
$emailToAddress = $conf['add.' . $componentid . '.emailToAddress'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'emailToAddress');
$showCaptcha = $conf['add.' . $componentid . '.showCaptcha'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid,  'showCaptcha');
$captchaAlignment = $conf['add.' . $componentid . '.captchaAlignment'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid,  'captchaAlignment');


// css from xml file
$CSSstyle = $conf['add.' . $componentid . '.style'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid,  'style');


$emailPropertiesFile=$cgiDir.'ts_emailTemplate_'.$language.'.properties';

$fields="id;:;firstname;:;lastname;:;title;:;description;:;date;:;email;:;emailonpage;:;picture";



/*
if(trim($email)=="true"){
    $fields.=";:;email";
    $fields.=";:;emailonpage";
}
if(trim($picture)=="true"){
    $fields.=";:;picture";
}
*/

$fields.=";:;validation"."\r\n";
?>
<?php
$msg = $GLOBALS['nof_resources']->get('ts.AddForm.Required.PleaseFill.Text');
//upload
    $path =$cgiDir."images/";
    $upload_file_name = "userfile";
    $acceptable_file_types = "image/jpg|image/bmp|image/gif|image/jpeg|image/pjpeg|image/png";
    $default_extension = "";
    $mode = 2;

//




if($myVars['tstact']=="add"){

      if ($showCaptcha=='true' && !nof_captcha_validate_by_code($nof_componentId)) {
        $msg = $GLOBALS['nof_resources']->get('ts.AddForm.CaptchaField.InvalidMessage');

        $frmFirstName         = $myVars['firstname'];
        $frmLastName         = $myVars['lastname'];
        $frmTitle         = $myVars['title'];
        $frmDescription    = $myVars['description'];
        $frmEmail        = $myVars['email'];

        if ($myVars['emailonpage']=='true') $frmEmailOnPage = 'checked';

    }
else {
      $file_name="";
      $arr_file=array();
    $uploadedFiles = array();
    $uploadedFiles = GetFileVariable('');
    if(trim($picture)=="true" and $uploadedFiles[$upload_file_name]['name']!=""){
        $my_uploader = new uploader;
        $my_uploader->max_filesize(200000);
        $my_uploader->max_image_size(5000,5000);
        if ($my_uploader->upload($upload_file_name, $acceptable_file_types, $default_extension)) {
            $success = $my_uploader->save_file($path, $mode);
        }
        if($success){$file_name=$my_uploader->file['name'];}else{$msg=$GLOBALS['nof_resources']->get('ts.AddForm.CantUpload.Text');}
    }
    if (!file_exists(dirname($cgiDir.$dbPath))) {
        NOF_throwError(540,array("{1}"=>NOF_mapPath(dirname($cgiDir.$dbPath)),"{2}"=>getcwd()));
    }

    if (!file_exists($cgiDir.$dbPath)){
        if(!$FILE = @fopen($cgiDir.$dbPath, 'w')){
                 NOF_throwError(500,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
        }else{
            if(!@fputs($FILE,$fields)){
                        NOF_throwError(502,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
            }
            fclose($FILE);
        }
    }else{
        if(filesize ($cgiDir.$dbPath)==0){
            if(!$FILE = @fopen($cgiDir.$dbPath, 'w')){
                        NOF_throwError(502,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
        }else{
            if(!@fputs($FILE,$fields)){
                        NOF_throwError(502,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
            }
            fclose($FILE);
        }
    }



        if(!$FILE = @fopen($cgiDir.$dbPath, 'r')){
                  NOF_throwError(501,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
        }else{
            while (!feof ($FILE)) {
                $line=fgets($FILE, 1024);
                array_push($arr_file,$line);
            }
            fclose($FILE);
        }

        if(!$arr_file=@file($cgiDir.$dbPath)){
                  NOF_throwError(501,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
        }
        if(sizeof($arr_file)==0){
            if(!$FILE = @fopen($cgiDir.$dbPath, 'w')){
                        NOF_throwError(500,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
            }else{
                if(!@fputs($FILE,$fields, 10240)){
                              NOF_throwError(502,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
                }
            fclose($FILE);
            }
        }
    }

        $id=1;
        if(sizeof($arr_file)>1){
            foreach($arr_file as $value){
                $arrid=explode(";:;",$value);
                if(sizeof($arrid)>1 and $arrid[0]!=""){
                    $id=$arrid[0];
                }
            }

            $id=$arrid[0]+1;
        }

        $descript_filtered="";
            $myVars['description'] = htmlspecialchars($myVars['description']);
        for($i=0;$i<strlen($myVars['description']);$i++){
            if(ord($myVars['description'][$i])!=13 and ord($myVars['description'][$i])!=10){
                $descript_filtered.=$myVars['description'][$i];
            }
            if(ord($myVars['description'][$i])==13){
                $descript_filtered.="<br>";
            }
        }
        $line=$id.";:;".$myVars['firstname'].";:;".$myVars['lastname'].";:;".$myVars['title'].";:;".$descript_filtered.";:;".time();
        if(trim($email)=="true"){
            $line.=";:;".$myVars['email'];
            $line.=";:;".$myVars['emailonpage'];
        }else{
            $line.=";:;";
            $line.=";:;";
        }
        if(trim($picture)=="true"){
            $line.=";:;".$file_name;
        }else{
            $line.=";:;";
        }
        $line.=";:;false"."\r\n";


        if($uploadedFiles[$upload_file_name]['name']!="" and !$success) {

            // do nothing, it means troubles with upload and show the form
        } else {
            if(!$FILE = @fopen($cgiDir.$dbPath, 'a+')){
                NOF_throwError(502,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
            }else{
                if(!@fputs($FILE,$line)){
                    NOF_throwError(502,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
                }
                fclose($FILE);
            }
            $msg=$GLOBALS['nof_resources']->get('ts.AddForm.Success.Message');
            if($notify=="true"){
                $values=array($myVars['firstname'],$myVars['lastname'],$myVars['title'],$myVars['description']);
                if ( ! tstsendmail($emailPropertiesFile,$values,$emailToAddress) ) {
                    NOF_throwError(201,array("{1}"=>$emailToAddress,"{2}"=>$emailFromAddress,"{3}"=>$emailServer.":".$emailServerPort));
                    exit();
                }
            }
        }
    }
}
?>
<script language="javascript" type="text/javascript">
<!--
function ValidateEmail(theinput) {
   var s = new String;
   s = theinput.value
   var extra = String.fromCharCode(223,224,225,226,227,228,229,230,231,232,233,234,235,236,237,238,239,240,241,242,243,244,245,246,248,249,250,251,252,253,254,255,257,259,261,263,265,267,269,271,273,275,277,279,281,283,285,287,289,291,293,295,297,299,301,303,305,309,311,312,314,316,318,322,324,326,328,331,333,335,337,339,341,343,345,347,349,351,353,355,357,359,361,363,365,367,369,371,373,375,378,380,382);
   var regEx = new RegExp("^[^@]+@[a-z0-9" + extra + "]+([\\.-][a-z0-9" + extra + "]+)*\\.([a-z]{2,}|[0-9]{1,})$","gi");
   var validFormat = (s.search(regEx)>=0);
   return validFormat;
}


function tstvalidate() {
        if(document.form.email!=null){
        if(document.form.email.value!='' && (ValidateEmail(document.form.email)==false) ){
            alert('<?php echo html_entity_decode($GLOBALS['nof_resources']->get('ts.AddForm.EmailField.ValidMessage'))?>');
            document.form.email.focus();
            return false;
        }
        }
        if(document.form.firstname.value==''){
            alert('<?php echo html_entity_decode($GLOBALS['nof_resources']->get('ts.AddForm.Field.Label',array("{1}"=>$firstnamelabel)))?>');
            document.form.firstname.focus();
            return false;
        }


        if(document.form.lastname.value==''){
            alert('<?php echo html_entity_decode($GLOBALS['nof_resources']->get('ts.AddForm.Field.Label',array("{1}"=>$lastnamelabel)))?>');
            document.form.lastname.focus();
            return false;
        }
        if(document.form.title.value==''){
            alert('<?php echo html_entity_decode($GLOBALS['nof_resources']->get('ts.AddForm.Field.Label',array("{1}"=>$titlelabel)))?>');
            document.form.title.focus();
            return false;
        }
        if(document.form.description.value==''){
            alert('<?php echo html_entity_decode($GLOBALS['nof_resources']->get('ts.AddForm.Field.Label',array("{1}"=>$descrlabel)))?>');
            document.form.description.focus();
            return false;
        }
        if(document.form.captcha!=null && document.form.captcha.value==''){
            alert('<?php echo html_entity_decode($GLOBALS['nof_resources']->get('ts.AddForm.Field.Label',array("{1}"=>"$captchalabel")))?>');
            document.form.captcha.focus();
            return false;
        }
}

function goBackAct(obj) {
    var frm = obj.form;
    frm.action="<?php echo $linkback;?>";
    frm.onSubmit="";
    frm.submit();
}
//-->
</script>
<form action="<?php echo GetServerVariable('PHP_SELF')?>" target="_self" method="post" enctype="multipart/form-data" name="form" onSubmit="return tstvalidate();">
<input type="hidden" name="tstact" value="add">
<input type="hidden" name="link" value="<?php echo $linkback;?>">

<div class="nof_<?php echo $CSSstyle?>_content">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="2">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="nof_<?php echo $CSSstyle?>_header">
                <tr>
                    <td><span class="nof_<?php echo $CSSstyle?>_text"><span class="nof_<?php echo $CSSstyle?>_heading"><?php echo $titletext?></span></span></td>
                    <td align="right"><span class="nof_<?php echo $CSSstyle?>_text"><span class="nof_<?php echo $CSSstyle?>_required">*<?php echo $GLOBALS['nof_resources']->get('ts.AddForm.Required.Message')?></span></span></td>
                </tr>
            </table>
            </td>
        </tr>
        <tr>
            <td class="nof_<?php echo $CSSstyle?>_contentCell">&nbsp;</td>
            <td class="nof_<?php echo $CSSstyle?>_contentCell" width="100%"><span class="nof_<?php echo $CSSstyle?>_text"><span class="nof_<?php echo $CSSstyle?>_formInstructions"><?php echo $msg;?></span></span></td>
        </tr>
        <tr>
            <td class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><label for="firstname" class="nof_<?php echo $CSSstyle?>_label"><span class="nof_<?php echo $CSSstyle?>_required">*</span><?php echo $firstnamelabel?>:</label></span></td>
            <td width="100%" class="nof_<?php echo $CSSstyle?>_contentCell"><input name="firstname" type="text" class="nof_<?php echo $CSSstyle?>_input_text" id="firstname" value="<?php echo $frmFirstName?>" size="40"></td>
        </tr>
        <tr>
            <td class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><label for="lastname" class="nof_<?php echo $CSSstyle?>_label"><span class="nof_<?php echo $CSSstyle?>_required">*</span><?php echo $lastnamelabel?>:</label></span></td>
            <td width="100%" class="nof_<?php echo $CSSstyle?>_contentCell"><input name="lastname" type="text" class="nof_<?php echo $CSSstyle?>_input_text" id="lastname" value="<?php echo $frmLastName?>" size="40"></td>
        </tr>
        <tr>
            <td class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><label for="title" class="nof_<?php echo $CSSstyle?>_label"><span class="nof_<?php echo $CSSstyle?>_required">*</span><?php echo $titlelabel?>:</label></span></td>
            <td width="100%" class="nof_<?php echo $CSSstyle?>_contentCell"><input name="title" type="text" class="nof_<?php echo $CSSstyle?>_input_text" id="title" value="<?php echo $frmTitle?>" size="40"></td>
        </tr>
        <tr>
            <td class="nof_<?php echo $CSSstyle?>_contentCell" valign="top"><span class="nof_<?php echo $CSSstyle?>_text"><label for="description" class="nof_<?php echo $CSSstyle?>_label"><span class="nof_<?php echo $CSSstyle?>_required">*</span><?php echo $descrlabel?>:</label></span></td>
            <td width="100%" class="nof_<?php echo $CSSstyle?>_contentCell"><textarea name="description" cols="31" rows="5" class="nof_<?php echo $CSSstyle?>_textarea" id="description"><?php echo $frmDescription?></textarea></td>
        </tr>

        <?php
        if(trim($email)=="true"){
        ?>

        <tr>
            <td class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><label for="email" class="nof_<?php echo $CSSstyle?>_label"><?php echo $emaillabel?>:</label></span></td>
            <td width="100%" class="nof_<?php echo $CSSstyle?>_contentCell"><input name="email" type="text" class="nof_<?php echo $CSSstyle?>_input_text" id="email" value="<?php echo $frmEmail?>" size="40"></td>
        </tr>
        <tr>
            <td class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><label for="emailonpage" class="nof_<?php echo $CSSstyle?>_label"><?php echo $emailonpagelabel?>:</label></span></td>
            <td width="100%" valign="top" class="nof_<?php echo $CSSstyle?>_contentCell"><input name="emailonpage" type="checkbox" id="emailonpage" value="true" <?php echo $frmEmailOnPage;?> class="nof_<?php echo $CSSstyle?>_input_checkbox"></td>
        </tr>

        <?php
        }
        ?>
        <?php
        if(trim($picture)=="true"){
        ?>
        <tr>
            <td class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><label for="<?php echo $upload_file_name; ?>" class="nof_<?php echo $CSSstyle?>_label"><?php echo $picturelabel?>:</label></span></td>
            <td width="100%" valign="top" class="nof_<?php echo $CSSstyle?>_contentCell"><input name="<?php echo $upload_file_name; ?>" type="file" class="nof_<?php echo $CSSstyle?>_input_file" id="<?php echo $upload_file_name; ?>" size="40"><span class="nof_<?php echo $CSSstyle?>_text"><span class="nof_<?php echo $CSSstyle?>_maxPicSize"><?php echo $requiretext;?></span></span></td>
        </tr>

        <?php
        }
        ?>
        <?php
            if ( $showCaptcha == "true") {
            $SESSION_KEY = "nof_".$nof_componentId."_CaptchSettings";
            $properties = unserialize(GetSessionVariable($SESSION_KEY));
        ?>

        <tr>
            <td class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><label for="title" class="nof_<?php echo $CSSstyle?>_label"><span class="nof_<?php echo $CSSstyle?>_required">*</span><?php echo $captchlabel;?>:</label></span></td>
            <td width="100%" class="nof_<?php echo $CSSstyle?>_contentCell"><img alt="" src="<?php echo $cgiDir?>NOF_CaptchaBMP.class.php?cid=<?php echo $nof_componentId?>&amp;ft=<?php echo time();?>"><?php if  ($captchaAlignment=='vertically') echo "<br>";?><input name="captcha" id="captcha" type="text" value="" size="<?php echo strlen($properties->imageChars)?>" maxlength="<?php echo strlen($properties->imageChars)?>"></td>
        </tr>

        <?php
            }
        ?>

        <tr>
            <td class="nof_<?php echo $CSSstyle?>_contentCell">&nbsp;</td>
            <td width="100%" class="nof_<?php echo $CSSstyle?>_contentCell"><input class="nof_<?php echo $CSSstyle?>_input_submit" value="<?php echo $GLOBALS['nof_resources']->get('ts.AddForm.Add.Button.Label')?>" type="submit"><?php if($linkback!=""){?><input type="button" class="submitbtn" value="<?php echo $GLOBALS['nof_resources']->get('ts.AddForm.Back.Button.Label')?>" onClick="goBackAct(this);"><?php }?></td>
        </tr>
        <tr>
            <td colspan="2">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="nof_<?php echo $CSSstyle?>_footer">
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</form>

<?php
class uploader {

    var $file;
    var $errors;
    var $accepted;
    var $max_filesize;
    var $max_image_width;
    var $max_image_height;
    function max_filesize($size){
        $this->max_filesize = $size;
    }
    function max_image_size($width, $height){
        $this->max_image_width  = $width;
        $this->max_image_height = $height;
    }
    function upload($filename='', $accept_type='', $extention='') {
        $tmp_FILES = GetFileVariable('');

        if (!is_array($tmp_FILES[$filename]) || !$tmp_FILES[$filename]['name']) {
            $this->errors[0] = $GLOBALS['nof_resources']->get('ts.AddForm.NoFileWasUploaded.Text');
            $this->accepted  = FALSE;
            return FALSE;
        }

        // Copy PHP's global $_FILES array to a local array
        $this->file = $tmp_FILES[$filename];
        $this->file['file'] = $filename;

        // Initialize empty array elements
        if (!isset($this->file['extention'])) $this->file['extention'] = "";
        if (!isset($this->file['type']))      $this->file['type']      = "";
        if (!isset($this->file['size']))      $this->file['size']      = "";
        if (!isset($this->file['width']))     $this->file['width']     = "";
        if (!isset($this->file['height']))    $this->file['height']    = "";
        if (!isset($this->file['tmp_name']))  $this->file['tmp_name']  = "";
        if (!isset($this->file['raw_name']))  $this->file['raw_name']  = "";

        // test max size
        if($this->max_filesize && ($this->file["size"] > $this->max_filesize)) {
            $this->errors[1] = $GLOBALS['nof_resources']->get('ts.AddForm.FileOverSize.Message',array("{1}"=>$this->max_filesize/1000));
            $this->accepted  = FALSE;
            return FALSE;
        }

         if(stristr($this->file["type"], "image")) {

             /* IMAGES */

             $image = @getimagesize($this->file["tmp_name"]);
             $this->file["width"]  = $image[0];
             $this->file["height"] = $image[1];

            // test max image size
            if(($this->max_image_width || $this->max_image_height) && (($this->file["width"] > $this->max_image_width) || ($this->file["height"] > $this->max_image_height))) {
                $this->errors[2] = $GLOBALS['nof_resources']->get('ts.AddForm.MaximumImage.Text',array("{1}"=>$this->max_image_width,"{2}"=>$this->max_image_height));
                $this->accepted  = FALSE;
                return FALSE;
            }
            // Image Type is returned from getimagesize() function
             switch($image[2]) {
                 case 1:
                     $this->file["extention"] = ".gif"; break;
                 case 2:
                     $this->file["extention"] = ".jpg"; break;
                 case 3:
                     $this->file["extention"] = ".png"; break;
                 case 4:
                     $this->file["extention"] = ".swf"; break;
                 case 5:
                     $this->file["extention"] = ".psd"; break;
                 case 6:
                     $this->file["extention"] = ".bmp"; break;
                 case 7:
                     $this->file["extention"] = ".tif"; break;
                 case 8:
                     $this->file["extention"] = ".tif"; break;
                 default:
                    $this->file["extention"] = $extention; break;
             }
        } elseif(!ereg("(\.)([a-z0-9]{3,5})$", $this->file["name"]) && !$extention) {
            switch($this->file["type"]) {
                case "text/plain":
                    $this->file["extention"] = ".txt"; break;
                case "text/richtext":
                    $this->file["extention"] = ".txt"; break;
                default:
                    break;
            }
         } else {
            $this->file["extention"] = $extention;
        }

        if($accept_type) {
            if(stristr($accept_type, $this->file["type"])) {
                $this->accepted = TRUE;
            } else {
                $this->accepted = FALSE;
                $this->errors[3] = $GLOBALS['nof_resources']->get('ts.AddForm.OnlyFiles.Text',array("{1}"=>str_replace("|", " or ", $accept_type)));
            }
        } else {
            $this->accepted = TRUE;
        }

        return $this->accepted;
    }
function save_file($path, $overwrite_mode="3"){
        if ($path[strlen($path) - 1] != "/") {
            $path = $path . "/";
        }
        $this->path = $path;
        $copy        = "";
        $n            = 1;
        $aok         = false;

        if($this->accepted) {
            // Clean up file name (only lowercase letters, numbers and underscores)
            $this->file["name"] = ereg_replace("[^a-z0-9._]", "", str_replace(" ", "_", str_replace("%20", "_", strtolower($this->file["name"]))));

            // Clean up text file breaks
            if(stristr($this->file["type"], "text")) {
                $this->cleanup_text_file($this->file["tmp_name"]);
            }

            // get the raw name of the file (without it's extenstion)
            if(ereg("(\.)([a-z0-9]{2,5})$", $this->file["name"])) {
                $pos = strrpos($this->file["name"], ".");
                if(!$this->file["extention"]) {
                    $this->file["extention"] = substr($this->file["name"], $pos, strlen($this->file["name"]));
                }
                $this->file['raw_name'] = substr($this->file["name"], 0, $pos);
            } else {
                $this->file['raw_name'] = $this->file["name"];
                if ($this->file["extention"]) {
                    $this->file["name"] = $this->file["name"] . $this->file["extention"];
                }
            }

            switch($overwrite_mode) {
                case 1: // overwrite mode
                    $aok = @move_uploaded_file($this->file["tmp_name"], $this->path . $this->file["name"]) or NOF_throwError(702,array("{1}"=>$this->file["name"],"{2}"=>$this->path));
                    break;
                case 2: // create new with incremental extention
                    while(file_exists($this->path . $this->file['raw_name'] . $copy . $this->file["extention"])) {
                        $copy = "_copy" . $n;
                        $n++;
                    }
                    $this->file["name"]  = $this->file['raw_name'] . $copy . $this->file["extention"];
                    $aok = @move_uploaded_file($this->file["tmp_name"], $this->path . $this->file["name"]) or NOF_throwError(702,array("{1}"=>$this->file["name"],"{2}"=>$this->path));
                    break;
                case 3: // do nothing if exists, highest protection
                    if(file_exists($this->path . $this->file["name"])){
                        $this->errors[4] = $GLOBALS['nof_resources']->get("ts.AddForm.FileExists.Text",array("{1}"=>$this->path . $this->file["name"]));
                        $aok = null;
                    } else {
                        $aok = @move_uploaded_file($this->file["tmp_name"], $this->path . $this->file["name"]) or NOF_throwError(702,array("{1}"=>$this->file["name"],"{2}"=>$this->path));
                    }
                    break;
                default:
                    break;
            }
            chmod($this->path . $this->file["name"],0666);

            if(!$aok) { unset($this->file['tmp_name']); }
            return $aok;
        } else {
            $this->errors[3] = $GLOBALS['nof_resources']->get("ts.AddForm.OnlyFiles.Text",array("{1}"=>str_replace("|", " or ", $accept_type)));
            return FALSE;
        }
    }
    function cleanup_text_file($file){
        $new_file  = '';
        $old_file  = '';
        $fcontents = file($file);
        while (list ($line_num, $line) = each($fcontents)) {
            $old_file .= $line;
            $new_file .= str_replace(chr(13), chr(10), $line);
        }
        if ($old_file != $new_file) {
            // Open the uploaded file, and re-write it with the new changes
            $fp = @fopen($file, "w") or NOF_throwError(702,array("{1}"=>NOF_mapPath($file),"{2}"=>dirname($file)));
            @fwrite($fp, $new_file) or NOF_throwError(702,array("{1}"=>NOF_mapPath($file),"{2}"=>dirname($file)));
            fclose($fp);
        }
    }

}
//
function tstreadEmailTemplate($filePath,$kind) {

        global $conf;

        if(!$lines = @file($filePath)) {
              NOF_throwError(110,array("{1}"=>NOF_mapPath($filePath),"{2}"=>getcwd()));
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
function tstsendmail($emailPropertiesFile,$values,$email){
    global $conf,$emailServer,$emailFromAddress,$emailServerPort,$firstnamelabel,$lastnamelabel,$titlelabel,$descrlabel,$smtpAuth,$smtpUsername,$smtpPassword,$smtpSSL;
    tstreadEmailTemplate($emailPropertiesFile,"[EMAIL]");
    $fields=array("$firstnamelabel","$lastnamelabel","$titlelabel","$descrlabel");

    $body=$conf["[EMAIL]Body"];

    $stringval="<br><br>";
    for($i=0;$i<4;$i++){
    $stringval.=$fields[$i]." : ".$values[$i]."<br>";
    }
    $body = eregi_replace("\{beginiterator\}(.*)\{enditerator\}", $stringval."<br>" , $body);

    $mail = new PHPMailer();
    $mail->From = $emailFromAddress;
    $mail->FromName = $emailFromAddress;
    $mail->Host = $emailServer;
    $mail->Port = $emailServerPort;
    $mail->SMTPDebug = false;
    $mail->Mailer = "smtp";
    $mail->IsHTML = true;
    $mail->Subject = $conf["[EMAIL]Subject"];
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth = false;
    if ( $smtpAuth == "true" ) {
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUsername;
        $mail->Password = $smtpPassword;
    }
    $mail->SMTPSecure = $smtpSSL;
    $mail->AddAddress($email,$email);
    $htmlbody = nl2br($body);
    $htmlbody = str_replace('\\n',"<br>",$htmlbody);
    $htmlbody = str_replace('\\','',$htmlbody);
    $mail->Body = $htmlbody;
    $htmlbody = str_replace("<br>","\n",$htmlbody);
    $mail->AltBody = strip_tags($htmlbody);

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


?>
