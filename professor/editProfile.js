$(document).ready( function () {
      $editModal = $('#editProfileModal');
      $updateForm = $('#updateForm');
      $buildingSelect = $('.buildings');
      $roomSelect = $('.rooms');
      $('#editProfileButton').bind('click',displayEditForm);
      $updateForm.submit(function () { return false; });
      $buildingSelect.bind('change',getRooms);
      $('#updateButton').bind('click',updateProile);
      getBuildings();

});
    
/* Functions */

function displayEditForm(){
  /* Get the current values */
 	var userID = $(this).data('userid');
	var url = "professorCommands.php";
	var action = 'fetchProfessor';
	var data = {
		userID: userID,
		action: action
	}
	/* Submit a POST request */
	$.post(url,data,function (user){ fillUpdateForm(user); });
	$editModal.modal('show');
}

function fillUpdateForm(user){
	/* Associative array of a user */
	var professor = eval('(' + user + ')');
	$('#modalHeader').html(professor['firstName']+" "+professor['lastName']);
	
	/* Select the input fields in the context of the update form. */
	$("[name='firstName']",$updateForm).val(professor['firstName']);
	$("[name='lastName']",$updateForm).val(professor['lastName']);
	$("[name='email']",$updateForm).val(professor['email']);
	$("[name='officePhone']",$updateForm).val(professor['officePhone']);
	$("building",$updateForm).val(professor['building']);
	$("room",$updateForm).val(professor['room']);
}

function updateProile(){
	var url = $updateForm.attr('action');
	var action = 'updateProfessorProfile';
	
	/* Select the input fields in the context of the update form. */
	/* TODO: It would be much better if we could use serializeArray() 
	 * to create this associative array, but the fields come back undefined */
	var data = {
		'firstName': $("[name='firstName']",$updateForm).val(),
		'lastName': $("[name='lastName']",$updateForm).val(),
		'email': $("[name='email']",$updateForm).val(),
		'officePhone': $("[name='officePhone']",$updateForm).val(),
		'building': $("[name='building']",$updateForm).val(),
		'room': $("[name='room']",$updateForm).val(),
		'action': action
	}
	/* AJAX POST request to obtain results */	
	$.post(url,data,function (){ });
	
	/*TODO: Obtain a confirmation from the PHP script on success/failure and 
	 * notify the user */  
	$editModal.modal('hide');
}

/* Dropdown list handlers */
function getBuildings(){
	var url = 'professorCommands.php';
	var action = 'fetchBuildings';
	var data = {
		action: action
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
	var url = 'professorCommands.php';
	var action = 'fetchTheRooms';
	var data = {
		building: $(this).val(),
		action: action
	}
	removeRooms();
	$.post(url,data,function (rooms) { showRooms(rooms); } );
}

function showRooms(rooms){
	var rooms = eval('(' + rooms + ')');
	for(var i = 0; i < rooms.length; i++){
		$roomSelect.append("<option>" + rooms[i] + "</option>");
	}
	
}

function removeRooms(){
	$roomSelect.find('option').remove();
}
