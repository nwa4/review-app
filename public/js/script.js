// JavaScript Document
jQuery(document).ready(function($){
	$('.noDev').change(function(e){
		if($(this).is(':checked'))
			$('.dev').prop('checked', false).prop('disabled', true);
		else
			$('.dev').prop('checked', false).prop('disabled', false);
	});
	
	$('#photo').change(function(e){
		$('#lblMsg').html('');
		$('.btnSendPhoto').prop('disabled', false);
		$('.photoError').html('');
		var MAX_SIZE = 500*1024;
		 var fileInput = this;
		if(fileInput.files[0].size > MAX_SIZE)
			{
				$('#lblMsg').html('image size is too big, should be 500kb max.').css({'color':'red'});
				$('.btnSendPhoto').prop('disabled', true);
				return;
			}
				if (fileInput.files && fileInput.files[0]) { 
				 var reader = new FileReader(); 
				 reader.onload = function (e) { 
						 $('#img').attr('src', e.target.result); 
				 }
				 reader.readAsDataURL(fileInput.files[0]);
				}
			
	});
});