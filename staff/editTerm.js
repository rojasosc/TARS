$(document).ready(function () {
	$term = $('#selectTerm');
	$updateCourseContainer = $('#updateCourseContainer');
	$course = $('#selectCourse');
	$professor = $('#selectProfessor');
		
	/* bind components */	
	$term.bind('change',displayTerm);
	$course.bind('change',showCourse);
	$course.bind('change',showProfessors);
	$professor.bind('change',showCourse);
	
	/* prevent a page redirection */
	$('#updateCourseForm').submit(function (){ return false; });
	$('#newCourseForm').submit(function (){ return false; });

	$('#updateCourseButton').bind('click',updateCourse);
	$('#newCourseButton').bind('click',newCourse);
	
	showTerm();
	
}); /* End of onload event*/


/* Makes an AJAX POST request to update a course */
function updateCourse(){
	
	
	
}
/* Makes an AJAX POST request to create a course */
function newCourse(){
	
	
	
} 

function getCourses(){
	
}

function showCourses(){
	
}

function getProfessors(){
	
}

function showProfessors(){
	
}

function showCourse(){
	
	/*hide the current course form */
	
	$updateCourseContainer.fadeOut(200);
	
	/*obtain the course via ajax get request */
	
	
	$updateCourseContainer.fadeIn(200);
	
}



function showProfessors(){
	
	/* remove current professor drop table */
	$professor.prev().hide();
	$professor.hide();
	
	//hideCourseForm(); /* in case a course form was already opened */
	
	/* show the professors once the course has been selected */
	
	/* AJAX get request to retrieve professors who teach this course */
	
	
	/*build options based on the results obtained */
	$professor.prev().show(200);
	$professor.show(300); /* display professors retrieved from ajax get request */
	
	
	
}

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
	var termHeader = $term.val();
	$('.termHeader').text(termHeader);
	$('#termOverview').fadeIn(500);
}
