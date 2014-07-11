			<!-- BEGIN Page Header -->
			<div id="header">
<?php
if (!isset($error) || $error == null || $error->getAction() != Event::SESSION_CONTINUE) {
	$header_items = array(
		'home' => array('icon' => 'home', 'link' => 'home', 'text' => 'Home'),
		'asst' => array('icon' => 'th-list', 'link' => 'assistants', 'text' => 'Assistants'),
		'appl' => array('icon' => 'inbox', 'link' => 'applicants', 'text' => 'Applicants'));
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
								<a class="navbar-brand" href="../profile/profile.php"><span class="glyphicon glyphicon-user"></span> <?= $professor->getFILName() ?></a>
							</div> <!-- End navbar-header -->					
	    
							<div class="collapse navbar-collapse" id="navigationbar">
								<ul class="nav navbar-nav">
<?php
	foreach ($header_items as $key => $item) {
		$atext = '';
		if (isset($header_active) && $header_active == $key) {
			$atext = ' class="active"';
		}
		echo "<li$atext><a href=\"../$item[link]/$item[link].php\"><span class=\"glyphicon glyphicon-$item[icon]\"></span> $item[text]</a></li>";
	}
?>
								</ul> <!-- End navbar unordered list -->								
								<ul class="nav navbar-nav navbar-right">
									<li><a href="/TARS/logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
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
									<li><a href="/TARS/logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
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
