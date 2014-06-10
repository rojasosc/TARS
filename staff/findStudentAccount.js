$(document).ready(function() {
	var email;
	$('#results').hide();	
	/*Prevents a page redirection to the php page.*/
	$("#findAccountForm").submit(function(event){
		$('#formBox').hide(1000);
		$('#noResults').hide(1000);	
	return false;
	});

	/*Prevents a page redirection to the php page.*/
	$("#updateForm").submit(function(event){
		
	return false;
	
	});

	$('#searchStudents').click(function(){
			
		email = $('#emailSearch').val();
			
		// Ajax post(url,data,callback function)
		var url = $('#findAccountForm').attr('action');
		var data = $('#findAccountForm :input').serializeArray();
			
		$.post(url,data,function (info){ 
		clearInput();

		var student = eval('(' + info + ')');
		
		/* If there are no results, display a prompt */
		
		if(!student){
			
			$('#noResults').toggle(2000);
			
			
		}

		/* Prepare results table */
		
				
		
		
		
/*		$('#firstName').val(student["firstName"]);
		$('#lastName').val(student["lastName"]);
		$('#email').val(email);
		$('#mobilePhone').val(student["mobilePhone"]);
		$('#homePhone').val(student["homePhone"]);
		$('#major').val(student["major"]);
		$('#gpa').val(student["gpa"]);
		$('#classYear').val(student["classYear"]);
		$('#aboutMe').val(student["aboutMe"]);	*/	

		$('#results').fadeIn(1000);
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
	
	$('#closeForm').click(function(){
		
		$('#formBox').hide(1000);	
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


	