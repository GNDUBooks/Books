window.onload = function() {
	var hash = window.location.hash;
	var alert_id = "alert-message";
	if(hash == '#recoverErr') {
		notify(alert_id, 'Unable to process your request. Please try again later');
	} else if(hash == '#passchanged') {
		notify(alert_id, 'Your password has been changed and new password is sent to your email.', "success");
	} else if(hash == '#mailnotsent') {
		notify(alert_id, 'Unable to send new password to your email. Please try again later.');
	} else if(hash == '#temporaryaccountnotcreated') {
		notify(alert_id, 'Please try again later as some error may have occured.');
	} else if(hash == '#registrationcomplete') {
		notify(alert_id,'Your account has successfully been created.', "success");
	}
	window.location.hash = '';
}