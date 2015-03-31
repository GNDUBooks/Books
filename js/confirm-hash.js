window.onload = function() {
	var hash = window.location.hash;
	var alert_id = "alert-message";
	if(hash == "#temporaryaccountcreated") {
		notify(alert_id,'One Time Password has been sent to your email. Please confirm your email to complete registration.','success');
	} else if(hash == '#changemailsent') {
		notify(alert_id,'One Time Password has been sent to your new email address. Please confirm your new email address.','success');
	}
	window.location.hash = '';
}