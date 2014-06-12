$(document).ready(function() {
	var curPos;
	var studentID;
	var positionID;
    var emailModalBody = $('#emailModal').find('.modal-body');
    var emailModalFooter = $('#emailModal').find('.modal-footer')
    var emailModalHTML = emailModalBody.html();
    var emailModalButtons = emailModalFooter.html();
    var withdrawModalBody = $('#withdrawModal').find('.modal-body');
    var withdrawModalFooter = $('#withdrawModal').find('.modal-footer');
    var withdrawModalHTML = withdrawModalBody.html();
    var withdrawModalButtons = withdrawModalFooter.html();
	
	$('.withdrawButton').on('click', function(){
		curPos = $(this).closest('tr');
		positionID = curPos.find('.positionID').text();
	});
    $('#emailForm').submit(function(event) {
        alert('Email should be sent to professor');
		emailModalBody.html('<p>Your Email has been sent successfully!</p>');
		emailModalFooter.html('<button type="button" class="btn btn-success" data-dismiss="modal" id="emailOK">OK</button>');
		event.preventDefault();
    });

    $('#withdrawForm').submit(function(event) {
        alert('Email notification should be sent to the staff and professor');
		var url = $('#withdrawForm').attr('action');
		studentID = $('#studentID').val();
		$.post(url, {
			positionID: positionID,
			studentID: studentID
		}, function(data) {
			withdrawModalBody.html('<p>You have been withdrawn from this position.</p>');
			withdrawModalFooter.html('<button type="button" class="btn btn-success" data-dismiss="modal" id="withdrawOK">OK</button>');
			curPos.hide(800);
		});
		event.preventDefault();
    });

    $('#emailModal').on('hidden.bs.modal', function(event) {
        emailModalBody.html(emailModalHTML);
		emailModalFooter.html(emailModalButtons);
    });
	$('#withdrawModal').on('hidden.bs.modal', function(event){
		withdrawModalBody.html(withdrawModalHTML);
		withdrawModalFooter.html(withdrawModalButtons);
	});
});