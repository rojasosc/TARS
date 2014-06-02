<?php  
    include('studentSession.php');
	$positions = search($_POST['search'], $_POST['term'], $_POST['days'], $_POST['startTime'], $_POST['endTime']);
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
								<a class="navbar-brand" href="profile.php"><span class="glyphicon glyphicon-user"></span> <?= $brand ?></a>
							</div> <!-- End navbar-header -->					
	    
							<div class="collapse navbar-collapse" id="navigationbar">
								<ul class="nav navbar-nav">
									<li><a href="student.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
									<li><a href="cur_pos.php"><span class="glyphicon glyphicon-th-list"></span> Current Positions</a></li>
									<li class="active"><a href="search.php"><span class="glyphicon glyphicon-inbox"></span> Position Search</a></li>
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
						<h1 class="panel-title">Position Search</h1>
					</div>
					<div class="panel-body">
						<div class="container-fluid display-area">
							<form role="form" action="search.php" method="post" id="searchForm">
								<div class="row">
									<div class="col-xs-12">
										<input type="text" name="search" class="form-control" placeholder="Search..." />
									</div>
								</div>
								<div class="row" id="filters">
									<div class="col-xs-4">
										<div id="term-dropdown">
											Term: <br/>
											<select name="term">
												<option value="20142">Fall 2014</option>
												<option value="20141">Spring 2014</option>
												<option value="20132">Fall 2013</option>
												<option value="20131">Spring 2013</option>
											</select>
										</div>
									</div>
									<div class="col-xs-4">
										<div id="day-checkboxes">
											Days: <br/>
											M <input type="checkbox" name="days[]" value="M" />
											T <input type="checkbox" name="days[]" value="T" />
											W <input type="checkbox" name="days[]" value="W" />
											R <input type="checkbox" name="days[]" value="R" />
											F <input type="checkbox" name="days[]" value="F" />
										</div>
									</div>
									<div class="col-xs-4">
										<div id="time-constraint">
											Time: <br/>
											Between
											<input type="time" name="startTime"/>
											and
											<input type="time" name="endTime"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-2 col-xs-offset-5">
										<input type="submit" value="Submit" class="btn btn-primary"/>
									</div>
								</div>
							</form>				
							<hr/>
							<div id="search-results">
								<table class="table table-striped">
									<tr>
										<th>Position No.</th>
										<th>Course Number</th>
										<th>Course</th>
										<th>Professor</th>
										<th>Position Type</th>
										<th>Apply</th>
									</tr>
									<?php
										foreach($positions as $rows) {
									?>
										<tr>
											<td><?=$rows['positionID']?></td>
											<td><?=$rows['courseNumber']?></td>
											<td><?=$rows['courseTitle']?></td>
											<td><?=$rows['firstName']." ".$rows['lastName']?></td>
											<td><?=$rows['type']?></td>
											<td><button class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Apply</button></td>
										</tr>
									<?php
										}
									?>
								</table>
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
