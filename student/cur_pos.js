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
	});

    $('#releaseModal').on('submit', '#releaseForm', function(event) {
		event.preventDefault();
        alert('Email notification should be sent to the staff and professor');
		var url = $('#releaseForm').attr('action');
		var reasons = $('releaseReasons').val();
		studentID = $('#studentID').val();
		$.ajax({
			type: 'POST',
			url: url,
			data: {
				positionID: positionID,
				studentID: studentID,
				type: 'release',
				reasons: reasons
			},
			dataType: 'json',
			success: function(data) {
				releaseModalBody.html('<p>You have been withdrawn from this position.</p>');
				releaseModalFooter.html('<button type="button" class="btn btn-success" data-dismiss="modal" id="releaseOK">OK</button>');
				curPos.hide(800);
				curPos = null;
				positionID = null;
				studentID = null;
			},
			error: function(jsXHR, textStatus, errorThrown) {
				alert('AJAX ERROR! We need a better error handling system.');
				releaseModalFooter.append('<div class="error"><p><b>' + textStatus + '</b></p><br>' +
									  '<p>' + errorThrown + '</p></div>');
			}
		});
	});
	
	$('#withdrawModal').on('submit', '#withdrawForm', function(event) {
		event.preventDefault();
		alert('Email notification should be sent to the staff and professor');
		var url = $('#withdrawForm').attr('action');
		studentID = $('#studentID').val();
		$.ajax({
			type: 'POST',
			url: url,
			data: {
				positionID: positionID,
				studentID: studentID,
				type: 'withdraw'
			},
			dataType: 'json',
			success: function(data) {
				withdrawModalBody.html('<p>Your application has been withdrawn.</p>');
				withdrawModalFooter.html('<button type="button" class="btn btn-success" data-dismiss="modal" id="withdrawOK">OK</button>');
				curPos.hide(800);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('AJAX ERROR! We need a better error handling system.');
				withdrawModalFooter.append('<div class="error"><p><b>' + textStatus + '</b></p><br>' +
									  '<p>' + errorThrown + '</p></div>');
			}
		});
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