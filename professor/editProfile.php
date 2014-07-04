<?php
require_once 'professorSession.php';
if ($professor) {
	$office = $professor->getOffice();
}
?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>My Profile</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="professor.css" rel="stylesheet">
		<link rel="stylesheet" href="../bootstrapValidator.min.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/tars_utilities.js"></script>
	</head>
  
	<body>
		<!-- BEGIN Edit Profile Modal-->
		<div class="modal fade" id="profile-form-modal" tabindex="-1" role="dialog" aria-labelledby="editProfileModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title" id="modalHeader"></h1>
					</div> <!-- End modal-header -->
					<div class="modal-body">
						<form action="staffCommands.php" class="form-horizontal" id="professor-profile-form" method="post">
							<div class="row">
								<div class="col-md-4">				
									<div class="form-group"> 				
										<label class="control-label" for="firstName">First Name</label>
											<input id="firstName" type="text" class="form-control" name="firstName">																					
									</div> <!-- End form-group -->											
								</div> <!-- End column -->
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label" for="firstName">Last Name</label>
												<input id="lastName" type="text" class="form-control" name="lastName">													
										</div> <!-- End form-group -->							
									</div>	<!-- End column -->						
							</div> <!-- End row -->
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="email">Email</label>
										<input id="email" type="email" class="form-control" disabled="disabled" name="email">					
									</div> <!-- End form-group -->							
								</div>	<!-- End column -->						
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="mobilePhone">Mobile Phone</label>
										<input id="mobilePhone" type="tel" class="form-control" name="mobilePhone" placeholder="Mobile Phone">
									</div> <!-- End form-group -->
								</div> <!-- End column -->								
							</div> <!-- End row -->
							<legend>Office</legend>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="building">Building</label>
										<select name="building" class="form-control buildings" id="buildings">
										</select> <!-- End select -->										
									</div> <!-- End form-group -->
								</div> <!-- End column -->
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="room">Room</label>
										<select name="room" class="form-control rooms" id="rooms">
										</select> <!-- End select -->										
									</div> <!-- End form-group -->
								</div> <!-- End column -->							
							</div> <!-- End Row -->	
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="homePhone">Office Phone</label>
										<input type="tel" class="form-control" name="officePhone" placeholder="Office Phone"/>
									</div> <!-- End form-group -->
								</div> <!-- End column -->						
							</div> <!-- End row -->							
						</form> <!-- End form -->					
					</div> <!-- End modal-body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						<button id="update-professor-button" type="submit"  name="updateButton" class="btn btn-primary"><span class="glyphicon glyphicon-thumbs-up"></span> Update</button>
					</div> <!-- End modal-footer -->			
				</div> <!-- End modal-content -->
			</div> <!-- End modal dialog -->
		</div> <!-- End modal fade -->
		<!-- END Edit Profile Modal-->     
		
		<!-- BEGIN page-wrapper -->
            
		<div id="page-wrapper">
			
<?php
// Display header for Profile
$header_active = 'profile';
require 'header.php';
?>

			<!-- BEGIN Page Content -->
			<div id="content">
<?php
// only possible error is SESSION_CONTINUE
if ($error != null) {
	echo $error->toHTML();
} else {
?>
				<div class="container">				
					<div class="row">
						<h1 class="panelHeader">My Profile</h1>
					</div> <!-- End row -->				
				</div> <!-- End container -->
				<div class="container">
					<div class="panel panel-primary">
						<div class="panel-heading">
						</div> <!-- End panel-body -->
						<div class="panel-body">
							<div class="row">
								<div class="col-xs-4">
									<legend> Courses </legend>
									<?php 
										$sections = $professor->getSections();
										foreach($sections as $section){
									?>
										<div class="row">
											<div class="col-xs-12">
												<?= $section->getCourseTitle() ?>
											</div> <!-- End column -->
										</div> <!-- End row -->
									<?php
										}
									?>
								</div> <!-- End column -->
								<div class="col-xs-8">
									<legend>Personal Details</legend>
									<div class="row">
										<div class="col-xs-4">
										First Name: <?= $professor->getFirstName() ?>
										</div> <!-- End column -->
										<div class="col-xs-4">
										Last Name: <?= $professor->getLastName() ?>
										</div> <!-- End column -->						
									</div> <!-- End row -->
									<br>
									<div class="row">
										<div class="col-xs-4">
										Email: 	<?= $professor->getEmail() ?>
										</div> <!-- End column -->
										<div class="col-xs-4">
										Mobile Phone: 	<?= $professor->getMobilePhone() ?>
										</div> <!-- End column -->									
									</div> <!-- End row -->
									<br>
									<legend>Office</legend>
									<div class="row">
										<div class="col-xs-4">
										Building: <?= $office->getBuilding() ?>	
										</div> <!-- End column -->
										<div class="col-xs-4">
										Room: <?= $office->getRoom() ?>	
										</div> <!-- End column -->	
									</div> <!-- End row -->
									<div class="row">
										<div class="col-xs-4">
										Office Phone: <?= $professor->getOfficePhone() ?>
										</div> 
									</div>
									<br>
									<div class="row">
										<div class="col-xs-3">
											<button data-target="#profile-form-modal" data-toggle="modal" data-usertype="<?= PROFESSOR ?>" data-userid="<?= $professor->getID() ?>" name="editProfileButton" class="btn btn-success edit-profile"><span class="glyphicon glyphicon-wrench"></span> Edit Profile</button>
										</div> <!-- End column -->										
										<div class="col-xs-3">
											<button data-userid="<?= $professor->getID() ?>" id="changePasswordButton" name="changePasswordButton" class="btn btn-danger"><span class="glyphicon glyphicon-wrench"></span> Change Password</button>
										</div> <!-- End column -->
									</div> <!-- End row --> 
								</div> <!-- End column -->
							</div>	<!-- End Row -->
						</div> <!-- End panel-body -->
						<div class="panel-footer">
						</div> <!-- End panel-footer -->
					</div> <!-- End panel panel-primary -->
				</div> <!-- End container -->	
<?php
}
?>
			</div>
			<!-- END Page Content --> 
	    
			<!--BEGIN Page Footer -->
			<div id="footer">
			</div>
			<!--END Page Footer -->
	
		</div> 
		<!-- End page-wrapper -->
	</body>
</html>
