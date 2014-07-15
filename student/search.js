$(document).ready(function() {
	//Hehlper Variables
    var positionID;
    var studentID;
    var compensation;
    var qualifications;
    var url;
	var currPos;
	//Form HTML to be saved
	var appModalBody = $('#applyModal .modal-body');
	var appModalFooter = $('#applyModal .modal-footer');
	var	appFormHTML = appModalBody.html();
	var	appFormButtons = appModalFooter.html();	

    //Get the positionID of the position student is applying ot
    $('.applyButton').on('click', function() {
        currPos = $(this).closest('tr');
		positionID = currPos.find('.positionID').text();
		var posType = currPos.find('.posType').text();
		var course = currPos.find('.courseNum').text() + ': ' + currPos.find('.courseTitle').text();
		var prof = currPos.find('.instructor').text();
		var place = currPos.find('.place').text();
		var time = currPos.find('.days').text() + " " + currPos.find('.time').text();

		$('#jobDetails').html('<div class="row"><div class="col-xs-10 col-xs-offset-1"><h2>Position Details</h2></div></div><div class="row"><div class="col-xs-10 col-xs-offset-1"><p>Type: ' + posType + '</p></div></div><div class="row"><div class="col-xs-10 col-xs-offset-1"><p>Course: ' + course + '</p></div></div><div class="row"><div class="col-xs-10 col-xs-offset-1"><p>Instructor: ' + prof + '</p></div></div><div class="row"><div class="col-xs-10 col-xs-offset-1"><p>Place: ' + place + '</p></div></div><div class="row"><div class="col-xs-10 col-xs-offset-1"><p>Time: ' + time + '</p></div></div>');
	});

    //Submit a post request to search_process.php
    $('#applyModal').on('submit', '#application', function(event) {
		event.preventDefault();
        url = $('#application').attr('action');
        compensation = $('#compensation').val();
        qualifications = $('#qualifications').val();
		$.ajax({
			type: 'POST',
			url: url,
			data: {
				positionID: positionID,
				compensation: compensation,
				qualifications: qualifications
			}, 
			dataType: 'json',
			success: function(data) {
				if (data.success) {
					appModalBody.html('<p>Thank you for applying for this position!<br/>We hope to be able to get back to you soon with our decision.</p>');
					appModalFooter.html('<button type="button" class="btn btn-success" data-dismiss="modal" id="appOK">OK</button>');
					appBtn = currPos.find('.applyButton');
					appBtn.attr('disabled', 'disabled');
					appBtn.text('Applied');
				} else {
					$('#appAlertHolder').html(
						'<div class="alert alert-danger">' +
						'<strong>' + data.error.title + '!</strong> '+
						data.error.message+'</div>');
				}
			},
			error: function(jsXHR, textStatus, errorThrown) {
				$('#appAlertHolder').html(
					'<div class="alert alert-danger">' +
					'<strong>Error applying to position!</strong> '+
					'An AJAX error occured (' + errorThrown + ')</div>');
			}
		});
    });
	
	//Restore form HTML when modal is closed
	$('#applyModal').on('hidden.bs.modal', function(event) {
		appModalBody.html(appFormHTML);
		appModalFooter.html(appFormButtons);
	});
});
