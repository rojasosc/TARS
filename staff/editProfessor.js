$(document).ready(function() {	
	$resultTable = $('#resultTable');
	$noResults = $('#emptyResult');
	$searchForm = $('#searchUsersForm');
	$updateForm = $('#updateForm');
	$profileModal = $('#editProfileModal');
	$buildingSelect = $('#buildings');
	$roomSelect = $('#rooms');
	
	
	preventRedirection();
	
	$resultTable.hide();
	$noResults.hide();
	$('#searchType').hide();
	
	/* Bind buttons */
	$('#searchButton').click(findUsers);
	$('#updateButton').click(updateUser);
	
	$buildingSelect.bind('change',getRooms);

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
		'officePhone': $("[name='officePhone']",$updateForm).val(),
		'building': $("[name='building']",$updateForm).val(),
		'room': $("[name='room']",$updateForm).val()
	}
	/* AJAX POST request to obtain results */	
	$.post(url,data,function (){ });

	/*TODO: Obtain a confirmation from the PHP script on success/failure and 
	 * notify the user */
	$profileModal.modal('hide');
}

function displayResults(users){
	/* Clear any existing results */
	$('#resultTable').hide();
	$('#resultTable').html('');
	/* Associative array of a user */
	var professors = eval('(' + users + ')');
	/* Render results table */
	var row = new Array(), j = -1;
	var size = professors.length; 
	for (var key = 0;key<size; key++){
		row[++j] ='<tr><td>';
		row[++j] = professors[key]["firstName"];
		row[++j] = '</td><td>';
		row[++j] = professors[key]["lastName"];
		row[++j] = '</td><td>';
		row[++j] = professors[key]["email"];
		row[++j] = '</td><td>';
		row[++j] = '<button data-toggle="modal" data-target="#editProfileModal" class="btn btn-default edit" data-id="'+professors[key]["userID"]+'"><span class="glyphicon glyphicon-wrench"></span> Profile</button>'
		row[++j] = '</td></tr>';
		
	}
	
	/* Render the appropriate user profile update forms */
	$('#resultTable').append(row.join(''));
	$resultTable.show();
	$('.edit').click(displayUpdateForm);
}

function displayUpdateForm(){
	var userID = $(this).data('id');
	var url = "fetchProfessor.php";
	var data = {
		'userID': userID
	}
	/* Submit a POST request */
	$.post(url,data,function (user){ fillUpdateForm(user); });
	getBuildings();
}

function fillUpdateForm(user){
	/* Associative array of a user */
	var professor = eval('(' + user + ')');
	$('#modalHeader').html(professor['firstName']+" "+professor['lastName']);
	
	/* Select the input fields in the context of the update form. */
	$("[name='firstName']",$updateForm).val(professor['firstName']);
	$("[name='lastName']",$updateForm).val(professor['lastName']);
	$("[name='email']",$updateForm).val(professor['email']);
	$("[name='mobilePhone']",$updateForm).val(professor['mobilePhone']);
	$("[name='officePhone']",$updateForm).val(professor['officePhone']);
	$("[name='building']",$updateForm).val(professor['building']);
	$("[name='room']",$updateForm).val(professor['room']);
}

function getBuildings(){
	var url = 'fetchBuildings.php';
	var data = {
		building: url
	}
	$.post(url,data,function (buildings) { showBuildings(buildings); } );
}

function showBuildings(buildings){
	var buildings = eval('(' + buildings + ')');
	for(var i = 0; i < buildings.length; i++){
		$buildingSelect.append("<option>" + buildings[i]['building'] + "</option>");;
	}
	$buildingSelect.trigger('change');
}

function getRooms(){
	var url = 'fetchRooms.php';
	var data = {
		building: $(this).val()
	}
	removeRooms();
	$.post(url,data,function (rooms) { showRooms(rooms); } );
}

function showRooms(rooms){
	var rooms = eval('(' + rooms + ')');
	for(var i = 0; i < rooms.length; i++){
		addRoom(rooms[i]);
	}
	
}

function removeRooms(){
	$roomSelect.find('option').remove();
}

function addRoom(room){
	$roomSelect.append("<option>" + room + "</option>");
}