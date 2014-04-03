=== Embed Facebook ===

Contributors: sohailabid, selfexile
Tags: facebook, embed, embed facebook, facebook albums, facebook events, facebook notes, facebook groups, facebook videos
Requires at least: 2.8
Tested up to: 3.1.2
Stable tag: 1.4

Just paste the URL of a facebook album, photo, event, video, page, group, or note in a WordPress post/page, the plugin will embed it for you.

== Description ==

Embed Facebook lets you embed various Facebook objects (album, event, group, note, photo, or video) in a post or page. You just need to paste the URL of a Facebook object anywhere in a post or page, the plugin will automatically embed it for you. Check "Screenshots" for how the embedded objects look.

The object must be public (i.e. they should belong to a Facebook Page. Your personal albums are not embeddable.)

The URL of facebook object should be on its own line (with an empty line above and below).

** Demo & Instructions: http://sohailabid.com/projects/embed-facebook/ **

== Installation ==

1. Unzip the plugin and Upload the directory 'embed-facebook' to '/wp-content/plugins/' on your site

2. Activate the plugin through the 'Plugins' menu in WordPress

3. That's all! No configuration is required. Start pasting Facebook URLs in your posts/pages.

== Screenshots ==

1. an embedded facebook album

2. an embedded facebook photo

3. an embedded facebook page

4. an embedded facebook video

5. an embedded facebook event

6. an embedded facebook group

7. an embedded facebook note

== Changelog ==

= 1.4 =
* Facebook have changed the album URL again. Updated the plugin to incorporate the new(er) URLs, too.

= 1.3 =
* fixed: unable to embed facebook albums with new url
* fixed: incorrect display of event start and end time
* new: supports embedding facebook albums using new url
* changed: uses a standalone overlay gallery for facebook albums, slidewindow, instead of slimbox2 that required jquery

= 1.2 =
* fixed: error when the photos of an album cannot be accessed
* uses php's built-in json_decode function when available

= 1.1 =
* fixed: URLs pasted in visual mode were not being converted into embedded objects.

= 1.0 =
* first public release
