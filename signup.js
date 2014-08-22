$(document).ready(function () {
    //$('.selectpicker').selectpicker();
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
            doAction('signup', $('#signupForm :input').serializeArray(), './actions.php'
            ).done(function (data) {
                if (data.success) {
                    //Remove the form and display a confirmation.
                    displayConfirmation();
                } else {
                    showError(data.error, $('#alertHolder'));
                }
            }).fail(function (jqXHR, textStatus, errorMessage) {
                showError({message: errorMessage}, $('#alertHolder'));
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
                        min: 1,
                        message: 'Your first name must have at least one character'
                    },
                    regexp: {
                        regexp: /^[\D\s]+$/,
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
                        min: 1,
                        message: 'Your last name must have at least one character'
                    },
                    regexp: {
                        regexp: /^[\D\s]+$/,
                        message: 'Your last name can only consist of alphabetical characters'
                    }
                }
            },
            email: {
                message: 'Your email is not valid',
                validators: {
                    notEmpty: {
                        message: 'Your email is required and cannot be empty'
                    },
                    emailAddress: {
                        message: 'Your input is not a valid email address'
                    },
                    remote: {
                        message: 'This email is already in use',
                        url: 'actions.php?action=emailAvailable'
                    }

                }
            },
            emailConfirm: {
                validators: {
                    notEmpty: {
                        message: 'You must retype your email address'
                    },
                    identical: {
                        field: 'email',
                        message: 'You must retype your email address'
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
                    }
                }
            },
            passwordConfirm: {
                validators: {
                    notEmpty: {
                        message: 'Passwords do not match'
                    },
                    identical: {
                        field: 'password',
                        message: 'Passwords do not match'
                    }

                }
            },
            mobilePhone: {
                message: 'Your phone number is not valid',
                validators: {
                    notEmpty: {
                        message: 'Your phone number is required and cannot be empty'
                    },
                    phone: {
                        country: 'US',
                        message: 'Your phone number must be valid and include the area code'
                    }
                }
            },
            gpa: {
                message: 'Your GPA is not valid',
                validators: {
                    notEmpty: {
                        message: 'Your GPA is required and cannot be empty'
                    },
                    between: {
                        min: 0,
                        max: 4,
                        inclusive: false,
                        message: 'Your GPA must be a decimal value from 0.000 to 4.000'
                    },
                    stringLength: {
                        min: 1,
                        max: 5,
                        message: 'Your GPA will not be stored so precisely'
                    }

                }
            },
            universityID: {
                message: 'Your University ID is not valid',
                validators: {
                    notEmpty: {
                        message: 'Your University ID is required and cannot be empty'
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
                message: 'Your qualifications field is not valid',
                validators: {
                    notEmpty: {
                        message: 'Your qualifications are required and cannot be empty'
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
    $('#signupForm').fadeOut('slow', function () {
        $('#formBox').html(message).fadeIn('slow');
    });

}


