			<div class="recover"></div>
        </div><!--End left-col-->
              
			  <?php get_sidebar(1); ?>
              
              <div class="recover"></div>
			</div><!-- #container-shoulder -->
        </div><!-- #Container-->
         
 
        <div id="globalnav">
        	<div id="navpocket">
              <ul id="nav"<?php echo (get_option('tbf1_search_header') == "no") ? ' class="nav-wide"' : '';?>>
                
                <?php if(get_option('tbf1_nav_hide_home') != 'yes') : ?>
                <li><a href="<?php echo get_option('home'); ?>" rel="nofollow"><?php _e('Home')?></a></li>
                <?php endif; ?>
                
				<?php wp_list_pages('title_li=&sort_column=menu_order&exclude='.get_option('tbf1_exclude_pages')); ?>
                
                <?php /* Uncomment this if you want to show categories in the top navigation
				<li><a rel="nofollow" href="#"><?php _e("Topics"); ?></a>
                    <ul><?php wp_list_categories('title_li=&depth=4&orderby=name'); ?></ul>
                </li>*/?>
              </ul>
              
                <?php //Search box 
                    if(get_option('tbf1_search_header') == "yes" || (isset($_GET['preview']) && isset($_GET['template']))) : ?>
                    <?php include(TEMPLATEPATH.'/searchform.php'); ?>
                <?php endif; ?>
        	</div>
        </div>
		
		<ul id="socialize-icons">
			<?php if(get_option('tbf1_icon_rss') || isset($_GET['preview'])):?>
            	<li id="icon-rss"><a href="<?php echo (get_option('tbf1_icon_rss')) ? get_option('tbf1_icon_rss') : bloginfo('rss2_url')?>">RSS Feed</a></li>
            <?php endif;?>
			<?php if(get_option('tbf1_icon_facebook') || isset($_GET['preview'])):?>
            	<li id="icon-facebook"><a href="<?php echo (get_option('tbf1_icon_facebook')) ? get_option('tbf1_icon_facebook') : '#'?>" rel="nofollow" target="_blank">facebook</a></li>
            <?php endif;?>
            <?php if(get_option('tbf1_icon_twitter') || isset($_GET['preview'])):?>
				<li id="icon-twitter"><a href="<?php echo (get_option('tbf1_icon_twitter')) ? get_option('tbf1_icon_twitter') : '#'?>" rel="nofollow" target="_blank">twitter</a></li>
            <?php endif;?>
		</ul>
		
      <div id="footer" <?php echo (get_option('tbf1_footer_image_file')) ? 'style="background:url('.get_option('tbf1_footer_image_file'). ') no-repeat"' : ''?>>
		<div class="footer-content">
        	<div class="footer-widget">
                <ul class="footerlinks">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer Left") ) : ?>
                    <li><strong>Footer Left Content</strong><br />To replace this, go to "Widgets" page and add your own widgets to "Footer Left".<br /><br />Suggested widgets are: Categories, Recent Comments, Banners, Ads, Promotional Links etc.</li>
					<?php endif; ?>	
				</ul>
            </div>
        	<div class="footer-widget">
                <ul class="footerlinks">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer Middle") ) : ?>
                    <li><strong>Footer Middle Content</strong><br />To replace this, go to "Widgets" page and add your own widgets "Footer Middle".<br /><br />Suggested widgets are: Categories, Recent Comments, Banners, Ads, Promotional Links etc.</li>
					<?php endif; ?>	
				</ul>
            </div>
        	<div class="footer-widget">
                <ul class="footerlinks">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer Right") ) : ?>
                    <li><strong>Footer Right Content</strong><br />To replace this, go to "Widgets" page and add your own widgets "Footer Right".<br /><br />Suggested widgets are: Categories, Recent Comments, Banners, Ads, Promotional Links etc.</li>
					<?php endif; ?>	
				</ul>
            </div>
       	  <div class="recover"></div>
          </div>
          
          <span id="copyright"><span class="alignleft"><?php _e('Copyright &copy; ')?>
		  	<script type="text/javascript">
			/* <![CDATA[ */
			var startCopyrightYear = <?php echo (get_option('tbf1_copy_year')) ? get_option('tbf1_copy_year') : "''" ?>;
			if(!startCopyrightYear) {
				var d=new Date();
				startCopyrightYear = d.getFullYear();
			}
			printCopyrightYears(startCopyrightYear)
			/* ]]> */
            </script>
            <?php echo bloginfo('site_name')?></span><span id="footer-tag"> | &nbsp; <a href="http://www.topblogformula.com/wordpress-business-themes/intrepidity" target="_blank">intrepidity</a> Theme <?php _e('by')?> <a href="http://www.topblogformula.com/" target="_blank">Top Blog Formula</a> on <a href="http://www.wordpress.org" target="_blank">WordPress</a></span> | &nbsp; 
			<?php if(is_user_logged_in()):?>
				<a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php echo _e('Log Out') ?>"><?php echo _e('Log Out'); ?></a>
			<?php else:?>
				<a href="<?php echo bloginfo('url')?>/wp-login.php"><?php _e('Log In'); ?></a>
			<?php endif;?>
		  </span>

      </div>
            
    </div><!--End shadow-->

</div><!--End bg-->


<?php //Login Bar at the top 
		if(get_option('tbf1_user_login') == "yes") { ?>
		 <div id="login">
    <?php
		global $user_identity, $user_level;
		if (is_user_logged_in()) { ?>
            <ul>
                <li><span style="float:left;"><?php _e('Logged in as:')?> <strong><?php echo $user_identity ?></strong></span></li>
				<li><a href="<?php bloginfo('url'); ?>/wp-admin">Control Panel</a></li>
                <?php if ( $user_level >= 1 ) { ?>
                <li class="dot"><a href="<?php bloginfo('url') ?>/wp-admin/post-new.php">New Post</a></li>
                <li class="dot"><a href="<?php bloginfo('url') ?>/wp-admin/page-new.php">New Page</a></li>
                <li class="dot"><a href="<?php bloginfo('url') ?>/wp-admin/widgets.php">Widgets</a></li>
                <li class="dot"><a href="<?php bloginfo('url') ?>/wp-admin/admin.php?page=tbf-design.php">Theme Setting</a></li>
			<?php } ?>
                <li class="dot"><a href="<?php bloginfo('url') ?>/wp-admin/profile.php">Profile</a></li>
				<li class="dot"><a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log Out') ?>"><?php _e('Log Out'); ?></a></li>
             </ul>
            <?php 
		} else {
			echo '<ul>';
			echo '<li><a href="'; echo bloginfo("url"); echo '/wp-login.php">Log In</a></li>';
			if (get_option('users_can_register')) { ?>
				<li class="dot"><a href="<?php echo site_url('wp-login.php?action=register', 'login') ?>"><?php _e('Register') ?></a> </li>
                
            <?php 
			}
			echo "</ul>";
		} ?> 
    </div>
<?php } ?>

<?php wp_footer(); ?>

</body>
</html>