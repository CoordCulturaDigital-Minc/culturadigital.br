<?php
// This file is included from functions.php. It will be loaded only when the wp_head action is called from Wordpress.
//  It is also isolates all the CSS overrides that are generated as a result of theme settings.

function ttw_generate_wphead() {
    /* this guy does ALL the work for generating theme look - it writes out the over-rides to the standard style.css */
	global $ttw_options, $ttwHeadOptions;

	printf("\n<!-- This site is using %s %s subtheme: %s -->\n",TTW_THEMENAME, TTW_VERSION, ttw_getopt('ttw_subtheme'));
	if (!ttw_getadminopt('ttw_hide_metainfo'))
	    echo(str_replace("\\", "", ttw_getadminopt('ttw_metainfo')."\n"));
        echo('<style type="text/css">'."\n");
	/* here, we output our style sheet overrides */

	if (($val = ttw_getopt("ttw_after_header")) != ttw_getopt_std("ttw_after_header")) {
            echo("#main { padding: $val"); echo ("px 0 0 0; }\n");
        }

	/* **** Rounded Corners **** */
        if (ttw_getopt("ttw_rounded_corners")) {
            echo("#container, #primary, #secondary, #ttw-top-widget, #ttw-bot-widget {-moz-border-radius: 10px; -webkit-border-radius: 10px; border-radius: 10px;}\n");
	    if (ttw_getopt('ttw_move_menu')) {
		echo('#access2 {-moz-border-radius-bottomleft: 7px; -moz-border-radius-bottomright: 7px;
  -webkit-border-bottom-left-radius: 7px; -webkit-border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; border-bottom-right-radius: 7px;}');
		echo('#access {-moz-border-radius-topleft: 7px; -moz-border-radius-topright: 7px;
  -webkit-border-top-left-radius: 7px; -webkit-border-top-right-radius: 7px; border-top-left-radius: 7px; border-top-right-radius: 7px;}');
	    } else {
		echo('#access {-moz-border-radius-bottomleft: 7px; -moz-border-radius-bottomright: 7px;
  -webkit-border-bottom-left-radius: 7px; -webkit-border-bottom-right-radius: 7px;border-bottom-left-radius: 7px; border-bottom-right-radius: 7px;}');
		echo('#access2 {-moz-border-radius-topleft: 7px; -moz-border-radius-topright: 7px;
  -webkit-border-top-left-radius: 7px; -webkit-border-top-right-radius: 7px;border-top-left-radius: 7px; border-top-right-radius: 7px;}');
	    }
            echo("#wrapper {-moz-border-radius: 10px; -webkit-border-radius: 10px; border-radius: 10px; margin-top: 15px; margin-bottom: 15px;}\n");
        }

	/* **** Use Borders **** */
        if (ttw_getopt("ttw_useborders")) {
            echo("#wrapper {border: 1px solid #222222; padding-right: 20px; margin-top: 15px; margin-bottom: 15px;}\n");
            echo("#header {margin-top: 0px;}");
	    echo("#primary, #secondary, #ttw-top-widget, #ttw-bot-widget {border: 1px solid #222222;}\n");
        }

	/* **** Widget text color **** */
	if (($widgetColor = ttw_getopt_newcolor('ttw_widget_color'))) {
		t_css_color('body, input, textarea',$widgetColor);
	}

	/* **** Content area text color **** */
	if (($contentColor = ttw_getopt_newcolor('ttw_content_color')) ) {
		t_css_color('#content, #content input, #content textarea',$contentColor);
	}

	/* **** Smaller content font **** */
        if (ttw_getopt("ttw_small_content_font")) {
            echo("#content {font-size: 120%; line-height: 125%; }\n");  /* stange, but true! */
            echo("#comments {font-size: 90%; line-height: 90%; }\n");  /* stange, but true! */
        }

	/* **** Info label color **** */
	if (($infoColor = ttw_getopt_newcolor('ttw_info_color'))) {
	    t_css_color('#comments .pingback p', $infoColor);
	    t_css_color('#respond label, #respond dt, #respond dd', $infoColor);
	    t_css_color('.entry-meta, .entry-content label, .entry-utility', $infoColor);
	    t_css_color('#content .wp-caption, #content .gallery .gallery-caption', $infoColor);
	    t_css_color('.navigation', $infoColor);
	}

	/* **** Content text **** */
	if (($textColor = ttw_getopt_newcolor('ttw_text_color'))) {
	    t_css_color('#content h1, #content h2, #content h3, #content h4, #content h5, #content h6',$textColor);
	    t_css_color('h1, h2, h3, h4, h5, h6',$textColor);
	    t_css_color('.page-title',$textColor);
	    t_css_color('#content .entry-title',$textColor);
	    t_css_color('.page-link',$textColor);
	    t_css_color('#entry-author-info h2',$textColor);
	    t_css_color('h3#comments-title, h3#reply-title',$textColor);
	    t_css_color('.comment-author cite',$textColor);
	    t_css_color('.entry-content fieldset legend',$textColor);
	}

	/* **** Footer Border **** */
	if (($bgColor = ttw_getopt_newcolor('ttw_footer_border_color'))) {
	    echo("#colophon { border-top: 4px solid $bgColor ;  }\n");
	}

	/* **** <HR> color **** */
	if (($bgColor = ttw_getopt_newcolor("ttw_hr_color"))) {
	    t_css_bgcolor('hr',$bgColor);
	}

	/* **** Bar under Titles **** */
	$val = (int)ttw_getopt('ttw_header_underline');       /* bar under headers */
        if ($val != '' && $val != 0) {
            $titleColor = ttw_getopt("ttw_text_color");
            echo(".entry-title {border-bottom: $val"."px solid $titleColor; }\n");
        }

	/* **** Bar under Widget Titles **** */
	$val = (int)ttw_getopt('ttw_widget_header_underline');       /* bar under widget */
        if ($val != '' && $val != 0) {
            $titleColor = ttw_getopt("ttw_widget_title_color");
            echo(".widget-title {border-bottom: $val"."px solid $titleColor; }\n");
        }

	/* for main div areas, let's just always output the bg color to avoid any inheritance issues */
	if (($bgColor = ttw_getopt("ttw_page_bgcolor"))) {
		t_css_bgcolor('#wrapper',$bgColor);
	}
	if (($bgColor = ttw_getopt("ttw_main_bgcolor"))) {
		t_css_bgcolor('#main',$bgColor);
	}
	if (($bgColor = ttw_getopt("ttw_container_bgcolor"))) {
		t_css_bgcolor('#container',$bgColor);
	}
        if (($bgColor = ttw_getopt("ttw_content_bgcolor"))) {
		t_css_bgcolor('#content',$bgColor);
	}
	if (($bgColor = ttw_getopt("ttw_footer_bgcolor"))) {
		t_css_bgcolor('#footer',$bgColor);
	}
	if (($bgColor = ttw_getopt("ttw_side1_bgcolor"))) {
		t_css_bgcolor('#primary',$bgColor, 'padding-left: 10px; padding-top: 10px; margin-bottom: 5px;');
        }
	if (($bgColor = ttw_getopt("ttw_side2_bgcolor"))) {
		t_css_bgcolor('#secondary',$bgColor, 'padding-left: 10px; padding-top: 10px; margin-bottom: 5px;');
 	}
	if (($bgColor = ttw_getopt("ttw_topbottom_bgcolor"))) {
		t_css_bgcolor('#ttw-top-widget, #ttw-bot-widget',$bgColor, 'padding-left: 10px; padding-top: 10px; margin-bottom: 5px;');
	}

	if (($wigtitleColor = ttw_getopt_newcolor("ttw_widget_title_color"))) {
		t_css_color('.widget-title', $wigtitleColor);
		t_css_color('.widget_search label', $wigtitleColor);
		t_css_color('#wp-calendar caption', $wigtitleColor);
        }

        $val = ttw_getopt("ttw_list_bullet");       /* add a new lsit bullet */
        if ($val && $val != '' && $val != 'default') {
	    if ($val == 'none' || $val == 'circle' || $val == 'disc' || $val == 'square')
	       printf(".widget-area ul ul {list-style:%s;}\n",$val);
	    else
	    printf(".widget-area ul ul {list-style:none; list-style-position:inside; list-style-image: url(%s/images/bullets/%s.gif);}\n",
	       get_bloginfo('stylesheet_directory'),$val);
        }
	$val = ttw_getopt("ttw_contentlist_bullet");       /* add a new lsit bullet */
        if ($val && $val != '' && $val != 'default') {
	    if ($val == 'none' || $val == 'circle' || $val == 'disc' || $val == 'square')
	       printf("ul {list-style:%s;}\n",$val);
	    else
            printf("ul {list-style:none;list-style-image: url(%s/images/bullets/%s.gif);}\n",
	       get_bloginfo('stylesheet_directory'),$val);
        }

	if (ttw_getopt('ttw_fadebody_bg')) {
	printf("body {background-image: url(%s/images/gr.png); background-attachment: scroll; background-repeat: repeat-x;}\n",
	       get_bloginfo('stylesheet_directory'));
    }

	if (($optColor = ttw_getopt_newcolor("ttw_link_color"))) { /* normal links */
		t_css_color('a:link', $optColor);
	}
	if (($optColor = ttw_getopt_newcolor("ttw_link_visited_color"))) {
		t_css_color('a:visited', $optColor);
	}
	if (($optColor = ttw_getopt_newcolor("ttw_link_hover_color"))) {
		t_css_color('a:active, a:hover', $optColor);
	}

	if (($optColor = ttw_getopt_newcolor("ttw_wlink_color"))) { /* widget links */
		t_css_color('#primary a:link, #secondary a:link, #footer-widget-area a:link', $optColor);
	}
	if (($optColor = ttw_getopt_newcolor("ttw_wlink_visited_color"))) {
		t_css_color('#primary a:visited, #secondary a:visited, #footer-widget-area a:visited', $optColor);
	}
	if (($optColor = ttw_getopt_newcolor("ttw_wlink_hover_color"))) {
		t_css_color('#primary a:hover, #secondary a:hover, #footer-widget-area a:hover', $optColor);
	}

	if (($optColor = ttw_getopt_newcolor("ttw_plink_color"))) { /* post title links */
		t_css_color('.entry-title a:link', $optColor);
		t_css_color('.widget_rss a.rsswidget:link', $optColor);
	}
	if (($optColor = ttw_getopt_newcolor("ttw_plink_visited_color"))) {
		t_css_color('.entry-title a:visited', $optColor);
		t_css_color('.widget_rss a.rsswidget:visited', $optColor);
	}
	if (($optColor = ttw_getopt_newcolor("ttw_plink_hover_color"))) {
		t_css_color('.entry-title a:active, .entry-title a:hover', $optColor);
		t_css_color('.widget_rss a.rsswidget:active, .widget_rss a.rsswidget:hover', $optColor);
	}

	if (($optColor = ttw_getopt_newcolor("ttw_ilink_color"))) { /* post title links */
		t_css_color('.page-title a:link', $optColor);
		t_css_color('.entry-meta a:link', $optColor);
		t_css_color('.entry-utility a:link', $optColor);
		t_css_color('.navigation a:link', $optColor);
		t_css_color('.comment-meta a:link', $optColor);
		t_css_color('.reply a:link, a.comment-edit-link:link', $optColor);
	}
	if (($optColor = ttw_getopt_newcolor("ttw_ilink_visited_color"))) {
		t_css_color('.page-title a:visited', $optColor);
		t_css_color('.entry-meta a:visited', $optColor);
		t_css_color('.entry-utility a:visited', $optColor);
		t_css_color('.navigation a:visited', $optColor);
		t_css_color('.comment-meta a:visited', $optColor);
		t_css_color('.reply a:visited, a.comment-edit-link:visited', $optColor);
	}
	if (($optColor = ttw_getopt_newcolor("ttw_ilink_hover_color"))) {
		t_css_color('.page-title a:active, .page-title a:hover', $optColor);
		t_css_color('.entry-meta a:hover, .entry-meta a:active', $optColor);
		t_css_color('.entry-utility a:hover, .entry-utility a:active', $optColor);
		t_css_color('.navigation a:active, .navigation a:hover', $optColor);
		t_css_color('.comment-meta a:active, .comment-meta a:hover', $optColor);
		t_css_color('.reply a:active, a.comment-edit-link:active, .reply a:hover, a.comment-edit-link:hover', $optColor);
	}

	if (($optColor = ttw_getopt_newcolor("ttw_menubar_color"))) {
		t_css_bgcolor('#access, #access2', $optColor);
	}
	if (ttw_getopt("ttw_bold_menu")) {
		echo("#access, #access li ul ul > a {font-weight:bold;}");
		echo("#access2, #access2 li ul ul > a {font-weight:bold;}");
	}
	if (($optColor = ttw_getopt_newcolor("ttw_menubar_hoverbg_color"))) {
		t_css_bgcolor('#access ul ul a, #access li:hover > a, #access ul ul :hover > a ', $optColor);
		t_css_bgcolor('#access2 ul ul a, #access2 li:hover > a, #access2 ul ul :hover > a ', $optColor);
	}
	if (($optColor = ttw_getopt_newcolor("ttw_menubar_text_color"))) {
		t_css_color('#access a', $optColor);
		t_css_color('#access2 a', $optColor);
	}
	if (($optColor = ttw_getopt_newcolor("ttw_menubar_hover_color"))) {
		t_css_color('#access li:hover > a, #access ul ul :hover > a', $optColor);
		t_css_color('#access2 li:hover > a, #access2 ul ul :hover > a', $optColor);
	}
	if (($optColor = ttw_getopt_newcolor("ttw_menubar_curpage_color"))) {
		t_css_color('#access ul li.current_page_item > a, #access ul li.current-menu-ancestor > a,
#access ul li.current-menu-item > a, #access ul li.current-menu-parent > a', $optColor);
		t_css_color('#access2 ul li.current_page_item > a, #access2 ul li.current-menu-ancestor > a,
#access2 ul li.current-menu-item > a, #access ul li.current-menu-parent > a', $optColor);
	}

	if (($optColor = ttw_getopt_newcolor('ttw_title_color'))) {
		echo("#site-title a { color: ".$optColor."; }\n");
	}

	if (($optColor = ttw_getopt_newcolor('ttw_desc_color'))) {
		echo("#site-description { color: ".$optColor."; }\n");
	}

	if (ttw_getopt('ttw_title_on_header')) {
	    printf("#site-title {position: absolute; margin-top: 44px; margin-left: 40px; }
#site-description { text-align:left; clear: both; float: left; position:absolute;  margin-top: 90px;  margin-left: 40px;}\n");

	}

	/* Content Font-family */
	if (($useFont = t_get_font_value('ttw_content_font')) != '') {
		t_css_fontfamily('body, input, textarea, .page-title span, .pingback a.url', $useFont);
	}

	/* Title Font-family */
	if (($useFont = t_get_font_value('ttw_title_font')) != '') {
		t_css_fontfamily(
"h3#comments-title, h3#reply-title, #access .menu, #access div.menu ul, #access2 .menu, #access2 div.menu ul,
#cancel-comment-reply-link, .form-allowed-tags, #site-info, #site-title, #wp-calendar,
.comment-meta, .comment-body tr th, .comment-body thead th, .entry-content label, .entry-content tr th,
.entry-content thead th, .entry-meta, .entry-title, .entry-utility, #respond label, .navigation,
.page-title, .pingback p, .reply, .widget_search label, .widget-title, input[type=submit]", $useFont);
	}

        /* make the site Tagline large */
	if (ttw_getopt("ttw_large_tagline")) {
		echo("#site-description {font-size:130%; font-style:bold;}");
	}

	$sidebars = ttw_getopt("ttw_sidebars");
	$themewidth = ttw_getopt("ttw_header_image_width");
	if (! $themewidth) $themewidth = '940';
	$sidebarwidth = ttw_getopt("ttw_sidebar_width");

	/* SIDEBAR layout - need to calculate and override items if change width.
	 *	First, calculate and emit everything that is constant for each alternative:
	 *	Footer width, site-info, site-generator, site-title, site-description.
	 */
	$mw = (int) $themewidth;		/* main width */
	$fw = (int)((($mw+20)/4)-20);		/* footer width ((mainwidth+20)/4)-20  */
	$si = (int) ( ($mw - 40) * .50 );	/* site info = 60% of mainwidth-40*/
	$sg = (int) ( $mw - 50 - $si );		/* site generator - left over space */
	$caw = (int) $mw - 40;			/* mainwidth - 40 */
	$ocw = (int)($mw * .90);		/* one column width */
	$tbmult = 0.85;				// usually make top/bottom widget areas 85%
	if (ttw_getopt('ttw_wide_top_bottom')) $tbmult = 1;

	if ($mw != 940)	{			/* non-default width means override footer, etc. */
	    printf("\n#access .menu-header, #access2 .menu-header, div.menu, #colophon, #branding, #main, #wrapper { width: %dpx; }\n",$mw);
	    printf("#footer-widget-area .widget-area {width: %dpx; }\n",$fw);
	    printf("#site-info { width: %dpx;}\n",$si);
	    printf("#site-generator {text-align:right; width: %dpx; }\n",$sg);
	    printf("#site-title { width: 55%%;} ");
	    printf("#site-description {text-align:right; padding-right: 20px; width: 40%%;}\n");
	    printf("#access, #access2 { width: %dpx; }\n",$mw);
	    printf("#access .menu-header, #access2 .menu-header, div.menu {width: %dpx;}\n",$mw-12);
	    printf("#content .attachment img { max-width: %dpx;}\n",$caw);
	    printf(".single-attachment #content { width: %dpx;}\n",$caw);
	    printf(".one-column #content { margin-left: %dpx; padding: 0; width: %dpx;}\n",(int)($mw*.05),$ocw);
	    printf("#main {margin-bottom:4px;}\n");
	}

	if ($sidebars == SB_none) { /* no sidebars - simply hide them */
	    if ($themewidth != 940 || $sidebarwidth || $tbmult == 1) {
		$sidebarwidth = 0; /* use default if not set */
		/* contentwidth + primary_secondary_width == (mainwidth-50) */
		$contentw = (int)($mw - 50 - $sidebarwidth);	/* from formula */
		$containerw = (int)($contentw + 38);		/* contentwidth + 38 */
		if (ttw_getopt('ttw_wide_top_bottom')) $ttwwid = $contentw;	// same as content?
		else $ttwwid = (int)($contentw - 70);		/* contentwidth - 70 */

    printf("#container { float: left; margin: 0 0px 4px 0; width: %dpx; }\n",$containerw);
    printf("#content {width: %dpx; overflow:hidden; margin:0 0px 10px 0px; padding: 5px 0px 0px 20px;}\n",$contentw);
    printf("#content img { max-width: %dpx;}\n",$contentw);
    printf("#primary, #secondary { visibility:hidden; width:0px; height: 0px;}\n");
    if (ttw_getopt('ttw_wide_top_bottom')) printf("#ttw-top-widget, #ttw-bot-widget {width: %dpx; margin-left: 20px;}\n",$ttwwid);
    else printf("#ttw-top-widget, #ttw-bot-widget {width: %dpx;}\n",$ttwwid);

	    } else {	/* using defaults, so make shorter */
	?>
#container { width:940px; margin: 0px 0px -30px 0px; }
#content { width: 860px; margin: 0 100px 30px 0px; overflow:hidden; padding-top: 10px;}
#content img { max-width: 860px;}
#primary { visibility:hidden; width:0px; height: 0px;}
#secondary { visibility:hidden; width:0px; height: 0px;}
#ttw-top-widget, #ttw-bot-widget {width: 800px;}
.one-column #content { margin: 0 0 0 80px; padding: 0; width: 860px;}
	<?php
	    }
	} elseif ($sidebars == SB_2c) {	/* 2 sidebars, main central column */
if ($themewidth != 940 || $sidebarwidth || $tbmult == 1 ) {
		if (! $sidebarwidth) $sidebarwidth = 220; /* use default if not set */
		/* contentwidth = mainwidth - 40 - (2*sidebarwidth ) */
		$contentw = (int)($mw - 40 - ($sidebarwidth*2));	/* from formula */
		$containerw = (int)($mw);		/* same a mw */
		$ttwwid = (int)($contentw * $tbmult);	/* 85%  or 100% */
		if ($tbmult == 1) {$ttwmargin = $sidebarwidth+20; $ttwwid -= 10; }
		else $ttwmargin = (int)($sidebarwidth + $contentw * .115);

		if (ttw_getopt('ttw_useborders')) $second_left = (int)$sidebarwidth+6;
		else $second_left = (int)$sidebarwidth+4;

    printf("#container { width:%dpx; float:left; margin:0 0 4px 0px;}\n",$containerw);
    printf("#content { width: %dpx; margin: 0px 0px 5px %dpx; overflow:hidden; padding: 5px 20px 4px 20px; }\n",
	   $contentw,$sidebarwidth);
    printf("#content img { max-width: %dpx;}\n",$contentw);
    printf("#primary { width:%dpx; float:left; margin: 0 0 4px -%dpx; }\n",$sidebarwidth-6,$mw);
    printf("#secondary { width:%dpx; float:left; margin: 0 0 4px -%dpx;}\n",$sidebarwidth-6,$second_left);
    printf("#ttw-top-widget, #ttw-bot-widget {width: %dpx; margin-left: %dpx;}\n",$ttwwid, $ttwmargin);
	    } else {	/* using defaults, so make shorter */
	?>
#container { width:940px; float:left; margin:0 0 4px 0px; }
#content { width: 460px; margin: 0px 0px 5px 220px; overflow:hidden; padding: 5px 20px 4px 20px; }
#content img { max-width: 460px;}
#primary { width:214px; float:left; margin: 0 0 4px -940px; }
#secondary { width:214px; float:left; margin: 0 0 4px -226px;}
#ttw-top-widget, #ttw-bot-widget {width: 400px; margin-left: 265px;}
.one-column #content { margin: 0 0 0 0 px; padding: 0; width: 800px;}
	<?php
	    }
	} elseif ($sidebars == SB_2r) {
	    if ($themewidth != 940 || $sidebarwidth || $tbmult == 1) {
		if (! $sidebarwidth) $sidebarwidth = 220; /* use default if not set */
		$sb2 = (int)($sidebarwidth*.75);
		$contentw = (int)($mw - 60 - $sidebarwidth - $sb2);	/* from formula */
		if (ttw_getopt('ttw_useborders')) $containerw = (int)($contentw + 36);
		else $containerw = (int)($contentw + 40);

		$ttwwid = (int)($contentw * $tbmult);
		if ($tbmult == 1) { $ttwmargin = 0; $ttwwid += 5; }
		else $ttwmargin = (int)($contentw*.115);

    printf("#container { width:%dpx; float:left; margin:0 0px 4px 0px;}\n",$containerw);
    printf("#content {width: %dpx; overflow:hidden; margin:0 0px 10px 0px; padding: 5px 0px 0px 20px;}\n",$contentw);
    printf("#content img { max-width: %dpx;}\n",$contentw);
    printf("#primary { width:%dpx; float:left; margin:0 0px 4px 0px;}\n",$sidebarwidth);
    printf("#secondary { width:%dpx; float:left; margin:0 0 4px 0px;}\n",$sb2);
    printf("#ttw-top-widget, #ttw-bot-widget {width: %dpx;margin-left:%dpx;}\n",$ttwwid,$ttwmargin);
	    } else {	/* using defaults, so make shorter */
	?>
#container { width:525px; float:left; margin:0 0px 4px 0px;}
#content { width: 490px; overflow:hidden; margin:0 0px 10px 0px; padding: 5px 0px 0px 20px;}
#content img { max-width: 490px;}
#primary { width:220px; float:left; margin:0 0px 4px 0px;}
#secondary { width:170px; float:left; margin:0 0 4px 0px;}
#ttw-top-widget, #ttw-bot-widget {width: 420px; margin-left: 40px;}
.one-column #content { margin: 0 0 0 80px; padding: 0; width: 800px;}
	<?php
	    }
	} elseif ($sidebars == SB_2l) {
	    if ($themewidth != 940 || $sidebarwidth || $tbmult == 1) {
		if (! $sidebarwidth) $sidebarwidth = 220; /* use default if not set */
		$sb2 = (int)($sidebarwidth*.75);
		$contentw = (int)($mw - 60 - $sidebarwidth - $sb2);	/* from formula */
		if (ttw_getopt('ttw_useborders')) $containerw = (int)($contentw + 36);
		else $containerw = (int)($contentw + 40);
		$ttwwid = (int)($contentw * $tbmult);
		if ($tbmult == 1) $ttwmargin = 18;
		else $ttwmargin = (int)($contentw*.115);

    printf("#container { width:%dpx; float:right; margin:0 0 4px 0px;}\n",$containerw);
    printf("#content { width: %dpx; overflow:hidden; float:right; padding: 5px 10px 5px 0px; margin:0 10px 4px 0px;}\n",$contentw);
    printf("#content img { max-width: %dpx;}\n",$contentw);
    printf("#primary { width:%dpx; float:left; margin:0 0px 4px 0px;}\n",$sidebarwidth-4);
    printf("#secondary { width:%dpx; float:left; margin:0 0 4px 0px; clear:none;}\n",$sb2);
    printf("#ttw-top-widget, #ttw-bot-widget {width: %dpx;margin-left:%dpx;}\n",$ttwwid,$ttwmargin);
    printf(".one-column #content { margin-right: %dpx;}\n",(int)($mw*.05));
	    } else {	/* using defaults, so make shorter */
	?>
#container { width:530px; float:right; margin:0 0 4px 0px;}
#content { width: 490px; overflow:hidden; float:right;  padding: 5px 10px 5px 0px; margin:0 10px 4px 0px;}
#content img { max-width: 490px;}
#primary { width:216px; float:left; margin:0 0px 4px 0px;}
#secondary { width:170px; float:left; margin:0 0 4px 0px; clear:none;}
#ttw-top-widget, #ttw-bot-widget {width: 420px;}
.one-column #content {margin: 0 60px 0 0px; padding: 0; width: 800px;}
	<?php
	    }
	} elseif ($sidebars == SB_1l) {
	    if ($themewidth != 940 || $sidebarwidth || $tbmult == 1) {
		if (! $sidebarwidth) $sidebarwidth = 220; /* use default if not set */
		$contentw = (int)($mw - 70 - $sidebarwidth);	/* from formula */
		$ttwwid = (int)($contentw * $tbmult);
		if ($tbmult == 1) $ttwmargin = 30;
		else $ttwmargin = (int)($contentw*.115);

    printf("#container { float: right; margin: 0 -%dpx 4px 0; width: 100%%;}\n",$sidebarwidth+20);
    printf("#content { margin: 0px %dpx 4px 0px; width: %dpx; padding: 10px 25px 5px 25px;}\n",
	   $sidebarwidth+20,$contentw);
    printf("#content img { max-width: %dpx;}\n",$contentw);
    printf("#primary, #secondary { float: left;  width: %dpx; padding-left: 15px; margin-bottom:4px;}\n",$sidebarwidth+3);
    printf("#secondary { clear: left; }\n");
    printf("#ttw-top-widget, #ttw-bot-widget {width: %dpx; margin-left: %dpx;}\n",$ttwwid, $ttwmargin);
    printf(".one-column #content { margin-left: %dpx;}\n",(int)($mw*.05-$sidebarwidth-20));
  	    } else {	/* using defaults, so make shorter */
	?>
#container { float: right; margin: 0 -240px 4px 0; width: 100%;}
#content { margin: 0px 240px 4px 0px; width: 640px; padding: 10px 25px 5px 25px;}
#content img { max-width: 640px;}
#primary, #secondary { float: left;  width: 223px; padding-left: 15px; margin-bottom:4px;}
#secondary { clear: left;}
#ttw-top-widget, #ttw-bot-widget {width: 600px;}
.one-column #content { margin: 0 0 0 -160px; padding: 0; width: 800px;}
    <?php
	    }
	} elseif ($sidebars == SB_1rw) {
	    if ($themewidth != 940 || $sidebarwidth  || $tbmult == 1) {
		if (! $sidebarwidth) $sidebarwidth = 300; /* use default if not set */
		/* contentwidth + primary_secondary_width == (mainwidth-50) */
		$contentw = (int)($mw - 50 - $sidebarwidth);	/* from formula */
		$containerw = (int)($contentw + 38);		/* contentwidth + 38 */
		$ttwwid = (int)($contentw - 70);		/* contentwidth - 70 */
		if ($tbmult == 1) $ttwwid = $contentw;

    printf("#container { float: left; margin: 0 0px 4px 0; width: %dpx; }\n",$containerw);
    printf("#content {width: %dpx; overflow:hidden; margin:0 0px 10px 0px; padding: 5px 0px 0px 20px;}\n",$contentw);
    printf("#content img { max-width: %dpx;}\n",$contentw);
    printf("#primary, #secondary { float: right; overflow: hidden; width: %dpx; margin: 0 0 4px 0;}\n",$sidebarwidth);
    if ($tbmult == 1) printf("#ttw-top-widget, #ttw-bot-widget {width: %dpx; margin-left: 1px; margin-right: 1px;}\n",$ttwwid);
    else printf("#ttw-top-widget, #ttw-bot-widget {width: %dpx;}\n",$ttwwid);

	    } else {	/* using defaults, so make shorter */
	?>
#main {margin-bottom:4px;}
#container { float: left; margin: 0 0px 4px 0; width: 628px; }
#content {width: 590px; overflow:hidden; margin:0 0px 10px 0px; padding: 5px 0px 0px 20px;}
#content img { max-width: 590px;}
#primary, #secondary { float: right; overflow: hidden; width: 300px; margin: 0 0 4px 0;}
#ttw-top-widget, #ttw-bot-widget {width: 520px;}
.one-column #content { margin: 0 0 0 80px; padding: 0; width: 800px;}
	<?php
	    }
	} else {		/* default right sidebar */
if ($themewidth != 940 || $sidebarwidth || $tbmult == 1) {
		if (! $sidebarwidth) $sidebarwidth = 220; /* use default if not set */
		/* contentwidth + primary_secondary_width == (mainwidth-50) */
		$contentw = (int)($mw - 50 - $sidebarwidth);	/* from formula */
		$containerw = (int)($contentw + 38);		/* contentwidth + 38 */
		$ttwwid = (int)($contentw - 70);		/* contentwidth - 70 */
		if ($tbmult == 1) $ttwwid = $contentw;

    printf("#container { float: left; margin: 0 0px 4px 0; width: %dpx; }\n",$containerw);
    printf("#content {width: %dpx; overflow:hidden; margin:0 0px 10px 0px; padding: 5px 0px 0px 20px;}\n",$contentw);
    printf("#content img { max-width: %dpx;}\n",$contentw);
    printf("#primary, #secondary { float: right; overflow: hidden; width: %dpx; margin: 0 0 4px 0;}\n",$sidebarwidth);
    if ($tbmult == 1) printf("#ttw-top-widget, #ttw-bot-widget {width: %dpx; margin-left: 1px; margin-right: 1px;}\n",$ttwwid);
    else printf("#ttw-top-widget, #ttw-bot-widget {width: %dpx;}\n",$ttwwid);
	    }
	}

    // fix the table crap?
    if (ttw_getopt('ttw_weaver_tables')) {
?>
/* ttw tables */
#content table {border: 2px solid #e7e7e7; text-align: left; margin: auto; margin-bottom: 5px; width: auto;}
#content tr th, #content thead th {color: #888; font-size: medium; font-weight: normal; line-height: normal; padding: 3px;}
#content tr td {border: 1px solid #e7e7e7; padding: 3px;}
#content tr.odd td {background: inherit;}
<?php }

    if (ttw_getopt('ttw_gradient_menu')) {
	printf("#access, #access2 { background-image: url(%s/images/weaver/fade.png);}\n",
	       get_bloginfo('stylesheet_directory'));
	printf("#access ul ul a, #access li:hover > a, #access ul ul :hover > a  { background-image: url(%s/images/weaver/fadeup.png);}\n",
	       get_bloginfo('stylesheet_directory'));
	printf("#access2 ul ul a, #access2 li:hover > a, #access2 ul ul :hover > a  { background-image: url(%s/images/weaver/fadeup.png);}\n",
	       get_bloginfo('stylesheet_directory'));
   }

   if (ttw_getopt('ttw_hide_post_fill')) {
	printf(".entry-utility-prep{display: none;} .meta-prep-author {display:none;} .meta-sep {display: none;}\n");
   }

   if (ttw_getopt('ttw_post_icons')) {
	$leftm = '8';
	if (!ttw_getopt('ttw_hide_post_fill')) $leftm = '0';	// no left margin if not hiding fill in
	printf(".cat-links {background: url(%s/images/icons/category-1.png) no-repeat 1px;padding-top:3px;padding-left:23px;}\n",
	    get_bloginfo('stylesheet_directory'));
	printf(".vcard {background: url(%s/images/icons/author-1.png) no-repeat;padding-top: 2px;padding-left:21px;margin-left:%spx;}\n",
	       	    get_bloginfo('stylesheet_directory'),$leftm);
	printf(".entry-meta {background: url(%s/images/icons/date-1.png) no-repeat 1px;padding-top:1px;padding-left:26px;}\n",
	    get_bloginfo('stylesheet_directory'));
	printf(".comments-link {background: url(%s/images/icons/comment-1.png) no-repeat 1px; padding-left:24px;padding-top:2px;margin-left:%spx;}\n",
	       get_bloginfo('stylesheet_directory'),$leftm);
	printf(".tag-links {background: url(%s/images/icons/tag-1.png) no-repeat 1px;padding-top:2px;padding-left:24px;margin-left:%spx;}\n",
	       get_bloginfo('stylesheet_directory'),$leftm);
	printf(".edit-link{ background: url(%s/images/icons/edit-1.png) no-repeat 1px;padding-top:3px;padding-left:21px;margin-left:%spx;}\n",
	       get_bloginfo('stylesheet_directory'),$leftm);
   }

    /* finally body stuff */
    if (($bgColor = ttw_getopt_newcolor('ttw_body_bgcolor'))) {
		t_css_bgcolor('body',$bgColor);
	}

    if (ttw_getopt('ttw_fadebody_bg')) {
	printf("body {background-image: url(%s/images/gr.png); background-attachment: scroll; background-repeat: repeat-x;}\n",
	       get_bloginfo('stylesheet_directory'));
    }

    if (ttw_getopt('ttw_wrap_shadow')) {
	echo("#wrapper {box-shadow: 0 0 3px 3px rgba(0,0,0,0.25); -webkit-box-shadow: 0 0 3px 3px rgba(0,0,0,0.25); -moz-box-shadow: 0 0 3px 3px rgba(0,0,0,0.25);}\n");
    }
?>
</style> <!-- end of style section -->

<?php
    /* now head options */
    echo(str_replace("\\", "", ttw_getopt('ttw_theme_head_opts')));
    echo(str_replace("\\", "", ttw_getopt('ttw_head_opts')));		/* let the user have the last word! */

    do_action('ttwx_extended_wp_head'); 	/* call extended wp_head stuff */
    do_action('ttwx_super_wp_head');		// future header plugin

    echo("\n<!-- End of TT Weaver options -->\n");

} /* end mytheme_wp_head */
