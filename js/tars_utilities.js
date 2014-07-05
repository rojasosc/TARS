$(document).ready(function() {
	window.STUDENT = 0;
	window.PROFESSOR = 1;
	window.STAFF = 2;
	window.ADMIN = 3;
	window.actions_url = "../actions.php"; 

	if($(".search-users-form").length){
		$user_search_form = $(".search-users-form");
		$user_search_form.submit(function() { return false; });	
		$user_search_button = $("[type='submit']", $user_search_form);
		$user_search_button.click(function() { 
			search_users($user_search_form.data("usertype"));
		});
	}

	if($(".user-search-table").length){
		$results = $(".user-search-table");

	}

	if($(".profile-modal").length){
		$user_modal = $(".profile-modal");
		if($(".edit-profile-form").length){
			$edit_profile_form = $(".edit-profile-form");
			$edit_profile_form.submit(function() { return false; });
			//In case the button is not in a table
			$(".edit-profile").click(function() { 
				view_user_form($(this).data("userid"),$edit_profile_form.data("usertype"));

			})
		}
		if($(".profile").length){
			$user_modal = $(".profile-modal");
			$(".profile").click(view_profile);

		}
	}

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

});


function search_users(user_type) {
	var action = "searchForUsers";
	var data = {
		firstName: $("[name='firstName']",$user_search_form).val(),
		lastName: $("[name='lastName']",$user_search_form).val(),
		email: $("[name='emailSearch']",$user_search_form).val(),
		searchType: user_type,
		action: action
	}
	$.post(actions_url,data,function(users) { view_results(users); });

}

function view_results(users) {
	/* Clear any existing results */
	$results.hide();
	$results.find('tbody').remove();
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
		row[++j] = '<button data-toggle="modal" data-target="#profile-modal" class="btn btn-default edit-profile circle" data-userid="'+users[key]["userID"]+'"><span class="glyphicon glyphicon-wrench"></span></button>'
		row[++j] = '</td></tr>';
		
	}
	
	/* Render the appropriate user profile update forms */
	$results.append(row.join(''));
	$results.show();
	$(".edit-profile").click(function() {
		view_user_form($(this).data("userid"),$edit_profile_form.data("usertype"));
	});

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
			action = "TODO";
			break;
		case ADMIN:
			action = "TODO";
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

function view_user_form(user_id, user_type) {
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
	$.post(actions_url,data,function (user){ prepare_user_form(user, user_type); });
}

function prepare_user_form(user, user_type) {
	$user = $.parseJSON(user);
	switch($user['type']){
		case STUDENT:
			$('#modalHeader').html($user['firstName']+" "+$user['lastName']);
			$("[name='firstName']",$edit_profile_form).val($user['firstName']);
			$("[name='lastName']",$edit_profile_form).val($user['lastName']);
			$("[name='email']",$edit_profile_form).val($user['email']);
			$("[name='mobilePhone']",$edit_profile_form).val($user['mobilePhone']);
			$("[name='classYear']",$edit_profile_form).val($user['classYear']);
			$("[name='major']",$edit_profile_form).val($user['major']);
			$("[name='gpa']",$edit_profile_form).val($user['gpa']);
			$("[name='universityID']",$edit_profile_form).val($user['universityID']);
			$("[name='aboutMe']",$edit_profile_form).val($user['aboutMe']);
			break;
		case PROFESSOR:
			$('#modalHeader').html($user['firstName']+" "+$user['lastName']);	
			$("[name='firstName']",$edit_profile_form).val($user ['firstName']);
			$("[name='lastName']",$edit_profile_form).val($user ['lastName']);
			$("[name='email']",$edit_profile_form).val($user ['email']);
			$("[name='mobilePhone']",$edit_profile_form).val($user ['mobilePhone']);
			$("[name='officePhone']",$edit_profile_form).val($user ['officePhone']);
			$("[name='building']",$edit_profile_form).val($user ['office']['building']);
			$("[name='room']",$edit_profile_form).val($user ['office']['room']);		
			break;
		case STAFF:
			action = "TODO";
			break;
		case ADMIN:
			action = "TODO";
			break; 
	}
	$("[type='submit']").click(update_user_profile);
	$user_modal.modal("show");
}

function update_user_profile() {
	var action = "";
	switch($user['type']){
		case STUDENT:
			action = "updateStudentProfile";
			/* Select the input fields in the context of the update form. */
			var data = {
				firstName: $("[name='firstName']",$edit_profile_form).val(),
				lastName: $("[name='lastName']",$edit_profile_form).val(),
				email: $("[name='email']",$edit_profile_form).val(),
				mobilePhone: $("[name='mobilePhone']",$edit_profile_form).val(),
				classYear: $("[name='classYear']",$edit_profile_form).val(),
				major: $("[name='major']",$edit_profile_form).val(),
				gpa: $("[name='gpa']",$edit_profile_form).val(),
				universityID: $("[name='universityID']", $edit_profile_form).val(),
				aboutMe: $("[name='aboutMe']",$edit_profile_form).val(),
				action: action
			}
			break;
		case PROFESSOR:
			/* Select the input fields in the context of the update form. */
			action = "updateProfessorProfile";
			var data = {
				firstName: $("[name='firstName']",$edit_profile_form).val(),
				lastName: $("[name='lastName']",$edit_profile_form).val(),
				email: $("[name='email']",$edit_profile_form).val(),
				mobilePhone: $("[name='mobilePhone']",$edit_profile_form).val(),
				officePhone: $("[name='officePhone']",$edit_profile_form).val(),
				building: $("[name='building']",$edit_profile_form).val(),
				room: $("[name='room']",$edit_profile_form).val(),
				action: action
			}
			break;
		case STAFF:
			action = "TODO";
			break;
		case ADMIN:
			action = "TODO";
			break; 
	}

	$.post(actions_url,data,function (info){ });

	/*TODO: Obtain a confirmation from the PHP script on success/failure and 
	 * notify the user */
	$user_modal.modal("hide");
}

function prepare_buildings_dropdown() { 
	var action = "fetchBuildings";
	var data = {
		action: action
	}
	$.post(actions_url,data,function (buildings) { 
		var buildings = eval('(' + buildings + ')');
		for(var i = 0; i < buildings.length; i++){
			$buildings_dropdown.append("<option>" + buildings[i]['building'] + "</option>");
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