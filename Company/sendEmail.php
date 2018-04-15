<?php
  require_once 'server/functions.php';

  if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
    $redirect = $_SERVER['HTTP_REFERER'];
  else
    $redirect = 'profile.php';


  if(isset($_POST['emailSubject']) && !empty($_POST['emailSubject'])
    && isset($_POST['message']) && !empty($_POST['message'])
    && isset($_POST['email']) && !empty($_POST['email'])
  ){
    $_POST = testInput($_POST);
    $emailSubject = $_POST['emailSubject'];
    $message = $_POST['message'];
    $email = $_POST['email'];
    $headers = "From: no-reply@internshub.com";
    if(mail($email, $emailSubject, $message, $headers)){
      showMessage("Email Sent", $redirect);
    }
    else{
      showMessage("Error in sending email, Try again later", $redirect);
    }
  }
?>
