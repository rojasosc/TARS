$(document).ready(function() {
    //Attach the boostrap validator js to the form
    $('#profile').bootstrapValidator({
        message: 'This value is invalid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        submitHandler: function(validator, form, submitButton) {
            doAction('updateProfile', $('#profile :input').serializeArray()
            ).done(function (data) {
                if (data.success) {
                    showAlert({message: 'Your profile has been updated.'}, $('#alertHolder'), 'success');
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
        } //END fields
    });
    //Prevent page redirection
    $('#profile').submit(function(event) {
        return false;
    });
});
