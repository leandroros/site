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
error_reporting(0);
$nof_debug = "false";
$nof_langFile = "../../scripts/SecureSite_pt.properties";
$nof_suiteName = "SecureSite";
$nof_componentId="1636476297804";
$nof_componentName="admin";
$nof_rootDir="../..";
$nof_scriptDir="scripts";
$nof_scriptInterfaceFile="SecureSite1636473377108.xml.php";
if (NOF_fileExists("../../scripts/ss_secureadminpage.php")) include("../../scripts/ss_secureadminpage.php");
?>
<!DOCTYPE html>
<html>
<head>
<title>Adm</title>
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
div#NOFSecureSite1LYR { position:absolute; top:148px; left:118px; width:188px; height:220px; z-index:2 }
-->
</style>

<script type="text/javascript" src="./adm_nof.js">
</script>
</head>
<body>
  <div id="LayoutLYR">
    <div id="NOFSecureSite1LYR">

<!-- <img id="NOFSecureSite1" height="220" width="188" src="../../Login/Adm/icon_adminmodule.gif" alt=""> -->


<?php
$nof_componentId="1636476297804";
$nof_rootDir="../..";
$nof_scriptDir="scripts";
$nof_scriptInterfaceFile="SecureSite1636473377108.xml.php";
$nof_debug = "false";
$nof_langFile = "../../scripts/SecureSite_pt.properties";
$nof_suiteName = "SecureSite";
if (NOF_fileExists("../../scripts/ss_admin.php")) include("../../scripts/ss_admin.php");
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
 