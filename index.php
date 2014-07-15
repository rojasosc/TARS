<?php
require_once 'db.php';
require_once 'formInput.php';
require_once 'error.php';
require_once 'session.php';

function show_version() {
	$version_code = trim(@file_get_contents('version.txt'));
	//$git_revision = array(getcwd());
	@exec('git log -1 --format="%h%d %ci"', $git_revision);
	if (count($git_revision) >= 1) {
		echo "$version_code: {$git_revision[0]}";
	} else {
		echo $version_code;
	}
}

$error = null;
if (isset($_POST['submit'])) {
	$form_args = get_form_values(array('email','password'));

	if (!$form_args['email']) {
		$error = new TarsException(Event::ERROR_FORM_FIELD,
			Event::SESSION_LOGIN, array('email'));
	} elseif (!$form_args['password']) {
		$error = new TarsException(Event::ERROR_FORM_FIELD,
			Event::SESSION_LOGIN, array('password'));
	} else {
		try {
			$login_user = Session::login($form_args['email'], $form_args['password']);
		} catch (TarsException $ex) {
			$error = $ex;
		} catch (PDOException $ex) {
			$error = new TarsException(Event::SERVER_DBERROR,
				Event::SESSION_LOGIN, $ex);
		}

		if ($login_user) {
			/* User type */
			$type = $login_user->getObjectType();
			
			if ($type == STUDENT) {
				header('Location: student/student.php');
				exit;
			} elseif ($type == PROFESSOR) {
				header('Location: professor/professor.php');
				exit;
			} elseif ($type == STAFF) {
				header('Location: staff/staff.php');
				exit;
			} elseif ($type == ADMIN) {
				header('Location: admin/admin.php');
				exit;
			} else {
				$error = new TarsException(Event::SERVER_EXCEPTION,
					Event::SESSION_LOGIN, new Exception('Unknown User type value.'));
			}
		} else {
			$error = new TarsException(Event::ERROR_LOGIN, Event::SESSION_LOGIN);
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		
		<title>TARS</title>
		
		<link href="css/bootstrap.min.css" rel="stylesheet"/>
		<link href="index.css" rel="stylesheet"/>

	</head>
  
	<body>
		<!-- BEGIN Forgot Password -->
		<div class="modal fade" id="passmodal" tabindex="-1" role="dialog" aria-labelledby="passModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Password Recovery</h1>
					</div>
					<div class="modal-body">
						<p>Enter the email address you used to register for your account and further instructions will be sent to that address.
							This would probably be your school E-mail address.</p>
						<form action="passrecov.php" method="post" id="passrecov">
							<fieldset>
								<div class="row">
									<div class="col-xs-6">
										<label for="passrecovemail">Email</label>
										<input type="email" id="passrecovemail" name="passrecov" class="form-control" size="64"/>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" form="passrecov" value="Submit">Submit</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Forgot Password -->
		<!-- BEGIN Report a Bug -->
		<div class="modal fade" id="bugmodal" tabindex="-1" role="dialog" aria-labelledby="bugModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Bug Report</h1>
					</div>
					<div class="modal-body">
						<p>
							Enter your information so we can verify your identity.<br/>
							In the space provided, detail the bug that you have encountered as clearly and concisely as you can muster.
						</p>
						<form action="bugrep.php" method="post" id="bugrep">
							<fieldset>
								<div class="row">
									<div class="col-xs-5 col-xs-offset-1">
										<label for="bugrepemail">Email</label>
										<input class="form-control" type="email" id="bugrepemail" name="bugrep" size="32"/>
									</div>
									<div class="col-xs-5">
										<label for="bugreppass">Password</label>
										<input class="form-control" type="password" id="bugreppass" name="bugreppass" size="32"/>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-10 col-xs-offset-1">
										<label for="bugrepdata">Comments</label>
										<textarea class="form-control" id="bugrepdata" name="bugrepdata" rows="4" cols="64"></textarea>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" form="bugrep" value="Submit">Submit</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Report a Bug -->
		<!-- BEGIN Contact Us -->
		<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Contact Us</h1>
					</div>
					<div class="modal-body">
						<div class="container">
							<div class="row">
								<div class="col-xs-4">      
									<ul id="contact-us">
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
										<li> <br />
											Nate Book <br />
											Email: me@natembook.com <br />
											Phone Number: 860-324-4055 <br />
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Contact Us -->

		<!-- BEGIN page-wrapper -->
            
		<div id="page-wrapper">
			
			<!-- BEGIN Page Header -->
			<div id="header">

			</div>		
			<!--END Page Header -->	  
	  
			<!-- BEGIN Page Content -->
			<div id="content">
				<div id="login">
					<form action="index.php" method="post">
						<fieldset>
							<?php if ($error != null) { echo $error->toHtml(); } ?>
							<h2 class="center colorWhite">TARS Sign In</h2>
							<div class="row">
								<div class="col-md-10">
									<label for="email" class="colorWhite">Email</label>
									<input type="email" id="email" name="email" class="form-control" place-holder="" value="<?=get_form_value('email')?>">
								</div> <!-- End column -->
							</div> <!-- End row -->
							<div class="row">
								<div class="col-md-10">
									<label for="password" class="colorWhite">Password</label>
									<input type="password" id="password" name="password" class="form-control" place-holder="">
								</div> <!-- End column -->
							</div> <!-- End row -->	
							<br>
							<div class="row">
								<div class="col-md-3">
									<button name="submit" class="btn btn-success btn-block"><span class="glyphicon glyphicon-hand-right"></span> Login</button>
								</div> <!-- End column -->
							</div> <!-- End row -->
							<br>
							<div class="row">
								<div class="col-xs-6">
									<a href="signup.php">Sign Up</a>
								</div> <!-- End column -->
								<div class="col-xs-6">
									<div class="form-group">
										<a href="#passmodal" data-toggle="modal">
											Forgot Password?
										</a>
									</div>
								</div> <!-- End column -->								
							</div> <!-- End row -->
							<div class="row">
								<div class="col-xs-6">
									<a href="#bugmodal" data-toggle="modal">Report A Bug</a>
								</div> <!-- End column -->
								<div class="col-xs-6">
									<a href="#contactModal" data-toggle="modal">Contact Us</a>
								</div>
							</div> <!-- End row -->							
							<br>
							<div class="row">
								<div class="col-xs-12 versionData">
									<?php show_version(); ?>
								</div>
							</div> <!-- End row -->
						</fieldset> <!-- End fieldset -->
					</form> <!-- End form -->
				</div> <!-- End container -->
			</div>
			<!-- END Page Content --> 
	    
			<!--BEGIN Page Footer -->
			<div id="footer">
				
			</div>
			<!--END Page Footer -->
	
		</div> 
		<!-- End page-wrapper -->
		
		<!-- BEGIN Scripts -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<!-- END Scripts -->
	</body>
</html>
