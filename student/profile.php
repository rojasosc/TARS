<?php  
    include('studentSession.php');
	if(!empty($_POST)) {
		updateProfile($email, $_POST['fn'], $_POST['ln'], $_POST['pn'], $_POST['mjr'], $_POST['year'], $_POST['gpa'], $_POST['qual-hist']);
		$student = getStudent($email);
		$fn = $student->getFirstName();
		$ln = $student->getLastName();
		$brand = $fn[0].". ".$ln;
	}
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>TARS</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="student.css" rel="stylesheet">
		<link href="profile.css" rel="stylesheet">
		
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
							<form role="form" action="profile.php" method="post" id="profile">
								<div class="row">
									<div class="col-xs-6">
										<label>First Name:
											<input class="form-control" type="text" name="fn" size="32" value="<?=$fn?>" />
										</label>
									</div>
									<div class="col-xs-6">
										<label>Last Name:
											<input class="form-control" type="text" name="ln" size="32" value="<?=$ln?>" />	
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<label>E-mail:
											<input class="form-control" readonly="readonly" type="email" name="email" size="64" value="<?=$email?>" />
										</label>
									</div>
									<div class="col-xs-6">
										<label>Phone Number:
											<input class="form-control" type="text" name="pn" size="11" value="<?=$student->getMobilePhone()?>" />
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-4">
										<label>Major:
											<input class="form-control" type="text" name="mjr" size="32" value="<?=$student->getMajor()?>" />
										</label>		
									</div>
									<div class="col-xs-4">
										<label>Class Year:
											<input class="form-control" type="text" name="year" size="32" value="<?=$student->getClassYear()?>" />
										</label>
									</div>
									<div class="col-xs-4">
										<label>Cumulative GPA:
											<input class="form-control" type="text" name="gpa" size="32" value="<?=$student->getGPA()?>" />
										</label>
									</div>
								</div>
								<div class="row col-xs-12">
									<label>Qualifications and TA-ing history: <br />
										<textarea class="form-control" rows="10" cols="100" name="qual-hist" form="profile"><?=$student->getAboutMe()?></textarea>
									</label>
								</div>
								<div class="row">
									<input class="btn btn-primary btn-lg submitbutton" type="submit" value="Save" />
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- END Page Content --> 
	    
			<!--BEGIN Page Footer -->
			<div id="footer">
				<div class="container">
					<div class="row">
						<div class="col-xs-4">						
							<ul id="contact-us">
								<lh>Contact Us</lh>
								<li> <br />
									Oscar Rojas <br />
									Email: orojas@u.rochester.edu <br />
									Phone Number: 404-996-7988<br />
								</li>
								<li> <br />
									Jinze An <br />
									Email: jan2@u.rochester.edu <br />
									Phone Number: 585-749-5590 <br />
								</li>
							</ul>
						</div>
					</div>
				</div> <!-- End row -->
			</div>
			<!--END Page Footer -->
	
		</div> 
		<!-- End page-wrapper -->
		
		<!-- BEGIN Scripts -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<!-- END Scripts -->
	</body>
</html>
