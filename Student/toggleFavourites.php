<?php
  require_once 'server/functions.php';
  authenticateUser();
  $studentData = getCurrentSessionStudentData();
  $studentId = $studentData['Student_Id'];
  if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
    $redirect = $_SERVER['HTTP_REFERER'];
  }
  else{
    $redirect = 'myFavourites.php';
  }
  if(isset($_GET['internshipId']) && !empty($_GET['internshipId'])
    && isset($_GET['toggle']) && !empty($_GET['toggle'])
  ){
    $internshipId = $_GET['internshipId'];
    $toggle = $_GET['toggle'];
    if($toggle == 'add'){
      $query = "INSERT INTO student_favourites(Student_Id, Internship_Id) VALUES(
        '$studentId', '$internshipId'
      )";
      $queryExec = mysqli_query($con, $query);
      if($queryExec){
        showMessage('Added to favourites', $redirect);
      }
      else{
        showMessage('Some error occured', $redirect);
      }
    }
    else if($toggle == 'delete'){
      $query = "DELETE FROM student_favourites WHERE Student_Id = '$studentId' AND Internship_Id = '$internshipId'";
      $queryExec = mysqli_query($con, $query);
      if($queryExec){
        showMessage('Removed from favourites', $redirect);
      }
      else{
        showMessage('Some error occured', $redirect);
      }
    }
    else{
      showMessage('Invalid request', $redirect);
    }
  }
?>
