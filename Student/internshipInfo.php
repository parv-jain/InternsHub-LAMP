<?php
require_once 'server/functions.php';
authenticateUser();
if(isset($_GET['internshipId']) && !empty($_GET['internshipId'])){
  $internshipId = $_GET['internshipId'];
  $internshipDetails = getInternshipDetailsByInternshipId($internshipId);

  $companyName = getCompanyNameByCompanyId($internshipDetails['Company_Id']);

  $studentDetails = getCurrentSessionStudentData();
  $studentId = $studentDetails['Student_Id'];

  if(isset($_POST['solutionLink']) && !empty($_POST['solutionLink'])){
    $_POST = testInput($_POST);
    $solutionLink = $_POST['solutionLink'];
    $applicationStatus = "In Process";
    date_default_timezone_set('Asia/Kolkata');
    $applicationDate = date('d F Y');

    $resume = $_FILES['resume'];
    $resume_name = $resume['name'].'-'.$applicationDate.'-'.time();
    $resume_type = $resume['type'];
    $resume_size = $resume['size'];
    $resume_tmp_loc = $resume['tmp_name'];

    $errors = array();
    $maxsize = 2097152;
    $acceptable = array('application/pdf');

    if($resume_size >= $maxsize || $resume_size == 0)
      $errors[] = 'File too large, File must be less than 2 megabytes';

    if(!in_array($resume_type, $acceptable) && !empty($resume_type))
      $errors[] = 'Invalid file type, Only PDF file type is accepted';

    if(intershipAlreadyAppliedCheck($internshipId, $studentId))
      $errors[] = 'You have already applied to this Internship';

    if(count($errors) === 0){
      $query = "INSERT INTO internship_applications(Student_Id, Internship_Id, Application_Status, Application_Date, Solution_Link, Resume) VALUES(
        '$studentId',
        '$internshipId',
        '$applicationStatus',
        '$applicationDate',
        '$solutionLink',
        '$resume_name'
      )";
      //chmod("Resumes",0777);
      if(move_uploaded_file($resume_tmp_loc, 'Resumes/'.$resume_name) && mysqli_query($con, $query)){
        $success = "Applied Successfully";
        echo "<script>alert('$success');</script>";
      }
      else{
        $error = "Some error occured, Try again";
        echo "<script>alert('$error');</script>";
      }
    }
    else{
      foreach($errors as $error)
        echo "<script>alert('$error');</script>";
    }
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>InternsHub | <?php echo $internshipDetails['Title'].' at '.$companyName; ?></title>
    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <!--<link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">-->
    <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>

    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom styles by Aditya Tyagi for Students Panel of InternsHub-->
    <link href="css/student.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">

    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
        <a class="navbar-brand" href="#">InternsHub</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="My Profile">
                    <a class="nav-link" href="myProfile.php">
                        <i class="fa fa-fw fa-user"></i>
                        <span class="nav-link-text">My Profile</span>
                    </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Search">
                    <a class="nav-link" href="searchInternship.php">
                        <i class="fa fa-fw fa-search"></i>
                        <span class="nav-link-text">Search</span>
                    </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="My Applications">
                    <a class="nav-link" href="myApplications.php">
                        <i class="fa fa-fw fa-file"></i>
                        <span class="nav-link-text">My Applications</span>
                    </a>
                </li>

                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="My Favourites">
                    <a class="nav-link" href="myFavourites.php">
                        <i class="fa fa-fw fa-heart"></i>
                        <span class="nav-link-text">My Favourites</span>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav sidenav-toggler">
                <li class="nav-item">
                    <a class="nav-link text-center" id="sidenavToggler">
                        <i class="fa fa-fw fa-angle-left"></i>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
                        <i class="fas fa-sign-out-alt"></i>Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Navigation Ends -->

    <div class="content-wrapper">

        <!-- THIS IS THE START OF THE MY PROFILE PAGE -->
        <div class="container-fluid">

            <!-- CONTENT:START -->
            <div class="content">

                <!-- Container for the posts:START-->
                <div class="internship-post border border-secondary rounded-right">
                    <div class="post-header d-flex">
                        <div class="company-name mr-auto p-2">
                            <h4><?php echo $companyName; ?></h4>
                        </div>

                        <div class="apply-by p-2">
                          <p><strong>Date Posted: </strong><?php echo $internshipDetails['Date_Posted']; ?></p>
                        </div>

                        <div class="favourites p-2">
                          <?php
                            if(internshipStudentFavouriteCheck($internshipDetails['Internship_Id'], $studentId)){
                              ?>
                                  <a href="toggleFavourites.php?toggle=delete&internshipId=<?php echo $internshipDetails['Internship_Id']; ?>"><i class="fa fa-heart fa_custom fa-2x"></i></a>
                              <?php
                            }
                            else{
                              ?>
                                  <a href="toggleFavourites.php?toggle=add&internshipId=<?php echo $internshipDetails['Internship_Id']; ?>"><i class="fa fa-heart far fa-2x"></i></a>
                              <?php
                            }
                          ?>
                        </div>

                    </div>

                    <hr style="margin-top:  -5px;">

                    <div class="post-info">
                        <ul>
                          <li><strong>Title: </strong><?php echo $internshipDetails['Title']; ?></li>
                          <li><strong>Posted On: </strong><?php echo $internshipDetails['Date_Posted']; ?></li>
                          <li><strong>Stipend: </strong><?php echo $internshipDetails['Stipend']; ?></li>
                          <li><strong>Number of Applications: </strong><?php echo getNoOfApplicantsByInternshipId($internshipDetails['Internship_Id']); ?></li>
                          <li><strong>Type: </strong><?php echo getInternshipTypeByInternshipTypeId($internshipDetails['Type_Id']); ?></li>
                          <li><strong>Location: </strong><?php echo getInternshipLocationByInternshipLocationId($internshipDetails['Location_Id']); ?></li>
                          <li><strong>Duration: </strong><?php echo getInternshipDurationByInternshipDurationId($internshipDetails['Duration_Id']); ?></li>
                        </ul>
                    </div>

                    <div class="question mr-auto p-2">
                        <h4>Question to Solve:</h4>
                        <p class="question-text">
                          <?php echo $internshipDetails['Question']; ?>
                        </p>
                    </div>

                </div>
                <!-- Container for the posts:ENDS-->

                <?php $companyDetails = getCompanyDetailsByCompanyId($internshipDetails['Company_Id']); ?>
                <!-- Container for the INFORMATION:START-->
                <div class="information border border-secondary rounded-right mr-auto p-2">
                    <div class="info-company">
                        <h5>About <?php echo $companyName; ?></h5>
                        <p class="company-text">
                          <?php echo $companyDetails['Company_Details']; ?>
                        </p>
                    </div>

                    <br />

                    <div class="about-internship">
                        <h5>About the Internship</h5>
                        <?php echo $internshipDetails['Internship_Details'] ?>
                    </div>

                    <br />

                    <div class="skills-required">
                        <h5>Skills Required</h5>
                        <?php echo $internshipDetails['Skills_Required'] ?>
                    </div>

                    <br />

                    <div class="guidelines">
                        <h5>Guidelines To Apply</h5>
                        <p>
                            <ol>
                                <li>In order to apply, you have to solve/complete the question/task given by the company.</li>
                                <li>Once done, you have to UPLOAD IT ON YOUR GOOGLE DRIVE and share the link of that folder here, while applying.</li>
                                <li>The companies will go through your solution/submitted work and will rank you accordingly.</li>
                                <li>You can see the status of your application and your ranking on the LEADERBOARD.</li>
                                <li>Top performers will be selected as interns by the company.</li>
                            </ol>
                        </p>
                    </div>

                    <hr>
                    <div class="apply">
                        <button class="btn btn-success btn-lg btn-block" data-toggle="modal" data-target="#applyConfirmation" id="applyButton">Apply</button>
                    </div>

                    <!--Modal for applying internship: STARTS-->
                    <div class="modal fade" id="applyConfirmation" tabindex="-1" role="dialog" aria-labelledby="applyConfirmationTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Go for it!</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <form method="post" enctype="multipart/form-data">
                                  <div class="modal-body">
                                      <div class="form-group">
                                          <label for="solutionLink">Link for your solution</label>
                                          <input type="text" class="form-control" id="solutionLink" placeholder="Paste Google Drive/DropBox link of your solution" name="solutionLink" required>
                                      </div>
                                      <div class="form-group">
                                          <div class="input-group mb-3">
                                              <div class="input-group-prepend">
                                                  <span class="input-group-text">Upload Resume</span>
                                              </div>
                                              <div class="custom-file">
                                                  <input type="file" class="custom-file-input" id="inputGroupFile01" accept="application/pdf" name="resume" required>
                                                  <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                      <button type="submit" class="btn btn-success">Apply</button>
                                  </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--Modal for applying internship: -->

                </div>
                <!-- Container for the INFORMATION:ENDS-->

                <div class="leaderboard border border-secondary rounded-right mr-auto p-2">
                    <h5>Leaderboard</h5>

                    <table class="table table-responsive">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Rank</th>
                                <th scope="col">Name</th>
                                <th scope="col">Mail</th>
                                <th scope="col">Status</th>
                                <th scope="col">Applied On</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php
                            $leaderBoard = getRankWiseOrder($internshipId);
                            $rank = 0;
                            foreach($leaderBoard as $student){
                              $rank += 1;
                              $studentStatus = $student['Application_Status'];
                              $studentApplicationDate = $student['Application_Date'];
                              $studentData = getStudentDetails($student['Student_Id']);
                              $studentName = $studentData['First_Name'].' '.$studentData['Last_Name'];
                              $studentEmail = $studentData['Email'];
                              if($studentStatus == 'Accepted')
                								echo "<tr class='table-success'>";
                							else if($studentStatus == 'Rejected')
                								echo "<tr class='table-danger'>";
                							else
                								echo "<tr class='table'>";
                							echo "<th scope='row'>$rank</th>
                                  <td>$studentName</td>
                                  <td>$studentEmail</td>
                                  <td>$studentStatus</td>
                                  <td>$studentApplicationDate</td>
                              </tr>";
                            }
                          ?>
                        </tbody>
                    </table>

                </div>


            </div>
            <!-- CONTENT:ENDS -->

        </div>
        <!-- /.container-fluid-->
        <!-- /.content-wrapper-->
        <footer class="sticky-footer">
            <div class="container">
                <div class="text-center">
                    <small>Copyright © Sivana Inc. 2018</small>
                </div>
            </div>
        </footer>
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fa fa-angle-up"></i>
        </a>
        <!-- Logout Modal-->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
        <!-- Page level plugin JavaScript-->
        <script src="vendor/chart.js/Chart.min.js"></script>
        <script src="vendor/datatables/jquery.dataTables.js"></script>
        <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin.min.js"></script>
        <!-- Custom scripts for this page-->
        <script src="js/sb-admin-datatables.min.js"></script>

    </div>
</body>

</html>
<?php
  if(intershipAlreadyAppliedCheck($internshipId, $studentId)){
    ?>
    <script>
      document.getElementById("applyButton").disabled = true;
      document.getElementById("applyButton").innerHTML = "You have already applied for this Internship";
    </script>
    <?php
  }
}
?>
