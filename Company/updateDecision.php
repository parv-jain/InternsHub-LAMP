<?php
require_once 'server/functions.php';
authenticateUser();
if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
  $redirect = $_SERVER['HTTP_REFERER'];
else
  $redirect = 'profile.php';

if(isset($_GET['accept']) && isset($_GET['applicationId']) && !empty($_GET['applicationId'])){
  $_GET = testInput($_GET);
  acceptInternshipApplication($_GET['applicationId'], $redirect);
}
else if(isset($_GET['reject']) && isset($_GET['applicationId']) && !empty($_GET['applicationId'])){
  $_GET = testInput($_GET);
  rejectInternshipApplication($_GET['applicationId'], $redirect);
}
else{
  showMessage('Invalid Request', $redirect);
}
?>
