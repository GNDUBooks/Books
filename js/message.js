var notify = function(id, message, type) {
	if("undefined" == typeof type) {
		type = "error";
	}
	var element = document.getElementById(id);
	element.setAttribute("class","message " + type);
	element.style.display = "block";
	element.appendChild(document.createTextNode(message));
}