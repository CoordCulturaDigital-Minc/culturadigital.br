=== Twitter Widget ===
Contributors: seanys
Donate link: http://www.greenpeace.org/
Tags: twitter, widget
Requires at least: 2.2
Tested up to: 3.4.2
Stable tag: 1.0.5
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description == 
Adds a sidebar widget to display Twitter updates (using the Javascript 
[Twitter 'widgets'](https://twitter.com/settings/widgets))

Sorry guys but *Twitter have discontinued support for 'badges'* and made using this widget WAAAAY more difficult in the process.

Make sure you **READ THE INSTRUCTIONS** for this version.

== Installation == 
1. Go to [Twitter 'widgets'](https://twitter.com/settings/widgets)
1. Create a new 'widget' with your desired settings
 2. **IMPORTANT**: make sure you enter the domain of the site the widget appears on
1. Copy the generated code from the text field labelled "Copy and paste the code into the HTML of your site" and either:
 2. Copy just the data-widget-id *number* from the generated code. Paste the code into Notepad or your favourite text editor and copy the data-widget-id number from there (I know, it's a pain).
  OR
 2. Copy the number from the URL, e.g. https://twitter.com/settings/widgets/<copy this number here>/edit
1. "Save changes" on the widget settings page
1. *Optional* (If you didn't install direct from WordPress.org) Upload the '**twitter-widget.php**' file to your WordPress '*/wp-content/plugins/widgets*' folder.
1. Activate the '*Twitter*' plugin in your WordPress admin '*Plugins*'
1. Go to '*Presentation / Widgets*' in your WordPress admin area.
1. Drag the '*Twitter*' widget to your sidebar.
1. Configure the options:
 2. *Account*: your Twitter account name, required
 2. *Data Widget Id*: the data-widget-id *number*
 2. *Title*: the heading you want to appear above your Twitters in the 
   sidebar, defaults to 'Twitter Updates'

WordPress 2.0.x and 2.1.x users

You guys should also be able use this widget if you install the 
[Widgets Plugin](http://automattic.com/code/widgets/). I tested it
briefly and it appeared to work.

WordPress 2.5 users

Beware, if you leave the '**twitter-widget.php**' file in the 'plugins/widgets' 
folder you will erroneously be prompted to 'upgrade' it on the 'Plugins' 
admin screen. I recommend moving the '**twitter-widget.php**' file 
into the 'plugins' folder.

== Screenshots ==
1. The options screen.

== Legal ==
This software comes without any warranty, express or otherwise, and if it
breaks your blog or results in your cat being shaved, it's not my fault.

== Other == 
Plugin URI: http://seanys.com/2007/10/12/twitter-wordpress-widget/<br />
Author: Sean Spalding<br />
Author URI: http://seanys.com/<br />

== Version History ==
1.0 Initial release

1.0.1 Documentation update

1.0.2 Fixed validation and typo

1.0.3 Stable

1.0.4 Twitter discontinued 'badges', changed to support 'widgets'
