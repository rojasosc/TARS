$(document).ready(function() {
	$professorForm = $("#professorForm");
	$professorPanel = $("#createAccountPanel");
	$professorPanelFooter = $("#professorPanelFooter");
	$("#createAccountBody").toggle("show");

	$professorForm.bootstrapValidator({
		message: "This value is not valid",
		feedbackIcons: {
			valid: "glyphicon glyphicon-ok",
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
			
		},
		fields: {
			firstName: {
				message: 'The first name is not valid',
					validators: {
					notEmpty: {
						message: 'The first name is required and cannot be empty'
					},
					stringLength: {
						min: 1,
						message: 'The first name must have at least one character'
					},
					regexp: {
						regexp: /^[\D\s]+$/,
						message: 'The first name can only consist of alphabetical characters'
					}
				}
			},
			lastName: {
				message: 'The last name is not valid',
				validators: {
					notEmpty: {
						message: 'The last name is required and cannot be empty'
					},
					stringLength: {
						min: 1,
						message: 'The last name must have at least one character'
					},
					regexp: {
						regexp: /^[\D\s]+$/,
						message: 'The last name can only consist of alphabetical characters'
					}
				}
			},	    
			email: {
				message: 'The email is not valid',
				validators: {
					notEmpty: {
						message: 'The email is required and cannot be empty'
					},
					emailAddress: {
						message: 'the input is not a valid email address'
					},
					remote: {
						message: 'This email address is already in use',
						url: '../actions.php?action=emailAvailable'
					}
				}
			},
			officePhone: {
				message: 'The office phone number is not valid',
				validators: {
					phone: {
						country: 'US',
						message: 'The phone number must be valid and include the area code'
					}
				}
			},	 
		} /* END Fields */				
    }); /* END bootstrapValidator */

	$professorForm.submit(function(event){
		event.preventDefault();
		createProfessor();
	});

});

function createProfessor(){
	clearError($('#alertHolder'));
	doAction('createUser', {
		firstName: $("[name='firstName']",$professorForm).val(),
		lastName: $("[name='lastName']",$professorForm).val(),
		email: $("[name='email']",$professorForm).val(),
		officePhone: $("[name='officePhone']",$professorForm).val(),
		building: $("[name='building']",$professorForm).val(),
		room: $("[name='room']",$professorForm).val(),
		userType: PROFESSOR
	}).done(function (data) {
		if (data.success) {
			showAlert({message: 'Successfully created a user. They will receive an email to confirm their account.'}, $('#alertHolder'), 'success');
		} else {
			showError(data.error, $('#alertHolder'));
		}
	}).fail(function (jqXHR, textStatus, errorMessage) {
		showError({message: errorMessage}, $('#alertHolder'));
	});
}

function refreshForm(){
	clearInput();
	$professorPanel.collapse('hide');
	$professorForm.data('bootstrapValidator').resetForm();
	$professorPanel.collapse('show');
	$professorPanelFooter.html("The account was successfully created.");
}

function clearInput() {
	$("input",$professorForm).each(function() { $(this).val(''); });
}

