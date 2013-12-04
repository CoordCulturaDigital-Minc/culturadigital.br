<?php get_header(); ?>

    <div class="page">
        <h2 class="catheader catcenter">
            <?php single_cat_title(); ?>
        </h2>

        <?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
            <div class="page-content">
                <h3><a href="<?php the_permalink() ?>" title="Click to read <?php the_title(); ?>"><?php the_title(); ?></a></h3>
                <div class="meta">
                    <?php _e("Posted by"); ?> <span class="usr-meta"><?php the_author_posts_link(); ?></span> <?php _e("on"); ?> <?php the_time(get_option('date_format')); ?> <?php _e("at"); ?> <?php the_time('g:i a'); ?> <span class="editpost"><?php edit_post_link('Edit'); ?></span>
                </div>
                
                <?php getImage('1'); ?>    
                <?php the_excerpt(); ?>
            </div>
            
            <?php endwhile; ?>
            
            <div class="navigation">
                <div class="alignleft"><?php next_posts_link('Older Entries') ?></div>
                <div class="alignright"><?php previous_posts_link('Newer Entries') ?></div>
            </div>
                
        <?php else : ?>
            <h2 class="catheader"><?php _e("We're sorry - that page was not found (Error 404)")?></h2>
            <p><?php _e('Make sure the URL is correct. Try searching for it.')?></p>
            <?php include('searchform.php') ?>
        <?php endif; ?>
        
    </div>

<?php get_footer(); ?>