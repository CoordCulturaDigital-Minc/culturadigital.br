if (window.attachEvent) {window.attachEvent('onload', add_dropDown);}
else if (window.addEventListener) {window.addEventListener('load', add_dropDown, false);}
else {document.addEventListener('load', add_dropDown, false);} 

function add_dropDown() {
	var container = document.getElementById("doaction").parentNode;
	var dropDownBox = "<select onchange='showMore(this.value)' id='show_more_pages'>";

	dropDownBox += "<option value='"+currentValue+"'>"+dropDownText+"</option>";

	var dropdown_options = new Array(20,40,60,100,250,500);
	for(i=0;i<dropdown_options.length;i++) {
		currentSelected = '';
		if(currentValue == dropdown_options[i] && getValue) currentSelected = 'selected';
		dropDownBox += "<option "+currentSelected+">"+dropdown_options[i]+"</option>";
	}
	container.innerHTML += dropDownBox;
}

function showMore(show_pages) {
	window.location='?show_pages='+show_pages;
}