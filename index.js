$(document).ready(function () {
	$('#bugReport').on('submit', function(event) {
		event.preventDefault();
		doAction('sendBugReport', $('#bugReport :input').serializeArray(), './actions.php'
		).done(function (data) {
			if (data.success) {
				showAlert({message: 'Bug report sent'}, $('#alertHolder'), 'success');
				$('#bugmodal').modal('hide');
			} else {
				showError(data.error, $('#bugAlertHolder'));
			}	
		}).fail(function (jqXHR, textStatus, errorMessage) {
			showError({message: errorMessage}, $('#bugAlertHolder'));
		});
	});
});

