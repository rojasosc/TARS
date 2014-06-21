$(document).ready(function () {
	$buildingSelect = $('.buildings');
	$roomSelect = $('.rooms');
	$courseSelect = $('.courses');
	$professorSelect = $('.professors');
	$profSelect = $('.all-professors');
	
	getBuildings();
	getCourses();
	getProfs();
	
	$buildingSelect.bind('change',getRooms);
	$courseSelect.bind('change',getProfessors);
	
});

function getProfs(){
	var url = 'fetchAllProfessors.php';
	var data = {
		profsd: url
	}
	$.post(url,data, function (profs){ showProfs(profs); });
}

function showProfs(profs){
	var profs = eval('(' + profs + ')');
	for(var i = 0; i < profs.length; i++){
		$profSelect.append("<option>" +profs[i]['firstName'] + " " + profs[i]['lastName']+"</option>");
	}
}

function getCourses(){
	var url = 'fetchAllCourses.php';
	var data = {
		courses: url
	}
	$.post(url,data, function (courses) { showCourses(courses); } );
}

function showCourses(courses){	
	var courses = eval('(' + courses + ')');
	for (var i = 0; i < courses.length; i++){
		$courseSelect.append("<option>" + courses[i]['courseTitle'] + "</option>");
	}
	$courseSelect.trigger('change');
	
}

function getProfessors(){
	var url = 'fetchCourseProfessors.php';
	var data = {
		courseTitle: $(this).val()
	}	
	$.post(url,data,function (professors) { showProfessors(professors); } );
	
}

function showProfessors(professors){
	removeProfessors();
	var professors = eval('(' + professors + ')');
	for(var i = 0; i < professors.length; i++){
		$professorSelect.append("<option>" +professors[i]['firstName'] + " " + professors[i]['lastName']+"</option>");
	}
}

function removeProfessors(){
	$professorSelect.find('option').remove();
	
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
		$roomSelect.append("<option>" + rooms[i] + "</option>");
	}
	
}

function removeRooms(){
	$roomSelect.find('option').remove();
}