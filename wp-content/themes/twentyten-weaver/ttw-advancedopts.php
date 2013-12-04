<?php
/* admin tab for Advanced Options */

function ttw_advanced_admin() {
    global $ttw_options;

    $myName = esc_attr( get_bloginfo( 'name', 'display' ) );
    $myDescrip = esc_attr( get_bloginfo( 'description', 'display' ) );
    if (strcasecmp($myDescrip,'Just another WordPress site') == 0) $myDescrip = '';

    $headText = "<!-- Add your own CSS snippets between the style tags. -->
<style type=\"text/css\">
</style>";
    $SEOText = "<meta name=\"description\" content=\" $myName - $myDescrip \" />
<meta name=\"keywords\" content=\"$myName blog, $myName\" />";

    if (!ttw_getopt('ttw_head_opts'))
	ttw_setopt('ttw_head_opts', $headText);		// fill in something first time
    if (ttw_getadminopt('ttw_metainfo') == '' && !ttw_getadminopt('ttw_hide_metainfo'))
	ttw_setadminopt('ttw_metainfo', $SEOText);	// fill in something first time
   ?>

    <div style="padding-left: 20px; padding-right: 20px;">

        <form name="ttw_options_form" method="post">
	    <input type="hidden" name="updated" value="1" />

	    <h3>Advanced Options </h3>
	    <p><strong>Advanced Options - Insert your own code or snippets</strong></p>

	    <br /><input type="submit" name="saveadvanced" value="Update Advanced Options" class="button-primary" /><br /><br />

	    <fieldset class="options">
		<p>The fields on this page allow you to save pieces of HTML code required by third-party plugins and widgets.
                You can also use them to save Google Maps/Analytics/AdSense javascript snippets. You will need to understand a bit of HTML
                coding to used these fields effectively.</p>

		<p>The values you put here are saved in the Wordpress database, and will survive theme upgrades and other changes.</p>

		<p>PLEASE NOTE: NO validation is made on the field values, so be careful not to paste invalid code.
                Invalid code is usually harmless, but it can make your site display incorrectly. If your site looks broken after you add stuff here,
                please double check that what you entered uses valid HTML commands. Also note that backslashes will be stripped.</p>

                <hr />

		 <!-- ======== -->

		<label><span style="color:blue;"><b>&lt;HEAD&gt; Section</b></span></label><br/>

                Code entered in this box is included right before &lt;/HEAD&gt; tag in your site. You can add <em>custom CSS</em> here to further enhance the look of your site.
                There are examples of some custom CSS on the <b>Snippets</b> tab. This field is also useful for entering links to javascript files or anything else that
                belongs in the &lt;HEAD&gt;, but this use will be uncommon - usually you can find a WP Plugin to do what you need.
		<br />

		<textarea name="ttw_head_opts" rows=7 style="width: 95%"><?php echo(str_replace("\\", "", ttw_getopt('ttw_head_opts'))); ?></textarea>
                <br /><br />

		 <!-- ======== -->

		<label><span style="color:blue;"><b>Site Footer Area</b></span></label><br/>
                This code will be inserted into the site footer area, right before the before the "Powered by" credits, but after any Footer widgets. This
                could include extra information, visit counters, etc. You can use HTML here (including
		<a href="http://codex.wordpress.org/Shortcode" target="_blank">WP shortcodes</a> - see next section), so include style tags if you want!
		<br />

		<textarea name="ttw_footer_opts" rows=5 style="width: 95%"><?php echo(str_replace("\\", "", ttw_getopt('ttw_footer_opts'))); ?></textarea>
		<br /><br />

		 <!-- ======== -->

		<label><span style="color:blue;"><b>Site Header Insert Code</b></span></label><br/>
                This must be HTML markup code (including <a href="http://codex.wordpress.org/Shortcode" target="_blank">WP shortcodes</a>),
		and will be inserted into the <em>#branding div</em> header area right above where the standard site
		header image goes. You can use it for logos, better site name text - whatever. When used in combination with hiding the site title,
		header image, and the menu, you can design a completely custom header. If you hide the title, image, and header, no other code is generated
		in the #branding div, so this code can be a complete header replacement. You will almost certainly need to add some CSS style, too.
		You can override #branding, create a new div, or use in-line styling. You can also use WP shortcodes to embed plugins, including rotating image slideshows
		such as <a href="http://www.jleuze.com/plugins/meteor-slides/" target="_blank">Meteor Slides</a>. And Weaver automatically supports the
		<a href="http:wordpress.org/extend/plugins/dynamic-headers/" target="_blank">Dynamic Headers</a> plugin which allows you
		create highly dynamic headers from its control panel - just install and it will work without any other code edits.
		<br />
		<textarea name="ttw_header_insert" rows=5 style="width: 95%"><?php echo(str_replace("\\", "", ttw_getopt('ttw_header_insert'))); ?></textarea>
		<br />
		<label>Insert on Front Page Only: </label><input type="checkbox" name="ttw_header_frontpage_only" id="ttw_header_frontpage_only" <?php echo (ttw_getopt( 'ttw_header_frontpage_only' ) ? "checked" : ""); ?> />
		<small>If you check this box, then this Header code will be used only when the front page is displayed. Other
		pages will be displayed using normal header settings. Checking this will also automatically hide the standard
		header image on the front page so you can use a slide show on the front page, and standard header images on other pages.</small>
		<br /><br />

		<!-- ======== -->

                <label><span style="color:#4444CC;"><b>SEO Tags</b></span></label><br/>
		Every site should have at least "description" and "keywords" meta tags
		for basic SEO (Search Engine Optimization) support. Please edit these tags to provide more information about your site, which is inserted
		into the &lt;HEAD&gt; section of your site. You might want to check out other Wordpress SEO plugins if you need more advanced SEO. Note
		that this information is not part of your theme settings, and will not be included when you save or restore your theme.
		<br />

		<textarea name="ttw_metainfo" rows=3 style="width: 95%"><?php echo(str_replace("\\", "", ttw_getadminopt('ttw_metainfo'))); ?></textarea>
		<br>
                <label>Don't add SEO meta tags: </label><input type="checkbox" name='ttw_hide_metainfo' id='ttw_hide_metainfo' <?php echo (ttw_getadminopt( 'ttw_hide_metainfo' ) ? "checked" : ""); ?> />
		<small>If you check this box, then this meta information will not be added to your site. You might want to check this box if you are using
		more advanced Wordpress SEO plugins.</small>
                <br /><br />

		<!-- ======== -->

                <label><span style="color:#4444CC;"><b>Site Copyright</b></span></label><br/>
		If you fill this in, the default copyright notice in the footer will be replaced with the text here. It will not
		automatically update from year to year. Use &amp;copy; to display &copy;. You can use other HTML as well.
		<br />

		<textarea name="ttw_copyright" rows=1 style="width: 95%"><?php echo(str_replace("\\", "", ttw_getadminopt('ttw_copyright'))); ?></textarea>
		<br>
                <label>Hide Powered By tag: </label><input type="checkbox" name='ttw_hide_poweredby' id='ttw_hide_poweredby' <?php echo (ttw_getadminopt( 'ttw_hide_poweredby' ) ? "checked" : ""); ?> />
		<small>Check this to hide the "Proudly powered by" notice in the footer.</small>
                <br /><br />

		<!-- ======== -->

                <label><span style="color:#4444CC;"><b>The Last Thing</b></span></label><br/>
		This code is inserted right before the closing &lt;/body&gt; tag.
                This is the best place for your Google analytics code and other final code that is not usually displayed. Note
		that this information is not part of your theme settings, and will not be included when you save or restore your theme.
		<br />

		<textarea name="ttw_end_opts" rows=5 style="width: 95%"><?php echo(str_replace("\\", "", ttw_getadminopt('ttw_end_opts'))); ?></textarea>

                <br /><br />

		<!-- ======== -->

		<label><span style="color:#8888FF;"><b>Predefined Theme CSS Rules</b></span></label><br/>
		Many of the predefined themes include extra CSS rules (and possibly other HTML tags) to define their style, and those rules are included here.
		You may edit them if needed to make your own theme work. If you are defining a theme you want to share, you should move the definitions from
		the &lt;HEAD&gt; Section to here for your final version. That will leave the &lt;HEAD&gt; Section empty for others to add more customizations.
		This code is included before the &lt;HEAD&gt; Section code in the final HTML output file.
		<br />

		<textarea name="ttw_theme_head_opts" rows=3 style="width: 95%"><?php echo(str_replace("\\", "", ttw_getopt('ttw_theme_head_opts'))); ?></textarea>
		<hr />
		<label><span style="color:green;"><b>Administrative Options</b></span></label><br/>
		These options control some administrative options and appearance features.
		<br /><br />
		<label>Hide Site Preview: </label><input type="checkbox" name="ttw_hide_preview" id="ttw_hide_preview" <?php echo (ttw_getadminopt( 'ttw_hide_preview' ) ? "checked" : ""); ?> />
		    <small>Checking this box will hide the Site Preview at the bottom of the screen which might speed up response a bit.</small><br />
		<label>Hide Theme Thumbnails: </label>
		<input type="checkbox" name="ttw_hide_theme_thumbs" id="ttw_hide_theme_thumbs" <?php echo (ttw_getadminopt( 'ttw_hide_theme_thumbs' ) ? "checked" : ""); ?> />
		    <small>Checking this box will hide the Sub-theme preview thumbnails on the Weaver Themes tab which might speed up response a bit.</small><br />
		</fieldset>

		<br /><input type="submit" name="saveadvanced" value="Update Advanced Options" class="button-primary" /><br /><br />
	</form>
	<hr />
	<form name="ttw_resetweaver_form" method="post" onSubmit="return confirm('Are you sure you want to reset all Weaver settings?');">
	    Click the Clear button to reset all Weaver settings to the default values.<br > <em>Warning: You will lose all current settings.</em> You should use the Save/Restore tab to save a copy
	    of your current settings before clearing! <span class="submit"><input type="submit" name="reset_weaver" value="Clear All Weaver Settings"/></submit>
	</form>
     </div>

<hr />
<?php
}
?>
