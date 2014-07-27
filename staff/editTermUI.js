$(document).ready(function() {
	alert('js file called');
	
    $('.sectionPanel').on('hidden.bs.collapse', function() {
		alert('hidden!');
		$(this).closest('.coursePanel').find('h2').addClass('summary');
	});
	
	$('.sectionPanel').on('shown.bs.collapse', function() {
		alert('shown!');
		$(this).closest('.coursePanel').find('h2').removeClass('summary');
	});
});