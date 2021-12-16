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
$nof_debug = "false";
$nof_langFile = "../../scripts/SecureSite_pt.properties";
$nof_suiteName = "SecureSite";
$nof_componentId="1636476506793";
$nof_componentName="changepassword";
$nof_rootDir="../..";
$nof_scriptDir="scripts";
$nof_scriptInterfaceFile="SecureSite1636473377108.xml.php";
if (NOF_fileExists("../../scripts/ss_securepage.php")) include("../../scripts/ss_securepage.php");
?>
<!DOCTYPE html>
<html>
<head>
<title>Senha</title>
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
div#NOFSecureSite1LYR { position:absolute; top:156px; left:121px; width:276px; height:227px; z-index:2 }
-->
</style>

<script type="text/javascript" src="./senha_nof.js">
</script>
</head>
<body>
  <div id="LayoutLYR">
    <div id="NOFSecureSite1LYR">
<?php
if(NOF_fileExists("../../scripts/ss_changepassword.php")) {
?>


<!-- <img id="NOFSecureSite1" height="227" width="276" src="../../Login/Senha/icon_changepasswordmodule.gif" alt=""> -->
<form accept-charset='UNKNOWN' method='POST' target='_self' action='../../scripts/ss_changepassword.php' name='changepassword1636476506793' enctype='application/x-www-form-urlencoded'><input type="hidden" name="nof_componentId" value="1636476506793">
<input type="hidden" name="nof_componentGroupId" value="1636473377108">
<input type="hidden" name="nof_packageId" value="com.netobjects.nfxcomp.securesite">
<input type="hidden" name="nof_componentName" value="changepassword">
<input type="hidden" name="nof_componentGroupName" value="banco">
<input type="hidden" name="nof_rootDir" value="../..">
<input type="hidden" name="nof_scriptDir" value="scripts">
<input type="hidden" name="nof_scriptInterfaceFile" value="SecureSite1636473377108.xml.php">
<input type="hidden" name="nof_debug" value="false">
<input type="hidden" name="nof_langFile" value="SecureSite_pt.properties">
<input type="hidden" name="nof_formName" value="changepassword1636476506793">
<table><tr><td nowrap><label for='oldPassword' class='jcarousel-skin-nof .jcarousel-direction-rtl .jcarousel-prev-horizontal'>Digite a senha atual</label></td><td nowrap><?php if(isset($_POST["1636476506793_oldPassword_errorimg"])) echo stripslashes($_POST["1636476506793_oldPassword_errorimg"]); ?></td><td><input class='nof_secureSite_input_password' id='oldPassword' name='oldPassword' maxlength='30' value='' size='15' type='PASSWORD'></td></tr><tr><td nowrap><label for='newPassword' class='nof_secureSite_label'>Selecione uma nova senha</label></td><td nowrap><?php if(isset($_POST["1636476506793_newPassword_errorimg"])) echo stripslashes($_POST["1636476506793_newPassword_errorimg"]); ?></td><td><input class='nof_secureSite_input_password' id='newPassword' name='newPassword' maxlength='30' value='' size='15' type='PASSWORD'></td></tr><tr><td nowrap><label for='retypeNewPassword' class='nof_secureSite_label'>Confirmar a nova senha</label></td><td nowrap><?php if(isset($_POST["1636476506793_retypeNewPassword_errorimg"])) echo stripslashes($_POST["1636476506793_retypeNewPassword_errorimg"]); ?></td><td><input class='nof_secureSite_input_password' id='retypeNewPassword' name='retypeNewPassword' maxlength='30' value='' size='15' type='PASSWORD'></td></tr><tr><td nowrap><label for='changePassword' class='nof_secureSite_label'></label></td><td nowrap><?php if(isset($_POST["1636476506793_changePassword_errorimg"])) echo stripslashes($_POST["1636476506793_changePassword_errorimg"]); ?></td><td><input class='nof_secureSite_input_submit' id='changePassword' name='changePassword' maxlength='0' value='Submit' size='0' type='SUBMIT'></td></tr><tr><td colspan='3'><?php if(isset($_POST["1636476506793_errormessgs"])) echo stripslashes($_POST["1636476506793_errormessgs"]); ?></td></tr></table></form>

<?php
$formName = "changepassword1636476506793";
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
 