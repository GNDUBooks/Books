window.onload = function() {
	var hash = window.location.hash;
	var alert_id = "alert-message";
	if(hash == '#editpostsuccess') {
		notify(alert_id, 'Your ad has been edited successfully',"success");
	} else if(hash == '#unabledbaccess') {
		notify(alert_id, 'Unable to access database. Please try again.');
	} else if(hash == '#failure') {
		notify(alert_id, 'Unable to process your request');
	} else if(hash == '#success') {
		notify(alert_id, 'Your posts have successfully been updated','success');
	}
	window.location.hash = '';
}