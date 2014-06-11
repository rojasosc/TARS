$(document).ready(function() {
    var positionID;
    var studentID;
    var compensation;
    var qualifications;
    var url;
	var appModalBody;
	var appModalFooter;
	var appFormHTML;
	var appFormButtons;
	var currPos;

    //Get the positionID of the position student is applying ot
    $('.applyButton').on('click', function() {
        currPos = $(this).closest('tr');
		positionID = currPos.find(".positionID").text();
	});

    //Submit a post request to search_process.php
    $('#application').submit(function(event) {
        url = $('#application').attr('action');
		studentID = $('#studentID').val();
        compensation = $('#compensation').val();
        qualifications = $('#qualifications').val();
		appModalBody = $(this).closest(".modal-body");
		appModalFooter = appModalBody.next('.modal-footer');
		appFormHTML = appModalBody.html();
		appFormButtons = appModalFooter.html();	
        $.post(url, {
            positionID: positionID,
			studentID: studentID,
            compensation: compensation,
            qualifications: qualifications
        }, function(data) {
            appModalBody.html('<p>Thank you for applying for this position!<br/>We hope to be able to get back to you soon with our decision.</p>');
            appModalFooter.html('<button type="button" class="btn btn-success" data-dismiss="modal" id="appOK">OK</button>');
			alert(data);
		});
        event.preventDefault();
    });
	
	$('#applymodal').on('hidden.bs.modal', function(event) {
		appModalBody.html(appFormHTML);
		appModalFooter.html(appFormButtons);
		currPos.hide(800);
	});
});