<?php  
require_once('studentSession.php');
require_once('../formInput.php');
require_once('../error.php');

?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>TARS</title>
		<!-- BEGIN CSS -->
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../bootstrapValidator.min.css" rel="stylesheet">
		<link href="student.css" rel="stylesheet">
		<link href="profile.css" rel="stylesheet">
		<!-- END CSS -->
		<!-- BEGIN Scripts -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../bootstrapValidator.min.js"></script>
		<script type="text/javascript" src="profile.js"></script>
		<!-- END Scripts -->
		
	</head>
  
	<body>

		<!-- BEGIN page-wrapper -->
            
		<div id="page-wrapper">
			
			<!-- BEGIN Page Header -->
			<div id="header">
				<div class="row" id="navbar-theme">
					<nav class="navbar navbar-default navbar-static-top" role="navigation">
						<div class="container-fluid">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
									<span class="sr-only">Toggle Navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<a class="navbar-brand" href="profile.php"><span class="glyphicon glyphicon-user"></span> <?= $brand ?></a>
							</div> <!-- End navbar-header -->					
	    
							<div class="collapse navbar-collapse" id="navigationbar">
								<ul class="nav navbar-nav">
									<li><a href="student.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
									<li><a href="cur_pos.php"><span class="glyphicon glyphicon-th-list"></span> Current Positions</a></li>
									<li><a href="search.php"><span class="glyphicon glyphicon-inbox"></span> Position Search</a></li>
								</ul> <!-- End navbar unordered list -->

								<ul class="nav navbar-nav navbar-right">
									<li><a href="../logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
								</ul> <!-- End navbar unordered list -->
							</div> <!-- End navbar-collapse collapse -->        
						</div> <!-- End container-fluid -->
					</nav>
				</div> <!-- End navbar-theme -->
			</div>		
			<!--END Page Header -->	  
	  
			<!-- BEGIN Page Content -->
			<div id="content">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h1 class="panel-title">Edit Profile</h1>
					</div>
					<div class="panel-body">
						<div class="container-fluid display-area">
							<form role="form" action="profileProcess.php" method="post" id="profile">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="firstName">First Name</label>
											<input class="form-control" type="text" id="firstName" name="firstName" size="30" value="<?=$fn?>" />
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="lastName">Last Name</label>
											<input class="form-control" type="text" id="lastName" name="lastName" size="30" value="<?=$ln?>" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="email">Email</label>
											<input class="form-control" readonly="readonly" type="email" id="email" name="email" size="30" value="<?=$email?>" />
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="mobilePhone">Mobile Phone</label>
											<input class="form-control" type="text" id="mobilePhone" name="mobilePhone" size="30" value="<?=$student->getMobilePhone()?>" />
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
											<input class="form-control" type="text" id="gpa" name="gpa" size="30" value="<?=$student->getGPA()?>" />
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="universityID">University Student ID</label>
											<input class="form-control" type="text" id="universityID" name="universityID" size="30" value="<?=$student->getUniversityID()?>" />
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
