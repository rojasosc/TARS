$(document).ready(function() {
    var positionID;
    var studentID;
    var compensation;
    var qualifications;
    var url;

    //Get the positionID of the position student is applying ot
    $('.applyButton').on('click', function() {
        positionID = $(this).closest('tr').find(".positionID").text();
    });

    //Submit a post request to search_process.php
    $('#application').submit(function(event) {
        url = $('#application').attr('action');
        studentID = $('#studentID').val();
        compensation = $('#compensation').val();
        qualifications = $('#qualifications').val();

        $.post(url, {
            positionID: positionID,
            studentID: studentID,
            compensation: compensation,
            qualifications: qualifications
        }, function(data) {
            $('.modal-body').html('<p>Thank you for applying for this position!<br/>We hope to be able to get back to you soon with our decision.</p>');
            $('.modal-footer').html('<button type="button" class="btn btn-success" data-dismiss="modal" id="appOK">OK</button>');
        });
        event.preventDefault();
    });
});