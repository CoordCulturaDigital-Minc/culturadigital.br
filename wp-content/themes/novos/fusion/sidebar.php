<?php /* Fusion/digitalnature */

if(!is_page_template('page-nosidebar.php')):
 if((is_page_template('page-3col.php') || (get_option('fusion_3col')=='yes')) && (!is_page_template('page-2col.php'))): include(TEMPLATEPATH . '/sidebar2.php'); endif; ?>

<!-- sidebar -->
<div id="sidebar">
 <!-- sidebar 1st container -->
 <div id="sidebar-wrap1">
  <!-- sidebar 2nd container -->
  <div id="sidebar-wrap2">
     <ul id="sidelist">
     	<?php if ( is_404() || is_category() || is_day() || is_month() || is_year() || is_search() || is_paged() ) { ?>
        <li class="infotext">
			<?php /* If this is a 404 page */ if (is_404()) { ?>
			<?php /* If this is a category archive */ } elseif (is_category()) { ?>
            <p><?php printf(__('You are currently browsing the archives for the %s category.', 'fusion'), single_cat_title('',false)); ?></p>

			<?php /* If this is a yearly archive */ } elseif (is_day()) { ?>
            <p><?php printf(__('You are currently browsing the archives for %s','fusion'), get_the_time(__('l, F jS, Y','fusion'))); ?></p>

			<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
            <p><?php printf(__('You are currently browsing the archives for %s','fusion'), get_the_time(__('F, Y','fusion'))); ?></p>

			<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
            <p><?php printf(__('You are currently browsing the archives for the year %s','fusion'), get_the_time(__('Y','fusion'))); ?></p>

			<?php /* If this is a monthly archive */ } elseif (is_search()) { ?>
            <p class="error"><?php printf(__('You have searched the archives for %s.','fusion'), '<strong>'.get_search_query().'</strong>'); ?></p>

			<?php /* If this is a monthly archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			  <p><?php _e('You are currently browsing the archives.','fusion'); ?></p>
			<?php } ?>
	 	</li>
        <?php }?>

        <?php 	/* Widgetized sidebar, if you have the plugin installed. */
        if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

        <!-- wp search form -->
        <li>
         <div class="widget">
          <div id="searchtab">
            <div class="inside">
              <form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
                <fieldset>
                <input type="text" name="s" id="searchbox" class="searchfield" value="<?php _e("Search","fusion"); ?>" onfocus="if(this.value == '<?php _e("Search","fusion"); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e("Search","fusion"); ?>';}" />
                 <input type="submit" value="Go" class="searchbutton" />
                </fieldset>
              </form>
            </div>
          </div>
         </div>
        </li>
        <!-- /wp search form -->

        <li>
         <div class="widget">
          <!-- sidebar menu (categories) -->
          <ul class="nav">
            <?php if(get_option('fusion_jquery')=='no')
              echo preg_replace('@\<li([^>]*)>\<a([^>]*)>(.*?)\<\/a>@i', '<li$1><a$2><span>$3</span></a>', wp_list_categories('show_count=0&echo=0&title_li='));
             else
              echo preg_replace('@\<li([^>]*)>\<a([^>]*)>(.*?)\<\/a> \(\<a ([^>]*)([^>]*)>XML\<\/a>\) \((.*?)\)@i', '<li $1><a$2><span>$3 <em>($6)</em></span></a><a class="rss tip" $4></a>', wp_list_categories('show_count=1&echo=0&title_li=&feed=XML'));  ?>

            <?php if (function_exists('xili_language_list')) { xili_language_list(); } ?>

          </ul>
          <!-- /sidebar menu -->
          </div>
        </li>

        <li>
         <div class="widget">
          <ul>
           <?php wp_list_bookmarks('title_before=<h2 class="title">&title_after=</h2>'); ?>
          </ul>
         </div>
        </li>

        <li class="box-wrap" id="box-archives">
          <div class="box">
           <h2 class="title"><?php _e('Archives','fusion'); ?></h2>
           <div class="inside">
            <ul>
             <?php wp_get_archives('type=monthly&show_post_count=1'); ?>
            </ul>
           </div>
          </div>
        </li>

        <li class="box-wrap" id="box-meta">
          <div class="box">
           <h2 class="title"><?php _e('Meta','fusion'); ?></h2>
           <div class="inside">
            <ul>
             <?php wp_register(); ?>
             <li><?php wp_loginout(); ?></li>
             <li><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
             <li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
             <li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
             <?php wp_meta(); ?>
            </ul>
           </div>
          </div>
        </li>
        <?php endif; ?>
     </ul>
  </div>
  <!-- /sidebar 2nd container -->
 </div>
 <!-- /sidebar 1st container -->
</div>
<!-- /sidebar -->

<?php endif;  ?>