$(document).ready(function() {
	window.STUDENT = 0;
	window.PROFESSOR = 1;
	window.STAFF = 2;
	window.ADMIN = 3;
	window.actions_url = "../actions.php"; 

	$(".profile").click(view_profile);
	if($("#profile-form-modal").length){
		$(".edit-profile").click(view_user_form);
		$profile_form_modal = $("#profile-form-modal");
	}
	/* Form References */
	$student_profile_form = $("#student-profile-form");
	$professor_profile_form = $("#professor-profile-form");
	$staff_profile_form = $("#staff-profile-form");
	$admin_profile_form = $("#admin-profile-form");
	$search_form = $("#search-users-form");
	prevent_redirection();

	if($(".buildings").length){
		$buildings_dropdown = $(".buildings");
		$rooms_dropdown = $(".rooms");
		prepare_buildings_dropdown();
		$buildings_dropdown.change(prepare_rooms_dropdown);
	}

	if($(".courses").length){
		$courses_dropdown = $(".courses");
		$professors_dropdown = $(".professors");
		prepare_courses_dropdown();
		$courses_dropdown.change(prepare_professors_dropdown);
	}

	$student_profile_modal = $("#student_profile_modal");
	$results = $("#results");

	/* Button References */
	$student_search_button = $("#student-search-button");
	$professor_search_button = $("#professor-search-button");
	$new_professor_button = $("#new-professor-button");

	$student_search_button.click(search_students);
	$professor_search_button.click(search_professors);

});

function search_students() {
	var action = "searchForUsers";
	var data = {
		firstName: $("[name='firstName']",$search_form).val(),
		lastName: $("[name='lastName']",$search_form).val(),
		email: $("[name='emailSearch']",$search_form).val(),
		searchType: STUDENT,
		action: action
	}
	
	/* AJAX POST request to obtain results */	
	$.post(actions_url,data,function(users) { view_results(users, STUDENT); });
}

function search_professors() {
	var action = 'searchForUsers';
	var data = {
		firstName: $("[name='firstName']",$search_form).val(),
		lastName: $("[name='lastName']",$search_form).val(),
		email: $("[name='emailSearch']",$search_form).val(),
		searchType: PROFESSOR,
		action: action
	}
	
	/* AJAX POST request to obtain results */	
	$.post(actions_url,data,function(users) { view_results(users, PROFESSOR); });
}

function view_results(users, user_type) {
	/* Clear any existing results */
	$('#resultTable').hide();
	$('#resultTable').find('tbody').remove();
	/* Associative array of a user */
	var users = eval('(' + users + ')');
	/* Render results table */
	var row = new Array(), j = -1;
	var size = users.length; 
	for (var key = 0;key<size; key++){
		row[++j] ='<tr><td>';
		row[++j] = users[key]["firstName"];
		row[++j] = '</td><td>';
		row[++j] = users[key]["lastName"];
		row[++j] = '</td><td>';
		row[++j] = users[key]["email"];
		row[++j] = '</td><td>';
		row[++j] = '<button data-toggle="modal" data-target="#editProfileModal" class="btn btn-default edit-profile circle" data-usertype="'+ user_type +'" data-userid="'+users[key]["userID"]+'"><span class="glyphicon glyphicon-wrench"></span></button>'
		row[++j] = '</td></tr>';
		
	}
	
	/* Render the appropriate user profile update forms */
	$('#resultTable').append(row.join(''));
	$resultTable.show();
	$(".edit-profile").click(view_user_form);

}

function view_profile() {
	var user_id = $(this).data('userid');
	var user_type = $(this).data('usertype');
	var action = "";

	switch(user_type){
		case STUDENT:
			action = "fetchStudent";
			break;
		case PROFESSOR:
			action = "fetchProfessor";
			break;
		case STAFF:
			action = "fetchStaff";
			break;
		case ADMIN:
			action = "fetchAdmin";
			break; 
	}
	var data = {
		userID: user_id,
		action: action
	}

	$.post(actions_url,data,function(user) { prepare_student_modal(user); });
}

function prepare_student_modal(user) {
	var student = $.parseJSON(user);
	var num_comments = student['comments']['size'];
	$comments = student['comments'];
	$commentsBlock = $("#comments");
	
	$('#studentModalTitle').html(student['firstName']+" "+student['lastName']);
	$('#studentMajor').html("Major: " + student['major']);
	$('#studentGPA').html("GPA: " + student['gpa']);
	$('#studentEmail').html("Email: " + student['email']);
	$('#studentMobilePhone').html("Mobile Phone: " + student['mobilePhone']);
	$('#studentAboutMe').html(student['aboutMe']);
	$('#studentClassYear').html("Class Year: " + student['classYear']);

	for (var i = num_comments -1; i >= 0; i--) {
		$comment = $comments[i];
		$commentsBlock.append('<div class="row"><div class="col-xs-4"><p>Date: ' + $comment['createTime'] + '</p></div><!-- End column --></div><!-- End row --><div class="row"><div class="col-xs-4"><p>Written By: ' + $comment['author'] + '</p></div> <!-- End column --></div> <!-- End row --><div class="row"><div class="col-xs-12"><p>Message: ' + $comment['comment'] + '</p></div><!-- End column --></div> <!-- End row --><br>');
		
	};

}

function view_user_form() {

	var userID = $(this).data("userid");
	var user_type = $(this).data("usertype");
	var action = "";
	switch(user_type){
		case STUDENT:
			action = "fetchStudent";
			break;
		case PROFESSOR:
			action = "fetchProfessor";
			break;
		case STAFF:
			action = "fetchStaff";
			break;
		case ADMIN:
			action = "fetchAdmin";
			break; 
	}
	var data = {
		userID: userID,
		action: action
	}
	$.post(actions_url,data,function (user){ prepare_user_form(user); });
}

function prepare_user_form(user) {
	$user = $.parseJSON(user);
	switch($user['type']){
		case STUDENT:
			$('#modalHeader').html($user['firstName']+" "+$user['lastName']);
			$("[name='firstName']",$student_profile_form).val($user['firstName']);
			$("[name='lastName']",$student_profile_form).val($user['lastName']);
			$("[name='email']",$student_profile_form).val($user['email']);
			$("[name='mobilePhone']",$student_profile_form).val($user['mobilePhone']);
			$("[name='classYear']",$student_profile_form).val($user['classYear']);
			$("[name='major']",$student_profile_form).val($user['major']);
			$("[name='gpa']",$student_profile_form).val($user['gpa']);
			$("[name='universityID']",$student_profile_form).val($user['universityID']);
			$("[name='aboutMe']",$student_profile_form).val($user['aboutMe']);
			$("#update-student-button").click(update_user_profile);
			break;
		case PROFESSOR:
			$('#modalHeader').html($user['firstName']+" "+$user['lastName']);	
			$("[name='firstName']",$professor_profile_form).val($user ['firstName']);
			$("[name='lastName']",$professor_profile_form).val($user ['lastName']);
			$("[name='email']",$professor_profile_form).val($user ['email']);
			$("[name='mobilePhone']",$professor_profile_form).val($user ['mobilePhone']);
			$("[name='officePhone']",$professor_profile_form).val($user ['officePhone']);
			$("[name='building']",$professor_profile_form).val($user ['office']['building']);
			$("[name='room']",$professor_profile_form).val($user ['office']['room']);		
			$("#update-professor-button").click(update_user_profile);
			break;
		case STAFF:
			action = "fetchStaff";
			break;
		case ADMIN:
			action = "fetchAdmin";
			break; 
	}

	$profile_form_modal.modal("show");
}

function update_user_profile() {
	var action = "";
	switch($user['type']){
		case STUDENT:
			action = "updateStudentProfile";
			/* Select the input fields in the context of the update form. */
			var data = {
				firstName: $("[name='firstName']",$student_profile_form).val(),
				lastName: $("[name='lastName']",$student_profile_form).val(),
				email: $("[name='email']",$student_profile_form).val(),
				mobilePhone: $("[name='mobilePhone']",$student_profile_form).val(),
				classYear: $("[name='classYear']",$student_profile_form).val(),
				major: $("[name='major']",$student_profile_form).val(),
				gpa: $("[name='gpa']",$student_profile_form).val(),
				universityID: $("[name='universityID']", $student_profile_form).val(),
				aboutMe: $("[name='aboutMe']",$student_profile_form).val(),
				action: action
			}
			break;
		case PROFESSOR:
			/* Select the input fields in the context of the update form. */
			action = "updateProfessorProfile";
			var data = {
				firstName: $("[name='firstName']",$professor_profile_form).val(),
				lastName: $("[name='lastName']",$professor_profile_form).val(),
				email: $("[name='email']",$professor_profile_form).val(),
				mobilePhone: $("[name='mobilePhone']",$professor_profile_form).val(),
				officePhone: $("[name='officePhone']",$professor_profile_form).val(),
				building: $("[name='building']",$professor_profile_form).val(),
				room: $("[name='room']",$professor_profile_form).val(),
				action: action
			}
			break;
		case STAFF:
			action = "fetchStaff";
			break;
		case ADMIN:
			action = "fetchAdmin";
			break; 
	}

	$.post(actions_url,data,function (info){ });

	/*TODO: Obtain a confirmation from the PHP script on success/failure and 
	 * notify the user */
	$profile_form_modal.modal("hide");
}

function prepare_buildings_dropdown() { 
	var action = "fetchBuildings";
	var data = {
		action: action
	}
	$.post(actions_url,data,function (buildings) { 
		var buildings = eval('(' + buildings + ')');
		for(var i = 0; i < buildings.length; i++){
			$buildings_dropdown.append("<option>" + buildings[i]['building'] + "</option>");;
		}
		$buildings_dropdown.trigger('change');
	 });
}

function prepare_rooms_dropdown() {
	var action = "fetchTheRooms";
	var data = {
		building: $(this).val(),
		action: action
	}
	clear_dropdown($rooms_dropdown);
	$.post(actions_url,data,function (rooms) { 
		var rooms = eval('(' + rooms + ')');
		for(var i = 0; i < rooms.length; i++){
			$rooms_dropdown.append("<option>" + rooms[i] + "</option>");
		}
	 });	
}

function prepare_courses_dropdown() {
	var action = "fetchCourses";
	var data = {
		action: action
	}
	$.post(actions_url,data, function (courses) { 
		var courses = eval('(' + courses + ')');
		for (var i = 0; i < courses.length; i++){
			$courses_dropdown.append("<option>" + courses[i]['courseTitle'] + "</option>");
		}
		$courses_dropdown.trigger("change");
	 });	
}

function prepare_professors_dropdown() {
	var action = "fetchTheProfessors";
	var data = {
		courseTitle: $(this).val(),
		action: action
	}	
	$.post(url,data,function (professors) { 
		clear_dropdown($professors_dropdown);
		var professors = eval('(' + professors + ')');
		for(var i = 0; i < professors.length; i++){
			$professors_dropdown.append("<option>" +professors[i]['firstName'] + " " + professors[i]['lastName']+"</option>");
		}
	 });	
}

function clear_dropdown($dropdown){
	$dropdown.find('option').remove();
	
}

function prevent_redirection() {
	$search_form.submit(function() { return false; });	
	$student_profile_form.submit(function() { return false; });
	$professor_profile_form.submit(function() { return false; });
	$staff_profile_form.submit(function() { return false; });
	$admin_profile_form.submit(function() { return false; });

}

