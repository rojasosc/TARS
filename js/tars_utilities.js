window.STUDENT = 1;
window.PROFESSOR = 2;
window.STAFF = 4;
window.ADMIN = 8;
window.actionsUrl = "../actions.php";

$(document).ready(function() {
    if ($(".search-users-form").length) {
        $userSearchForm = $(".search-users-form");
        $userSearchForm.on('submit', function(event) {
            event.preventDefault();
            searchUsers();
        });
        $userSearchForm.on('change', 'input:radio', function() {
            if ($('input[name="userType"]:checked').val() == STUDENT) {
                $('.user-search-table thead').html('<tr><th>First Name</th><th>Last Name</th><th>E-mail</th><th>Class Year</th><th>Profile</th></tr>');
            } else if ($('input[name="userType"]:checked').val() == PROFESSOR) {
                $('.user-search-table thead').html('<tr><th>First Name</th><th>Last Name</th><th>E-mail</th><th>Profile</th></tr>');
            }
            $userSearchForm.trigger('submit');
        });
        $userSearchForm.trigger('submit');
    }


    if ($(".user-search-table").length) {
        $results = $(".user-search-table");
        $results.on('click', '.edit-profile', function() {
            viewUserForm($(this).data("userid"), $(this).data('usertype'));
        });

    }

    if ($(".filter-events-form").length) {
        $filterEventsForm = $(".filter-events-form");
        $filterEventsForm.on('submit', function(event) {
            event.preventDefault();
            filterEvents();
        });
        $filterEventsForm.trigger('submit');
    }

    if ($(".profile-modal").length) {
        $userModal = $(".profile-modal");
        if ($(".edit-profile-form").length) {

            //In case the button is not in a table
            $(".edit-profile").on('click', function() {
                viewUserForm($(this).data("userid"), $(this).data("usertype"));
            });

            $(".edit-profile-form").on('submit', function(event) {
                event.preventDefault();
                updateUserProfile();
            });
        }

        if ($(".profile").length) {
            $userModal = $(".profile-modal");
            $(".profile").click(viewUserProfile);
            if ($(".qualifications").length) {
                $qualifications = $(".qualifications");
                $(".profile").click(injectQualifications);
            }
        }
    }

    if ($(".comment").length) {
        $('.comment').click(prepareCommentModal);
        $('#submitCommentButton').click(submitComment);
    }

    if ($(".password-modal").length) {
        $passwordModal = $(".password-modal");
        $passwordForm = $(".change-password-form");
        $passwordForm.on('submit', function(event) {
            event.preventDefault();
            changeUserPassword();
        });
        $passwordForm.bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                oldPassword: {
                    validators: {
                        notEmpty: {
                            message: 'You must confirm your old password'
                        }
                    }
                },
                newPassword: {
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
                confirmPassword: {
                    validators: {
                        notEmpty: {
                            message: 'Passwords do not match'
                        },
                        identical: {
                            field: 'newPassword',
                            message: 'Passwords do not match'

                        }
                    }
                }
            } /* close fields */
        });
        viewPasswordForm($passwordForm.data("userid"), $passwordForm.data("usertype"));
    }

    if ($(".comments-modal").length) {
        $commentsModal = $(".comments-modal");
        $commentsBlock = $(".comments-block");
        $(".comments").click(viewUserComments);
    }

    if ($(".buildings").length) {
        $buildingsData = $(".buildings");
        $buildingsInput = $("[name='building']");
        $roomsDropdown = $(".rooms");
        $buildingsInput.change(prepareRoomsDropdown);
        prepareBuildingsDropdown();

    }

    if ($(".courses").length) {
        $coursesDropdown = $(".courses");
        $professorsDropdown = $(".professors");
        prepareCoursesDropdown();
        $coursesDropdown.change(prepareProfessorsDropdown);
    }

    if ($(".decision").length) {
        $(".decision").click(submitDecision);
    }

    if ($(".pagination").length) {
        $(".pagination").on('click', 'a', function(event) {
            event.preventDefault();
            if (!$(this).parent().hasClass('active')) {
                data = $pgData;
                data.pgIndex = $(this).data('target');
                data.pgLength = 10; // XXX here is the number of rows per page requested
                data.pgGetTotal = false;
                doAction($pgAction, data).done($pgAjaxDone).fail($pgAjaxFail);
            }
        });
    }

    if ($(".fetch-sections-form").length) {
        $filterSectionsForm = $('.fetch-sections-form');
        $filterSectionsForm.on('submit', function(event) {

            event.preventDefault();
            var input = {
                crn: $("[name='CRNFilter']", $filterSectionsForm).val(),
                course: $("[name='courseFilter']", $filterSectionsForm).val(),
                type: $("[name='typeFilter']", $filterSectionsForm).val(),
                status: $("input[type='radio']:checked", $filterSectionsForm).val()
            };
            doPaginatedAction('fetchSections', input, function(data) {
                    if (data.success) {
                        alert('data success');
                        if (data.pg) {
                            handlePagination(data.pg, $('.pagination'));
                        }
                        if (data.objects) {
                            alert('data objectsd');
                            alert(JSON.stringify(data.objects));
                            if (data.objects.length == 0) {
                                $('thead tr').hide();
                                $('#results').html('<em>No results</em>');
                            } else {
                                $('thead tr').show();
                                var resultHTML = [];
                                for (var key in data.objects) {
                                    var section = data.objects[key];
                                    var courseNum = 'TBD';
                                    var title = 'TBD';
                                    var type = 'TBD';
                                    var crn = 'TBD';
                                    var day = 'TBD';
                                    var startTime = 'TBD';
                                    var endTime = 'TBD'
                                    var building = 'TBD';
                                    var room = 'TBD';
                                    var labTA = 'TBD';
                                    var WSL = 'TBD';
                                    var WSSL = 'TBD';
                                    var lecTA = 'TBD';
                                    var grader = 'TBD';
                                    var instructorEmail = 'TBD';

                                    crn = section.crn;
                                    type = section.type;
                                    if (section.course) {
                                        courseNum = 'CSC' + section.course.number;
                                        title = section.course.title;
                                    }
                                    if (section.instructor) {
                                        //There has got to be a better way than this
                                        var length = section.instructor.length;
                                        instructorEmail = section.instructor[0].email;
                                        if (length > 1) {
                                            instructorEmail += ', ' + section.instructor[1].email;
                                        }
                                    }
                                    if (section.sessions.length > 0) {
                                        day = section.sessions[0].weekdays;
                                        startTime = section.sessions[0].startTime;
                                        endTime = section.sessions[0].endTime;
                                        building = section.sessions[0].building;
                                        room = section.sessions[0].room;
                                    }
                                    var secRow = '<tr>';
                                    secRow += '<td class="courseNum">' + courseNum + '</td>';
                                    secRow += '<td class="type">' + type + '</td>';
                                    secRow += '<td class="crn">' + crn + '</td>';
                                    secRow += '<td class="days">' + day + '</td>';
                                    secRow += '<td class="time">' + startTime + ' - ' + endTime + '</td>';
                                    secRow += '<td class="place">' + building + ' ' + room + '</td>';
                                    secRow += '<td class="labTA">' + labTA + '</td>';
                                    secRow += '<td class="WSL">' + WSL + '</td>';
                                    secRow += '<td class="WSSL">' + WSSL + '</td>';
                                    secRow += '<td class="lecTA">' + lecTA + '</td>';
                                    secRow += '<td class="grader">' + grader + '</td>';
                                    secRow += '<td class="hidden instructor-emails">' + instructorEmail + '</td>';
                                    secRow += '<td class="hidden courseTitle">' + title + '</td>';
                                    secRow += '<td><button data-toggle="modal" data-target="edit-modal" class="btn btn-default edit-section circle"><span class="glyphicon glyphicon-wrench"></span></button></td>';
                                    secRow += '</tr>';
                                    resultHTML.push(secRow);
                                }
                                $('#results').html(resultHTML.join(''));
                            }
                        }
                    } else {
                        showError(data.error, $('#alertHolder'));
                    }
                },
                function(jqXHR, textStatus, errorMessage) {
                    showError({
                        message: errorMessage
                    }, $('#alertholder'));
                });
        });
        $('#fetchSectionsForm').trigger('submit');
    }
    if ($("#application-table").length) {
        $appView = $("#application-table");
        viewApplications();
    }
});

function doPaginatedAction(action, data, ajaxDone, ajaxFail) {
    var sameQuery = true;
    if (typeof $pgData != 'undefined') {
        for (var key in data) {
            if ($pgData[key] != data[key]) {
                sameQuery = false;
            }
        }
    } else {
        sameQuery = false;
    }
    $pgAction = action;
    $pgData = data;
    $pgAjaxDone = ajaxDone;
    $pgAjaxFail = ajaxFail;
    data.pgIndex = 1;
    data.pgLength = 10; // XXX: here is the number of rows per page requested
    data.pgGetTotal = !sameQuery;
    return doAction($pgAction, data).done(ajaxDone).fail(ajaxFail);
}

function handlePagination(pg, listDOM) {
    if (pg.getTotal) {
        $pgTotal = pg.total;
    }
    //console.log(pg);
    var pages = [];
    if ($pgTotal > 1) {
        if (pg.index > 4) {
            pages.push({
                target: 1,
                title: 'First Page',
                text: '&laquo;',
                active: false
            });
        }
        for (var i = Math.max(1, pg.index - 3); i <= Math.min($pgTotal, pg.index + 3); i++) {
            pages.push({
                target: i,
                title: 'Page ' + i.toString(),
                text: i.toString(),
                active: i == pg.index
            });
        }
        if (pg.index + 3 < $pgTotal) {
            pages.push({
                target: $pgTotal,
                title: 'Last Page',
                text: '&raquo;',
                active: false
            });
        }
    }

    var html = '';
    for (var idx in pages) {
        var active = '';
        if (pages[idx].active) {
            active = ' class="active"';
        }
        html += '<li' + active + '><a href="#" title="' + pages[idx].title + '" data-target="' + pages[idx].target + '">' + pages[idx].text + '</a></li>';
    }
    listDOM.html(html);
}

function doAction(action, params, altUrl) {
    var url = actionsUrl;
    if (arguments.length == 3) {
        url = altUrl;
    }
    var request = {
        type: 'POST',
        url: url,
        dataType: 'json'
    };
    if (typeof FormData === 'function' && params instanceof FormData) {
        request.data = params;
        request.url += '?action=' + encodeURIComponent(action);
        request.cache = false;
        request.contentType = false;
        request.processData = false;
    } else if (params instanceof Array) {
        params.push({
            name: 'action',
            value: action
        });
        request.data = params;
    } else {
        params.action = action;
        request.data = params;
    }
    // return the Deferred so that code can .done() and .fail() it
    return $.ajax(request);
}

function showError(errorObj, element) {
    showAlert(errorObj, element, 'danger');
}

function clearAlert(element) {
    element.children().fadeOut('slow');
    // do not call element.empty() here because the fadeout might not be done when
    // another alert appears and we don't want to remove that one
}

var clearError = clearAlert;

function showAlert(alertObj, element, level) {
    if (!('title' in alertObj)) {
        switch (level) {
            case 'success':
                alertObj.title = 'Success';
                break;
            case 'warning':
                alertObj.title = 'Warning';
                break;
            case 'danger':
                alertObj.title = 'An error occured';
                break;
            default:
                alertObj.title = 'Notice';
                level = 'info';
                break;
        }
    }
    element.hide();
    element.html('<div class="alert alert-' + level + '"><strong>' + alertObj.title + '!</strong> ' + alertObj.message + '</div>');
    element.fadeIn('slow');
}

function submitDecision() {
    clearError($('#alertHolder'));
    doAction('setAppStatus', {
        appID: $(this).parent().data('applicationid'),
        decision: $(this).data('decision')
    }).done(function(data) {
        if (data.success) {
            showAlert({
                message: 'Application status updated.'
            }, $('#alertHolder'), 'success');
            location.reload(); // XXX: hides alert that something happened!
        } else {
            showError(data.error, $('#alertHolder'));
        }
    }).fail(function(jqXHR, textStatus, errorMessage) {
        showError({
            message: errorMessage
        }, $('#alertHolder'));
    });
}

function injectQualifications() {
    clearError($('#profileAlertHolder'));
    doAction('fetchApplication', {
        appID: $(this).data('appid')
    }).done(function(data) {
        if (data.success) {
            $qualifications.html(data.object.qualifications);
        } else {
            showError(data.error, $('#profileAlertHolder'));
        }
    }).fail(function(jqXHR, textStatus, errorMessage) {
        showError({
            message: errorMessage
        }, $('#profileAlertHolder'));
    });
}

function viewUserComments() {
    clearError($('#commentsAlertHolder'));
    doAction('fetchComments', {
        userID: $(this).data('userid')
    }).done(function(data) {
        if (data.success) {
            var comments = data.objects;
            for (var i = comments.length - 1; i >= 0; i--) {
                $comment = comments[i];
                $commentsBlock.append("<p class='commentDate'>" + $comment.createTime + "</p><blockquote><p class='commentContent'>" +
                    $comment.comment + "</p><footer>" + $comment.creator.firstName + ' ' + $comment.creator.lastName + "</footer></blockquote></div><!-- End column --></div> <!-- End row --><br>");
            }
            if (!comments.length) {
                $commentsBlock.html("There are no reviews available for this student.");
            }
        } else {
            showError(data.error, $('#commentsAlertHolder'));
        }
    }).fail(function(jqXHR, textStatus, errorMessage) {
        showError({
            message: errorMessage
        }, $('#commentsAlertHolder'));
    });
    $commentsModal.bind("hidden.bs.modal", function() {
        $commentsBlock.html("");
    });
}

function searchUsers() {
    clearError($('#alertHolder'));
    var input = {
        firstName: $("[name='fN']", $userSearchForm).val(),
        lastName: $("[name='lN']", $userSearchForm).val(),
        email: $("[name='emailSearch']", $userSearchForm).val(),
        classYear: $("[name='classYear']", $userSearchForm).val(),
        userType: $("input[type='radio']:checked", $userSearchForm).val()
    };
    doPaginatedAction('searchForUsers', input,
        function(data) {
            if (data.success) {
                if (data.pg) {
                    handlePagination(data.pg, $('.pagination'));
                }
                if (data.objects) {
                    if (data.objects.length === 0) {
                        $('thead tr').hide();
                        $results.find('tbody').html('<em>No results</em>');
                    } else {
                        $('thead tr').show();
                        viewResults(data.objects, $("input[type='radio']:checked", $userSearchForm).val());
                    }
                }
            } else {
                showError(data.error, $('#alertHolder'));
            }
        },
        function(jqXHR, textStatus, errorMessage) {
            showError({
                message: errorMessage
            }, $('#alertHolder'));
        });
}

function filterEvents() {
    clearError($('#alertHolder'));
    var input = {
        userFilter: $("[name='user']", $filterEventsForm).is(':checked'),
        sevCrit: $("[name='sevCrit']", $filterEventsForm).is(':checked'),
        sevError: $("[name='sevError']", $filterEventsForm).is(':checked'),
        sevNotice: $("[name='sevNotice']", $filterEventsForm).is(':checked'),
        sevInfo: $("[name='sevInfo']", $filterEventsForm).is(':checked'),
        sevDebug: $("[name='sevDebug']", $filterEventsForm).is(':checked')
    };
    doPaginatedAction('findEvents', input,
        function(data) {
            if (data.success) {
                if (data.pg) {
                    handlePagination(data.pg, $('.pagination'));
                }
                if (data.objects) {
                    if (data.objects.length === 0) {
                        $('#results thead tr').hide();
                        $('#results tbody').html('<em>No results</em>');
                    } else {
                        $('#results thead tr').show();
                        var output = '';
                        for (var i = 0; i < data.objects.length; i++) {
                            var eventObj = data.objects[i];
                            var icon = 'exclamation-sign';
                            var color = 'black';
                            switch (eventObj.severity) {
                                case 'crit':
                                    icon = 'warning-sign';
                                    color = 'red';
                                    break;
                                case 'error':
                                    icon = 'warning-sign';
                                    color = 'black';
                                    break;
                                case 'notice':
                                    icon = 'info-sign';
                                    color = 'black';
                                    break;
                                case 'info':
                                    icon = 'info-sign';
                                    color = 'blue';
                                    break;
                                case 'debug':
                                    icon = 'question-sign';
                                    color = 'orange';
                                    break;

                            }
                            var tr = $('<tr/>');
                            tr.append($('<td class="hidden"/>').text(eventObj.id));
                            tr.append($('<td/>').text(eventObj.createTime));
                            if (eventObj.creator === null) {
                                tr.append('<td><em>not logged in</em></tr>');
                            } else {
                                tr.append($('<td/>').text(eventObj.creator.firstName + ' ' + eventObj.creator.lastName));
                            }
                            tr.append($('<td/>').text(eventObj.creatorIP));
                            tr.append($('<td style="text-align:left"/>').html('<span class="glyphicon glyphicon-' + icon + '" style="color: ' + color + '" title="' + eventObj.severity + '"></span> ' + $('<span/>').text(eventObj.type).text()));
                            tr.append($('<td/>').text(eventObj.description));
                            tr.append($('<td/>').text(eventObj.objectType + ': ' + eventObj.object));
                            output += tr[0].outerHTML;
                        }
                        $('#results tbody').html(output);
                    }
                }
            } else {
                showError(data.error, $('#alertHolder'));
            }
        },
        function(jqXHR, textStatus, errorMessage) {
            showError({
                message: errorMessage
            }, $('#alertHolder'));
        });
}

function viewApplications() {
    clearError($('#alertHolder'));
    doPaginatedAction('fetchTermApplications', {
            appStatus: 0,
            termID: 1
        },
        function(data) {
            if (data.success) {
                if (data.pg) {
                    handlePagination(data.pg, $(".pagination"));
                }
                if (data.objects) {
                    $appView.find("tbody").html("");
                    var applications = data.objects;
                    for (var key in applications) {
                        var app = applications[key];
                        var creator = app['creator'];
                        var position = app['position'];
                        var section = position['section'];
                        var course = section['course'];
                        var fullName = creator['firstName'] + " " + creator['lastName'];
                        var universityID = creator['universityID'];
                        var email = creator['email'];
                        var type = position['type'].title;
                        var appButton = "<button data-toggle='modal' data-target='#profile-modal' data-appID='" + app['id'] + "' data-usertype='" + creator['type'] + "' data-userid='" + creator['id'] + "' class='btn btn-info circle profile'>" +
                            "<span class='glyphicon glyphicon-file'></span>" +
                            "</button>";
                        var reviewsButton = "<button data-toggle='modal' data-target='#commentsModal' data-userID='" + creator['id'] + "' class='btn btn-info comments'>" +
                            "<span class='glyphicon glyphicon-comment'></span>" +
                            "</button>";
                        var row = "";
                        row += "<tr><td>" +
                            "<div class='dropdown actions'>" +
                            "<a class='dropdown-toggle' type='button' id='actionsMenu' data-toggle='dropdown'>" + fullName +
                            "<span class='caret'></span>" +
                            "</a>" +
                            "<ul class='dropdown-menu' role='menu' id='actionsMenu' aria-labelledby='actionsMenu'>" +
                            "<li role='presentation'><a class='comment' role='menuitem' data-commenterID='2' data-studentID='" + creator['id'] + "' data-toggle='modal' href='#commentModal' tabindex='1'>Review Student</a></li>" +
                            "<li role='presentation'><a data-toggle='modal' role='menuitem' tabindex='-1' data-target='#emailModal'>Send Email</a></li>" +
                            "</ul>" +
                            "</div>";
                        row += "</td><td>" + universityID;
                        row += "</td><td>" + email;
                        row += "</td><td>" + type;
                        row += "</td><td>" + course['department'] + " " + course['number'];
                        row += "</td><td>" + appButton;
                        row += "</td><td>" + reviewsButton;
                        row += "</td></tr>";
                        $appView.find("tbody").append(row);
                    }
                }
                if ($(".profile").length) {
                    $userModal = $(".profile-modal");
                    $(".profile").unbind();
                    $(".profile").click(viewUserProfile);
                    if ($(".qualifications").length) {
                        $qualifications = $(".qualifications");
                        $(".profile").click(injectQualifications);
                    }
                }
                if ($(".comments-modal").length) {
                    $(".comments").unbind();
                    $commentsModal = $(".comments-modal");
                    $commentsBlock = $(".comments-block");
                    $(".comments").click(viewUserComments);
                }
                $(".comment").unbind();
                $("#submitCommentButton").unbind();
                $(".comment").click(prepareCommentModal);
                $('#submitCommentButton').click(submitComment);
            } else {
                showError(data.error, $('#alertHolder'));
            }
        }, function(jqXHR, textStatus, errorMessage) {
            showError({
                message: errorMessage
            }, $('#alertHolder'));
        });
}

function viewResults(users, userType) {
    /* Clear any existing results */
    $results.hide();
    $results.find("tbody").remove();
    /* Associative array of a user */
    /* Render results table */
    var row = new Array();
    var j = -1;
    var size = users.length;
    for (var key = 0; key < size; key++) {
        row[++j] = "<tr><td>";
        row[++j] = users[key].firstName;
        row[++j] = "</td><td>";
        row[++j] = users[key].lastName;
        row[++j] = "</td><td>";
        row[++j] = users[key].email;
        row[++j] = "</td><td>";
        if (users[key].type == 1) {
            row[++j] = users[key].classYear;
            row[++j] = "</td><td>";
        }
        row[++j] = "<button data-toggle='modal' data-target='#profile-modal' class='btn btn-default edit-profile circle' data-userid='" +
            users[key].id + "' data-usertype='" + users[key].type + "'><span class='glyphicon glyphicon-wrench'></span></button>";
        row[++j] = "</td></tr>";
    }

    /* Render the appropriate user profile update forms */
    $results.append(row.join(''));
    $results.show();

}

function viewUserProfile() {
    clearError($('#profileAlertHolder'));
    doAction('fetchUser', {
        userID: $(this).data('userid'),
        userType: $(this).data('usertype')
    }).done(function(data) {
        if (data.success) {
            prepareStudentModal(data.object, $(this).data('usertype'));
        } else {
            showError(data.error, $('#profileAlertHolder'));
        }
    }).fail(function(jqXHR, textStatus, errorMessage) {
        showError({
            message: errorMessage
        }, $('#profileAlertHolder'));
    });
}

function prepareStudentModal(student) {
    $("#studentModalTitle").html(student.firstName + " " + student.lastName);
    $("#studentMajor").html("Major: " + student.major);
    $("#studentGPA").html("GPA: " + student.gpa);
    $("#studentEmail").html("Email: " + student.email);
    $("#studentMobilePhone").html("Mobile Phone: " + student.mobilePhone);
    $("#studentAboutMe").html(student.aboutMe);
    $("#studentClassYear").html("Class Year: " + student.classYear);

}

function viewPasswordForm(userID, userType) {
    //console.log('viewpasswordform');
    //console.log(userID, userType);
    clearError($('#editPasswordAlertHolder'));
    doAction('fetchUser', {
        userID: userID,
        userType: userType
    }).done(function(data) {
        if (data.success) {
            preparePasswordForm(data.object);
        } else {
            showError(data.error, $('#editPasswordAlertHolder'));
        }
    }).fail(function(jqXHR, textStatus, errorMessage) {
        showError({
            message: errorMessage
        }, $('#editPasswordAlertHolder'));
    });
}

function viewUserForm(userID, userType) {
    clearError($('#editProfileAlertHolder'));
    var userType = parseInt(userType, 10);
    $editProfileForm = $("#profileForm" + userType);
    // hide all sub-forms
    $('.edit-profile-form').hide();
    // show the current sub-form
    $editProfileForm.show();
    // set submit button to the current sub-form
    $(".profile-modal button[type=submit]").attr({
        form: $editProfileForm.attr('id')
    });
    doAction('fetchUser', {
        userID: userID,
        userType: userType
    }).done(function(data) {
        if (data.success) {
            prepareUserForm(data.object, userType);
        } else {
            showError(data.error, $('#editProfileAlertHolder'));
        }
    }).fail(function(jqXHR, textStatus, errorMessage) {
        showError({
            message: errorMessage
        }, $('#editProfileAlertHolder'));
    });
}

function preparePasswordForm(user) {
    $("[name='email']", $passwordForm).val(user.email);
}

function prepareUserForm($user, userType) {
    window.$user = $user;
    $("#modalHeader").html($user.firstName + " " + $user.lastName);
    $("[name='firstName']", $editProfileForm).val($user.firstName);
    $("[name='lastName']", $editProfileForm).val($user.lastName);
    $("[name='email']", $editProfileForm).val($user.email);
    switch (userType) {
        case STUDENT:
            $("[name='mobilePhone']", $editProfileForm).val($user.mobilePhone);
            $("[name='classYear']", $editProfileForm).val($user.classYear);
            $("[name='major']", $editProfileForm).val($user.major);
            $("[name='gpa']", $editProfileForm).val($user.gpa);
            $("[name='universityID']", $editProfileForm).val($user.universityID);
            $("[name='aboutMe']", $editProfileForm).val($user.aboutMe);
            break;
        case PROFESSOR:
        case STAFF:
            if ($user.officePhone !== null) {
                $("[name='officePhone']", $editProfileForm).val($user.officePhone);
            }
            if ($user.office !== null) {
                $("[name='building']", $editProfileForm).val($user.building);
                $("[name='room']", $editProfileForm).val($user.room);
            }
            break;
        case ADMIN:
            // I doubt admins have extra properties
            break;
        default:
            break;
    }
    //$userModal.modal("show");
}

function changeUserPassword() {
    clearError($('#editPasswordAlertHolder'));
    clearError($('#alertHolder'));
    doAction('changeUserPassword', {
        oldPassword: $("[name='oldPassword']", $passwordForm).val(),
        newPassword: $("[name='newPassword']", $passwordForm).val(),
        confirmPassword: $("[name='confirmPassword']", $passwordForm).val()
    }).done(function(data) {
        if (data.success) {
            $passwordModal.modal('hide');
            showAlert({
                message: 'Your password has been changed.'
            }, $('#alertHolder'), 'success');
        } else {
            showError(data.error, $('#editPasswordAlertHolder'));
        }
    }).fail(function(jqXHR, textStatus, errorMessage) {
        showError({
            message: errorMessage
        }, $('#editPasswordAlertHolder'));
    });
}

function updateUserProfile() {
    var action = "";
    var input = {};
    switch ($user.type) {
        case STUDENT:
            /* Select the input fields in the context of the update form. */
            input = {
                userID: $user.id,
                firstName: $("[name='firstName']", $editProfileForm).val(),
                lastName: $("[name='lastName']", $editProfileForm).val(),
                mobilePhone: $("[name='mobilePhone']", $editProfileForm).val(),
                classYear: $("[name='classYear']", $editProfileForm).val(),
                major: $("[name='major']", $editProfileForm).val(),
                gpa: $("[name='gpa']", $editProfileForm).val(),
                universityID: $("[name='universityID']", $editProfileForm).val(),
                aboutMe: $("[name='aboutMe']", $editProfileForm).val()
            };
            break;
        case PROFESSOR:
        case STAFF:
            /* Select the input fields in the context of the update form. */
            input = {
                userID: $user.id,
                firstName: $("[name='firstName']", $editProfileForm).val(),
                lastName: $("[name='lastName']", $editProfileForm).val(),
                officePhone: $("[name='officePhone']", $editProfileForm).val(),
                building: $("[name='building']", $editProfileForm).val(),
                room: $("[name='room']", $editProfileForm).val()
            };
            break;
        case ADMIN:
            input = {
                userID: $user.id,
                firstName: $("[name='firstName']", $editProfileForm).val(),
                lastName: $("[name='lastName']", $editProfileForm).val()
            };
            break;
    }
    clearError($('#editProfileAlertHolder'));
    clearError($('#alertHolder'));

    doAction('updateProfile', input).done(function(data) {
        if (data.success) {
            $userModal.modal('hide');
            updateUserProfileCard(data.object, $user.type);
            showAlert({
                message: 'Your profile has been updated.'
            }, $('#alertHolder'), 'success');
        } else {
            showError(data.error, $('#editProfileAlertHolder'));
        }
    }).fail(function(jqXHR, textStatus, errorMessage) {
        showError({
            message: errorMessage
        }, $('#editProfileAlertHolder'));
    });
}

function updateUserProfileCard(data, userType) {
    // iterate through the properties of data
    //console.log(data);
    for (key in data) {
        //console.log(key);
        if (data[key] === null) {
            $('#cur-' + key).text('');
        } else {
            $('#cur-' + key).text(data[key]);
        }
    }
}

function prepareBuildingsDropdown() {
    clearError($('#alertHolder'));
    doAction('fetchBuildings', {}).done(function(data) {
        if (data.success) {
            var buildings = data.objects;
            for (var i = 0; i < buildings.length; i++) {
                $buildingsData.append("<option>" + buildings[i] + "</option>");
            }
            $buildingsInput.trigger("change");
        } else {
            showError(data.error, $('#alertHolder'));
        }
    }).fail(function(jqXHR, textStatus, errorMessage) {
        showError({
            message: errorMessage
        }, $('#alertHolder'));
    });
}

function prepareRoomsDropdown() {
    clearError($('#alertHolder'));
    clearDropdown($roomsDropdown);
    if ($(this).val() !== '') {
        doAction('fetchRooms', {
            building: $(this).val()
        }).done(function(data) {
            if (data.success) {
                var rooms = data.objects;
                for (var i = 0; i < rooms.length; i++) {
                    $roomsDropdown.append('<option>' + rooms[i] + '</option>');
                }
                $roomsDropdown.trigger("change");
            } else {
                showError(data.error, $('#alertHolder'));
            }
        }).fail(function(jqXHR, textStatus, errorMessage) {
            showError({
                message: errorMessage
            }, $('#alertHolder'));
        });
    }
}

function prepareCoursesDropdown() {
    // TODO update with editTermUI
    var action = "fetchCourses";
    var data = {
        action: action
    }
    $.post(actionsUrl, data, function(courses) {
        var courses = eval("(" + courses + ")");
        for (var i = 0; i < courses.length; i++) {
            $coursesDropdown.append("<option>" + courses[i]["courseTitle"] + "</option>");
        }
        $coursesDropdown.trigger("change");
    });

}

function prepareProfessorsDropdown() {
    // TODO update with editTermUI
    var action = "fetchTheProfessors";
    var data = {
        courseTitle: $(this).val(),
        action: action
    }
    $.post(actionsUrl, data, function(professors) {
        clearDropdown($professorsDropdown);
        var professors = eval("(" + professors + ")");
        for (var i = 0; i < professors.length; i++) {
            $professorsDropdown.append("<option>" + professors[i].firstName + " " + professors[i].lastName + "</option>");
        }
        $professorsDropdown.trigger('change');
    });

}

function clearDropdown($dropdown) {
    $dropdown.find("option").remove();

}