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
?>

<div class="content-wrapper">
	<div class="container-fluid">

		<!-- Breadcrumbs-->
		<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="#">Company Profile</a>
		</li>
		</ol>

		<!-- COMPANY PROFILE BEGINS -->
		<div id="myProfile-form">
		    <form>
						<div class="form-row">
	            <div class="form-group col-md-6">
	                <label for="companyName">Company Name</label>
	                <input type="text" name="companyName" class="form-control" id="companyName" placeholder="Company Name" value="<?php echo $companyData['Company_Name']; ?>" readonly>
	            </div>

		        	<div class="form-group col-md-6">
		        	    <label for="Email">Company Email</label>
		        	    <input type="email" class="form-control" id="email" placeholder="abc@yourcompany.com" value="<?php echo $companyData['Email']; ?>" readonly>
		        	</div>
						</div>
		        <div class="form-row">
			        <div class="form-group col-md-12">
			            <label for="description">Company Description</label>
			            <textarea name="description" class="form-control" id="description" placeholder="Company description" readonly><?php echo $companyData['Company_Details']; ?></textarea>
			        </div>
		        </div>
		    </form>
		</div>
		<!--Form for company profile ends-->

		<!-- Profile Update Modal -->
		<div class="modal fade" id="updateConfirmation" tabindex="-1" role="dialog" aria-labelledby="updateConfirmationTitle" aria-hidden="true">
		    <div class="modal-dialog modal-dialog-centered" role="document">
		        <div class="modal-content">
		            <div class="modal-header">
		                <h5 class="modal-title" id="exampleModalLongTitle">Are You Sure?</h5>
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		    				<span aria-hidden="true">&times;</span>
						</button>
		            </div>
		            <div class="modal-body">
		                <p>Are you sure with your changes, because these will be reflected to your employer.</p>
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		                <button type="button" class="btn btn-primary">Save changes</button>
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
