

if (isIE == 'undefined') var isIE = false;
if (isIE6 == 'undefined') var isIE6 = false;


/* easing */
// t: current time, b: begInnIng value, c: change In value, d: duration
jQuery.easing['jswing'] = jQuery.easing['swing'];
jQuery.extend(jQuery.easing, {
  def: 'easeOutQuad',
  swing: function (x, t, b, c, d) { //alert(jQuery.easing.default);
    return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
  },
  easeInQuad: function (x, t, b, c, d) {
    return c * (t /= d) * t + b;
  },
  easeOutQuad: function (x, t, b, c, d) {
    return -c * (t /= d) * (t - 2) + b;
  },
  easeInOutQuad: function (x, t, b, c, d) {
    if ((t /= d / 2) < 1) return c / 2 * t * t + b;
    return -c / 2 * ((--t) * (t - 2) - 1) + b;
  },
  easeInCubic: function (x, t, b, c, d) {
    return c * (t /= d) * t * t + b;
  },
  easeOutCubic: function (x, t, b, c, d) {
    return c * ((t = t / d - 1) * t * t + 1) + b;
  },
  easeInOutCubic: function (x, t, b, c, d) {
    if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
    return c / 2 * ((t -= 2) * t * t + 2) + b;
  },
  easeInQuart: function (x, t, b, c, d) {
    return c * (t /= d) * t * t * t + b;
  },
  easeOutQuart: function (x, t, b, c, d) {
    return -c * ((t = t / d - 1) * t * t * t - 1) + b;
  },
  easeInOutQuart: function (x, t, b, c, d) {
    if ((t /= d / 2) < 1) return c / 2 * t * t * t * t + b;
    return -c / 2 * ((t -= 2) * t * t * t - 2) + b;
  },
  easeInQuint: function (x, t, b, c, d) {
    return c * (t /= d) * t * t * t * t + b;
  },
  easeOutQuint: function (x, t, b, c, d) {
    return c * ((t = t / d - 1) * t * t * t * t + 1) + b;
  },
  easeInOutQuint: function (x, t, b, c, d) {
    if ((t /= d / 2) < 1) return c / 2 * t * t * t * t * t + b;
    return c / 2 * ((t -= 2) * t * t * t * t + 2) + b;
  },
  easeInSine: function (x, t, b, c, d) {
    return -c * Math.cos(t / d * (Math.PI / 2)) + c + b;
  },
  easeOutSine: function (x, t, b, c, d) {
    return c * Math.sin(t / d * (Math.PI / 2)) + b;
  },
  easeInOutSine: function (x, t, b, c, d) {
    return -c / 2 * (Math.cos(Math.PI * t / d) - 1) + b;
  },
  easeInExpo: function (x, t, b, c, d) {
    return (t == 0) ? b : c * Math.pow(2, 10 * (t / d - 1)) + b;
  },
  easeOutExpo: function (x, t, b, c, d) {
    return (t == d) ? b + c : c * (-Math.pow(2, -10 * t / d) + 1) + b;
  },
  easeInOutExpo: function (x, t, b, c, d) {
    if (t == 0) return b;
    if (t == d) return b + c;
    if ((t /= d / 2) < 1) return c / 2 * Math.pow(2, 10 * (t - 1)) + b;
    return c / 2 * (-Math.pow(2, -10 * --t) + 2) + b;
  },
  easeInCirc: function (x, t, b, c, d) {
    return -c * (Math.sqrt(1 - (t /= d) * t) - 1) + b;
  },
  easeOutCirc: function (x, t, b, c, d) {
    return c * Math.sqrt(1 - (t = t / d - 1) * t) + b;
  },
  easeInOutCirc: function (x, t, b, c, d) {
    if ((t /= d / 2) < 1) return -c / 2 * (Math.sqrt(1 - t * t) - 1) + b;
    return c / 2 * (Math.sqrt(1 - (t -= 2) * t) + 1) + b;
  },
  easeInElastic: function (x, t, b, c, d) {
    var s = 1.70158;
    var p = 0;
    var a = c;
    if (t == 0) return b;
    if ((t /= d) == 1) return b + c;
    if (!p) p = d * .3;
    if (a < Math.abs(c)) {
      a = c;
      var s = p / 4;
    } else var s = p / (2 * Math.PI) * Math.asin(c / a);
    return - (a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p)) + b;
  },
  easeOutElastic: function (x, t, b, c, d) {
    var s = 1.70158;
    var p = 0;
    var a = c;
    if (t == 0) return b;
    if ((t /= d) == 1) return b + c;
    if (!p) p = d * .3;
    if (a < Math.abs(c)) {
      a = c;
      var s = p / 4;
    } else var s = p / (2 * Math.PI) * Math.asin(c / a);
    return a * Math.pow(2, -10 * t) * Math.sin((t * d - s) * (2 * Math.PI) / p) + c + b;
  },
  easeInOutElastic: function (x, t, b, c, d) {
    var s = 1.70158;
    var p = 0;
    var a = c;
    if (t == 0) return b;
    if ((t /= d / 2) == 2) return b + c;
    if (!p) p = d * (.3 * 1.5);
    if (a < Math.abs(c)) {
      a = c;
      var s = p / 4;
    } else var s = p / (2 * Math.PI) * Math.asin(c / a);
    if (t < 1) return -.5 * (a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p)) + b;
    return a * Math.pow(2, -10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p) * .5 + c + b;
  },
  easeInBack: function (x, t, b, c, d, s) {
    if (s == undefined) s = 1.70158;
    return c * (t /= d) * t * ((s + 1) * t - s) + b;
  },
  easeOutBack: function (x, t, b, c, d, s) {
    if (s == undefined) s = 1.70158;
    return c * ((t = t / d - 1) * t * ((s + 1) * t + s) + 1) + b;
  },
  easeInOutBack: function (x, t, b, c, d, s) {
    if (s == undefined) s = 1.70158;
    if ((t /= d / 2) < 1) return c / 2 * (t * t * (((s *= (1.525)) + 1) * t - s)) + b;
    return c / 2 * ((t -= 2) * t * (((s *= (1.525)) + 1) * t + s) + 2) + b;
  },
  easeInBounce: function (x, t, b, c, d) {
    return c - jQuery.easing.easeOutBounce(x, d - t, 0, c, d) + b;
  },
  easeOutBounce: function (x, t, b, c, d) {
    if ((t /= d) < (1 / 2.75)) {
      return c * (7.5625 * t * t) + b;
    } else if (t < (2 / 2.75)) {
      return c * (7.5625 * (t -= (1.5 / 2.75)) * t + .75) + b;
    } else if (t < (2.5 / 2.75)) {
      return c * (7.5625 * (t -= (2.25 / 2.75)) * t + .9375) + b;
    } else {
      return c * (7.5625 * (t -= (2.625 / 2.75)) * t + .984375) + b;
    }
  },
  easeInOutBounce: function (x, t, b, c, d) {
    if (t < d / 2) return jQuery.easing.easeInBounce(x, t * 2, 0, c, d) * .5 + b;
    return jQuery.easing.easeOutBounce(x, t * 2 - d, 0, c, d) * .5 + c * .5 + b;
  }
});



/*
  	loopedSlider 0.5.4 - jQuery plugin
 	written by Nathan Searles
 	http://nathansearles.com/loopedslider/

 	Copyright (c) 2009 Nathan Searles (http://nathansearles.com/)
 	Dual licensed under the MIT (MIT-LICENSE.txt)
 	and GPL (GPL-LICENSE.txt) licenses.

 	Built for jQuery library
 	http://jquery.com

    - MODIFIED FOR MYSTIQUE! BE CAREFUL WHEN UPDATING! */

(function (jQuery) {
  jQuery.fn.loopedSlider = function (options) {
    var defaults = {
      container: '.slide-container',
      slides: '.slides',
      pagination: '.pagination',
      containerClick: false,
      // Click container for next slide
      autoStart: 0,
      // Set to positive number for auto start and interval time
      restart: 0,
      // Set to positive number for restart and restart time
      slidespeed: 333,
      // Speed of slide animation
      fadespeed: 133,
      // Speed of fade animation
      autoHeight: true,
      // Set to positive number for auto height and animation speed
      easing: 'easeOutQuart'
    };
    this.each(function () {
      var obj = jQuery(this);
      var o = jQuery.extend(defaults, options);
      var pagination = jQuery(o.pagination + ' li a', obj);
      var m = 0;
      var t = 1;
      var s = jQuery(o.slides, obj).find('li.slide').size();
      var w = jQuery(o.slides, obj).find('li.slide').outerWidth();
      var p = 0;
      var u = false;
      var n = 0;
      var interval = 0;
      var restart = 0;
      jQuery(o.slides, obj).css({
        width: (s * w)
      });
      jQuery(o.slides, obj).find('li.slide').each(function () {
        jQuery(this).css({
          position: 'absolute',
          left: p,
          display: 'block'
        });
        p = p + w;
      });
      jQuery(pagination, obj).each(function () {
        n = n + 1;
        jQuery(this).attr('rel', n);
        jQuery(pagination.eq(0), obj).parent().addClass('active');
      });
      jQuery(o.slides, obj).find('li.slide:eq(' + (s - 1) + ')').css({
        position: 'absolute',
        left: -w
      });
      if (s > 3) {
        jQuery(o.slides, obj).find('li.slide:eq(' + (s - 1) + ')').css({
          position: 'absolute',
          left: -w
        });
      }
      if (o.autoHeight) {
        autoHeight(t);
      }
      jQuery('.next', obj).click(function () {
        if (u === false) {
          animate('next', true);
          if (o.autoStart) {
            if (o.restart) {
              autoStart();
            } else {
              clearInterval(sliderIntervalID);
            }
          }
        }
        return false;
      });
      jQuery('.previous', obj).click(function () {
        if (u === false) {
          animate('prev', true);
          if (o.autoStart) {
            if (o.restart) {
              autoStart();
            } else {
              clearInterval(sliderIntervalID);
            }
          }
        }
        return false;
      });
      if (o.containerClick) {
        jQuery(o.container, obj).click(function () {
          if (u === false) {
            animate('next', true);
            if (o.autoStart) {
              if (o.restart) {
                autoStart();
              } else {
                clearInterval(sliderIntervalID);
              }
            }
          }
          return false;
        });
      }
      jQuery(pagination, obj).click(function () {
        if (jQuery(this).parent().hasClass('active')) {
          return false;
        } else {
          t = jQuery(this).attr('rel');
          jQuery(pagination, obj).parent().siblings().removeClass('active');
          jQuery(this).parent().addClass('active');
          animate('fade', t);
          if (o.autoStart) {
            if (o.restart) {
              autoStart();
            } else {
              clearInterval(sliderIntervalID);
            }
          }
        }
        return false;
      });
      if (o.autoStart) {
        sliderIntervalID = setInterval(function () {
          if (u === false) {
            animate('next', true);
          }
        },
        o.autoStart);

        function autoStart() {
          if (o.restart) {
            clearInterval(sliderIntervalID);
            clearInterval(interval);
            clearTimeout(restart);
            restart = setTimeout(function () {
              interval = setInterval(function () {
                animate('next', true);
              },
              o.autoStart);
            },
            o.restart);
          } else {
            sliderIntervalID = setInterval(function () {
              if (u === false) {
                animate('next', true);
              }
            },
            o.autoStart);
          }
        };
      }

      function current(t) {
        if (t === s + 1) {
          t = 1;
        }
        if (t === 0) {
          t = s;
        }
        jQuery(pagination, obj).parent().siblings().removeClass('active');
        jQuery(pagination + '[rel="' + (t) + '"]', obj).parent().addClass('active');
      };

      function autoHeight(t) {
        if (t === s + 1) {
          t = 1;
        }
        if (t === 0) {
          t = s;
        }
        var getHeight = jQuery(o.slides, obj).find('li.slide:eq(' + (t - 1) + ')', obj).outerHeight();
        jQuery(o.container, obj).animate({
          height: getHeight
        },
        o.autoHeight, o.easing);
      };

      function animate(dir, clicked) {
        u = true;
        switch (dir) {
        case 'next':
          t = t + 1;
          m = (-(t * w - w));
          current(t);
          if (o.autoHeight) {
            autoHeight(t);
          }
          if (s < 3) {
            if (t === 3) {
              jQuery(o.slides, obj).find('li.slide:eq(0)').css({
                left: (s * w)
              });
            }
            if (t === 2) {
              jQuery(o.slides, obj).find('li.slide:eq(' + (s - 1) + ')').css({
                position: 'absolute',
                left: (w)
              });
            }
          }
          jQuery(o.slides, obj).animate({
            left: m
          },
          o.slidespeed, o.easing, function () {
            if (t === s + 1) {
              t = 1;
              jQuery(o.slides, obj).css({
                left: 0
              },
              function () {
                jQuery(o.slides, obj).animate({
                  left: m
                })
              });
              jQuery(o.slides, obj).find('li.slide:eq(0)').css({
                left: 0
              });
              jQuery(o.slides, obj).find('li.slide:eq(' + (s - 1) + ')').css({
                position: 'absolute',
                left: -w
              });
            }
            if (t === s) jQuery(o.slides, obj).find('li.slide:eq(0)').css({
              left: (s * w)
            });
            if (t === s - 1) jQuery(o.slides, obj).find('li.slide:eq(' + (s - 1) + ')').css({
              left: s * w - w
            });
            u = false;
          });
          break;
        case 'prev':
          t = t - 1;
          m = (-(t * w - w));
          current(t);
          if (o.autoHeight) {
            autoHeight(t);
          }
          if (s < 3) {
            if (t === 0) {
              jQuery(o.slides, obj).find('li.slide:eq(' + (s - 1) + ')').css({
                position: 'absolute',
                left: (-w)
              });
            }
            if (t === 1) {
              jQuery(o.slides, obj).find('li.slide:eq(0)').css({
                position: 'absolute',
                left: 0
              });
            }
          }
          jQuery(o.slides, obj).animate({
            left: m
          },
          o.slidespeed, o.easing, function () {
            if (t === 0) {
              t = s;
              jQuery(o.slides, obj).find('li.slide:eq(' + (s - 1) + ')').css({
                position: 'absolute',
                left: (s * w - w)
              });
              jQuery(o.slides, obj).css({
                left: -(s * w - w)
              });
              jQuery(o.slides, obj).find('li.slide:eq(0)').css({
                left: (s * w)
              });
            }
            if (t === 2) jQuery(o.slides, obj).find('li.slide:eq(0)').css({
              position: 'absolute',
              left: 0
            });
            if (t === 1) jQuery(o.slides, obj).find('li.slide:eq(' + (s - 1) + ')').css({
              position: 'absolute',
              left: -w
            });
            u = false;
          });
          break;
        case 'fade':
          t = [t] * 1;
          m = (-(t * w - w));
          current(t);
          if (o.autoHeight) {
            autoHeight(t);
          }
          jQuery(o.slides, obj).find('li:slide').fadeOut(o.fadespeed, function () {
            jQuery(o.slides, obj).css({
              left: m
            });
            jQuery(o.slides, obj).find('li.slide:eq(' + (s - 1) + ')').css({
              left: s * w - w
            });
            jQuery(o.slides, obj).find('li.slide:eq(0)').css({
              left: 0
            });
            if (t === s) {
              jQuery(o.slides, obj).find('li.slide:eq(0)').css({
                left: (s * w)
              });
            }
            if (t === 1) {
              jQuery(o.slides, obj).find('li.slide:eq(' + (s - 1) + ')').css({
                position: 'absolute',
                left: -w
              });
            }
            jQuery(o.slides, obj).find('li:slide').fadeIn(o.fadespeed);
            u = false;
          });
          break;
        default:
          break;
        }
      };
    });
  };
})(jQuery);

/* "clearField" by Stijn Van Minnebruggen
   http://www.donotfold.be  */
(function (jQuery) {
  jQuery.fn.clearField = function (settings) {
    settings = jQuery.extend({
      blurClass: 'clearFieldBlurred',
      activeClass: 'clearFieldActive'
    },
    settings);
    jQuery(this).each(function () {
      var el = jQuery(this);
      if (el.hasClass('password') && el.hasClass('label')){
        el.show();
        var $target = jQuery('#'+el.attr('alt'));
        $target.hide();

        $target.blur(function () {
          if (el.hasClass('password') && el.hasClass('label') && (jQuery(this).val() == (''))){
           jQuery(this).hide();
           el.show();
           el.val(el.attr('rel')).removeClass(settings.activeClass).addClass(settings.blurClass);
          }
        });

      }

      if (el.attr('rel') == undefined) {
        el.attr('rel', el.val()).addClass(settings.blurClass);
      }
      el.focus(function () {
        if (el.val() == el.attr('rel')) {
          var v = '';
          if (el.attr('name') == 'url') v = 'http://';

          if (el.hasClass('password') && el.hasClass('label')){
            el.hide();
            $target.show();
            $target.focus();
          }

          el.val(v).removeClass(settings.blurClass).addClass(settings.activeClass);
        }
      });
      el.blur(function () {
        if ((el.val() == ('')) || ((el.attr('name') == 'url') && (el.val() == ('http://')))) {
          el.val(el.attr('rel')).removeClass(settings.activeClass).addClass(settings.blurClass);
        }
      });
    });
    return jQuery;
  };
})(jQuery);


/*
 * jQuery Flickr - jQuery plug-in
 * Version 1.0, Released 2008.04.17
 *
 * Copyright (c) 2008 Daniel MacDonald (www.projectatomic.com)
 * Dual licensed GPL http://www.gnu.org/licenses/gpl.html
 * and MIT http://www.opensource.org/licenses/mit-license.php
 */
(function (jQuery) {
  jQuery.fn.flickr = function (o) {
    var s = {
      api_key: null,
      // [string]    required, see http://www.flickr.com/services/api/misc.api_keys.html
      type: null,
      // [string]    allowed values: 'photoset', 'search', default: 'flickr.photos.getRecent'
      photoset_id: null,
      // [string]    required, for type=='photoset'
      text: null,
      // [string]    for type=='search' free text search
      user_id: null,
      // [string]    for type=='search' search by user id
      group_id: null,
      // [string]    for type=='search' search by group id
      tags: null,
      // [string]    for type=='search' comma separated list
      tag_mode: 'any',
      // [string]    for type=='search' allowed values: 'any' (OR), 'all' (AND)
      sort: 'relevance',
      // [string]    for type=='search' allowed values: 'date-posted-asc', 'date-posted-desc', 'date-taken-asc', 'date-taken-desc', 'interestingness-desc', 'interestingness-asc', 'relevance'
      thumb_size: 's',
      // [string]    allowed values: 's' (75x75), 't' (100x?), 'm' (240x?)
      size: null,
      // [string]    allowed values: 'm' (240x?), 'b' (1024x?), 'o' (original), default: (500x?)
      per_page: 100,
      // [integer]   allowed values: max of 500
      page: 1,
      // [integer]   see paging notes
      attr: '',
      // [string]    optional, attributes applied to thumbnail <a> tag
      api_url: null,
      // [string]    optional, custom url that returns flickr JSON or JSON-P 'photos' or 'photoset'
      params: '',
      // [string]    optional, custom arguments, see http://www.flickr.com/services/api/flickr.photos.search.html
      api_callback: '?',
      // [string]    optional, custom callback in flickr JSON-P response
      callback: null // [function]  optional, callback function applied to entire <ul>
      // PAGING NOTES: jQuery Flickr plug-in does not provide paging functionality, but does provide hooks for a custom paging routine
      // within the <ul> created by the plug-in, there are two hidden <input> tags,
      // input:eq(0): current page, input:eq(1): total number of pages, input:eq(2): images per page, input:eq(3): total number of images
      // SEARCH NOTES: when setting type to 'search' at least one search parameter  must also be passed text, user_id, group_id, or tags
      // SIZE NOTES: photos must allow viewing original size for size 'o' to function, if not, default size is shown
    };
    if (o) jQuery.extend(s, o);
    return this.each(function () { // create unordered list to contain flickr images
      var list = jQuery('<ul class="clearfix">').appendTo(this);
      var url = jQuery.flickr.format(s);
      jQuery.getJSON(url, function (r) {
        if (r.stat != "ok") {
          for (i in r) {
            jQuery('<li>').text(i + ': ' + r[i]).appendTo(list);
          };
        } else {
          if (s.type == 'photoset') r.photos = r.photoset; // add hooks to access paging data
          list.append('<input type="hidden" value="' + r.photos.page + '" />');
          list.append('<input type="hidden" value="' + r.photos.pages + '" />');
          list.append('<input type="hidden" value="' + r.photos.perpage + '" />');
          list.append('<input type="hidden" value="' + r.photos.total + '" />');
          for (var i = 0; i < r.photos.photo.length; i++) {
            var photo = r.photos.photo[i]; // format thumbnail url
            var t = 'http://farm' + photo['farm'] + '.static.flickr.com/' + photo['server'] + '/' + photo['id'] + '_' + photo['secret'] + '_' + s.thumb_size + '.jpg'; //format image url
            var h = 'http://farm' + photo['farm'] + '.static.flickr.com/' + photo['server'] + '/' + photo['id'] + '_';
            switch (s.size) {
            case 'm':
              h += photo['secret'] + '_m.jpg';
              break;
            case 'b':
              h += photo['secret'] + '_b.jpg';
              break;
            case 'o':
              if (photo['originalsecret'] && photo['originalformat']) {
                h += photo['originalsecret'] + '_o.' + photo['originalformat'];
              } else {
                h += photo['secret'] + '_b.jpg';
              };
              break;
            default:
              h += photo['secret'] + '.jpg';
            };
            list.append('<li><a class="thumb" rel="flickr" href="' + h + '" ' + s.attr + ' title="' + photo['title'] + '"><img src="' + t + '" alt="' + photo['title'] + '" /></a></li>'); //galleryPreview("#flickrGallery li a.thumb","tooltip");
          };
          if (s.callback) s.callback(list);
        };
      });
    });
  }; // static function to format the flickr API url according to the plug-in settings
  jQuery.flickr = {
    format: function (s) {
      if (s.url) return s.url;
      var url = 'http://api.flickr.com/services/rest/?format=json&jsoncallback=' + s.api_callback + '&api_key=' + s.api_key;
      switch (s.type) {
      case 'photoset':
        url += '&method=flickr.photosets.getPhotos&photoset_id=' + s.photoset_id;
        break;
      case 'search':
        url += '&method=flickr.photos.search&sort=' + s.sort;
        if (s.user_id) url += '&user_id=' + s.user_id;
        if (s.group_id) url += '&group_id=' + s.group_id;
        if (s.tags) url += '&tags=' + s.tags;
        if (s.tag_mode) url += '&tag_mode=' + s.tag_mode;
        if (s.text) url += '&text=' + s.text;
        break;
      default:
        url += '&method=flickr.photos.getRecent';
      };
      if (s.size == 'o') url += '&extras=original_format';
      url += '&per_page=' + s.per_page + '&page=' + s.page + s.params;
      return url;
    }
  };
})(jQuery);

///* twitter functions  */
///* http://jquery-howto.blogspot.com/2009/04/jquery-twitter-api-plugin.html */
//(function (jQuery) {
//  jQuery.extend({
//    jTwitter: function (username, fnk) {
//      var url = "http://twitter.com/status/user_timeline/" + username + ".json?count=1&callback=?";
//      var info = {};
//      jQuery.getJSON(url, function (data) {
//        if (typeof fnk == 'function') fnk.call(this, data[0].user);
//      });
//    }
//  });
//})(jQuery);
//
//
//
///* http://tweet.seaofclouds.com/ */
//(function(jQuery) {
//
//  jQuery.fn.getTwitter = function(o){
//    var s = {
//      username: ["wordpress"],                // [string]   required, unless you want to display our tweets. :) it can be an array, just do ["username1","username2","etc"]
//      avatar_size: null,                      // [integer]  height and width of avatar if displayed (48px max)
//      count: 6,                               // [integer]  how many tweets to display?
//      loading_text: null,                     // [string]   optional loading text, displayed while tweets load
//      query: null                             // [string]   optional search query
//    };
//
//    jQuery.fn.extend({
//      linkUrl: function() {
//        var returning = [];
//        var regexp = /((ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?)/gi;
//        this.each(function() {
//          returning.push(this.replace(regexp,"<a href=\"$1\">$1</a>"))
//        });
//        return jQuery(returning);
//      },
//      linkUser: function() {
//        var returning = [];
//        var regexp = /[\@]+([A-Za-z0-9-_]+)/gi;
//        this.each(function() {
//          returning.push(this.replace(regexp,"<a href=\"http://twitter.com/$1\">@$1</a>"))
//        });
//        return jQuery(returning);
//      },
//      linkHash: function() {
//        var returning = [];
//        var regexp = / [\#]+([A-Za-z0-9-_]+)/gi;
//        this.each(function() {
//          returning.push(this.replace(regexp, ' <a href="http://search.twitter.com/search?q=&tag=$1&lang=all&from='+s.username.join("%2BOR%2B")+'">#$1</a>'))
//        });
//        return jQuery(returning);
//      },
//      capAwesome: function() {
//        var returning = [];
//        this.each(function() {
//          returning.push(this.replace(/(a|A)wesome/gi, 'AWESOME'))
//        });
//        return jQuery(returning);
//      },
//      capEpic: function() {
//        var returning = [];
//        this.each(function() {
//          returning.push(this.replace(/(e|E)pic/gi, 'EPIC'))
//        });
//        return jQuery(returning);
//      },
//      makeHeart: function() {
//        var returning = [];
//        this.each(function() {
//          returning.push(this.replace(/[&lt;]+[3]/gi, "<tt class='heart'>&#x2665;</tt>"))
//        });
//        return jQuery(returning);
//      }
//    });
//
//    function relative_time(time_value) {
//      var parsed_date = Date.parse(time_value);
//      var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
//      var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
//      if(delta < 60) {
//      return 'less than a minute ago';
//      } else if(delta < 120) {
//      return 'about a minute ago';
//      } else if(delta < (45*60)) {
//      return (parseInt(delta / 60)).toString() + ' minutes ago';
//      } else if(delta < (90*60)) {
//      return 'about an hour ago';
//      } else if(delta < (24*60*60)) {
//      return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
//      } else if(delta < (48*60*60)) {
//      return '1 day ago';
//      } else {
//      return (parseInt(delta / 86400)).toString() + ' days ago';
//      }
//    }
//
//    if(o) jQuery.extend(s, o);
//    return this.each(function(){
//      var list = jQuery('<ul class="tweet_list">').appendTo(this);
//      list.hide();
//      var loading = jQuery('<p class="preLoader">'+s.loading_text+'</p>');
//      if(typeof(s.username) == "string"){
//        s.username = [s.username];
//      }
//      var query = '';
//      if(s.query) {
//        query += 'q='+s.query;
//      }
//      query += '&q=from:'+s.username.join('%20OR%20from:');
//      var url = 'http://search.twitter.com/search.json?&'+query+'&rpp='+s.count+'&callback=?';
//      if (s.loading_text) jQuery(this).append(loading);
//      jQuery.getJSON(url, function(data){
//        if (s.loading_text) loading.remove();
//        jQuery.each(data.results, function(i,item){
//
//          var avatar_template = '<a class="tweet_avatar" href="http://twitter.com/'+ item.from_user+'"><img src="'+item.profile_image_url+'" height="'+s.avatar_size+'" width="'+s.avatar_size+'" alt="'+item.from_user+'\'s avatar" border="0"/></a>';
//          var avatar = (s.avatar_size ? avatar_template : '')
//          var date = '<a class="date" href="http://twitter.com/'+item.from_user+'/statuses/'+item.id+'" title="view tweet on twitter">'+relative_time(item.created_at)+'</a>';
//          var text = '<span class="entry">' +jQuery([item.text]).linkUrl().linkUser().linkHash().makeHeart().capAwesome().capEpic()[0]+ date + '</span>';
//
//          // until we create a template option, arrange the items below to alter a tweet's display.
//          list.append('<li>' + text + '</li>');
//
//          list.children('li:first').addClass('firstTweet');
//          list.children('li:odd').addClass('even');
//          list.children('li:even').addClass('lastTweet');
//
//
//        });
//
//        list.animate({opacity: 'toggle', height: 'toggle'}, 500, 'easeOutQuart');
//
//      });
//
//
//    });
//  };
//})(jQuery);








/*! Copyright (c) 2008 Brandon Aaron (http://brandonaaron.net)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.0.3
 * Requires jQuery 1.1.3+
 * Docs: http://docs.jquery.com/Plugins/livequery
 */

(function($) {

$.extend($.fn, {
	livequery: function(type, fn, fn2) {
		var self = this, q;

		// Handle different call patterns
		if ($.isFunction(type))
			fn2 = fn, fn = type, type = undefined;

		// See if Live Query already exists
		$.each( $.livequery.queries, function(i, query) {
			if ( self.selector == query.selector && self.context == query.context &&
				type == query.type && (!fn || fn.$lqguid == query.fn.$lqguid) && (!fn2 || fn2.$lqguid == query.fn2.$lqguid) )
					// Found the query, exit the each loop
					return (q = query) && false;
		});

		// Create new Live Query if it wasn't found
		q = q || new $.livequery(this.selector, this.context, type, fn, fn2);

		// Make sure it is running
		q.stopped = false;

		// Run it immediately for the first time
		q.run();

		// Contnue the chain
		return this;
	},

	expire: function(type, fn, fn2) {
		var self = this;

		// Handle different call patterns
		if ($.isFunction(type))
			fn2 = fn, fn = type, type = undefined;

		// Find the Live Query based on arguments and stop it
		$.each( $.livequery.queries, function(i, query) {
			if ( self.selector == query.selector && self.context == query.context &&
				(!type || type == query.type) && (!fn || fn.$lqguid == query.fn.$lqguid) && (!fn2 || fn2.$lqguid == query.fn2.$lqguid) && !this.stopped )
					$.livequery.stop(query.id);
		});

		// Continue the chain
		return this;
	}
});

$.livequery = function(selector, context, type, fn, fn2) {
	this.selector = selector;
	this.context  = context || document;
	this.type     = type;
	this.fn       = fn;
	this.fn2      = fn2;
	this.elements = [];
	this.stopped  = false;

	// The id is the index of the Live Query in $.livequery.queries
	this.id = $.livequery.queries.push(this)-1;

	// Mark the functions for matching later on
	fn.$lqguid = fn.$lqguid || $.livequery.guid++;
	if (fn2) fn2.$lqguid = fn2.$lqguid || $.livequery.guid++;

	// Return the Live Query
	return this;
};

$.livequery.prototype = {
	stop: function() {
		var query = this;

		if ( this.type )
			// Unbind all bound events
			this.elements.unbind(this.type, this.fn);
		else if (this.fn2)
			// Call the second function for all matched elements
			this.elements.each(function(i, el) {
				query.fn2.apply(el);
			});

		// Clear out matched elements
		this.elements = [];

		// Stop the Live Query from running until restarted
		this.stopped = true;
	},

	run: function() {
		// Short-circuit if stopped
		if ( this.stopped ) return;
		var query = this;

		var oEls = this.elements,
			els  = $(this.selector, this.context),
			nEls = els.not(oEls);

		// Set elements to the latest set of matched elements
		this.elements = els;

		if (this.type) {
			// Bind events to newly matched elements
			nEls.bind(this.type, this.fn);

			// Unbind events to elements no longer matched
			if (oEls.length > 0)
				$.each(oEls, function(i, el) {
					if ( $.inArray(el, els) < 0 )
						$.event.remove(el, query.type, query.fn);
				});
		}
		else {
			// Call the first function for newly matched elements
			nEls.each(function() {
				query.fn.apply(this);
			});

			// Call the second function for elements no longer matched
			if ( this.fn2 && oEls.length > 0 )
				$.each(oEls, function(i, el) {
					if ( $.inArray(el, els) < 0 )
						query.fn2.apply(el);
				});
		}
	}
};

$.extend($.livequery, {
	guid: 0,
	queries: [],
	queue: [],
	running: false,
	timeout: null,

	checkQueue: function() {
		if ( $.livequery.running && $.livequery.queue.length ) {
			var length = $.livequery.queue.length;
			// Run each Live Query currently in the queue
			while ( length-- )
				$.livequery.queries[ $.livequery.queue.shift() ].run();
		}
	},

	pause: function() {
		// Don't run anymore Live Queries until restarted
		$.livequery.running = false;
	},

	play: function() {
		// Restart Live Queries
		$.livequery.running = true;
		// Request a run of the Live Queries
		$.livequery.run();
	},

	registerPlugin: function() {
		$.each( arguments, function(i,n) {
			// Short-circuit if the method doesn't exist
			if (!$.fn[n]) return;

			// Save a reference to the original method
			var old = $.fn[n];

			// Create a new method
			$.fn[n] = function() {
				// Call the original method
				var r = old.apply(this, arguments);

				// Request a run of the Live Queries
				$.livequery.run();

				// Return the original methods result
				return r;
			}
		});
	},

	run: function(id) {
		if (id != undefined) {
			// Put the particular Live Query in the queue if it doesn't already exist
			if ( $.inArray(id, $.livequery.queue) < 0 )
				$.livequery.queue.push( id );
		}
		else
			// Put each Live Query in the queue if it doesn't already exist
			$.each( $.livequery.queries, function(id) {
				if ( $.inArray(id, $.livequery.queue) < 0 )
					$.livequery.queue.push( id );
			});

		// Clear timeout if it already exists
		if ($.livequery.timeout) clearTimeout($.livequery.timeout);
		// Create a timeout to check the queue and actually run the Live Queries
		$.livequery.timeout = setTimeout($.livequery.checkQueue, 20);
	},

	stop: function(id) {
		if (id != undefined)
			// Stop are particular Live Query
			$.livequery.queries[ id ].stop();
		else
			// Stop all Live Queries
			$.each( $.livequery.queries, function(id) {
				$.livequery.queries[ id ].stop();
			});
	}
});

// Register core DOM manipulation methods
$.livequery.registerPlugin('append', 'prepend', 'after', 'before', 'wrap', 'attr', 'removeAttr', 'addClass', 'removeClass', 'toggleClass', 'empty', 'remove');

// Run Live Queries when the Document is ready
$(function() { $.livequery.play(); });


// Save a reference to the original init method
var init = $.prototype.init;

// Create a new init method that exposes two new properties: selector and context
$.prototype.init = function(a,c) {
	// Call the original init and save the result
	var r = init.apply(this, arguments);

	// Copy over properties if they exist already
	if (a && a.selector)
		r.context = a.context, r.selector = a.selector;

	// Set properties
	if ( typeof a == 'string' )
		r.context = c || document, r.selector = a;

	// Return the result
	return r;
};

// Give the init function the jQuery prototype for later instantiation (needed after Rev 4091)
$.prototype.init.prototype = $.prototype;

})(jQuery);






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

// fixes for IE-7/8 cleartype bug on fade in/out
jQuery.fn.fadeIn = function (speed, callback) {
  return this.animate({
    opacity: 'show'
  },
  speed, function () {
    if (jQuery.browser.msie) this.style.removeAttribute('filter');
    if (jQuery.isFunction(callback)) callback();
  });
};
jQuery.fn.fadeOut = function (speed, callback) {
  return this.animate({
    opacity: 'hide'
  },
  speed, function () {
    if (jQuery.browser.msie) this.style.removeAttribute('filter');
    if (jQuery.isFunction(callback)) callback();
  });
};
jQuery.fn.fadeTo = function (speed, to, callback) {
  return this.animate({
    opacity: to
  },
  speed, function () {
    if (to == 1 && jQuery.browser.msie) this.style.removeAttribute('filter');
    if (jQuery.isFunction(callback)) callback();
  });
};

// nundge effect
jQuery.fn.nudge = function (params) { //set default parameters
  params = jQuery.extend({
    amount: 20,
    //amount of pixels to pad / marginize
    duration: 300,
    //amount of milliseconds to take
    property: 'padding',
    //the property to animate (could also use margin)
    direction: 'left',
    //direction to animate (could also use right)
    toCallback: function () {},
    //function to execute when MO animation completes
    fromCallback: function () {} //function to execute when MOut animation completes
  },
  params); //For every element meant to nudge...
  this.each(function () { //variables
    var $t = jQuery(this);
    var $p = params;
    var dir = $p.direction;
    var prop = $p.property + dir.substring(0, 1).toUpperCase() + dir.substring(1, dir.length);
    var initialValue = $t.css(prop);
    /* fx */
    var go = {};
    go[prop] = parseInt($p.amount) + parseInt(initialValue);
    var bk = {};
    bk[prop] = initialValue; //Proceed to nudge on hover
    $t.hover(function () {
      $t.stop().animate(go, $p.duration, '', $p.toCallback);
    },
    function () {
      $t.stop().animate(bk, $p.duration, '', $p.fromCallback);
    });
  });
  return this;
};

// bubble
(function (jQuery) {
  jQuery.fn.bubble = function (options) {
    jQuery.fn.bubble.defaults = {
      timeout: 0,
      offset: 22
    };
    var o = jQuery.extend({},
    jQuery.fn.bubble.defaults, options);

    return this.livequery(function () {
      var showTip = function () {
        var el = jQuery(this).find('.bubble').css('display', 'block')[0];
        var ttHeight = jQuery(el).height();
        var ttOffset = el.offsetHeight;
        var ttTop = ttOffset + ttHeight;
        jQuery(this).find('.bubble').stop().css({
          'opacity': 0,
          'top': 2 - ttOffset
        }).animate({
          'opacity': 1,
          'top': o.offset - ttOffset
        },
        250);
      };
      var hideTip = function () {
        var self = this;
        var el = jQuery('.bubble', this).css('display', 'block')[0];
        var ttHeight = jQuery(el).height();
        var ttOffset = el.offsetHeight;
        var ttTop = ttOffset + ttHeight;
        jQuery(this).find('.bubble').stop().animate({
          'opacity': 0,
          'top': 12 - ttOffset
        },
        250, 'swing', function () {
          el.hiding = false;
          jQuery(this).css('display', 'none');
        })
      }

      jQuery(this).find('.bubble').hover(function () {
        return false;
      },
      function () {
        return true;
      });

      jQuery(this).hover(function () {
        var self = this;
        showTip.apply(this);
        if (o.timeout > 0) this.tttimeout = setTimeout(function () {
          hideTip.apply(self)
        },
        o.timeout);
      },
      function () {
        clearTimeout(this.tttimeout);
        hideTip.apply(this);
      });
    });

  };
})(jQuery);

//Private function for setting cookie
function updateCookie(target, data) {
  var cookie = target.replace(/[#. ]/g, '');
  jQuery.cookie(cookie, data, {
    path: '/'
  });
}

function fontControl(container, target, minSize, maxSize) {
  jQuery(container).append('<a href="javascript:void(0);" class="fontSize bubble" title="Increase or decrease text size"></a>');
  var cookie = 'page-font-size';
  var value = jQuery.cookie(cookie);
  if (value != null) {
    jQuery(target).css('fontSize', parseInt(value));
  } //on clicking small font button, font size is decreased by 1px
  jQuery(container + " .fontSize").click(function () {
    curSize = parseInt(jQuery(target).css("fontSize"));
    newSize = curSize + 1;
    if (newSize > maxSize) newSize = minSize;
    if (newSize >= minSize) //jQuery(target).css('fontSize', newSize);
    jQuery(target).animate({
      fontSize: newSize
    },
    333, 'swing');
    updateCookie(cookie, newSize); //sets the cookie
  });
}

function pageWidthControl(container, target, fullWidth, fixedWidth, fluidWidth) {
  jQuery(container).append('<a href="javascript:void(0);" class="pageWidth bubble" title="switch from fixed to fluid page width"></a>');
  var cookie = 'page-max-width';
  var value = jQuery.cookie(cookie);
  if (value != null) {
    jQuery(target).css('maxWidth', value);
  }
  jQuery(container + " .pageWidth").click(function () {
    curMaxWidth = jQuery(target).css('maxWidth');
    newMaxWidth = curMaxWidth;
    switch (curMaxWidth) {
    case fullWidth:
      newMaxWidth = fixedWidth;
      break;
    case fixedWidth:
      newMaxWidth = fluidWidth;
      break;
    case fluidWidth:
      newMaxWidth = fullWidth;
      break;
    default:
      newMaxWidth = fluidWidth;
    }
    jQuery(target).animate({
      maxWidth: newMaxWidth
    },
    333, 'easeOutQuart');
    updateCookie(cookie, newMaxWidth); //sets the cookie
  });
}

/* old menu */
//(function (jQuery) {
//  jQuery.fn.dropDown = function (options) {
//    jQuery.fn.dropDown.defaults = {
//      delay: 0
//    };
//    var o = jQuery.extend({},
//    jQuery.fn.dropDown.defaults, options);
//    return this.each(function () {
//      jQuery(this).find("ul").css({
//        display: "none"
//      });
//      jQuery(this).find("li").hover(function () {
//        jQuery(this).find('ul:first').css({
//          display: "block",
//          opacity: 0,
//          marginLeft: 20
//        }).animate({
//          opacity: 1,
//          marginLeft: 0
//        },
//        150, 'swing');
//      },
//      function () {
//        jQuery(this).find('ul:first').animate({
//          opacity: 0,
//          marginLeft: 20
//        },
//        150, 'swing', function () {
//          jQuery(this).css({
//            display: "none"
//          });
//        });
//      });
//    });
//  };
//})(jQuery);

/*
 * Superfish v1.4.8 - jQuery menu widget
 * Copyright (c) 2008 Joel Birch
 *
 * Dual licensed under the MIT and GPL licenses:
 * 	http://www.opensource.org/licenses/mit-license.php
 * 	http://www.gnu.org/licenses/gpl.html
 *
 * CHANGELOG: http://users.tpg.com.au/j_birch/plugins/superfish/changelog.txt
 
- MODIFIED FOR MYSTIQUE! BE CAREFUL WHEN UPDATING!

 */
;
(function (jQuery) {
  jQuery.fn.superfish = function (op) {
    var sf = jQuery.fn.superfish,
      c = sf.c,
      $arrow = jQuery(['<span class="', c.arrowClass, '"> &#187;</span>'].join('')),
      over = function () {
      var $$ = jQuery(this),
        menu = getMenu($$);
      clearTimeout(menu.sfTimer);
      $$.showSuperfishUl().siblings().hideSuperfishUl();
    },
      out = function () {
      var $$ = jQuery(this),
        menu = getMenu($$),
        o = sf.op;
      clearTimeout(menu.sfTimer);
      menu.sfTimer = setTimeout(function () {
        o.retainPath = (jQuery.inArray($$[0], o.$path) > -1);
        $$.hideSuperfishUl();
        if (o.$path.length && $$.parents(['li.', o.hoverClass].join('')).length < 1) {
          over.call(o.$path);
        }
      },
      o.delay);
    },
      getMenu = function ($menu) {
      var menu = $menu.parents(['ul.', c.menuClass, ':first'].join(''))[0];
      sf.op = sf.o[menu.serial];
      return menu;
    },
      addArrow = function ($a) {
      $a.addClass(c.anchorClass).append($arrow.clone());
    };
    return this.each(function () {
      var s = this.serial = sf.o.length;
      var o = jQuery.extend({},
      sf.defaults, op);
      o.$path = jQuery('li.' + o.pathClass, this).slice(0, o.pathLevels).each(function () {
        jQuery(this).addClass([o.hoverClass, c.bcClass].join(' ')).filter('li:has(ul)').removeClass(o.pathClass);
      });
      sf.o[s] = sf.op = o;
      jQuery('li:has(ul)', this)[(jQuery.fn.hoverIntent && !o.disableHI) ? 'hoverIntent' : 'hover'](over, out).each(function () {
        if (o.autoArrows) addArrow(jQuery('>a:first-child', this));
      }).not('.' + c.bcClass).hideSuperfishUl();
      var $a = jQuery('a', this);
      $a.each(function (i) {
        var $li = $a.eq(i).parents('li');
        $a.eq(i).focus(function () {
          over.call($li);
        }).blur(function () {
          out.call($li);
        });
      });
      o.onInit.call(this);
    }).each(function () {
      var menuClasses = [c.menuClass];
      jQuery(this).addClass(menuClasses.join(' '));
    });
  };
  var sf = jQuery.fn.superfish;
  sf.o = [];
  sf.op = {};
  sf.c = {
    bcClass: 'sf-breadcrumb',
    menuClass: 'sf-js-enabled',
    anchorClass: 'sf-with-ul',
    arrowClass: 'arrow'
  };
  sf.defaults = {
    hoverClass: 'sfHover',
    pathClass: 'overideThisToUse',
    pathLevels: 1,
    delay: 500,
    speed: 'normal',
    autoArrows: true,
    disableHI: false,
    // true disables hoverIntent detection
    onInit: function () {},
    // callback functions
    onBeforeShow: function () {},
    onShow: function () {},
    onHide: function () {}
  };
  jQuery.fn.extend({
    hideSuperfishUl: function () {
      var o = sf.op,
        not = (o.retainPath === true) ? o.$path : '';
      o.retainPath = false;
      if (isIE) {
        css1 = {
          marginLeft: 20
        };
      } else {
        css1 = {
          opacity: 0,
          marginLeft: 20
        };
      }
      var $ul = jQuery(['li.', o.hoverClass].join(''), this).add(this).not(not).removeClass(o.hoverClass).find('>ul').animate(css1, 150, 'swing', function () {
        jQuery(this).css({
          display: "none"
        })
      });
      o.onHide.call($ul);
      return this;
    },
    showSuperfishUl: function () {
      var o = sf.op,
        $ul = this.addClass(o.hoverClass).find('>ul:hidden').css('visibility', 'visible');
      o.onBeforeShow.call($ul);
      if (isIE) {
        css1 = {
          display: "block",
          marginLeft: 20
        };
        css2 = {
          marginLeft: 0
        };
      } else {
        css1 = {
          display: "block",
          opacity: 0,
          marginLeft: 20
        };
        css2 = {
          opacity: 1,
          marginLeft: 0
        };
      }
      $ul.css(css1).animate(css2, 150, 'swing', function () {
        o.onShow.call($ul);
      });
      return this;
    }
  });
})(jQuery);

// simple tooltips
function webshot(target_items, name) {
  jQuery(target_items).each(function (i) {
    jQuery("body").append("<div class='" + name + "' id='" + name + i + "'><img src='http://images.websnapr.com/?size=s&amp;url=" + jQuery(this).attr('href') + "' width='202' height='152' /></div>");
    var my_tooltip = jQuery("#" + name + i);
    jQuery(this).mouseover(function () {
      my_tooltip.css({
        opacity: 1,
        display: "none"
      }).fadeIn(333);
    }).mousemove(function (kmouse) {
      my_tooltip.css({
        left: kmouse.pageX + 15,
        top: kmouse.pageY + 15
      });
    }).mouseout(function () {
      my_tooltip.fadeOut(333);
    });
  });
}

// optimized minitabs
(function (jQuery) {
  jQuery.fn.minitabs = function (options) {
    jQuery.fn.minitabs.defaults = {
      content: '.sections',
      nav: 'ul:first',
      effect: 'top',
      speed: 333,
      cookies: true
    };
    var o = jQuery.extend({},
    jQuery.fn.minitabs.defaults, options);
    return this.each(function () {
      var $tabs = jQuery(this);
      var $instance = $tabs.attr('id');
      var $nav = jQuery('#' + $instance + ' ' + o.nav);
      if (o.cookies) { // check for the active tab cookie
        var cookieID = $instance;
        var cookieState = jQuery.cookie(cookieID);
      } // hide all sections
      $tabs.find(o.content + " >div:gt(0)").hide();
      if (o.cookies && (cookieState != null)) { // if we have a cookie then show the section according to its value
        $nav.find('li.' + cookieState).addClass("active");
        var link = $nav.find('li.' + cookieState + ' a');
        var section = link.attr('href');
        $tabs.find(o.content + ' div' + section).show();
      } else { // if not, show the 1st section
        $nav.find('li:last').addClass("active");
        $tabs.find(o.content + ' div:first').show();
      }
      $nav.find("li>a").click(function () {
        if (!jQuery(this).parent('li').hasClass("active")) {
          $nav.find('li').removeClass("active");
          if (o.cookies) {
            var cookieValue = jQuery(this).parent('li').attr("class");
            jQuery.cookie(cookieID, cookieValue, {
              path: '/'
            });
          }
          jQuery(this).parent('li').addClass("active");
          jQuery(this).blur();
          var re = /([_\-\w]+$)/i;
          var target = jQuery('#' + $instance + ' #' + re.exec(this.href)[1]);
          if (o.effect == 'slide') $tabs.find(o.content + " >div").slideUp(o.effect);
          else $tabs.find(o.content + " >div").hide();
          switch (o.effect) {
          case 'top':
            if (isIE) target.css({
              top: -300
            }).show().animate({
              top: 0
            },
            o.speed, 'easeOutQuart');
            else target.css({
              opacity: 0,
              top: -300
            }).show().animate({
              opacity: 1,
              top: 0
            },
            o.speed, 'easeOutQuart');
            break;
          case 'slide':
            target.slideDown(o.speed);
            break;
          case 'height':
            originalHeight = target.height();
            target.css({
              opacity: 0,
              height: 0
            }).show().animate({
              opacity: 1,
              height: originalHeight
            },
            o.speed, 'easeOutQuart');
            break;
          }
          return false;
        }
      })
    });
  };
})(jQuery);

// better alternative to slidetoggle
jQuery.fn.slideFade = function(type, speed, easing, callback) {
  if (isIE) return this.animate({height: type}, speed, easing, callback); // no fading on IE because of the text AA bug
  else return this.animate({opacity: type, height: type}, speed, easing, callback);
};


/*
// based on "Read More Right Here" plugin by William King - http://www.wooliet.com
function setup_readmorelink() {

  jQuery('a.more-link').each(function () {

    var url = jQuery(this).attr('href');
    var pos = url.lastIndexOf('-');

    jQuery(this).bind('click', {
      "el": jQuery(this),
      "url": url,
      "postid": url.substr(++pos)
    },
    ajaxClick);
  });


  function ajaxClick(event) {
    var theEl = event.data.el;
    //var loading = jQuery(document.createElement('span')).attr('class','loading');

    // append and display the loading image
    // after the 'more' anchor element
    theEl.html('Loading...');

    // perform the ajax request
    jQuery.ajax({
      type: "POST",
      url: event.data.url,
      dataType: "html",
      cache: false,
      data: {
        'redirect-more-link': '1',
        'postid': event.data.postid
      },
      error: function (request, textStatus, errorThrown) {
        //data = "<p class=\"error\">Sorry! There was an error retrieving content.<br>Click again to be taken to this entry's page.</p>";
        ajaxFinished(theEl, data, true);

      },
      success: function (data, textStatus) {
        ajaxFinished(theEl, data, false);
      }

    });
    // keep anchor 'click' event propagating
    return false;
  }



  function ajaxFinished(theEl, result, bError) {
    var newEl = jQuery("<p>").html(result).hide(),
      tempFunc, funcObjToggle = function () {
      newEl.find('object').each(

      function () {
        jQuery(this).toggle();
      });
    },
      funcArray = new Array(

    function () {
      funcObjToggle();
      newEl.slideFade('toggle',333, 'easeOutCubic', function () {

        theEl.html('More &gt;');
      });
    },

    function () {
      newEl.slideFade('toggle',333, 'easeOutCubic', function () {

        theEl.html('&lt; Less');
        funcObjToggle();

      });
    });

    theEl.unbind('click', ajaxClick);

    // If IE 7 and newer, and the new content has an
    //	embedded object (e.g. flash video), we have
    //	to just re-direct to the single page entry.
    //	The object will NOT display.
    if (isIE) if (hasEmbed(newEl)) {
      window.location = theEl.attr('href');
      return;
    }

    newEl.find('object').each(

    function () {
      jQuery(this).hide();
    });

    // put the new content after the more link
    theEl.after(newEl);

    newEl.slideFade('toggle',333, 'easeOutCubic', function () {

      theEl.html('&lt; Less');
      funcObjToggle();
    });

    // if no error, 'more' link slides the content in and
    // out of view; otherwise future clicks behave normally
    // and take user to the single post page
    if (!bError) {
      theEl.click(function () {
        funcArray[0]();

        // Swap functions to execute. When 'collapse'
        //	want object to hide first. When expand want
        //	object to show last.
        tempFunc = funcArray[0];
        funcArray[0] = funcArray[1];
        funcArray[1] = tempFunc;

        // keep anchor 'click' event propagating
        return false;
      });
    }
  }

  function hasEmbed(el) {
    var result = false;
    el.find('object').each(

    function () {
      result = true;
      console.log(this);
      return;
    });

    return result;
  }

}
*/



function setup_readmorelink() {

  jQuery('a.more-link').click(function () {

    var target_url = jQuery(this).attr('href');
    var pos = target_url.lastIndexOf('-');
    var postid = target_url.substr(++pos);

    var link = jQuery(this);
    var content = jQuery(this).parent();
    var more_content = jQuery("<div></div>")

    link.html('').addClass('loading');

    // perform the ajax request
    jQuery.ajax({
      type: "POST",
      url: target_url,
      dataType: "html",
      cache: false,
      data: {
        'redirect-more-link': 1,
        'postid': postid
      },
      error: function (request, textStatus, errorThrown) {
        window.location = link.attr('href'); // go to link

      },
      success: function (data, textStatus) {
        more_content.html(data).hide();
        content.append(more_content);
        link.remove();
        more_content.slideFade('show',333, 'easeOutCubic', function () {
      });

      }

    });
    return false;
  });
}



/*!
 * jQuery Form Plugin
 * version: 2.43 (12-MAR-2010)
 * @requires jQuery v1.3.2 or later
 *
 * Examples and documentation at: http://malsup.com/jquery/form/
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
;(function($) {

/*
	Usage Note:
	-----------
	Do not use both ajaxSubmit and ajaxForm on the same form.  These
	functions are intended to be exclusive.  Use ajaxSubmit if you want
	to bind your own submit handler to the form.  For example,

	$(document).ready(function() {
		$('#myForm').bind('submit', function() {
			$(this).ajaxSubmit({
				target: '#output'
			});
			return false; // <-- important!
		});
	});

	Use ajaxForm when you want the plugin to manage all the event binding
	for you.  For example,

	$(document).ready(function() {
		$('#myForm').ajaxForm({
			target: '#output'
		});
	});

	When using ajaxForm, the ajaxSubmit function will be invoked for you
	at the appropriate time.
*/

/**
 * ajaxSubmit() provides a mechanism for immediately submitting
 * an HTML form using AJAX.
 */
$.fn.ajaxSubmit = function(options) {
	// fast fail if nothing selected (http://dev.jquery.com/ticket/2752)
	if (!this.length) {
		log('ajaxSubmit: skipping submit process - no element selected');
		return this;
	}

	if (typeof options == 'function')
		options = { success: options };

	var url = $.trim(this.attr('action'));
	if (url) {
		// clean url (don't include hash vaue)
		url = (url.match(/^([^#]+)/)||[])[1];
   	}
   	url = url || window.location.href || '';

	options = $.extend({
		url:  url,
		type: this.attr('method') || 'GET',
		iframeSrc: /^https/i.test(window.location.href || '') ? 'javascript:false' : 'about:blank'
	}, options || {});

	// hook for manipulating the form data before it is extracted;
	// convenient for use with rich editors like tinyMCE or FCKEditor
	var veto = {};
	this.trigger('form-pre-serialize', [this, options, veto]);
	if (veto.veto) {
		log('ajaxSubmit: submit vetoed via form-pre-serialize trigger');
		return this;
	}

	// provide opportunity to alter form data before it is serialized
	if (options.beforeSerialize && options.beforeSerialize(this, options) === false) {
		log('ajaxSubmit: submit aborted via beforeSerialize callback');
		return this;
	}

	var a = this.formToArray(options.semantic);
	if (options.data) {
		options.extraData = options.data;
		for (var n in options.data) {
		  if(options.data[n] instanceof Array) {
			for (var k in options.data[n])
			  a.push( { name: n, value: options.data[n][k] } );
		  }
		  else
			 a.push( { name: n, value: options.data[n] } );
		}
	}

	// give pre-submit callback an opportunity to abort the submit
	if (options.beforeSubmit && options.beforeSubmit(a, this, options) === false) {
		log('ajaxSubmit: submit aborted via beforeSubmit callback');
		return this;
	}

	// fire vetoable 'validate' event
	this.trigger('form-submit-validate', [a, this, options, veto]);
	if (veto.veto) {
		log('ajaxSubmit: submit vetoed via form-submit-validate trigger');
		return this;
	}

	var q = $.param(a);

	if (options.type.toUpperCase() == 'GET') {
		options.url += (options.url.indexOf('?') >= 0 ? '&' : '?') + q;
		options.data = null;  // data is null for 'get'
	}
	else
		options.data = q; // data is the query string for 'post'

	var $form = this, callbacks = [];
	if (options.resetForm) callbacks.push(function() { $form.resetForm(); });
	if (options.clearForm) callbacks.push(function() { $form.clearForm(); });

	// perform a load on the target only if dataType is not provided
	if (!options.dataType && options.target) {
		var oldSuccess = options.success || function(){};
		callbacks.push(function(data) {
			var fn = options.replaceTarget ? 'replaceWith' : 'html';
			$(options.target)[fn](data).each(oldSuccess, arguments);
		});
	}
	else if (options.success)
		callbacks.push(options.success);

	options.success = function(data, status, xhr) { // jQuery 1.4+ passes xhr as 3rd arg
		for (var i=0, max=callbacks.length; i < max; i++)
			callbacks[i].apply(options, [data, status, xhr || $form, $form]);
	};

	// are there files to upload?
	var files = $('input:file', this).fieldValue();
	var found = false;
	for (var j=0; j < files.length; j++)
		if (files[j])
			found = true;

	var multipart = false;
//	var mp = 'multipart/form-data';
//	multipart = ($form.attr('enctype') == mp || $form.attr('encoding') == mp);

	// options.iframe allows user to force iframe mode
	// 06-NOV-09: now defaulting to iframe mode if file input is detected
   if ((files.length && options.iframe !== false) || options.iframe || found || multipart) {
	   // hack to fix Safari hang (thanks to Tim Molendijk for this)
	   // see:  http://groups.google.com/group/jquery-dev/browse_thread/thread/36395b7ab510dd5d
	   if (options.closeKeepAlive)
		   $.get(options.closeKeepAlive, fileUpload);
	   else
		   fileUpload();
	   }
   else
	   $.ajax(options);

	// fire 'notify' event
	this.trigger('form-submit-notify', [this, options]);
	return this;


	// private function for handling file uploads (hat tip to YAHOO!)
	function fileUpload() {
		var form = $form[0];

		if ($(':input[name=submit]', form).length) {
			alert('Error: Form elements must not be named "submit".');
			return;
		}

		var opts = $.extend({}, $.ajaxSettings, options);
		var s = $.extend(true, {}, $.extend(true, {}, $.ajaxSettings), opts);

		var id = 'jqFormIO' + (new Date().getTime());
		var $io = $('<iframe id="' + id + '" name="' + id + '" src="'+ opts.iframeSrc +'" onload="(jQuery(this).data(\'form-plugin-onload\'))()" />');
		var io = $io[0];

		$io.css({ position: 'absolute', top: '-1000px', left: '-1000px' });

		var xhr = { // mock object
			aborted: 0,
			responseText: null,
			responseXML: null,
			status: 0,
			statusText: 'n/a',
			getAllResponseHeaders: function() {},
			getResponseHeader: function() {},
			setRequestHeader: function() {},
			abort: function() {
				this.aborted = 1;
				$io.attr('src', opts.iframeSrc); // abort op in progress
			}
		};

		var g = opts.global;
		// trigger ajax global events so that activity/block indicators work like normal
		if (g && ! $.active++) $.event.trigger("ajaxStart");
		if (g) $.event.trigger("ajaxSend", [xhr, opts]);

		if (s.beforeSend && s.beforeSend(xhr, s) === false) {
			s.global && $.active--;
			return;
		}
		if (xhr.aborted)
			return;

		var cbInvoked = false;
		var timedOut = 0;

		// add submitting element to data if we know it
		var sub = form.clk;
		if (sub) {
			var n = sub.name;
			if (n && !sub.disabled) {
				opts.extraData = opts.extraData || {};
				opts.extraData[n] = sub.value;
				if (sub.type == "image") {
					opts.extraData[n+'.x'] = form.clk_x;
					opts.extraData[n+'.y'] = form.clk_y;
				}
			}
		}

		// take a breath so that pending repaints get some cpu time before the upload starts
		function doSubmit() {
			// make sure form attrs are set
			var t = $form.attr('target'), a = $form.attr('action');

			// update form attrs in IE friendly way
			form.setAttribute('target',id);
			if (form.getAttribute('method') != 'POST')
				form.setAttribute('method', 'POST');
			if (form.getAttribute('action') != opts.url)
				form.setAttribute('action', opts.url);

			// ie borks in some cases when setting encoding
			if (! opts.skipEncodingOverride) {
				$form.attr({
					encoding: 'multipart/form-data',
					enctype:  'multipart/form-data'
				});
			}

			// support timout
			if (opts.timeout)
				setTimeout(function() { timedOut = true; cb(); }, opts.timeout);

			// add "extra" data to form if provided in options
			var extraInputs = [];
			try {
				if (opts.extraData)
					for (var n in opts.extraData)
						extraInputs.push(
							$('<input type="hidden" name="'+n+'" value="'+opts.extraData[n]+'" />')
								.appendTo(form)[0]);

				// add iframe to doc and submit the form
				$io.appendTo('body');
				$io.data('form-plugin-onload', cb);
				form.submit();
			}
			finally {
				// reset attrs and remove "extra" input elements
				form.setAttribute('action',a);
				t ? form.setAttribute('target', t) : $form.removeAttr('target');
				$(extraInputs).remove();
			}
		};

		if (opts.forceSync)
			doSubmit();
		else
			setTimeout(doSubmit, 10); // this lets dom updates render

		var domCheckCount = 100;

		function cb() {
			if (cbInvoked)
				return;

			var ok = true;
			try {
				if (timedOut) throw 'timeout';
				// extract the server response from the iframe
				var data, doc;

				doc = io.contentWindow ? io.contentWindow.document : io.contentDocument ? io.contentDocument : io.document;

				var isXml = opts.dataType == 'xml' || doc.XMLDocument || $.isXMLDoc(doc);
				log('isXml='+isXml);
				if (!isXml && (doc.body == null || doc.body.innerHTML == '')) {
				 	if (--domCheckCount) {
						// in some browsers (Opera) the iframe DOM is not always traversable when
						// the onload callback fires, so we loop a bit to accommodate
				 		log('requeing onLoad callback, DOM not available');
						setTimeout(cb, 250);
						return;
					}
					log('Could not access iframe DOM after 100 tries.');
					return;
				}

				log('response detected');
				cbInvoked = true;
				xhr.responseText = doc.body ? doc.body.innerHTML : null;
				xhr.responseXML = doc.XMLDocument ? doc.XMLDocument : doc;
				xhr.getResponseHeader = function(header){
					var headers = {'content-type': opts.dataType};
					return headers[header];
				};

				if (opts.dataType == 'json' || opts.dataType == 'script') {
					// see if user embedded response in textarea
					var ta = doc.getElementsByTagName('textarea')[0];
					if (ta)
						xhr.responseText = ta.value;
					else {
						// account for browsers injecting pre around json response
						var pre = doc.getElementsByTagName('pre')[0];
						if (pre)
							xhr.responseText = pre.innerHTML;
					}
				}
				else if (opts.dataType == 'xml' && !xhr.responseXML && xhr.responseText != null) {
					xhr.responseXML = toXml(xhr.responseText);
				}
				data = $.httpData(xhr, opts.dataType);
			}
			catch(e){
				log('error caught:',e);
				ok = false;
				xhr.error = e;
				$.handleError(opts, xhr, 'error', e);
			}

			// ordering of these callbacks/triggers is odd, but that's how $.ajax does it
			if (ok) {
				opts.success(data, 'success');
				if (g) $.event.trigger("ajaxSuccess", [xhr, opts]);
			}
			if (g) $.event.trigger("ajaxComplete", [xhr, opts]);
			if (g && ! --$.active) $.event.trigger("ajaxStop");
			if (opts.complete) opts.complete(xhr, ok ? 'success' : 'error');

			// clean up
			setTimeout(function() {
				$io.removeData('form-plugin-onload');
				$io.remove();
				xhr.responseXML = null;
			}, 100);
		};

		function toXml(s, doc) {
			if (window.ActiveXObject) {
				doc = new ActiveXObject('Microsoft.XMLDOM');
				doc.async = 'false';
				doc.loadXML(s);
			}
			else
				doc = (new DOMParser()).parseFromString(s, 'text/xml');
			return (doc && doc.documentElement && doc.documentElement.tagName != 'parsererror') ? doc : null;
		};
	};
};



/**
 * formToArray() gathers form element data into an array of objects that can
 * be passed to any of the following ajax functions: $.get, $.post, or load.
 * Each object in the array has both a 'name' and 'value' property.  An example of
 * an array for a simple login form might be:
 *
 * [ { name: 'username', value: 'jresig' }, { name: 'password', value: 'secret' } ]
 *
 * It is this array that is passed to pre-submit callback functions provided to the
 * ajaxSubmit() and ajaxForm() methods.
 */
$.fn.formToArray = function(semantic) {
	var a = [];
	if (this.length == 0) return a;

	var form = this[0];
	var els = semantic ? form.getElementsByTagName('*') : form.elements;
	if (!els) return a;
	for(var i=0, max=els.length; i < max; i++) {
		var el = els[i];
		var n = el.name;
		if (!n) continue;

		if (semantic && form.clk && el.type == "image") {
			// handle image inputs on the fly when semantic == true
			if(!el.disabled && form.clk == el) {
				a.push({name: n, value: $(el).val()});
				a.push({name: n+'.x', value: form.clk_x}, {name: n+'.y', value: form.clk_y});
			}
			continue;
		}

		var v = $.fieldValue(el, true);
		if (v && v.constructor == Array) {
			for(var j=0, jmax=v.length; j < jmax; j++)
				a.push({name: n, value: v[j]});
		}
		else if (v !== null && typeof v != 'undefined')
			a.push({name: n, value: v});
	}

	if (!semantic && form.clk) {
		// input type=='image' are not found in elements array! handle it here
		var $input = $(form.clk), input = $input[0], n = input.name;
		if (n && !input.disabled && input.type == 'image') {
			a.push({name: n, value: $input.val()});
			a.push({name: n+'.x', value: form.clk_x}, {name: n+'.y', value: form.clk_y});
		}
	}
	return a;
};



/**
 * Returns the value(s) of the element in the matched set.  For example, consider the following form:

 */
$.fn.fieldValue = function(successful) {
	for (var val=[], i=0, max=this.length; i < max; i++) {
		var el = this[i];
		var v = $.fieldValue(el, successful);
		if (v === null || typeof v == 'undefined' || (v.constructor == Array && !v.length))
			continue;
		v.constructor == Array ? $.merge(val, v) : val.push(v);
	}
	return val;
};

/**
 * Returns the value of the field element.
 */
$.fieldValue = function(el, successful) {
	var n = el.name, t = el.type, tag = el.tagName.toLowerCase();
	if (typeof successful == 'undefined') successful = true;

	if (successful && (!n || el.disabled || t == 'reset' || t == 'button' ||
		(t == 'checkbox' || t == 'radio') && !el.checked ||
		(t == 'submit' || t == 'image') && el.form && el.form.clk != el ||
		tag == 'select' && el.selectedIndex == -1))
			return null;

	if (tag == 'select') {
		var index = el.selectedIndex;
		if (index < 0) return null;
		var a = [], ops = el.options;
		var one = (t == 'select-one');
		var max = (one ? index+1 : ops.length);
		for(var i=(one ? index : 0); i < max; i++) {
			var op = ops[i];
			if (op.selected) {
				var v = op.value;
				if (!v) // extra pain for IE...
					v = (op.attributes && op.attributes['value'] && !(op.attributes['value'].specified)) ? op.text : op.value;
				if (one) return v;
				a.push(v);
			}
		}
		return a;
	}
	return el.value;
};

})(jQuery);


jQuery.fn.extend({
	plainHtml: function(value) {
		if (value == undefined) {
			return (this[0] ? this[0].innerHTML : null);
		}
		else if(this[0]) {
			try {
				this[0].innerHTML = value;
			} catch(e) {}
			return this;
		}
	}
});




  function setup_comment_controls(){
    jQuery.fn.appendVal = function (txt) {
      return this.each(function () {
        this.value += txt;
      });
    };

    comment_class = 'li.comment';
    target_id = '#comment';

    jQuery('a.quote').livequery("click", function () {
        $c = jQuery(this).parents(comment_class).find('.comment-text');
        $author = jQuery(this).parents(comment_class).find('.comment-author');
        $cid = jQuery(this).parents(comment_class).find('.comment-id').attr('href');
        jQuery(target_id).appendVal('<blockquote>\n<a href="' + $cid + '">\n<strong><em>' + $author.html() + ':</em></strong>\n</a>\n ' + $c.html() + '</blockquote>');
        jQuery(target_id).focus();
        return false;
      })


  // replaces wp's comment reply script
  jQuery("a.reply").livequery("click", function () {
    linkClass = jQuery(this).attr('id');
    var pos = linkClass.lastIndexOf('-');
    var targetID = linkClass.substr(++pos);

    jQuery("#respond").hide();

    jQuery('#comment_parent').attr('value',targetID);
    // jQuery('#comment_post_ID').attr('value',postID);

    jQuery("#cancel-reply").show();
    jQuery("#respond").appendTo('#comment-body-'+targetID).show(0,function(){

      // move cursor in textarea, at the end of the text
      jQuery('#comment').each(function(){
       if (this.createTextRange) {
        var r = this.createTextRange();
        r.collapse(false);
        r.select();
       }
       jQuery(this).focus();
      });

    });

    return false;
  });

  // cancel-reply
  jQuery("#cancel-reply").livequery("click", function (event) {
    jQuery("#respond").hide();
    jQuery('#comment_parent').attr('value','0');
    //  jQuery('#comment_post_ID').attr('value',postID);

    jQuery("#cancel-reply").hide();
    jQuery("#respond").appendTo('#section-comments').show(0,function(){

     // move cursor in textarea, at the end of the text
     jQuery('#comment').each(function(){
      if (this.createTextRange) {
       var r = this.createTextRange();
       r.collapse(false);
       r.select();
      }
      jQuery(this).focus();
     });

    });
    return false;
  });

  jQuery(".comment-head").bubble({timeout: 0});
}

  function setup_comment_ajax(){

  jQuery(".comment-navigation a").livequery("click", function () {
    $link = jQuery(this);
    var link_url = $link.attr('href');


//	var commentPage = 1;
//	if (/comment-page-/i.test(link_url)) {
//		commentPage = link_url.split(/^.*comment-page-/)[1];
//        commentPage = commentPage.split(/(\/|#|&).*$/)[0];
//	} else if (/cpage=/i.test(link_url)) {
//		commentPage = link_url.split(/^.*cpage=/)[1];
//        commentPage = commentPage.split(/(\/|#|&).*$/)[0];
//	}

	var ajax_url = link_url.split(/#.*$/)[0];
	//ajax_url += /\?/i.test(link_url) ? '&' : '?';

	jQuery('.comment-navigation').before('<div id="pagination_status"></div>');
	var status = jQuery('#pagination_status');

    jQuery.ajax({
        url: ajax_url,
        type: "GET",
        data: ({action: 'commentnavi'/*, page: commentPage*/}),
		beforeSend: function() {
			status.empty();
            $link.removeAttr('href').addClass('loading').html('&nbsp;');
            jQuery("ul#comments").css('opacity', 1).animate({ opacity: 0.5 }, 333,'easeOutQuart');

		}, // end beforeSubmit

		error: function(request){

		        window.location=target_url;
				return false;
		},

        success: function(data) {
           try {
                var response = jQuery("<div />").plainHtml(data);
                    $comm = response.find('#comments-wrap').plainHtml();

                jQuery("ul#comments #cancel-reply").hide();
                jQuery("ul#comments").find("#respond").appendTo('#section-comments');

			 	jQuery('#comments-wrap').plainHtml($comm);
                jQuery("ul#comments").css('opacity', 0.5).animate({ opacity: 1 }, 333,'easeOutQuart');
				status.empty();

            } catch (e) {

				status.attr("class","error").plainHtml(e);

            }

                if (window.AjaxEditComments) AjaxEditComments.init();

			}

		});

    return false;
  });





//  posts
//
//    jQuery(".page-navigation a").livequery("click", function () {
//    $link = jQuery(this);
//    var link_url = $link.attr('href');
//
//	var ajax_url = link_url.split(/#.*$/)[0];
//	//ajax_url += /\?/i.test(link_url) ? '&' : '?';
//
//	jQuery('.page-navigation').before('<div id="pagination_status"></div>');
//	var status = jQuery('#pagination_status');
//
//    jQuery.ajax({
//        url: ajax_url,
//        type: "GET",
//        data: ({action: 'pagenavi'/*, page: commentPage*/}),
//		beforeSend: function() {
//			status.empty();
//            $link.removeAttr('href').addClass('loading').html('&nbsp;');
//            jQuery("#primary-content").css('opacity', 1).animate({ opacity: 0.5 }, 333,'easeOutQuart');
//
//		}, // end beforeSubmit
//
//		error: function(request){
//
//		        window.location = target_url;
//				return false;
//		},
//
//        success: function(data) {
//           try {
//                var response = jQuery("<div />").plainHtml(data);
//                    $comm = response.find('#primary-content .blocks').plainHtml();
//
//			 	jQuery('#primary-content .blocks').plainHtml($comm);
//                jQuery("#primary-content").css('opacity', 0.5).animate({ opacity: 1 }, 333,'easeOutQuart');
//				status.empty();
//
//            } catch (e) {
//
//				status.attr("class","error").plainHtml(e);
//
//            }
//
//			}
//		});
//
//    return false;
//  });




  // comment submit
	jQuery('#respond form').before('<div id="comment_post_status"></div>');
	var form = jQuery('#commentform');
	var status = jQuery('#comment_post_status');
    var submit_text = jQuery('#submit').val();


    form.submit(function(evt) {

    jQuery(this).ajaxSubmit({

		beforeSubmit: function() {
			status.empty();
			jQuery('#submit, #comment').attr('disabled','disabled');
          	jQuery('#submit').val($lang[0]);
		}, // end beforeSubmit

		error: function(request){
				status.empty();
				if (request.responseText.search(/<title>WordPress &rsaquo; Error<\/title>/) != -1) {
					var data = request.responseText.match(/<p>(.*)<\/p>/);
					status.attr("class","error").plainHtml(data[1]);
				} else {
					var data = request.responseText;
					status.attr("class","error").plainHtml(data[1]);
				}
				jQuery('#submit').val(submit_text);
				jQuery('#submit, #comment').removeAttr("disabled");
				return false;
		},

        success: function(data) {
           try {
                var response = jQuery("<div />").plainHtml(data);
                    $comm = response.find('#comments-wrap').plainHtml();
                    // if is a nested comment
                    jQuery("ul#comments #cancel-reply").hide();
                    jQuery("ul#comments").find("#respond").appendTo('#section-comments');

			 		jQuery('#comments-wrap').plainHtml($comm);
				    status.empty();
                    status.attr("class","success").plainHtml($lang[1]);
                    jQuery('#comment').val('');
                    jQuery('#submit').val($lang[2]);
                    jQuery('#submit, #comment').removeAttr("disabled");
                    jQuery('#comment_parent').attr('value', '0');

            } catch (e) {
				jQuery('#submit, #comment').removeAttr("disabled");
				status.attr("class","error").plainHtml(e);

            }

            if (window.AjaxEditComments) AjaxEditComments.init();

			}

		});

        return false;

	});



}


(function( $ ){
  $.cssRule = function (Selector, Property, Value) {

    // Selector == {}
    if(typeof Selector == "object"){
      jQuery.each(Selector, function(NewSelector, NewProperty){
        jQuery.cssRule(NewSelector, NewProperty);
      });
      return;
    }

    // Selector == "body:background:#F99"
    if((typeof Selector == "string") && (Selector.indexOf(":") > -1)
      && (Property == undefined) && (Value == undefined)){
      Data = Selector.split("{");
      Data[1] = Data[1].replace(/\}/, "");
      jQuery.cssRule(jQuery.trim(Data[0]), jQuery.trim(Data[1]));
      return;
    }

    // Check for multi-selector, [ IE don't accept multi-selector on this way, we need to split ]
    if((typeof Selector == "string") && (Selector.indexOf(",") > -1)){
      Multi = Selector.split(",");
      for(x = 0; x < Multi.length; x++){
        Multi[x] = jQuery.trim(Multi[x]);
        if(Multi[x] != "")
          jQuery.cssRule(Multi[x], Property, Value);
      }

      return;
    }

    // Porperty == {} or []
    if(typeof Property == "object"){

      // Is {}
      if(Property.length == undefined){

        // Selector, {}
        jQuery.each(Property, function(NewProperty, NewValue){
          jQuery.cssRule(Selector + " " + NewProperty, NewValue);
        });

      // Is [Prop, Value]
      }else if((Property.length == 2) && (typeof Property[0] == "string") &&
        (typeof Property[1] == "string")){
        jQuery.cssRule(Selector, Property[0], Property[1]);

      // Is array of settings
      }else{
        for(x1 = 0; x1 < Property.length; x1++){
          jQuery.cssRule(Selector, Property[x1], Value);
        }
      }

      return;
    }

    // Parse for property at CSS Style "{property:value}"
    if((typeof Property == "string") && (Property.indexOf("{") > -1)
       && (Property.indexOf("}") > -1)){
      Property = Property.replace(/\{/, "").replace(/\}/, "");
    }

    // Check for multiple properties
    if((typeof Property == "string") && (Property.indexOf(";") > -1)){
      Multi1 = Property.split(";");
      for(x2 = 0; x2 < Multi1.length; x2++){
        jQuery.cssRule(Selector, Multi1[x2], undefined);
      }
      return;
    }

    // Check for property:value
    if((typeof Property == "string") && (Property.indexOf(":") > -1)){
      Multi3 = Property.split(":");
      jQuery.cssRule(Selector, Multi3[0], Multi3[1]);
      return;
    }

    //********************************************
    // Logical CssRule additions
    // Check for multiple logical properties [ "padding,margin,border:0px" ]
    if((typeof Property == "string") && (Property.indexOf(",") > -1)){
      Multi2 = Property.split(",");
      for(x3 = 0; x3 < Multi2.length; x3++){
        jQuery.cssRule(Selector, Multi2[x3], Value);
      }
      return;
    }


    if((Property == undefined) || (Value == undefined))
      return;

    Selector = jQuery.trim(Selector);
    Property = jQuery.trim(Property);
    Value = jQuery.trim(Value);

    if((Property == "") || (Value == ""))
      return;

    // adjusts on property
    if(jQuery.browser.msie){
      // for IE (@.@)^^^
      switch(Property){
        case "float": Property = "style-float"; break;
      }
    }else{
      // CSS rights
      switch(Property){
        case "float": Property = "css-float"; break;
      }
    }

    CssProperty = (Property || "").replace(/\-(\w)/g, function(m, c){ return (c.toUpperCase()); });



    if(Property && Value){
      for(var i = 0; i < document.styleSheets.length; i++){
        WorkerStyleSheet = document.styleSheets[i];
        if(WorkerStyleSheet.insertRule){
          Rules = (WorkerStyleSheet.cssRules || WorkerStyleSheet.rules);
          WorkerStyleSheet.insertRule(Selector + "{ " + Property + ":" + Value + "; }", Rules.length);
        }else if(WorkerStyleSheet.addRule){
          WorkerStyleSheet.addRule(Selector, Property + ":" + Value + ";", 0);
        }else{
          throw new Error("Add/insert not enabled.");
        }
      }
    }
  };
})( jQuery );





 jQuery.fn.extend({
    highlight: function(search, insensitive, hls_class){
      var regex = new RegExp("(<[^>]*>)|(\\b"+ search.replace(/([-.*+?^${}()|[\]\/\\])/g,"\\$1") +")", insensitive ? "ig" : "g");
      return this.html(this.html().replace(regex, function(a, b, c){
        return (a.charAt(0) == "<") ? a : "<strong class=\""+ hls_class +"\">" + c + "</strong>";
      }));
    }
  });



/*
* Print Element Plugin 1.0
*
* Copyright (c) 2009 Erik Zaadi
*
* Inspired by PrintArea (http://plugins.jquery.com/project/PrintArea) and
* http://stackoverflow.com/questions/472951/how-do-i-print-an-iframe-from-javascript-in-safari-chrome
*
*  jQuery plugin page : http://plugins.jquery.com/project/printElement
*  Wiki : http://wiki.github.com/erikzaadi/jQueryPlugins/jqueryprintelement
*  Home Page : http://erikzaadi.github.com/jQueryPlugins/jQuery.printElement
*
*  Thanks to David B (http://github.com/ungenio) and icgJohn (http://www.blogger.com/profile/11881116857076484100)
*  For their great contributions!
*
* Dual licensed under the MIT and GPL licenses:
*   http://www.opensource.org/licenses/mit-license.php
*   http://www.gnu.org/licenses/gpl.html
*
*   Note, Iframe Printing is not supported in Opera and Chrome 3.0, a popup window will be shown instead
*/
;
(function($){
    $.fn.printElement = function(options){
        var mainOptions = $.extend({}, $.fn.printElement.defaults, options);
        //iframe mode is not supported for opera and chrome 3.0 (it prints the entire page).
        //http://www.google.com/support/forum/p/Webmasters/thread?tid=2cb0f08dce8821c3&hl=en
        if (mainOptions.printMode == 'iframe') {
            if ($.browser.opera || (/chrome/.test(navigator.userAgent.toLowerCase())))
                mainOptions.printMode = 'popup';
        }
        //Remove previously printed iframe if exists
        $("[id^='printElement_']").remove();

        return this.each(function(){
            //Support Metadata Plug-in if available
            var opts = $.meta ? $.extend({}, mainOptions, $this.data()) : mainOptions;
            _printElement($(this), opts);
        });
    };
    $.fn.printElement.defaults = {
        printMode: 'iframe', //Usage : iframe / popup
        pageTitle: '', //Print Page Title
        overrideElementCSS: null,
        /* Can be one of the following 3 options:
         * 1 : boolean (pass true for stripping all css linked)
         * 2 : array of $.fn.printElement.cssElement (s)
         * 3 : array of strings with paths to alternate css files (optimized for print)
         */
        printBodyOptions: {
            styleToAdd: 'padding:10px;margin:10px;background:#fff;', //style attributes to add to the body of print document
            classNameToAdd: '' //css class to add to the body of print document
        },
        leaveOpen: false, // in case of popup, leave the print page open or not
        iframeElementOptions: {
            styleToAdd: 'border:none;position:absolute;width:0px;height:0px;bottom:0px;left:0px;background:#fff;', //style attributes to add to the iframe element
            classNameToAdd: '' //css class to add to the iframe element
        }
    };
    $.fn.printElement.cssElement = {
        href: '',
        media: ''
    };
    function _printElement(element, opts){
        //Create markup to be printed
        var html = _getMarkup(element, opts);

        var popupOrIframe = null;
        var documentToWriteTo = null;
        if (opts.printMode.toLowerCase() == 'popup') {
            popupOrIframe = window.open('about:blank', 'printElementWindow', 'width=650,height=440,scrollbars=yes');
            documentToWriteTo = popupOrIframe.document;
        }
        else {
            //The random ID is to overcome a safari bug http://www.cjboco.com.sharedcopy.com/post.cfm/442dc92cd1c0ca10a5c35210b8166882.html
            var printElementID = "printElement_" + (Math.round(Math.random() * 99999)).toString();
            //Native creation of the element is faster..
            var iframe = document.createElement('IFRAME');
            $(iframe).attr({
                style: opts.iframeElementOptions.styleToAdd,
                id: printElementID,
                className: opts.iframeElementOptions.classNameToAdd,
                frameBorder: 0,
                scrolling: 'no',
                src: 'about:blank'
            });
            document.body.appendChild(iframe);
            documentToWriteTo = (iframe.contentWindow || iframe.contentDocument);
            if (documentToWriteTo.document)
                documentToWriteTo = documentToWriteTo.document;
            iframe = document.frames ? document.frames[printElementID] : document.getElementById(printElementID);
            popupOrIframe = iframe.contentWindow || iframe;
        }
        focus();
        documentToWriteTo.open();
        documentToWriteTo.write(html);
        documentToWriteTo.close();
        _callPrint(popupOrIframe);
    };

    function _callPrint(element){
        if (element && element.printPage)
            element.printPage();
        else
            setTimeout(function(){
                _callPrint(element);
            }, 50);
    }

    function _getElementHTMLIncludingFormElements(element){
        var $element = $(element);
        //Radiobuttons and checkboxes
        $(":checked", $element).each(function(){
            this.setAttribute('checked', 'checked');
        });
        //simple text inputs
        $("input[type='text']", $element).each(function(){
            this.setAttribute('value', $(this).val());
        });
        $("select", $element).each(function(){
            var $select = $(this);
            $("option", $select).each(function(){
                if ($select.val() == $(this).val())
                    this.setAttribute('selected', 'selected');
            });
        });
        $("textarea", $element).each(function(){
            //Thanks http://blog.ekini.net/2009/02/24/jquery-getting-the-latest-textvalue-inside-a-textarea/
            var value = $(this).attr('value');
            if ($.browser.mozilla) {
               if(this.firstChild) this.firstChild.textContent = value;
            }
            else {
                this.innerHTML = value; }
        });
        var elementHtml = $element.html();
        return elementHtml;
    }

    function _getBaseHref(){
        return window.location.protocol + window.location.hostname + window.location.pathname;
    }

    function _getMarkup(element, opts){
        var $element = $(element);
        var elementHtml = _getElementHTMLIncludingFormElements(element);

        var html = new Array();
        html.push('<html><head><title>' + opts.pageTitle + '</title>');
        if (opts.overrideElementCSS) {
            if (opts.overrideElementCSS.length > 0) {
                for (var x = 0; x < opts.overrideElementCSS.length; x++) {
                    var current = opts.overrideElementCSS[x];
                    if (typeof(current) == 'string')
                        html.push('<link type="text/css" rel="stylesheet" href="' + current + '" >');
                    else
                        html.push('<link type="text/css" rel="stylesheet" href="' + current.href + '" media="' + current.media + '" >');
                }
            }
        }
        else {
            $(document).find("link").filter(function(){
                return $(this).attr("rel").toLowerCase() == "stylesheet";
            }).each(function(){
                html.push('<link type="text/css" rel="stylesheet" href="' + $(this).attr("href") + '" media="' + $(this).attr('media') + '" >');
            });
        }
        //Ensure that relative links work
        html.push('<base href="' + _getBaseHref() + '" />');
        html.push('</head><body style="' + opts.printBodyOptions.styleToAdd + '" class="' + opts.printBodyOptions.classNameToAdd + '">');
        html.push('<div class="' + $element.attr('class') + '">' + elementHtml + '</div>');
        html.push('<script type="text/javascript">function printPage(){focus();print();' + ((!$.browser.opera && !opts.leaveOpen && opts.printMode.toLowerCase() == 'popup') ? 'close();' : '') + '}</script>');
        html.push('</body></html>');

        return html.join('');
    };
    })(jQuery);






(function (jQuery) {
	jQuery.fn.addGrid = function (cols, options) {
		var defaults = {
			default_cols: 12,
			z_index: 999,
			img_path: '/images/',
			opacity:.6
		};

		// Extend our default options with those provided.
		var opts = jQuery.extend(defaults, options);

		var cols = cols != null && (cols === 12 || cols === 16) ? cols : 12;
		var cols = cols === opts.default_cols ? '12_col' : '16_col';

		return this.each(function () {
			var $el = jQuery(this);
			var height = $el.height();

			var wrapper = jQuery('<div id="'+opts.grid_id+'"/>')
				.appendTo($el)
				.css({
					'display':'none',
					'position':'absolute',
					'top':0,
					'z-index':(opts.z_index -1),
					'height':height,
					'opacity':opts.opacity,
					'width':'100%'});

			jQuery('<div/>')
				.addClass('container_12')
				.css({
					'margin':'0 auto',
					'width':'960px',
					'height':height,
					'background-image': 'url('+opts.img_path+cols + '.png)',
					'background-repeat': 'repeat-y'})
				.appendTo(wrapper);

				// add toggle
				jQuery('<div>grid on</div>')
					.appendTo($el)
					.css({
						'position':'absolute',
						'top':0,
						'left':0,
						'z-index':opts.z_index,
						'background': '#ed1e24',
						'font-weight': 'bold',
						'text-transform': 'uppercase',
						'color':'#fff',
						'padding': '3px 6px',
                        'cursor' : 'pointer',
						'text-align':'left'
					})
					.hover( function() {
						jQuery(this).css("cursor", "pointer");
					}, function() {
						jQuery(this).css("cursor", "default");
					})
					.toggle( function () {
						jQuery(this).text("grid off");
						jQuery('#'+opts.grid_id).slideDown();
					},
					function() {
						jQuery(this).text("grid on");
						jQuery('#'+opts.grid_id).slideUp();
					});

		});

	};
})(jQuery);












/**
 * jquery.tablesort.js: Tiny table sorting script for jQuery.
 *
 * Fork me on github:
 *   http://github.com/micha/jquery-tablesort/
 *
 * Author:
 *   Micha Niskin <micha@thinkminimo.com>
 *   Copyright 2009, no rights reserved.
 */
(function($) {
  jQuery.fn.makeSortable = function() {
    this.each(function(i_table, v_table) {
      var tbl = $(this).addClass("table-sort");
      var h = '';
      if (tbl.get()[0].tagName.toUpperCase() == "TABLE") {

        $("thead th", tbl).each(function(i_col, v_col) {
          var th = $(this);
          th.click(function() {
            var not  = tbl.find("td table *");
            tbl.find("tbody").not(not).each(function(i_tbody, v_tbody) {

              h = $(v_tbody).find("tr:first").attr('class');

              var rows = $(v_tbody).find("tr").not(not);
              var bak  = [], sort_as = null;
              rows.each(function(i_row, v_row) {
                if(h != '') $(this).removeClass('odd even');
                var td = bak[i_row] =
                  $(this).find("td").not(not).eq(i_col).text()+"";
                var type =
                  (!isNaN(Date.parse(td)) ? "date"
                    : (!isNaN(new Number(td)) ? "number"
                      : (!isNaN(new Number(td.replace(/^\$/,""))) ? "currency"
                        : "string")));
                sort_as = (!!sort_as && sort_as != type ? "string" : type);
              });
              rows = rows.sort(function(a, b) {
                var va = $(a).find("td").not(not).eq(i_col).text();
                var vb = $(b).find("td").not(not).eq(i_col).text();
                if (sort_as == "date") {
                  va = Date.parse(va);
                  vb = Date.parse(vb);
                  return (va < vb ? -1 : (va == vb ? 0 : 1));
                } else if (sort_as == "currency") {
                  return (va.replace(/^\$/, "") - vb.replace(/^\$/, ""));
                } else if (sort_as == "number") {
                  return (va - vb);
                } else if (sort_as == "string") {
                  va = va.toString();
                  vb = vb.toString();
                  return (va < vb ? -1 : (va == vb ? 0 : 1));
                } else {
                  return 0;
                }
              });
              $(".sort-asc", tbl).not(not).removeClass("sort-asc");
              $(".sort-desc", tbl).not(not).removeClass("sort-desc");
              if ((function() {
                for (var i=0; i<rows.size(); i++)
                  if (rows.eq(i).find("td").not(not).eq(i_col).text() != bak[i])
                    return false;
                return true;
              })()) {
                rows = $(rows.get().reverse());
                th.removeClass("sort-asc").addClass("sort-desc");
              } else {
                th.removeClass("sort-desc").addClass("sort-asc");
              }
              $(v_tbody).append(rows);
              if(h == 'odd'){
                $(v_tbody).find('tr:odd').addClass('even');
                $(v_tbody).find('tr:even').addClass('odd');
              }
              if(h == 'even'){
                $(v_tbody).find('tr:odd').addClass('odd');
                $(v_tbody).find('tr:even').addClass('even');
              }

            });
            tbl.trigger('sort');
          });
        });
        tbl.trigger('sort');
      }
    });
    return this;
  }
})(jQuery);


function liteboxCallback() {
  jQuery('.flickrGallery li a').fancyboxlite({
    'zoomSpeedIn': 333,
    'zoomSpeedOut': 333,
    'zoomSpeedChange': 133,
    'easingIn': 'easeOutQuart',
    'easingOut': 'easeInQuart',
    'overlayShow': true,
    'overlayOpacity': 0.75
  });
}

/*
 * FancyBox - jQuery Plugin
 * Simple and fancy lightbox alternative
 *
 * Examples and documentation at: http://fancybox.net
 *
 * Copyright (c) 2008 - 2010 Janis Skarnelis
 *
 * Version: 1.3.1 (05/03/2010)
 * Requires: jQuery v1.3+
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

(function($) {

	var tmp, loading, overlay, wrap, outer, inner, close, nav_left, nav_right,

		selectedIndex = 0, selectedOpts = {}, selectedArray = [], currentIndex = 0, currentOpts = {}, currentArray = [],

		ajaxLoader = null, imgPreloader = new Image(), imgRegExp = /\.(jpg|gif|png|bmp|jpeg)(.*)?$/i, swfRegExp = /[^\.]\.(swf)\s*$/i,

		loadingTimer, loadingFrame = 1,

		start_pos, final_pos, busy = false, shadow = 20, fx = $.extend($('<div/>')[0], { prop: 0 }), titleh = 0,

		isIE6 = !$.support.opacity && !window.XMLHttpRequest,

		/*
		 * Private methods
		 */

		fancybox_abort = function() {
			loading.hide();

			imgPreloader.onerror = imgPreloader.onload = null;

			if (ajaxLoader) {
				ajaxLoader.abort();
			}

			tmp.empty();
		},

		fancybox_error = function() {
			$.fancybox('<p id="fancybox_error">The requested content cannot be loaded.<br />Please try again later.</p>', {
				'scrolling'		: 'no',
				'padding'		: 20,
				'transitionIn'	: 'none',
				'transitionOut'	: 'none'
			});
		},

		fancybox_get_viewport = function() {
			return [ $(window).width(), $(window).height(), $(document).scrollLeft(), $(document).scrollTop() ];
		},

		fancybox_get_zoom_to = function () {
			var view	= fancybox_get_viewport(),
				to		= {},

				margin = currentOpts.margin,
				resize = currentOpts.autoScale,

				horizontal_space	= (shadow + margin) * 2,
				vertical_space		= (shadow + margin) * 2,
				double_padding		= (currentOpts.padding * 2),

				ratio;

			if (currentOpts.width.toString().indexOf('%') > -1) {
				to.width = ((view[0] * parseFloat(currentOpts.width)) / 100) - (shadow * 2) ;
				resize = false;

			} else {
				to.width = currentOpts.width + double_padding;
			}

			if (currentOpts.height.toString().indexOf('%') > -1) {
				to.height = ((view[1] * parseFloat(currentOpts.height)) / 100) - (shadow * 2);
				resize = false;

			} else {
				to.height = currentOpts.height + double_padding;
			}

			if (resize && (to.width > (view[0] - horizontal_space) || to.height > (view[1] - vertical_space))) {
				if (selectedOpts.type == 'image' || selectedOpts.type == 'swf') {
					horizontal_space	+= double_padding;
					vertical_space		+= double_padding;

					ratio = Math.min(Math.min( view[0] - horizontal_space, currentOpts.width) / currentOpts.width, Math.min( view[1] - vertical_space, currentOpts.height) / currentOpts.height);

					to.width	= Math.round(ratio * (to.width	- double_padding)) + double_padding;
					to.height	= Math.round(ratio * (to.height	- double_padding)) + double_padding;

				} else {
					to.width	= Math.min(to.width,	(view[0] - horizontal_space));
					to.height	= Math.min(to.height,	(view[1] - vertical_space));
				}
			}

			to.top	= view[3] + ((view[1] - (to.height	+ (shadow * 2 ))) * 0.5);
			to.left	= view[2] + ((view[0] - (to.width	+ (shadow * 2 ))) * 0.5);

			if (currentOpts.autoScale === false) {
				to.top	= Math.max(view[3] + margin, to.top);
				to.left	= Math.max(view[2] + margin, to.left);
			}

			return to;
		},

		fancybox_format_title = function(title) {
			if (title && title.length) {
				switch (currentOpts.titlePosition) {
					case 'inside':
						return title;
					default:
						return '<span id="fancybox-title-over">' + title + '</span>';
				}
			}

			return false;
		},

		fancybox_process_title = function() {
			var title	= currentOpts.title,
				width	= final_pos.width - (currentOpts.padding * 2),
				titlec	= 'fancybox-title-' + currentOpts.titlePosition;

			$('#fancybox-title').remove();

			titleh = 0;

			if (currentOpts.titleShow === false) {
				return;
			}

			title = $.isFunction(currentOpts.titleFormat) ? currentOpts.titleFormat(title, currentArray, currentIndex, currentOpts) : fancybox_format_title(title);

			if (!title || title === '') {
				return;
			}

			$('<div id="fancybox-title" class="' + titlec + '" />').css({
				'width'			: width,
				'paddingLeft'	: currentOpts.padding,
				'paddingRight'	: currentOpts.padding
			}).html(title).appendTo('body');

			switch (currentOpts.titlePosition) {
				case 'inside':
					titleh = $("#fancybox-title").outerHeight(true) - currentOpts.padding;
					final_pos.height += titleh;
				break;

				case 'over':
					$('#fancybox-title').css('bottom', currentOpts.padding);
				break;

				default:
					$('#fancybox-title').css('bottom', $("#fancybox-title").outerHeight(true) * -1);
				break;
			}

			$('#fancybox-title').appendTo( outer ).hide();
		},

		fancybox_set_navigation = function() {
			$(document).unbind('keydown.fb').bind('keydown.fb', function(e) {
				if (e.keyCode == 27 && currentOpts.enableEscapeButton) {
					e.preventDefault();
					$.fancyboxlite.close();

				} else if (e.keyCode == 37) {
					e.preventDefault();
					$.fancyboxlite.prev();

				} else if (e.keyCode == 39) {
					e.preventDefault();
					$.fancyboxlite.next();
				}
			});

			if ($.fn.mousewheel) {
				wrap.unbind('mousewheel.fb');

				if (currentArray.length > 1) {
					wrap.bind('mousewheel.fb', function(e, delta) {
						e.preventDefault();

						if (busy || delta === 0) {
							return;
						}

						if (delta > 0) {
							$.fancyboxlite.prev();
						} else {
							$.fancyboxlite.next();
						}
					});
				}
			}

			if (!currentOpts.showNavArrows) { return; }

			if ((currentOpts.cyclic && currentArray.length > 1) || currentIndex !== 0) {
				nav_left.show();
			}

			if ((currentOpts.cyclic && currentArray.length > 1) || currentIndex != (currentArray.length -1)) {
				nav_right.show();
			}
		},

		fancybox_preload_images = function() {
			var href,
				objNext;

			if ((currentArray.length -1) > currentIndex) {
				href = currentArray[ currentIndex + 1 ].href;

				if (typeof href !== 'undefined' && href.match(imgRegExp)) {
					objNext = new Image();
					objNext.src = href;
				}
			}

			if (currentIndex > 0) {
				href = currentArray[ currentIndex - 1 ].href;

				if (typeof href !== 'undefined' && href.match(imgRegExp)) {
					objNext = new Image();
					objNext.src = href;
				}
			}
		},

		_finish = function () {
			inner.css('overflow', (currentOpts.scrolling == 'auto' ? (currentOpts.type == 'image' || currentOpts.type == 'iframe' || currentOpts.type == 'swf' ? 'hidden' : 'auto') : (currentOpts.scrolling == 'yes' ? 'auto' : 'visible')));

			if (!$.support.opacity) {
				inner.get(0).style.removeAttribute('filter');
				wrap.get(0).style.removeAttribute('filter');
			}

			$('#fancybox-title').show();

			if (currentOpts.hideOnContentClick)	{
				inner.one('click', $.fancyboxlite.close);
			}
			if (currentOpts.hideOnOverlayClick)	{
				overlay.one('click', $.fancyboxlite.close);
			}

			if (currentOpts.showCloseButton) {
				close.show();
			}

			fancybox_set_navigation();

			$(window).bind("resize.fb", $.fancyboxlite.center);

			if (currentOpts.centerOnScroll) {
				$(window).bind("scroll.fb", $.fancyboxlite.center);
			} else {
				$(window).unbind("scroll.fb");
			}

			if ($.isFunction(currentOpts.onComplete)) {
				currentOpts.onComplete(currentArray, currentIndex, currentOpts);
			}

			busy = false;

			fancybox_preload_images();
		},

		fancybox_draw = function(pos) {
			var width	= Math.round(start_pos.width	+ (final_pos.width	- start_pos.width)	* pos),
				height	= Math.round(start_pos.height	+ (final_pos.height	- start_pos.height)	* pos),

				top		= Math.round(start_pos.top	+ (final_pos.top	- start_pos.top)	* pos),
				left	= Math.round(start_pos.left	+ (final_pos.left	- start_pos.left)	* pos);

			wrap.css({
				'width'		: width		+ 'px',
				'height'	: height	+ 'px',
				'top'		: top		+ 'px',
				'left'		: left		+ 'px'
			});

			width	= Math.max(width - currentOpts.padding * 2, 0);
			height	= Math.max(height - (currentOpts.padding * 2 + (titleh * pos)), 0);

			inner.css({
				'width'		: width		+ 'px',
				'height'	: height	+ 'px'
			});

			if (typeof final_pos.opacity !== 'undefined') {
				wrap.css('opacity', (pos < 0.5 ? 0.5 : pos));
			}
		},

		fancybox_get_obj_pos = function(obj) {
			var pos		= obj.offset();

			pos.top		+= parseFloat( obj.css('paddingTop') )	|| 0;
			pos.left	+= parseFloat( obj.css('paddingLeft') )	|| 0;

			pos.top		+= parseFloat( obj.css('border-top-width') )	|| 0;
			pos.left	+= parseFloat( obj.css('border-left-width') )	|| 0;

			pos.width	= obj.width();
			pos.height	= obj.height();

			return pos;
		},

		fancybox_get_zoom_from = function() {
			var orig = selectedOpts.orig ? $(selectedOpts.orig) : false,
				from = {},
				pos,
				view;

			if (orig && orig.length) {
				pos = fancybox_get_obj_pos(orig);

				from = {
					width	: (pos.width	+ (currentOpts.padding * 2)),
					height	: (pos.height	+ (currentOpts.padding * 2)),
					top		: (pos.top		- currentOpts.padding - shadow),
					left	: (pos.left		- currentOpts.padding - shadow)
				};

			} else {
				view = fancybox_get_viewport();

				from = {
					width	: 1,
					height	: 1,
					top		: view[3] + view[1] * 0.5,
					left	: view[2] + view[0] * 0.5
				};
			}

			return from;
		},

		fancybox_show = function() {
			loading.hide();

			if (wrap.is(":visible") && $.isFunction(currentOpts.onCleanup)) {
				if (currentOpts.onCleanup(currentArray, currentIndex, currentOpts) === false) {
					$.event.trigger('fancybox-cancel');

					busy = false;
					return;
				}
			}

			currentArray	= selectedArray;
			currentIndex	= selectedIndex;
			currentOpts		= selectedOpts;

			inner.get(0).scrollTop	= 0;
			inner.get(0).scrollLeft	= 0;

			if (currentOpts.overlayShow) {
				if (isIE6) {
					$('select:not(#fancybox-tmp select)').filter(function() {
						return this.style.visibility !== 'hidden';
					}).css({'visibility':'hidden'}).one('fancybox-cleanup', function() {
						this.style.visibility = 'inherit';
					});
				}

				overlay.css({
					'background-color'	: currentOpts.overlayColor,
					'opacity'			: currentOpts.overlayOpacity
				}).unbind().show();
			}

			final_pos = fancybox_get_zoom_to();

			fancybox_process_title();

			if (wrap.is(":visible")) {
				$( close.add( nav_left ).add( nav_right ) ).hide();

				var pos = wrap.position(),
					equal;

				start_pos = {
					top		:	pos.top ,
					left	:	pos.left,
					width	:	wrap.width(),
					height	:	wrap.height()
				};

				equal = (start_pos.width == final_pos.width && start_pos.height == final_pos.height);

				inner.fadeOut(currentOpts.changeFade, function() {
					var finish_resizing = function() {
						inner.html( tmp.contents() ).fadeIn(currentOpts.changeFade, _finish);
					};

					$.event.trigger('fancybox-change');

					inner.empty().css('overflow', 'hidden');

					if (equal) {
						inner.css({
							top			: currentOpts.padding,
							left		: currentOpts.padding,
							width		: Math.max(final_pos.width	- (currentOpts.padding * 2), 1),
							height		: Math.max(final_pos.height	- (currentOpts.padding * 2) - titleh, 1)
						});

						finish_resizing();

					} else {
						inner.css({
							top			: currentOpts.padding,
							left		: currentOpts.padding,
							width		: Math.max(start_pos.width	- (currentOpts.padding * 2), 1),
							height		: Math.max(start_pos.height	- (currentOpts.padding * 2), 1)
						});

						fx.prop = 0;

						$(fx).animate({ prop: 1 }, {
							 duration	: currentOpts.changeSpeed,
							 easing		: currentOpts.easingChange,
							 step		: fancybox_draw,
							 complete	: finish_resizing
						});
					}
				});

				return;
			}

			wrap.css('opacity', 1);

			if (currentOpts.transitionIn == 'elastic') {
				start_pos = fancybox_get_zoom_from();

				inner.css({
						top			: currentOpts.padding,
						left		: currentOpts.padding,
						width		: Math.max(start_pos.width	- (currentOpts.padding * 2), 1),
						height		: Math.max(start_pos.height	- (currentOpts.padding * 2), 1)
					})
					.html( tmp.contents() );

				wrap.css(start_pos).show();

				if (currentOpts.opacity) {
					final_pos.opacity = 0;
				}

				fx.prop = 0;

				$(fx).animate({ prop: 1 }, {
					 duration	: currentOpts.speedIn,
					 easing		: currentOpts.easingIn,
					 step		: fancybox_draw,
					 complete	: _finish
				});

			} else {
				inner.css({
						top			: currentOpts.padding,
						left		: currentOpts.padding,
						width		: Math.max(final_pos.width	- (currentOpts.padding * 2), 1),
						height		: Math.max(final_pos.height	- (currentOpts.padding * 2) - titleh, 1)
					})
					.html( tmp.contents() );

				wrap.css( final_pos ).fadeIn( currentOpts.transitionIn == 'none' ? 0 : currentOpts.speedIn, _finish );
			}
		},

		fancybox_process_inline = function() {
			tmp.width(	selectedOpts.width );
			tmp.height(	selectedOpts.height );

			if (selectedOpts.width	== 'auto') {
				selectedOpts.width = tmp.width();
			}
			if (selectedOpts.height	== 'auto') {
				selectedOpts.height	= tmp.height();
			}

			fancybox_show();
		},

		fancybox_process_image = function() {
			busy = true;

			selectedOpts.width	= imgPreloader.width;
			selectedOpts.height	= imgPreloader.height;

			$("<img />").attr({
				'id'	: 'fancybox-img',
				'src'	: imgPreloader.src,
				'alt'	: selectedOpts.title
			}).appendTo( tmp );

			fancybox_show();
		},

		fancybox_start = function() {
			fancybox_abort();

			var obj	= selectedArray[ selectedIndex ],
				href,
				type,
				title,
				str,
				emb,
				selector,
				data;

			selectedOpts = $.extend({}, $.fn.fancyboxlite.defaults, (typeof $(obj).data('fancybox') == 'undefined' ? selectedOpts : $(obj).data('fancybox')));
			title = obj.title || $(obj).title || selectedOpts.title || '';

			if (obj.nodeName && !selectedOpts.orig) {
				selectedOpts.orig = $(obj).children("img:first").length ? $(obj).children("img:first") : $(obj);
			}

			if (title === '' && selectedOpts.orig) {
				title = selectedOpts.orig.attr('alt');
			}

			if (obj.nodeName && (/^(?:javascript|#)/i).test(obj.href)) {
				href = selectedOpts.href || null;
			} else {
				href = selectedOpts.href || obj.href || null;
			}

			if (selectedOpts.type) {
				type = selectedOpts.type;

				if (!href) {
					href = selectedOpts.content;
				}

			} else if (selectedOpts.content) {
				type	= 'html';

			} else if (href) {
				if (href.match(imgRegExp)) {
					type = 'image';

				} else if (href.match(swfRegExp)) {
					type = 'swf';

				} else if ($(obj).hasClass("iframe")) {
					type = 'iframe';

				} else if (href.match(/#/)) {
					obj = href.substr(href.indexOf("#"));

					type = $(obj).length > 0 ? 'inline' : 'ajax';
				} else {
					type = 'ajax';
				}
			} else {
				type = 'inline';
			}

			selectedOpts.type	= type;
			selectedOpts.href	= href;
			selectedOpts.title	= title;

			if (selectedOpts.autoDimensions && selectedOpts.type !== 'iframe' && selectedOpts.type !== 'swf') {
				selectedOpts.width		= 'auto';
				selectedOpts.height		= 'auto';
			}

			if (selectedOpts.modal) {
				selectedOpts.overlayShow		= true;
				selectedOpts.hideOnOverlayClick	= false;
				selectedOpts.hideOnContentClick	= false;
				selectedOpts.enableEscapeButton	= false;
				selectedOpts.showCloseButton	= false;
			}

			if ($.isFunction(selectedOpts.onStart)) {
				if (selectedOpts.onStart(selectedArray, selectedIndex, selectedOpts) === false) {
					busy = false;
					return;
				}
			}

			tmp.css('padding', (shadow + selectedOpts.padding + selectedOpts.margin));

			$('.fancybox-inline-tmp').unbind('fancybox-cancel').bind('fancybox-change', function() {
				$(this).replaceWith(inner.children());
			});

			switch (type) {
				case 'html' :
					tmp.html( selectedOpts.content );
					fancybox_process_inline();
				break;

				case 'inline' :
					$('<div class="fancybox-inline-tmp" />').hide().insertBefore( $(obj) ).bind('fancybox-cleanup', function() {
						$(this).replaceWith(inner.children());
					}).bind('fancybox-cancel', function() {
						$(this).replaceWith(tmp.children());
					});

					$(obj).appendTo(tmp);

					fancybox_process_inline();
				break;

				case 'image':
					busy = false;

					$.fancyboxlite.showActivity();

					imgPreloader = new Image();

					imgPreloader.onerror = function() {
						fancybox_error();
					};

					imgPreloader.onload = function() {
						imgPreloader.onerror = null;
						imgPreloader.onload = null;
						fancybox_process_image();
					};

					imgPreloader.src = href;

				break;

				case 'swf':
					str = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="' + selectedOpts.width + '" height="' + selectedOpts.height + '"><param name="movie" value="' + href + '"></param>';
					emb = '';

					$.each(selectedOpts.swf, function(name, val) {
						str += '<param name="' + name + '" value="' + val + '"></param>';
						emb += ' ' + name + '="' + val + '"';
					});

					str += '<embed src="' + href + '" type="application/x-shockwave-flash" width="' + selectedOpts.width + '" height="' + selectedOpts.height + '"' + emb + '></embed></object>';

					tmp.html(str);

					fancybox_process_inline();
				break;

				case 'ajax':
					selector	= href.split('#', 2);
					data		= selectedOpts.ajax.data || {};

					if (selector.length > 1) {
						href = selector[0];

						if (typeof data == "string") {
							data += '&selector=' + selector[1];
						} else {
							data.selector = selector[1];
						}
					}

					busy = false;
					$.fancyboxlite.showActivity();

					ajaxLoader = $.ajax($.extend(selectedOpts.ajax, {
						url		: href,
						data	: data,
						error	: fancybox_error,
						success : function(data, textStatus, XMLHttpRequest) {
							if (ajaxLoader.status == 200) {
								tmp.html( data );
								fancybox_process_inline();
							}
						}
					}));

				break;

				case 'iframe' :
					$('<iframe id="fancybox-frame" name="fancybox-frame' + new Date().getTime() + '" frameborder="0" hspace="0" scrolling="' + selectedOpts.scrolling + '" src="' + selectedOpts.href + '"></iframe>').appendTo(tmp);
					fancybox_show();
				break;
			}
		},

		fancybox_animate_loading = function() {
			if (!loading.is(':visible')){
				clearInterval(loadingTimer);
				return;
			}

			$('div', loading).css('top', (loadingFrame * -40) + 'px');

			loadingFrame = (loadingFrame + 1) % 12;
		},

		fancybox_init = function() {
			if ($("#fancybox-wrap").length) {
				return;
			}

			$('body').append(
				tmp			= $('<div id="fancybox-tmp"></div>'),
				loading		= $('<div id="fancybox-loading"><div></div></div>'),
				overlay		= $('<div id="fancybox-overlay"></div>'),
				wrap		= $('<div id="fancybox-wrap"></div>')
			);

			if (!$.support.opacity) {
				wrap.addClass('fancybox-ie');
				loading.addClass('fancybox-ie');
			}

			outer = $('<div id="fancybox-outer"></div>')
				.appendTo( wrap );

			outer.append(
				inner		= $('<div id="fancybox-inner"></div>'),
				close		= $('<a id="fancybox-close"></a>'),

				nav_left	= $('<a href="javascript:;" id="fancybox-left"><span class="fancy-ico" id="fancybox-left-ico"></span></a>'),
				nav_right	= $('<a href="javascript:;" id="fancybox-right"><span class="fancy-ico" id="fancybox-right-ico"></span></a>')
			);

			close.click($.fancyboxlite.close);
			loading.click($.fancyboxlite.cancel);

			nav_left.click(function(e) {
				e.preventDefault();
				$.fancyboxlite.prev();
			});

			nav_right.click(function(e) {
				e.preventDefault();
				$.fancyboxlite.next();
			});

			if (isIE6) {
				overlay.get(0).style.setExpression('height',	"document.body.scrollHeight > document.body.offsetHeight ? document.body.scrollHeight : document.body.offsetHeight + 'px'");
				loading.get(0).style.setExpression('top',		"(-20 + (document.documentElement.clientHeight ? document.documentElement.clientHeight/2 : document.body.clientHeight/2 ) + ( ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop )) + 'px'");

				outer.prepend('<iframe id="fancybox-hide-sel-frame" src="javascript:\'\';" scrolling="no" frameborder="0" ></iframe>');
			}
		};

	/*
	 * Public methods
	 */

	$.fn.fancyboxlite = function(options) {
		$(this)
			.data('fancybox', $.extend({}, options, ($.metadata ? $(this).metadata() : {})))
			.unbind('click.fb').bind('click.fb', function(e) {
				e.preventDefault();

				if (busy) {
					return;
				}

				busy = true;

				$(this).blur();

				selectedArray	= [];
				selectedIndex	= 0;

				var rel = $(this).attr('rel') || '';

				if (!rel || rel == '' || rel === 'nofollow') {
					selectedArray.push(this);

				} else {
					selectedArray	= $("a[rel=" + rel + "], area[rel=" + rel + "]");
					selectedIndex	= selectedArray.index( this );
				}

				fancybox_start();

				return false;
			});

		return this;
	};

	$.fancyboxlite = function(obj) {
		if (busy) {
			return;
		}

		busy = true;

		var opts = typeof arguments[1] !== 'undefined' ? arguments[1] : {};

		selectedArray	= [];
		selectedIndex	= opts.index || 0;

		if ($.isArray(obj)) {
			for (var i = 0, j = obj.length; i < j; i++) {
				if (typeof obj[i] == 'object') {
					$(obj[i]).data('fancybox', $.extend({}, opts, obj[i]));
				} else {
					obj[i] = $({}).data('fancybox', $.extend({content : obj[i]}, opts));
				}
			}

			selectedArray = jQuery.merge(selectedArray, obj);

		} else {
			if (typeof obj == 'object') {
				$(obj).data('fancybox', $.extend({}, opts, obj));
			} else {
				obj = $({}).data('fancybox', $.extend({content : obj}, opts));
			}

			selectedArray.push(obj);
		}

		if (selectedIndex > selectedArray.length || selectedIndex < 0) {
			selectedIndex = 0;
		}

		fancybox_start();
	};

	$.fancyboxlite.showActivity = function() {
		clearInterval(loadingTimer);

		loading.show();
		loadingTimer = setInterval(fancybox_animate_loading, 66);
	};

	$.fancyboxlite.hideActivity = function() {
		loading.hide();
	};

	$.fancyboxlite.next = function() {
		return $.fancyboxlite.pos( currentIndex + 1);
	};

	$.fancyboxlite.prev = function() {
		return $.fancyboxlite.pos( currentIndex - 1);
	};

	$.fancyboxlite.pos = function(pos) {
		if (busy) {
			return;
		}

		pos = parseInt(pos, 10);

		if (pos > -1 && currentArray.length > pos) {
			selectedIndex = pos;
			fancybox_start();
		}

		if (currentOpts.cyclic && currentArray.length > 1 && pos < 0) {
			selectedIndex = currentArray.length - 1;
			fancybox_start();
		}

		if (currentOpts.cyclic && currentArray.length > 1 && pos >= currentArray.length) {
			selectedIndex = 0;
			fancybox_start();
		}

		return;
	};

	$.fancyboxlite.cancel = function() {
		if (busy) {
			return;
		}

		busy = true;

		$.event.trigger('fancybox-cancel');

		fancybox_abort();

		if (selectedOpts && $.isFunction(selectedOpts.onCancel)) {
			selectedOpts.onCancel(selectedArray, selectedIndex, selectedOpts);
		}

		busy = false;
	};

	// Note: within an iframe use - parent.$.fancybox.close();
	$.fancyboxlite.close = function() {
		if (busy || wrap.is(':hidden')) {
			return;
		}

		busy = true;

		if (currentOpts && $.isFunction(currentOpts.onCleanup)) {
			if (currentOpts.onCleanup(currentArray, currentIndex, currentOpts) === false) {
				busy = false;
				return;
			}
		}

		fancybox_abort();

		$(close.add( nav_left ).add( nav_right )).hide();

		$('#fancybox-title').remove();

		wrap.add(inner).add(overlay).unbind();

		$(window).unbind("resize.fb scroll.fb");
		$(document).unbind('keydown.fb');

		function _cleanup() {
			overlay.fadeOut('fast');

			wrap.hide();

			$.event.trigger('fancybox-cleanup');

			inner.empty();

			if ($.isFunction(currentOpts.onClosed)) {
				currentOpts.onClosed(currentArray, currentIndex, currentOpts);
			}

			currentArray	= selectedOpts	= [];
			currentIndex	= selectedIndex	= 0;
			currentOpts		= selectedOpts	= {};

			busy = false;
		}

		inner.css('overflow', 'hidden');

		if (currentOpts.transitionOut == 'elastic') {
			start_pos = fancybox_get_zoom_from();

			var pos = wrap.position();

			final_pos = {
				top		:	pos.top ,
				left	:	pos.left,
				width	:	wrap.width(),
				height	:	wrap.height()
			};

			if (currentOpts.opacity) {
				final_pos.opacity = 1;
			}

			fx.prop = 1;

			$(fx).animate({ prop: 0 }, {
				 duration	: currentOpts.speedOut,
				 easing		: currentOpts.easingOut,
				 step		: fancybox_draw,
				 complete	: _cleanup
			});

		} else {
			wrap.fadeOut( currentOpts.transitionOut == 'none' ? 0 : currentOpts.speedOut, _cleanup);
		}
	};

	$.fancyboxlite.resize = function() {
		var c, h;

		if (busy || wrap.is(':hidden')) {
			return;
		}

		busy = true;

		c = inner.wrapInner("<div style='overflow:auto'></div>").children();
		h = c.height();

		wrap.css({height:	h + (currentOpts.padding * 2) + titleh});
		inner.css({height:	h});

		c.replaceWith(c.children());

		$.fancyboxlite.center();
	};

	$.fancyboxlite.center = function() {
		busy = true;

		var view	= fancybox_get_viewport(),
			margin	= currentOpts.margin,
			to		= {};

		to.top	= view[3] + ((view[1] - ((wrap.height() - titleh) + (shadow * 2 ))) * 0.5);
		to.left	= view[2] + ((view[0] - (wrap.width() + (shadow * 2 ))) * 0.5);

		to.top	= Math.max(view[3] + margin, to.top);
		to.left	= Math.max(view[2] + margin, to.left);

		wrap.css(to);

		busy = false;
	};

	$.fn.fancyboxlite.defaults = {
		padding				:	10,
		margin				:	20,
		opacity				:	false,
		modal				:	false,
		cyclic				:	false,
		scrolling			:	'auto',	// 'auto', 'yes' or 'no'

		width				:	560,
		height				:	340,

		autoScale			:	true,
		autoDimensions		:	true,
		centerOnScroll		:	true,

		ajax				:	{},
		swf					:	{ wmode: 'transparent' },

		hideOnOverlayClick	:	true,
		hideOnContentClick	:	false,

		overlayShow			:	true,
		overlayOpacity		:	0.3,
		overlayColor		:	'#333',

		titleShow			:	true,
		titlePosition		:	'inside',	// 'outside', 'inside' or 'over'
		titleFormat			:	null,

		transitionIn		:	'elastic',	// 'elastic', 'fade' or 'none'
		transitionOut		:	'elastic',	// 'elastic', 'fade' or 'none'

		speedIn				:	300,
		speedOut			:	300,

		changeSpeed			:	300,
		changeFade			:	'fast',

		easingIn			:	'swing',
		easingOut			:	'swing',

		showCloseButton		:	true,
		showNavArrows		:	true,
		enableEscapeButton	:	true,

		onStart				:	null,
		onCancel			:	null,
		onComplete			:	null,
		onCleanup			:	null,
		onClosed			:	null
	};

	$(document).ready(function() {
		fancybox_init();
	});

})(jQuery);



