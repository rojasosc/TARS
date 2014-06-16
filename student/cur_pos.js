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
	
	$('.releaseButton').on('click', function(){
		curPos = $(this).closest('tr');
		positionID = curPos.find('.positionID').text();
	});
	
	$('.withdrawButton').on('click', function(){
		curPos = $(this).closest('tr');
		positionID = curPos.find('.positionID').text();
		alert(withdrawModalHTML);
		alert(withdrawModalButtons);
	});

    $('#releaseForm').submit(function(event) {
        alert('Email notification should be sent to the staff and professor');
		var url = $('#releaseForm').attr('action');
		studentID = $('#studentID').val();
		$.post(url, {
			positionID: positionID,
			studentID: studentID
		}, function(data) {
			releaseModalBody.html('<p>You have been withdrawn from this position.</p>');
			releaseModalFooter.html('<button type="button" class="btn btn-success" data-dismiss="modal" id="releaseOK">OK</button>');
			curPos.hide(800);
		});
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
	
	$('#releaseModal').on('hidden.bs.modal', function(event){
		releaseModalBody.html(releaseModalHTML);
		releaseModalFooter.html(releaseModalButtons);
	});

	$('#withdrawModal').on('hidden.bs.modal', function(event){
		withdrawModalBody.html(withdrawModalHTML);
		withdrawModalFooter.html(withdrawModalButtons);
	});
});