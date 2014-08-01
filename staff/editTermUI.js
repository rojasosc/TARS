$(document).ready(function() {

    $('.coursePanel').on('hidden.bs.collapse', function() {
        var sectionTitle = $(this).find('h2');
        var sectionTitleHTML = sectionTitle.html();
		//TODO: Somehow use .data('sectionType');
		var sectionForm = $(this).find('form');
		var sectionType = sectionForm.attr('data-sectionType');
		var sectionCRN = sectionForm.find('.CRN').val();
		var sectionDays = sectionForm.find('.day').val();
		var sectionStartTime = sectionForm.find('.startTime').val();
		var sectionEndTime = sectionForm.find('.endTime').val();
		var sectionBuilding = sectionForm.find('.building').val();
		var sectionRoom = sectionForm.find('.room').val();
		var sectionCourseNumber = sectionForm.find('.courseNum').val();
		var sectionLabTACount = sectionForm.find('.labTACount').val();
		var sectionWSTACount = sectionForm.find('.wsTACount').val();
		var sectionSLTACount = sectionForm.find('.slTACount').val();
		var sectionLecTACount = sectionForm.find('.lecTACount').val();
		var sectionGraderCount = sectionForm.find('.graderCount').val();
		//FIND A BETTER WAY TO DISPLAY THIS
		sectionTitle.html(sectionCourseNumber + ' ' + sectionType + ' | ' + sectionCRN + ' | ' + sectionDays + ' | ' + sectionStartTime + ' - ' + sectionEndTime + ' | ' + sectionBuilding + ' ' + sectionRoom + ' | ' +  sectionLabTACount + ' ' + sectionWSTACount + ' ' + sectionSLTACount + ' ' + sectionLecTACount + ' ' + sectionGraderCount);
        //sectionTitle.addClass('summary'); DO WE NEED THIS?
    });

    $('.coursePanel').on('shown.bs.collapse', function() {
		var sectionTitle = $(this).find('h2');
		var sectionForm = $(this).find('form');
		var sectionType = sectionForm.attr('data-sectionType');
		var sectionCourseNumber = sectionForm.find('.courseNum').val();
		var sectionCourseTitle = sectionForm.find('.courseTitle').val();
		//sectionTitle.removeClass('summary'); DO WE NEED THIS?
		sectionTitle.html('[' + sectionType + '] CSC ' + sectionCourseNumber + '<span class="hidden-xs">: ' + sectionCourseTitle + '</span>');
    });
});