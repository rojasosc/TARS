$(document).ready(function () {	
	$('.profile').click(displayProfile);
	$staffComments = $('.staffComments');
	$professorComments = $('.professorComments');
});

function displayProfile(){
	var userID = $(this).data('id');
	var action = "fetchStudent";
	var url = "professorCommands.php";
	var data = {
		userID: userID,
		action: action
	}
	/* Submit a POST request */
	$.post(url,data,function (user){ fillProfile(user); });
	
}

function fillProfile(user){
	$staffComments.html('');
	$professorComments.html('');
	/* Associative array of a user */
	var student = eval('(' + user + ')');
	$('#studentModalTitle').html(student['firstName']+" "+student['lastName']);
	$('#studentMajor').html("Major: " + student['major']);
	$('#studentGPA').html("GPA: " + student['gpa']);
	$('#studentEmail').html("Email: " + student['email']);
	$('#studentMobilePhone').html("Mobile Phone: " + student['mobilePhone']);
	$('#studentAboutMe').html(student['aboutMe']);
	$('#studentClassYear').html("Class Year: " + student['classYear']);
	for (var i = student['staffComments'].length - 1; i >= 0; i--) {
		var comment = student['staffComments'][i];
		$staffComments.append('<div class="row"><div class="col-xs-4"><p>Date: ' + comment['dateTime'] + '</p></div><!-- End column --></div><!-- End row --><div class="row"><div class="col-xs-4"><p>Written By: ' + comment['firstName']+ ' ' + comment['lastName'] + '</p></div> <!-- End column --></div> <!-- End row --><div class="row"><div class="col-xs-12"><p>Message: ' + comment['comment'] + '</p></div><!-- End column --></div> <!-- End row --><br>');
		
	};
	for (var i = student['professorComments'].length - 1; i >= 0; i--) {
		var comment = student['professorComments'][i];
		$professorComments.append('<div class="row"><div class="col-xs-4"><p>Date: ' + comment['dateTime'] + '</p></div><!-- End column --></div><!-- End row --><div class="row"><div class="col-xs-4"><p>Written By: ' + comment['firstName']+ ' ' + comment['lastName'] + '</p></div> <!-- End column --></div> <!-- End row --><div class="row"><div class="col-xs-12"><p>Message: ' + comment['comment'] + '</p></div><!-- End column --></div> <!-- End row --><br>');
	};
}