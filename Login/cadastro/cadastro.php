<?php
if ( session_id() == "" ) @session_start();
?>
<?php
error_reporting(0);
$nof_suiteName="SecureSite";
$nof_debug = "false";
$nof_langFile = "../../scripts/SecureSite_pt.properties";
$nof_rootDir = "../..";
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
require_once($nof_rootDir . "/" . $nof_scriptDir . "/" . "NOF_CaptchaProperties.class.php");
?>
<!DOCTYPE html>
<html>
<head>
<title>cadastro</title>
<meta charset="UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="Generator" content="NetObjects (http://NetObjects.com)"/>
<script type="text/javascript" src="../../jquery.js">
</script>
<script type="text/javascript" src="../../navbars.js">
</script>
<link rel="stylesheet" type="text/css" href="../../fusion.css">
<link rel="stylesheet" type="text/css" href="../../style.css">
<link rel="stylesheet" type="text/css" href="../../site.css">
<style type="text/css" title="NOF_STYLE_SHEET">
<!--
body { margin:0px; width: 960px; }
div#LayoutLYR { float:left; position:absolute; }
div#NavigationBar1LYR { position:absolute; top:30px; left:100px; width:303px; height:36px; z-index:1 }
div#NOFSecureSite1LYR { position:absolute; top:157px; left:128px; width:357px; height:253px; z-index:2 }
-->
</style>

<script type="text/javascript" src="./cadastro_nof.js">
</script>
</head>
<body>
  <div id="LayoutLYR">
    <div id="NOFSecureSite1LYR">
<?php
if (NOF_fileExists("../../scripts/ss_signup.php")) {
?>

<?php
$properties = new NOF_CaptchaProperties();
$imageChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

$properties->imageChars  	= nof_captcha_randomChars($imageChars, 5);
$properties->spaceInner  	= 0;
$properties->bgColor     	= "ffffff";
$properties->fgColors    	= Array("000000");
$properties->spaceTop    	= 10;
$properties->spaceBottom 	= 10;
$properties->spaceLeft   	= 5;
$properties->spaceRight  	= 5;
$properties->charFontDir 	= "charsmap/zebra";

SetSessionVariable("nof_1636476527414_CaptchSettings", serialize($properties));
?>

<!-- <img id="NOFSecureSite1" height="253" width="357" src="../../Login/cadastro/icon_signupmodule.gif" alt=""> -->
<form accept-charset='UNKNOWN' method='POST' target='_self' action='../../scripts/ss_signup.php' name='signup1636476527414' enctype='application/x-www-form-urlencoded'><input type="hidden" name="nof_componentId" value="1636476527414">
<input type="hidden" name="nof_componentGroupId" value="1636473377108">
<input type="hidden" name="nof_packageId" value="com.netobjects.nfxcomp.securesite">
<input type="hidden" name="nof_componentName" value="signup">
<input type="hidden" name="nof_componentGroupName" value="banco">
<input type="hidden" name="nof_rootDir" value="../..">
<input type="hidden" name="nof_scriptDir" value="scripts">
<input type="hidden" name="nof_scriptInterfaceFile" value="SecureSite1636473377108.xml.php">
<input type="hidden" name="nof_debug" value="false">
<input type="hidden" name="nof_langFile" value="SecureSite_pt.properties">
<input type="hidden" name="nof_formName" value="signup1636476527414">
<table><tr><td nowrap><label for='username' class='jcarousel-skin-nof .jcarousel-direction-rtl .jcarousel-prev-horizontal'>Nome de usuário</label></td><td nowrap><?php if(isset($_POST["1636476527414_username_errorimg"])) echo stripslashes($_POST["1636476527414_username_errorimg"]); ?></td><td><input class='nof_secureSite_input_text' id='username' name='username' maxlength='30' value='' size='15' type='TEXT'></td></tr><tr><td nowrap><label for='password' class='nof_secureSite_label'>Senha</label></td><td nowrap><?php if(isset($_POST["1636476527414_password_errorimg"])) echo stripslashes($_POST["1636476527414_password_errorimg"]); ?></td><td><input class='nof_secureSite_input_password' id='password' name='password' maxlength='30' value='' size='15' type='PASSWORD'></td></tr><tr><td nowrap><label for='retypePassword' class='nof_secureSite_label'>Redigitar senha</label></td><td nowrap><?php if(isset($_POST["1636476527414_retypePassword_errorimg"])) echo stripslashes($_POST["1636476527414_retypePassword_errorimg"]); ?></td><td><input class='nof_secureSite_input_password' id='retypePassword' name='retypePassword' maxlength='30' value='' size='15' type='PASSWORD'></td></tr><tr><td nowrap><label for='firstName' class='nof_secureSite_label'>Nome</label></td><td nowrap><?php if(isset($_POST["1636476527414_firstName_errorimg"])) echo stripslashes($_POST["1636476527414_firstName_errorimg"]); ?></td><td><input class='nof_secureSite_input_text' id='firstName' name='firstName' maxlength='30' value='' size='15' type='TEXT'></td></tr><tr><td nowrap><label for='lastName' class='nof_secureSite_label'>Sobrenome</label></td><td nowrap><?php if(isset($_POST["1636476527414_lastName_errorimg"])) echo stripslashes($_POST["1636476527414_lastName_errorimg"]); ?></td><td><input class='nof_secureSite_input_text' id='lastName' name='lastName' maxlength='30' value='' size='15' type='TEXT'></td></tr><tr><td nowrap><label for='address' class='nof_secureSite_label'>Endereço</label></td><td nowrap><?php if(isset($_POST["1636476527414_address_errorimg"])) echo stripslashes($_POST["1636476527414_address_errorimg"]); ?></td><td><input class='nof_secureSite_input_text' id='address' name='address' maxlength='30' value='' size='15' type='TEXT'></td></tr><tr><td nowrap><label for='zip' class='nof_secureSite_label'>CEP</label></td><td nowrap><?php if(isset($_POST["1636476527414_zip_errorimg"])) echo stripslashes($_POST["1636476527414_zip_errorimg"]); ?></td><td><input class='nof_secureSite_input_text' id='zip' name='zip' maxlength='30' value='' size='15' type='TEXT'></td></tr><tr><td nowrap><label for='city' class='nof_secureSite_label'>Cidade</label></td><td nowrap><?php if(isset($_POST["1636476527414_city_errorimg"])) echo stripslashes($_POST["1636476527414_city_errorimg"]); ?></td><td><input class='nof_secureSite_input_text' id='city' name='city' maxlength='30' value='' size='15' type='TEXT'></td></tr><tr><td nowrap><label for='county' class='nof_secureSite_label'>Município</label></td><td nowrap><?php if(isset($_POST["1636476527414_county_errorimg"])) echo stripslashes($_POST["1636476527414_county_errorimg"]); ?></td><td><input class='nof_secureSite_input_text' id='county' name='county' maxlength='30' value='' size='15' type='TEXT'></td></tr><tr><td nowrap><label for='state' class='nof_secureSite_label'>Estado</label></td><td nowrap><?php if(isset($_POST["1636476527414_state_errorimg"])) echo stripslashes($_POST["1636476527414_state_errorimg"]); ?></td><td><input class='nof_secureSite_input_text' id='state' name='state' maxlength='30' value='' size='15' type='TEXT'></td></tr><tr><td nowrap><label for='country' class='nof_secureSite_label'>País</label></td><td nowrap><?php if(isset($_POST["1636476527414_country_errorimg"])) echo stripslashes($_POST["1636476527414_country_errorimg"]); ?></td><td><input class='nof_secureSite_input_text' id='country' name='country' maxlength='30' value='' size='15' type='TEXT'></td></tr><tr><td nowrap><label for='phone' class='nof_secureSite_label'>Telefone</label></td><td nowrap><?php if(isset($_POST["1636476527414_phone_errorimg"])) echo stripslashes($_POST["1636476527414_phone_errorimg"]); ?></td><td><input class='nof_secureSite_input_text' id='phone' name='phone' maxlength='30' value='' size='15' type='TEXT'></td></tr><tr><td nowrap><label for='email' class='nof_secureSite_label'>Email</label></td><td nowrap><?php if(isset($_POST["1636476527414_email_errorimg"])) echo stripslashes($_POST["1636476527414_email_errorimg"]); ?></td><td><input class='nof_secureSite_input_text' id='email' name='email' maxlength='30' value='' size='15' type='TEXT'></td></tr><tr><td nowrap><label for='captcha' class='nof_secureSite_label'>Especifique o código mostrado:</label></td><td nowrap><?php if(isset($_POST["1636476527414_captcha_errorimg"])) echo stripslashes($_POST["1636476527414_captcha_errorimg"]); ?></td><td><span><img alt='' class='nof_secureSite_input_text' src='../../scripts/NOF_CaptchaBMP.class.php?cid=1636476527414&amp;ft=<?php echo time();?>'><input class='nof_secureSite_input_text' id='captcha' name='captcha' maxlength='5' value='' size='5' type='TEXT'></span></td></tr><tr><td nowrap><label for='Signup' class='nof_secureSite_label'></label></td><td nowrap><?php if(isset($_POST["1636476527414_Signup_errorimg"])) echo stripslashes($_POST["1636476527414_Signup_errorimg"]); ?></td><td><input class='nof_secureSite_input_submit' id='Signup' name='Signup' maxlength='0' value='Signup' size='0' type='SUBMIT'></td></tr><tr><td colspan='3'><?php if(isset($_POST["1636476527414_errormessgs"])) echo stripslashes($_POST["1636476527414_errormessgs"]); ?></td></tr></table></form>

<?php
$formName = "signup1636476527414";
if(NOF_fileExists("../../scripts/ss_remembervalues.php")) include("../../scripts/ss_remembervalues.php");
}
?>
</div>
    <div id="NavigationBar1LYR" style="z-index: 1000">
      <ul id="NavigationBar1" style="z-index: 1000; display: none;">
        <li id="Botãodenavegação1"><a href="../../index.php" title="Home" style="line-height: 0">Home</a></li>
        <li id="Botãodenavegação2"><a href="../../Login/login.php" title="Login" style="line-height: 0">Login</a>
          <ul id="NavigationBar1_1">
            <li id="Botãodenavegação4"><a href="../../Login/Adm/adm.php" title="Adm" style="line-height: 0">Adm</a></li>
            <li id="Botãodenavegação5"><a href="../../Login/Senha/senha.php" title="Senha" style="line-height: 0">Senha</a></li>
            <li id="Botãodenavegação6"><a href="../../Login/cadastro/cadastro.php" title="cadastro" style="line-height: 0">cadastro</a></li>
          </ul>
        </li>
        <li id="Botãodenavegação3"><a href="../../Tabiban/tabiban.php" title="Tabiban" style="line-height: 0">Tabiban</a></li>
      </ul>
    </div>
  </div>
</body>
</html>
 