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
   header('Location: ./site.php');
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
      header('Location: ./site.php');
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
if (session_id() == "")
{
   session_start();
}
if (!isset($_SESSION['username']))
{
   header('Location: ');
   exit;
}
if ($_SESSION['username'] == 'Leandro')
{
   header('Location: ./Template.php');
   exit;
}
if ($_SESSION['username'] == 'Funchal')
{
   header('Location: ./Funchal.php');
   exit;
}
if ($_SESSION['username'] == 'Sandro')
{
   header('Location: ./Template.php');
   exit;
}
if ($_SESSION['username'] == 'teste')
{
   header('Location: ./Template.php');
   exit;
}
if ($_SESSION['username'] == 'Epsoft')
{
   header('Location: ./epsoft.php');
   exit;
}
if ($_SESSION['username'] == 'admin')
{
   header('Location: ./Adm.php');
   exit;
}
if ($_SESSION['username'] == 'infowin')
{
   header('Location: ./mdf.php');
   exit;
}
if ($_SESSION['username'] == 'Amauri')
{
   header('Location: ./Template.php');
   exit;
}
if ($_SESSION['username'] == 'Abicab')
{
   header('Location: ./Abicab.php');
   exit;
}
if ($_SESSION['username'] == 'Sicab')
{
   header('Location: ./Sicab.php');
   exit;
}
if ($_SESSION['username'] == 'Elitesoft')
{
   header('Location: ./Elitesoft.php');
   exit;
}
if ($_SESSION['username'] == 'Contadata')
{
   header('Location: ./Contadata.php');
   exit;
}
if ($_SESSION['username'] == 'Ecorodovias')
{
   header('Location: ./Ecorodovias.php');
   exit;
}
if ($_SESSION['username'] == 'Webinar')
{
   header('Location: ./Webinar.php');
   exit;
}
if ($_SESSION['username'] == 'Msi')
{
   header('Location: ./MSI.php');
   exit;
}
if ($_SESSION['username'] == 'M2G')
{
   header('Location: ./M2G.php');
   exit;
}
if ($_SESSION['username'] == 'Belson')
{
   header('Location: ./Belson.php');
   exit;
}
if ($_SESSION['username'] == 'Blanver')
{
   header('Location: ./Blanver.php');
   exit;
}
if ($_SESSION['username'] == 'mdf')
{
   header('Location: ./mdf.php');
   exit;
}
if ($_SESSION['username'] == 'icen')
{
   header('Location: ./icen.php');
   exit;
}
if ($_SESSION['username'] == 'Aticon')
{
   header('Location: ./Aticon.php');
   exit;
}
if ($_SESSION['username'] == 'Hoteis')
{
   header('Location: ./Hotel_Vitoria.php');
   exit;
}
if ($_SESSION['username'] == 'Bpaulista')
{
   header('Location: ./BancoPaulista.php');
   exit;
}
if ($_SESSION['username'] == 'RTM')
{
   header('Location: ./RTM.php');
   exit;
}
if ($_SESSION['username'] == 'Protega')
{
   header('Location: ./Protega.php');
   exit;
}
if ($_SESSION['username'] == 'youit')
{
   header('Location: ./youit.php');
   exit;
}
if ($_SESSION['username'] == 'Frionline')
{
   header('Location: ./frionline.php');
   exit;
}
if ($_SESSION['username'] == 'rlct')
{
   header('Location: ./rltc.php');
   exit;
}
if ($_SESSION['username'] == 'Contentti')
{
   header('Location: ./Contetti.php');
   exit;
}
if ($_SESSION['username'] == 'Icts')
{
   header('Location: ./ICTS.php');
   exit;
}
if ($_SESSION['username'] == 'Plastcor')
{
   header('Location: ./Plastcor.php');
   exit;
}
if ($_SESSION['username'] == 'Liveonti')
{
   header('Location: ./liveonti.php');
   exit;
}
if ($_SESSION['username'] == 'Privacynow')
{
   header('Location: ./privacynow.php');
   exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Clouds</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="font-awesome.min.css" rel="stylesheet">
<link href="Clouds.css" rel="stylesheet">
<link href="site.css" rel="stylesheet">
<!-- Insert Google Analytics code here -->
</head>
<body data-spy="scroll">
<header id="PageHeader1" data--10-bottom="height:50px;" data-bottom="height:80px;" data-top="background:rgba(0,0,0,0);" data--10-top="background:rgba(0,0,0,1);">
<div id="PageHeader1_Container">
<div id="wb_FontAwesomeIcon1" data-top="transform:translate(0px,0px) scale(1.0,1.0);" data--10-top="transform:translate(0px,-30px) scale(0.5,0.5);">
<a href="./Abicab.php" target="_blank"><div id="FontAwesomeIcon1"><i class="fa fa-cloud"></i></div></a></div>
<div id="wb_CssMenu1" data-top="top:30px;" data--10-top="top:0px;">
<ul role="menubar">
<li class="firstmain"><a role="menuitem" href="#intro" target="_self">HOME</a>
</li>
<li><a role="menuitem" href="#about" target="_self">DOWNLOAD</a>
</li>
<li><a role="menuitem" href="#team" target="_self">TIME</a>
</li>
<li><a role="menuitem" href="#contact" target="_self">CONTATO</a>
</li>
</ul>
</div>
<div id="wb_Logout1">
<form name="logoutform" method="post" action="<?php echo basename(__FILE__); ?>" id="logoutform">
<input type="hidden" name="form_name" value="logoutform">
<input type="submit" name="logout" value="Logout" id="Logout1">
</form>
</div>
<div id="wb_LoginName1">
<span id="LoginName1">Welcome <?php
if (isset($_SESSION['username']))
{
   echo $_SESSION['username'];
}
else
{
   echo 'logged in';
}
?>!</span></div>

</div>
</header>
<div id="intro">
<div id="intro_Container">
<div id="wb_Text1">
<span style="color:#FFFFFF;font-family:'Trebuchet MS';font-size:75px;"><strong>FlashSafe DLP</strong></span></div>
</div>
</div>
<div id="team">
<div id="team_Container">
<div id="wb_Text4">
<span style="color:#FFFFFF;font-family:'Trebuchet MS';font-size:29px;"><strong>Time de Infraestrutura<br></strong></span></div>
</div>
</div>
<div id="wb_team_grid">
<div id="team_grid">
<div class="row">
<div class="col-1">
<div id="wb_Team1">
<a href="#top" title="Top"><div id="Team1"><i class="fa fa-user-o"></i></div></a>
</div>
<div id="wb_Name1">
<h3 id="Name1">Leandro Oliveira</h3>
</div>
<div id="wb_Text8">
<span style="color:#FFFFFF;font-family:'Trebuchet MS';font-size:16px;">Suporte</span>
</div>
<div id="wb_FontAwesomeIcon5">
<a href="./site.php"><div id="FontAwesomeIcon5"><i class="fa fa-twitter"></i></div></a>
</div>
<div id="wb_FontAwesomeIcon12">
<a href="./site.php"><div id="FontAwesomeIcon12"><i class="fa fa-facebook"></i></div></a>
</div>
<div id="wb_FontAwesomeIcon15">
<a href="./site.php"><div id="FontAwesomeIcon15"><i class="fa fa-linkedin"></i></div></a>
</div>
<hr id="spacer-line1">
</div>
<div class="col-2">
<div id="wb_Team2">
<a href="#top" title="Top"><div id="Team2"><i class="fa fa-user-secret"></i></div></a>
</div>
<div id="wb_Name2">
<h3 id="Name2">Ricardo</h3>
</div>
<div id="wb_Text10">
<span style="color:#FFFFFF;font-family:'Trebuchet MS';font-size:16px;">Gerente Infraestrutura</span>
</div>
<div id="wb_FontAwesomeIcon9">
<a href="./site.php"><div id="FontAwesomeIcon9"><i class="fa fa-twitter"></i></div></a>
</div>
<div id="wb_FontAwesomeIcon13">
<a href="./site.php"><div id="FontAwesomeIcon13"><i class="fa fa-facebook"></i></div></a>
</div>
<div id="wb_FontAwesomeIcon16">
<a href="./site.php"><div id="FontAwesomeIcon16"><i class="fa fa-linkedin"></i></div></a>
</div>
<hr id="spacer-line2">
</div>
<div class="col-3">
<div id="wb_Team3">
<a href="#top" title="Top"><div id="Team3"><i class="fa fa-user"></i></div></a>
</div>
<div id="wb_Name3">
<h3 id="Name3">Jair Alves</h3>
</div>
<div id="wb_Text11">
<span style="color:#FFFFFF;font-family:'Trebuchet MS';font-size:16px;">Suporte</span>
</div>
<div id="wb_FontAwesomeIcon11">
<a href="./site.php"><div id="FontAwesomeIcon11"><i class="fa fa-twitter"></i></div></a>
</div>
<div id="wb_FontAwesomeIcon14">
<a href="./site.php"><div id="FontAwesomeIcon14"><i class="fa fa-facebook"></i></div></a>
</div>
<div id="wb_FontAwesomeIcon17">
<a href="./site.php"><div id="FontAwesomeIcon17"><i class="fa fa-linkedin"></i></div></a>
</div>
<hr id="spacer-line3">
</div>
<div class="col-4">
</div>
</div>
</div>
</div>
<div id="wb_contact">
<div id="contact">
<div class="row">
<div class="col-1">
<div id="wb_Text6">
<span style="color:#FFFFFF;font-family:'Trebuchet MS';font-size:29px;"><strong>Contato</strong></span>
</div>
<div id="wb_Text7">
<span style="color:#FFFFFF;font-family:Arial;font-size:16px;">suporte@flashsafe.com.br</span>
</div>
<div id="wb_FontAwesomeIcon3">
<a href="#top" title="Top"><div id="FontAwesomeIcon3"><i class="fa fa-twitter"></i></div></a>
</div>
<div id="wb_FontAwesomeIcon2">
<a href="#top" title="Top"><div id="FontAwesomeIcon2"><i class="fa fa-facebook"></i></div></a>
</div>
<div id="wb_FontAwesomeIcon6">
<a href="#top" title="Top"><div id="FontAwesomeIcon6"><i class="fa fa-linkedin"></i></div></a>
</div>
<div id="wb_Text9">
<span style="color:#000000;font-family:'Trebuchet MS';font-size:16px;"><br></span><span style="color:#777777;font-family:'Trebuchet MS';font-size:16px;">Copyright © FlashSafe 2021</span><span style="color:#000000;font-family:'Trebuchet MS';font-size:16px;"><br></span>
</div>
</div>
</div>
</div>
</div>

<script src="jquery-1.12.4.min.js"></script>
<script src="skrollr.min.js"></script>
<script src="jquery-ui.min.js"></script>
<script src="scrollspy.min.js"></script>
<script src="wwb15.min.js"></script>
<script src="site.js"></script>
</body>
</html>