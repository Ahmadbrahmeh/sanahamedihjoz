

// this is functions for alerts

function SuccessAlert(message){
	$('#success_message .message').text(message);
	$('#success_message').show().delay(6000).fadeOut();
}

function ErrorAlert(message){
	$('#error_message .message').text(message);
	$('.alert-danger').show().delay(6000).fadeOut();
}

function WarningAlert(message){
	$('#warning_message .message').text(message);
	$('#warning_message').show().delay(6000).fadeOut();
}

function scrollPageTop () {
	$("html, body").animate({ scrollTop: 0 }, "slow");
}