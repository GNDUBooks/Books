window.onload = function() {
	var hash = window.location.hash;
	var alert_id = "alert-message";
	if(hash == '#adposted') {
		notify(alert_id, 'Your ad has been posted',"success");
	} else if(hash == '#notposted') {
		notify(alert_id, 'Unable to post your ad. Please try again.');
	} else if(hash == '#notsent') {
		notify(alert_id, 'Unable to send offer');
	} else if(hash == '#notreported') {
		notify(alert_id, 'Unable to report ad');
	} else if(hash == '#passchanged') {
		notify(alert_id,'Your password has been changed and new password has been sent to your email address.',"success");
	} else if(hash == '#passnotchanged') {
		notify(alert_id,'There was problem in updating your Password. Try after some time.');
	} else if(hash == '#emailchanged') {
		notify(alert_id,'Your email has successfully been changed.',"success");
	} else if(hash == '#editprofilesuccess') {
		notify(alert_id,'Your profile has been updated successfully',"success");
	} else if(hash == '#editprofilefailed') {
		notify(alert_id,'Unable to udate your profile. Please try again');
	}
	window.location.hash = '';
}