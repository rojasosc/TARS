$(document).ready(function () {
	
	$('#emailExists').hide();
	$('#emailExistsC').hide();
	
	
	/* listens for any change in the email fields */
	$('#email').bind('keyup',emailExists);
	$('#emailConfirm').bind('keyup',emailExistsC);
	$('#email').bind('input',emailExists);
	$('#emailConfirm').bind('input',emailExistsC);
	$('#email').bind('onpaste',emailExists);
	$('#emailConfirm').bind('onpaste',emailExistsC);
	$('#email').bind('oninput',emailExists);
	$('#emailConfirm').bind('oninput',emailExistsC);
	
	
	/*Attach a bootstrapValidator to the form*/	
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
		$.post(url,data,function (info){ 
			clearInput(); 		//Clear all fields.
			displayConfirmation();  //Remove the form and display a confirmation message. 
			});
		},
		fields: {
		firstName: {
			message: 'Your first name is not valid',
			validators: {
			notEmpty: {
				message: 'Your first name is required and cannot be empty'
			},
			stringLength: {
				min: 4,
				max: 30,
				message: 'Your first name must be between 4 and 30 characters long'
			},
			regexp: {
				regexp: /^[a-zA-Z]+$/,
				message: 'Your first name can only consist of alphabetical characters'
			}
			}
		},
		lastName: {
			message: 'Your last name is not valid',
			validators: {
			notEmpty: {
				message: 'Your last name is required and cannot be empty'
			},
			stringLength: {
				min: 4,
				max: 30,
				message: 'Your last name must be between 4 and 30 characters long'
			},
			regexp: {
				regexp: /^[a-zA-Z]+$/,
				message: 'Your last name can only consist of alphabetical characters'
			}
			}
		},	    
		email: {
			validators: {
			notEmpty: {
				message: 'Your email is required and cannot be empty'
			},
			emailAddress: {
				message: 'Your input is not a valid email address'
			}

			

			}
		
		},
		emailConfirm: {
			validators: {
			notEmpty: {
				message: 'An email confirmation is required and cannot be empty'
			},
			emailAddress: {
				message: 'Your input is not a valid email address'
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
				message: 'Your password is required and cannot be empty'
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
				message: 'Your password is required and cannot be empty'
			},
			stringLength: {
				min: 6,
				max: 20,
				message: 'Your password must be between 6 and 20 characters long'
			},
			regexp: {
				regexp: /^[a-zA-Z0-9]+$/,
				message: 'Your password can only consist of alphabetical and numerical characters'
			},
			identical: {
				field: 'password',
				message: 'Passwords don\'t match'
				
			}

			}
		},
		homePhone: {
			message: 'Your home phone is not valid',
			validators: {
			notEmpty: {
				message: 'Your home phone is required and cannot be empty'
			},
			stringLength: {
				min: 10,
				max: 15,
				message: 'Your home phone must be between 10 and 15 characters long'
			},
			regexp: {
				regexp: /^[0-9]+$/,
				message: 'Your home phone can only consist of numerical digits'
			}
			}
		},	 
		mobilePhone: {
			message: 'Your mobile phone is not valid',
			validators: {
			notEmpty: {
				message: 'Your mobile phone is required and cannot be empty'
			},
			stringLength: {
				min: 10,
				max: 15,
				message: 'Your mobile phone must be between 10 and 15 characters long'
			},
			regexp: {
				regexp: /^[0-9]+$/,
				message: 'Your mobile phone can only consist of numerical digits'
			}
			}
		},	  
		gpa: {
			message: 'Your gpa is not valid',
			validators: {
			notEmpty: {
				message: 'Your gpa is required and cannot be empty'
			},
			stringLength: {
				min: 3,
				max: 6,
				message: 'Your gpa must be a decimal between 2 and 5 digits long'
			},
			regexp: {
				regexp: /^\d+(.\d+){0,1}$/,
				message: 'Your gpa can only consist of numerical digits'
			}

			}
		}	    
	
		} /* close fields */
		
		
	});



	/*Prevents a page redirection to the php page.*/
	$("#signupForm").submit(function(event){
			
		return false;
	});
	
	

}); /* End on ready function */

/* Checks to see if the email is already in use and marks the field invalid */
function emailExists(){
			
			var url = "emailExists.php";
			var email = $(this).val();
			var data = {
				
				'email': email
			}
			
			$.post(url,data,function(info){
				
				if(info == "true"){
					
					$('#signupForm').data('bootstrapValidator').updateStatus('email', 'INVALID');
					
					$('#emailExists').show();
				}else{
					
					$('#emailExists').hide();
					validate('email');
				}
				
			});
			
	}
	
function emailExistsC(){
		
		var url = "emailExists.php";
		var email = $(this).val();
		
		var data = {
			
			'email': email
		}
		
		$.post(url,data,function(info){
			
			if(info == "true"){
				
				$('#signupForm').data('bootstrapValidator').updateStatus('emailConfirm', 'INVALID');
				$('#emailExistsC').show();

			}else{
				
				$('#emailExistsC').hide();
				validate('emailConfirm');
			}
			
		});
		
	}

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


