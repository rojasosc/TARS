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
		submitHandler: function(validator, form, submitButton) {
				createProfessor();
		},
		fields: {
			firstName: {
				message: 'a first name is not valid',
					validators: {
					notEmpty: {
						message: 'a first name is required and cannot be empty'
					},
					stringLength: {
						min: 2,
						max: 30,
						message: 'a first name must be between 2 and 30 characters long'
					},
					regexp: {
						regexp: /^[a-z_A-Z]+$/,
						message: 'a first name can only consist of alphabetical characters'
					}
				}
			},
			lastName: {
				message: 'a last name is not valid',
				validators: {
					notEmpty: {
						message: 'a last name is required and cannot be empty'
					},
					stringLength: {
						min: 2,
						max: 30,
						message: 'a last name must be between 2 and 30 characters long'
					},
					regexp: {
						regexp: /^[a-z_A-Z]+$/,
						message: 'a last name can only consist of alphabetical characters'
					}
				}
			},	    
			email: {
				validators: {
					notEmpty: {
						message: 'an email is required and cannot be empty'
					},
					emailAddress: {
						message: 'the input is not a valid email address'
					},
					remote: {
						message: 'This email address is already in use',
						url: '../emailExists.php'
					}
				}
			},
			emailConfirm: {
				validators: {
					notEmpty: {
						message: 'an email confirmation is required and cannot be empty'
					},
					emailAddress: {
						message: 'the input is not a valid email address'
					},
					identical: {
						field: 'email',
						message: 'email addresses don\'t match'
						
					}					
				}
			},
			password: {
				message: 'a password is not valid',
					validators: {
					notEmpty: {
						message: 'a password is required and cannot be empty'
					},
					stringLength: {
						min: 6,
						max: 20,
						message: 'a password must be between 6 and 20 characters long'
					},
					regexp: {
						regexp: /^[a-z_A-Z0-9]+$/,
						message: 'a password can only consist of alphabetical and numerical characters'
					}
				}
			},	 
			passwordConfirm: {
				message: 'a password is not valid',
					validators: {
					notEmpty: {
						message: 'a password is required and cannot be empty'
					},
					stringLength: {
						min: 6,
						max: 20,
						message: 'a password must be between 6 and 20 characters long'
					},
					regexp: {
						regexp: /^[a-z_A-Z0-9]+$/,
						message: 'a password can only consist of alphabetical and numerical characters'
					},
					identical: {
						field: 'password',
						message: 'Passwords don\'t match'
						
					}
				}
			},
			officePhone: {
				message: 'the office phone is not valid',
				validators: {
					notEmpty: {
						message: 'an office phone is required and cannot be empty'
					},
					stringLength: {
						min: 10,
						max: 15,
						message: 'an office phone must be between 10 and 15 characters long'
					},
					regexp: {
						regexp: /^[0-9]+$/,
						message: 'an office phone can only consist of numerical digits'
					}
				}
			},	 
			mobilePhone: {
				message: 'the mobile phone is not valid',
				validators: {
					notEmpty: {
						message: 'a mobile phone is required and cannot be empty'
					},
					stringLength: {
						min: 10,
						max: 15,
						message: 'a mobile phone must be between 10 and 15 characters long'
					},
					regexp: {
						regexp: /^[0-9]+$/,
						message: 'a mobile phone can only consist of numerical digits'
					}
				}
			}  
		} /* END Fields */				
    }); /* END bootstrapValidator */

	$professorForm.submit(function(event){ return false; });

});

function createProfessor(){
    var url = $professorForm.attr('action');
    var action = 'createProfessor';
	var data = {
		firstName: $("[name='firstName']",$professorForm).val(),
		lastName: $("[name='lastName']",$professorForm).val(),
		email: $("[name='email']",$professorForm).val(),
		password: $("[name='password']",$professorForm).val(),
		mobilePhone: $("[name='mobilePhone']",$professorForm).val(),
		officePhone: $("[name='officePhone']",$professorForm).val(),
		building: $("[name='building']",$professorForm).val(),
		room: $("[name='room']",$professorForm).val(),
		action: action
	}
    $.post(url,data,function () { refreshForm(); });		
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