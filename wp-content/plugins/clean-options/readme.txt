=== Clean Options ===
Contributors: Mittineague
Donate link: http://www.mittineague.com/blog/2009/06/clean-options-translations/
Tags: remove options, wp_options
Requires at least: 2.3
Tested up to: 2.9.2
Stable tag: Trunk

== License ==  
Released under the terms of the GNU General Public License.  

== Changelog ==  

= 1.3.2 26-Jun-2010 =  
* updated the $known_ok array (for WordPress 3.0)  
* de_DE German translation  
* nl_NL Dutch translation  

= 1.3.1 27-Mar-2010 =  
* allow 2.9.1 blogs to remove 2.8 transient options  
* added timestamp to blog date/time format in review  
* updated $known_ok array  
* get_all_yes_autoload_options() optimization  
* expanded string search to core files  
* added 'site' to regex pattern  
* added get_transient regex  
* sr_RS Serbian Cyrillic translation  
* sr_RS Serbian Latinica translation  
* minor typo fixes  
* updated older translation files  

= 1.3.0 16-Jan-2010 =  
* updated the $known_ok array (for WordPress 2.9)  
* optimized for WordPress 2.9  
* replaced deprecated user_role  
* pt_BR Portuguese translation  
* zh_CN Chinese translation  
* hr_HR Croatian translation  

= 1.2.3 10-Sep-2009 =  
* removed WordPress < ver. 2.3 feature  
* WordPress < ver. 2.7 fix  
* be_BY Belarusian translation  

= 1.2.2 27-Aug-2009 =  
* es_ES Spanish translation  
* corrected ru_RU Russian translation  
* uk_UA Ukrainian translation  

= 1.2.1 14-Aug-2009 =  
* added more "transient"s  
* allow 2.8+ blogs to remove all obsolete rss_hash rows  
* allow for non-default folder locations/names  
* improved unpaired rss block  
* capability check for added security  
* eliminate 2.8+ false warnings  
* ru_RU Russian translation  
* minor tweaks  
* changed Version History to Changelog in readme  

= 1.2.0 19-Jun-2009 =  
* Internationalization  
* updated the $known_ok array (for WordPress 2.8)  
* changed admin CSS hook  
* plugin page Settings link  
* option count in admin menu  
* minor tweaks  

= 1.1.9 27-Mar-2009 =  
* $wpdb compatibility with WordPress < 2.5  

= 1.1.8 27-Mar-2009 =  
* nonce compatibility with WordPress < 2.5  
* minor tweak  

= 1.1.7 24-Mar-2009 =  
* added show/hide Known Core  
* added Search feature  
* exclude non WP folders in searchdir()  
* tweaked nonce calls  
* changed fopen to is_readable  
* changed fread to file\_get\_contents  

= 1.1.6 18-Mar-2009 =  
* reduced # of dt in warnings  
* searchdir tweak  
* added support for MySQL < 4.1  
* $rss\_ts\_arr sort tweak  
* added option_name Google search links  

= 1.1.5 09-Mar-2009 =  
* "delete all" Bug Fix  
  
= 1.1.4 08-Mar-2009 =  
* added link to options.php page  
* some regex refinement  
* added "known Core" wording  
* removed %s from yes in queries  
* added category_children as known core  
* added show/hide AS warnings  

= 1.1.3 07-Mar-2009 =  
* added error message info  
* added find non-string option names  
* changed the way limit_query works  
* optimized database queries  
* refined 'yes' regex  
* more minor tweaks yet again  

= 1.1.2 25-Feb-2009 =  
* added show_errors to DB objects  
* friendlier CSS selectors  
* added label tags  

= 1.1.1 21-Feb-2009 =  
* query syntax change  
* query error catching added  
* error scope changes  

= 1.1.0 RC 27-Jan-2009 =  
* limit 'delete all' rss delete to < 100 highest id  
* added rss_hash limited find  
* fixed and updated the $known_ok array  
* improved robustness  
- fixed searchdir() return type initialization  
- set explicit return type in $wpdb->get_results queries  
- ini\_get('safe\_mode') fixes  
* changed found rss_hash options section  
* added javascript select/deselect all  
* various other minor tweaks  

= 1.0.0 RC 12-Nov-2008 =  
* increased memory limit from 32M to 64M  
* added remove all rss_hash section  

Complete Version History available at the plugin's page  
[Clean Options](http://www.mittineague.com/dev/co.php)

== Description ==
Finds orphaned options and allows for their removal from the wp\_options table.  

== Long Description ==
The Clean Options plugin provides an easy way for WordPress bloggers to manage their wp\_options table. It has many built in safety features that will help prevent accidental deletion of table rows that may be needed for the error free operation of the blog. It's goal is to give plugin users an easy and safe way to get a bloated wp\_options table down to a manageable size, thus improving the performance of their blog, and keep it that way. It also provides warning messages that will alert plugin users of potential problems that are encountered.  

In addition to the warning messages, option names can be double-checked by following the link to the wp-admin/options.php page, using the Google search links, and by searching the wp-content folder for files that contain either the option\_name outside of get\_option, or fragments of the option\_name (up to 3 pieces).  

== Other Notes ==
= Orphaned Options List =
Listed Options are those that are found in the wp\_options table but are not referenced by "get\_option" or "get\_settings" by any of the PHP files located within your blog directory. If you have deactivated plugins and/or non-used themes in your directory, the associated options will not be considered orphaned until the files are uninstalled.  

When shown, non-selectable Options are known to have been created from files present during upgrade or backup, or are legitimate options that do not "fit" the search for get\_option or get\_settings (eg. core WordPress files that use alternate "non-string" syntax). If you wish to remove them by other means, do so at your own risk.  

= RSS Options =
The plugin handles the rss\_hash options, added to the wp\_options table from the blog's dashboard page and other files that parse RSS feeds and cache the results, in three ways.  

If the wp\_options table contains more than 500 rss\_hash options, the "delete all" feature will be available. Submitting the "Delete ALL 'rss' Options" may delete **ALL** "rss\_hash" rows from the wp\_options table, including the **CURRENT** ones. It is not expected that doing so will cause any problems, however, it makes the performance of a database **BACKUP** prior to deletion even more important. To help ensure that no current "rss" options are deleted, the plugin makes the last 100 entries of the wp\_options table exempt from the "delete all". But depending on your installation history this may not be adequate protection.  
The "rss\_hash" rows are **not** retrieved and displayed, but simply deleted. Even with the built in safety feature, it is recommended that instead of doing this, that the number of rss\_hash options found be limited to only a selected number of the most recent at a time, being repeated as needed.  

If the wp\_options table contains more than 350 options, radio buttons are visible that will allow the number of rss\_hash options found to be limited to various numbers of most recent pairs. This can be repeated until the number of options is less than 350.  

When the wp\_options table contains less than 350 options, the plugin finds **ALL** of the "RSS" Options. In each pair, the upper option is the cached feed and the lower is the option's timestamp.  
Those listed may include options that are **Currently Active**  
When shown, rss\_option pairs with dates newer or the same as the date of 14'th newest rss\_option pair (the ones that are more likely to be current) have no checkbox but begin with "-" and end with "*# days old*" in italics.  
The older rss\_options can be selected and end with "**# days old**" in bold.  
Please only remove the older options in which **BOTH** options of the pair can be selected.  

For convenience, a javascript select/deselect all has been added to the plugin. "all" means BOTH "plugin" AND "rss\_" options.  

= Orphaned Options Review =
Spaces have been added after every 10th character of the option\_name and every 20th character of the option\_value to preserve page layout.  
Not all options have values.  
Please review this information very carefully and only remove Options that you know for certain have been orphaned or deprecated.  
It is strongly suggested that you **BACKUP** your database before removing any options.  

== Installation ==
1.  If you are upgrading, deactivate the plugin and remove the cleanoptions.php file from the plugins directory before step 2
2.  Upload the Clean Options folder to the '/wp-content/plugins/' directory.
3.  Activate the plugin through the 'Plugins' menu in WordPress
4.  Click the 'Manage'/'Tools' admin menu link, and select 'CleanOptions'

== Frequently Asked Questions ==
= Does this plugin have any limitations? =
The Clean Option plugin searches only PHP files in your blog's folders for get\_option('option\_name') and get\_settings('option\_name'). It does match slight variations such as get\_option - space - ( - space - " etc. but there may be instances where files use values in the wp\_options table that do not match these patterns. 2 of these alternate forms of syntax are looked for and if found, the plugin will display a warning to help you indentify options that may not really be orphaned.  
Nor does the plugin find unused options. It finds orphaned options, that is, options that do not have any files that "get" their values. Some options are known to have been created by files that are temporary, such as during upgrade and back-up.  

This plugin finds **ALL** of the "rss\_hash" options, even those that are current. Rather than tasking the server with a script that identifies current options, this plugin indentifies options that are *likely* to be current based on their timestamp.  

Because of these limitations, the fact that unused options in the wp\_options table have only a negligible effect upon performance, and the unknown effects of removing needed options, only options that are known to have been orphaned or deprecated should be removed.  

= Will this plugin work with PHP version 4 ? =
Yes. I honestly don't know why some hosts still offer PHP 4 as version 5 has been out for quite some time, but yes, this plugin works with PHP >= 4.2.0 and PHP 5. Because the Clean Options plugin uses native WordPress functions whenever possible, as long as WordPress works with PHP version 4 so will this plugin.  

= How can I help? =
If you find any bugs with this plugin, please let me know. Many thanks to those that have reported problems with the plugin.  
 
I have mixed feelings about the "Alternate Syntax" warnings. On the one hand, not having them may result with those less cautious accidentally deleting options that are not truly orphaned. On the other, having as much information about various situations available should be helpful in making wiser decisions about what options to delete. Alas, many plugins safely use "non-string" option names in their code, and in these cases the warning is not needed as *there are no options associated with them listed*. This introduces a proverbial "cry wolf" scenario that could be potentially annoying at best and distracting at worse. If you encounter such warnings (with a plugin from the *WordPress plugin repository only*, please), please leave a brief comment at the blog [Alternate Syntax](http://www.mittineague.com/blog/2009/03/alternate-syntax/) so I can analyze the plugin and add it to the "ignore" list if it is safe to do so. For those that would rather not see the Alternate Syntax Warnings every time, the show/hide feature can be used. The Alternate Syntax Warnings are not important when dealing with "rss\_hash" options, but it is recommended that you show them before deleting any possibly orphaned plugin options.  

= How about about a select all? =
The Clean Options plugin now has a javascript select/deselect all feature. Please note that the plugin author strongly believes that deleting rows from the wp\_options table should be done thoughtfully and with care. However, for some blogs the table has become so bloated with excessive "rss\_hash" rows, it is obviously more than just a matter of convenience. In fact, in extreme cases, the number of rows is such that the plugin taxes the memory limits of PHP while gathering the information to display them.  

The plugin attempts to remedy this in several ways. A "Delete ALL 'rss' Options" has been added to the plugin (see Other Notes - RSS Options). There is also a limited "Find" (see Other Notes - RSS Options) that will find rss\_hash options limited to batches of various numbers of pairs.  

== Screenshots ==

1. The Orphaned Options list

2. The RSS Options list

3. Example Warning Message

4. The pre-delete Review table

== More Info ==
For more information, please visit the plugin's page  
[Clean Options](http://www.mittineague.com/dev/co.php)

For support, please visit the forum (registration required to post)  
[Support](http://www.mittineague.com/forums/viewtopic.php?t=101)

= Translation Acknowledgements =  
be\_BY [Fat Cow](mailto://zhr@tut.by) [Fat Cow](http://www.fatcow.com)  
de\_DE [Thomas Knapp](mailto://quitzlipochtli@gmail.com) [Blog für Politik, Medien und Philosophie](http://thomasknapp.at)  
es\_ES [Samuel Aguilera](mailto://correo@samuelaguilera.com) [Desarrollo web con WordPress](http://www.samuelaguilera.com)  
hr\_HR [Vladimir](mailto://vdjuranic@gmail.com) [News](http://vladowsky.com)  
nl\_NL [WordPressPluginGuide.com](mailto://info@wppg.me) [WordPress premium themes &#38; plugins](http://wpwebshop.com)  
pt\_BR [Cadu Silva](mailto://cadusilvas@gmail.com) [Winnext](http://www.winnext.com.br/)  
ru\_RU [Vadim N.](http://onix.name) Visit my [Portfolio](http://onix.name/portfolio/)  
sr\_RS [Vladimir](mailto://vdjuranic@gmail.com) [paraISRAEL](http://paraisrael.com)  
-> languages folder contains Serbian Cyrillic, languages/extra folder contains Serbian Latinica  
uk\_UA [Vadim N.](mailto://onix@onix.name) Visit my [Blog](http://onix.name/)  
zh\_CN [Francis](mailto://francis.tm@gmail.com) [Wopus](http://www.wopus.org)  

***********************
** AN IMPORTANT NOTE **
***********************
WARNING !!  

Clean Options version 1.3.2 has not been thoroughly tested for use with WordPress MULTISITES *enabled*.  
Clean Options version 1.3.2 should work with WordPress 3.0 with the exception that if MULTISITES is enabled, the plugin will only find options found in the primary blog's wp_options table.  
If you have MULTISITES enabled Use At Your Own Risk.  
Clean Options 2.0.0 is currently being developed. It will not be fully compatible with older versions of WordPress, but it will be compatible with WordPress 3.0, including finding options if MULTISITES is enabled. However, portions of the i18n l10n files will not have translations for any of the new text - unless and until they are provided.  