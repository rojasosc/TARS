$(document).ready(function () {
	var studentID = null;
	var commenterID = null;
	$commentModal = $('#commentModal');
	$commentForm = $('#commentForm');
	$commentForm.submit(function () { return false; });
	$('.comment').click(prepareCommentModal);
	$('#submitCommentButton').click(submitComment);
	$commentModal.bind('hidden.bs.modal',clearCommentForm);
});

function submitComment(){
	doAction('createComment', {
		studentID: studentID,
		comment: $("[name='commentText']").val(),
		/*TODO: Support a subject line? */
	}).done(function (data) {
		if (data.success) {
			clearCommentForm();
			$commentModal.modal('hide');
			showAlert({message: 'Comment created.'}, $('#alertHolder'), 'success');
		} else {
			showError(data.error, $('#createCommentAlertHolder'));
		}
	}).fail(function (jqXHR, textStatus, errorMessage) {
		showError({message: errorMessage}, $('#createCommentAlertHolder'));
	});
}

function prepareCommentModal(){
	studentID = $(this).data('studentid');
	commenterID = $(this).data('commenterid');
	$commentModal.modal('show');
}

function clearCommentForm(){
	$commentForm.trigger("reset");
}
