$(document).ready(function (){
	
	/* Attach a bootstrapFileInput to the file upload input */
	$('input[type=file]').bootstrapFileInput();
	$('.file-inputs').bootstrapFileInput();
	
	$('#newTermButton').click(function (event) {
		event.preventDefault();
		if (typeof FormData === 'function') {
			showAlert({title: '<span class="badge" style="background-color:white"><img src="../images/ajax-loader.gif" alt="Loading" /></span> Uploading term', message: 'Upload in progress...'}, $('#alertHolder'), 'warning');
			doAction('uploadTerm', new FormData($('#newTermForm')[0])
			).done(function (data) {
				if (data.success) {
					showAlert({message: 'Upload successful. A new term has been created.'}, $('#alertHolder'), 'success');
				} else {
					showError(data.error, $('#alertHolder'));
				}
			}).fail(function (jqXHR, textStatus, errorMessage) {
				showError({message: errorMessage}, $('#alertHolder'));
			});
		} else {
			showError({message: 'Asynchronous upload is not supported by your browser.'}, $('#alertHolder'));
		}
	});
	
});
