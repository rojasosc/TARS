$(document).ready(function () {
	$commentModal = $('#commentModal');
	$commentForm = $('#commentForm');
	var studentID = null;
	var commenterID = null;
	
	$commentForm.submit(function () { return false; });
	$('.comment').click(prepareCommentModal);
	$('#submitCommentButton').click(submitComment);
	$commentModal.bind('hidden.bs.modal',clearCommentForm);
});

function submitComment(){
	var url = $commentForm.attr('action');
	var action = 'newStudentComment';
	var data = {
		/*TODO: Support a subject line. */
		studentID: studentID,
		commenterID: commenterID,
		comment: $("[name='commentText']").val(),
		action: action
	}
	studentID = null;
	commenterID = null;
	$.post(url,data,function () { });
	clearCommentForm();
	$commentModal.modal('hide');
}

function prepareCommentModal(){
	studentID = $(this).data('studentid');
	commenterID = $(this).data('staffid');
	$commentModal.modal('show');
}

function clearCommentForm(){
	$commentForm.trigger("reset");
}
