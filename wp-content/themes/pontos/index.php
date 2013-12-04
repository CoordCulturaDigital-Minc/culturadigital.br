<?php get_header(); ?>
	
	 <?php
		//Get value from Admin Panel
		$cp_categories = get_categories('hide_empty=0');
		
        $status1 = get_settings( "cp_preventHeadline" );
        
        if ( $status1 != "No" ) {
        
        $ar_headline = get_settings( "ar_headline" );
		$ar_featured = get_settings( "ar_featured" );
		
        }
        
        ?>


	<div id="bottom" class="clearfloat">
		
	<div id="bottom-left">

	<?php if(!is_paged()) { ?>	

	<div id="front-list">	
	
	
	<?php query_posts(array(
			'category__not_in' => array($ar_headline,$ar_featured),
			'showposts' => 1,
			)); ?>
	
	<?php while (have_posts()) : the_post(); ?>		
    <?php global $ar_ID; global $post; $ar_ID[] = $post->ID; ?>
     
	<div class="clearfloat">
	<h3 class="cat_title"><?php the_category(', '); ?> &raquo;</h3>
	<span class="title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></span>
	<div class="meta"><?php the_time(get_option('date_format')); ?> &#150; <?php the_time(); ?> | <?php comments_popup_link(__('No Comment','arthemia'), __('One Comment','arthemia'), __('% Comments','arthemia'));?></div>		
	   
	<p><?php the_thumb('thumbnail', 'class="left"'); ?></p>
		
	<?php the_content(__('Read the full story &raquo;','arthemia')); ?>
	</div>

		
      	<?php endwhile; ?>
	<?php wp_reset_query(); ?>

	</div>

	<?php } ?>

	<?php $column = get_settings ( "cp_status_Column" );
		if ( $column != "one" ) { ?>	

	<div id="paged-list">	
	<?php add_filter('post_limits', 'my_post_limit'); ?>
	
	<?php
		global $myOffset;
		$myOffset = 1;
		$temp = $wp_query;
		$wp_query= null;
		$wp_query = new WP_Query();
		$wp_query->query(array(
				'offset' => $myOffset,
				'category__not_in' => array($ar_headline,$ar_featured),
				'paged' => $paged,
				)); ?>

		<?php if (have_posts()) : ?>
		<?php $i = 1; ?>

		<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>	
        <?php global $ar_ID; global $post; $ar_ID[] = $post->ID; ?>
        
	<?php if( $odd = $i%2 ) { echo '<div class="clearfloat">'; } ?>
	
    
    
	<div class="tanbox <?php if( $odd = $i%2 ) { echo 'left'; } else { echo 'right'; } ?>">
		<span class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></span>
		<div class="meta"><?php the_time(get_option('date_format')); ?> &#150; <?php the_time(); ?> | <?php comments_popup_link(__('No Comment','arthemia'), __('One Comment','arthemia'), __('% Comments','arthemia'));?></div>
	
		<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_thumb('thumbnail', 'class="left"'); ?></a>

		<?php $status = get_settings ( "cp_excerptColumn" );		if ( $status != "no" ) { ?>
		<?php the_excerpt() ?>
		<?php } ?>
	</div>
	
	<?php if( $odd = $i%2 ) { } else { echo '</div>'; } ?>
		
      <?php $i++; endwhile; ?>
      

	<?php if( $odd = $i%2 ) { } else { echo '</div>'; } ?>
			
	<div id="navigation">
	<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>
    
	</div>

	
	<?php endif; ?>
	</div>

	<?php } else { ?>

	<div id="paged-list">	
	<?php add_filter('post_limits', 'my_post_limit'); ?>
	
	<?php
		global $myOffset;
		$myOffset = 1;
		$temp = $wp_query;
		$wp_query= null;
		$wp_query = new WP_Query();
		$wp_query->query(array(
				'offset' => $myOffset,
				'category__not_in' => array($ar_headline,$ar_featured),
				'paged' => $paged,
				)); ?>

		<?php if (have_posts()) : ?>

		<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>	
	    <?php global $ar_ID; global $post; $ar_ID[] = $post->ID; ?>
        
		<div class="onecolumn clearfloat">
	
		<span class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></span>
		<div class="meta"><?php the_time(get_option('date_format')); ?> &#150; <?php the_time(); ?> | <?php comments_popup_link(__('No Comment','arthemia'), __('One Comment','arthemia'), __('% Comments','arthemia'));?></div>

		<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_thumb('thumbnail', 'class="left"'); ?></a>
		
		<?php $status = get_settings ( "cp_excerptColumn" );		if ( $status != "no" ) { ?>
		<?php the_excerpt() ?>
		<?php } ?>
		</div>
			
      	<?php endwhile; ?>
			
		<div id="navigation">
		<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?></div>

		<?php endif; ?>

	</div>
	
	<?php } ?>	

	<?php $wp_query = null; $wp_query = $temp;?>
	<?php remove_filter('post_limits', 'my_post_limit'); ?>

	</div>
	<?php get_sidebar(); ?>

	</div>	

<?php get_footer(); ?>
