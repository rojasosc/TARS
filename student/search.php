<?php  
 
	include('../dbinterface.php');
  
   session_start();

    if (!isset($_SESSION['auth'])) {
    // if not redirect to login screen. 
		header('Location: ../index2.php');
    } else {
		$firstName = $_SESSION['firstName'];
		$lastName = $_SESSION['lastName'];
	}

	$dbHost = 'localhost'; 
	$dbUser = 'root'; 
	$dbPass = '12345';
	$dbDatabase = 'TAR'; 
	$con = mysql_connect($dbHost, $dbUser, $dbPass) or trigger_error("Failed to connect to MySQL Server. Error: " . mysql_error());
	mysql_select_db($dbDatabase) or trigger_error("Failed to connect to database {$dbDatabase}. Error: " . mysql_error());
	
	if(isset($_POST['submit']))
	{
		$query = $_POST['query'];
		
		if(strlen($query) >= 1)
		{
			$query = htmlspecialchars($query);
			$query = mysql_real_escape_string($query);
			
			if (isset($_POST['days']) && !empty($_POST['days'])) 
			{
				foreach($_POST['days'] as $days)
				{ 
					$criteria = mysql_escape_string($days);  
					$criteria = implode(' OR ', $criteria);
					echo $criteria;
					//echo $days;
				}
			}
				
			$raw_results = mysql_query("SELECT * FROM ta WHERE (`requirement` LIKE '%".$query."%') OR (`startTime` LIKE '%".$query."%') OR (`endTime` LIKE '%".$query."%') OR (`classRoom` LIKE '%".$query."%')"); 
		}
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
		<link href="search.css" rel="stylesheet">
		
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
				<!-- Profile Modal -->
				<div class="modal fade" id="apply" tabindex="-1" role="dialog" aria-labelledby="applylabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h1 class="modal-title" id="applylabel">Name</h1>
							</div>
							<div class="modal-body">
								<div class="container">
									<p>Information </p>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-primary" data-dismiss="modal">Confirm</button>
								<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
				<!-- End Profile Modal -->
				<div class="panel panel-primary">
				<div class = "container">  
					<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
						<form class="navbar-form navbar-right" action="student_search.php" method="post">
							<input type="text" name="query" class="form-control" placeholder="Search..." />
							<div class="control-group">
								<label class="control-label"><br />Term</label>
								<div class="controls row-fluid span9">
									<select class="input-block-level">
										<option value="20142">Fall 2014</option>
										<option value="20141">Spring 2014</option>
										<option value="20132">Fall 2013</option>
										<option value="20131">Spring 2013</option>
									</select>
								</div>
							</div>
							<br />
							<div class="control-group">
								<label class="control-label">Days</label>
								<div class="controls row-fluid span9">
									M <input type="checkbox" name="days[]" value="M" />
									T <input type="checkbox" name="days[]" value="T" />
									W <input type="checkbox" name="days[]" value="W" />
									R <input type="checkbox" name="days[]" value="R" />
									F <input type="checkbox" name="days[]" value="F" />
								</div>
							</div>
							<br />
							<div class="control-group">
								<label class="control-label">Time</label>
								<div class="controls row-fluid span9">
									Between <br />
									<input type="time" /> <br />
									and <br />
									<input type="time" />
								</div>
							</div>
							<br />
							<button type="submit" class="btn btn-primary" name="submit" id="searchbtn">Search</button>
						</form>
					</div>	
					<div class="container">	
						<?php 
							if(!empty($raw_results)&&mysql_num_rows($raw_results) > 0) {
								while($results = mysql_fetch_array($raw_results)) {
						?>
						<div class="panel panel-default" style="height:100px">
							<div class="panel-body">
								<b><?php echo $results['requirement']?></b> <br /> <font size="3"><?php echo "\r\n " . $results['type'] . " " . $results['startTime'] . "-" . $results['endTime']?></font>
					
								<a type="button" type="button" id="tainfo" data-toggle="modal" href="#apply" class="btn btn-default">
									<span class="glyphicon glyphicon-pencil"></span> Apply</a>
							</div>
						</div>
							
						<?php		
								}
							}		
						?>
						<div class="text-center">
							<ul class="pagination">
								<li class="disabled"><a href="#">« Previous</a></li> 
								<li class="active"><a title="Go to page 1 of 12" href="#">1</a></li> 
								<li><a title="Go to page 2 of 12" href="/index.php?page=2&ipp=10">2</a></li> 
								<li><a title="Go to page 3 of 12" href="/index.php?page=3&ipp=10">3</a></li> 
								<li><a href="/index.php?page=2&ipp=10">Next »</a></li>
							</ul>
						</div>
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
