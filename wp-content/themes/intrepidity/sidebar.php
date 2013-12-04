		<!-- begin sidebar -->
		<div id="sidebar">
        	<?php if(get_option('tbf1_optin_form') != 'no') : ?>
              <div id="optinbox">
                <div id="optin-container"<?php echo (get_option('tbf1_optin_hide_msg') == 'yes') ? ' class="optin-nomsg"' : ''?>>
                  <div class="containwithin">	
                    
					<?php if(get_option('tbf1_optin_image_file') && get_option('tbf1_optin_img') == 'yes') :?>
                        <img src="<?php echo get_option('tbf1_optin_image_file')?>"<?php echo (get_option('tbf1_optin_img_alignment') && in_array(get_option('tbf1_optin_img_alignment'), array('left','right')))? ' class="align'.get_option('tbf1_optin_img_alignment').'"' : '' ?><?php echo (get_option('tbf1_optin_img_alignment') == 'center') ? ' style="display:block;margin:2px auto"' : ''?> alt=" " />
                    
					<?php elseif(get_option('tbf1_optin_img') != 'no'): //Default: User has not decided about the use of the image ?>
                        <img src="<?php bloginfo('template_url')?>/images/sample-ecover.png" class="alignleft" alt="Sample eBook" />
                    <?php endif;?>
                    
                    <?php echo (get_option('tbf1_optin_text')) ? get_option('tbf1_optin_text') : '<p>To customize or remove this area, please go to your Admin panel and find My Theme -> Optin Form. Modify it at own will!</p>';?>
                    
                    <div class="recover"></div>
                    
                        <?php if(get_option('tbf1_optin_html')) :?>
                            <?php echo get_option('tbf1_optin_html');?>
                        <?php else: //Example optin form ?>
                            <form action="" method="post" name="opt_form">
                                <input type="text" name="opt_name" id="opt_name" value="<?php _e('Name')?>" onfocus="clearDefault(this)" onblur="restoreDefault(this)" class="textfield" />
                                <input type="text" name="opt_email" id="opt_email" value="<?php _e('Primary Email')?>" onfocus="clearDefault(this)" onblur="restoreDefault(this)" class="textfield" />
                                <input type="text" name="opt_data" id="opt_data" value="" class="textfield" />
                                <input type="submit" name="opt_submit" id="opt_submit" value="Instant Access Now!" onclick="return optformValidate(document.forms.opt_form)" />
                            </form>
                        <?php endif;?>
                    </div>
                </div>
              </div>
            <?php endif;?>
			  
			<ul>
				<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar("Sidebar")) : //If no user selected widgets, display below ?>
                <li id="recent-post-default" class="widget">
                    <h2><a href="#" rel="nofollow" class="sidebartitle"><?php _e("Recent Posts"); ?></a></h2>
                    <ul>
                        <?php wp_get_archives('title_li=&type=postbypost&limit=10'); ?>
                    </ul>
                </li>
                
                <li id="categories" class="widget">
                    <h2><a href="#" rel="nofollow" class="sidebartitle"><?php _e("Categories"); ?></a></h2>
                    <ul>
                      <?php wp_list_categories('orderby=name&title_li=&depth=2'); ?>
                    </ul>
                </li>
                <li id="archives" class="widget">
                    <h2><a href="#" rel="nofollow" class="sidebartitle"><?php _e("Archives"); ?></a></h2>
                    <ul>
                      <?php wp_get_archives('type=monthly'); ?>	
                    </ul>
                </li>
                <?php /* If this is the frontpage */ if ( is_home() || is_page() ) { ?>
                    <?php $args = array('title_before'=>'<h2><a class="sidebartitle" rel="nofollow" href="#">', 'title_after'=>'</a></h2>', 'class'=>'widget',); ?>
                    <?php wp_list_bookmarks($args); ?>
                
                    <li id="blogmeta" class="widget"><h2><a class="sidebartitle" rel="nofollow" href="#">Meta</a></h2>
                    <ul>
                        <?php wp_register(); ?>
                        <li><?php wp_loginout(); ?></li>
                        <li><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
                        <li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
                        <li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
                        <?php wp_meta(); ?>
                    </ul>
                    </li>
                <?php } ?>
                
                <?php endif; ?>
                
                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('240x130 Side Banner Space') ) : ?>
                <?php endif; ?>
			</ul>
		</div><!-- end sidebar -->