$(document).ready(function () {
	
	$('#first').bind('click',enableRow);
	$('#second').bind('click',enableRow);
	$('#third').bind('click',enableRow);
	$('#fourth').bind('click',enableRow);
    $('#editProfileForm').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
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
			message: 'Passwords dont match'
			 
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
                        regexp: /^[0.0-5]+$/,
                        message: 'Your gpa can only consist of numerical digits'
                    }

                }
            },	    
 
        } /* close fields */
	
	
    });	
	
	
});



function enableRow(){
	
	var row = $(this).attr('id');
	
	row = '.'+row;
	
	$(row).find('input').removeAttr('disabled');
	
	//target all input fields in this row 
	
	
}