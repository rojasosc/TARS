<?php

require_once 'actions.php';

function strip_excess_git_tags($tags) {
	$tags = explode(',', trim($tags, ' ()'));
	$stripped = array();
	foreach ($tags as $tag) {
		if (trim($tag) != 'HEAD' && strpos($tag, '/') === false) {
			$stripped[] = trim($tag);
		}
	}
	return implode(', ', $stripped);
}

function show_version() {
	$version_code = trim(@file_get_contents('version.txt'));
	//$git_revision = array(getcwd());
	@exec('git log -1 --format="%h"', $git_h);
	@exec('git log -1 --format="%d"', $git_d);
	@exec('git log -1 --format="%ci"', $git_ci);
	if (count($git_h) && count($git_d) && count($git_ci)) {
		$git_h = $git_h[0];
		$git_d = strip_excess_git_tags($git_d[0]);
		$git_ci = $git_ci[0];
		echo "$version_code: $git_h ($git_d) $git_ci";
	} else {
		echo $version_code;
	}
}

function server_domain() {
	return isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'example.com';
}

function server_linkbase() {
	$is_https = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] === 443 : false;
	$s = $is_https ? 's' : '';
	$domain = server_domain();
	$path = dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '/index.php');
	if (substr($path, -1) !== '/') {
		$path .= '/';
	}
	return "http$s://$domain$path";
}

$error = null;
// creates and destroys a session
$output = LoginSession::retrieveSavedData();

$email = isset($_POST['email']) ? $_POST['email'] : '';
if ($output == null && $error == null && !empty($email)) {
	// creates a session
	$output = Action::callAction('login', $_POST);

	if (isset($output['object'])) {
		$user_obj = $output['object'];
		/* User type */
		$type = $user_obj['type'];
		
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
				Event::SESSION_LOGIN, 'Unknown User type value.');
		}
	}
}

$adminCreated = intval(Configuration::get(Configuration::ADMIN_CREATED));

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
									<div class="col-xs-12">
										<label for="passrecovemail">Email</label>
										<input type="email" id="passrecovemail" name="email" class="form-control" size="64"/>
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
					<div id="alertHolder">
<?php
$formMode = 'index';
$token = '';
if ($output !== null) {
	if ($output['success']) {
		if (isset($output['object'])) {
			if (isset($output['object']['alert'])) {
				$alert = $output['object']['alert'];
				echo TarsException::makeAlert($alert['title'],
					$alert['message'].'.', $alert['class']);
			}
			if (isset($output['object']['resetCallback'])) {
				$formMode = 'setpass';
				$token = $output['object']['resetCallback'];
				$userName = $output['object']['userName'];
			}
		}
	} elseif (isset($output['token'])) {
		$tokenLink = Email::getLink(ResetToken::decodeToken($output['token']), false);
		$tokenLinkText = ' <a class="alert-link" href="'.$tokenLink.'">Click here</a> to '.$output['tokenAction'].'.';
		echo TarsException::makeAlert($output['error']['title'], $output['error']['message'].$tokenLinkText, 'warning');
	} else {
		echo TarsException::makeAlert($output['error']['title'], $output['error']['message'], 'danger');
	}
} elseif ($adminCreated === 0) {
	$formMode = 'setup';
}
?>
					</div>
<?php
if ($formMode === 'index') {
?>
					<form action="./" method="post">
						<fieldset>
							<h2 class="center colorWhite">TARS Sign In</h2>
							<div class="row">
								<div class="col-md-12">
									<label for="email" class="colorWhite">Email</label>
									<input type="email" id="email" name="email" class="form-control" place-holder="" value="<?=$email?>"/>
								</div> <!-- End column -->
							</div> <!-- End row -->
							<div class="row">
								<div class="col-md-12">
									<label for="password" class="colorWhite">Password</label>
									<input type="password" id="password" name="password" class="form-control" place-holder=""/>
								</div> <!-- End column -->
							</div> <!-- End row -->
							<br>
							<div class="row">
								<div class="col-md-4">
									<button name="submit" class="btn btn-success btn-block"><span class="glyphicon glyphicon-hand-right"></span> Login</button>
								</div> <!-- End column -->
							</div> <!-- End row -->
						</fieldset> <!-- End fieldset -->
					</form> <!-- End form -->
					<br />
					<div class="row">
						<div class="col-xs-6">
							<a href="#passmodal" data-toggle="modal">Forgot Password?</a>
						</div> <!-- End column -->
						<div class="col-xs-6">
							<a href="signup.php">Sign Up</a>
						</div> <!-- End column -->
					</div> <!-- End row -->
<?php
} elseif ($formMode === 'setpass') {
?>
					<form action="token.php?token=<?=$token?>" method="post">
						<fieldset>
							<h2 class="center colorWhite"><?=$userName?> Password Reset</h2>
							<div class="row">
								<div class="col-md-12">
									<label for="password" class="colorWhite">Password</label>
									<input type="password" id="password" name="password" class="form-control" place-holder=""/>
								</div> <!-- End column -->
							</div> <!-- End row -->
							<div class="row">
								<div class="col-md-12">
									<label for="password" class="colorWhite">Confirm Password</label>
									<input type="password" id="passwordConfirm" name="passwordConfirm" class="form-control" place-holder=""/>
								</div> <!-- End column -->
							</div> <!-- End row -->
							<br>
							<div class="row">
								<div class="col-md-6">
									<button name="submit" class="btn btn-success btn-block"><span class="glyphicon glyphicon-hand-right"></span> Set Password</button>
								</div> <!-- End column -->
							</div> <!-- End row -->
						</fieldset> <!-- End fieldset -->
					</form> <!-- End form -->
					<br />
					<div class="row">
						<div class="col-xs-6">
							<a href="./">Cancel Login</a>
						</div> <!-- End column -->
					</div> <!-- End row -->
<?php
} elseif ($formMode === 'setup') {
?>
					<form action="./" method="post">
						<input type="hidden" id="password" name="password" value=""/>
						<input type="hidden" id="cfg" name="cfg" value="1"/>
						<fieldset>
							<h2 class="center colorWhite">TARS Setup</h2>
							<p>TARS has not been setup. These settings may be changed later.</p>
							<br />
							<div class="row">
								<div class="col-md-12">
									<label for="email" class="colorWhite">Initial Account Email</label>
									<p>Enter the root Admin account name. You will be prompted to provide a password.</p>
									<input type="email" id="email" name="email" class="form-control" place-holder="" value="<?=htmlentities($email)?>" />
								</div> <!-- End column -->
							</div> <!-- End row -->
							<div class="row">
								<div class="col-md-12">
									<label for="cfg-bug-user" class="colorWhite">Bug Reporting Email</label>
									<p>Enter the bug reporting target user. A disabled account is created as the target of "USER_REPORT_BUG" notifications.</p>
									<input type="email" id="cfg-buf-user" name="cfg-bug-user" class="form-control" place-holder="" value="tarsbug@<?=htmlentities(server_domain())?>" />
								</div> <!-- End column -->
							</div> <!-- End row -->
							<br />
							<div class="row">
								<div class="col-md-12">
									<label for="cfg-email-name" class="colorWhite">Send Email: User Name</label>
									<p>Enter the name-part of the outgoing email address, i.e. "cs.rochester.edu". Used to generate a "-f" CLI argument to the sendmail program.</p>
									<input type="text" id="cfg-email-name" name="cfg-email-name" class="form-control" place-holder="" value="no-reply" />
								</div> <!-- End column -->
							</div> <!-- End row -->
							<div class="row">
								<div class="col-md-12">
									<label for="cfg-email-domain" class="colorWhite">Send Email: Current Domain</label>
									<p>Enter the domain-part of the outgoing email address, i.e. "cs.rochester.edu". Used to generate a "-f" CLI argument to the sendmail program.</p>
									<input type="text" id="cfg-email-domain" name="cfg-email-domain" class="form-control" place-holder="" value="<?=htmlentities(server_domain())?>" />
								</div> <!-- End column -->
							</div> <!-- End row -->
							<div class="row">
								<div class="col-md-12">
									<label for="cfg-email-linkbase" class="colorWhite">Send Email: Current Link-back URL</label>
									<p>Enter the link base for outgoing email, i.e. "http://www.cs.rochester.edu/tars/". Used to generate a valid link back to this application to put in outgoing emails.</p>
									<input type="text" id="cfg-email-linkbase" name="cfg-email-linkbase" class="form-control" place-holder="" value="<?=htmlentities(server_linkbase())?>" />
								</div> <!-- End column -->
							</div> <!-- End row -->
							<br />
							<div class="row">
								<div class="col-md-4">
									<button name="submit" class="btn btn-success btn-block"><span class="glyphicon glyphicon-hand-right"></span> Continue</button>
								</div> <!-- End column -->
							</div> <!-- End row -->
						</fieldset> <!-- End fieldset -->
					</form> <!-- End form -->
<?php
}
?>
					<br />
					<div class="row">
						<div class="col-xs-6">
							<a href="#bugmodal" data-toggle="modal">Report a Bug</a>
						</div> <!-- End column -->
						<div class="col-xs-6">
							<a href="#contactModal" data-toggle="modal">Contact Us</a>
						</div>
					</div> <!-- End row -->
					<br />
					<div class="row">
						<div class="col-xs-12"><p><?php show_version(); ?></p></div>
					</div> <!-- End row -->
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
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/tars_utilities.js"></script>
		<script src="index.js"></script>
		<!-- END Scripts -->
	</body>
</html>
