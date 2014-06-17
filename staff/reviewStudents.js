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
	var url = "reviewProcess.php";
	var data = {
		userID: userID,
		status: decision
	}
	$.post(url,data,function () {});
}