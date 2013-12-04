=== Plugin Name ===
Contributors: sirzooro
Tags: access, anti-spam, authentication, comment, comments, plugin, security, spam
Requires at least: 2.7
Tested up to: 2.9.9
Stable tag: 1.8.1

This plugin protects your registration, login and comment forms against spambots.

== Description ==

This plugin protects registration, login and comment forms from spambots by adding two extra fields hidden by CSS. This approach gave me 100% anti-spam protection on one of my sites.

The idea behind this plugin is simple: SPAMBOTs either fill every form field they find (generic spambots) or fill WordPress-specific fields only (spambots which will recognise WP or are targeting WP only). Therefore it is sufficient to add two extra text fields to form (one empty and one with predefined value), and check theirs values after form is submitted. 1st field (empty one) will be filled by generic spambots, and 2nd one will not be filled by spambots targeting WP only. With these two simple checks probably all spambots can be easily detected, so WP can return error "403 Forbidden" for them.

These two extra fields are hidden with CSS rule, so they will not be visible for most users. Only users with text-based browsers (and very old ones which not support CSS) will see them, but don't be afraid - plugin has special message for them.

Not surprisingly, some spammers found Invisible Defender too and updated their spamming software to detect and bypass this plugin. Therefore I started adding new protection methods. First one is blacklist for heavy spammers; more will be added soon.

Invisible Defender also shows number of blocked spammers in Dashboard, so you can see that it really works.

Available translations:

* English
* Polish (pl_PL) - done by me
* Russian (ru_RU) - thanks [Fat Cow](http://www.fatcow.com/)
* French (fr_FR) - thanks [Lise](http://liseweb.fr/BLOG/)
* Italian (it_IT) - thanks [Gianni](http://gidibao.net/)
* Hungarian (hu_HU) - thanks [Péter](http://seo-hungary.com/)
* German (de_DE) - thanks [pixeltunes](http://pixeltunes.de/)
* Dutch  (nl_NL) - thanks [Rene](http://wpwebshop.com/)
* Hindi (hi_IN) - thanks [Ashish J.](http://outshinesolutions.com/)
* Czech (cs_CZ) - thanks [Lelkoun](http://lelkoun.cz/)

[Changelog](http://wordpress.org/extend/plugins/invisible-defender/changelog/)

== Installation ==

1. Upload `invisible-defender` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Enjoy :)

== Frequently Asked Questions ==

= I can not login and I am getting 'Bye Bye, SPAMBOT!' error message. Help! =

You have installed plugin which replaces default login form (not Invisible Defender). Please use FTP to login to your server, go to `wp-content/plugins/` directory and find directory (or file) of that plugin. Next change its name to something else (or just remove it). When you do this, standard login form will be restored, so you should be able to login now. Login and go to the Invisible Defender options page, and disable protection for Login Form. Then you can restore name of plugin directory, which you changed earlier (or upload it again if you have removed it). You may also need to enable that plugin again (WordPress may deactivate it) and configure it. After you do this, please contact author of that conflicting plugin and tell him/her about this plugin conflict.

= Why I am getting 'Bye Bye, SPAMBOT!' error message when I try to login and/or register? =

Some plugins allows to replace default registration and/or login forms. This can cause problems, when new registration and/or login forms doesn't calls appropriate hooks, which are used by Invisible Defender to add its hidden fields to the form. Invisible Defender uses following two hooks to add its hidden fields to registration and login forms:

`<?php do_action('register_form'); ?>`

`<?php do_action('login_form'); ?>`

= Why I am getting 'Bye Bye, SPAMBOT!' error message when I try to add newcomment? =

Invisible defender uses `comment_form` hook to add its hidden fields to the comment form. This hook is called from template, so most probably your template does not do this. Check if your template has `comments.php` file (it should be in `wp-content/themes/your-template/` directory). If you have it, check if somewhere inside <form> element is following line:

`<?php do_action('comment_form', $post->ID); ?>`

If this line is missing, add it. You can also contact with template author and ask him/her for help.

You can also disable comments form protection on Invisible Defender options page.

= How to remove link added by Invisible Defender under forms? =

This plugin is provided for free, so this link is the only way I can get credit from happy users. Of course you can disable it - just go to the plugin Options page, scroll to the bottom, uncheck 'Show "Protected by" link:' and save options.

== Changelog ==

= 1.8.1 =
* Added Dutch translation (thanks Rene);
* Added Hindi translation (thanks Ashish J.);
* Added Czech translation (thanks Lelkoun);
* Code cleanup

= 1.8 =
* Blacklisted Business Communication Agency, Ltd. (92.242.64.0 - 92.242.95.255) and netdirekt e.K. (89.149.241.0 - 89.149.244.255);
* Fixed incorrect HTML code generated by plugin;
* Code cleanup

= 1.7 =
* Blacklisted SIA "CSS GROUP" (94.142.128.0 - 94.142.135.255);
* Marked as compatible with WP 2.9.x

= 1.6 =
* Blacklisted AD TECHNOLOGY SIA IPs (188.92.72.0 - 188.92.79.255)

= 1.5.1 =
* Marked as compatible with WP 2.8.5

= 1.5 =
* Fix: skip hidden field check for XML-RPC, Trackbacks and Pingback requests

= 1.4.7 =
* Added German translation (thanks pixeltunes)

= 1.4.6 =
* Added Hungarian translation (thanks Péter)

= 1.4.5 =
* Added Italian translation (thanks Gianni)

= 1.4.4 =
* Added French translation (thanks Lise)

= 1.4.3 =
* Added Russian translation (thanks Fat Cow)

= 1.4.2 =
* Calculate stats for blocked requests from blacklisted IPs too

= 1.4.1 =
* Removed debug code left by mistake

= 1.4 =
* Count number of times bots are blocked and show stats in Dashboard;
* Added option to show 'Protected by' link

= 1.3.1 =
* Mark plugin as tested with WP 2.8

= 1.3 =
* Blacklisted AltusHost Inc. IPs (89.248.160.191 - 89.248.160.254);
* Added options page and options to disable protection for registration, login and/or comments forms

= 1.2 =
* Fix: skip hidden field check for AJAX requests

= 1.1 =
* Blacklisted Dragonara Alliance Ltd IPs (194.8.74.0 - 194.8.75.255)

= 1.0 =
* Initial version
