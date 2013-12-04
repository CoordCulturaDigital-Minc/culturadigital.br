<?php

$url = $_SERVER['REQUEST_URI'];

if(($n = strpos($url,basename(__FILE__))) != FALSE){
	$url = substr($url,0,$n);
}

if($_SERVER['SERVER_PORT'] !== '80' && (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' && $_SERVER['SERVER_PORT'] !== '443')){
	$url = ':'.$_SERVER['SERVER_PORT'].$url;
}

$url = $_SERVER['SERVER_NAME'].$url;

if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off'){
	$url = 'https://'.$url;
}else{
	$url = 'http://'.$url;
}

if($_GET['jsver'] === 'common'){
	//header("Content-Type:text/javascript");
?>
var rpPel = null;
var Commentarea = null;

function $s(){
	if(arguments.length == 1)
		return get$(arguments[0]);
	
	var elements = [];
	$c(arguments).each(function(el){elements.push(get$(el));});

	return elements;
}

function get$(el){
	if(typeof el == 'string')
		el = document.getElementById(el);
	return el;
}

function $c(array){
	var nArray = [];
	for (i=0;el=array[i];i++) nArray.push(el);
	return nArray;
}

function commentarea(){
	var fi = $s(commentformid).getElementsByTagName('textarea');
	for(var i=0; i<fi.length; i++ ){
			if(fi[i].name == 'comment'){
				return fi[i];
			}
	}
	return null;
}

function movecfm(event,Id,dp,author){
	var cfm = $s(commentformid);

	if(cfm == null){
	  	alert("ERROR:\nCan't find the 'commentformid' div.");
		return false;
	}

	var reRootElement = $s("cancel_reply");

	if(reRootElement == null){
		alert("Error:\nNo anchor tag called 'cancel_reply'.");
		return false;
	}
	
	var replyId = $s("comment_reply_ID");
	
	if(replyId == null){
		alert("Error:\nNo form field called 'comment_reply_ID'.");
		return false;
	}

	var dpId = $s("comment_reply_dp");

	if(Commentarea == null)
		Commentarea = commentarea();

	if(parseInt(Id)){
		if(cfm.style.display == "none"){
			alert("New Comment is submiting, please wait a moment");
			return false;
		}

		if(event == null)
              event = window.event;

		rpPel = event.srcElement? event.srcElement : event.target;
		rpPel = rpPel.parentNode.parentNode;

		var OId = $s("comment-"+Id);
		if(OId == null){
			//alert("Error:\nNo comment called 'comment-xxx'.");
			//return false;
			OId = rpPel;
		}

		replyId.value = Id;
		if(dpId)
			dpId.value = dp;
		reRootElement.style.display = "block";

		if($s("cfmguid") == null){
			var c = document.createElement("div");
			c.id = "cfmguid";
			c.style.display = "none";
			cfm.parentNode.insertBefore(c,cfm);
		}
		cfm.parentNode.removeChild(cfm);
		OId.appendChild(cfm);

		if(Commentarea && Commentarea.display != "none"){
			Commentarea.focus();
			if(atreply == 'author')
				Commentarea.value = '@' + author + ', ';
			else if(atreply == 'authorlink')
				Commentarea.value = '<a href="#comment-' + Id + '">@' + author + '</a>, ';
		}

		cfm.style.display = "block";
	}else{
		replyId.value = "0";
		if(dpId)
			dpId.value = "0";
		reRootElement.style.display = "none";
			
		var c = $s("cfmguid");
		if(c){
			cfm.parentNode.removeChild(cfm);
			c.parentNode.insertBefore(cfm,c);
		}

		if(parseInt(dp) && Commentarea && Commentarea.display != "none"){
			Commentarea.focus();
			//Commentarea.value = '';
		}
	}
	return true;
}
<?php
	die();exit();
}
if($_GET['jsver'] === 'ajax'){
	//header("Content-Type:text/javascript");
?>
if(lstcommentid){
	if($s("comment-"+lstcommentid)){
		lstcommentid = $s("comment-"+lstcommentid);
		var commentformel = $s("comment_reply_ID");
		while(commentformel != null && commentformel.tagName != "FORM"){
			commentformel = commentformel.parentNode;
		}
		if(commentformel != null && commentformel.action.indexOf("wp-comments-post.php") != -1){
			commentformel.onsubmit = wptcajaxsend;
		}
	}
}

function gparam(f){
	var p='wptcajax=wptcajax';
	var fi = f.getElementsByTagName('input');
	for(i=0; i<fi.length; i++ ){
		e=fi[i];
		if(e.name!=''){
			if(e.type=='select')
				element_value=e.options[e.selectedIndex].value;
			else if(e.type=='checkbox' || e.type=='radio'){
				if(e.checked==false)
					continue;
				element_value=e.value;
			}else{
				element_value=e.value;
			}
			p+="&"+e.name+'='+encodeURIComponent(element_value);
		}
	}
	fi = f.getElementsByTagName('textarea');
	for(i=0; i<fi.length; i++)
		p+="&"+fi[i].name+"="+encodeURIComponent(fi[i].value);
	
	return p;
}

function getXMLInstance(){
	var req;
	if(window.XMLHttpRequest){
		req = new XMLHttpRequest();
		if (req.overrideMimeType){
			//req.overrideMimeType('text/xml');
		}
	}else if(window.ActiveXObject){
		try{
			req = new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			try{
				req = new ActiveXObject("Microsoft.XMLHTTP");
			}catch(e){}
		}
	}
	if(!req){
		alert('Giving up :( Cannot create an XMLHTTP instance');
		return false;
	}
	return req;
}

function wptcajaxsend(){
	var req = getXMLInstance();
	var r = $s('comment_reply_ID').value;
	var c = null;

	var p=gparam(commentformel);

	if(p != null)
		q=p.split("&");

	var author = null, email = null, comment = null;

	if(USERINFO == false){
		author = stpm("author",q);
		email = stpm("email",q);

		if(needauthoremail == true){
			if(author != null && author == ""){
				alert("please enter a valid author name");
				if($s("author"))
					$s("author" && $s("author").style.display != "none").focus();
				return false;
			}

			if(email != null && (email == "" || email.length < 6 || email.match(/^([a-z0-9+_]|\-|\.)+@(([a-z0-9_]|\-)+\.)+[a-z]{2,6}$/i) == null)){
				alert("please enter a valid email address");
				if($s("email"))
					$s("email" && $s("email").style.display != "none").focus();
				return false;
			}
		}else{
			if(author == null || author == ""){
				author = "Anonymous";
			}
		}
	}else{
		author = USERINFO;
	}

	comment = stpm("comment",q);

	if(comment == null || comment == ""){
		alert("comment can not be empty");
		if(Commentarea && Commentarea.display != "none")
			Commentarea.focus();
		return false;
	}

	comment = comment.replace(/\r\n\r\n/g, "</p><p>");
	comment = comment.replace(/\r\n/g, "<br />");
	comment = comment.replace(/\n\n/g, "</p><p>");
	comment = comment.replace(/\n/g, "<br />");
	comment = "<p>"+comment+"</p>";
	var dateObj = new Date();

	if(r == 0){
		c = document.createElement('div');
		//c = document.createElement(lstcommentid.tagName);
		c.id = "newcomment";
		if(sortflag == 'DESC'){
			lstcommentid.parentNode.insertBefore(c,lstcommentid);
			window.location="#newcomment";
		}else{
			if(lstcommentid.parentNode.lastChild == lstcommentid){
				lstcommentid.parentNode.appendChild(c);
			}else{
				lstcommentid.parentNode.insertBefore(c,lstcommentid.nextSibling);
			}
		}
	}else{
		c = document.createElement('div');
		c.id = "newcomment";
		rpPel.appendChild(c);
	}

	c.innerHTML = "<div id=\"newcommentcontent\"><p>"+author+" <em>Submit on "+dateObj.toLocaleString()+"</em>:</p>"+comment+"</div><div id=\"newcommentsubmit\"><p>new comment is submiting, please wait a comment...<img src=\"<?php echo $url; ?>loading.gif\" /></p></div>";

	$s(commentformid).style.display='none';
	
	req.onreadystatechange = function(){
		if(req.readyState == 4){
			$s(commentformid).style.display='block';
			if(req.status == 200){
				wptctextreplace($s('newcomment'),req.responseText);

				if(parseInt(r)){
					movecfm(null,0,0,null);	
				}
				if($s('comment')) $s('comment').value = '';
			}else{
				c.parentNode.removeChild(c);
				var error = req.responseText.match(/<body[^>]*>[\s\S]*?<p[^>]*>([\s\S]*)<\/p>[\s\S]*?<\/body>/i);
				if(typeof(error) != 'undefined' && error != null && error != ''){
					alert(error[1]);
				}else{
					alert('Failed to add your comment.');
				}
			}
		}
	}
	
	req.open('POST', commentformel.action, true);
	req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	req.setRequestHeader("Content-length", p.length);
	req.setRequestHeader("Connection", "close");
	req.send(p);
	
	return false;
}

function wptctextreplace(element,text){
	if(!text)
		return false;

	element.innerHTML = text;

	var commentid = text.match(/<(\w+).*?id\s*=\s*("|')(comment-\d+?)\2[^>]*>[\s\S]*<\/\1>/i);
	if(typeof(commentid) != 'undefined' && commentid != null){
		var commentid = commentid[3];

		var childelement = $s(commentid);
	
		var n=1;
		while(childelement.parentNode != element){
			childelement = childelement.parentNode;
			n= n+1;
			if(n>10) break;
		}

		if(n<11){
			if(typeof(childelement.className) != 'undefined' && lstcommentid.className.match(/\balt\b/i)){
				childelement.className = childelement.className.replace(/\balt\b/i,'');
			}

			element.parentNode.insertBefore(childelement,element);
			element.parentNode.removeChild(element);
			if($s('comment_reply_ID').value == 0)
				lstcommentid = childelement;

<?php if(isset($_GET['wpajaxeditcomments']) && $_GET['wpajaxeditcomments'] === 'true'){ ?>
	if(typeof(AjaxEditComments) != 'undefined' && typeof(AjaxEditComments.init) == 'function')
		AjaxEditComments.init();
<?php } ?>

		}

	}else{
		lstcommentid = element;
	}

	return true;
}
function stpm(aname,array){
	for (var i=0; i < array.length; i++){
		var c = array[i].split("=");
		c[0] = unescape(decodeURI(c[0]));
		c[0] = c[0].replace(/^\s+|\s+$/g,"");
		if (aname == c[0]){
			c[1] = unescape(decodeURI(c[1]));
			c[1] = c[1].replace(/^\s+|\s+$/g,"");
			return c[1];
		}
	}
 	return null;
}
<?php
	die();exit();
}

if($_GET['jsver'] === 'adminajax'){
	//header("Content-Type:text/javascript");
?>

var rpPel = null;
var rpSrc = null;

function $s(){
	if(arguments.length == 1)
		return get$(arguments[0]);
	
	var elements = [];
	$c(arguments).each(function(el){elements.push(get$(el));});

	return elements;
}

function get$(el){
	if(typeof el == 'string')
		el = document.getElementById(el);
	return el;
}

function $c(array){
	var nArray = [];
	for (i=0;el=array[i];i++) nArray.push(el);
	return nArray;
}

function popuptext(event,pid,cid,author){
	if(event == null)
		event = window.event;

	if(parseInt($s("wptc_comment_post_ID").value) != 0 && $s("inlinereply").style.display == "none"){
		alert("Comment is Submiting, Please wait a moment");
		return false;
	}

	rpSrc = event.srcElement? event.srcElement : event.target;
	rpSrc = rpSrc.parentNode;
	rpPel = rpSrc.parentNode;

	if(pid == 0 || pid == null || cid == 0 || cid == null){
		return false;
	}

	var cfm = $s('inlinereply');
	if(cfm == null){
		return false;
	}

	$s("comment_reply_ID").value = cid;
	$s("wptc_comment_post_ID").value = pid;

	rpPel.insertBefore(cfm,rpSrc.nextSibling);
	cfm.style.display = 'block';
	if(atreply == 'author')
		$s("wptc_comment").value = '@' + author + ', ';
	else if(atreply == 'authorlink')
		$s("wptc_comment").value = '<a href="#comment-' + cid + '">@' + author + '</a>, ';

}

function getXMLInstance(){
	var req;
	if(window.XMLHttpRequest){
		req = new XMLHttpRequest();
		if (req.overrideMimeType){
			//req.overrideMimeType('text/xml');
		}
	}else if(window.ActiveXObject){
		try{
			req = new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			try{
				req = new ActiveXObject("Microsoft.XMLHTTP");
			}catch(e){}
		}
	}
	if(!req){
		alert('Giving up :( Cannot create an XMLHTTP instance');
		return false;
	}
	return req;
}

function wptcadminajaxsend(){
	var req = getXMLInstance();
	var c = null;

	var comment = null;
	comment = $s('wptc_comment').value

	if(comment == null || comment == ""){
		alert("comment can not be empty");
		$s("wptc_comment").focus();
		return false;
	}

	var cfm = $s('inlinereply');

	var p= "wptcadminajax=wptcadminajax&comment="+encodeURIComponent(comment)+"&comment_post_ID="+encodeURIComponent($s("wptc_comment_post_ID").value)+"&comment_reply_ID="+encodeURIComponent($s("comment_reply_ID").value)+"&comment_nospam="+encodeURIComponent($s("wptc_comment_post_ID").value);

	comment = comment.replace(/\r\n\r\n/g, "</p><p>");
	comment = comment.replace(/\r\n/g, "<br />");
	comment = comment.replace(/\n\n/g, "</p><p>");
	comment = comment.replace(/\n/g, "<br />");
	comment = "<p>"+comment+"</p>";
	var dateObj = new Date();

	c = document.createElement('div');
	c.id = "commentreply-" + $s('comment_reply_ID').value;
	c.className = "adminreplycomment";
	rpPel.insertBefore(c, rpSrc.nextSibling);

	comment = "<p><em>You Submit on " + dateObj.toLocaleString() + "</em>:</p>" + comment;

	c.innerHTML = "<div id=\"newcommentcontent\">" + comment + "</div><div id=\"newcommentsubmit\"><p>new comment is submiting, please wait a moment...<img src=\"<?php echo $url; ?>loading.gif\" /></p></div>";

	cfm.style.display='none';
	
	req.onreadystatechange = function(){
		if(req.readyState == 4){	
			if(req.status == 200){
				c.innerHTML = comment;
				$s('wptc_comment').value = '';
				$s("wptc_comment_post_ID").value = 0;
			}else{
				c.parentNode.removeChild(c);
				var error = req.responseText.match(/<body>[\s\S]*?<p>([\s\S]*)<\/p>[\s\S]*?<\/body>/i);
				if(typeof(error) != 'undefined' && error != null && error != ''){
					alert(error[1]);
				}else{
					alert('Failed to add your comment.');
				}
				cfm.style.display='block';
			}
		}
		return false;
	}
	
	req.open('POST', cfm.action, true);
	req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	req.setRequestHeader("Content-length", p.length);
	req.setRequestHeader("Connection", "close");
	req.send(p);
	
	return false;
}
<?php
	die();exit();
}
?>