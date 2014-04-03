=== TweetMeme Button ===
Contributors: dtsn
Tags: twitter, retweet, voting, button, tweetmeme, hashtags
Requires at least: 2.7.2
Tested up to: 3.4.2
Stable tag: 3.0

== Description ==


The Twitter button is the defacto standard in retweeting - used by some of the biggest websites in the world including Techcrunch.com, PerezHilton.com, Break.com, CNET.com, Wired, Time Magazine and hundreds of other massive brands, in total it is installed on over 100,000 websites around the globe. 

Easily allows your blog post or page to be retweeted. It provides a live count of how many times your post/page has been retweeted throughout Twitter.


= Features =

* Support for the Twitter Tweet button
* Live count of tweets from Twitter
* Allows you to change the source which you retweet, E.g. "<the title> <the url> via @<your_username>"
* Easily installation and customisation
* Quicker loading times for the buttons
* Hashtag support (which are automatically taken from your post tags)

== Installation ==

Follow the steps below to install the plugin.

1. Upload the TweetMeme directory to the /wp-content/plugins/ directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings/tweetmeme to configure the button


== Changelog ==

= 3.0 =

* Removed TweetMeme due to service shut down (sorry!). We are now converting all the TweetMeme buttons to Twitter buttons. This is a _REQUIRED_ update, if you do not update your buttons will stop working.

= 2.3 =

* Fixed a problem with the button showing in the feed

= 2.2 =

* Fixed problem with extra large buttons & where scrollbars appear in button

= 2.1 =

* Fix for Wordpress installs which have a non-standard URL structure

= 2.0 =

* Button now gets loaded via JS instead of directly writing out the iFrame, should lead to faster load times

= 1.9 =

* Added the option to use the Twitter Tweet button and the TweetMeme Retweet Button

= 1.8.6 =

* Removed analytics because it is no longer supported

= 1.8.5 =

* Fix problem with the API field not correclty storing the values

= 1.8.4 =

* Updated support for Bit.ly Pro

= 1.8.3 =

* Analytics now uses seralize instead of json_decode (for PHP installs older than 5.2)

= 1.8.2 =

* Buttons in feed were not rendering correctly or at the correct size

= 1.8 =

* Added support for hashtags and spaces

= 1.7.5 =

* Users were getting confused to what the API field does, updated the documentation

= 1.7.4 =

* Tested and works with version 2.9.1

= 1.7.3 =

* Changed line 101 (get_post_meta) to compare against null instead of empty string due to the new way Wordpress 2.9 returns meta_data

= 1.7.2 =

* Fixed the validation errors. Replaced '&' with '&amp;'
* Add a strip_tags to the meta title output, some plugins where causing tags to be outputted in the title
