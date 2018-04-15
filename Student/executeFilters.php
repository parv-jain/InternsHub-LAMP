<?php
require_once 'server/functions.php';
authenticateUser();
$studentData = getCurrentSessionStudentData();
$studentId = $studentData['Student_Id'];
if(isset($_GET['categoryId']) && isset($_GET['locationId']) && isset($_GET['durationId']) && isset($_GET['typeId'])){
  $categoryId = $_GET['categoryId'];
  $locationId = $_GET['locationId'];
  $durationId = $_GET['durationId'];
  $typeId = $_GET['typeId'];
  $query = "SELECT * FROM internships WHERE Category_Id LIKE '$categoryId' AND Location_Id LIKE '$locationId' AND Duration_Id LIKE '$durationId' AND Type_Id LIKE '$typeId'";
  $queryExec = mysqli_query($con, $query);
  $noOfInternshipsFound = mysqli_num_rows($queryExec);
  echo "<b>No. of internships found: $noOfInternshipsFound</b>";
  while($internship = mysqli_fetch_assoc($queryExec)) {
    $internshipDetails = getInternshipDetailsByInternshipId($internship['Internship_Id']);
    ?>
    <!-- Container for the posts:START-->
    <div class="internship-post border border-secondary rounded-right">
        <div class="post-header d-flex">
            <div class="company-name mr-auto p-2">
                <h4><?php echo getCompanyNameByCompanyId($internshipDetails['Company_Id']); ?></h4>
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
                        $leaderBoard = getRankWiseOrder($internship['Internship_Id']);
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
}
?>
