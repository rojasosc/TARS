<?php  
require_once '../session.php';
require_once '../formInput.php';
require_once '../error.php';

$error = null;
$student = null;
try {
	$student = Session::start(STUDENT);
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
		
		<title>TARS</title>
		<!-- BEGIN CSS -->
		<link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet">
		<link type="text/css" href="../css/bootstrap-validator.min.css" rel="stylesheet">
		<link type="text/css" href="../css/bootstrap-select.min.css" rel="stylesheet">
		<link type="text/css" href="student.css" rel="stylesheet">
		<link type="text/css" href="profile.css" rel="stylesheet">
		<!-- END CSS -->
		<!-- BEGIN Scripts -->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap-validator.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap-select.min.js"></script>
		<script type="text/javascript" src="../js/tars_utilities.js"></script>
		<script type="text/javascript" src="profile.js"></script>
		<!-- END Scripts -->
		
	</head>
  
	<body>

		<!-- BEGIN page-wrapper -->
            
		<div id="page-wrapper">
<?php
// Display header for Home
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
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h1 class="panel-title">Edit Profile</h1>
					</div>
					<div class="panel-body">
						<div class="container-fluid display-area">
							<form role="form" action="profileProcess.php" method="post" id="profile">
								<div class="row" id="alertHolder"></div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="firstName">First Name</label>
											<input class="form-control" type="text" id="firstName" name="firstName" size="30" value="<?=$student->getFirstName()?>" />
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="lastName">Last Name</label>
											<input class="form-control" type="text" id="lastName" name="lastName" size="30" value="<?=$student->getLastName()?>" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="email">Email</label>
											<input class="form-control" readonly="readonly" type="email" id="email" name="email" size="30" value="<?=$student->getEmail()?>" />
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="mobilePhone">Mobile Phone</label>
											<div class="input-group">
												<span class="input-group-addon">+1</span>
												<input class="form-control" type="tel" id="mobilePhone" name="mobilePhone" size="30" value="<?=$student->getMobilePhone()?>" maxlength="14" />
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="classYear">Class Year</label>
											<input class="form-control" type="text" id="classYear" name="classYear" size="30" value="<?=$student->getClassYear()?>" />
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="major">Major</label>
											<input class="form-control" type="text" id="major" name="major" size="30" value="<?=$student->getMajor()?>" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="gpa">Cumulative GPA</label>
											<input class="form-control" type="text" id="gpa" name="gpa" size="30" value="<?=$student->getGPA()?>" maxlength="5" />
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="universityID">University Student ID</label>
											<input class="form-control" type="text" id="universityID" name="universityID" size="30" value="<?=$student->getUniversityID()?>" maxlength="8" />
										</div>
									</div>
								</div>
								<div class="row col-sm-12">
									<div class="form-group">
										<label for="aboutMe">Qualifications and TA-ing History</label>
										<textarea class="form-control" rows="10" cols="100" id="aboutMe" name="aboutMe" form="profile"><?=$student->getAboutMe()?></textarea>
									</div>
								</div>
								<div class="row">
									<button class="btn btn-primary btn-lg" id="submitButton" name="submitButton" type="submit">Save</button>
								</div>
							</form>
						</div>
					</div>
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
