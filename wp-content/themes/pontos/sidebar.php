<div id="sidebar">

<?php 

$showSpoilers = get_settings ( "cp_catSpoilers" );		

if ( $showSpoilers != "no" ) {	

$cp_categories = get_categories('hide_empty=0');
$postcat = get_settings( "ar_spoilers" );

if( ! is_array( $postcat ) ) {
	foreach ( $cp_categories as $b ) {
		$postcat[] = $b->cat_ID;
	}	
}

$cp_baseURL = get_bloginfo('url');

if ( is_front_page()  ) {
    
$status1 = get_settings( "cp_preventHeadline" );
$status2 = get_settings( "cp_preventLatest" );

if ( $status2 != "No" ) {
global $ar_ID; 

/*foreach ($ar_ID as $tops) {
        echo $tops . ' ' ; } */
}

if ( $status1 != "No" ) {

$ar_headline = get_settings( "ar_headline" );
if( $ar_headline == 0 ) { $ar_headline = $cp_categories[0]->cat_ID; }

$ar_featured = get_settings( "ar_featured" );
if( $ar_featured == 0 ) { $ar_featured = $cp_categories[0]->cat_ID; }

}
}

foreach ($postcat as $cp_pC ) {
	
	query_posts(array(
                'showposts' => 1,
				'cat' => $cp_pC,
				'category__not_in' => array($ar_headline,$ar_featured),
                'post__not_in' => $ar_ID,
				)); ?>
     
     <?php if (have_posts()) : ?>

	<div class="spoiler clearfloat">
	
	<h3 class="catt-<?php echo $cp_pC; ?>"><a href="<?php echo get_category_link( $cp_pC ); ?>"><?php single_cat_title() ?> &raquo;</a></h3>
	
		<?php
		$count = 0;
		while (have_posts()) {
			the_post();
			if( $count == 0 ) { ?>
				<div class="clearfloat">
		  		<h4 class="title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h4>
		 		
				<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_thumb('thumbnail', 'class="left"'); ?></a>
				
				<?php $status = get_settings ( "cp_excerptSpoilers" );		if ( $status != "no" ) { ?>
				<?php the_excerpt() ?>
				<?php } ?>
				</div>
				<div class="right"><a href="<?php echo get_category_link($cp_pC);?>"><?php _e('More articles &raquo;','arthemia');?></a></div>

				<?php } else { ?>
							
				<?php	} ?>		
				<?php	$count ++;	} ?>
	 </div>
     
     <?php endif; ?>
     
<?php } } ?>



<?php $cp_i = 2; $cp_iAd = get_settings( "cp_adImage_" . $cp_i ); ?>
	
	<?php if(($cp_iAd != "") && ($cp_iAd != "Adsense")) { ?>
	<div id="sidebar-ads">
	<a href="<?php echo get_settings( "cp_adURL_" . $cp_i ); ?>">
	<img src="<?php bloginfo('template_url'); ?>/images/ads/<?php echo $cp_iAd; ?>" alt="" width="300px" height="250px" /></a>
	</div>
	<?php } else { ?>

	<?php if( $cp_iAd != "") { ?>
	
	<?php $cp_iAdcode = get_settings( "cp_adAdsenseCode_" . $cp_i ); 
		if( $cp_iAdcode != "" ) { 
		?>
	<div id="sidebar-ads">
	<script type="text/javascript"><!--
google_ad_client = "<?php echo get_settings( "cp_adGoogleID" ); ?>";
google_ad_slot = "<?php echo get_settings( "cp_adAdsenseCode_" . $cp_i ); ?>";
google_ad_width = 300;
google_ad_height = 250;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
	</div>
	<?php	} } ?>
	<?php } ?>



  
<div id="sidebar-top"> 
<?php 	/* Widgetized sidebar, if you have the plugin installed. */ 					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : ?>
<?php endif; ?>
</div>


<div id="sidebar-middle" class="clearfloat"> 
<div id="sidebar-left">
<?php 	/* Widgetized sidebar, if you have the plugin installed. */ 					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(2) ) : ?>
<h3><?php _e('Archive','arthemia');?></h3>
<ul>
<?php wp_get_archives('type=monthly&limit=6'); ?>
</ul>	
<?php endif; ?> 

</div>  

<div id="sidebar-right">
<?php 	/* Widgetized sidebar, if you have the plugin installed. */ 					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(3) ) : ?> 		
<h3><?php _e('Blogroll','arthemia');?></h3>
<ul>
<?php wp_list_bookmarks('title_li=&categorize=0'); ?>
</ul>
<?php endif; ?>

</div> 

</div>

<div id="sidebar-bottom"> 
<?php 	/* Widgetized sidebar, if you have the plugin installed. */ 					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(4) ) : ?>
<h3><?php _e('Tag Cloud','arthemia');?></h3>
<?php wp_tag_cloud(''); ?>		
<?php endif; ?> </div>   


</div>