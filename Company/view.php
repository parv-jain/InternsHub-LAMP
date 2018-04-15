<?php
	//head
	require 'components/head.php';
	//Navigation
	require 'components/nav.php';
?>
<?php
	require_once 'server/functions.php';
	authenticateUser();
	$companyData = getCurrentSessionCompanyData();
	$companyId = $companyData['Company_Id'];
	$companyInternshipsData = getCompanyInternshipsData($companyId);
?>

<style type="text/css">
	.internship-post {
		text-decoration: none;
		color: #555;
		transition: .5s;
		border: 1px #ddd solid !important;
		margin-bottom: 20px;
	}
	.internship-post:hover {
		text-decoration: none;
		color: #222;
		background: #ddd;
	}
	.post-header {

	}
	.internship-title {
		margin-bottom: 0;
	}
	.internship-info {

	}
</style>

<div class="content-wrapper">
	<div class="container-fluid">

		<!-- Breadcrumbs-->
		<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="#">Review postings</a>
		</li>
		</ol>

		 <div class="content">

			 <?php
			 	foreach($companyInternshipsData as $internshipDetails){
					?>
					<!-- Internship post-->
					<a href="statistics.php?internshipId=<?php echo $internshipDetails['Internship_Id']; ?>" class="internship-post border border-secondary rounded-right d-block">
						<div class="post-header d-flex">
							<div class="mr-auto p-2">
								<h4 class="internship-title"><?php echo $internshipDetails['Title']; ?></h4>
							</div>

							<div class="apply-by p-2">
								<p>Posted on <span class="i-postDate"><?php echo $internshipDetails['Date_Posted']; ?></span></p>
							</div>

						</div>

						<div class="internship-info">
							<ul>
								<li><strong>Stipend: </strong><?php echo $internshipDetails['Stipend']; ?></li>
								<li><strong>Number of Applications: </strong><?php echo getNoOfApplicantsByInternshipId($internshipDetails['Internship_Id']); ?></li>
								<li><strong>Type: </strong><?php echo getInternshipTypeByInternshipTypeId($internshipDetails['Type_Id']); ?></li>
								<li><strong>Location: </strong><?php echo getInternshipLocationByInternshipLocationId($internshipDetails['Location_Id']); ?></li>
								<li><strong>Duration: </strong><?php echo getInternshipDurationByInternshipDurationId($internshipDetails['Duration_Id']); ?></li>
							</ul>
						</div>
					</a>
					<?php
				}
			 ?>

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
