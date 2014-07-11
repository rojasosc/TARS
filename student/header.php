			<!-- BEGIN Page Header -->
			<div id="header">
<?php
if (!isset($error) || $error == null || $error->getAction() != Event::SESSION_CONTINUE) {
	$header_items = array(
		'home' => array('icon' => 'home', 'link' => 'student.php', 'text' => 'Home'),
		'curp' => array('icon' => 'th-list', 'link' => 'cur_pos.php', 'text' => 'Current Positions'),
		'search' => array('icon' => 'inbox', 'link' => 'search.php', 'text' => 'Position Search'));
?>
				<div class="row" id="navbar-theme">
					<nav class="navbar navbar-default navbar-static-top" role="navigation">
						<div class="container-fluid">
							<div class="navbar-header active">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
									<span class="sr-only">Toggle Navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<a class="navbar-brand" href="profile.php"><span class="glyphicon glyphicon-user"></span> <?= $student->getFILName() ?></a>
							</div> <!-- End navbar-header -->					
	    
							<div class="collapse navbar-collapse" id="navigationbar">
								<ul class="nav navbar-nav">
<?php
	foreach ($header_items as $key => $item) {
		$atext = '';
		if (isset($header_active) && $header_active == $key) {
			$atext = ' class="active"';
		}
		echo "<li$atext><a href=\"$item[link]\"><span class=\"glyphicon glyphicon-$item[icon]\"></span> $item[text]</a></li>";
	}
?>
								</ul> <!-- End navbar unordered list -->								
								<ul class="nav navbar-nav navbar-right">
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
									<li><a href="../logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
=======
									<li><a href="/TARS/logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
>>>>>>> parent of 39b2bbb... bug fixes to files in directory refactoring
=======
									<li><a href="../../logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
>>>>>>> parent of f6dab39... refactored professor directory, tweaked student directory
=======
									<li><a href="/TARS/logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
>>>>>>> parent of 39b2bbb... bug fixes to files in directory refactoring
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
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
									<li><a href="../logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
=======
									<li><a href="/TARS/logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
>>>>>>> parent of 39b2bbb... bug fixes to files in directory refactoring
=======
									<li><a href="../../logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
>>>>>>> parent of f6dab39... refactored professor directory, tweaked student directory
=======
									<li><a href="/TARS/logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
>>>>>>> parent of 39b2bbb... bug fixes to files in directory refactoring
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
