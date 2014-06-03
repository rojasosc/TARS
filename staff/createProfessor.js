$(document).ready(function() {
	
$('#createAccountBody').collapse('toggle');
	
  /*Attach a bootstrapValidator to the form*/	
  $('#createAccountForm ').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
	submitHandler: function(validator, form, submitButton) {
	    // Ajax post(url,data,callback function)
	    var url = $('#createAccountForm').attr('action');
	    var data = $('#createAccountForm :input').serializeArray();

	    $.post(url,data,function (info){  
		clearInput(); 
		$('#createAccountBody').collapse('toggle');
		$('#createdProfessors').html(info).fadeIn();
		$('#createAccountBody').collapse('toggle');
		});
        
	},
        fields: {
            firstName: {
                message: 'a first name is not valid',
                validators: {
                    notEmpty: {
                        message: 'a first name is required and cannot be empty'
                    },
                    stringLength: {
                        min: 4,
                        max: 30,
                        message: 'a first name must be between 4 and 30 characters long'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z]+$/,
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
                        min: 4,
                        max: 30,
                        message: 'a last name must be between 4 and 30 characters long'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z]+$/,
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
			message: 'Email addresses don\'t match'
			 
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
                        regexp: /^[a-zA-Z0-9]+$/,
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
                        regexp: /^[a-zA-Z0-9]+$/,
                        message: 'a password can only consist of alphabetical and numerical characters'
                    },
		    identical: {
			field: 'password',
			message: 'Passwords don\'t match'
			 
		    }

                }
            },
            officePhone: {
                message: 'an office phone is not valid',
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
                message: 'a mobile phone is not valid',
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
    
        } /* close fields */
	
	
    });
    
 $('#submitButton').click(function(){
	    // Ajax post(url,data,callback function)
	    var url = $('#createAccountForm').attr('action');
	    var data = $('#createAccountForm :input').serializeArray();
	    $.post(url,data,function (info){  
		clearInput(); 
		$('#createAccountForm ').fadeOut(600);
		$('#formBox').html(info).fadeIn();
	});
});

});

/*Clears all the fields in the registration form*/
function clearInput() {
	$("#createAccountForm :input").each( function() {
	   $(this).val('');
	});
}

/*Prevents a page redirection to the php page.*/
$("#createAccountForm").submit(function(event){
		
  return false;
});

