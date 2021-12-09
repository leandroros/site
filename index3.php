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
<link href="iconeFlashsafe.png" rel="icon" sizes="646x520" type="image/png">
<link href="font-awesome.min.css" rel="stylesheet">
<link href="Clouds.css" rel="stylesheet">
<link href="index3.css" rel="stylesheet">
<!-- Insert Google Analytics code here --></head>
<body>
<div id="container">
<div id="wb_Shape1" style="position:absolute;left:456px;top:95px;width:514px;height:464px;z-index:3;">
<div id="Shape1"></div></div>
<div id="wb_Text2" style="position:absolute;left:494px;top:255px;width:341px;height:38px;z-index:4;">
<span style="color:#F5FFFA;font-family:'Roboto Medium';font-size:32px;"><strong>FlashSafe DLP</strong></span></div>
<img src="images/img0007.png" id="Text3" alt="" style="position:absolute;left:494px;top:311px;width:314px;height:160px;z-index:5;">
<div id="wb_Text4" style="position:absolute;left:494px;top:119px;width:121px;height:15px;z-index:6;">
<span style="color:#F5FFFA;font-family:Arial;font-size:13px;">+55 11 0000-0000</span></div>
<div id="wb_Text5" style="position:absolute;left:608px;top:118px;width:212px;height:15px;z-index:7;">
<span style="color:#F5FFFA;font-family:Arial;font-size:13px;">suporte@flashsafe.com.br</span></div>
<div id="wb_Image3" style="position:absolute;left:748px;top:298px;width:208px;height:263px;z-index:8;">
<img src="images/sem%20fundo2.png" id="Image3" alt=""></div>
<div id="wb_Image2" style="position:absolute;left:491px;top:182px;width:197px;height:51px;z-index:9;">
<img src="images/logo flashsafe horizontal.png" id="Image2" alt=""></div>
<div id="wb_Login1" style="position:absolute;left:0px;top:213px;width:451px;height:311px;z-index:10;">
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
<div id="wb_Image1" style="position:absolute;left:3px;top:95px;width:152px;height:39px;z-index:11;">
<img src="images/logo%20flashsafe%20horizontal.jpg" id="Image1" alt=""></div>
<div id="wb_Text1" style="position:absolute;left:2px;top:173px;width:91px;height:29px;z-index:12;">
<span style="color:#696969;font-family:'Roboto Light';font-size:24px;"><strong><em>Entrar</em></strong></span></div>
<img src="images/img0008.png" id="Text7" alt="" style="position:absolute;left:3px;top:241px;width:381px;height:16px;z-index:13;">
<div id="wb_FontAwesomeIcon1" style="position:absolute;left:2px;top:276px;width:33px;height:48px;text-align:center;z-index:14;">
<div id="FontAwesomeIcon1"><i class="fa fa-user"></i></div></div>
<div id="wb_FontAwesomeIcon8" style="position:absolute;left:0px;top:367px;width:37px;height:39px;text-align:center;z-index:15;">
<div id="FontAwesomeIcon8"><i class="fa fa-lock"></i></div></div>
<div id="wb_Shape3" style="position:absolute;left:5px;top:559px;width:965px;height:52px;z-index:16;">
<img src="images/img0009.png" id="Shape3" alt="" style="width:965px;height:52px;"></div>
<div id="wb_Text6" style="position:absolute;left:291px;top:421px;width:131px;height:16px;z-index:17;">
<span style="color:#4973A6;font-family:'Roboto Medium';font-size:13px;"><a href="./construcao.html">Não sei minha senha</a></span></div>
<div id="wb_FontAwesomeIcon2" style="position:absolute;left:799px;top:115px;width:29px;height:20px;text-align:center;z-index:18;">
<a href="mailto:suporte@flashsafe.com.br"><div id="FontAwesomeIcon2"><i class="fa fa-envelope-o"></i></div></a></div>
<div id="wb_FontAwesomeIcon3" style="position:absolute;left:914px;top:116px;width:29px;height:20px;text-align:center;z-index:19;">
<a href="tel:551100000000"><div id="FontAwesomeIcon3"><i class="fa fa-whatsapp"></i></div></a></div>
<div id="wb_FontAwesomeIcon4" style="position:absolute;left:885px;top:115px;width:29px;height:20px;text-align:center;z-index:20;">
<div id="FontAwesomeIcon4"><i class="fa fa-twitter-square"></i></div></div>
<div id="wb_FontAwesomeIcon5" style="position:absolute;left:856px;top:115px;width:29px;height:20px;text-align:center;z-index:21;">
<div id="FontAwesomeIcon5"><i class="fa fa-linkedin-square"></i></div></div>
<div id="wb_FontAwesomeIcon6" style="position:absolute;left:827px;top:116px;width:29px;height:20px;text-align:center;z-index:22;">
<a href="https://www.facebook.com/flashsafe.epsoft/" target="_blank"><div id="FontAwesomeIcon6"><i class="fa fa-facebook-official"></i></div></a></div>
</div>
<div id="wb_header">
<div id="header">
<div class="row">
<div class="col-1">
</div>
<div class="col-2">
</div>
<div class="col-3">
</div>
<div class="col-4">
</div>
</div>
</div>
</div>
<div id="wb_CardContainer9">
<div id="CardContainer9">
</div>
</div>
<div style="z-index:23">
</div>
<div id="cookie_policy" style="position:fixed;text-align:center;left:0;right:0;bottom:0;height:164px;z-index:24;display: none;">
<div id="cookie_policy_Container" style="width:970px;position:relative;margin-left:auto;margin-right:auto;text-align:left;">
<div id="wb_cookie_button" style="position:absolute;left:419px;top:112px;width:131px;height:31px;z-index:0;">
<a href="#"><div id="cookie_button"><div id="cookie_button_text"><span style="color:#FFFFFF;font-family:Verdana;font-size:13px;"><strong>I AGREE</strong></span></div></div></a></div>
<div id="wb_cookie_message" style="position:absolute;left:209px;top:44px;width:550px;height:45px;text-align:center;z-index:1;">
<span style="color:#FFFFFF;font-family:'Roboto Medium';font-size:19px;">Este site usa cookies de sessão se permitido. Ao continuar a navegar no site, você concorda com os termos de uso.</span></div>

</div>
</div>
<script src="jquery-1.12.4.min.js"></script>
<script src="jquery-ui.min.js"></script>
<script src="index3.js"></script>
</body>
</html>