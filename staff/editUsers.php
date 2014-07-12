<?php  
require_once '../session.php';

$error = null;
$staff = null;
try {
	$staff = Session::start(STAFF);
} catch (TarsException $ex) {
	$error = $ex;
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Edit Users</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="staff.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>	
		<script src="../js/tars_utilities.js"></script>
	</head>
	<body>
		<!-- BEGIN page-wrapper -->
            
		<div id="page-wrapper">
<?php
// Display header for Manage
$header_active = 'profile';
require 'header.php';
?>
			<!-- BEGIN Page Content -->
			<div id="content">
<?php
if ($error != null) {
	echo $error->toHTML();
}
if ($error == null || $error->getAction() != Event::SESSION_CONTINUE) {
?>
		<!-- BEGIN Edit Profile Modal-->
		<div class="modal fade profile-modal" id="profile-modal" tabindex="-1" role="dialog" aria-labelledby="editProfileModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title" id="modalHeader"></h1>
					</div> 
					<div class="modal-body">
						<form class="edit-profile-form" data-usertype="<?= STUDENT ?>">
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
						<legend>Academic Information</legend>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="classYear">Class Year</label>
										<select id="classYear" name="classYear" class="form-control">
											<option>2014</option>
											<option>2015</option>
											<option>2016</option>
											<option>2017</option>
											<option>2018</option>
										</select> <!-- End select -->										
									</div> <!-- End form-group -->
								</div> <!-- End column -->
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="major">Major</label>
										<select id="major" name="major" class="form-control">
											<option>Accounting</option>
											<option>Computer Science</option>
											<option>Physics</option>
											<option>Mathematics</option>
											<option>Economics</option>
										</select> <!-- End select -->										
									</div> <!-- End form-group -->
								</div> <!-- End column -->							
							</div> <!-- End Row -->	
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="GPA">GPA</label>
										<input id="gpa" type="text" class="form-control" name="gpa" placeholder="GPA">
									</div> <!-- End form-group -->
								</div> <!-- End column -->
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="GPA">University Student ID</label>
										<input id="universityID" type="text" class="form-control" name="universityID" placeholder="University Student ID">
									</div> <!-- End form-group -->
								</div> <!-- End column -->																	
							</div> <!-- End row -->	
							<div class="row">
								<div class="col-md-8">
									<label class="control-label" for="aboutMe">About Me</label>
									<textarea id="aboutMe" class="form-control" name="aboutMe" placeholder="Fill this area with previous experience and relevant qualifications."></textarea>
							</div> <!-- End row -->					
						</form> <!-- End form -->					
					</div> <!-- End modal-body -->
				</div> <!-- End modal-content -->
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						<button type="submit"  name="updateButton" class="btn btn-primary"><span class="glyphicon glyphicon-thumbs-up"></span> Update</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Edit Profile Modal-->   

						<div class="panel panel-primary">
							<div class="panel-heading">
								<h1 class="panel-title">Edit Users</h1>
							</div> <!-- End panel-heading -->

								<div class="panel-body">
										<form class="form-horizontal search-users-form"  role="form" data-usertype="<?= STUDENT ?>">
											<div class="row">
												<div class="col-xs-3">
													<label class="control-label" for="emailSearch">Email</label>
													<input id="emailSearch" type="email" class="form-control" name="emailSearch" placeholder="Email">			
												</div> <!-- End column -->
												<div class="col-xs-3">
														<label class="control-label" for="firstName">First Name</label>
															<input id="firstName" type="text" class="form-control" name="firstName" placeholder="First Name">				
												</div> <!-- End column -->
												<div class="col-xs-3">
													<label class="control-label" for="lastName">Last Name</label>
													<input id="lastName" type="text" class="form-control" name="lastName" placeholder="Last Name">
												</div> <!-- End column -->												
											</div> <!-- End row -->
											<br>
											<div class="row">
												<div class="col-xs-3">
													<div class="btn-group" data-toggle="buttons">
														  <label class="btn btn-primary active">
														    	<input type="checkbox" checked> Students
														  </label>
														  <label class="btn btn-primary">
														   		<input type="checkbox"> Professors
														  </label>
													</div>																
												</div> <!-- End column -->
												<div class="col-xs-3">
													<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>
												</div> <!-- End column -->																					
											</div> <!-- End row -->
										</form> <!-- End form -->
										<br>
										<div class="row">
											<div class="col-md-12">
												<table class="table table-striped table-hover user-search-table">
													<thead>
														<tr>
														<th>First Name</th><th>Last Name</th><th>email</th><th>Profile</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table> <!-- End table table-striped -->									
											</div> <!-- End column -->
										</div> <!-- End row -->										
								</div> <!-- End panel-body -->									

							<div class="panel-footer">
							</div> <!-- End panel-footer -->
						</div> <!-- End panel panel-primary -->
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
