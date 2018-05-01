<?php
require_once 'server/functions.php';
authenticateUser();
$studentData = getCurrentSessionStudentData();
$studentId = $studentData['Student_Id'];
$applications = getStudentAppliedInternshipsByStudentId($studentId);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>InternsHub</title>
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

              <?php
              foreach ($applications as $application) {
                $internshipDetails = getInternshipDetailsByInternshipId($application['Internship_Id']);
                ?>
                <!-- Container for the posts:START-->
                <div class="internship-post border border-secondary rounded-right">
                    <div class="post-header d-flex">
                        <div class="company-name mr-auto p-2">
                            <h4><?php echo getCompanyNameByCompanyId($internshipDetails['Company_Id']); ?></h4>
                        </div>

                        <div class="apply-by p-2">
                            <p><strong>Applied On: </strong><?php echo $application['Application_Date']; ?></p>
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
                            <li><strong><a href="internshipInfo.php?internshipId=<?php echo $internshipDetails['Internship_Id']; ?>">Apply/Details</a></strong></li>
                        </ul>
                    </div>

                    <div class="question mr-auto p-2">
                        <h4>Question to Solve:</h4>
                        <p class="question-text">
                          <?php echo $internshipDetails['Question']; ?>
                        </p>
                    </div>

                    <div class="view-leaderboard mr-auto p-2">
                        <p>
                            <button class="btn btn-primary  btn-lg btn-block" id="view-leaderboard-btn" type="button" data-toggle="collapse" data-target="<?php echo '#view-leaderboard-'.$internshipDetails['Internship_Id'] ?>" aria-expanded="false" aria-controls="<?php echo 'view-leaderboard-'.$internshipDetails['Internship_Id'] ?>">View Leaderboard    <i class="fa fa-angle-right"></i></button>
                        </p>
                    </div>
                    <div class="collapse" id="<?php echo 'view-leaderboard-'.$internshipDetails['Internship_Id'] ?>">
                        <div class="card card-body">
                            <h5>Leaderboard</h5>

                            <table class="table table-responsive" style="display:table;">
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
                                    $leaderBoard = getRankWiseOrder($application['Internship_Id']);
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
                </div>
                <!-- Container for the posts:ENDS-->
                <?php
              }
              ?>

            </div>
            <!-- CONTENT:ENDS -->


        </div>
        <!-- /.container-fluid-->
        <!-- /.content-wrapper-->
        <footer class="sticky-footer">
            <div class="container">
                <div class="text-center">
                    <small>Major Project May 2018</small>
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
        <!-- Custom scripts for student: front-end-->
        <script src="js/student.js"></script>
    </div>
</body>

</html>
