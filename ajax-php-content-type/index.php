<?php

?>
<div>
<input type="text" name="firstname" value="Abijeet" id="txtFirstname">
<input type="text" name="lastname" value="Patro" id="txtLastname">
<input type="button" class="btn_submit" value="GET [application/x-www-form-urlencoded]" data-method="sendViaGetURLEncoded">
<input type="button" class="btn_submit" value="POST [application/x-www-form-urlencoded]" data-method="sendViaPostURLEncoded">
<input type="button" class="btn_submit" value="GET [application/json]" data-method="sendViaGetJSON">
<input type="button" class="btn_submit" value="POST [application/json]" data-method="sendViaPostJSON">
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> 
<script type="text/javascript">
$(document).ready(function(){
	$('.btn_submit').click(function(){
		var user = {
			firstname : $('#txtFirstname').val(),
			lastname : $('#txtLastname').val()
		};
		var methodToCall = $(this).data('method');
		window[methodToCall](user);	
	});
});
function makeAjaxCall(dataToSend, type, contentType) { 		
	var ajaxCall = $.ajax({
	    type: type,
	    url: 'api.php',
	    contentType : contentType,
	    data: dataToSend,
		timeout: 60000,	  
	    success: function(response) {
	        console.log(response);
	    },
	    error: function(jqXHR, status, error) {
			console.log(jqXHR);
			console.log(status);
			console.log(error);
	    }
	});
	return ajaxCall;
}
function sendViaGetURLEncoded(user) {
	makeAjaxCall(user, 'GET', 'application/x-www-form-urlencoded; charset=UTF-8');
}
function sendViaPostURLEncoded(user) {
	makeAjaxCall(user, 'POST', 'application/x-www-form-urlencoded; charset=UTF-8');
}
function sendViaGetJSON(user) {
	makeAjaxCall(JSON.stringify(user), 'POST', 'application/json; charset=UTF-8');
}
function sendViaPostJSON(user) {
	makeAjaxCall(JSON.stringify(user), 'POST', 'application/json; charset=UTF-8');	
}
</script>