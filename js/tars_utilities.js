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

	if( $( ".password-modal" ).length ){
		$passwordModal = $( ".password-modal" );
		$passwordForm = $( ".change-password-form");
		$( ".change-password" ).click(function () {
			$passwordForm.bootstrapValidator({
				message: 'This value is not valid',
				feedbackIcons: {
					valid: 'glyphicon glyphicon-ok',
					invalid: 'glyphicon glyphicon-remove',
					validating: 'glyphicon glyphicon-refresh'
				},
				fields: {
					oldPassword: {
						validators: {
							notEmpty: {
								message: 'You must confirm your old password'
							}
						}
					},			
					newPassword: {
						message: 'Your password is not valid',
						validators: {
							notEmpty: {
								message: 'Your password is required and cannot be empty'
							},
							stringLength: {
								min: 6,
								max: 20,
								message: 'Your password must be between 6 and 20 characters long'
							}
						}
					},	 
					confirmPassword: {
						validators: {
							notEmpty: {
								message: 'Passwords do not match'
							},
							identical: {
								field: 'newPassword',
								message: 'Passwords do not match'

							}
						}
					}
				} /* close fields */		
			}); 
			viewPasswordForm( $(this).data( "userid" ), $passwordForm.data( "usertype" ) );
		});
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

	if( $( ".decision" ).length ){
		$( ".decision" ).click(submitDecision);
	}


});

function doAction(action, params, altUrl) {
	var url = actionsUrl;
	if (arguments.length == 3) {
		url = altUrl;
	}
	var request = {
		type: 'POST',
		url: url,
		dataType: 'json'
	};
	if (typeof FormData === 'function' && params instanceof FormData) {
		request.data = params;
		request.url += '?action=' + encodeURIComponent(action);
		request.cache = false;
		request.contentType = false;
		request.processData = false;
	} else if (params instanceof Array) {
		params.push({name: 'action', value: action});
		request.data = params;
	} else {
		params.action = action;
		request.data = params;
	}
	// return the Deferred so that code can .done() and .fail() it
	return $.ajax(request);
}

function showError(errorObj, element) {
	showAlert(errorObj, element, 'danger');
}

function showAlert(alertObj, element, level) {
	if (!('title' in alertObj)) {
		switch (level) {
			default:
				alertObj.title = 'Notice';
				level = 'info';
				break;
			case 'success':
				alertObj.title = 'Success';
				break;
			case 'warning':
				alertObj.title = 'Warning';
				break;
			case 'danger':
				alertObj.title = 'An error occured';
				break;
		}
	}
	element.hide();
	element.html('<div class="alert alert-' + level + '"><strong>' + alertObj.title + '!</strong> ' + alertObj.message + '</div>');
	element.fadeIn('slow');
}

function submitDecision(){
	doAction('setAppStatus', {
		appID: $(this).parent().data('applicationid'),
		decision: $(this).data('decision')
	}).done(function (data) {
		if (data.success) {
			showAlert({message: 'Application status updated.'}, $('#alertHolder'), 'success');
			location.reload(); // XXX: hides alert that something happened!
		} else {
			showError(data.error, $('#alertHolder'));
		}
	}).fail(function (jqXHR, textStatus, errorMessage) {
		showError({message: errorMessage}, $('#alertHolder'));
	});
}

function injectQualifications() {
	doAction('fetchApplication', {
		appID: $(this).data('appid')
	}).done(function (data) {
		if (data.success) {
			$qualifications.html(data.object.qualifications);
		} else {
			showError(data.error, $('#alertHolder'));
		}
	}).fail(function (jqXHR, textStatus, errorMessage) {
		showError({message: errorMessage}, $('#alertHolder'));
	});
}

function viewUserComments() {
	doAction('fetchComments', {
		userID: $(this).data('userid')
	}).done(function (data) {
		if (data.success) {
			var comments = data.objects;
			for ( var i = comments.length - 1; i >= 0; i-- ) {
				$comment = comments[i];
				$commentsBlock.append( "<p class='commentDate'>" + $comment.createTime + "</p><blockquote><p class='commentContent'>"+ 
					$comment.comment + "</p><footer>" + $comment.creator.firstName + ' ' + $comment.creator.lastName + "</footer></blockquote></div><!-- End column --></div> <!-- End row --><br>" );		
			};
			if(!comments.length){
				$commentsBlock.html("There are no reviews available for this student.");
			}
		} else {
			showError(data.error, $('#alertHolder'));
		}
	}).fail(function (jqXHR, textStatus, errorMessage) {
		showError({message: errorMessage}, $('#alertHolder'));
	});
	$commentsModal.bind("hidden.bs.modal", function() { $commentsBlock.html(""); });
}
function searchUsers( userType ) {
	doAction('searchForUsers', {
		firstName: $( "[name='firstName']", $userSearchForm ).val(),
		lastName: $( "[name='lastName']", $userSearchForm).val(),
		email: $( "[name='emailSearch']", $userSearchForm).val(),
		userTypes: Math.pow(2,STUDENT)+Math.pow(2,1+ PROFESSOR)
	}).done(function (data) {
		if (data.success) {
			viewResults(data.objects);
		} else {
			showError(data.error, $('#alertHolder'));
		}
	}).fail(function (jqXHR, textStatus, errorMessage) {
		showError({message: errorMessage}, $('#alertHolder'));
	});
}

function viewResults( users ) {
	/* Clear any existing results */
	$results.hide();
	$results.find( "tbody" ).remove();
	/* Associative array of a user */
	/* Render results table */
	var row = new Array(), j = -1;
	var size = users.length; 
	for ( var key = 0; key < size; key++ ){
		row[ ++j ] ="<tr><td>";
		row[ ++j ] = users[ key ].firstName;
		row[ ++j ] = "</td><td>";
		row[ ++j ] = users[ key ].lastName;
		row[ ++j ] = "</td><td>";
		row[ ++j ] = users[ key ].email;
		row[ ++j ] = "</td><td>";
		row[ ++j ] = "<button data-toggle='modal' data-target='#profile-modal' class='btn btn-default edit-profile circle' data-userid='" + 
						users[ key ].id + "'><span class='glyphicon glyphicon-wrench'></span></button>"
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
	doAction('fetchUser', {
		userID: $(this).data('userid'),
		userType: $(this).data('usertype')
	}).done(function (data) {
		if (data.success) {
			prepareStudentModal(data.object, $(this).data('usertype'));
		} else {
			showError(data.error, $('#alertHolder'));
		}
	}).fail(function (jqXHR, textStatus, errorMessage) {
		showError({message: errorMessage}, $('#alertHolder'));
	});
}

function prepareStudentModal( student ) {
	$( "#studentModalTitle" ).html( student.firstName + " " + student.lastName );
	$( "#studentMajor" ).html( "Major: " + student.major );
	$( "#studentGPA" ).html( "GPA: " + student.gpa );
	$( "#studentEmail" ).html( "Email: " + student.email );
	$( "#studentMobilePhone" ).html( "Mobile Phone: " + student.mobilePhone );
	$( "#studentAboutMe" ).html( student.aboutMe );
	$( "#studentClassYear" ).html( "Class Year: " + student.classYear );

}

function viewPasswordForm( userID, userType ){
	doAction('fetchUser', {userID: userID, userType: userType})
	.done(function (data) {
		if (data.success) {
			preparePasswordForm(data.object, userType);
		} else {
			showError(data.error, $('#alertHolder'));
		}
	}).fail(function (jqXHR, textStatus, errorMessage) {
		showError({message: errorMessage}, $('#alertHolder'));
	});
}

function viewUserForm( userID, userType ) {
	doAction('fetchUser', {userID: userID, userType: userType})
	.done(function (data) {
		if (data.success) {
			prepareUserForm(data.object, userType);
		} else {
			showError(data.error, $('#alertHolder'));
		}
	}).fail(function (jqXHR, textStatus, errorMessage) {
		showError({message: errorMessage}, $('#alertHolder'));
	});
}

function preparePasswordForm( user, userType ){
	$( "[name='email']", $passwordForm).val( user.email );
	$( "[type='submit']" ).click( changeUserPassword );
	$passwordModal.modal( "show" );	
}

function prepareUserForm( $user, userType ) {
	window.$user = $user;
	switch( userType ){
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
	$( "[type='submit']" ).click( updateUserProfile );
	$userModal.modal( "show" );
}

function changeUserPassword(){
	doAction('changeUserPassword', {
		oldPassword: $( "[name='oldPassword']", $passwordForm).val(),
		newPassword: $( "[name='newPassword']", $passwordForm).val(),
		confirmPassword: $( "[name='confirmPassword']", $passwordForm).val()
	}).done(function (data) {
		if (data.success) {
			$passwordModal.modal('hide');
			showAlert({message:'Your password has been changed.'}, $('#alertHolder'),	'success');
		} else {
			showError(data.error, $('#alertHolder'));
		}
	}).fail(function (jqXHR, textStatus, errorMessage) {
		showError({message: errorMessage}, $('#alertHolder'));
	});
}

function updateUserProfile() {
	var action = "";
	var data = {};
	switch($user[ "type" ]){
	case STUDENT:
		/* Select the input fields in the context of the update form. */
		data = {
			firstName: $( "[name='firstName']", $editProfileForm ).val(),
			lastName: $( "[name='lastName']", $editProfileForm ).val(),
			mobilePhone: $( "[name='mobilePhone']", $editProfileForm ).val(),
			classYear: $( "[name='classYear']", $editProfileForm ).val(),
			major: $( "[name='major']", $editProfileForm ).val(),
			gpa: $( "[name='gpa']", $editProfileForm ).val(),
			universityID: $( "[name='universityID']", $editProfileForm ).val(),
			aboutMe: $( "[name='aboutMe']", $editProfileForm ).val(),
		}
		break;
	case PROFESSOR:
		/* Select the input fields in the context of the update form. */
		data = {
			firstName: $( "[name='firstName']", $editProfileForm ).val(),
			lastName: $( "[name='lastName']", $editProfileForm ).val(),
			officePhone: $( "[name='officePhone']", $editProfileForm ).val(),
			building: $( "[name='building']", $editProfileForm ).val(),
			room: $( "[name='room']", $editProfileForm ).val(),
		}
		break;
	case STAFF:
		break;
	case ADMIN:
		break; 
	}

	doAction('updateProfile', data)
	.done(function(data) {
		if (data.success) {
			$userModal.modal('hide');
			showAlert({message:'Your profile has been updated.'}, $('#alertHolder'), 'success');
		} else {
			showError(data.error, $('#alertHolder'));
		}
	}).fail(function (jqXHR, textStatus, errorMessage) {
		showError({message: errorMessage}, $('#alertHolder'));
	});
}

function prepareBuildingsDropdown() { 
	doAction('fetchBuildings', {})
	.done(function (data) {
		if (data.success) {
			var buildings = data.objects;
			for( var i = 0; i < buildings.length; i++ ){
				$buildingsDropdown.append( "<option>" + buildings[i] + "</option>" );
			}
			$buildingsDropdown.trigger( "change" );
		} else {
			showError(data.error, $('#alertHolder'));
		}
	}).fail(function (jqXHR, textStatus, errorMessage) {
		showError({message: errorMessage}, $('#alertHolder'));
	});
}

function prepareRoomsDropdown() {
	clearDropdown( $roomsDropdown );
	if ($(this).val() != '') {
		doAction('fetchTheRoom', {building: $(this).val()})
		.done(function ( data ) {
			if (data.success) {
				var rooms = data.objects;
				for( var i = 0; i < rooms.length; i++ ){
					$roomsDropdown.append( '<option>' + rooms[i] + '</option>' );
				}
				$roomsDropdown.trigger( "change" );
			} else {
				showError(data.error, $('#alertHolder'));
			}
		}).fail(function (jqXHR, textStatus, errorMessage) {
			showError({message: errorMessage}, $('#alertHolder'));
		});
	}
}

function prepareCoursesDropdown() {
	// TODO update with editTermUI
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
	// TODO update with editTermUI
	var action = "fetchTheProfessors";
	var data = {
		courseTitle: $( this ).val(),
		action: action
	}	
	$.post( actionsUrl, data, function ( professors ) { 
		clearDropdown( $professorsDropdown );
		var professors = eval( "(" + professors + ")" );
		for( var i = 0; i < professors.length; i++ ){
			$professorsDropdown.append( "<option>" + professors[i].firstName + " " + professors[i].lastName + "</option>" );
		}
		$professorsDropdown.trigger('change');
	 });

}

function clearDropdown( $dropdown ){
	$dropdown.find( "option" ).remove();
	
}
