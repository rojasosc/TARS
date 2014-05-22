<?php  
    include('../dbinterface.php');
  
    session_start();

    if (!isset($_SESSION['auth'])) {
    // if not redirect to login screen. 
		header('Location: ../index.php');
    } else {
		$firstName = $_SESSION['firstName'];
		$lastName = $_SESSION['lastName'];
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
		<link href="../index.css" rel="stylesheet">
		<link href="student.css" rel="stylesheet">

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
								<a class="navbar-brand" href="profile.php"><span class="glyphicon glyphicon-user"></span> <?= $firstName[0].". ".$lastName ?></a>
							</div> <!-- End navbar-header -->					
	    
							<div class="collapse navbar-collapse" id="navigationbar">
								<ul class="nav navbar-nav">
									<li class="active"><a href="student.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
									<li><a href="cur_pos.php"><span class="glyphicon glyphicon-th-list"></span> Current Positions</a></li>
									<li><a href="search.php"><span class="glyphicon glyphicon-inbox"></span> Position Search</a></li>
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
						<h1 class="panel-title">Student Home Page</h1>
					</div>
					<div class="panel-body">
						<div class="jumbotron">
							<h2>Welcome to TARS!</h2>
							<p>
								This is your home page, click on the tabs in the navigation bar above to go to more useful pages.
							</p>
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
