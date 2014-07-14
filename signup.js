$(document).ready(function () {
	
	/* Attach a bootstrapValidator to the form */	
	$('#signupForm').bootstrapValidator({
		message: 'This value is not valid',
		feedbackIcons: {
		valid: 'glyphicon glyphicon-ok',
		invalid: 'glyphicon glyphicon-remove',
		validating: 'glyphicon glyphicon-refresh'
		},
		submitHandler: function(validator, form, submitButton) {
			// Ajax post(url,data,callback function)
			var url = $('#signupForm').attr('action');
			var data = $('#signupForm :input').serializeArray();
			$.ajax({
				type: 'POST',
				url: url,
				data: data,
				dataType: 'json',
				success: function(data) {
					if (data.success) {
						//Remove the form and display a confirmation.
						displayConfirmation();
					} else {
						$('#alertHolder').html(
							'<div class="alert alert-danger">' +
							'<strong>' + data.error.title + '!</strong> '+
							data.error.message+'</div>');
					}
				},
				error: function(jsXHR, textStatus, errorThrown) {
					$('#alertHolder').html(
						'<div class="alert alert-danger">' +
						'<strong>Error creating an account!</strong> '+
						'An AJAX error occured (' + errorThrown + ')</div>');
				}
				});
		},
		fields: {
		firstName: {
			message: 'Your first name is not valid',
			validators: {
			notEmpty: {
				message: 'Your first name is required and can\'t be empty'
			},
			stringLength: {
				min: 2,
				max: 30,
				message: 'Your first name must be between 2 and 30 characters long'
			},
			regexp: {
				regexp: /^[a-zA-Z0-9" "]+$/,
				message: 'Your first name can only consist of alphabetical characters'
			}
			}
		},
		lastName: {
			message: 'Your last name is not valid',
			validators: {
			notEmpty: {
				message: 'Your last name is required and can\'t be empty'
			},
			stringLength: {
				min: 2,
				max: 30,
				message: 'Your last name must be between 2 and 30 characters long'
			},
			regexp: {
				regexp: /^[a-zA-Z0-9" "]+$/,
				message: 'Your last name can only consist of alphabetical characters'
			}
			}
		},	    
		email: {
			validators: {
			notEmpty: {
				message: 'Your email is required and can\'t be empty'
			},
			emailAddress: {
				message: 'Your input is not a valid email address'
			},
			remote: {
				message: 'This email is already in use',
				url: 'emailExists.php'
			}
			
			}		
		},
		emailConfirm: {
			validators: {
			notEmpty: {
				message: 'An email confirmation is required and can\'t be empty'
			},
			identical: {
				field: 'email',
				message: 'Email addresses don\'t match'
				
				}		    
			}
		},
		password: {
			message: 'Your password is not valid',
			validators: {
			notEmpty: {
				message: 'Your password is required and can\'t be empty'
			},
			stringLength: {
				min: 6,
				max: 20,
				message: 'Your password must be between 6 and 20 characters long'
			},
			regexp: {
				regexp: /^[a-zA-Z0-9]+$/,
				message: 'Your password can only consist of alphabetical and numerical characters'
			}
			}
		},	 
		passwordConfirm: {
			message: 'Your password is not valid',
			validators: {
			notEmpty: {
				message: 'Your password is required and can\'t be empty'
			},
			identical: {
				field: 'password',
				message: 'Passwords don\'t match'
				
			}

			}
		},
		mobilePhone: {
			message: 'Your mobile phone is not valid',
			validators: {
			notEmpty: {
				message: 'Your mobile phone is required and can\'t be empty'
			},
			phone: {
				country: 'US',
				message: 'Your mobile phone must be valid and include the area code'
			}
			}
		},
		gpa: {
			message: 'Your gpa is not valid',
			validators: {
			notEmpty: {
				message: 'Your gpa is required and can\'t be empty'
			},
			between: {
				min: 0,
				max: 4,
				inclusive: false,
				message: 'Your gpa must be a decimal value from 0.000 to 4.000'
			},
			stringLength: {
				min: 1,
				max: 5,
				message: 'Your gpa will not be stored so precisely'
			}

			}
		},   	
		universityID: {
			message: 'Your University ID is not valid',
			validators: {
			notEmpty: {
				message: 'Your University ID is required and can\'t be empty'
			},
			stringLength: {
				min: 8,
				max: 8,
				message: 'Your University ID must be 8 digits long'
			},
			regexp: {
				regexp: /^[0-9]+$/,
				message: 'Your University ID can only consist of numerical digits'
			}
			}
		},
		aboutMe: {
			message: 'Your Qualifications is not valid',
			validators: {
			notEmpty: {
				message: 'Your Qualifications is required and can\'t be empty'
			}
			}
		}
	} /* close fields */		
});


	
}); /* End on ready function */

/*Clears all the fields in the registration form*/
function clearInput() {
	$("#signupForm :input").each( function() {
	   $(this).val('');
	});
}
/*Removes the form and displays a confirmation message*/
function displayConfirmation(){
	var message ="<p>We have sent you an email confirmation.</p> <p> Start seeking available positions by <a href=\"index.php\">signing in</a> with your email address.</p>";
	$('#signupForm').fadeOut(600);
	$('#formBox').html(message).fadeIn();
	
}


