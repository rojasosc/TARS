$(function(){

	/* bind components */
	
	$('#selectTerm').bind('change',displayTerm);
	
	showTerm();
	
}); /* End of onload event*/


function displayTerm(){
	
	/* hide the term if one is displayed */
	
	hideTerm();
	
	termHeader = $(this).val(); /* the selected term */
	
	/* AJAX Request for term selected */
	
	
	/* display term */
	
	showTerm();
	
}

function hideTerm(){
	
	$('#termOverview').fadeOut(500);
	
}

function showTerm(){
	var termHeader = $('#selectTerm').val();
	$('.termHeader').text(termHeader);
	$('#termOverview').fadeIn(500);
}
