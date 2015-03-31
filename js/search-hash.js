function updateTextInput(val,id) {
  document.getElementById('textInput'+id).value=val; 
}
function updateSliderInput(val,id) {
	document.getElementById('rangeInput'+id).value=val;
}
window.onload = function() {
	var hash = window.location.hash;
	var alert_id = "alert-message";
	if(hash == '#sent') {
		notify(alert_id,'Your request for sending offer has been processed.',"success");
	} else if(hash == '#reported') {
		notify(alert_id,'Your request for reporting of ad is sent to admin.',"success");
	} else if(hash == '#notsent') {
		notify(alert_id,'Unable to send offer. Please try again');
	} else if(hash == '#notreported') {
		notify(alert_id,'Unable to report ad. Please try again');
	} else if(hash == '#dbproblem') {
		notify(alert_id,'Unable to process your request');
	} else if(hash == '#alreadysent') {
		notify(alert_id,'You have already sent offer for this add');
	} else if(hash == '#offerlimitreached') {
		notify(alert_id,'Your limit of sending 5 offers per day has been reached');
	} else if(hash == '#alreadyreported') {
		notify(alert_id,'This post has already been reported');
	} else if(hash == '#reportlimitreached') {
		notify(alert_id,'Your limit of reporting 5 posts has been reached');
	}
	window.location.hash = '';
}