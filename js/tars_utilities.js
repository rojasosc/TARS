$(document).ready(function() {
	window.STUDENT = 0;
	window.PROFESSOR = 1;
	window.STAFF = 2;
	window.ADMIN = 3;
	window.actionsUrl = "../actions.php";

	if ($(".search-users-form").length) {
		$userSearchForm = $(".search-users-form");
		$userSearchForm.on('submit', function(event) {
			event.preventDefault();
			searchUsers();
		});
		$userSearchForm.trigger('submit');
	}


	if ($(".user-search-table").length) {
		$results = $(".user-search-table");

	}

	if ($(".profile-modal").length) {
		$userModal = $(".profile-modal");
		if ($(".edit-profile-form").length) {

			//In case the button is not in a table
			$(".edit-profile").click(function() {
				viewUserForm($(this).data("userid"), $(this).data("usertype"));

			});
		}

		if ($(".profile").length) {
			$userModal = $(".profile-modal");
			$(".profile").click(viewUserProfile);
			if ($(".qualifications").length) {
				$qualifications = $(".qualifications");
				$(".profile").click(injectQualifications);
			}
		}
	}

	if ($(".password-modal").length) {
		$passwordModal = $(".password-modal");
		$passwordForm = $(".change-password-form");
		$(".change-password").click(function() {
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
			viewPasswordForm($(this).data("userid"), $passwordForm.data("usertype"));
		});
	}

	if ($(".comments-modal").length) {
		$commentsModal = $(".comments-modal");
		$commentsBlock = $(".comments-block");
		$(".comments").click(viewUserComments);
	}

	if ($(".buildings").length) {
		$buildingsData = $(".buildings");
		$buildingsInput = $("[name='building']");
		$roomsDropdown = $(".rooms");
		$buildingsInput.change(prepareRoomsDropdown);
		prepareBuildingsDropdown();

	}

	if ($(".courses").length) {
		$coursesDropdown = $(".courses");
		$professorsDropdown = $(".professors");
		prepareCoursesDropdown();
		$coursesDropdown.change(prepareProfessorsDropdown);
	}

	if ($(".decision").length) {
		$(".decision").click(submitDecision);
	}

	if ($(".pagination").length) {
		$(".pagination").on('click', 'a', function(event) {
			event.preventDefault();
			if (!$(this).parent().hasClass('active')) {
				data = $pgData;
				data.pgIndex = $(this).data('target');
				data.pgLength = 10; // XXX here is the number of rows per page requested
				data.pgGetTotal = false;
				doAction($pgAction, data).done($pgAjaxDone).fail($pgAjaxFail);
			}
		});
	}
	
	if($(".fetch-sections-form").length) {
		$filterSectionsForm = $('.fetch-sections-form');
		$filterSectionsForm.on('submit', function(event) {
			
			event.preventDefault();
			var input = {
				crn: $("[name='CRNFilter']", $filterSectionsForm).val(),
				course: $("[name='courseFilter']", $filterSectionsForm).val(),
				type: $("[name='typeFilter']", $filterSectionsForm).val(),
				status: $("input[type='radio']:checked", $filterSectionsForm).val()
			};
			doPaginatedAction('fetchSections', input, function(data) {
				if(data.success) {
					if(data.pg) {
						handlePagination(data.pg, $('.pagination'));
					}
					if(data.objects) {
						if(data.objects.length == 0) {
							$('thead tr').hide();
							$('#results').html('<em>No results</em>');
						} else {
					//		alert(JSON.stringify(data.objects));
							$('thead tr').show();
							var html = [];
							for (var key in data.objects) {
								var section = data.objects[key];
								var courseNum = 'TBD';
								var title = 'TBD';
								var type = 'TBD';
								var crn = 'TBD';
								var day = 'TBD';
								var startTime = 'TBD';
								var endTime = 'TBD'
								var building = 'TBD';
								var room = 'TBD';
								var labTA = 'TBD';
								var WSL = 'TBD';
								var WSSL = 'TBD';
								var lecTA = 'TBD';
								var grader = 'TBD';
								var instructorEmail = 'TBD';
								
								if(section.length > 0) {
									
									crn = section.crn;
									type = section.type;
									if(section.course.length > 0) {
										courseNum = 'CSC' + section.course[0].number;
										title = section.course[0].title;
									}
									if(section.instructor.length > 0) {
										//There has got to be a better way than this
										var length = section.instructor.length;
										instructorEmail = section.instructor[0].email;
										if(length > 1) {
											instructorEmail += ', ' + section.instructor[1].email;
										}
									}
									if(section.sessions.length > 0) {
										day = section.sessions[0].weekdays;
										startTime = section.sessions[0].startTime;
										endTime = section.sessions[0].endTime;
										building = section.sessions[0].building;
										room = section.sessions[0].room;
									}
								}
								var secRow = '<tr>';
								secRow += '<td class="courseNum">' + courseNum + '</td>';
								secRow += '<td class="type">' + type + '</td>';
								secRow += '<td class="crn">' + crn + '</td>';
								secRow += '<td class="days">' + day + '</td>';
								secRow += '<td class="time">' + startTime + ' - ' + endTime + '</td>';
								secRow += '<td class="place">' + building + ' ' + room + '</td>';
								secRow += '<td class="labTA">' + labTA + '</td>';
								secRow += '<td class="WSL">' + WSL + '</td>';
								secRow += '<td class="WSSL">' + WSSL + '</td>';
								secRow += '<td class="lecTA">' + lecTA + '</td>';
								secRow += '<td class="grader">' + grader + '</td>';
								secRow += '<td class="hidden instructor-emails">' + instructorEmail + '</td>';
								secRow += '<td class="hidden courseTitle">' + title + '</td>';
								secRow += '<td><button data-toggle="modal" data-target="edit-modal" class="btn btn-default edit-section circle"><span class="glyphicon glypphicon-wrench"></span></button></td>';
								secRow += '</tr>';
								html.push(secRow);
							}
							$('#results').html(html.join(''));
							
						}
					}
				} else {
					showError(data.error, $('#alertHolder'));
				}
			},
			function(jqXHR, textStatus, errorMessage) {
				showError({
					message: errorMessage
				}, $('#alertholder'));
			});
		});
	}

});

function doPaginatedAction(action, data, ajaxDone, ajaxFail) {
	var sameQuery = true;
	if (typeof $pgData != 'undefined') {
		for (var key in data) {
			if ($pgData[key] != data[key]) {
				sameQuery = false;
			}
		}
	} else {
		sameQuery = false;
	}
	$pgAction = action;
	$pgData = data;
	$pgAjaxDone = ajaxDone;
	$pgAjaxFail = ajaxFail;
	data.pgIndex = 1;
	data.pgLength = 10; // XXX: here is the number of rows per page requested
	data.pgGetTotal = !sameQuery;
	return doAction($pgAction, data).done(ajaxDone).fail(ajaxFail);
}

function handlePagination(pg, listDOM) {
	if (pg.getTotal) {
		$pgTotal = pg.total;
	}
	//console.log(pg);
	var pages = [];
	if ($pgTotal > 1) {
		if (pg.index > 4) {
			pages.push({
				target: 1,
				title: 'First Page',
				text: '&laquo;',
				active: false
			});
		}
		for (var i = Math.max(1, pg.index - 3); i <= Math.min($pgTotal, pg.index + 3); i++) {
			pages.push({
				target: i,
				title: 'Page ' + i.toString(),
				text: i.toString(),
				active: i == pg.index
			});
		}
		if (pg.index + 3 < $pgTotal) {
			pages.push({
				target: $pgTotal,
				title: 'Last Page',
				text: '&raquo;',
				active: false
			});
		}
	}

	var html = '';
	for (var idx in pages) {
		var active = '';
		if (pages[idx].active) {
			active = ' class="active"';
		}
		html += '<li' + active + '><a href="#" title="' + pages[idx].title + '" data-target="' + pages[idx].target + '">' + pages[idx].text + '</a></li>';
	}
	listDOM.html(html);
}

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
		params.push({
			name: 'action',
			value: action
		});
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
			case 'success':
				alertObj.title = 'Success';
				break;
			case 'warning':
				alertObj.title = 'Warning';
				break;
			case 'danger':
				alertObj.title = 'An error occured';
				break;
			default: alertObj.title = 'Notice';
					 level = 'info';
					 break;                
		}
	}
	element.hide();
	element.html('<div class="alert alert-' + level + '"><strong>' + alertObj.title + '!</strong> ' + alertObj.message + '</div>');
	element.fadeIn('slow');
}

function submitDecision() {
	doAction('setAppStatus', {
		appID: $(this).parent().data('applicationid'),
	decision: $(this).data('decision')
	}).done(function(data) {
		if (data.success) {
			showAlert({
				message: 'Application status updated.'
			}, $('#alertHolder'), 'success');
			location.reload(); // XXX: hides alert that something happened!
		} else {
			showError(data.error, $('#alertHolder'));
		}
	}).fail(function(jqXHR, textStatus, errorMessage) {
		showError({
			message: errorMessage
		}, $('#alertHolder'));
	});
}

function injectQualifications() {
	doAction('fetchApplication', {
		appID: $(this).data('appid')
	}).done(function(data) {
		if (data.success) {
			$qualifications.html(data.object.qualifications);
		} else {
			showError(data.error, $('#profileAlertHolder'));
		}
	}).fail(function(jqXHR, textStatus, errorMessage) {
		showError({
			message: errorMessage
		}, $('#profileAlertHolder'));
	});
}

function viewUserComments() {
	doAction('fetchComments', {
		userID: $(this).data('userid')
	}).done(function(data) {
		if (data.success) {
			var comments = data.objects;
			for (var i = comments.length - 1; i >= 0; i--) {
				$comment = comments[i];
				$commentsBlock.append("<p class='commentDate'>" + $comment.createTime + "</p><blockquote><p class='commentContent'>" +
					$comment.comment + "</p><footer>" + $comment.creator.firstName + ' ' + $comment.creator.lastName + "</footer></blockquote></div><!-- End column --></div> <!-- End row --><br>");
			}
			if (!comments.length) {
				$commentsBlock.html("There are no reviews available for this student.");
			}
		} else {
			showError(data.error, $('#commentsAlertHolder'));
		}
	}).fail(function(jqXHR, textStatus, errorMessage) {
		showError({
			message: errorMessage
		}, $('#commentsAlertHolder'));
	});
	$commentsModal.bind("hidden.bs.modal", function() {
		$commentsBlock.html("");
	});
}

function searchUsers() {
	var input = {
		firstName: $("[name='fN']", $userSearchForm).val(),
		lastName: $("[name='lN']", $userSearchForm).val(),
		email: $("[name='emailSearch']", $userSearchForm).val(),
		userType: $("input[type='radio']:checked", $userSearchForm).val(),
		pgIndex: 1,
		pgLength: 15,
		pgGetTotal: true
	};
	doPaginatedAction('searchForUsers', input,
		function(data) {
			if (data.success) {
				if (data.pg) {
					handlePagination(data.pg, $('.pagination'));
				}
				if (data.objects) {
					if (data.objects.length === 0) {
						$('thead tr').hide();
						$results.find('tbody').html('<em>No results</em');
					} else {
						$('thead tr').show();
						viewResults(data.objects, $("input[type='radio']:checked", $userSearchForm).val());
					}
				}
			} else {
				showError(data.error, $('#alertHolder'));
			}
		},
		function(jqXHR, textStatus, errorMessage) {
			showError({
				message: errorMessage
			}, $('#alertHolder'));
		});
}

function viewResults(users, userType) {
	var userType = parseInt(userType,10);
	/* Clear any existing results */
	$results.hide();
	$results.find("tbody").remove();
	/* Associative array of a user */
	/* Render results table */
	var row = new Array(),
		j = -1;
	var size = users.length;
	for (var key = 0; key < size; key++) {
		row[++j] = "<tr><td>";
		row[++j] = users[key].firstName;
		row[++j] = "</td><td>";
		row[++j] = users[key].lastName;
		row[++j] = "</td><td>";
		row[++j] = users[key].email;
		row[++j] = "</td><td>";
		row[++j] = "<button data-toggle='modal' data-target='#profile-modal' class='btn btn-default edit-profile circle' data-userid='" +
					users[key].id + "' data-usertype='" + userType + "'><span class='glyphicon glyphicon-wrench'></span></button>";
		row[++j] = "</td></tr>";

	}

	/* Render the appropriate user profile update forms */
	$results.append(row.join(''));
	$results.show();
	$(".edit-profile").click(function() {
		viewUserForm($(this).data("userid"), userType);
	});

}

function viewUserProfile() {
	doAction('fetchUser', {
		userID: $(this).data('userid'),
	userType: $(this).data('usertype')
	}).done(function(data) {
		if (data.success) {
			prepareStudentModal(data.object, $(this).data('usertype'));
		} else {
			showError(data.error, $('#profileAlertHolder'));
		}
	}).fail(function(jqXHR, textStatus, errorMessage) {
		showError({
			message: errorMessage
		}, $('#profileAlertHolder'));
	});
}

function prepareStudentModal(student) {
	$("#studentModalTitle").html(student.firstName + " " + student.lastName);
	$("#studentMajor").html("Major: " + student.major);
	$("#studentGPA").html("GPA: " + student.gpa);
	$("#studentEmail").html("Email: " + student.email);
	$("#studentMobilePhone").html("Mobile Phone: " + student.mobilePhone);
	$("#studentAboutMe").html(student.aboutMe);
	$("#studentClassYear").html("Class Year: " + student.classYear);

}

function viewPasswordForm(userID, userType) {
	doAction('fetchUser', {
		userID: userID,
	userType: userType
	}).done(function(data) {
		if (data.success) {
			preparePasswordForm(data.object);
		} else {
			showError(data.error, $('#editPasswordAlertHolder'));
		}
	}).fail(function(jqXHR, textStatus, errorMessage) {
		showError({
			message: errorMessage
		}, $('#editPasswordAlertHolder'));
	});
}

function viewUserForm(userID, userType) {
	var userType = parseInt(userType, 10);
	switch (userType) {
		case STUDENT:
			$editProfileForm = $("#profileForm0");
			break;
		case PROFESSOR:
			$editProfileForm = $("#profileForm1");
			break;
	}
	$editProfileForm.submit(function() {
		return false;
	});
	doAction('fetchUser', {
		userID: userID,
		userType: userType
	}).done(function(data) {
		if (data.success) {
			prepareUserForm(data.object, userType);
		} else {
			showError(data.error, $('#editProfileAlertHolder'));
		}
	}).fail(function(jqXHR, textStatus, errorMessage) {
		showError({
			message: errorMessage
		}, $('#editProfileAlertHolder'));
	});
}

function preparePasswordForm(user) {
	$("[name='email']", $passwordForm).val(user.email);
	$("[type='submit']").click(changeUserPassword);
	$passwordModal.modal("show");
}

function prepareUserForm($user, userType) {
	window.$user = $user;
	$("#modalHeader").html($user.firstName + " " + $user.lastName);
	$("[name='firstName']", $editProfileForm).val($user["firstName"]);
	$("[name='lastName']", $editProfileForm).val($user["lastName"]);
	$("[name='email']", $editProfileForm).val($user["email"]);
	switch (userType) {
		case STUDENT:
			$("#profileForm1").hide();
			$("#profileForm0").show();
			$("[name='mobilePhone']", $editProfileForm).val($user.mobilePhone);
			$("[name='classYear']", $editProfileForm).val($user.classYear);
			$("[name='major']", $editProfileForm).val($user.major);
			$("[name='gpa']", $editProfileForm).val($user.gpa);
			$("[name='universityID']", $editProfileForm).val($user.universityID);
			$("[name='aboutMe']", $editProfileForm).val($user.aboutMe);
			break;
		case PROFESSOR:
			$("#profileForm0").hide();
			$("#profileForm1").show();
			if ($user.officePhone !== null) {
				$("[name='officePhone']", $editProfileForm).val($user.officePhone);
			}
			if ($user.office !== null) {
				$("[name='building']", $editProfileForm).val($user.office.building);
				$("[name='room']", $editProfileForm).val($user.office.room);
			}
			break;
		case STAFF:
			$("#profileForm0").hide();
			$("#profileForm1").show();
			if ($user.officePhone !== null) {
				$("[name='officePhone']", $editProfileForm).val($user.officePhone);
			}
			if ($user.office !== null) {
				$("[name='building']", $editProfileForm).val($user.office.building);
				$("[name='room']", $editProfileForm).val($user.office.room);
			}
			break;
		case ADMIN:
			// I doubt admins have extra properties
			break;
		default:
			break;
	}
	$("[type='submit']").click(updateUserProfile);
	$userModal.modal("show");
}

function changeUserPassword() {
	doAction('changeUserPassword', {
		oldPassword: $("[name='oldPassword']", $passwordForm).val(),
	newPassword: $("[name='newPassword']", $passwordForm).val(),
	confirmPassword: $("[name='confirmPassword']", $passwordForm).val()
	}).done(function(data) {
		if (data.success) {
			$passwordModal.modal('hide');
			showAlert({
				message: 'Your password has been changed.'
			}, $('#alertHolder'), 'success');
		} else {
			showError(data.error, $('#editPasswordAlertHolder'));
		}
	}).fail(function(jqXHR, textStatus, errorMessage) {
		showError({
			message: errorMessage
		}, $('#editPasswordAlertHolder'));
	});
}

function updateUserProfile() {
	var action = "";
	var input = {};
	switch ($user.type) {
		case STUDENT:
			/* Select the input fields in the context of the update form. */
			input = {
				userID: $user.id,
				firstName: $("[name='firstName']", $editProfileForm).val(),
				lastName: $("[name='lastName']", $editProfileForm).val(),
				mobilePhone: $("[name='mobilePhone']", $editProfileForm).val(),
				classYear: $("[name='classYear']", $editProfileForm).val(),
				major: $("[name='major']", $editProfileForm).val(),
				gpa: $("[name='gpa']", $editProfileForm).val(),
				universityID: $("[name='universityID']", $editProfileForm).val(),
				aboutMe: $("[name='aboutMe']", $editProfileForm).val()
			}
			break;
		case PROFESSOR:
		case STAFF:
			/* Select the input fields in the context of the update form. */
			input = {
				userID: $user.id,
				firstName: $("[name='firstName']", $editProfileForm).val(),
				lastName: $("[name='lastName']", $editProfileForm).val(),
				officePhone: $("[name='officePhone']", $editProfileForm).val(),
				building: $("[name='building']", $editProfileForm).val(),
				room: $("[name='room']", $editProfileForm).val()
			}
			break;
		case ADMIN:
			break;
	}

	doAction('updateProfile', input).done(function(data) {
		if (data.success) {
			$userModal.modal('hide');
			updateUserProfileCard(data.object, $user.type);
			showAlert({
				message: 'Your profile has been updated.'
			}, $('#alertHolder'), 'success');
		} else {
			showError(data.error, $('#editProfileAlertHolder'));
		}
	}).fail(function(jqXHR, textStatus, errorMessage) {
		showError({
			message: errorMessage
		}, $('#editProfileAlertHolder'));
	});
}

function updateUserProfileCard(data, userType) {
	// iterate through the properties of data
	//console.log(data);
	for (key in data) {
		//console.log(key);
		if (data[key] === null) {
			$('#cur-' + key).text('');
		} else {
			$('#cur-' + key).text(data[key]);
		}
	}
}

function prepareBuildingsDropdown() {
	doAction('fetchBuildings', {}).done(function(data) {
		if (data.success) {
			var buildings = data.objects;
			for (var i = 0; i < buildings.length; i++) {
				$buildingsData.append("<option>" + buildings[i] + "</option>");
			}
			$buildingsInput.trigger("change");
		} else {
			showError(data.error, $('#alertHolder'));
		}
	}).fail(function(jqXHR, textStatus, errorMessage) {
		showError({
			message: errorMessage
		}, $('#alertHolder'));
	});
}

function prepareRoomsDropdown() {
	clearDropdown($roomsDropdown);
	if ($(this).val() !== '') {
		doAction('fetchRooms', {
			building: $(this).val()
		}).done(function(data) {
			if (data.success) {
				var rooms = data.objects;
				for (var i = 0; i < rooms.length; i++) {
					$roomsDropdown.append('<option>' + rooms[i] + '</option>');
				}
				$roomsDropdown.trigger("change");
			} else {
				showError(data.error, $('#alertHolder'));
			}
		}).fail(function(jqXHR, textStatus, errorMessage) {
			showError({
				message: errorMessage
			}, $('#alertHolder'));
		});
	}
}

function prepareCoursesDropdown() {
	// TODO update with editTermUI
	var action = "fetchCourses";
	var data = {
		action: action
	}
	$.post(actionsUrl, data, function(courses) {
		var courses = eval("(" + courses + ")");
		for (var i = 0; i < courses.length; i++) {
			$coursesDropdown.append("<option>" + courses[i]["courseTitle"] + "</option>");
		}
		$coursesDropdown.trigger("change");
	});

}

function prepareProfessorsDropdown() {
	// TODO update with editTermUI
	var action = "fetchTheProfessors";
	var data = {
		courseTitle: $(this).val(),
		action: action
	}
	$.post(actionsUrl, data, function(professors) {
		clearDropdown($professorsDropdown);
		var professors = eval("(" + professors + ")");
		for (var i = 0; i < professors.length; i++) {
			$professorsDropdown.append("<option>" + professors[i].firstName + " " + professors[i].lastName + "</option>");
		}
		$professorsDropdown.trigger('change');
	});

}

function clearDropdown($dropdown) {
	$dropdown.find("option").remove();

}
