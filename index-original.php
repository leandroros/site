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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="font-awesome.min.css" rel="stylesheet">
<link href="Clouds.css" rel="stylesheet">
<link href="index-original.css" rel="stylesheet">
<!-- Insert Google Analytics code here --></head>
<body>
<div id="wb_CardContainer9">
<div id="CardContainer9">
</div>
</div>
<div id="wb_Shape1">
<div id="Shape1"></div></div>
<div id="wb_Text2">
<span style="color:#F5FFFA;font-family:'Roboto Medium';font-size:43px;"><strong>FlashSafe DLP</strong></span></div>
<img src="images/img0002.png" id="Text3" alt="">
<div id="wb_Image3">
<img src="images/sem%20fundo2.png" id="Image3" alt=""></div>
<div id="wb_Image2">
<img src="images/logo flashsafe horizontal.png" id="Image2" alt=""></div>
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
<div id="wb_Text1">
<span style="color:#696969;font-family:'Roboto Light';font-size:29px;"><strong><em>Entrar</em></strong></span></div>
<img src="images/img0003.png" id="Text7" alt="">
<div id="wb_FontAwesomeIcon1">
<div id="FontAwesomeIcon1"><i class="fa fa-user"></i></div></div>
<div id="wb_FontAwesomeIcon8">
<div id="FontAwesomeIcon8"><i class="fa fa-lock"></i></div></div>
<div id="wb_Shape3">
<img src="images/img0004.png" id="Shape3" alt=""></div>
<div id="wb_Text6">
<span style="color:#4973A6;font-family:'Roboto Medium';font-size:16px;"><a href="./construcao.html">Não sei minha senha</a></span></div>
<div id="cookie_policy">
<div id="cookie_policy_Container">
<div id="wb_cookie_button">
<a href="#"><div id="cookie_button"><div id="cookie_button_text"><span style="color:#FFFFFF;font-family:Verdana;font-size:16px;"><strong>I AGREE</strong></span></div></div></a></div>
<div id="wb_cookie_message">
<span style="color:#FFFFFF;font-family:'Roboto Medium';font-size:24px;">Este site usa cookies de sessão se permitido. Ao continuar a navegar no site, você concorda com os termos de uso.</span></div>

</div>
</div>
<div id="wb_Text8">
<span style="color:#FFFFFF;font-family:Arial;font-size:15px;">Houve a noemação do encarregado pela Proteção de Dados e para quaisquer dúvidas encaminhar e-mail para dpo@flashsafe.com.br</span></div>
<div id="wb_Image1">
<img src="images/Logolago.png" id="Image1" alt=""></div>
<nav id="wb_Breadcrumb1">
<ul id="Breadcrumb1">
<li><a href="http://">o</a></li>
</ul>
</nav>
<div id="wb_FontAwesomeIcon3">
<a href="./site.php"><div id="FontAwesomeIcon3"><i class="fa fa-facebook-square"></i></div></a></div>
<div id="wb_FontAwesomeIcon4">
<a href="./site.php"><div id="FontAwesomeIcon4"><i class="fa fa-instagram"></i></div></a></div>
<div id="wb_FontAwesomeIcon5">
<a href="./site.php"><div id="FontAwesomeIcon5"><i class="fa fa-youtube"></i></div></a></div>
<div id="wb_FontAwesomeIcon6">
<a href="./site.php"><div id="FontAwesomeIcon6"><i class="fa fa-twitter"></i></div></a></div>
<div id="wb_FontAwesomeIcon2">
<a href="./site.php"><div id="FontAwesomeIcon2"><i class="fa fa-twitter"></i></div></a></div>
<div id="wb_Text4">
<span style="color:#F5FFFA;font-family:Arial;font-size:16px;">+55 11 0000-0000</span></div>
<div id="wb_Text5">
<span style="color:#F5FFFA;font-family:Arial;font-size:16px;">suporte@flashsafe.com.br</span></div>
<div style="z-index:27">
</div>
<script src="jquery-1.12.4.min.js"></script>
<script src="index-original.js"></script>
</body>
</html>