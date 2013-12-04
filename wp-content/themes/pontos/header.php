<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<!--[if IE 6]>
    <style type="text/css"> 
    body {
        behavior:url("<?php bloginfo('template_url'); ?>/scripts/csshover2.htc");
    }
    </style>
<![endif]-->

<?php if (is_home()) { ?>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/scripts/jquery-latest.pack.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/scripts/jcarousellite_1.0.1c4.js"></script>
<?php } ?>

<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php $cp_iIcon = get_settings( "cp_favICON" ); 
		if( $cp_iIcon != "" ) { 
		?>
<link rel="icon" href="<?php bloginfo('template_url'); ?>/images/icons/<?php echo $cp_iIcon; ?>" />
<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/images/icons/<?php echo $cp_iIcon; ?>" />
	<?php	} ?>

<style type="text/css">

<?php 
$cp_max = 10;
$cp_categories = get_categories('');
$postcat = get_settings( "ar_categories" );

if( is_array( $postcat ) ) {
	foreach ( $cp_categories as $b ) {
		$cp_max ++;
	}	
}

for( $cp_i = 1; $cp_i <= $cp_max; $cp_i ++ ) {
	$cp_catCol = get_settings( "cp_catColor" );
    $cp_iCol = get_settings( "cp_hexColor_" . $cp_i );
    $cp_tCol = get_settings( "cp_textColor_" . $cp_i );
    $cp_hCol = get_settings( "cp_hoverColor_" . $cp_i );
		 if( ($cp_iCol != "") || ($cp_tCol != "")  ) { ?>
    /* category bar */
    #cat-<?php echo get_settings( "cp_colorCategory_" . $cp_i ); ?> { border-top:8px solid <?php echo $cp_iCol ?>; color:<?php echo $cp_tCol ?>; }
    #cat-<?php echo get_settings( "cp_colorCategory_" . $cp_i ); ?>:hover { background:<?php echo $cp_iCol ?>; color:<?php echo $cp_hCol ?>; }
    /* sidebar */
    #sidebar h3.catt-<?php echo get_settings( "cp_colorCategory_" . $cp_i ); ?>  {background:<?php echo $cp_iCol ?>; color:<?php echo $cp_catCol ?>; }
    #sidebar h3.catt-<?php echo get_settings( "cp_colorCategory_" . $cp_i ); ?> a { color:<?php echo $cp_catCol ?>; }
		
<?php }	} ?>

<?php $style = get_settings ( "cp_styleHead" );
        if ( $style != "wide" ) { $height = 237; }
		else { $height = 395; }
	if (is_home()) { ?>
    
#featured .arthemia-carousel {
    height: <?php echo $height; ?>px;
    }
    <?php } ?>
</style>

<?php if (is_home()) { ?>

<?php 
$style = get_settings ( "cp_styleHead" );
$speed = get_settings ( "cp_ScrollSpeed" );
if ( $speed != "" ) { } else { $speed = 1000; }

if ( $style != "wide" ) { $visible = 3; }
else {  $visible = 5;  }
        
$scroll = get_settings ( "cp_autoScroll" );
if ( $scroll != "No" ) { ?>
<script type="text/javascript">
$(function() {

    $(".arthemia-carousel").jCarouselLite({
        btnNext: ".next",
        btnPrev: ".prev",
        vertical: true,
        hoverPause:true, 
        visible: <?php echo $visible; ?>,
        auto:<?php echo $speed; ?>,
        speed:<?php echo $speed; ?>
             
    });
});
</script>
<?php } else { ?>
<script type="text/javascript">
$(function() {

    $(".arthemia-carousel").jCarouselLite({
        btnNext: ".next",
        btnPrev: ".prev",
        vertical: true,
        visible: <?php echo $visible; ?>,
        circular: false
             
    });
});
</script>
<?php } ?>

<?php } ?>

<?php load_theme_textdomain('arthemia');?>

<?php if (is_singular()) wp_enqueue_script('comment-reply'); wp_head(); ?>

</head>

<body>
<div id="main">

<div id="head" class="clearfloat">
<div id="linksHeader">
	<div class="dia"><?php print __(date('l')).", ".date('j')." de ".__(date('F'))." de ".date('Y'); ?></div> 
	<div class="outrasFuncoes">
	<div class="twitter"><a href="http://twitter.com/CulturaGovBr" target="_blank" title="twitter">Twitter</a></div>
	<div class="rss"><a href="<?php bloginfo('rss2_url'); ?>" title="RSS">RSS</a></div>
	</div>
</div>
<div class="clearfloat">
	<div id="logo" class="left">
	
	<?php $cp_iLogo = get_settings( "cp_logo" ); 
		if( $cp_iLogo != "" ) { 
		?>
	<a href="<?php echo get_option('home'); ?>">
	<img src="<?php bloginfo('template_url'); ?>/images/logo/<?php echo $cp_iLogo; ?>" alt="" height="90px" /></a>
	<?php	} ?>

	</div>

	<div class="right">
	<?php $cp_i = 1; $cp_iAd = get_settings( "cp_adImage_" . $cp_i ); ?>
	
	<?php if(($cp_iAd != "") && ($cp_iAd != "Adsense")) { ?>
	<a href="<?php echo get_settings( "cp_adURL_" . $cp_i ); ?>">
	<img src="<?php bloginfo('template_url'); ?>/images/ads/<?php echo $cp_iAd; ?>" alt="" width="728px" height="90px" /></a>
	<?php } else { ?>

	<?php if( $cp_iAd != "") { ?>
	
	<?php $cp_iAdcode = get_settings( "cp_adAdsenseCode_" . $cp_i ); 
		if( $cp_iAdcode != "" ) { 
		?>
	
	<script type="text/javascript"><!--
google_ad_client = "<?php echo get_settings( "cp_adGoogleID" ); ?>";
google_ad_slot = "<?php echo get_settings( "cp_adAdsenseCode_" . $cp_i ); ?>";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

	<?php	} } ?>
	<?php } ?>

	
	</div>

</div>

</div>

<div id="navbar" class="clearfloat">

<ul id="page-bar" class="left clearfloat">

<li><a href="<?php echo get_option('home'); ?>/"><?php _e('Home','arthemia');?></a></li>

<?php wp_list_pages('sort_column=menu_order&title_li='); ?>

</ul>

<?php include (TEMPLATEPATH . '/searchform.php'); ?>

</div>

<div id="top" class="clearfloat">
	<?php if (is_home() && !is_paged()) { ?>
		<div id="headline">
		<?php
		//Get value from Admin Panel
			$cp_categories = get_categories('hide_empty=0');
			$ar_headline = get_settings( "ar_headline" );
			if( $ar_headline == 0 ) { $ar_headline = $cp_categories[0]->cat_ID; }
			query_posts( 'showposts=1&cat=' . $ar_headline );
		 	?>
		<div class="label"><a href="<?php echo get_category_link($ar_headline);?>"><?php single_cat_title(); ?> &raquo;</a></div>
		<?php while (have_posts()) : the_post(); ?>	
	<div class="clearfloat">
	
 	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_thumb('medium', 'class="left"'); ?></a>
 	
	<div class="title"><a href="<?php the_permalink() ?>" rel="bookmark"><em><?php the_title(); ?></em></a></div>
	<div class="meta"><?php the_time(get_option('date_format')); ?> &#150; <?php the_time(); ?> | <?php comments_popup_link(__('No Comment','arthemia'), __('One Comment','arthemia'), __('% Comments','arthemia'));?></div>	
		<?php the_excerpt() ?>
	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php _e('Read the full story &raquo;','arthemia');?></a>
	</div>
	<?php endwhile; ?>
	</div>
    
    <div id="featured">    
    <?php
		//Get value from Admin Panel
			$cp_categories = get_categories('hide_empty=0');
			$ar_featured = get_settings( "ar_featured" );
			if( $ar_featured == 0 ) { $ar_featured = $cp_categories[0]->cat_ID; }
			$num = get_settings( "cp_numFeatured" );
			if( $num == 0 ) { $num = 10; }
			query_posts( 'showposts=' . $num . '&cat=' . $ar_featured );
		 	?>
	<div class="label"><a href="<?php echo get_category_link($ar_featured);?>"><?php single_cat_title(); ?> &raquo;</a></div>
    
    <div class="arthemia-carousel">
    <ul>
        <?php while (have_posts()) : the_post(); ?>
        <li>
        <div>
        
                <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
                <?php the_thumb('thumbnail', 'width="100" height="65" class="left"'); ?></a>
            <div class="info"><a href="<?php the_permalink() ?>" rel="bookmark" class="title"><?php the_title(); ?></a>
            <div class="meta"><?php the_time(get_option('date_format')); ?> &#150; <?php the_time(); ?> | <?php comments_popup_link(__('No Comment','arthemia'), __('One Comment','arthemia'), __('% Comments','arthemia'));?></div>
            </div>
        </div>
        </li>
        <?php endwhile; ?>
        <?php wp_reset_query(); ?>
    </ul>
    
    </div>
    
    <div style="text-align:center;">
        <img class="prev" src="<?php bloginfo('template_url'); ?>/images/prev.png" style="cursor:pointer;width:17px;height:10px;margin-right:10px;" alt= ""/>
        <img class="next" src="<?php bloginfo('template_url'); ?>/images/next.png" style="cursor:pointer;width:17px;height:10px;" alt= "" />    
    </div>
    
    </div>    
    
 	
</div>
	<?php } else { ?>
	<?php 	//Get value from Admin Panel
			$cp_categories = get_categories('hide_empty=0');
			$ar_headline = get_settings( "ar_headline" );
			if( $ar_headline == 0 ) { $ar_headline = $cp_categories[0]->cat_ID; }
			
            $showheadline1 = get_settings ( "cp_showpostheadline" );
            $showheadline2 = get_settings ( "cp_showarchiveheadline" );
            if ( (is_home() && is_paged()) || (is_search() && $showheadline2 != "no") || (is_archive() && $showheadline2 != "no") || (is_single() && $showheadline1 != "no") ) {
          
            query_posts( 'showposts=1&cat=' . $ar_headline ); ?>
          
	<?php while (have_posts()) : the_post(); ?>	
	<div id="single_head">
	
	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
<?php the_thumb('thumb', 'width="80" height="80" class="left"'); ?></a>
	
	</div>
	<div id="single_desc">
	<div class="label"><a href="<?php echo get_category_link($ar_headline);?>"><?php single_cat_title(); ?> &raquo;</a></div>
	<div class="cleafloat"><div class="title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></div>
	<div class="meta"><?php the_time(get_option('date_format')); ?> &#150; <?php the_time(); ?> | <?php comments_popup_link(__('No Comment','arthemia'), __('One Comment','arthemia'), __('% Comments','arthemia'));?></div>	
	<?php the_excerpt(); ?>
	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php _e('Read the full story &raquo;','arthemia');?></a>
   	</div></div>
    <?php endwhile; ?>
	<?php wp_reset_query(); ?>
<?php } ?>
</div>
<!--</div>-->

<?php } ?>
	<?php $showcatbar1 = get_settings ( "cp_showpostcatbar" );
            $showcatbar2 = get_settings ( "cp_showarchivecatbar" );
		$showcatbar3 = get_settings ( "cp_showindexcatbar" );
	
		  if ( (is_home() && $showcatbar3 != "no") ||  (is_search() && $showcatbar2 != "no") || (is_archive() && $showcatbar2 != "no") || (is_single() && $showcatbar1 != "no") ) { ?>
	<div id="middle" class="clearfloat">

	<?php $postcat = get_settings( "ar_categories" );
		if( $ar_categories == 0 ) { $ar_categories= $cp_categories->cat_ID; }

	if( ! is_array( $postcat ) ) {
		foreach ( $cp_categories as $b ) {
		$postcat[] = $b->cat_ID;
		}	
	}
	
	$postcat = array_slice($postcat, 0, 10);

	foreach ($postcat as $cp_pC ) { ?>
	
	<?php query_posts("showposts=1&cat=$cp_pC"); ?>
	<div id="cat-<?php echo $cp_pC; ?>" class="category" onclick="window.location.href='<?php echo get_category_link($cp_pC);?>';">
		<span class="cat_title"><?php single_cat_title(); ?></span>
		<p><?php echo category_description($cp_pC); ?></p>
	</div>
	<?php } ?>

	<?php wp_reset_query(); ?>
    </div>

	
	<?php } ?>

    

	<div id="page" class="clearfloat">
    <?php if (!is_home()) { ?><div id="inner" class="clearfloat"><?php } ?>
