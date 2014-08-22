<?php

require_once '../db.php';

$error = null;
$staff = null;
try {
    $staff = LoginSession::sessionContinue(ADMIN);
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

        <title>My Profile</title>

        <link href="../css/bootstrap.min.css" rel="stylesheet" />
        <link href="../css/bootstrap-validator.min.css" rel="stylesheet" />
        <link href="staff.css" rel="stylesheet" />
        <link href="../favicon.ico" rel="shortcut icon"/>
        <script src="../js/jquery.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/bootstrap-validator.min.js"></script>
        <script src="../js/tars_utilities.js"></script>
    </head>

    <body>
    <!-- BEGIN Edit Profile Modal-->
    <div class="modal fade profile-modal" id="profile-modal" tabindex="-1" role="dialog" aria-labelledby="editProfileModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h1 class="modal-title" id="modalHeader"></h1>
                </div> <!-- End modal-header -->
                <div class="modal-body">
                    <div id="editProfileAlertHolder"></div>
                    <form class="edit-profile-form" id="profileForm<?= STAFF ?>" data-usertype="<?= STAFF ?>">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="control-label" for="firstName">First Name</label>
                                        <input id="firstName" type="text" class="form-control" name="firstName">
                                </div> <!-- End form-group -->
                            </div> <!-- End column -->
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label" for="lastName">Last Name</label>
                                            <input id="lastName" type="text" class="form-control" name="lastName">
                                    </div> <!-- End form-group -->
                                </div>    <!-- End column -->
                        </div> <!-- End row -->
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="control-label" for="email">Email</label>
                                    <input id="email" type="email" class="form-control" disabled="disabled" name="email">
                                </div> <!-- End form-group -->
                            </div>    <!-- End column -->
                        </div> <!-- End row -->
                    </form> <!-- End form -->
                </div> <!-- End modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-thumbs-up"></span> Update</button>
                </div> <!-- End modal-footer -->
            </div> <!-- End modal-content -->
        </div> <!-- End modal dialog -->
    </div> <!-- End modal fade -->
    <!-- END Edit Profile Modal-->

    <!-- BEGIN Change Password Modal-->
    <div class="modal fade password-modal" id="password-modal" tabindex="-1" role="dialog" aria-labelledby="passwordMdalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h1 class="modal-title">Change password</h1>
                    <small>To change your password, provide the following information, and click Continue.</small>
                </div> <!-- End modal-header -->
                <div class="modal-body">
                    <div id="editPasswordAlertHolder"></div>
                    <form class="change-password-form" id="change-password-form" data-userid="<?= $staff->getID() ?>" data-usertype="<?= STAFF ?>">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="control-label" for="email">Email</label>
                                    <input type="email" class="form-control" disabled="disabled" id="email" name="email">
                                </div> <!-- End form-group -->
                            </div>    <!-- End column -->
                            <div class="col-xs-6">
                                <label class="control-label" for="oldPassword">Old Password</label>
                                <input type="password" class="form-control" id="oldPassword" name="oldPassword">
                            </div> <!-- End column -->
                        </div> <!-- End row -->
                        <div class="row">
                            <div class="col-xs-6">
                                <label class="control-label" for="newPassword">New Password</label>
                                <input type="password" class="form-control" id="newPassword" name="newPassword">
                            </div> <!-- End column -->
                            <div class="col-xs-6">
                                <label class="control-label" for="confirmPassword">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                            </div> <!-- End column -->
                        </div> <!-- End row -->
                    </form> <!-- End form -->
                </div> <!-- End modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="submit" form="change-password-form" class="btn btn-primary"><span class="glyphicon glyphicon-thumbs-up"></span> Continue</button>
                </div> <!-- End modal-footer -->
            </div> <!-- End modal-content -->
        </div> <!-- End modal dialog -->
    </div> <!-- End modal fade -->
    <!-- END Change Password Modal-->


        <!-- BEGIN page-wrapper -->

        <div id="page-wrapper">

<?php
// Display header for Profile
$header_active = 'profile';
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
                <div class="container">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title panelHeader">Profile</h4>
                        </div> <!-- End panel-body -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-8">
                                    <legend>Personal Details</legend>
                                    <div class="row">
                                        <div class="col-xs-6">
                                        First Name: <strong id="cur-firstName"><?= $staff->getFirstName() ?></strong>
                                        </div> <!-- End column -->
                                        <div class="col-xs-6">
                                        Last Name: <strong id="cur-lastName"><?= $staff->getLastName() ?></strong>
                                        </div> <!-- End column -->
                                    </div> <!-- End row -->
                                    <br>
                                    <div class="row">
                                        <div class="col-xs-6">
                                        Email: <strong id="cur-email"><?= $staff->getEmail() ?></strong>
                                        </div> <!-- End column -->
                                    </div> <!-- End row -->
                                </div> <!-- End column -->
                            </div>    <!-- End Row -->
                        </div> <!-- End panel-body -->
                        <div class="panel-footer">
                            <button data-target="#profile-modal" data-toggle="modal" data-usertype="<?= STAFF ?>" data-userid="<?= $staff->getID() ?>" name="editProfileButton" class="btn btn-success edit-profile"><span class="glyphicon glyphicon-wrench"></span> Edit Profile</button>
                            <button data-target="#password-modal" data-toggle="modal" data-userid="<?= $staff->getID() ?>" id="changePasswordButton" name="changePasswordButton" class="btn btn-danger change-password"><span class="glyphicon glyphicon-wrench"></span> Change Password</button>
                        </div> <!-- End panel-footer -->
                    </div> <!-- End panel panel-primary -->
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
