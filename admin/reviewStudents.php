<?php

require_once '../db.php';

$error = null;
$staff = null;
try {
    $staff = LoginSession::sessionContinue(ADMIN);
} catch (TarsException $ex) {
    $error = $ex;
}

$term = null;
if ($error == null) {
    try {
        $currentTermID = Configuration::get(Configuration::CURRENT_TERM);
        if ($currentTermID != null) {
            $term = Term::getTermByID($currentTermID);
        }
    } catch (PDOException $ex) {
        $error = new TarsException(Event::SERVER_DBERROR, Event::USER_GET_SECTIONS, $ex);
    }
}
?>

<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>

        <title>Review Students</title>

        <link href="../css/bootstrap.min.css" rel="stylesheet"/>
        <link href="staff.css" rel="stylesheet"/>
        <link href="../favicon.ico" rel="shortcut icon"/>
        <script src="../js/jquery.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/comments.js"></script>
        <script src="../js/tars_utilities.js"></script>
    </head>
    <body>
        <!-- Profile Modal -->
        <div class="modal fade profile-modal" id="profile-modal" tabindex="-1" role="dialog" aria-labelledby="studentProfileModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h2 class="modal-title" id="studentModalTitle"></h2>
                    </div>
                    <div class="modal-body">
                        <div id="profileAlertHolder"></div>
                        <h3>Qualifications</h3>
                        <div class="container qualifications">
                        </div> <!-- End container -->
                        <h3>Academic Information</h3>
                        <div class="container">
                            <p id="studentMajor"></p>
                            <p id="studentGPA"></p>
                            <p id="studentClassYear"></p>
                        </div> <!-- End container -->
                        <h3>Contact Information</h3>
                        <div class="container">
                            <p id="studentEmail"></p>
                            <p id="studentMobilePhone"></p>
                        </div> <!-- End container -->
                    </div> <!-- End modal body -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div> <!-- End modal-footer -->
                </div> <!-- End modal-content -->
            </div> <!-- End modal-dialog -->
        </div> <!-- End modal fade -->
        <!-- End Profile Modal -->

        <!-- BEGIN Comment Modal-->
        <div class="modal fade comments-modal" id="commentsModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h1 class="modal-title">Reviews</h1>
                    </div> <!-- End modal-header -->
                    <div class="modal-body">
                        <div id="commentsAlertHolder"></div>
                        <div class="comments-block"></div>
                    </div> <!-- End modal-body -->
                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div> <!-- End modal-footer -->
                </div> <!-- End modal-content -->
            </div> <!-- End modal-dialog -->
        </div> <!-- End modal fade -->
        <!-- END Comment Modal-->

        <!-- Begin Email Modal -->
        <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myProfileLabel">TARS</h4>
                    </div> <!-- End modal-header -->
                    <div class="modal-body">
                        <h3>Email Form</h3>
                        <div class="container">
                            <form action="#" method="post">
                                <fieldset>
                                    <div class="row">
                                        <div class="col-sm-8 col-md-6">
                                            <label>Subject</label>
                                            <input type="text" name="subjectLine" class="form-control" placeholder="Enter A Subject Line.">
                                        </div> <!-- End column -->
                                    </div><!-- End row -->
                                    <div class="row">
                                        <div class="col-sm-8 col-md-6">
                                            <label>Message</label>
                                            <textarea class="form-control" rows="3" placeholder="Enter Your Message."></textarea> <!-- End text area -->
                                        </div> <!-- End column -->
                                    </div> <!-- End row -->
                                </fieldset>
                            </form> <!-- End form -->
                        </div> <!-- End container -->
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button> <!-- close modal -->
                        </div>    <!-- End modal footer -->
                    </div> <!-- End modal body -->
                </div> <!-- End modal-content -->
            </div> <!--End modal-dialog-->
        </div> <!-- End modal fade -->
        <!-- End Email Modal -->
        <!-- BEGIN Comment Modal-->
        <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h1 class="modal-title">Student Review</h1>
                        <small>Please, briefly describe this student's performance.</small>
                        <small> For example, describe this assistant's productivity, enthusiasm, punctuality, initiative, or dependability.</small>
                    </div> <!-- End modal-header -->
                    <div class="modal-body">
                        <div id="createCommentAlertHolder"></div>
                        <form action="../actions.php" method="post" id="commentForm" class="form-horizontal">
                            <fieldset>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <textarea name="commentText" class="form-control"></textarea>
                                    </div> <!-- End column -->
                                </div> <!-- End row -->
                            </fieldset> <!-- End comment fieldset -->
                        </form> <!-- End comment form -->
                    </div> <!-- End modal-body -->
                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" name="submitComment" id="submitCommentButton">Add Comment</button>
                    </div> <!-- End modal-footer -->
                </div> <!-- End modal-content -->
            </div> <!-- End modal-dialog -->
        </div> <!-- End modal fade -->
        <!-- END Comment Modal-->

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
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title panelHeader">Applications</h4>
                    </div> <!-- End panel-heading -->
                    <div class="panel-body">
<!--                         <form class="form-horizontal filter-applications-form" role="form" id="filterApplicationsForm">
                            <fieldset>
                                <legend>Filter by:</legend>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-4 col-md-3">
                                        <label class="control-label" for="courseFilter">Course:</label>
                                        <input id="courseFilter" name="courseFilter" type="text" class="form-control" placeholder="e.g. CSC171, CSC172">
                                    </div>
                                    <div class="col-xs-6 col-sm-4 col-md-3">
                                        <label class="control-label" for="professorFilter">Professor:</label>
                                        <input id="professorFilter" name="professorFilter" type="text" class="form-control" placeholder="e.g. Ted Pawlicki, Ted, Pawlicki">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="btn-group" data-toggle="buttons">
                                            <label class="btn btn-primary active">
                                                <input type="radio" value="all" name="all" checked> All
                                            </label>
                                            <label class="btn btn-primary">
                                                <input type="radio" value="reviewed" name="reviewed"> Reviewed
                                            </label>
                                            <label class="btn btn-primary">
                                                <input type="radio" value="not-reviewed" name="notReviewed"> Not Reviewed
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-3"></div>
                                </div>
                            </fieldset>
                        </form> -->
                        <hr />
                        <ul class="pagination"></ul>
                        <table class="table table-striped applications-review-table" id="results">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>University ID</th>
                                    <th>Type</th>
                                    <th>Course</th>
                                    <th>Application</th>
                                    <th>Reviews</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table> <!-- End table -->
                        <ul class="pagination"></ul>
                    </div> <!-- End panel-body -->
                </div> <!-- End panel panel-primary -->
                <?php
}
                ?>
                <!-- END Course Panels -->
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
