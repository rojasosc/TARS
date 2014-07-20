			<!-- BEGIN Page Header -->
			<div id="header">
<?php
if (!isset($error) || $error == null || $error->getAction() != Event::SESSION_CONTINUE) {
?>
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
								<a class="navbar-brand" href="profile.php"><span class="glyphicon glyphicon-user"></span> <?= $staff->getFILName() ?></a>
							</div> <!-- End navbar-header -->					
	    
							<div class="collapse navbar-collapse" id="navigationbar">
								<ul class="nav navbar-nav">
									<li<?php if ($header_active == 'home') { echo ' class="active"'; } ?>><a href="staff.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
									<li class="dropdown <?php if ($header_active == 'manage') { echo ' active'; } ?>" style="cursor:pointer">
										<a class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Manage<b class="caret"></b></a>
										<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu2">
											<li role="presentation" class="dropdown-header">New</li>
												<li><a href="newTerm.php">Term</a></li>
												<li><a href="createProfessor.php">Professor</a></li>
											<li role="presentation" class="divider"></li>
											<li role="presentation" class="dropdown-header">Edit</li>
												<li><a href="editTerm.php">Term</a></li>
												<li><a href="editUsers.php">User</a></li>											
											<li role="presentation" class="divider"></li>
											<li role="presentation" class="dropdown-header">Review</li>
												<li><a href="reviewStudents.php">Applications</a></li>																				  
										</ul>
									</li> <!-- End dropdown list item -->
									<li<?php if ($header_active == 'payroll') { echo ' class="active"'; } ?>><a href="payroll.php"><span class="glyphicon glyphicon-usd"></span> Payroll</a></li>
								</ul> <!-- End navbar unordered list -->
								<ul class="nav navbar-nav navbar-right">
									<li><a href="../logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
								</ul> <!-- End navbar unordered list -->								
							</div> <!-- End navbar-collapse collapse -->	
						</div> <!-- End container-fluid -->
					</nav>
				</div> <!-- End navbar-theme -->
<?php
} else {
?>
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
							</div> <!-- End navbar-header -->

							<div class="collapse navbar-collapse" id="navigationbar">
								<ul class="nav navbar-nav navbar-right">
									<li><a href="../logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
								</ul> <!-- End navbar unordered list -->								
							</div> <!-- End navbar-collapse collapse -->	
						</div> <!-- End container-fluid -->
					</nav>
				</div> <!-- End navbar-theme -->
<?php
}
?>
			</div>		
			<!--END Page Header -->	  
