<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form_name']) && $_POST['form_name'] == 'loginform')
{
   $success_page = './site.php';
   $error_page = './error_page.html';
   $database = './usersdb.php';
   $crypt_pass = md5($_POST['password']);
   $found = false;
   $fullname = '';
   $session_timeout = 600;
   if(filesize($database) > 0)
   {
      $items = file($database, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      foreach($items as $line)
      {
         list($username, $password, $email, $name, $active) = explode('|', trim($line));
         if ($username == $_POST['username'] && $active != "0" && $password == $crypt_pass)
         {
            $found = true;
            $fullname = $name;
         }
      }
   }
   if($found == false)
   {
      header('Location: '.$error_page);
      exit;
   }
   else
   {
      if (session_id() == "")
      {
         session_start();
      }
      $_SESSION['username'] = $_POST['username'];
      $_SESSION['fullname'] = $fullname;
      $_SESSION['expires_by'] = time() + $session_timeout;
      $_SESSION['expires_timeout'] = $session_timeout;
      $rememberme = isset($_POST['rememberme']) ? true : false;
      if ($rememberme)
      {
         setcookie('username', $_POST['username'], time() + 3600*24*30);
         setcookie('password', $_POST['password'], time() + 3600*24*30);
      }
      header('Location: '.$success_page);
      exit;
   }
}
$username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
$password = isset($_COOKIE['password']) ? $_COOKIE['password'] : '';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<meta name="author" content="Leandro">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="FlashsafeGrande.png" rel="icon" sizes="219x230" type="image/png">
<link href="font-awesome.min.css" rel="stylesheet">
<link href="Clouds.css" rel="stylesheet">
<link href="index1.css" rel="stylesheet">
<!-- Insert Google Analytics code here -->
</head>
<body>
<div id="container">
<div id="wb_Login1" style="position:absolute;left:458px;top:185px;width:225px;height:236px;z-index:15;">
<form name="loginform" method="post" accept-charset="UTF-8" action="<?php echo basename(__FILE__); ?>" id="loginform">
<input type="hidden" name="form_name" value="loginform">
<table id="Login1">
<tr>
   <td class="header">Log In</td>
</tr>
<tr>
   <td class="label"><label for="username">Nome do usuario</label></td>
</tr>
<tr>
   <td class="row"><input class="input" name="username" type="text" id="username" value="<?php echo $username; ?>"></td>
</tr>
<tr>
   <td class="label"><label for="password">Senha</label></td>
</tr>
<tr>
   <td class="row"><input class="input" name="password" type="password" id="password" value="<?php echo $password; ?>"></td>
</tr>
<tr>
   <td class="row"><input id="rememberme" type="checkbox" name="rememberme"><label for="rememberme">Lembrar</label></td>
</tr>
<tr>
   <td style="text-align:center;vertical-align:bottom"><input class="button" type="submit" name="login" value="Log In" id="login"></td>
</tr>
</table>
</form>
</div>
</div>
<div id="cookie_policy" style="position:fixed;text-align:center;left:0;right:0;bottom:0;height:1135px;z-index:16;display: none;">
<div id="cookie_policy_Container" style="width:970px;position:relative;margin-left:auto;margin-right:auto;text-align:left;">
<div id="wb_cookie_button" style="position:absolute;left:447px;top:626px;width:131px;height:43px;z-index:0;">
<a href="#" onclick="ShowObject('wb_cookie_button', 1);return false;"><div id="cookie_button"><div id="cookie_button_text"><span style="color:#FFFFFF;font-family:Verdana;font-size:24px;"><strong>Concordo</strong></span></div></div></a></div>
<div id="wb_cookie_message" style="position:absolute;left:173px;top:505px;width:633px;height:91px;text-align:center;z-index:1;">
<span style="color:#FFFFFF;font-family:Verdana;font-size:24px;">Este site usa cookies. Ao continuar a navegar no site, você concorda com os termos de uso<br><br><strong><u>Mais informações sobre cookies</u></strong></span></div>
<!-- Cookie script -->
</div>
</div>
<div id="wb_header">
<div id="header">
<div class="row">
<div class="col-1">
<div id="wb_headerIcon1" style="display:inline-block;width:24px;height:33px;text-align:center;z-index:3;">
<a href="tel:1155555555"><div id="headerIcon1"><i class="fa fa-phone"></i></div></a>
</div>
<label for="" id="headerLabel1" style="display:inline-block;width:169px;line-height:16px;z-index:4;">Telefone: +55 11</label>
</div>
<div class="col-2">
<div id="wb_headerIcon2" style="display:inline-block;width:24px;height:33px;text-align:center;z-index:5;">
<a href="mailto:suporte@flashsafe.com.br"><div id="headerIcon2"><i class="fa fa-envelope-o"></i></div></a>
</div>
<label for="" id="headerLabel2" style="display:inline-block;width:250px;line-height:16px;z-index:6;">Email: suporte@flashsafe.com.br</label>
</div>
<div class="col-3">
</div>
<div class="col-4">
</div>
</div>
</div>
</div>
<div id="wb_navigation">
<div id="navigation">
<div class="row">
<div class="col-1">
<div id="wb_navigationHeading" style="display:inline-block;width:100%;z-index:7;">
<h1 id="navigationHeading">FlashSafe DLP</h1>
</div>
</div>
<div class="col-2">
<div id="wb_navigationMenu" style="display:inline-block;width:100%;z-index:1008;">
<div id="navigationMenu" class="navigationMenu" style ="width:100%;height:auto !important;">
<div class="container">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navigationMenu-navbar-collapse">
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
</div>
<div class="navigationMenu-navbar-collapse collapse">
<ul class="nav navbar-nav">
<li class="">
<a href="./index1.php"><i class="fa fa-home"></i>HOME</a>
</li>
<li class="">
<a href="./Adm.php"><i class="fa fa-user-circle"></i>Acesso Administrador</a>
</li>
<li class="">
<a href="./index1.php"><i class="fa fa-user-circle"></i>Esqueceu a Senha</a>
</li>
</ul>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="wb_CardContainer9">
<div id="CardContainer9">
<div id="wb_Card23" style="display:flex;z-index:9;">
   <div id="Card23-card-body">
      <img id="Card23-card-item0" src="images/Flashsafe.jpg" alt="" title="">
      <div id="Card23-card-item1">Login pelo Painel ao lado</div>
      <div id="Card23-card-item2">Usuários Autenticados</div>
      <div id="Card23-card-item3"><i class="fa fa-star"></i></div>
      <div id="Card23-card-item4"><i class="fa fa-star"></i></div>
      <div id="Card23-card-item5"><i class="fa fa-star"></i></div>
      <div id="Card23-card-item6"><i class="fa fa-star"></i></div>
      <div id="Card23-card-item7"><i class="fa fa-star-half-o"></i></div>
      <div id="Card23-card-item8">Por favor entre com usuário e senha <br>fornecidos pelo Suporte Flashsafe</div>
   </div>

</div>
</div>
</div>
<div id="wb_social">
<div id="social">
<div class="row">
<div class="col-1">
<div id="wb_socialIcon1" style="display:inline-block;width:40px;height:40px;text-align:center;z-index:10;">
<a href="https://www.facebook.com/flashsafe.epsoft/" target="_blank"><div id="socialIcon1"><i class="fa fa-facebook"></i></div></a>
</div>
</div>
<div class="col-2">
<div id="wb_socialIcon2" style="display:inline-block;width:40px;height:40px;text-align:center;z-index:11;">
<a href="https://www.instagram.com/flashsafe_dlp/" target="_blank"><div id="socialIcon2"><i class="fa fa-instagram"></i></div></a>
</div>
</div>
<div class="col-3">
<div id="wb_socialIcon3" style="display:inline-block;width:40px;height:40px;text-align:center;z-index:12;">
<a href="./site.php"><div id="socialIcon3"><i class="fa fa-twitter"></i></div></a>
</div>
</div>
<div class="col-4">
<div id="wb_socialIcon4" style="display:inline-block;width:40px;height:40px;text-align:center;z-index:13;">
<a href="https://www.youtube.com/channel/UCxZZc5EwYHcv1AwGaygUaOw" target="_blank"><div id="socialIcon4"><i class="fa fa-youtube"></i></div></a>
</div>
</div>
</div>
</div>
</div>
<div id="wb_links">
<div id="links-divider-top">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 25" preserveAspectRatio="none">
<path class="divider-fill" d="m 0,0 v 3.8265306 c 0,0 393.8,0 483.4,0 9.2,0 16.6,9.4387754 16.6,21.1734694 0,-11.607143 7.4,-21.1734694 16.6,-21.1734694 89.6,0 483.4,0 483.4,0 V 0 Z" />
</svg>
</div>
<div id="links">
<div class="row">
<div class="col-1">
</div>
<div class="col-2">
</div>
<div class="col-3">
</div>
</div>
</div>
</div>
<div id="wb_footer">
<div id="footer">
<div class="row">
<div class="col-1">
<div id="wb_footerText">
<span style="color:#000000;">Copyright © 2021 Infra.&nbsp; All Rights Reserved</span>
</div>
</div>
</div>
</div>
</div>
<script src="jquery-1.12.4.min.js"></script>
<script src="jquery-ui.min.js"></script>
<script src="transition.min.js"></script>
<script src="collapse.min.js"></script>
<script src="dropdown.min.js"></script>
<script src="wwb15.min.js"></script>
<script src="index1.js"></script>
</body>
</html>