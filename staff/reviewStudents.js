$(document).ready(function () {
	$('#applyDecisions').bind('click',getDecisions);
	
});

function getDecisions(){
	$students = $('.btn-group');
	$students.each(function () { submitDecisions($(this)); });
	location.reload();
}

function submitDecisions($student){
	var userID = $student.data('userid');
	var decision = $('input:checked',$student).val();
	var action = 'updateStudentStatus';
	var url = "staffCommands.php";
	var data = {
		userID: userID,
		status: decision,
		action: action
	}
	$.post(url,data,function () {});
}