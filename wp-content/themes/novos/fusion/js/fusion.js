

// cookie functions
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options = jQuery.extend({}, options); // clone object since it's unexpected behavior if the expired property were changed
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // NOTE Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};
// /cookie functions

// fixes for IE-7 cleartype bug on fade in/out
jQuery.fn.fadeIn = function(speed, callback) {
 return this.animate({opacity: 'show'}, speed, function() {
  if (jQuery.browser.msie) this.style.removeAttribute('filter');
  if (jQuery.isFunction(callback)) callback();
 });
};

jQuery.fn.fadeOut = function(speed, callback) {
 return this.animate({opacity: 'hide'}, speed, function() {
  if (jQuery.browser.msie) this.style.removeAttribute('filter');
  if (jQuery.isFunction(callback)) callback();
 });
};

jQuery.fn.fadeTo = function(speed,to,callback) {
 return this.animate({opacity: to}, speed, function() {
  if (to == 1 && jQuery.browser.msie) this.style.removeAttribute('filter');
  if (jQuery.isFunction(callback)) callback();
 });
};


// liquid <> fixed
function setPageWidth(){
   var currentWidth = jQuery('#page').css('width');
   if (currentWidth=="95%") newWidth = "980px"; else if (currentWidth=="980px") newWidth = "95%"; else newWidth = "980px";
   jQuery("#page").animate({width: newWidth}, 333).fadeIn("slow");
   jQuery.cookie('pageWidth', newWidth);
}

// body font size
function setFontSize() {
   var size = jQuery.cookie('fontSize');
   if (size=='.75em') newSize = '.95em';
   else if (size=='.95em') newSize = '.70em';
   else if (size=='.70em') newSize = '.75em';
   else newSize = '.95em';
   jQuery("body").animate({fontSize: newSize}, 333).fadeIn("slow");
   jQuery.cookie('fontSize',newSize)
}

// minitabs
jQuery.fn.minitabs = function(speed,effect) {
  id = "#" + this.attr('id')
  jQuery(id + ">DIV:gt(0)").hide();
  jQuery(id + ">UL>LI>A:first").addClass("current");
  jQuery(id + ">UL>LI>A").click(
    function(){
      jQuery(id + ">UL>LI>A").removeClass("current");
      jQuery(this).addClass("current");
      jQuery(this).blur();
      var re = /([_\-\w]+$)/i;
      var target = jQuery('#' + re.exec(this.href)[1]);
      var old = jQuery(id + ">DIV");
      switch (effect) {
        case 'fade':
          old.fadeOut(speed).fadeOut(speed);
          target.fadeIn(speed);
          break;
        case 'slide':
          old.slideUp(speed);
          target.fadeOut(speed).fadeIn(speed);
          break;
        default :
          old.hide(speed);
          target.show(speed)
      }
      return false;
    }
 );
}

function initTooltips(o) {
  var showTip = function() {
  	var el = jQuery('.tip', this).css('display', 'block')[0];
  	var ttHeight = jQuery(el).height();
    var ttOffset =  el.offsetHeight;
	var ttTop = ttOffset + ttHeight;
    jQuery('.tip', this)
	  .stop()
	  .css({'opacity': 0, 'top': 2 - ttOffset})
  	  .animate({'opacity': 1, 'top': 18 - ttOffset}, 250);
	};
	var hideTip = function() {
  	  var self = this;
	  var el = jQuery('.tip', this).css('display', 'block')[0];
      var ttHeight = jQuery(el).height();
	  var ttOffset =  el.offsetHeight;
	  var ttTop = ttOffset + ttHeight;
      jQuery('.tip', this)
	  	.stop()
	  	.animate({'opacity': 0,'top': 10 - ttOffset}, 250, function() {
		   el.hiding = false;
		   jQuery(this).css('display', 'none');
		}
      );
	};
	jQuery('.tip').hover(
	  function() { return false; },
	  function() { return true; }
	);
	jQuery('.tiptrigger, #sidebar .cat-item').hover(
	  function(){
	  	var self = this;
	  	showTip.apply(this);
	  	if (o.timeout) this.tttimeout = setTimeout(function() { hideTip.apply(self) } , o.timeout);
	  },
	  function() {
	  	clearTimeout(this.tttimeout);
	  	hideTip.apply(this);
	  }
	);
}


// simple tooltips
function webshot(target_items, name){
 jQuery(target_items).each(function(i){
		jQuery("body").append("<div class='"+name+"' id='"+name+i+"'><img src='"+jQuery(this).attr('src')+"' /></div>");
		var my_tooltip = jQuery("#"+name+i);

		jQuery(this).mouseover(function(){
				my_tooltip.css({opacity:1, display:"none"}).fadeIn(166);
		}).mousemove(function(kmouse){
				my_tooltip.css({left:kmouse.pageX+15, top:kmouse.pageY+15});
		}).mouseout(function(){
				my_tooltip.fadeOut(333);
		});
	});
}

function tabmenudropdowns(){
jQuery(" #tabs ul ul ").css({display: "none"}); // Opera Fix
jQuery(" #tabs li").hover(function(){
  jQuery(this).find('ul:first').css({visibility: "visible",display: "none"}).show(333);
 },function(){
    jQuery(this).find('ul:first').css({visibility: "hidden"});
   });
}

// comment.js by mg12 - http://www.neoease.com/
(function() {
    function $$$(id) { return document.getElementById(id); }
    function setStyleDisplay(id, status) {	$$$(id).style.display = status; }

    window['MGJS'] = {};
    window['MGJS']['$$$'] = $$$;
    window['MGJS']['setStyleDisplay'] = setStyleDisplay;

})();


(function() {
  function quote(authorId, commentId, commentBodyId, commentBox) {
	var author = MGJS.$$$(authorId).innerHTML;
	var comment = MGJS.$$$(commentBodyId).innerHTML;

	var insertStr = '<blockquote cite="#' + commentBodyId + '">';
	insertStr += '\n<strong><a href="#' + commentId + '">' + author.replace(/\t|\n|\r\n/g, "") + '</a> :</strong>';
	insertStr += comment.replace(/\t/g, "");
	insertStr += '</blockquote>\n';

	insertQuote(insertStr, commentBox);
  }

  function insertQuote(insertStr, commentBox) {
   if(MGJS.$$$(commentBox) && MGJS.$$$(commentBox).type == 'textarea') {
		field = MGJS.$$$(commentBox);

	} else {
		alert("The comment box does not exist!");
		return false;
	}

	if(document.selection) {
		field.focus();
		sel = document.selection.createRange();
		sel.text = insertStr;
		field.focus();

	} else if (field.selectionStart || field.selectionStart == '0') {
		var startPos = field.selectionStart;
		var endPos = field.selectionEnd;
		var cursorPos = startPos;
		field.value = field.value.substring(0, startPos)
					  + insertStr
					  + field.value.substring(endPos, field.value.length);
		cursorPos += insertStr.length;
		field.focus();
		field.selectionStart = cursorPos;
		field.selectionEnd = cursorPos;

	} else {
		field.value += insertStr;
		field.focus();
	}
  }

  window['MGJS_CMT'] = {};
  window['MGJS_CMT']['quote'] = quote;

})();