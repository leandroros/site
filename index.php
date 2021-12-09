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
<title>Login Flashsafe</title>
<meta name="generator" content="WYSIWYG Web Builder 15 - http://www.wysiwygwebbuilder.com">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="logoFlash.png" rel="icon" sizes="50x50" type="image/png">
<link href="iconeFlashsafe.png" rel="apple-touch-icon" sizes="646x520">
<link href="font-awesome.min.css" rel="stylesheet">
<link href="Clouds.css" rel="stylesheet">
<link href="index.css" rel="stylesheet">
<!-- Insert Google Analytics code here -->
</head>
<body>
<div id="container">
<div id="wb_Shape1">
<div id="Shape1"></div></div>
<div id="wb_Text2">
<span style="color:#F5FFFA;font-family:'Roboto Medium';font-size:53px;"><strong>FlashSafe DLP</strong></span></div>
<img src="images/img0013.png" id="Text3" alt="">
<div id="wb_Image2">
<img src="images/logo flashsafe horizontal.png" id="Image2" alt=""></div>
<div id="wb_FontAwesomeIcon5">
<a href="https://www.facebook.com/flashsafe.epsoft/" target="_blank"><div id="FontAwesomeIcon5"><i class="fa fa-facebook-square"></i></div></a></div>
<div id="wb_FontAwesomeIcon6">
<a href="mailto:suporte@flashsafe.com.br"><div id="FontAwesomeIcon6"><i class="fa fa-envelope"></i></div></a></div>
<div id="wb_FontAwesomeIcon7">
<a href="./index.php"><div id="FontAwesomeIcon7"><i class="fa fa-linkedin-square"></i></div></a></div>
<div id="wb_FontAwesomeIcon8">
<a href="./index.php"><div id="FontAwesomeIcon8"><i class="fa fa-twitter"></i></div></a></div>
<div id="wb_FontAwesomeIcon9">
<a href="tel:551155559914"><div id="FontAwesomeIcon9"><i class="fa fa-whatsapp"></i></div></a></div>
<div id="wb_Text4">
<span style="color:#F5FFFA;font-family:Arial;font-size:16px;">11 5555-9914</span></div>
<div id="wb_Text5">
<span style="color:#F5FFFA;font-family:Arial;font-size:16px;">suporte@flashsafe.com.br</span></div>
<div id="wb_Login1">
<form name="loginform" method="post" accept-charset="UTF-8" action="<?php echo basename(__FILE__); ?>" id="loginform">
<input type="hidden" name="form_name" value="loginform">
<table id="Login1">
<tr>
   <td class="label"><label for="username"></label></td>
</tr>
<tr>
   <td class="row"><input class="input" name="username" type="text" id="username" value="<?php echo $username; ?>"></td>
</tr>
<tr>
   <td class="label"><label for="password"></label></td>
</tr>
<tr>
   <td class="row"><input class="input" name="password" type="password" id="password" value="<?php echo $password; ?>"></td>
</tr>
<tr>
   <td class="row"><input id="rememberme" type="checkbox" name="rememberme"><label for="rememberme">Manter-me conectado</label></td>
</tr>
<tr>
   <td style="text-align:center;vertical-align:bottom"><input class="button" type="submit" name="login" value="                                Entrar" id="login"></td>
</tr>
</table>
</form>
</div>
<div id="wb_Text6">
<span style="color:#4973A6;font-family:'Roboto Medium';font-size:15px;"><a href="./construcao.html">Não sei minha senha</a></span></div>
<div id="wb_FontAwesomeIcon1">
<div id="FontAwesomeIcon1"><i class="fa fa-lock"></i></div></div>
<div id="wb_FontAwesomeIcon2">
<div id="FontAwesomeIcon2"><i class="fa fa-user"></i></div></div>
<img src="images/img0014.png" id="Text7" alt="">
<div id="wb_Text1">
<span style="color:#696969;font-family:'Roboto Light';font-size:29px;"><strong><em>Entrar</em></strong></span></div>
<div id="wb_Image1">
<img src="images/Logolago.png" id="Image1" alt=""></div>
<div id="wb_Image3">
<img src="images/sem%20fundo2.png" id="Image3" alt=""></div>
<div id="wb_Shape3">
<img src="images/img0015.png" id="Shape3" alt=""></div>
<div id="wb_Text8">
<span style="color:#FFFFFF;font-family:Arial;font-size:11px;">Houve a nomeação do encarregado pela Proteção de Dados e para quaisquer dúvidas encaminhar e-mail para dpo@flashsafe.com.br</span></div>
</div>
<div style="z-index:21">
</div>
<script src="index.js"></script>
</body>
</html>