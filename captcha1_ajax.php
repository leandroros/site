<?php
session_start();
if (isset($_POST['value'],$_SESSION['random_txt']) && md5($_POST['value']) == $_SESSION['random_txt'])
{
   echo "true";
}
else
{
   echo "false";
}
?>