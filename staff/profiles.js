$(document).ready(function () {	
	$('.profile').click(displayProfile);
});

function displayProfile(){
	var userID = $(this).data('userid');
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