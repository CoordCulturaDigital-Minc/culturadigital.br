/*
* streaming.js - This file have the streaming template functions
*
* Copyright (C) 2010 Ministério da Cultura Brasileira
* Copyright (C) 2010  Marcos Maia Lopes <marcosmlopes01@gmail.com>
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

$j = jQuery;
last_id = false;

$j(function() {
    // Handle embbed button
    $j('div.media li.embbed a').click(function() {
	var embbed = $j('div.media #videoplayer').html();
	$j(this).parent().toggleClass('active');
	$j('div.media div.embbed textarea').val(embbed);
	$j('div.media div.embbed').toggle();

	return false;
    });

    // Init flickr widget
    $j('div.flickr .carousel').flickrWidget('culturadigitalbr,futurivel');

    // Init twitter widget
    $j('div.social-network').twitterWidget('culturadigitalbr');
});

/*
 * Flickr widget
 */
$j.fn.flickrWidget = function(hashtag) {
    return this.each(function() {
	$j.ajax({
	    type: 'GET',
	    dataType: 'jsonp',
	    data: {
		method: 'flickr.photos.search',
		format: 'json',
		api_key: '4765b01ab6a15f195d4f300c7b57de75',
		tags: hashtag,
		per_page: 20
	    },
	    url: 'http://www.flickr.com/services/rest/'
	});
    });
}

/*
 * Parse flickr json
 */
function jsonFlickrApi(json) {
    var flickr = $j.map(json.photos.photo, function(obj, index) {
	if(index <= 19) {
	    return {
		id: obj.id,
		farm: obj.farm,
		server: obj.server,
		secret: obj.secret,
		title: obj.title,
		user: obj.owner
	    }
	}
    });
    
    $j('#flickr').tmpl(flickr).appendTo($j('.flickr .carousel ul'));
    $j('.flickr .carousel').flickrCarousel();
    $j('.flickr').removeClass('loading');
}

/*
 * Flickr carousel
 */
$j.fn.flickrCarousel = function() {
    return this.each(function() {
	var $this = $j(this);
	var limit = 5;
	var page = 1;
	var items = $j('li', $this);
	var items_width = items.width() + 9;
	var pages = Math.ceil(items.length / limit);
	var wrapper_width = items.length * items_width;
	var view_width = limit * items_width;

	$j('ul', $this).width(wrapper_width);
	$j('.prev', $this).click(function() {
	    if(page > 1) {
		$j('ul', $this).animate({
		  marginLeft: '+=' + view_width
		}, 400);

		page--;
	    }

	    return false;
	});
	$j('.next', $this).click(function() {
	    if(page < pages) {
		$j('ul', $this).animate({
		  marginLeft: '-=' + view_width
		}, 400);

		page++;
	    }

	    return false;
	});
    });
}

/*
 * Twitter widget
 */
$j.fn.twitterWidget = function(hashtag) {
    return this.each(function() {
	var $this = $j(this);

	$j.ajax({
	    type: 'GET',
	    dataType: 'jsonp',
	    data: {
		q: hashtag
	    },
	    url: 'http://search.twitter.com/search.json',
	    success: function(tweets) {
		jsonTwitterApi(tweets);
		$j('div.loading', $this).removeClass('loading');
	    }
	});

	setInterval(function() {
	    if(last_id) {
		$j.ajax({
		    type: 'GET',
		    dataType: 'jsonp',
		    data: {
			q: hashtag,
			since_id: last_id
		    },
		    url: 'http://search.twitter.com/search.json',
		    success: function(tweets) {
			jsonTwitterApi(tweets);
		    }
		});
	    }
	}, 10000);
    });
}

/*
 * Parse twitter json
 */
function jsonTwitterApi(tweets) {
    var twitter = $j.map(tweets.results, function(obj, index) {
	if(index == 0) {
	    last_id = obj.id
	}

	if(index <= 7) {
	    return {
		id: obj.id,
		username: obj.from_user,
		content: obj.text,
		avatar: obj.profile_image_url,
		time: prettyDate(obj.created_at),
		geo: obj.geo
	    }
	}
    });

    $j(twitter.reverse()).each(function(i) {
	$j('ul.twitter').prepend($j('#tweets').tmpl(twitter[i]).hide())
	    .find('li:hidden').fadeIn(600);

	if( $j('ul.twitter li').length == 9 ) {
	    $j('ul.twitter li:last-child').fadeOut(600).remove();
	}
    });
}

String.prototype.parseURL = function() {
    return this.replace(/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&\?\/.=]+/g, function(url) {
	return url.link(url);
    });
}; 

String.prototype.parseUsername = function() {
    return this.replace(/[@]+[A-Za-z0-9-_]+/g, function(u) {
	var username = u.replace("@","")
	return u.link("http://twitter.com/"+username);
    });
};
 
String.prototype.parseHashtag = function() {
    return this.replace(/[#]+[A-Za-z0-9-_]+/g, function(t) {
	var tag = t.replace("#","%23")
	return t.link("http://search.twitter.com/search?q="+tag);
    });
};
