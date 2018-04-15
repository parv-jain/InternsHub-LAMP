<?php
	//head
	require 'components/head.php';
	//Navigation
	require 'components/nav.php';
?>
<?php
	require_once 'server/functions.php';
	authenticateUser();
	if(isset($_POST['title']) && isset($_POST['location']) && isset($_POST['category']) && isset($_POST['type']) && isset($_POST['duration'])
	&& isset($_POST['stipend']) && isset($_POST['question']) && isset($_POST['about']) && isset($_POST['skills'])){
	  if(!empty($_POST['title']) && !empty($_POST['location']) && !empty($_POST['category']) && !empty($_POST['type']) && !empty($_POST['duration'])
		&& !empty($_POST['stipend']) && !empty($_POST['question']) && !empty($_POST['about']) && !empty($_POST['skills']))
	    postNewInternship($_POST);
	  else
	    showMessage("Form Fields can\'t left empty", "register.php");
	}
?>

<div class="content-wrapper">
	<div class="container-fluid">

		<!-- Breadcrumbs-->
		<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="#">Add new internships</a>
		</li>
		</ol>

		<!-- Add Internship form BEGINS -->
		<div id="addNew-form">
			<form action="add-new.php" id="new-internship-form" method="post">
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="title">Title of Internship</label>
						<input type="text" class="form-control" id="title" placeholder="Title of Internship" name="title" required>
					</div>
					<div class="form-group col-md-6">
						<label class="" for="location">Location</label>
						<select class="custom-select" id="location" name="location" required>
							<?php $internshipLocations = getAllDataFromTable("internship_locations"); ?>
							<option selected disabled>Choose...</option>
							<?php
								foreach($internshipLocations as $location){
										$location_name = $location['Location'];
										$location_id = $location['Internship_Location_Id'];
										echo "<option value='$location_id'>$location_name</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group col-md-6">
						<label class="" for="category">Category</label>
						<select class="custom-select my-1 mr-sm-2" id="category" name="category" required>
								<?php $internshipCategories = getAllDataFromTable("internship_categories"); ?>
								<option selected disabled>Choose...</option>
								<?php
									foreach($internshipCategories as $category){
											$category_name = $category['Category'];
											$category_id = $category['Internship_Category_Id'];
											echo "<option value='$category_id'>$category_name</option>";
									}
								?>
						</select>
					</div>
					<div class="form-group col-md-6">
						<label class="" for="type">Type</label>
						<select class="custom-select my-1 mr-sm-2" id="type" name="type" required>
								<?php $internshipType = getAllDataFromTable("internship_type"); ?>
								<option selected disabled>Choose...</option>
								<?php
									foreach($internshipType as $type){
											$type_name = $type['Type'];
											$type_id = $type['Internship_Type_Id'];
											echo "<option value='$type_id'>$type_name</option>";
									}
								?>
						</select>
					</div>
					<div class="form-group col-md-6">
						<label class="" for="duration">Duration</label>
						<select class="custom-select my-1 mr-sm-2" id="duration" name="duration" required>
								<?php $internshipDurations = getAllDataFromTable("internship_durations"); ?>
								<option selected disabled>Choose...</option>
								<?php
									foreach($internshipDurations as $duration){
											$duration_name = $duration['Duration'];
											$duration_id = $duration['Internship_Duration_Id'];
											echo "<option value='$duration_id'>$duration_name</option>";
									}
								?>
						</select>
					</div>
					<div class="form-group col-md-6">
						<label for="stipend">Stipend</label>
						<input type="number" class="form-control" id="stipend" name="stipend" required>
					</div>
				</div>
				<div class="form-row">
				<div class="form-group col-12">
					<label for="question">Question to Solve</label>
					<textarea class="form-control" id="question" placeholder="Question to solve" name="question" required></textarea>
				</div>
				<div class="form-group col-12">
					<label for="about">About the Internship</label>
					<textarea class="form-control" id="about" placeholder="About the internship" name="about" required></textarea>
				</div>
				<div class="form-group col-12">
					<label for="skills">Skills required</label>
					<textarea class="form-control" id="skills" placeholder="Skills required for the internship" name="skills" required></textarea>
				</div>
				</div>
			</form>
			<button class="btn btn-success pull-right" data-toggle="modal" data-target="#addInternshipConfirmation" id="">Post internship</button>
		</div>
		<!--Form for add internship ends-->

		<!-- Add internship confirm Modal -->
		<div class="modal fade" id="addInternshipConfirmation" tabindex="-1" role="dialog" aria-labelledby="updateConfirmationTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">Are You Sure?</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" onClick="document.getElementById('new-internship-form').submit();">Yes, Post now</button>
					</div>
				</div>
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
