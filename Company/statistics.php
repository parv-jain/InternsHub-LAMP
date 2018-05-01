<?php
	//head
	require 'components/head.php';
	//Navigation
	require 'components/nav.php';
?>
<?php
	require_once 'server/functions.php';
	authenticateUser();
	if(isset($_GET['internshipId']) && !empty($_GET['internshipId'])){
		$internshipId = $_GET['internshipId'];
		if(isset($_POST['applicationId']) && !empty($_POST['applicationId'])
			&& isset($_POST['score']) && !empty($_POST['score'])
		){
			$applicationId = $_POST['applicationId'];
			$score = $_POST['score'];
			updateInternshipApplicationScore($applicationId, $score);
		}
?>


<style type="text/css">
	#leaderboard td,
	#leaderboard th {
		vertical-align: middle;
	}
	.submission-tile {

	}
	.submission-name {
		font-size: 18px;
		font-weight: bold;
	}
	.submission-rank {
		width: 50px;
	}
</style>

<div class="content-wrapper">
	<div class="container-fluid">

		<!-- Breadcrumbs-->
		<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="#">Statistics</a>
		</li>
		</ol>

		<div class="pb-2" id="">
			<div class="card card-body">
				<h5>Leaderboard</h5>
				<table class="table table-responsive" id="leaderboard" style="display:table;">
					<thead class="thead-dark">
						<tr>
							<th scope="col">Rank</th>
							<th scope="col">Name</th>
							<th scope="col">Mail</th>
							<th scope="col">Status</th>
							<th scope="col">Application Date</th>
							<th scope="col">Email</th>
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
									<td><button class='btn btn-success' data-toggle='modal' data-target='#$rank'>Send</button></td>
							</tr>";
							$internshipData = getInternshipDetailsByInternshipId($internshipId);
							$internshipTitle = $internshipData['Title'];
							$companyName = getCompanyNameByCompanyId($internshipData['Company_Id']);
							$emailSubject = "InternsHub Email Update | $internshipTitle at $companyName";
							?>
							<!-- Send Email Modal -->
							<div class="modal fade" id="<?php echo $rank; ?>" tabindex="-1" role="dialog" aria-labelledby="updateConfirmationTitle" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalLongTitle">Send Email to <?php echo $studentName; ?></h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<form method="post" action="sendEmail.php">
											<div class="modal-body">
												<div class="form-row">
													<div class="form-group col-md-6">
															<label for="emailSubject">Email Subject</label>
															<input type="text" name="emailSubject" class="form-control" id="emailSubject" placeholder="Email Subject" value="<?php echo $emailSubject ?>" readonly>
													</div>
													<div class="form-group col-md-6">
															<label for="email">Email</label>
															<input type="email" name="email" class="form-control" id="email" placeholder="Email" value="<?php echo $studentEmail; ?>" readonly>
													</div>
												</div>
												<div class="form-row">
													<div class="form-group col-md-12">
															<label for="message">Message</label>
															<textarea name="message" class="form-control" id="message" placeholder="Enter your message"></textarea>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
												<button type="submit" class="btn btn-primary" id="accept-confirm">Send</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>
			</div>

			<div class="" id="">
				<div class="card card-body">
				<h5>Submissions</h5>
				<a href="https://admin.speedexam.net/result.aspx?SSTATE=m6ppieobXCi7lSazbcZbCvJDHnYX+HT0IRiGeWdegLySxAyzK7IYQfn1j0Bv54ApJaUMTo6qpKmFDSY0+b/75Bg5aioX8aEM72t/nlbC8qI=" target="_blank">Click here to see test results</a>
				<?php
				$applications = getApplicationsByInternshipId($internshipId);
				foreach($applications as $application){
					$studentId = $application['Student_Id'];
					$studentName = getStudentNameByStudentId($studentId);
					?>
					<?php
					if(getInternshipApplicationStatus($application['Application_Id']) == 'Rejected')
						echo '<div class="card mb-2 alert-danger submission-tile">';
					else if(getInternshipApplicationStatus($application['Application_Id']) == 'Accepted')
						echo '<div class="card mb-2 alert-success submission-tile">';
					else
						echo '<div class="card mb-2 submission-tile">';
					?>
						<div class="card-header">
							<span class="submission-name">
								<?php echo $studentName; ?>
							</span>
							<form action="" class="pull-right m-0" method="post">
								<input type="hidden" name="applicationId" value="<?php echo $application['Application_Id']; ?>">
								<label for="rank" class="m-0">Student Internship Score</label>
								<input class="submission-rank" type="number" name="score" max="100" min="1" value="<?php echo $application['Student_Internship_Score'] ?>">
								<br><hr>
								<button type="submit" class="btn btn-success pull-right mx-1">Update Score</button>
							</form>
						</div>
						<div class="card-body">
							<div class="form-row">
							<div class="form-group col-md-3">
							<label for="companyName">Solution link</label>
							</div>

							<div class="form-group col-md-6">
							<input type="text" class="form-control" placeholder="Drive link" value="<?php echo $application['Solution_Link']; ?>" readonly>
							</div>
							<div class="form-group col-md-3">
								<a target="_blank" href="<?php echo $application['Solution_Link']; ?>" class="w-100 btn btn-primary">View Solution</a>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-3">
							<label for="companyName">Resume</label>
							</div>
							<div class="form-group col-md-9">
								<a target="_blank" href="<?php echo '../Student/Resumes/'.$application['Resume']; ?>" class="w-100 btn btn-primary">Click here to view resume</a>
							</div>
						</div>
						</div>
						<div class="card-footer text-muted">
							Applied on
							<span class="submission-date">
								<?php echo $application['Application_Date']; ?>
							</span>
							<a href="updateDecision.php?accept&applicationId=<?php echo $application['Application_Id']; ?>"><button class="btn btn-success pull-right mx-1 submission-accept">Accept</button></a>
							<a href="updateDecision.php?reject&applicationId=<?php echo $application['Application_Id']; ?>"><button class="btn btn-danger pull-right mx-1 submission-reject">Reject</button></a>
						</div>
					</div>
					<?php
				}
				?>
				</div>
			</div>
	</div><!-- /.container-fluid-->
</div><!-- /.content-wrapper-->

<?php
	//footer
	require 'components/footer.php';
	//Logout Modal
	require 'components/logout.php';
	require 'components/scripts.php';
?>
<script>
	$(document).ready(function(){
		$(document).on('click','.submission-accept',function() {

			$(this).parents('.submission-tile').addClass('alert-success');
			$(this).parents('.submission-tile').removeClass('alert-danger');
		});
		$(document).on('click','.submission-reject',function() {
			$(this).parents('.submission-tile').addClass('alert-danger');
			$(this).parents('.submission-tile').removeClass('alert-success');
		});
	});

</script>
<?php
}
?>
