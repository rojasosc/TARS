$(document).ready(function () {	
	$('.profile').click(displayProfile);
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
	var courseID = "#" + $(this).data('courseid');
	$courseTable = $(courseID);
	$applications = $('.btn-group',$courseTable);
	$applications.each(function () { submitDecisions($(this)); });
	var panelID = "#coursePanel" + $(this).data('courseid');
	$(panelID).collapse('hide');
}

function submitDecisions($application){
	var universityID = $application.data('universityid');
	var positionID = $application.data('appid');
	var decision = $('input:checked',$application).val();
	var url = "selections.php";
	var data = {
		universityID: universityID,
		positionID: positionID,
		decision: decision
	}
	//alert(data['universityID']+ " " +data['positionID']+ " " +data['decision'])
	$.post(url,data,function () {});
}