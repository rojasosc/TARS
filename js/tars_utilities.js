$(document).ready(function() {
	window.STUDENT = 0;
	window.PROFESSOR = 1;
	window.STAFF = 2;
	window.ADMIN = 3;
	window.actionsUrl = "../actions.php"; 

	if( $( ".search-users-form" ).length ){
		$userSearchForm = $( ".search-users-form" );
		$userSearchForm.submit( function() { return false; });	
		$userSearchButton = $( "[type='submit']", $userSearchForm );
		$userSearchButton.click(function() { 
			searchUsers( $userSearchForm.data( "usertype" ) );
		});
	}

	if( $( ".user-search-table" ).length ){
		$results = $( ".user-search-table" );

	}

	if( $( ".profile-modal" ).length ){
		$userModal = $( ".profile-modal" );
		if( $( ".edit-profile-form" ).length ){
			$editProfileForm = $( ".edit-profile-form" );
			$editProfileForm.submit(function() { return false; });
			//In case the button is not in a table
			$( ".edit-profile" ).click(function() { 
				viewUserForm( $(this).data( "userid" ), $editProfileForm.data( "usertype" ) );

			})
		}

		if( $( ".profile" ).length ){
			$userModal = $( ".profile-modal" );
			$( ".profile" ).click( viewUserProfile );
			if( $( ".qualifications" ).length ){
				$qualifications = $( ".qualifications" );
				$( ".profile" ).click( injectQualifications );
			}
		}
	}

	if( $( ".comments-modal").length ){
		$commentsModal = $( ".comments-modal" );
		$commentsBlock = $( ".comments-block" )
		$( ".comments" ).click( viewUserComments );
	}

	if( $( ".buildings" ).length ){
		$buildingsDropdown = $( ".buildings" );
		$roomsDropdown = $( ".rooms" );
		$buildingsDropdown.change( prepareRoomsDropdown );
		prepareBuildingsDropdown();
		
	}

	if( $( ".courses" ).length ){
		$coursesDropdown = $( ".courses" );
		$professorsDropdown = $( ".professors" );
		prepareCoursesDropdown();
		$coursesDropdown.change( prepareProfessorsDropdown );
	}

});

function injectQualifications() {
	var appID = $(this).data("appid");
	var action = "fetchQualifications";
	var data = {
		appID: appID,
		action: action
	}
	$.post( actionsUrl, data, function(qual) {
		$qual = $.parseJSON(qual);
		$qualifications.html($qual['qualifications']);
	});
}

function viewUserComments() {
	var action = "fetchComments";
	var data = {
		userID: $(this).data("userid"),
		action: action
	}
	$.post( actionsUrl, data, function(comments){
		$comments = $.parseJSON(comments);
		var commentsCnt = $comments['size'];
		for ( var i = commentsCnt -1; i >= 0; i-- ) {
			$comment = $comments[i];
			$commentsBlock.append( "<p class='commentDate'>" + $comment[ "createTime" ] + "</p><blockquote><p class='commentContent'>"+ 
				$comment[ "comment" ] + "</p><footer>" + $comment[ "author" ] + "</footer></blockquote></div><!-- End column --></div> <!-- End row --><br>" );		
		};
		if(!commentsCnt){
			$commentsBlock.html("There are no reviews available for this student.");
		}
	});
	$commentsModal.bind("hidden.bs.modal", function() { $commentsBlock.html(""); });
}
function searchUsers( userType ) {
	var action = "searchForUsers";
	var data = {
		firstName: $( "[name='firstName']", $userSearchForm ).val(),
		lastName: $( "[name='lastName']", $userSearchForm).val(),
		email: $( "[name='emailSearch']", $userSearchForm).val(),
		searchType: userType,
		action: action
	}
	$.post( actionsUrl, data, function( users ) { viewResults( users ); });

}

function viewResults( users ) {
	/* Clear any existing results */
	$results.hide();
	$results.find( "tbody" ).remove();
	/* Associative array of a user */
	var users = eval( "(" + users + ")" );
	/* Render results table */
	var row = new Array(), j = -1;
	var size = users.length; 
	for ( var key = 0; key < size; key++ ){
		row[ ++j ] ="<tr><td>";
		row[ ++j ] = users[ key ][ "firstName" ];
		row[ ++j ] = "</td><td>";
		row[ ++j ] = users[ key ][ "lastName" ];
		row[ ++j ] = "</td><td>";
		row[ ++j ] = users[ key ][ "email" ];
		row[ ++j ] = "</td><td>";
		row[ ++j ] = "<button data-toggle='modal' data-target='#profile-modal' class='btn btn-default edit-profile circle' data-userid='" + 
						users[ key ][ "userID" ] + "'><span class='glyphicon glyphicon-wrench'></span></button>"
		row[ ++j ] = "</td></tr>";
		
	}
	
	/* Render the appropriate user profile update forms */
	$results.append( row.join('') );
	$results.show();
	$( ".edit-profile" ).click(function() {
		viewUserForm( $(this).data( "userid" ),$editProfileForm.data( "usertype" ) );
	});

}

function viewUserProfile() {
	var userID = $(this).data( "userid" );
	var userType = $(this).data( "usertype" );
	var action = "";

	switch( userType ){
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
		userID: userID,
		action: action
	}

	$.post( actionsUrl, data, function( user ) { prepareStudentModal( user ); } );
}

function prepareStudentModal( user ) {
	var student = $.parseJSON( user );
	$( "#studentModalTitle" ).html( student[ "firstName" ] + " " + student[ "lastName" ] );
	$( "#studentMajor" ).html( "Major: " + student[ "major" ] );
	$( "#studentGPA" ).html( "GPA: " + student[ "gpa" ] );
	$( "#studentEmail" ).html( "Email: " + student[ "email" ] );
	$( "#studentMobilePhone" ).html( "Mobile Phone: " + student[ "mobilePhone" ] );
	$( "#studentAboutMe" ).html( student[ "aboutMe" ] );
	$( "#studentClassYear" ).html( "Class Year: " + student[ "classYear" ] );

}

function viewUserForm( userID, userType ) {
	var action = "";
	switch(userType){
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
		userID: userID,
		action: action
	}
	$.post( actionsUrl, data, function ( user ){ prepareUserForm( user, userType ); });
}

function prepareUserForm( user, userType ) {
	$user = $.parseJSON( user );
	switch( $user["type"] ){
	case STUDENT:
		$( "#modalHeader" ).html( $user[ "firstName" ] + " " + $user[ "lastName" ] );
		$( "[name='firstName']", $editProfileForm ).val( $user[ "firstName" ] );
		$( "[name='lastName']", $editProfileForm).val( $user[ "lastName" ] );
		$( "[name='email']", $editProfileForm).val( $user[ "email" ] );
		$( "[name='mobilePhone']", $editProfileForm).val( $user[ "mobilePhone" ] );
		$( "[name='classYear']", $editProfileForm).val( $user[ "classYear" ] );
		$( "[name='major']", $editProfileForm).val( $user[ "major" ] );
		$( "[name='gpa']", $editProfileForm).val( $user[ "gpa" ] );
		$( "[name='universityID']", $editProfileForm).val( $user[ "universityID" ] );
		$( "[name='aboutMe']", $editProfileForm).val( $user[ "aboutMe" ] );
		break;
	case PROFESSOR:
		$( "#modalHeader" ).html( $user[ "firstName" ] + " " + $user[ "lastName" ] );	
		$( "[name='firstName']", $editProfileForm).val( $user[ "firstName" ] );
		$( "[name='lastName']", $editProfileForm).val( $user[ "lastName" ] );
		$( "[name='email']", $editProfileForm).val( $user[ "email" ] );
		$( "[name='mobilePhone']", $editProfileForm).val( $user[ "mobilePhone" ] );
		$( "[name='officePhone']", $editProfileForm).val( $user[ "officePhone" ] );
		$( "[name='building']", $editProfileForm).val( $user[ "office" ][ "building" ] );
		$( "[name='room']", $editProfileForm).val( $user[ "office" ][ "room" ] );		
		break;
	case STAFF:
		action = "TODO";
		break;
	case ADMIN:
		action = "TODO";
		break; 
	}
	$( "[type='submit']" ).click( update_user_profile );
	$userModal.modal( "show" );
}

function update_user_profile() {
	var action = "";
	switch($user[ "type" ]){
	case STUDENT:
		action = "updateStudentProfile";
		/* Select the input fields in the context of the update form. */
		var data = {
			firstName: $( "[name='firstName']", $editProfileForm ).val(),
			lastName: $( "[name='lastName']", $editProfileForm ).val(),
			email: $( "[name='email']", $editProfileForm ).val(),
			mobilePhone: $( "[name='mobilePhone']", $editProfileForm ).val(),
			classYear: $( "[name='classYear']", $editProfileForm ).val(),
			major: $( "[name='major']", $editProfileForm ).val(),
			gpa: $( "[name='gpa']", $editProfileForm ).val(),
			universityID: $( "[name='universityID']", $editProfileForm ).val(),
			aboutMe: $( "[name='aboutMe']", $editProfileForm ).val(),
			action: action
		}
		break;
	case PROFESSOR:
		/* Select the input fields in the context of the update form. */
		action = "updateProfessorProfile";
		var data = {
			firstName: $( "[name='firstName']", $editProfileForm ).val(),
			lastName: $( "[name='lastName']", $editProfileForm ).val(),
			email: $( "[name='email']", $editProfileForm ).val(),
			mobilePhone: $( "[name='mobilePhone']", $editProfileForm ).val(),
			officePhone: $( "[name='officePhone']", $editProfileForm ).val(),
			building: $( "[name='building']", $editProfileForm ).val(),
			room: $( "[name='room']", $editProfileForm ).val(),
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

	$.post( actionsUrl, data, function ( info ){});

	/*TODO: Obtain a confirmation from the PHP script on success/failure and 
	 * notify the user */
	$userModal.modal( "hide" );
}

function prepareBuildingsDropdown() { 
	var action = "fetchBuildings";
	var data = {
		action: action
	}
	$.post( actionsUrl, data, function ( buildings ) { 
		var buildings = eval( "(" + buildings + ")" );
		for( var i = 0; i < buildings.length; i++ ){
			$buildingsDropdown.append( "<option>" + buildings[i][ "building" ] + "</option>" );
		}
		$buildingsDropdown.trigger( "change" );
	 });
}

function prepareRoomsDropdown() {
	var action = "fetchTheRooms";
	var data = {
		building: $( this ).val(),
		action: action
	}
	clearDropdown( $roomsDropdown );
	$.post( actionsUrl, data, function ( rooms ) { 
		var rooms = eval( "(" + rooms + ")" );
		for( var i = 0; i < rooms.length; i++ ){
			$roomsDropdown.append( "<option>" + rooms[i] + "</option>" );
		}
	 });	
}

function prepareCoursesDropdown() {
	var action = "fetchCourses";
	var data = {
		action: action
	}
	$.post( actionsUrl, data, function ( courses ) { 
		var courses = eval( "("+ courses + ")" );
		for ( var i = 0; i < courses.length; i++ ){
			$coursesDropdown.append( "<option>" + courses[i][ "courseTitle" ] + "</option>" );
		}
		$coursesDropdown.trigger( "change" );
	 });

}

function prepareProfessorsDropdown() {
	var action = "fetchTheProfessors";
	var data = {
		courseTitle: $( this ).val(),
		action: action
	}	
	$.post( actionsUrl, data, function ( professors ) { 
		clearDropdown( $professorsDropdown );
		var professors = eval( "(" + professors + ")" );
		for( var i = 0; i < professors.length; i++ ){
			$professorsDropdown.append( "<option>" + professors[i][ "firstName" ] + " " + professors[i][ "lastName" ] + "</option>" );
		}
	 });

}

function clearDropdown( $dropdown ){
	$dropdown.find( "option" ).remove();
	
}
