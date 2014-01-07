simpleX
======= 

Version: 2.0 is completely coded from ground up to use HTML5, CSS3 and responsive design. It now fully supports WordPress 3.3 features like post formats, header image on homepage and archive pages and custom background image.


Installation
============

Unzip the zipped files, place it on your wp-content/themes folder and activate.
Go to Settings -> Media and change your image sizes to
	Thumbnail: 150 x 150
	Medium: 300 x 0
	Large: 567 x 0
	Embed Width: 567 x 0


Internationalization
====================

simpleX can now be translated. The language files are inside lib/languages. You can use the .mo file to translate on your own language.


Widgets
======
Currently, there is only one widgetized area and its the sidebar.


Hooks
=====

You can add your own functions under these hooks.

simplex_before_page
simplex_before_header
simplex_after_header
simplex_before_article
simplex_after_article
simplex_before_title
simplex_after_title
simplex_before_content
simplex_after_content
simplex_before_meta
simplex_after_meta
simplex_before_sidebar
simplex_after_sidebar
simplex_before_footer
simplex_credits
simplex_after_footer