<?php
if ( session_id() == "" ) @session_start();
?>
<?php
error_reporting(0);
$nof_suiteName="SecureSite";
$nof_debug = "false";
$nof_langFile = "../scripts/SecureSite_pt.properties";
$nof_rootDir = "..";
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
<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<meta charset="UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="Generator" content="NetObjects (http://NetObjects.com)"/>
<script type="text/javascript" src="../jquery.js">
</script>
<script type="text/javascript" src="../navbars.js">
</script>
<link rel="stylesheet" type="text/css" href="../fusion.css">
<link rel="stylesheet" type="text/css" href="../style.css">
<link rel="stylesheet" type="text/css" href="../site.css">
<style type="text/css" title="NOF_STYLE_SHEET">
<!--
body { margin:0px; width: 960px; }
div#LayoutLYR { float:left; position:absolute; }
div#NavigationBar1LYR { position:absolute; top:30px; left:100px; width:303px; height:36px; z-index:1 }
div#NOFSecureSite1LYR { position:absolute; top:155px; left:111px; width:265px; height:105px; z-index:2 }
-->
</style>

<script type="text/javascript" src="./login_nof.js">
</script>
</head>
<body>
  <div id="LayoutLYR">
    <div id="NOFSecureSite1LYR">
<?php
if (NOF_fileExists("../scripts/ss_login.php")) {
?>


<!-- <img id="NOFSecureSite1" height="105" width="265" src="../icon_loginmodule.gif" alt=""> -->
<form accept-charset='UNKNOWN' method='POST' target='_self' action='../scripts/ss_login.php' name='login1636476040316' enctype='application/x-www-form-urlencoded'><input type="hidden" name="nof_componentId" value="1636476040316">
<input type="hidden" name="nof_componentGroupId" value="1636473377108">
<input type="hidden" name="nof_packageId" value="com.netobjects.nfxcomp.securesite">
<input type="hidden" name="nof_componentName" value="login">
<input type="hidden" name="nof_componentGroupName" value="banco">
<input type="hidden" name="nof_rootDir" value="..">
<input type="hidden" name="nof_scriptDir" value="scripts">
<input type="hidden" name="nof_scriptInterfaceFile" value="SecureSite1636473377108.xml.php">
<input type="hidden" name="nof_debug" value="false">
<input type="hidden" name="nof_langFile" value="SecureSite_pt.properties">
<input type="hidden" name="nof_formName" value="login1636476040316">
<table><tr><td nowrap><label for='username' class='ui-accordion'>Nome de usuário</label></td><td nowrap><?php if(isset($_POST["1636476040316_username_errorimg"])) echo stripslashes($_POST["1636476040316_username_errorimg"]); ?></td><td><input class='nof_secureSite_input_text' id='username' name='username' maxlength='30' value='' size='15' type='TEXT'></td></tr><tr><td nowrap><label for='password' class='nof_secureSite_label'>Senha</label></td><td nowrap><?php if(isset($_POST["1636476040316_password_errorimg"])) echo stripslashes($_POST["1636476040316_password_errorimg"]); ?></td><td><input class='nof_secureSite_input_password' id='password' name='password' maxlength='30' value='' size='15' type='PASSWORD'></td></tr><tr><td nowrap><label for='LOGIN' class='nof_secureSite_label'></label></td><td nowrap><?php if(isset($_POST["1636476040316_LOGIN_errorimg"])) echo stripslashes($_POST["1636476040316_LOGIN_errorimg"]); ?></td><td><input class='nof_secureSite_input_submit' id='LOGIN' name='LOGIN' maxlength='0' value='Login' size='0' type='SUBMIT'></td></tr><tr><td colspan='3'><?php if(isset($_POST["1636476040316_errormessgs"])) echo stripslashes($_POST["1636476040316_errormessgs"]); ?></td></tr></table></form>
<?php
$formName = "login1636476040316";
if (NOF_fileExists("../scripts/ss_remembervalues.php")) include("../scripts/ss_remembervalues.php");
}
?>
</div>
    <div id="NavigationBar1LYR" style="z-index: 1000">
      <ul id="NavigationBar1" style="z-index: 1000; display: none;">
        <li id="Botãodenavegação1"><a href="../index.php" title="Home" style="line-height: 0">Home</a></li>
        <li id="Botãodenavegação2"><a href="../Login/login.php" title="Login" style="line-height: 0">Login</a>
          <ul id="NavigationBar1_1">
            <li id="Botãodenavegação4"><a href="../Login/Adm/adm.php" title="Adm" style="line-height: 0">Adm</a></li>
            <li id="Botãodenavegação5"><a href="../Login/Senha/senha.php" title="Senha" style="line-height: 0">Senha</a></li>
            <li id="Botãodenavegação6"><a href="../Login/cadastro/cadastro.php" title="cadastro" style="line-height: 0">cadastro</a></li>
          </ul>
        </li>
        <li id="Botãodenavegação3"><a href="../Tabiban/tabiban.php" title="Tabiban" style="line-height: 0">Tabiban</a></li>
      </ul>
    </div>
  </div>
</body>
</html>
 