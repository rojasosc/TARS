$(document).ready(function() {
	alert("the js file is being read");
	//Get the positionID of the position student is applying ot
	$('.applyButton').click(function() {
		var positionID = $(this).closest('tr').find('.positionID').val();
		alert(positionID);
	});
	//Post information to search_process.php
	$('#appSubmit').click(function() {
		var studentID = '<?php echo $student['studentID']?>';
		var compensation = $('#compensation').val();
		var qualifications = $('#qualifications').val();
		var url = $('#application').attr('action');
								  
		$.post(url, {'studentID' : studentID, 'compensation' : compensation, 'qualifications' : qualifications, 'status' : '0', 'positionID' : positionID});
	});
	
	//Prevent redirection to the php page
	$('#appSubmit').submit(function(event) {
		return false;
	});
});