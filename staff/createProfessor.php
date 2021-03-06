<?php

require_once '../db.php';

$error = null;
$staff = null;
try {
    $staff = LoginSession::sessionContinue(STAFF);
} catch (TarsException $ex) {
    $error = $ex;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>

        <title>Create Account</title>

        <link href="../css/bootstrap.min.css" rel="stylesheet"/>
        <link href="staff.css" rel="stylesheet"/>
        <link href="../css/bootstrap-validator.min.css" rel="stylesheet"/>
        <link href="../favicon.ico" rel="shortcut icon"/>
        <script src="../js/jquery.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/bootstrap-validator.min.js"></script>
        <script src="../js/tars_utilities.js"></script>
        <script src="createProfessor.js"></script>

    </head>
    <body>
        <!-- BEGIN page-wrapper -->

        <div id="page-wrapper">
<?php
// Display header for Manage
$header_active = 'manage';
require 'header.php';
?>

            <!-- BEGIN Page Content -->
            <div id="content">
                <div id="alertHolder">
<?php
if ($error != null) {
    echo $error->toHTML();
}
?>
                </div>
<?php
if ($error == null || $error->getAction() != Event::SESSION_CONTINUE) {
?>
                <div class="container" id="formBox">
                        <div class="panel panel-success" id="createAccountPanel">
                            <div class="panel-heading">
                                <p class="panelHeader">New Professor Account</p>
                            </div> <!-- End panel-heading -->
                            <div class="collapse panel-collapse" id="createAccountBody">
                                <div class="panel-body">
                                    <form class="form-horizontal" id="professorForm" method="post" action="staffCommands.php">
                                        <fieldset>
                                            <legend>New Account</legend>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label" for="firstName">First Name</label>
                                                        <input type="text" class="form-control" name="firstName" placeholder="First Name"/>
                                                    </div> <!-- End form-group -->
                                                </div> <!-- End column -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label" for="firstName">Last Name</label>
                                                        <input type="text" class="form-control" name="lastName" placeholder="Last Name"/>
                                                    </div> <!-- End form-group -->
                                                </div> <!-- End column -->
                                            </div> <!-- End row -->
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label" for="email">Email</label>
                                                        <input type="email" class="form-control" name="email" placeholder="Email"/>
                                                    </div> <!-- End form-group -->
                                                </div> <!-- End column -->
                                            </div> <!-- End row -->
                                            <legend>Office</legend>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label" for="building">Building</label>
                                                        <input type="text" list="buildings-list" id="building" name="building" class="form-control">
                                                        <datalist id="buildings-list" class="buildings"></datalist>
                                                    </div> <!-- End form-group -->
                                                </div> <!-- End column -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label" for="room">Room</label>
                                                        <input type="text" list="rooms-list" id="room" name="room" class="form-control">
                                                        <datalist id="rooms-list" class="rooms"></datalist>
                                                    </div> <!-- End form-group -->
                                                </div> <!-- End column -->
                                            </div> <!-- End row -->
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label" for="homePhone">Office Phone</label>
                                                        <input type="tel" class="form-control" name="officePhone" placeholder="Office Phone"/>
                                                    </div> <!-- End form-group -->
                                                </div> <!-- End column -->
                                            </div> <!-- End row -->
                                        </fieldset> <!-- End fieldset -->
                                    </form> <!-- End form-horizontal -->
                                </div> <!-- End panel-body -->
                            </div> <!-- End panel panel-collapse -->
                            <div class="panel-footer" id="professorPanelFooter">
                                <div class="row">
                                    <div class="col-md-3">
                                        <button type="submit" name="submitButton" form="professorForm" class="btn btn-success btn-block"><span class="glyphicon glyphicon-thumbs-up"></span> Create Account</button>
                                    </div> <!-- End column -->
                                </div> <!-- End row -->

                            </div> <!-- End panel-footer -->
                        </div> <!-- End panel panel-success -->
                </div> <!-- End container -->
<?php
}
?>
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
