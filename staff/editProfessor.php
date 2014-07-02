<?php  
require_once 'staffSession.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Edit Professor</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet"/>
		<link href="staff.css" rel="stylesheet"/>
		<link rel="stylesheet" href="../bootstrapValidator.min.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script rel="text/javascript" src="../bootstrapValidator.min.js"></script>		
		<script rel="text/javascript" src="editProfessor.js"></script>
	</head>
	<body>
		<!-- BEGIN Edit Profile Modal-->
		<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title" id="modalHeader"></h1>
					</div> <!-- End modal-header -->
					<div class="modal-body">
						<form action="staffCommands.php" class="form-horizontal" id="updateForm" method="post">
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
										<select name="building" class="form-control" id="buildings">
										</select> <!-- End select -->										
									</div> <!-- End form-group -->
								</div> <!-- End column -->
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="room">Room</label>
										<select name="room" class="form-control" id="rooms">
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
						<button id="updateButton" type="submit"  name="updateButton" class="btn btn-primary"><span class="glyphicon glyphicon-thumbs-up"></span> Update</button>
					</div> <!-- End modal-footer -->			
				</div> <!-- End modal-content -->
			</div> <!-- End modal dialog -->
		</div> <!-- End modal fade -->
		<!-- END Edit Profile Modal-->    	
	
		<!-- BEGIN page-wrapper -->
            
		<div id="page-wrapper">
<?php
// Display header for Manage
$header_active = 'manage';
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
				<div class="row">
					<h1>Edit Professor</h1>
				</div> <!-- End row -->		
				<div class="container">
					<div class="container" id="search">
						<div class="row">
							<h3>Filter Constraints</h3>
						</div> <!-- end row -->
						<div class="row">
							<form class="form-horizontal" id="searchUsersForm" method="post" action="staffCommands.php">
								<div class="row">
									<div class="col-md-10">
											<label class="control-label" for="emailSearch">Email</label>
											<input id="emailSearch" type="email" class="form-control" name="emailSearch" placeholder="Email">																				
									</div> <!-- End column -->					
								</div> <!-- End row -->
								<div class="row">
									<div class="col-md-10">
											<label class="control-label" for="firstName">First Name</label>
												<input id="firstName" type="text" class="form-control" name="firstName" placeholder="First Name">																				
									</div> <!-- End column -->					
								</div> <!-- End row -->
								<div class="row">
									<div class="col-md-10">
											<label class="control-label" for="lastName">Last Name</label>
												<input id="lastName" type="text" class="form-control" name="lastName" placeholder="Last Name">																				
									</div> <!-- End column -->
									<div class="col-md-4" id="searchType">
										<div class="form-group">
												<input type="text" class="form-control" name="searchType" value="1">													
										</div> <!-- End form-group -->							
									</div>	<!-- End column -->										
								</div> <!-- End row -->								
								<br>
								<div class="row">
									<div class="col-md-6">
										<button id="searchButton"  type="submit" name="searchButton" class="btn btn-success btn-block"><span class="glyphicon glyphicon-search"></span> Search</button>
									</div> <!-- End column -->
								</div> <!-- End row -->	
							</form> <!-- End form-horizontal -->
						</div> <!-- End row -->
					</div> <!-- End container -->	
					
					<div class="container" id="result">
						<div class="row">
							<div class="col-md-4">
								<h3>Results</h3>
							</div> <!-- End column -->
						</div> <!-- End row -->						
						<div class="row">
							<div class="col-md-12">
								<table class="table table-striped table-hover" id="resultTable">
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
						<!-- TODO: Implement pagination every 10 rows -->				
					</div> <!-- End container -->
					<div class="jumbotron" id="emptyResult">
						<div class="row" id="testRow" data-id="14">
							<div class="col-md-12">
								<p>No Professors Found</p>
							</div> <!-- End column -->
						</div> <!-- End row -->
					</div> <!-- End jumbotron no results -->					
				</div>
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
