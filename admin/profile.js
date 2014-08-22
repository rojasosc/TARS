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
            }

        } /* close fields */


    });


});



function enableRow(){

    var row = $(this).attr('id');

    row = '.'+row;

    $(row).find('input').removeAttr('disabled');

    //target all input fields in this row


}
