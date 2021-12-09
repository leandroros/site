<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__.'/Exception.php';
require __DIR__.'/PHPMailer.php';
require __DIR__.'/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form_name']) && $_POST['form_name'] == 'forgotpasswordform')
{
   $email = isset($_POST['email']) ? addslashes($_POST['email']) : '';
   $found = false;
   $items = array();
   $success_page = '';
   $error_page = basename(__FILE__);
   $database = './usersdb.php';
   if (filesize($database) == 0 || empty($email))
   {
      header('Location: '.$error_page);
      exit;
   }
   else
   {
      $items = file($database, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      foreach($items as $line)
      {
         list($username, $password, $emailaddress, $fullname, $active) = explode('|', trim($line));
         if ($email == $emailaddress && $active != "0")
         {
            $found = true;
         }
      }
   }
   if ($found == true)
   {
      $newpassword = '';
      $alphanum = array('a','b','c','d','e','f','g','h','i','j','k','m','n','o','p','q','r','s','t','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','M','N','P','Q','R','S','T','U','V','W','X','Y','Z','2','3','4','5','6','7','8','9');
      $chars = sizeof($alphanum);
      $a = time();
      mt_srand($a);
      for ($i=0; $i < 6; $i++)
      {
         $randnum = intval(mt_rand(0,55));
         $newpassword .= $alphanum[$randnum];
      }
      $crypt_pass = md5($newpassword);
      $file = fopen($database, 'w');
      foreach($items as $line)
      {
         $values = explode('|', trim($line));
         if ($email == $values[2])
         {
            $values[1] = $crypt_pass;
            $line = '';
            for ($i=0; $i < count($values); $i++)
            {
               if ($i != 0)
                  $line .= '|';
               $line .= $values[$i];
            }
         }
         fwrite($file, $line);
         fwrite($file, "\r\n");
      }
      fclose($file);
      $mailto = $_POST['email'];
      $mailfrom = 'leleoros@gmail.com';
      $subject = 'New password';
      $message = 'Your new password for http://www.yourwebsite.com/ is:';
      $message .= $newpassword;
      $mail = new PHPMailer();
      $mail->IsSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->Port = 587;
      $mail->SMTPAuth = true;
      $mail->Username = 'leleoros@gmail.com';
      $mail->Password = 'Elaine@@1';
      $mail->SMTPSecure = 'tls';
      $mail->From = $mailfrom;
      $mail->FromName = $mailfrom;
      $mail->AddAddress($mailto, "");
      $mail->AddReplyTo($mailfrom);
      $mail->Body = stripslashes($message);
      $mail->Subject = stripslashes($subject);
      $mail->WordWrap = 80;
      if (!$mail->Send())
      {
         die('PHPMailer error: ' . $mail->ErrorInfo);
      }
      header('Location: '.$success_page);
   }
   else
   {
      header('Location: '.$error_page);
   }
   exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Page</title>
<link href="Clouds.css" rel="stylesheet">
<link href="Senha.css" rel="stylesheet">
</head>
<body>
<div id="wb_PasswordRecovery1" style="position:absolute;left:369px;top:353px;width:191px;height:185px;z-index:0;">
<form name="forgotpasswordform" method="post" accept-charset="UTF-8" action="<?php echo basename(__FILE__); ?>" id="forgotpasswordform">
<input type="hidden" name="form_name" value="forgotpasswordform">
<table id="PasswordRecovery1">
<tr>
   <td class="header">Esqueceu sua senha ?</td>
</tr>
<tr>
   <td class="label"><label for="email">Email</label></td>
</tr>
<tr>
   <td class="row"><input class="input" name="email" type="text" id="email"></td>
</tr>
<tr>
   <td style="text-align:center;vertical-align:bottom"><input class="button" type="submit" name="submit" value="Enviar" id="submit"></td>
</tr>
</table>
</form>
</div>
</body>
</html>