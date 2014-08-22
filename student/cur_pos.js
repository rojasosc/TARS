$(document).ready(function() {
    //Helper variables
    var curPos;
    var studentID;
    var positionID;
    //Target specific parts of the modal
    var releaseModalBody = $('#releaseModal').find('.modal-body');
    var releaseModalFooter = $('#releaseModal').find('.modal-footer');
    var withdrawModalBody = $('#withdrawModal').find('.modal-body');
    var withdrawModalFooter = $('#withdrawModal').find('.modal-footer');
    //Saving some form HTML
    var releaseModalHTML = releaseModalBody.html();
    var releaseModalButtons = releaseModalFooter.html();
    var withdrawModalHTML = withdrawModalBody.html();
    var withdrawModalButtons = withdrawModalFooter.html();
    /*
     * Grabs the positionID of the position that the user may potentially
     * be releasing themselves from.
     */
    $('.releaseButton').on('click', function(){
        curPos = $(this).closest('tr');
        positionID = curPos.find('.positionID').text();
    });
    /*
     * Grabs the positionID of the application that the user may potentially
     * withdraw.
     */
    $('.withdrawButton').on('click', function(){
        curPos = $(this).closest('tr');
        positionID = curPos.find('.positionID').text();
    });

    /*
     * Processes a release request on a position already held by the student without page redirect
     * TODO: SEND AN EMAIL NOTIFCATION TO STAFF AND PROFESSOR
     * TODO: ^ this is done server-side, :)
     * Mechanism:
     * Fetches URL of the page that is going to process the request
     * Fetches the student's reasons for releasing themselves from the position
     * Fetches the student's ID
     * Sends AJAX request to the previously specified URL
     * Returns either affirmative message on success, or error message on error
     */
    $('#releaseModal').on('submit', '#releaseForm', function(event) {
        event.preventDefault();
        doAction('withdraw', {positionID: positionID
        }).done(function(data) {
            if (data.success) {
                releaseModalBody.html('<p>You have been withdrawn from this position.</p>');
                releaseModalFooter.html('<button type="button" class="btn btn-success" data-dismiss="modal" id="releaseOK">OK</button>');
                curPos.hide(800);
                curPos = null;
                positionID = null;
                studentID = null;
            } else {
                showError(data.error, $('#relAlertHolder'));
            }
        }).fail(function(jqXHR, textStatus, errorMessage) {
            showError({message: errorMessage}, $('#relAlertHolder'));
        });
    });
    /*
     * Handles an application withdraw using the same mechanism as above
     */
    $('#withdrawModal').on('submit', '#withdrawForm', function(event) {
        event.preventDefault();
        doAction('withdraw', {positionID: positionID
        }).done(function(data) {
            if (data.success) {
                withdrawModalBody.html('<p>Your application has been cancelled.</p>');
                withdrawModalFooter.html('<button type="button" class="btn btn-success" data-dismiss="modal" id="withdrawOK">OK</button>');
                curPos.hide(800);
                curPos = null;
                positionID = null;
                studentID = null;
            } else {
                showError(data.error, $('#wAlertHolder'));
            }
        }).fail(function(jqXHR, textStatus, errorMessage) {
            showError({message: errorMessage}, $('#wAlertHolder'));
        });
    });
    /*
     * Once the modals are closed, these restore the form HTML
     */
    $('#releaseModal').on('hidden.bs.modal', function(event){
        releaseModalBody.html(releaseModalHTML);
        releaseModalFooter.html(releaseModalButtons);
    });

    $('#withdrawModal').on('hidden.bs.modal', function(event){
        withdrawModalBody.html(withdrawModalHTML);
        withdrawModalFooter.html(withdrawModalButtons);
    });
});
