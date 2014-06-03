$(document).ready(function() {
	var email;
	$('#formBox').hide();
	
	/*Prevents a page redirection to the php page.*/
	$("#findAccountForm").submit(function(event){
		
	return false;
	});

	/*Prevents a page redirection to the php page.*/
	$("#updateForm").submit(function(event){
		
	return false;
	
	});

	$('#submitButton').click(function(){
			
		email = $('#emailSearch').val();
			
		// Ajax post(url,data,callback function)
		var url = $('#findAccountForm').attr('action');
		var data = $('#findAccountForm :input').serializeArray();
			
		$.post(url,data,function (info){ 
		clearInput();
		
	
		
		var professor = eval('(' + info + ')');

		$('#firstName').val(professor["firstName"]);
		$('#lastName').val(professor["lastName"]);
		$('#email').val(email);
		$('#officePhone').val(professor["officePhone"]);
		$('#mobilePhone').val(professor["mobilePhone"]);
		$('#formBox').show(1000);
		});
			
	});
	
	$('#updateButton').click(function(){
		
		// Ajax post(url,data,callback function)
		var url = $('#updateForm').attr('action');
		var data = $('#updateForm :input').serializeArray();
			
		$.post(url,data,function (info){ 
		clearInput();
		
		$('#formBox').hide(1000);
		});
			
	});	


	
});

/*Clears the email field*/
function clearInput() {

	$("#findAccountForm :input").each( function() {		
	   $(this).val('');
	});
}


/*Clears all the fields in the update form*/
function clearUpdateForm() {

	$("#updateForm :input").each( function() {		
	   $(this).val('');
	});
}


	