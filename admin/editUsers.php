<?php

require_once '../db.php';

$error = null;
$staff = null;
try {
    $staff = LoginSession::sessionContinue(ADMIN);
} catch (TarsException $ex) {
    $error = $ex;
}

function generate_year_options($start_offset, $end_offset) {
    $current_year = intval(date('Y'));
    $output = array();
    for ($i = $current_year + $start_offset; $i <= $current_year + $end_offset; $i++) {
        $output[] = "<option>$i</output>";
    }
    return implode('',$output);
}

function generate_major_options() {
    // TODO XXX put in the database?
    $values = array('CSC' => 'Computer Science', 'PHY' => 'Physics',
        'MTH' => 'Mathematics', 'ECO' => 'Economics', 'ECE' => 'Electrical & Computer Engineering');
    $output = array();
    foreach ($values as $short => $name) {
        $name = htmlentities($name);
        $output[] = "<option value=\"$name\">$short</output>";
    }
    return implode('',$output);
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>

        <title>Edit Users</title>

        <link href="../css/bootstrap.min.css" rel="stylesheet"/>
        <link href="staff.css" rel="stylesheet"/>
        <link href="../favicon.ico" rel="shortcut icon"/>
        <script src="../js/jquery.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/tars_utilities.js"></script>
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
        <!-- BEGIN Edit Profile Modal-->
                <div class="modal fade profile-modal" id="profile-modal" tabindex="-1" role="dialog" aria-labelledby="editProfileModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h1 class="modal-title" id="modalHeader"></h1>
                            </div>
                            <div class="modal-body">
                                <div id="editProfileAlertHolder"></div>
                                <form class="edit-profile-form" id="profileForm<?= STUDENT ?>" data-usertype="<?= STUDENT ?>">
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
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label class="control-label" for="mobilePhone">Mobile Phone</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">+1</span>
                                                    <input type="tel" class="form-control" id="mobilePhone" name="mobilePhone" placeholder="Mobile Phone" maxlength="14" />
                                                </div>
                                            </div> <!-- End form-group -->
                                        </div> <!-- End column -->
                                    </div> <!-- End row -->
                                    <legend>Academic Information</legend>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label class="control-label" for="classYear">Class Year</label>
                                                <input type="text" list="years-list" id="classYear" name="classYear" class="form-control">
                                                <datalist id="years-list"><?= generate_year_options(0, 5) ?></datalist>
                                            </div> <!-- End form-group -->
                                        </div> <!-- End column -->
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label class="control-label" for="major">Major</label>
                                                <input type="text" list="majors-list" id="major" name="major" class="form-control">
                                                <datalist id="majors-list"><?= generate_major_options() ?></datalist>
                                            </div> <!-- End form-group -->
                                        </div> <!-- End column -->
                                    </div> <!-- End Row -->
                                    <br />
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label class="control-label" for="gpa">Cumulative GPA</label>
                                                <input type="text" id="gpa" name="gpa" class="form-control" maxlength="5">
                                            </div> <!-- End form-group -->
                                        </div> <!-- End column -->
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label class="control-label" for="universityID">University Student ID</label>
                                                <input type="text" id="universityID" name="universityID" class="form-control" maxlength="8">
                                            </div> <!-- End form-group -->
                                        </div> <!-- End column -->
                                    </div> <!-- End Row -->
                                    <br />
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label" for="aboutMe">Qualifications and TA-ing History</label>
                                                <textarea id="aboutMe" name="aboutMe" class="form-control" rows="5"></textarea>
                                            </div> <!-- End form-group -->
                                        </div> <!-- End column -->
                                    </div> <!-- End Row -->
                                    <legend>Account Management</legend>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <label class="control-label" for="enable-disable-account">Enable/Disable Account</label>
                                            <br>
                                            <div class="btn-group enable-disable-account" data-toggle="buttons" name="enable-disable-account">
                                                <label class="btn btn-primary active">
                                                    <input type="radio" value="enable" name="accStatus<?= STUDENT ?>" checked> Enabled </label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" value="disable" name="accStatus<?= STUDENT ?>"> Disabled
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </form> <!-- End form -->
                                <form class="edit-profile-form" id="profileForm<?= PROFESSOR ?>" data-usertype="<?= PROFESSOR ?>">
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
                                    <legend>Office</legend>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label class="control-label" for="building">Building</label>
                                                <input type="text" list="buildings-list" id="building" name="building" class="form-control">
                                                <datalist id="buildings-list" class="buildings"></datalist>
                                            </div> <!-- End form-group -->
                                        </div> <!-- End column -->
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label class="control-label" for="room">Room</label>
                                                <input type="text" list="rooms-list" id="room" name="room" class="form-control">
                                                <datalist id="rooms-list" class="rooms"></datalist>
                                            </div> <!-- End form-group -->
                                        </div> <!-- End column -->
                                    </div> <!-- End Row -->
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label class="control-label" for="officePhone">Office Phone</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">+1</span>
                                                    <input type="tel" class="form-control"id="officePhone" name="officePhone" placeholder="Office Phone" maxlength="14" />
                                                </div>
                                            </div> <!-- End form-group -->
                                        </div> <!-- End column -->
                                    </div> <!-- End row -->
                                    <legend>Account Management</legend>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <label class="control-label" for="enable-disable-account">Enable/Disable Account</label>
                                            <br>
                                            <div class="btn-group enable-disable-account" data-toggle="buttons" name="enable-disable-account">
                                                <label class="btn btn-primary active">
                                                    <input type="radio" value="enable" name="accStatus<?= PROFESSOR ?>" checked> Enabled </label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" value="disable" name="accStatus<?= PROFESSOR ?>"> Disabled
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </form> <!-- End form -->
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
                                    <legend>Office</legend>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label class="control-label" for="building">Building</label>
                                                <input type="text" list="buildings-list" id="building" name="building" class="form-control">
                                                <datalist id="buildings-list" class="buildings"></datalist>
                                            </div> <!-- End form-group -->
                                        </div> <!-- End column -->
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label class="control-label" for="room">Room</label>
                                                <input type="text" list="rooms-list" id="room" name="room" class="form-control">
                                                <datalist id="rooms-list" class="rooms"></datalist>
                                            </div> <!-- End form-group -->
                                        </div> <!-- End column -->
                                    </div> <!-- End Row -->
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label class="control-label" for="officePhone">Office Phone</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">+1</span>
                                                    <input type="tel" class="form-control"id="officePhone" name="officePhone" placeholder="Office Phone" maxlength="14" />
                                                </div>
                                            </div> <!-- End form-group -->
                                        </div> <!-- End column -->
                                    </div> <!-- End row -->
                                </form> <!-- End form -->
                                <form class="edit-profile-form" id="profileForm<?= ADMIN ?>" data-usertype="<?= ADMIN ?>">
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
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit"  name="updateButton" class="btn btn-primary"><span class="glyphicon glyphicon-thumbs-up"></span> Update</button>
                            </div>
                        </div> <!-- End modal-content -->
                    </div>
                </div>
        <!-- END Edit Profile Modal-->
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h1 class="panel-title">Edit Users</h1>
                    </div> <!-- End panel-heading -->
                    <div class="panel-body">
                        <form class="form-horizontal search-users-form" novalidate="novalidate" role="form" id="userSearchForm">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <label class="control-label" for="emailSearch">Email</label>
                                    <input id="emailSearch" type="email" class="form-control" name="emailSearch" placeholder="Email">
                                </div> <!-- End column -->
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <label class="control-label" for="fN">First Name</label>
                                    <input id="fN" type="text" class="form-control" name="fN" placeholder="First Name">
                                </div> <!-- End column -->
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <label class="control-label" for="lN">Last Name</label>
                                    <input id="lN" type="text" class="form-control" name="lN" placeholder="Last Name">
                                </div> <!-- End column -->
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <label class="control-label" for="classYear">Class Year</label>
                                    <input id="classYear" type="text" class="form-control" name="classYear" placeholder="e.g. 2016">
                                </div>
                            </div> <!-- End row -->
                            <br/>
                            <div class="row">
                                <div class="col-xs-3">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-primary active">
                                            <input type="checkbox" value="<?= STUDENT ?>" name="userType<?= STUDENT ?>" checked> Students
                                        </label>
                                        <label class="btn btn-primary">
                                            <input type="checkbox" value="<?= PROFESSOR ?>" name="userType<?= PROFESSOR ?>"> Professors
                                        </label>
                                        <label class="btn btn-primary">
                                            <input type="checkbox" value="<?= STAFF ?>" name="userType<?= STAFF ?>"> Staff
                                        </label>
                                        <label class="btn btn-primary">
                                            <input type="checkbox" value="<?= ADMIN ?>" name="userType<?= ADMIN ?>"> Admins
                                        </label>
                                    </div>
                                </div> <!-- End column -->
                                <div class="col-xs-3">
                                    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>
                                </div> <!-- End column -->
                            </div> <!-- End row -->
                        </form> <!-- End form -->
                        <br/>
                        <hr />
                        <!-- Begin Pagination -->
                        <ul class="pagination"></ul>
                        <!-- End Pagination -->
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped user-search-table" id="results">
                                    <thead>
                                        <tr>
                                            <th>First Name</th><th>Last Name</th><th>E-mail</th><th id="classYearHeader">Class Year</th><th>Profile</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table> <!-- End table table-striped -->
                            </div> <!-- End column -->
                        </div> <!-- End row -->
                        <!-- Begin Pagination -->
                        <ul class="pagination"></ul>
                        <!-- End Pagination -->
                    </div> <!-- End panel-body -->
                    <div class="panel-footer">
                    </div> <!-- End panel-footer -->
                </div> <!-- End panel panel-primary -->
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
