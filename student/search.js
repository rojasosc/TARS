$(document).ready(function() {
    //Helper Variables
    var positionID;
    var studentID;
    var compensation;
    var qualifications;
    var url;
    var currPos;
    //Form HTML to be saved
    var appModalBody = $('#applyModal .modal-body');
    var appModalFooter = $('#applyModal .modal-footer');
    var appFormHTML = appModalBody.html();
    var appFormButtons = appModalFooter.html();

    //Get the positionID of the position student is applying to
    $('#results').on('click', '.applyButton', function() {
        currPos = $(this).closest('tr');
        positionID = currPos.find('.positionID').text();
        var posType = currPos.find('.posType').text();
        var course = currPos.find('.courseNum').text() + ': ' + currPos.find('.courseTitle').text();
        var prof = currPos.find('.instructor').text();
        var place = currPos.find('.place').text();
        var time = currPos.find('.days').text() + " " + currPos.find('.time').text();

        $('#jobDetails').html('<div class="row"><div class="col-xs-10 col-xs-offset-1"><h2>Position Details</h2></div></div><div class="row"><div class="col-xs-10 col-xs-offset-1"><p>Type: ' + posType + '</p></div></div><div class="row"><div class="col-xs-10 col-xs-offset-1"><p>Course: ' + course + '</p></div></div><div class="row"><div class="col-xs-10 col-xs-offset-1"><p>Instructor: ' + prof + '</p></div></div><div class="row"><div class="col-xs-10 col-xs-offset-1"><p>Place: ' + place + '</p></div></div><div class="row"><div class="col-xs-10 col-xs-offset-1"><p>Time: ' + time + '</p></div></div>');
    });

    //Submit a post request to apply.php
    $('#applyModal').on('submit', '#application', function(event) {
        event.preventDefault();
        doAction('apply', {
            positionID: positionID,
            compensation: $('#compensation').val(),
            qualifications: $('#qualifications').val()
        }).done(function(data) {
            if (data.success) {
                appModalBody.html('<p>Thank you for applying for this position!<br/>We hope to be able to get back to you soon with our decision.</p>');
                appModalFooter.html('<button type="button" class="btn btn-success" data-dismiss="modal" id="appOK">OK</button>');
                appBtn = currPos.find('.applyButton');
                appBtn.attr('disabled', 'disabled');
                appBtn.text('Applied');
            } else {
                showError(data.error, $('#appAlertHolder'));
            }
        }).fail(function(jqXHR, textStatus, errorMessage) {
            showError({
                message: errorMessage
            }, $('#appAlertHolder'));
        });
    });

    //Restore form HTML when modal is closed
    $('#applyModal').on('hidden.bs.modal', function(event) {
        appModalBody.html(appFormHTML);
        appModalFooter.html(appFormButtons);
    });

    $('#searchForm').on('submit', function(event) {
        event.preventDefault();
        var data = {
            q: $('#q').val(),
            termID: $('#term').val(),
            typeID: $('#type').val()
        };
        doPaginatedAction('findPositions', data, function(data) {
            if (data.success) {
                if (data.pg) {
                    handlePagination(data.pg, $('.pagination'));
                }

                if (data.objects) {
                    if (data.objects.length === 0) {
                        $('thead tr').hide();
                        $('#results').html('<em>No results</em>');
                    } else {
                        $('thead tr').show();
                        var html = [];
                        for (var key in data.objects) {
                            var position = data.objects[key];
                            var courseNum = position.section.course.department + ' ' +
                                    position.section.course.number;
                            var instructors = 'TBD';
                            if (position.section.instructors.length > 0) {
                                instructors = position.section.instructors.map(function(i) {
                                    return i.filName;
                                }).join(', ');
                            }
                            var sessionD = 'TBD';
                            var sessionT = 'TBD';
                            var sessionP = 'TBD';
                            if (position.section.sessions.length > 0) {
                                sessionD = position.section.sessions[0].weekdays;
                                sessionT = position.section.sessions[0].startTime + ' - ' + position.section.sessions[0].endTime;
                                sessionP = position.section.sessions[0].building + ' ' + position.section.sessions[0].room;
                            }

                            var posRow = $('<tr/>');
                            posRow.append($('<td class="hidden positionID"/>').text(position.id));
                            posRow.append($('<td class="courseNum"/>').text(courseNum));
                            posRow.append($('<td class="hidden-xs hidden-sm courseTitle"/>').text(position.section.course.title));
                            posRow.append($('<td class="instructor"/>').text(instructors));
                            posRow.append($('<td class="posType"/>').text(position.type.title));
                            posRow.append($('<td class="days"/>').text(sessionD));
                            posRow.append($('<td class="time"/>').text(sessionT));
                            posRow.append($('<td class="place"/>').text(sessionP));
                            if (position.disableApplyText === '') {
                                posRow.append($('<td/>').html('<button class="btn btn-default applyButton" data-toggle="modal" data-target="#applyModal"><span class="glyphicon glyphicon-pencil"></span> Apply</button>'));
                            } else {
                                posRow.append($('<td/>').html($('<button class="btn btn-default" disabled="disabled"/>').text(position.disableApplyText)[0].outerHTML));
                            }
                            html.push(posRow[0].outerHTML);
                        }
                        $('#results').html(html.join(''));
                    }
                }
            } else {
                showError(data.error, $('#alertHolder'));
            }
        }, function(jqXHR, textStatus, errorMessage) {
            showError({
                message: errorMessage
            }, $('#alertHolder'));
        });
    });
    $('#searchForm').trigger('submit');
});

