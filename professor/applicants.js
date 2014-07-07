$(document).ready(function () {	
	$('.decisions').click(getDecisions);
	
});

function displayProfile(){
	var userID = $(this).data('id');
	var url = "fetchStudent.php";
	var data = {
		'userID': userID
	}
	/* Submit a POST request */
	$.post(url,data,function (user){ fillProfile(user); });
	
}

function fillProfile(user){
	/* Associative array of a user */
	var student = eval('(' + user + ')');
	$('#studentModalTitle').html(student['firstName']+" "+student['lastName']);
	$('#studentMajor').html("Major: " + student['major']);
	$('#studentGPA').html("GPA: " + student['gpa']);
	$('#studentEmail').html("Email: " + student['email']);
	$('#studentMobilePhone').html("Mobile Phone: " + student['mobilePhone']);
	$('#studentAboutMe').html(student['aboutMe']);
	$('#studentClassYear').html("Class Year: " + student['classYear']);
	
	
}

function getDecisions(){
	var panelID = "#" + $(this).data('panelid');
	$panel = $(panelID);
	$applications = $('.btn-group',$panel);
	$applications.each(function () { submitDecisions($(this)); });
	$panel.collapse('hide');
}

function submitDecisions($application){
	var applicationID = $application.data('applicationid');
	var decision = $('input:checked',$application).val();
	var url = "selections.php";
	var data = {
		applicationID: applicationID,
		decision: decision
	}
	$.post(url,data,function () {});
}