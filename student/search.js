$(document).ready(function() {
	$('button').on('click', function() {
		var posID = $(this).closest('tr').first().text();
		var stuID = '<?php echo $student['studentID']; ?>';
		$.post("search_process.php", {positionID : posID, studentID : stuID, compensation : 'paid'});
	});
});