$(document).ready(function () {
	
	$('#first').bind('click',enableRow);
	$('#second').bind('click',enableRow);
	$('#third').bind('click',enableRow);
	$('#fourth').bind('click',enableRow);
});



function enableRow(){
	
	var row = $(this).attr('id');
	
	row = '.'+row;
	
	$(row).find('input').removeAttr('disabled');
	
	//target all input fields in this row 
	
	
	
	
}