<div id="author-<?php the_author_meta('ID');  ?>" <?php post_class('clearfix page'); ?>>

    <div class="entry author-entry clearfix">
    
    	<?php do_action('graphene_author_entry'); ?>
    
    	<?php /* Display the user's gravatar */ ?>
        <?php echo get_avatar(get_the_author_meta('user_email'), 150); ?>
            
        <?php /* Page title, which is the user's nicename */ ?>
        <h2><?php echo ucfirst(get_the_author_meta('display_name')); ?></h2>
                            
        <?php /* Post content */ ?>
        <div class="entry-content clearfix">
        
        	<?php /* Author's details */ ?>
            <h4 class="author-first-heading"><?php _e("Author's details", 'graphene'); ?></h4>
            <p>
				<?php if (get_the_author_meta('user_firstname') != '' || get_the_author_meta('user_lastname') != '') { /* translators: %1$s is the first name, %2$s is the last name */ printf(__('<strong>Name:</strong> %1$s %2$s', 'graphene'), get_the_author_meta('user_firstname'), get_the_author_meta('user_lastname')); echo '<br />';} ?>
                <?php printf(__('<strong>Date registered:</strong> %1$s','graphene'),mysql2date(get_option('date_format'), get_the_author_meta('user_registered'))); ?>
                <?php if (get_the_author_meta('user_url') != '') {echo '<br />';printf('<strong>URL:</strong> %1$s', '<a href="'.get_the_author_meta('user_url').'">'.get_the_author_meta('user_url').'</a>');} ?>
                <?php if (get_the_author_meta('aim') != '') {echo '<br />';printf('<strong>AIM:</strong> %1$s', get_the_author_meta('aim'));} ?>
                <?php if (get_the_author_meta('jabber') != '') {echo '<br />';printf('<strong>Jabber / Google Talk:</strong> %1$s', get_the_author_meta('jabber'));} ?>
                <?php if (get_the_author_meta('yim') != '') {echo '<br />';printf('<strong>Yahoo! IM:</strong> %1$s', get_the_author_meta('yim'));} ?>
			</p>
            <?php do_action('graphene_author_details'); ?>
            
			<?php if (get_the_author_meta('description') != '') : ?>
            <h4><?php _e('Biography', 'graphene'); ?></h4>
			<p><?php the_author_meta('description'); ?></p>
            
            <?php do_action('graphene_author_desc'); ?>
            <?php endif; ?>
         
            
            <?php /* Lists the author's latest posts */ ?>
            <h4><?php _e('Latest posts', 'graphene'); ?></h4>
            <?php 
				// global $post;
				$posts = get_posts(array('numberposts' => 5, 'author' => get_the_author_meta('ID'), 'orderby' => 'date')); ?>
                <ol>
				<?php foreach ($posts as $post) { ?>
                    <li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(esc_attr__('Permalink Link to %s', 'graphene'), the_title_attribute('echo=0')); ?>"><?php if (get_the_title() == '') {_e('(No title)','graphene');} else {the_title();} ?></a> &mdash; <?php echo get_the_date(); ?></li>    
                <?php } ?>
            	</ol>
                <?php do_action('graphene_author_latestposts'); ?>
            
            <?php /* Lists the author's most commented posts */ ?>
            <h4><?php _e('Most commented posts', 'graphene'); ?></h4>
            <?php 
				// global $post;
				$posts = get_posts(array('numberposts' => 5, 'author' => get_the_author_meta('ID'), 'orderby' => 'comment_count')); ?>
                <ol>
				<?php foreach ($posts as $post) { setup_postdata($post); ?>
                	<?php /* List the post only if comment is open and there's comment(s) and the post is not password-protected */ ?>
                	<?php if (comments_open() && empty($post->post_password) && get_comments_number() != 0) : ?>
                    <li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(esc_attr__('Permalink Link to %s', 'graphene'), the_title_attribute('echo=0')); ?>"><?php if (get_the_title() == '') {_e('(No title)','graphene');} else {the_title();} ?></a> &mdash; <?php comments_number(__('Leave comment','graphene'), __('1 comment','graphene'), __("% comments",'graphene')); ?></li>
                    <?php endif; ?>
                <?php } ?>
            	</ol>
                <?php do_action('graphene_author_popularposts'); ?>
        </div>
        
        <?php /* Post footer */ ?>
        <div class="entry-footer clearfix">                        
            <?php 
                /**
                 * Display AddThis social sharing button if enabled.
                 * See the graphene_addthis() function in functions.php
                */ 
            ?>
            <?php graphene_addthis(); ?>
        </div>
    </div>
</div>	