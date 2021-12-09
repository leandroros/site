<?php
if (session_id() == "")
{
   session_start();
}
if (session_id() == "")
{
   session_start();
}
if (!isset($_SESSION['username']))
{
   $_SESSION['referrer'] = $_SERVER['REQUEST_URI'];
   header('Location: ');
   exit;
}
if (isset($_SESSION['expires_by']))
{
   $expires_by = intval($_SESSION['expires_by']);
   if (time() < $expires_by)
   {
      $_SESSION['expires_by'] = time() + intval($_SESSION['expires_timeout']);
   }
   else
   {
      unset($_SESSION['username']);
      unset($_SESSION['expires_by']);
      unset($_SESSION['expires_timeout']);
      $_SESSION['referrer'] = $_SERVER['REQUEST_URI'];
      header('Location: ');
      exit;
   }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form_name']) && $_POST['form_name'] == 'logoutform')
{
   if (session_id() == "")
   {
      session_start();
   }
   unset($_SESSION['username']);
   unset($_SESSION['fullname']);
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Download FlashSafe</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="logoFlash.png" rel="icon" sizes="50x50" type="image/png">
<link href="font-awesome.min.css" rel="stylesheet">
<link href="Clouds.css" rel="stylesheet">
<link href="Elitesoft.css" rel="stylesheet">
</head>
<body>
<div id="wb_header" style="overflow:hidden">
<div id="header">
<div class="col-1">
<div id="wb_FontAwesomeIcon1" style="display:inline-block;width:50px;height:50px;text-align:center;z-index:0;">
<div id="FontAwesomeIcon1"><i class="fa fa-user"></i></div>
</div>
</div>
<div class="col-2">
<div id="wb_LoginName1" style="display:inline-block;width:100%;z-index:1;">
<span id="LoginName1">Bem Vindo, <?php
if (isset($_SESSION['username']))
{
   echo $_SESSION['fullname'];
}
else
{
   echo 'Deslogado';
}
?>!</span>
</div>
</div>
<div class="col-3">
<div id="wb_JavaScript2" style="display:inline-block;width:100%;z-index:2;">
<div id="greeting" style="font-family:Arial;font-size:20px;color:#000080;font-weight:normal;font-style:normal;text-align:left;text-decoration:none;"></div> 
</div>
</div>
<div class="col-4">
<div id="wb_Logout1" style="display:inline-block;width:100%;text-align:center;z-index:3;">
<form name="logoutform" method="post" action="<?php echo basename(__FILE__); ?>" id="logoutform" style="display:inline">
<input type="hidden" name="form_name" value="logoutform">
<input type="submit" name="logout" value="Logout" id="Logout1">
</form>

</div>
</div>
<div class="col-5">
<div id="wb_Image2" style="display:inline-block;width:118px;height:30px;z-index:4;">
<img src="images/logo%20flashsafe%20horizontal.jpg" id="Image2" alt="">
</div>
</div>
<div class="col-6">
</div>
</div>
</div>
<div id="wb_welcome">
<div id="welcome">
<div class="row">
<div class="col-1">
<div id="wb_welcomeHeading1" style="display:inline-block;width:100%;z-index:5;">
<h1 id="welcomeHeading1">Cliente  ELITESOFT</h1>
</div>
<hr id="welcomeLine" style="display:inline-block;width:410px;z-index:6;">
<div id="wb_welcomeHeading2" style="display:inline-block;width:100%;z-index:7;">
<h2 id="welcomeHeading2">Clique abaixo em Download para baixar o FlashSafe DLP</h2>
</div>
<a id="welcomeButton1" href="https://deploydlp.s3.sa-east-1.amazonaws.com/Elitesoft/ClientDLP1_70_2.exe" rel="nofollow" target="_blank" title="FlashSafe DLP" style="display:inline-block;width:172px;height:38px;z-index:8;">Download</a>
</div>
</div>
</div>
</div>
<div id="wb_infoBlock1">
<div id="infoBlock1">
<div class="col-1">
<div id="wb_infoBlock1Card1" style="display:flex;width:calc(100% - 20px);z-index:9;">
   <div id="infoBlock1Card1-card-body">
      <div id="infoBlock1Card1-card-item0"><a href="./index.php"><i class="fa fa-chevron-circle-left"></i></a></div>
      <div id="infoBlock1Card1-card-item1">Login</div>
      <div id="infoBlock1Card1-card-item2">Voltar novamente para página de login</div>
   </div>

</div>
<div id="wb_infoBlock1Card2" style="display:flex;width:calc(100% - 20px);z-index:10;">
   <div id="infoBlock1Card2-card-body">
      <div id="infoBlock1Card2-card-item0"><a href="https://flashsafe.com.br/" target="_blank" title="FlashSafe"><i class="fa fa-globe"></i></a></div>
      <div id="infoBlock1Card2-card-item1">FlashSafe</div>
      <div id="infoBlock1Card2-card-item2">Visitar página da FlashSafe</div>
   </div>

</div>
</div>
<div class="col-2">
<hr id="infoBlock1Spacer" style="display:block;width: 100%;z-index:11;">
</div>
</div>
</div>
<div id="wb_chooseUs">
<div id="chooseUs">
<div class="row">
<div class="col-1">
<div id="wb_chooseUsHeading1" style="display:inline-block;width:100%;z-index:12;">
<h1 id="chooseUsHeading1">Problemas com Download Contate-nos</h1>
</div>
<a id="Button1" href="https://join.skype.com/tFXExV2dIyD5" target="_blank" title="Contato" style="display:inline-block;width:172px;height:38px;z-index:13;">Skype</a>
</div>
</div>
</div>
</div>

<div style="z-index:19">
</div>
<script src="jquery-1.12.4.min.js"></script>
<script src="jquery-ui.min.js"></script>
<script src="Elitesoft.js"></script>
</body>
</html>