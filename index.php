<?php
if ( session_id() == "" ) @session_start();
?>
<?php
error_reporting(0);
$nof_suiteName="SecureSite";
$nof_debug = "false";
$nof_langFile = "./scripts/SecureSite_pt.properties";
$nof_rootDir = ".";
$nof_scriptDir = "scripts";
?>
<?php
if (!file_exists($nof_langFile) || !file_exists($nof_rootDir . "/" . $nof_scriptDir . "/" . "nof_utils.inc.php")) {
if($nof_debug == "true") {
echo "<p>Os componentes exigidos pelo pacote <b>banco</b> não foram publicados. Verifique as suas definições de publicação no Fusion e republique o site.</p>";
} else {
echo "<p>Ocorreu um erro. Entre em contato com o administrador do site</p>
<p>Código de erro: 103</p>";
}
exit();
}

require_once($nof_rootDir . "/" . $nof_scriptDir . "/" . "nof_utils.inc.php");
$nof_resources->addFile($nof_langFile);
?>
<?php
error_reporting(0);
@session_start();
$nof_suiteName="Testimonials";
$nof_debug = "false";
$nof_langFile = "./scripts/Testimonials_pt.properties";
$nof_rootDir = ".";
$nof_scriptDir = "scripts";
?>
<?php
if (!file_exists($nof_langFile) || !file_exists($nof_rootDir . "/" . $nof_scriptDir . "/" . "nof_utils.inc.php")) {
if($nof_debug == "true") {
echo "<p>Os componentes exigidos pelo pacote <b>Jeova</b> não foram publicados. Verifique as suas definições de publicação no Fusion e republique o site.</p>";
} else {
echo "<p>Ocorreu um erro. Entre em contato com o administrador do site</p>
<p>Código de erro: 103</p>";
}
exit();
}

require_once($nof_rootDir . "/" . $nof_scriptDir . "/" . "nof_utils.inc.php");
$nof_resources->addFile($nof_langFile);
?>
<?php
require_once($nof_rootDir . "/" . $nof_scriptDir . "/" . "NOF_CaptchaProperties.class.php");
?>
<!DOCTYPE html>
<html>
<head>
<title>Home</title>
<meta charset="UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="Generator" content="NetObjects (http://NetObjects.com)"/>
<script type="text/javascript" src="./jquery.js">
</script>
<script type="text/javascript" src="./navbars.js">
</script>
<link rel="stylesheet" type="text/css" href="./fusion.css">
<link rel="stylesheet" type="text/css" href="./style.css">
<link rel="stylesheet" type="text/css" href="./site.css">
<style type="text/css" title="NOF_STYLE_SHEET">
<!--
body { margin:0px; width: 960px; }
div#LayoutLYR { float:left; position:absolute; }
div#NavigationBar1LYR { position:absolute; top:30px; left:100px; width:303px; height:36px; z-index:1 }
div#NOFSecureSite1LYR { position:absolute; top:152px; left:135px; width:208px; height:106px; z-index:2 }
div#NOFTestimonials1LYR { position:absolute; top:289px; left:120px; width:230px; height:138px; z-index:3 }
-->
</style>

<script type="text/javascript" src="./index_nof.js">
</script>
</head>
<body>
  <div id="LayoutLYR">
    <div id="NOFSecureSite1LYR">
<?php
if (NOF_fileExists("./scripts/ss_login.php")) {
?>


<!-- <img id="NOFSecureSite1" height="106" width="208" src="./icon_loginmodule.gif" alt=""> -->
<form accept-charset='UNKNOWN' method='POST' target='_self' action='./scripts/ss_login.php' name='login1636478311838' enctype='application/x-www-form-urlencoded'><input type="hidden" name="nof_componentId" value="1636478311838">
<input type="hidden" name="nof_componentGroupId" value="1636473377108">
<input type="hidden" name="nof_packageId" value="com.netobjects.nfxcomp.securesite">
<input type="hidden" name="nof_componentName" value="login">
<input type="hidden" name="nof_componentGroupName" value="banco">
<input type="hidden" name="nof_rootDir" value=".">
<input type="hidden" name="nof_scriptDir" value="scripts">
<input type="hidden" name="nof_scriptInterfaceFile" value="SecureSite1636473377108.xml.php">
<input type="hidden" name="nof_debug" value="false">
<input type="hidden" name="nof_langFile" value="SecureSite_pt.properties">
<input type="hidden" name="nof_formName" value="login1636478311838">
<table><tr><td nowrap><label for='username' class='nof_secureSite_label'>Nome de usuário</label></td><td nowrap><?php if(isset($_POST["1636478311838_username_errorimg"])) echo stripslashes($_POST["1636478311838_username_errorimg"]); ?></td><td><input class='nof_secureSite_input_text' id='username' name='username' maxlength='30' value='' size='15' type='TEXT'></td></tr><tr><td nowrap><label for='password' class='nof_secureSite_label'>Senha</label></td><td nowrap><?php if(isset($_POST["1636478311838_password_errorimg"])) echo stripslashes($_POST["1636478311838_password_errorimg"]); ?></td><td><input class='nof_secureSite_input_password' id='password' name='password' maxlength='30' value='' size='15' type='PASSWORD'></td></tr><tr><td nowrap><label for='LOGIN' class='nof_secureSite_label'></label></td><td nowrap><?php if(isset($_POST["1636478311838_LOGIN_errorimg"])) echo stripslashes($_POST["1636478311838_LOGIN_errorimg"]); ?></td><td><input class='nof_secureSite_input_submit' id='LOGIN' name='LOGIN' maxlength='0' value='Login' size='0' type='SUBMIT'></td></tr><tr><td colspan='3'><?php if(isset($_POST["1636478311838_errormessgs"])) echo stripslashes($_POST["1636478311838_errormessgs"]); ?></td></tr></table></form>
<?php
$formName = "login1636478311838";
if (NOF_fileExists("./scripts/ss_remembervalues.php")) include("./scripts/ss_remembervalues.php");
}
?>
</div>
    <div id="NOFTestimonials1LYR">
<?php
$properties = new NOF_CaptchaProperties();
$imageChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

if (GetSessionVariable("nof_1639679483600_CaptchSettings")) {
$previousCaptchaProperties = unserialize(GetSessionVariable("nof_1639679483600_CaptchSettings"));
$properties->codeChars = $previousCaptchaProperties->imageChars;
} else {
$properties->codeChars = "";
}
$properties->imageChars  	= nof_captcha_randomChars($imageChars, 5);
$properties->spaceInner  	= 0;
$properties->bgColor     	= "ffffff";
$properties->fgColors    	= Array("000000");
$properties->spaceTop    	= 10;
$properties->spaceBottom 	= 10;
$properties->spaceLeft   	= 5;
$properties->spaceRight  	= 5;
$properties->charFontDir 	= "charsmap/zebra";

SetSessionVariable("nof_1639679483600_CaptchSettings", serialize($properties));
?>

<!-- <img id="NOFTestimonials1" height="138" width="230" src="./add.gif" alt=""> -->


<?php
$nof_debug = "false";
$nof_suiteName = "Testimonials";
$nof_langFile = "./scripts/Testimonials_pt.properties";
$nof_componentId="1639679483600";
$nof_rootDir=".";
$nof_scriptDir="scripts";
$nof_scriptInterfaceFile="Testimonials1639679494845.xml.php";
if (NOF_fileExists("./scripts/ts_add.php")) include("./scripts/ts_add.php");
?>
</div>
    <div id="NavigationBar1LYR" style="z-index: 1000">
      <ul id="NavigationBar1" style="z-index: 1000; display: none;">
        <li id="Botãodenavegação1"><a href="./index.php" title="Home" style="line-height: 0">Home</a></li>
        <li id="Botãodenavegação2"><a href="./Login/login.php" title="Login" style="line-height: 0">Login</a>
          <ul id="NavigationBar1_1">
            <li id="Botãodenavegação4"><a href="./Login/Adm/adm.php" title="Adm" style="line-height: 0">Adm</a></li>
            <li id="Botãodenavegação5"><a href="./Login/Senha/senha.php" title="Senha" style="line-height: 0">Senha</a></li>
            <li id="Botãodenavegação6"><a href="./Login/cadastro/cadastro.php" title="cadastro" style="line-height: 0">cadastro</a></li>
          </ul>
        </li>
        <li id="Botãodenavegação3"><a href="./Tabiban/tabiban.php" title="Tabiban" style="line-height: 0">Tabiban</a></li>
      </ul>
    </div>
  </div>
</body>
</html>
 