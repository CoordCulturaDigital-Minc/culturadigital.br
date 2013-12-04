document.onmousemove = verplaats;
document.onmouseup = stopDrag;

//geen window.onload gebruiken -> kan met andere plugins clashen
if (window.attachEvent) {window.attachEvent('onload', pageIsLoaded);}
else if (window.addEventListener) {window.addEventListener('load', pageIsLoaded, false);}
else {document.addEventListener('load', pageIsLoaded, false);} 

function pageIsLoaded() { //tijdens het slepen mag je niet de tekst selecteeren
	document.onselectstart = function () { if(mousedown) { return false; } } // ie
	document.onmousedown = function () { if(mousedown) { return false; } } // mozilla
	giveRowClasses();
}


var mousedown;
var mouseStart;
var obj;
var tableRow;
var table;
var setEventsMO = false;

function verplaats(event) {
	if(mousedown) {
		document.body.style.cursor='move';
		//checken of de muis over de table row erboven of eronder gaat
		var tableRows = table.getElementsByTagName('tr');
		for(var i=0;i<tableRows.length;i++) {
			if(tableRows[i].id != tableRow) {
				if(tableRows[i].mouseIsOver) {
					//kijken waar je overheen gaat en daarmee wisselen
					var nieuweRow = i;
					tableRows[i].mouseIsOver = false;
				}
			} else {
				var huidigeRow = i;
			}
			
			if(nieuweRow && huidigeRow) {
				replaceRowsPage(nieuweRow, huidigeRow,tableRows);
				setMouse(event);
				break;
			}
		}
	}
}

function replaceRowsPage(nieuw,huidig,rows) { //bij het 'verwisselen' van rows verwissel je altijd de onderste met de bovenste, omdat je hem eigenlijk 'ervoor' plaatst
	//kijken waar de huidige een child van is, de nieuwe MOET hier ook een child van zijn
	var childName = determineChildClass(rows[huidig].className);
	var childNameNieuw = determineChildClass(rows[nieuw].className);
	
	if(childName == childNameNieuw) { //alleen wanneer de nieuwe en huidige bij dezelfde parent horen, kan je ze wisselen
		if(nieuw-huidig>0) { //naar beneden
			//wanneer je naar beneden gaat en de nieuwe een parent is, kan hij niet bewegen, dit gebeurd pas bij de onderste child (zie onder)
			if(rows[nieuw].className.indexOf('isParent') == -1) {
				table.tBodies[0].insertBefore(rows[nieuw],rows[huidig]);
			}
		} else { //omhoog
			//bij het omhoog gaan moet je er rekening mee houden of de huidige een parent is
			//zo ja, dan moeten de childs meebewegen
			if(rows[huidig].className.indexOf('isParent') != -1) { //als het een parent is
				var movingChilds = getTheChilds(rows[huidig]);
				table.tBodies[0].insertBefore(rows[huidig],rows[nieuw]);
				var moveWaarmee = rows[nieuw+1];
				for(i=0;i<movingChilds.length;i++) {
					table.tBodies[0].insertBefore(movingChilds[i],moveWaarmee);
				}
			} else {
				table.tBodies[0].insertBefore(rows[huidig],rows[nieuw]);
			}
		}
	} else { 
		//als je naar beneden gaat en
		//wanneer je wil wisselen met een parent, moet je over de onderste child van deze parent gaan
		//hier ga ik kijken of dat gebeurd, en dan beweeg ik de childs + parent
		
		if(nieuw-huidig>0) {
		
			//kijken of de nieuwe child de onderste child is
			var volgendeRowClassName = determineChildClass(rows[nieuw+1].className);
			
			//als 1 van jouw parent classes gelijk is aan de parent class van de volgende hit
			//dan kan je verder
			
			var isLower = 0;
			var allParentsCurrent = getAllParents(rows[huidig]);
			for(i=0;i<allParentsCurrent.length;i++) {
				if(determineChildClass(allParentsCurrent[i].className) == volgendeRowClassName || rows[nieuw+1].className.indexOf('isChildRowHome') != -1) {
					isLower = 1;
					break;
				}
			}
			
			if((volgendeRowClassName == childName || isLower == 1) && getParentFromChild(rows[nieuw])) { //wanneer de nieuwe childname niet gelijk is als die daarna en de parent niet "home" is
			
				//wanneer de "nieuwe" een andere child is en dieper ligt (dus de parent van deze child heeft dezelfde childName als de "huidige")
				//de parent zoeken, die bepaal je door de parent te pakken die dezelfde child-depth heeft als de huidige
				var getAllTheParents = getAllParents(rows[nieuw]);
				for(i=0;i<getAllTheParents.length;i++) {
					var getTheParentClass = determineChildClass(getAllTheParents[i].className);
					if(getTheParentClass == childName) {
						var theParent = getAllTheParents[i];
					}
				}
				
				var theParentChildName = determineChildClass(theParent.className);
				if(theParent != rows[huidig]) { //de parent mag niet hetzelfde zijn als de huidige row
					//childs pakken van deze parent en deze omhoog bewegen
					var movingChilds = getTheChilds(theParent);
					for(i=movingChilds.length-1;i>=0;i--) {
						table.tBodies[0].insertBefore(movingChilds[i],rows[huidig]);
					}
					table.tBodies[0].insertBefore(theParent,rows[huidig]);
				}
			}
			
		}
	}
}

function getParentFromChild(child) {
	var childName = determineChildClass(child.className);
	var parentIdStart = childName.indexOf('page');
	var parentId = childName.substr(parentIdStart);
	return document.getElementById(parentId);
}

function getAllParents(row) {
	var rowAbove = row;
	var allParents = new Array();
	for(i=0;getParentFromChild(rowAbove);i++) {
		rowAbove = getParentFromChild(rowAbove);
		allParents.push(rowAbove);
	}
	return allParents;
}

function getTheChilds(row) {
	var tableRows = table.getElementsByTagName('tr');
	var movingChilds = new Array();
	for(var i=0;i<tableRows.length;i++) {
		if(tableRows[i].id) {
			var allParents = getAllParents(tableRows[i]);
			for(c=0;c<allParents.length;c++) {
				if(allParents[c] == row) {
					movingChilds.push(tableRows[i]);
				}
			}
		}
	}
	return movingChilds;
}

function determineChildClass(class_name) {
	var positionChildStart = class_name.indexOf('isChildRow');
	var childPartString = class_name.substr(positionChildStart);
	var positionChildEnd = childPartString.indexOf(' ');
	if(positionChildEnd != -1) {
		var childName = childPartString.substr(0,positionChildEnd);
	} else {
		var childName = childPartString;
	}
	return childName;
}

function startDrag(object,event) {
	mousedown = true;
	setMouse(event);
	hideMoveIcons();
	hideRowActions();
	
	obj = object;
	tableRow = obj.parentNode.parentNode.id;
	table = obj.parentNode.parentNode.parentNode.parentNode;
	if(!setEventsMO) {
		initMouseovers();
	}
	
	colorActive(tableRow);
}

function stopDrag() {
	if(mousedown) {
		mousedown = false;
		document.body.style.cursor='';
		showMoveIcons();
		showRowActions();
		updatePageOrder();
		
		colorDeactive(tableRow);
	}
}

function setMouse(e) {
	var IE = document.all?true:false
	if (IE) {
		mouseStart = event.clientY + document.body.scrollTop
	} else {
		mouseStart = e.pageY
	}
}

function getMouse(e) {
	var IE = document.all?true:false
	if (IE) {
		tempMouse = event.clientY + document.body.scrollTop
	} else {
		tempMouse = e.pageY
	}
	if (tempMouse < 0){tempMouse = 0}
	return tempMouse;
}

function hideMoveIcons() {
	var iconEl = getElementsByClassName('movePageIcons');
	for(var i=0;i<iconEl.length;i++) {
		iconEl[i].style.display='none';
	}
}

function showMoveIcons() {
	var iconEl = getElementsByClassName('movePageIcons');
	for(var i=0;i<iconEl.length;i++) {
		iconEl[i].style.display='block';
	}
}

function hideRowActions() {
	var iconEl = getElementsByClassName('row-actions');
	for(var i=0;i<iconEl.length;i++) {
		iconEl[i].style.visibility='hidden';
	}
}

function showRowActions() {
	var iconEl = getElementsByClassName('row-actions');
	for(var i=0;i<iconEl.length;i++) {
		iconEl[i].style.visibility='';
	}
}

function initMouseovers() {
	var tableRows = table.getElementsByTagName('tr');
	for(var i=0;i<tableRows.length;i++) {
		if(tableRows[i].id) {
			tableRows[i].mouseIsOver = false;
			tableRows[i].onmouseover = function()   {
				this.mouseIsOver = true;
			};
			tableRows[i].onmouseout = function()   {
				this.mouseIsOver = false;
			};
		}
	}
	setEventsMO = true;
}

function updatePageOrder() {
	var tableRows = table.getElementsByTagName('tr');
	var theOrder = new Array();
	for(var i=0;i<tableRows.length;i++) {
		if(tableRows[i].id) {
			var newId = tableRows[i].id.replace('page-','');
			theOrder.push(newId);
		}
	}
	orderDoorgeven(theOrder);
}



function giveRowClasses() {
	var imgEls = getElementsByClassName('movePageIcons');
	for(i=0;i<imgEls.length;i++) {
		if(imgEls[i].className.indexOf('isChild') != -1) {
			for(c=i-1;c>=0;c--) {
				if(imgEls[c].className.indexOf('isParent') != -1) {
					var theChildClass = getMovePageIconChildClass(imgEls[i].className);
					var theChildClassFound = getMovePageIconChildClass(imgEls[c].className);
					var childOfWhat = theChildClass.replace('isChild','');
					if(theChildClass != theChildClassFound) {
						imgEls[i].parentNode.parentNode.className += ' childClass isChildRowpage-'+childOfWhat;
						break;
					}
				}
			}
		} else {
			imgEls[i].parentNode.parentNode.className += ' isChildRowHome';	//wanneer het geen child is, is ie een child van 'home'
		}
		if(imgEls[i].className.indexOf('isParent') != -1) {
			imgEls[i].parentNode.parentNode.className += ' isParent';
		}
		var paddingLeftForSub = getThePadding(imgEls[i].className);
		var theRow = imgEls[i].parentNode.parentNode;
		var firstTd = theRow.getElementsByTagName('td');
		firstTd[0].style.paddingLeft=paddingLeftForSub+"px";
	}
}

function getThePadding(class_name) {
	var positionChildStart = class_name.indexOf('childPad');
	var childPartString = class_name.substr(positionChildStart);
	var positionChildEnd = childPartString.indexOf(' ');
	if(positionChildEnd != -1) {
		var childName = childPartString.substr(0,positionChildEnd);
	} else {
		var childName = childPartString;
	}
	var thePadding = childName.replace('childPad','');
	return thePadding;
}

function getMovePageIconChildClass(class_name) {
	var positionChildStart = class_name.indexOf('isChild');
	var childPartString = class_name.substr(positionChildStart);
	var positionChildEnd = childPartString.indexOf(' ');
	if(positionChildEnd != -1) {
		var childName = childPartString.substr(0,positionChildEnd);
	} else {
		var childName = childPartString;
	}
	return childName;
}

function colorActive(rowId) {
	var tableRows = table.getElementsByTagName('tr');
	for(var i=0;i<tableRows.length;i++) {
		if(tableRows[i].id == rowId) {
			tableRows[i].style.backgroundColor='#dddeee';
			if(tableRows[i].className.indexOf('isParent') != -1) {
				var allChilds = getTheChilds(tableRows[i]);
				for(c=0;c<allChilds.length;c++) {
					allChilds[c].style.backgroundColor='#eeeeff';
				}
			} 
		}
	}
}

function colorDeactive(rowId) {
	var tableRows = table.getElementsByTagName('tr');
	for(var i=0;i<tableRows.length;i++) {
		if(tableRows[i].id == rowId) {
			tableRows[i].style.backgroundColor='';
			if(tableRows[i].className.indexOf('isParent') != -1) {
				var allChilds = getTheChilds(tableRows[i]);
				for(c=0;c<allChilds.length;c++) {
					allChilds[c].style.backgroundColor='';
				}
			} 
		}
	}
}

// niet zelfgeschreven functies
function getElementsByClassName(class_name)
      {
        var all_obj,ret_obj=new Array(),j=0,teststr;

        if(document.all)all_obj=document.all;
        else if(document.getElementsByTagName && !document.all)
          all_obj=document.getElementsByTagName("*");

        for(i=0;i<all_obj.length;i++)
        {
          if(all_obj[i].className.indexOf(class_name)!=-1)
          {
            teststr=","+all_obj[i].className.split(" ").join(",")+",";
            if(teststr.indexOf(","+class_name+",")!=-1)
            {
              ret_obj[j]=all_obj[i];
              j++;
            }
          }
        }
        return ret_obj;
	  }
	  
	  
	  
    function getURLParam(strParamName){
      var strReturn = "";
      var strHref = window.location.href;
      if ( strHref.indexOf("?") > -1 ){
        var strQueryString = strHref.substr(strHref.indexOf("?")).toLowerCase();
        var aQueryString = strQueryString.split("&");
        for ( var iParam = 0; iParam < aQueryString.length; iParam++ ){
          if (
    aQueryString[iParam].indexOf(strParamName.toLowerCase() + "=") > -1 ){
            var aParam = aQueryString[iParam].split("=");
            strReturn = aParam[1];
            break;
          }
        }
      }
      return unescape(strReturn);
    } 

