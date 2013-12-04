jQuery.fn.appendVal = function (txt) {
  return this.each(function () {
    this.value += txt;
  });
};



// cookie functions
jQuery.cookie = function (name, value, options) {
  if (typeof value != 'undefined') { // name and value given, set cookie
    options = options || {};
    if (value === null) {
      value = '';
      options = jQuery.extend({},
      options); // clone object since it's unexpected behavior if the expired property were changed
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
    } // NOTE Needed to parenthesize options.path and options.domain
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
        var cookie = jQuery.trim(cookies[i]); // Does this cookie string begin with the name we want?
        if (cookie.substring(0, name.length + 1) == (name + '=')) {
          cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
          break;
        }
      }
    }
    return cookieValue;
  }
};

// optimized minitabs
(function (jQuery) {
  jQuery.fn.minitabs = function (options) {
    jQuery.fn.minitabs.defaults = {
      contentClass: '.sections',
      effect: 'top',
      speed: 333
    };
    var o = jQuery.extend({},
    jQuery.fn.minitabs.defaults, options);
    return this.each(function () {
      var $tabs = jQuery(this); // check for the active tab cookie
      var cookieID = $tabs.attr('id');
      var cookieState = jQuery.cookie(cookieID); // hide all sections
      $tabs.find(o.contentClass + " >div:gt(0)").hide();
      if (cookieState != null) { // if we have a cookie then show the section according to its value
        $tabs.find('li.' + cookieState).addClass("active");
        var link = $tabs.find('li.' + cookieState + ' a');
        var section = link.attr('href');
        $tabs.find(o.contentClass + ' div' + section).show();
      } else { // if not, show the 1st section
        $tabs.find('ul:first').find('li:first').addClass("active");
        $tabs.find(o.contentClass + ' div:first').show();
      }
      $tabs.find("ul>li>a").click(function () {
        $tabs.find('ul>li').removeClass("active");
        var cookieValue = jQuery(this).parent('li').attr("class");
        jQuery.cookie(cookieID, cookieValue, {
          path: '/'
        });
        jQuery(this).parent('li').addClass("active");
        jQuery(this).blur();
        var re = /([_\-\w]+$)/i;
        var target = jQuery('#' + re.exec(this.href)[1]);
        $tabs.find(o.contentClass + " >div").hide();
        target.css({
          opacity: 0,
          top: -20
        }).show().animate({
          opacity: 1,
          top: 0
        },
        o.speed);
        return false;
      })
    });
  };
})(jQuery);






// jQuery Slider Plugin
// Egor Khmelev - http://blog.egorkhmelev.com/ - hmelyoff@gmail.com

(function(){

  // Simple Inheritance
  Function.prototype.inheritFrom = function(BaseClass, oOverride){
  	var Inheritance = function() {};
  	Inheritance.prototype = BaseClass.prototype;
  	this.prototype = new Inheritance();
  	this.prototype.constructor = this;
  	this.prototype.baseConstructor = BaseClass;
  	this.prototype.superClass = BaseClass.prototype;

  	if(oOverride){
  		for(var i in oOverride) {
  			this.prototype[i] = oOverride[i];
  		}
  	}
  };

  // Format numbers
  Number.prototype.nice=function(iRoundBase){
  	var re=/^(-)?(\d+)([\.,](\d+))?$/;
  	var iNum=Number(this);
  	var sNum=String(iNum);
  	var aMatches;
  	var sDecPart='';
  	var sTSeparator=' ';
  	if((aMatches = sNum.match(re))){
  		var sIntPart=aMatches[2];
  		var iDecPart=(aMatches[4]) ? Number('0.'+aMatches[4]) : 0;
  		if(iDecPart){
  			var iRF=Math.pow(10, (iRoundBase) ? iRoundBase : 2);
  			iDecPart=Math.round(iDecPart*iRF);
  			sNewDecPart=String(iDecPart);
  			sDecPart = sNewDecPart;
  			if(sNewDecPart.length < iRoundBase){
  				var iDiff = iRoundBase-sNewDecPart.length;
  				for (var i=0; i < iDiff; i++) {
  					sDecPart = "0" + sDecPart;
  				};
  			}
  			sDecPart = "," + sDecPart;
  		} else {
  			if(iRoundBase && iRoundBase != 0){
  				for (var i=0; i < iRoundBase; i++) {
  					sDecPart += "0";
  				};
  				sDecPart = "," + sDecPart;
  			}
  		}
  		var sResult;
  		if(Number(sIntPart) < 1000){
  			sResult = sIntPart+sDecPart;
  		}else{
  			var sNewNum='';
  			var i;
  			for(i=1; i*3<sIntPart.length; i++)
  				sNewNum=sTSeparator+sIntPart.substring(sIntPart.length - i*3, sIntPart.length - (i-1)*3)+sNewNum;
  			sResult = sIntPart.substr(0, 3 - i*3 + sIntPart.length)+sNewNum+sDecPart;
  		}
  		if(aMatches[1])
  			return '-'+sResult;
  		else
  			return sResult;
  	}
  	else{
  		return sNum;
  	}
  };


})();


// Simple JavaScript Templating
// John Resig - http://ejohn.org/ - MIT Licensed

(function(){
  var cache = {};

  this.tmpl = function tmpl(str, data){
    // Figure out if we're getting a template, or if we need to
    // load the template - and be sure to cache the result.
    var fn = !(/\W/).test(str) ?
      cache[str] = cache[str] ||
        tmpl(str) :

      // Generate a reusable function that will serve as a template
      // generator (and which will be cached).
      new Function("obj",
        "var p=[],print=function(){p.push.apply(p,arguments);};" +

        // Introduce the data as local variables using with(){}
        "with(obj){p.push('" +

        // Convert the template into pure JavaScript
        str
          .replace(/[\r\t\n]/g, " ")
          .split("<%").join("\t")
          .replace(/((^|%>)[^\t]*)'/g, "$1\r")
          .replace(/\t=(.*?)%>/g, "',$1,'")
          .split("\t").join("');")
          .split("%>").join("p.push('")
          .split("\r").join("\\'")
      + "');}return p.join('');");

    // Provide some basic currying to the user
    return data ? fn( data ) : fn;
  };
})();


// Draggable Class
// Egor Khmelev - http://blog.egorkhmelev.com/

(function( $ ){

  this.Draggable = function(){
  	this._init.apply( this, arguments );
  };

  Draggable.prototype = {
  	// Methods for re-init in child class
  	oninit: function(){},
  	events: function(){},
  	onmousedown: function(){
  		this.ptr.css({ position: "absolute" });
  	},
  	onmousemove: function( evt, x, y ){
  		this.ptr.css({ left: x, top: y });
  	},
  	onmouseup: function(){},

  	isDefault: {
  		drag: false,
  		clicked: false,
  		toclick: true,
  		mouseup: false
  	},

  	_init: function(){
  		if( arguments.length > 0 ){
  			this.ptr = $(arguments[0]);
  			this.outer = $(".draggable-outer");

  			this.is = {};
  			$.extend( this.is, this.isDefault );

  			var _offset = this.ptr.offset();
  			this.d = {
  				left: _offset.left,
  				top: _offset.top,
  				width: this.ptr.width(),
  				height: this.ptr.height()
  			};

  			this.oninit.apply( this, arguments );

  			this._events();
  		}
  	},
  	_events: function(){
  		var oThis = this;

  		$(document)
  			.mousemove(function( evt ){
  				if( oThis.is.drag ){
  					oThis._mousemove( evt );
  					return false;
  				}
  			})
  			.mouseup(function( evt ){
  				oThis._mouseup( evt );
  			})
  			.bind("dragstart", function(){
  				return false;
  			});

  		this.ptr
  			.mousedown(function( evt ){
  				oThis._mousedown( evt );
  				return false;
  			})
  			.mouseup(function( evt ){
  				oThis._mouseup( evt );
  			});

  		this.ptr.find("a")
  			.click(function(){
  				oThis.is.clicked = true;

  				if( !oThis.is.toclick ){
  					oThis.is.toclick = true;
  					return false;
  				}
  			})
  			.mousedown(function( evt ){
  				oThis._mousedown( evt );
  				return false;
  			})
  			.bind("dragstart", function(){
  				return false;
  			});


  		this.events();
  	},
  	_mousedown: function( evt ){
  		this.is.drag = true;
  		this.is.clicked = false;
  		this.is.mouseup = false;

  		var _offset = this.ptr.offset();
  		this.cx = evt.pageX - _offset.left;
  		this.cy = evt.pageY - _offset.top;

  		$.extend(this.d, {
  			left: _offset.left,
  			top: _offset.top,
  			width: this.ptr.width(),
  			height: this.ptr.height()
  		});

  		if( this.outer && this.outer.get(0) ){
  			this.outer.css({ height: Math.max(this.outer.height(), $(document.body).height()), overflow: "hidden" });
  		}

  		this.onmousedown( evt );
  	},
  	_mousemove: function( evt ){
  		this.is.toclick = false;
  		this.onmousemove( evt, evt.pageX - this.cx, evt.pageY - this.cy );
  	},
  	_mouseup: function( evt ){
  		var oThis = this;

  		if( this.is.drag ){
  			this.is.drag = false;

  			if( this.outer && this.outer.get(0) ){

  				if( $.browser.mozilla ){
  					this.outer.css({ overflow: "hidden" });
  				} else {
  					this.outer.css({ overflow: "visible" });
  				}

  				if( $.browser.msie && $.browser.version == '6.0' ){
  					this.outer.css({ height: "100%" });
  				} else {
  					this.outer.css({ height: "auto" });
  				}
  			}

  			this.onmouseup( evt );
  		}
  	}

  };

})( jQuery );



// jQuery Slider (Safari)
// Egor Khmelev - http://blog.egorkhmelev.com/

(function( $ ) {

	$.slider = function( node, settings ){
	  node.sliderHandler = new jSlider( node, settings );
	};

	$.fn.slider = function( settings ){
		return this.each(function(){
		  $.slider( this, settings );
		});
	};

  var OPTIONS = {

    settings: {
      from: 1,
      to: 10,
      step: 1,
      smooth: true,
      limits: true,
      round: 0,
      value: "5;7",
      dimension: ""
    },

    className: "jslider",
    selector: ".jslider-",

    template: tmpl(
      '<div class="<%=className%>">' +
        '<table><tr><td>' +
          '<div class="<%=className%>-bg">' +
            '<i class="l"><i></i></i><i class="r"><i></i></i>' +
            '<i class="v"><i></i></i>' +
          '</div>' +

          '<div class="<%=className%>-pointer"><i></i></div>' +
          '<div class="<%=className%>-pointer <%=className%>-pointer-to"><i></i></div>' +

          '<div class="<%=className%>-label"><span><%=settings.from%></span></div>' +
          '<div class="<%=className%>-label <%=className%>-label-to"><span><%=settings.to%></span><%=settings.dimension%></div>' +

          '<div class="<%=className%>-value"><span></span><%=settings.dimension%></div>' +
          '<div class="<%=className%>-value <%=className%>-value-to"><span></span><%=settings.dimension%></div>' +

          '<div class="<%=className%>-scale"><%=scale%></div>'+

        '</td></tr></table>' +
      '</div>'
    )

  };

  this.jSlider = function(){
  	return this.init.apply( this, arguments );
  };

  jSlider.prototype = {
    init: function( node, settings ){
      this.settings = $.extend(true, {}, OPTIONS.settings, settings ? settings : {});

      // obj.sliderHandler = this;
      this.inputNode = $( node ).hide();

			this.settings.interval = this.settings.to-this.settings.from;
			this.settings.value = this.inputNode.attr("value");

			if( this.settings.calculate && $.isFunction( this.settings.calculate ) )
			  this.nice = this.settings.calculate;

			if( this.settings.onstatechange && $.isFunction( this.settings.onstatechange ) )
			  this.onstatechange = this.settings.onstatechange;

      this.is = {
        init: false
      };
			this.o = {};

      this.create();
    },

    onstatechange: function(){},

    create: function(){
      var $this = this;

      this.domNode = $( OPTIONS.template({
        className: OPTIONS.className,
        settings: {
          from: this.nice( this.settings.from ),
          to: this.nice( this.settings.to ),
          dimension: this.settings.dimension
        },
        scale: this.generateScale()
      }) );

      this.inputNode.after( this.domNode );
      this.drawScale();

      // set skin class
      if( this.settings.skin && this.settings.skin.length > 0 )
        this.domNode.addDependClass(this.settings.skin, "_");

			this.sizes = {
			  domWidth: this.domNode.width(),
			  domOffset: this.domNode.offset()
			};

      // find some objects
      $.extend(this.o, {
        pointers: {},
        labels: {
          0: {
            o: this.domNode.find(OPTIONS.selector + "value").not(OPTIONS.selector + "value-to")
          },
          1: {
            o: this.domNode.find(OPTIONS.selector + "value").filter(OPTIONS.selector + "value-to")
          }
        },
        limits: {
          0: this.domNode.find(OPTIONS.selector + "label").not(OPTIONS.selector + "label-to"),
          1: this.domNode.find(OPTIONS.selector + "label").filter(OPTIONS.selector + "label-to")
        }
      });

      $.extend(this.o.labels[0], {
        value: this.o.labels[0].o.find("span")
      });

      $.extend(this.o.labels[1], {
        value: this.o.labels[1].o.find("span")
      });


      if( !$this.settings.value.split(";")[1] ){
        this.settings.single = true;
        this.domNode.addDependClass("single");
      }

      if( !$this.settings.limits )
        this.domNode.addDependClass("limitless");

      this.domNode.find(OPTIONS.selector + "pointer").each(function( i ){
        var value = $this.settings.value.split(";")[i];
        if( value ){
          $this.o.pointers[i] = new jSliderPointer( this, i, $this );

          var prev = $this.settings.value.split(";")[i-1];
          if( prev && new Number(value) < new Number(prev) ) value = prev;

          value = value < $this.settings.from ? $this.settings.from : value;
          value = value > $this.settings.to ? $this.settings.to : value;

          $this.o.pointers[i].set( value );
        }
      });

      this.o.value = this.domNode.find(".v");
      this.is.init = true;

      $.each(this.o.pointers, function(i){
        $this.redraw(this);
      });

      (function(self){
        $(window).resize(function(){
          self.onresize();
        });
      })(this);

    },

    generateScale: function(){
      if( this.settings.scale && this.settings.scale.length > 0 ){
        var str = "";
        var s = this.settings.scale;
        var prc = (((100/(s.length-1)))*10)/10; // Math.round() change
        for( var i=0; i < s.length; i++ ){
          str += '<span style="left: ' +  Math.round(i*prc) + '%">' + ( s[i] != '|' ? '<ins>' + s[i] + '</ins>' : '' ) + '</span>';
        };
        return str;
      } else return "";

      return "";
    },

    drawScale: function(){
      this.domNode.find(OPTIONS.selector + "scale span ins").each(function(){
        $(this).css({ marginLeft: -$(this).outerWidth()/2 });
      });
    },

    onresize: function(){
      var self = this;
			this.sizes = {
			  domWidth: this.domNode.width(),
			  domOffset: this.domNode.offset()
			};

      $.each(this.o.pointers, function(i){
        self.redraw(this);
      });
    },

    limits: function( x, pointer ){
  	  // smooth
  	  if( !this.settings.smooth ){
  	    var step = this.settings.step*100 / ( this.settings.interval );
  	    x = Math.round( x/step ) * step;
  	  }

  	  var another = this.o.pointers[1-pointer.uid];
  	  if( another && pointer.uid && x < another.value.prc ) x = another.value.prc;
  	  if( another && !pointer.uid && x > another.value.prc ) x = another.value.prc;

      // base limit
  	  if( x < 0 ) x = 0;
  	  if( x > 100 ) x = 100;

      return Math.round( x*10 ) / 10;
    },

    redraw: function( pointer ){
      if( !this.is.init ) return false;

      this.setValue();

      // redraw range line
      if( this.o.pointers[0] && this.o.pointers[1] )
        this.o.value.css({ left: this.o.pointers[0].value.prc + "%", width: ( this.o.pointers[1].value.prc - this.o.pointers[0].value.prc ) + "%" });

      this.o.labels[pointer.uid].value.html(
        this.nice(
          pointer.value.origin
        )
      );

      // redraw position of labels
      this.redrawLabels( pointer );

    },

    redrawLabels: function( pointer ){

      function setPosition( label, sizes, prc ){
    	  sizes.margin = -sizes.label/2;

        // left limit
        label_left = sizes.border + sizes.margin;
        if( label_left < 0 )
          sizes.margin -= label_left;

        // right limit
        if( sizes.border+sizes.label / 2 > self.sizes.domWidth ){
          sizes.margin = 0;
          sizes.right = true;
        } else
          sizes.right = false;

        label.o.css({ left: prc + "%", marginLeft: sizes.margin, right: "auto" });
        if( sizes.right ) label.o.css({ left: "auto", right: 0 });
        return sizes;
      }

      var self = this;
  	  var label = this.o.labels[pointer.uid];
  	  var prc = pointer.value.prc;

  	  var sizes = {
  	    label: label.o.outerWidth(),
  	    right: false,
  	    border: ( prc * this.sizes.domWidth ) / 100
  	  };

      //console.log(this.o.pointers[1-pointer.uid])
      if( !this.settings.single ){
        // glue if near;
        var another = this.o.pointers[1-pointer.uid];
      	var another_label = this.o.labels[another.uid];

        switch( pointer.uid ){
          case 0:
            if( sizes.border+sizes.label / 2 > another_label.o.offset().left-this.sizes.domOffset.left ){
              another_label.o.css({ visibility: "hidden" });
          	  another_label.value.html( this.nice( another.value.origin ) );

            	label.o.css({ visibility: "visible" });

            	prc = ( another.value.prc - prc ) / 2 + prc;
            	if( another.value.prc != pointer.value.prc ){
            	  label.value.html( this.nice(pointer.value.origin) + "&nbsp;&ndash;&nbsp;" + this.nice(another.value.origin) );
              	sizes.label = label.o.outerWidth();
              	sizes.border = ( prc * this.sizes.domWidth ) / 100;
              }
            } else {
            	another_label.o.css({ visibility: "visible" });
            }
            break;

          case 1:
            if( sizes.border - sizes.label / 2 < another_label.o.offset().left - this.sizes.domOffset.left + another_label.o.outerWidth() ){
              another_label.o.css({ visibility: "hidden" });
          	  another_label.value.html( this.nice(another.value.origin) );

            	label.o.css({ visibility: "visible" });

            	prc = ( prc - another.value.prc ) / 2 + another.value.prc;
            	if( another.value.prc != pointer.value.prc ){
            	  label.value.html( this.nice(another.value.origin) + "&nbsp;&ndash;&nbsp;" + this.nice(pointer.value.origin) );
              	sizes.label = label.o.outerWidth();
              	sizes.border = ( prc * this.sizes.domWidth ) / 100;
              }
            } else {
              another_label.o.css({ visibility: "visible" });
            }
            break;
        }
      }

      sizes = setPosition( label, sizes, prc );

      /* draw second label */
      if( another_label ){
        var sizes = {
    	    label: another_label.o.outerWidth(),
    	    right: false,
    	    border: ( another.value.prc * this.sizes.domWidth ) / 100
    	  };
        sizes = setPosition( another_label, sizes, another.value.prc );
      }

	    this.redrawLimits();
    },

    redrawLimits: function(){
  	  if( this.settings.limits ){

        var limits = [ true, true ];

        for( key in this.o.pointers ){

          if( !this.settings.single || key == 0 ){

        	  var pointer = this.o.pointers[key];
            var label = this.o.labels[pointer.uid];
            var label_left = label.o.offset().left - this.sizes.domOffset.left;

        	  var limit = this.o.limits[0];
            if( label_left < limit.outerWidth() )
              limits[0] = false;

        	  var limit = this.o.limits[1];
        	  if( label_left + label.o.outerWidth() > this.sizes.domWidth - limit.outerWidth() )
        	    limits[1] = false;
        	}

        };

        for( var i=0; i < limits.length; i++ ){
          if( limits[i] )
            this.o.limits[i].fadeIn("fast");
          else
            this.o.limits[i].fadeOut("fast");
        };

  	  }
    },

    setValue: function(){
      var value = this.getValue();
      this.inputNode.attr( "value", value );
      this.onstatechange.call( this, value );
    },
    getValue: function(){
      if(!this.is.init) return false;
      var $this = this;

      var value = "";
      $.each( this.o.pointers, function(i){
        if( this.value.prc != undefined && !isNaN(this.value.prc) ) value += (i > 0 ? ";" : "") + $this.prcToValue( this.value.prc );
      });
      return value;
    },
    prcToValue: function( prc ){

  	  if( this.settings.heterogeneity && this.settings.heterogeneity.length > 0 ){
    	  var h = this.settings.heterogeneity;

    	  var _start = 0;
    	  var _from = this.settings.from;

    	  for( var i=0; i <= h.length; i++ ){
    	    if( h[i] ) var v = h[i].split("/");
    	    else       var v = [100, this.settings.to];

    	    v[0] = new Number(v[0]);
    	    v[1] = new Number(v[1]);

    	    if( prc >= _start && prc <= v[0] ) {
    	      var value = _from + ( (prc-_start) * (v[1]-_from) ) / (v[0]-_start);
    	    }

    	    _start = v[0];
    	    _from = v[1];
    	  };

  	  } else {
        var value = this.settings.from + ( prc * this.settings.interval ) / 100;
  	  }

      return this.round( value );
    },

  	round: function( value ){
	    value = Math.round( value / this.settings.step ) * this.settings.step;
  		if( this.settings.round ) value = Math.round( value * Math.pow(10, this.settings.round) ) / Math.pow(10, this.settings.round);
  		else value = Math.round( value );
  		return value;
  	},

  	nice: function( value ){
  		value = value.toString().replace(/,/gi, ".");
  		value = value.toString().replace(/ /gi, "");
  		if( Number.prototype.nice )
  		  return (new Number(value)).nice(this.settings.round).replace(/-/gi, "&minus;");
  		else
  		  return new Number(value);
  	}

  };

  function jSliderPointer(){
  	this.baseConstructor.apply(this, arguments);
  }

  jSliderPointer.inheritFrom(Draggable, {
    oninit: function( ptr, id, _constructor ){
      this.uid = id;
      this.parent = _constructor;
      this.value = {};
      this.settings = this.parent.settings;
    },
  	onmousedown: function(evt){
  	  this._parent = {
  	    offset: this.parent.domNode.offset(),
  	    width: this.parent.domNode.width()
  	  };
  	  this.ptr.addDependClass("hover");
  	},
  	onmousemove: function(evt, x){
  	  this._set(this.calc(evt.pageX));
  	},
  	onmouseup: function(evt){
  	  this._set(this.calc(evt.pageX));

  	  if( this.parent.settings.callback && $.isFunction(this.parent.settings.callback) )
  	    this.parent.settings.callback.call( this.parent, this.parent.getValue() );

  	  this.ptr.removeDependClass("hover");
  	},

  	limits: function(x){
  	  x = this.parent.limits(x, this);
  	  return x;
  	},

  	calc: function(coords){
  	  var x = this.limits(((coords-this._parent.offset.left)*100)/this._parent.width);
  	  return x;
  	},
  	set: function(value){
  	  this.value.origin = this.parent.round(value);

  	  if( this.settings.heterogeneity && this.settings.heterogeneity.length > 0 ){
    	  var h = this.settings.heterogeneity;

    	  var _start = 0;
    	  var _from = this.settings.from;

    	  for (var i=0; i <= h.length; i++) {
    	    if(h[i]) var v = h[i].split("/");
    	    else     var v = [100, this.settings.to];
    	    v[0] = new Number(v[0]); v[1] = new Number(v[1]);

    	    if(value >= _from && value <= v[1]){
    	      var prc = this.limits(_start + (value-_from)*(v[0]-_start)/(v[1]-_from));
    	    }

    	    _start = v[0]; _from = v[1];
    	  };

  	  } else {
    	  var prc = this.limits((value-this.settings.from)*100/this.settings.interval);
  	  }

  	  this._set( prc, true );
  	},
  	_set: function(prc, origin){
  	  if(!origin)
  	    this.value.origin = this.parent.prcToValue(prc);
  	  this.value.prc = prc;
  		this.ptr.css({ left: prc + "%" });
  	  this.parent.redraw(this);
  	}

  });


})(jQuery);





/*
 * Depend Class v0.1b : attach class based on first class in list of current element
 * File: jquery.dependClass.js
 * Copyright (c) 2009 Egor Hmelyoff, hmelyoff@gmail.com
 */


(function($) {
	// Init plugin function
	$.baseClass = function(obj){
	  obj = $(obj);
	  return obj.get(0).className.match(/([^ ]+)/)[1];
	};

	$.fn.addDependClass = function(className, delimiter){
		var options = {
		  delimiter: delimiter ? delimiter : '-'
		}
		return this.each(function(){
		  var baseClass = $.baseClass(this);
		  if(baseClass)
    		$(this).addClass(baseClass + options.delimiter + className);
		});
	};

	$.fn.removeDependClass = function(className, delimiter){
		var options = {
		  delimiter: delimiter ? delimiter : '-'
		}
		return this.each(function(){
		  var baseClass = $.baseClass(this);
		  if(baseClass)
    		$(this).removeClass(baseClass + options.delimiter + className);
		});
	};

	$.fn.toggleDependClass = function(className, delimiter){
		var options = {
		  delimiter: delimiter ? delimiter : '-'
		}
		return this.each(function(){
		  var baseClass = $.baseClass(this);
		  if(baseClass)
		    if($(this).is("." + baseClass + options.delimiter + className))
    		  $(this).removeClass(baseClass + options.delimiter + className);
    		else
    		  $(this).addClass(baseClass + options.delimiter + className);
		});
	};

	// end of closure
})(jQuery);






/**
 *
 * Color picker
 * Author: Stefan Petre www.eyecon.ro
 *
 * Dual licensed under the MIT and GPL licenses
 *
 */
(function ($) {
	var ColorPicker = function () {
		var
			ids = {},
			inAction,
			charMin = 65,
			visible,
			tpl = '<div class="colorpicker"><div class="colorpicker_color"><div><div></div></div></div><div class="colorpicker_hue"><div></div></div><div class="colorpicker_new_color"></div><div class="colorpicker_current_color"></div><div class="colorpicker_hex"><input type="text" maxlength="6" size="6" /></div><div class="colorpicker_rgb_r colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_rgb_g colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_rgb_b colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_h colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_s colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_b colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_submit"></div></div>',
			defaults = {
				eventName: 'click',
				onShow: function () {},
				onBeforeShow: function(){},
				onHide: function () {},
				onChange: function () {},
				onSubmit: function () {},
				color: 'ff0000',
				livePreview: true,
				flat: false
			},
			fillRGBFields = function  (hsb, cal) {
				var rgb = HSBToRGB(hsb);
				$(cal).data('colorpicker').fields
					.eq(1).val(rgb.r).end()
					.eq(2).val(rgb.g).end()
					.eq(3).val(rgb.b).end();
			},
			fillHSBFields = function  (hsb, cal) {
				$(cal).data('colorpicker').fields
					.eq(4).val(hsb.h).end()
					.eq(5).val(hsb.s).end()
					.eq(6).val(hsb.b).end();
			},
			fillHexFields = function (hsb, cal) {
				$(cal).data('colorpicker').fields
					.eq(0).val(HSBToHex(hsb)).end();
			},
			setSelector = function (hsb, cal) {
				$(cal).data('colorpicker').selector.css('backgroundColor', '#' + HSBToHex({h: hsb.h, s: 100, b: 100}));
				$(cal).data('colorpicker').selectorIndic.css({
					left: parseInt(150 * hsb.s/100, 10),
					top: parseInt(150 * (100-hsb.b)/100, 10)
				});
			},
			setHue = function (hsb, cal) {
				$(cal).data('colorpicker').hue.css('top', parseInt(150 - 150 * hsb.h/360, 10));
			},
			setCurrentColor = function (hsb, cal) {
				$(cal).data('colorpicker').currentColor.css('backgroundColor', '#' + HSBToHex(hsb));
			},
			setNewColor = function (hsb, cal) {
				$(cal).data('colorpicker').newColor.css('backgroundColor', '#' + HSBToHex(hsb));
			},
			keyDown = function (ev) {
				var pressedKey = ev.charCode || ev.keyCode || -1;
				if ((pressedKey > charMin && pressedKey <= 90) || pressedKey == 32) {
					return false;
				}
				var cal = $(this).parent().parent();
				if (cal.data('colorpicker').livePreview === true) {
					change.apply(this);
				}
			},
			change = function (ev) {
				var cal = $(this).parent().parent(), col;
				if (this.parentNode.className.indexOf('_hex') > 0) {
					cal.data('colorpicker').color = col = HexToHSB(fixHex(this.value));
				} else if (this.parentNode.className.indexOf('_hsb') > 0) {
					cal.data('colorpicker').color = col = fixHSB({
						h: parseInt(cal.data('colorpicker').fields.eq(4).val(), 10),
						s: parseInt(cal.data('colorpicker').fields.eq(5).val(), 10),
						b: parseInt(cal.data('colorpicker').fields.eq(6).val(), 10)
					});
				} else {
					cal.data('colorpicker').color = col = RGBToHSB(fixRGB({
						r: parseInt(cal.data('colorpicker').fields.eq(1).val(), 10),
						g: parseInt(cal.data('colorpicker').fields.eq(2).val(), 10),
						b: parseInt(cal.data('colorpicker').fields.eq(3).val(), 10)
					}));
				}
				if (ev) {
					fillRGBFields(col, cal.get(0));
					fillHexFields(col, cal.get(0));
					fillHSBFields(col, cal.get(0));
				}
				setSelector(col, cal.get(0));
				setHue(col, cal.get(0));
				setNewColor(col, cal.get(0));
				cal.data('colorpicker').onChange.apply(cal, [col, HSBToHex(col), HSBToRGB(col), cal.data('colorpicker').el]);
			},
			blur = function (ev) {
				var cal = $(this).parent().parent();
				cal.data('colorpicker').fields.parent().removeClass('colorpicker_focus');
			},
			focus = function () {
				charMin = this.parentNode.className.indexOf('_hex') > 0 ? 70 : 65;
				$(this).parent().parent().data('colorpicker').fields.parent().removeClass('colorpicker_focus');
				$(this).parent().addClass('colorpicker_focus');
			},
			downIncrement = function (ev) {
				var field = $(this).parent().find('input').focus();
				var current = {
					el: $(this).parent().addClass('colorpicker_slider'),
					max: this.parentNode.className.indexOf('_hsb_h') > 0 ? 360 : (this.parentNode.className.indexOf('_hsb') > 0 ? 100 : 255),
					y: ev.pageY,
					field: field,
					val: parseInt(field.val(), 10),
					preview: $(this).parent().parent().data('colorpicker').livePreview
				};
				$(document).bind('mouseup', current, upIncrement);
				$(document).bind('mousemove', current, moveIncrement);
			},
			moveIncrement = function (ev) {
				ev.data.field.val(Math.max(0, Math.min(ev.data.max, parseInt(ev.data.val + ev.pageY - ev.data.y, 10))));
				if (ev.data.preview) {
					change.apply(ev.data.field.get(0), [true]);
				}
				return false;
			},
			upIncrement = function (ev) {
				change.apply(ev.data.field.get(0), [true]);
				ev.data.el.removeClass('colorpicker_slider').find('input').focus();
				$(document).unbind('mouseup', upIncrement);
				$(document).unbind('mousemove', moveIncrement);
				return false;
			},
			downHue = function (ev) {
				var current = {
					cal: $(this).parent(),
					y: $(this).offset().top
				};
				current.preview = current.cal.data('colorpicker').livePreview;
				$(document).bind('mouseup', current, upHue);
				$(document).bind('mousemove', current, moveHue);
			},
			moveHue = function (ev) {
				change.apply(
					ev.data.cal.data('colorpicker')
						.fields
						.eq(4)
						.val(parseInt(360*(150 - Math.max(0,Math.min(150,(ev.pageY - ev.data.y))))/150, 10))
						.get(0),
					[ev.data.preview]
				);
				return false;
			},
			upHue = function (ev) {
				fillRGBFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
				fillHexFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
				$(document).unbind('mouseup', upHue);
				$(document).unbind('mousemove', moveHue);
				return false;
			},
			downSelector = function (ev) {
				var current = {
					cal: $(this).parent(),
					pos: $(this).offset()
				};
				current.preview = current.cal.data('colorpicker').livePreview;
				$(document).bind('mouseup', current, upSelector);
				$(document).bind('mousemove', current, moveSelector);
			},
			moveSelector = function (ev) {
				change.apply(
					ev.data.cal.data('colorpicker')
						.fields
						.eq(6)
						.val(parseInt(100*(150 - Math.max(0,Math.min(150,(ev.pageY - ev.data.pos.top))))/150, 10))
						.end()
						.eq(5)
						.val(parseInt(100*(Math.max(0,Math.min(150,(ev.pageX - ev.data.pos.left))))/150, 10))
						.get(0),
					[ev.data.preview]
				);
				return false;
			},
			upSelector = function (ev) {
				fillRGBFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
				fillHexFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
				$(document).unbind('mouseup', upSelector);
				$(document).unbind('mousemove', moveSelector);
				return false;
			},
			enterSubmit = function (ev) {
				$(this).addClass('colorpicker_focus');
			},
			leaveSubmit = function (ev) {
				$(this).removeClass('colorpicker_focus');
			},
			clickSubmit = function (ev) {
				var cal = $(this).parent();
				var col = cal.data('colorpicker').color;
				cal.data('colorpicker').origColor = col;
				setCurrentColor(col, cal.get(0));
				cal.data('colorpicker').onSubmit(col, HSBToHex(col), HSBToRGB(col), cal.data('colorpicker').el);
			},
			show = function (ev) {
				var cal = $('#' + $(this).data('colorpickerId'));
				cal.data('colorpicker').onBeforeShow.apply(this, [cal.get(0)]);
				var pos = $(this).offset();
				var viewPort = getViewport();
				var top = pos.top + this.offsetHeight;
				var left = pos.left;
				if (top + 176 > viewPort.t + viewPort.h) {
					top -= this.offsetHeight + 176;
				}
				if (left + 356 > viewPort.l + viewPort.w) {
					left -= 356;
				}
				cal.css({left: left + 'px', top: top + 'px'});
				if (cal.data('colorpicker').onShow.apply(this, [cal.get(0)]) != false) {
					cal.show();
				}
				$(document).bind('mousedown', {cal: cal}, hide);
				return false;
			},
			hide = function (ev) {
				if (!isChildOf(ev.data.cal.get(0), ev.target, ev.data.cal.get(0))) {
					if (ev.data.cal.data('colorpicker').onHide.apply(this, [ev.data.cal.get(0)]) != false) {
						ev.data.cal.hide();
					}
					$(document).unbind('mousedown', hide);
				}
			},
			isChildOf = function(parentEl, el, container) {
				if (parentEl == el) {
					return true;
				}
				if (parentEl.contains) {
					return parentEl.contains(el);
				}
				if ( parentEl.compareDocumentPosition ) {
					return !!(parentEl.compareDocumentPosition(el) & 16);
				}
				var prEl = el.parentNode;
				while(prEl && prEl != container) {
					if (prEl == parentEl)
						return true;
					prEl = prEl.parentNode;
				}
				return false;
			},
			getViewport = function () {
				var m = document.compatMode == 'CSS1Compat';
				return {
					l : window.pageXOffset || (m ? document.documentElement.scrollLeft : document.body.scrollLeft),
					t : window.pageYOffset || (m ? document.documentElement.scrollTop : document.body.scrollTop),
					w : window.innerWidth || (m ? document.documentElement.clientWidth : document.body.clientWidth),
					h : window.innerHeight || (m ? document.documentElement.clientHeight : document.body.clientHeight)
				};
			},
			fixHSB = function (hsb) {
				return {
					h: Math.min(360, Math.max(0, hsb.h)),
					s: Math.min(100, Math.max(0, hsb.s)),
					b: Math.min(100, Math.max(0, hsb.b))
				};
			},
			fixRGB = function (rgb) {
				return {
					r: Math.min(255, Math.max(0, rgb.r)),
					g: Math.min(255, Math.max(0, rgb.g)),
					b: Math.min(255, Math.max(0, rgb.b))
				};
			},
			fixHex = function (hex) {
				var len = 6 - hex.length;
				if (len > 0) {
					var o = [];
					for (var i=0; i<len; i++) {
						o.push('0');
					}
					o.push(hex);
					hex = o.join('');
				}
				return hex;
			},
			HexToRGB = function (hex) {
				var hex = parseInt(((hex.indexOf('#') > -1) ? hex.substring(1) : hex), 16);
				return {r: hex >> 16, g: (hex & 0x00FF00) >> 8, b: (hex & 0x0000FF)};
			},
			HexToHSB = function (hex) {
				return RGBToHSB(HexToRGB(hex));
			},
			RGBToHSB = function (rgb) {
				var hsb = {
					h: 0,
					s: 0,
					b: 0
				};
				var min = Math.min(rgb.r, rgb.g, rgb.b);
				var max = Math.max(rgb.r, rgb.g, rgb.b);
				var delta = max - min;
				hsb.b = max;
				if (max != 0) {

				}
				hsb.s = max != 0 ? 255 * delta / max : 0;
				if (hsb.s != 0) {
					if (rgb.r == max) {
						hsb.h = (rgb.g - rgb.b) / delta;
					} else if (rgb.g == max) {
						hsb.h = 2 + (rgb.b - rgb.r) / delta;
					} else {
						hsb.h = 4 + (rgb.r - rgb.g) / delta;
					}
				} else {
					hsb.h = -1;
				}
				hsb.h *= 60;
				if (hsb.h < 0) {
					hsb.h += 360;
				}
				hsb.s *= 100/255;
				hsb.b *= 100/255;
				return hsb;
			},
			HSBToRGB = function (hsb) {
				var rgb = {};
				var h = Math.round(hsb.h);
				var s = Math.round(hsb.s*255/100);
				var v = Math.round(hsb.b*255/100);
				if(s == 0) {
					rgb.r = rgb.g = rgb.b = v;
				} else {
					var t1 = v;
					var t2 = (255-s)*v/255;
					var t3 = (t1-t2)*(h%60)/60;
					if(h==360) h = 0;
					if(h<60) {rgb.r=t1;	rgb.b=t2; rgb.g=t2+t3}
					else if(h<120) {rgb.g=t1; rgb.b=t2;	rgb.r=t1-t3}
					else if(h<180) {rgb.g=t1; rgb.r=t2;	rgb.b=t2+t3}
					else if(h<240) {rgb.b=t1; rgb.r=t2;	rgb.g=t1-t3}
					else if(h<300) {rgb.b=t1; rgb.g=t2;	rgb.r=t2+t3}
					else if(h<360) {rgb.r=t1; rgb.g=t2;	rgb.b=t1-t3}
					else {rgb.r=0; rgb.g=0;	rgb.b=0}
				}
				return {r:Math.round(rgb.r), g:Math.round(rgb.g), b:Math.round(rgb.b)};
			},
			RGBToHex = function (rgb) {
				var hex = [
					rgb.r.toString(16),
					rgb.g.toString(16),
					rgb.b.toString(16)
				];
				$.each(hex, function (nr, val) {
					if (val.length == 1) {
						hex[nr] = '0' + val;
					}
				});
				return hex.join('');
			},
			HSBToHex = function (hsb) {
				return RGBToHex(HSBToRGB(hsb));
			},
			restoreOriginal = function () {
				var cal = $(this).parent();
				var col = cal.data('colorpicker').origColor;
				cal.data('colorpicker').color = col;
				fillRGBFields(col, cal.get(0));
				fillHexFields(col, cal.get(0));
				fillHSBFields(col, cal.get(0));
				setSelector(col, cal.get(0));
				setHue(col, cal.get(0));
				setNewColor(col, cal.get(0));
			};
		return {
			init: function (opt) {
				opt = $.extend({}, defaults, opt||{});
				if (typeof opt.color == 'string') {
					opt.color = HexToHSB(opt.color);
				} else if (opt.color.r != undefined && opt.color.g != undefined && opt.color.b != undefined) {
					opt.color = RGBToHSB(opt.color);
				} else if (opt.color.h != undefined && opt.color.s != undefined && opt.color.b != undefined) {
					opt.color = fixHSB(opt.color);
				} else {
					return this;
				}
				return this.each(function () {
					if (!$(this).data('colorpickerId')) {
						var options = $.extend({}, opt);
						options.origColor = opt.color;
						var id = 'collorpicker_' + parseInt(Math.random() * 1000);
						$(this).data('colorpickerId', id);
						var cal = $(tpl).attr('id', id);
						if (options.flat) {
							cal.appendTo(this).show();
						} else {
							cal.appendTo(document.body);
						}
						options.fields = cal
											.find('input')
												.bind('keyup', keyDown)
												.bind('change', change)
												.bind('blur', blur)
												.bind('focus', focus);
						cal
							.find('span').bind('mousedown', downIncrement).end()
							.find('>div.colorpicker_current_color').bind('click', restoreOriginal);
						options.selector = cal.find('div.colorpicker_color').bind('mousedown', downSelector);
						options.selectorIndic = options.selector.find('div div');
						options.el = this;
						options.hue = cal.find('div.colorpicker_hue div');
						cal.find('div.colorpicker_hue').bind('mousedown', downHue);
						options.newColor = cal.find('div.colorpicker_new_color');
						options.currentColor = cal.find('div.colorpicker_current_color');
						cal.data('colorpicker', options);
						cal.find('div.colorpicker_submit')
							.bind('mouseenter', enterSubmit)
							.bind('mouseleave', leaveSubmit)
							.bind('click', clickSubmit);
						fillRGBFields(options.color, cal.get(0));
						fillHSBFields(options.color, cal.get(0));
						fillHexFields(options.color, cal.get(0));
						setHue(options.color, cal.get(0));
						setSelector(options.color, cal.get(0));
						setCurrentColor(options.color, cal.get(0));
						setNewColor(options.color, cal.get(0));
						if (options.flat) {
							cal.css({
								position: 'relative',
								display: 'block'
							});
						} else {
							$(this).bind(options.eventName, show);
						}
					}
				});
			},
			showPicker: function() {
				return this.each( function () {
					if ($(this).data('colorpickerId')) {
						show.apply(this);
					}
				});
			},
			hidePicker: function() {
				return this.each( function () {
					if ($(this).data('colorpickerId')) {
						$('#' + $(this).data('colorpickerId')).hide();
					}
				});
			},
			setColor: function(col) {
				if (typeof col == 'string') {
					col = HexToHSB(col);
				} else if (col.r != undefined && col.g != undefined && col.b != undefined) {
					col = RGBToHSB(col);
				} else if (col.h != undefined && col.s != undefined && col.b != undefined) {
					col = fixHSB(col);
				} else {
					return this;
				}
				return this.each(function(){
					if ($(this).data('colorpickerId')) {
						var cal = $('#' + $(this).data('colorpickerId'));
						cal.data('colorpicker').color = col;
						cal.data('colorpicker').origColor = col;
						fillRGBFields(col, cal.get(0));
						fillHSBFields(col, cal.get(0));
						fillHexFields(col, cal.get(0));
						setHue(col, cal.get(0));
						setSelector(col, cal.get(0));
						setCurrentColor(col, cal.get(0));
						setNewColor(col, cal.get(0));
					}
				});
			}
		};
	}();
	$.fn.extend({
		ColorPicker: ColorPicker.init,
		ColorPickerHide: ColorPicker.hidePicker,
		ColorPickerShow: ColorPicker.showPicker,
		ColorPickerSetColor: ColorPicker.setColor
	});
})(jQuery);





/*
	jQuery TextAreaResizer plugin
	Created on 17th January 2008 by Ryan O'Dell
	Version 1.0.4

	Converted from Drupal -> textarea.js
	Found source: http://plugins.jquery.com/misc/textarea.js
	$Id: textarea.js,v 1.11.2.1 2007/04/18 02:41:19 drumm Exp $

	1.0.1 Updates to missing global 'var', added extra global variables, fixed multiple instances, improved iFrame support
	1.0.2 Updates according to textarea.focus
	1.0.3 Further updates including removing the textarea.focus and moving private variables to top
	1.0.4 Re-instated the blur/focus events, according to information supplied by dec


*/
(function(jQuery) {
	/* private variable "oHover" used to determine if you're still hovering over the same element */
	var textarea, staticOffset;  // added the var declaration for 'staticOffset' thanks to issue logged by dec.
	var iLastMousePos = 0;
	var iMin = 32;
	var grip;
	/* TextAreaResizer plugin */
	jQuery.fn.TextAreaResizer = function() {
		return this.each(function() {
		    textarea = jQuery(this).addClass('processed'), staticOffset = null;

			// 18-01-08 jQuery bind to pass data element rather than direct mousedown - Ryan O'Dell
		    // When wrapping the text area, work around an IE margin bug.  See:
		    // http://jaspan.com/ie-inherited-margin-bug-form-elements-and-haslayout

            //wrap('<div class="resizable-textarea"><span></span></div>').parent().

		    jQuery(this).append(jQuery('<div class="grippie"></div>').bind("mousedown",{el: this} , startDrag));

		    var grippie = jQuery('div.grippie', jQuery(this).parent())[0];
		    grippie.style.marginRight = (grippie.offsetWidth - jQuery(this)[0].offsetWidth) +'px';

		});
	};
	/* private functions */
	function startDrag(e) {
		textarea = jQuery(e.data.el);
		textarea.blur();
		iLastMousePos = mousePosition(e).y;
		staticOffset = textarea.height() - iLastMousePos;
		textarea.css('opacity', 0.25);
		jQuery(document).mousemove(performDrag).mouseup(endDrag);
		return false;
	}

	function performDrag(e) {
		var iThisMousePos = mousePosition(e).y;
		var iMousePos = staticOffset + iThisMousePos;
		if (iLastMousePos >= (iThisMousePos)) {
			iMousePos -= 5;
		}
		iLastMousePos = iThisMousePos;
		iMousePos = Math.max(iMin, iMousePos);
		textarea.height(iMousePos + 'px');
		if (iMousePos < iMin) {
			endDrag(e);
		}
		return false;
	}

	function endDrag(e) {
		jQuery(document).unbind('mousemove', performDrag).unbind('mouseup', endDrag);
		textarea.css('opacity', 1);
		textarea.focus();
		textarea = null;
		staticOffset = null;
		iLastMousePos = 0;
	}

	function mousePosition(e) {
		return { x: e.clientX + document.documentElement.scrollLeft, y: e.clientY + document.documentElement.scrollTop };
	};
})(jQuery);



// convert radio buttons to select
jQuery.fn.radio2select = function () {
  return this.each(function () {
    var $section = jQuery(this);

    $section.find('input[type=radio]').change(function () {
      jQuery(this).css("visibility", "hidden");
      $section.find('label').removeClass("checked");
      val = $section.find('input[type=radio]:checked').attr('value');
      $section.find('label.' + val).addClass("checked");
    });
    jQuery(this).find('input[type=radio]').change();


  });
};
