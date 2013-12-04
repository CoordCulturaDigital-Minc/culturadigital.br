<?php get_header(); ?>

	<div id="content">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<div class="post" id="post-<?php the_ID(); ?>">
	
	<span id="map"><a href="<?php echo get_option('home'); ?>/"><?php _e('Home','arthemia'); ?></a> &raquo; <?php the_category(', ') ?></span>
	
	<h2 class="title"><?php the_title(); ?></h2>
	<div id="stats" class="clearfloat"><span class="left"><?php _e('Submitted by','arthemia');?> <?php the_author_posts_link(); ?> <?php _e('on','arthemia');?> <?php the_time(get_option('date_format')); ?> &#150; <?php the_time(); ?></span><span class="right"><a href="#respond"><?php comments_number(__('No Comment','arthemia'), __('One Comment','arthemia'), __('% Comments','arthemia') );?></a></span></div>

	<div class="entry clearfloat">
	    
    <?php $video = get_post_meta($post->ID, 'Video', $single=true); ?>
                      
        <?php if($video !== '') { ?>
        <div style="float:left;margin:0px 10px 10px 0px;">

        <?php $the_video = $video . '"';
        $pattern = '!http://.*?v=(.*?)"!';
        preg_match_all($pattern, $the_video, $matches);
        $video_src = $matches['1'][0]; ?>
        
        <div id="player"><object width="570" height="320">
        <param name="movie" value="http://www.youtube.com/v/<?php echo $video_src; ?>"></param>
        <param name="allowFullScreen" value="true"></param>
        <embed src="http://www.youtube.com/v/<?php echo $video_src; ?>" type="application/x-shockwave-flash" allowfullscreen="true" width="570" height="320">
        </embed>
        </object></div>
        
        <!--<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/mediaplayer/swfobject.js"></script>
 
        <div id="player"><div style="height:320px;width:570px;border:1px solid #ececec;padding:10px">Adobe Flash Plugin is required to see this video. Click <a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" style="text-decoration:underline;">here</a> to download the latest version of Adobe Flash Player Plugin.</div></div>
        <script type="text/javascript">
        var s1 = new SWFObject('<?php bloginfo('template_url'); ?>/mediaplayer/mediaplayer.swf','mpl','570','320','8');
        s1.addParam('allowscriptaccess','always');
        s1.addParam('allowfullscreen','true');
        s1.addVariable('height','320');
        s1.addVariable('width','570');
        s1.addVariable('file','<?php echo $video; ?>');
        s1.addVariable('image','<?php echo get_option('home'); ?>/<?php $values = get_post_custom_values("Image"); echo $values[0]; ?>');
        s1.write('player');
        </script>
        </script> -->
        </div>
        <?php } ?>
   
	<?php the_content(__('Read the rest of this entry &raquo;','arthemia')); ?>

	<?php wp_link_pages(array('before' => __('<p><strong>Pages:</strong>','arthemia'), 'after' => '</p>', 'next_or_number' => 'number')); ?>
	
	</div>


	</div>
	
	<p align="center">
	<?php $cp_i = 3; $cp_iAd = get_settings( "cp_adImage_" . $cp_i ); ?>
	
	<?php if(($cp_iAd != "") && ($cp_iAd != "Adsense")) { ?>
	<a href="<?php echo get_settings( "cp_adURL_" . $cp_i ); ?>">
	<img src="<?php bloginfo('template_url'); ?>/images/ads/<?php echo $cp_iAd; ?>" alt="" width="468px" height="60px" /></a>
	<?php } else { ?>

	<?php if( $cp_iAd != "") { ?>
	
	<?php $cp_iAdcode = get_settings( "cp_adAdsenseCode_" . $cp_i ); 
		if( $cp_iAdcode != "" ) { 
		?>
	
	<script type="text/javascript"><!--
google_ad_client = "<?php echo get_settings( "cp_adGoogleID" ); ?>";
google_ad_slot = "<?php echo get_settings( "cp_adAdsenseCode_" . $cp_i ); ?>";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

	<?php	} } ?>
	<?php } ?>

</p>
	
	<div id="comments">
	<?php comments_template(); ?>
	</div>

	<?php endwhile; else: ?>

	<p><?php _e('Sorry, no posts matched your criteria.','arthemia');?></p>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>