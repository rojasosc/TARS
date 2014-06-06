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
		
		$.post(url, {positionID : positionID, studentID : studentID, compensation : compensation, qualifications : qualifications, status : '0'}, function(data){
			$('#applymodal').attr('aria-hidden', 'true');
		});
		event.preventDefault();
	});	
});