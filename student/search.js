$(document).ready(function() {
    var positionID;
    var studentID;
    var compensation;
    var qualifications;
    var url;
	var appModalBody = $('#applyModal .modal-body');
	var appModalFooter = $('#applyModal .modal-footer');
	var appFormHTML;
	var appFormButtons;
	var currPos;

    //Get the positionID of the position student is applying ot
    $('.applyButton').on('click', function() {
		// TODO: explicitly indicate which position this application is for to the user
        currPos = $(this).closest('tr');
		positionID = currPos.find(".positionID").text();
	});

    //Submit a post request to search_process.php
    $('#applyModal').on('submit', '#application', function(event) {
		event.preventDefault();
        url = $('#application').attr('action');
        compensation = $('#compensation').val();
        qualifications = $('#qualifications').val();
		appFormHTML = appModalBody.html();
		appFormButtons = appModalFooter.html();	
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
	
	$('#applyModal').on('hidden.bs.modal', function(event) {
		appModalBody.html(appFormHTML);
		appModalFooter.html(appFormButtons);
	});
});
