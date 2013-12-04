/* <![CDATA[ */
			
var oldOnload = window.onload;
if(typeof oldOnload == "function") {
	window.onload = function() {
		if(oldOnload) {
			oldOnload();
		}
		showMe();
	}
} else {
	window.onload = showMe;
}

function showMe() {
	var optin_form = document.forms['myForm'].tbf1_optin_form;
	var optin_formLength = optin_form.length;
	
	for (i = 0; i <optin_formLength; i++) {
		if (optin_form[i].checked) {
			var optinPick = optin_form[i].value
		}
	}
	
	if (optinPick == "no") {
		document.getElementById("optinform").style.display = "none";
	} else {
		document.getElementById("optinform").style.display = "block";
	}

	var optin_custom = document.forms['myForm'].tbf1_optin_img;
	var optin_customLength = optin_custom.length;

	for (i = 0; i <optin_customLength; i++) {
		if (optin_custom[i].checked) {
			var optin_customPick = optin_custom[i].value
		}
	}
	
	if (optin_customPick == "yes") {
		document.getElementById("optindefaultdiv").style.display = "block";
	} else {
		document.getElementById("optindefaultdiv").style.display = "none";
	}
}
/* ]]> */