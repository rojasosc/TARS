$(document).ready(function() {	
	$resultTable = $('#resultTable');
	$noResults = $('#emptyResult');
	$searchForm = $('#searchUsersForm');
	$updateForm = $('#updateForm');
	$profileModal = $('#editProfileModal');
	
	preventRedirection();
	
	
	$noResults.hide();
	$('#searchType').hide();
	
	/* Bind buttons */
	$('#searchButton').click(findUsers);
	$('#updateButton').click(updateUser);

	/*TODO: Keep track of the users currently being displayed to avoid 
	 *duplicate rows. As of now the table is recreated after every search.
	 It would be much more user friendly to implement a filtering mechanism
	 for the current rows*/	
	
	
});

function preventRedirection(){	
	$searchForm.submit(function(){ return false; });	
	$updateForm.submit(function(){ return false; });
	
}

function findUsers(){
	var email = $('#emailSearch').val();
	var url = $searchForm.attr('action');
	var data = $('#searchUsersForm :input').serializeArray();

	/* AJAX POST request to obtain results */	
	$.post(url,data,function (users){ displayResults(users); });
		
}

function updateUser(){
	var url = $updateForm.attr('action');
	
	/* Select the input fields in the context of the update form. */
	/* TODO: It would be much better if we could use serializeArray() 
	 * to create this associative array, but the fields come back undefined */
	var data = {
		'firstName': $("[name='firstName']",$updateForm).val(),
		'lastName': $("[name='lastName']",$updateForm).val(),
		'email': $("[name='email']",$updateForm).val(),
		'mobilePhone': $("[name='mobilePhone']",$updateForm).val(),
		'classYear': $("[name='classYear']",$updateForm).val(),
		'major': $("[name='major']",$updateForm).val(),
		'gpa': $("[name='gpa']",$updateForm).val(),
		'aboutMe': $("[name='aboutMe']",$updateForm).val()
	}
	
	/* AJAX POST request to obtain results */	
	$.post(url,data,function (info){ });

	/*TODO: Obtain a confirmation from the PHP script on success/failure and 
	 * notify the user */
	$profileModal.modal('hide');
}

function displayResults(users){
	/* Clear any existing results */
	$('#resultTable').hide();
	$('#resultTable').find('tbody').remove();
	/* Associative array of a user */
	var students = eval('(' + users + ')');
	/* Render results table */
	var row = new Array(), j = -1;
	var size = students.length; 
	for (var key = 0;key<size; key++){
		row[++j] ='<tr><td>';
		row[++j] = students[key]["firstName"];
		row[++j] = '</td><td>';
		row[++j] = students[key]["lastName"];
		row[++j] = '</td><td>';
		row[++j] = students[key]["email"];
		row[++j] = '</td><td>';
		row[++j] = '<button data-toggle="modal" data-target="#editProfileModal" class="btn btn-default edit" data-id="'+students[key]["userID"]+'"><span class="glyphicon glyphicon-wrench"></span> Profile</button>'
		row[++j] = '</td></tr>';
		
	}
	
	/* Render the appropriate user profile update forms */
	$('#resultTable').append(row.join(''));
	$resultTable.show();
	$('.edit').click(displayUpdateForm);
}

function displayUpdateForm(){
	var userID = $(this).data('id');
	var url = "fetchStudent.php";
	var data = {
		'userID': userID
	}
	/* Submit a POST request */
	$.post(url,data,function (user){ fillUpdateForm(user); });
	
}

function fillUpdateForm(user){
	/* Associative array of a user */
	var student = eval('(' + user + ')');
	$('#modalHeader').html(student['firstName']+" "+student['lastName']);
	
	/* Select the input fields in the context of the update form. */
	$("[name='firstName']",$updateForm).val(student['firstName']);
	$("[name='lastName']",$updateForm).val(student['lastName']);
	$("[name='email']",$updateForm).val(student['email']);
	$("[name='mobilePhone']",$updateForm).val(student['mobilePhone']);
	$("[name='classYear']",$updateForm).val(student['classYear']);
	$("[name='major']",$updateForm).val(student['major']);
	$("[name='gpa']",$updateForm).val(student['gpa']);
	$("[name='aboutMe']",$updateForm).val(student['aboutMe']);
	
}