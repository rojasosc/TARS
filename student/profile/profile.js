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
            var url = $('#profile').attr('action');
            var data = $('#profile :input').serializeArray();
            $.post(url, data, function(data) {
                //TODO: Replace alert mechanism with Bootstrap Alert
                alert('Your profile has been successfully updated!');
				window.location.replace("profile.php");
            });
        },
        fields: {
            firstName: {
                message: 'Invalid first name',
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
                        message: 'Your first name can only consist of English alphabet letters'
                    }
                }
            },
            lastName: {
                message: 'Invalid last name',
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
                        message: 'Your last name can only consist of English alphabet letters'
                    }
                }
            },
            mobilePhone: {
                message: 'Invalid phone number',
                validators: {
                    notEmpty: {
                        message: 'Your phone number is required and cannot be empty'
                    },
                    stringLength: {
                        min: 10,
                        max: 11,
                        message: 'Your phone number must include at least the area code in addition to the 7 standard digits'
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'Your phone number can only contain numbers'
                    }
                }
            },
            classYear: {
                message: 'Invalid class year',
                validators: {
                    notEmpty: {
                        message: 'Your class year is required and cannot be empty'
                    },
                    stringLength: {
                        min: 4,
                        max: 4,
                        message: 'Your class year must be exactly 4 digits'
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'Your class year can contain only numbers'
                    }
                }
            },
            gpa: {
                message: 'Invalid GPA',
                validators: {
                    notEmpty: {
                        message: 'Your GPA is required and cannot be empty'
                    },
                    stringLength: {
                        min: 5,
                        max: 5,
                        message: 'Your GPA must have the following format: \'A.BCD\''
                    },
                    regexp: {
                        regexp: /^[0-4][.][0-9]{3}$/,
                        message: 'Your GPA must be a valid decimal number with 3 digits of precision'
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
