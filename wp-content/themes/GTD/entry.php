<?php $current_user_id = get_the_author_ID(); ?>

    <li id="prologue-<?php the_ID(); ?>" class="post user_id_<?php the_author_ID( ); ?>">

		<?php echo prologue_get_avatar( $current_user_id, get_the_author_email( ), 36 ); ?>

		<?php prologue_the_title("<h2>","</h2>"); ?>

	    <h4>

		    <?php the_author_posts_link(); ?>

		    <span class="meta">

		        <?php printf( __('%s <em>on</em> %s', 'p2'),  get_the_time(), get_the_time( get_option('date_format') ) ); ?> |

			    <a href="<?php comments_link() ?>"><?php comments_number('0 Comment', '1 Comment', '%  comments'); ?></a>

			    <span class="actions">

			        <a href="<?php the_permalink( ); ?>" class="thepermalink"><?php _e('Permalink', 'p2'); ?></a>

			        <?php

			            if ( function_exists('post_reply_link') )

			                echo post_reply_link(array('before' => ' | ', 'reply_text' => __('Reply', 'p2'), 'add_below' => 'prologue'), get_the_id());

			        ?>

			        <?php if (current_user_can('edit_post', get_the_id())): ?>

			            |  <a href="<?php echo (get_edit_post_link( get_the_id() ))?>" class="post-edit-link" rel="<?php the_ID(); ?>"><?php _e('Edit', 'p2'); ?></a>

			        <?php endif; ?>

			    </span>

			    <br />

			    <?php tags_with_count( '', __( 'Tags:' , 'p2').' ', ', ', ' ' ); ?>
               
		    </span>

	    </h4>

	    <div class="postcontent<?php if (current_user_can( 'edit_post', get_the_id() )) {?> editarea<?php } ?>" id="content-<?php the_ID(); ?>">

	        <?php the_content( __( '(More ...)' , 'p2') ); ?>
            
            <?php
            $attached_file = get_post_meta($post->ID, 'attached_file', $single = true);
			if($attached_file)
			{
				$attached_file_array = explode(',',$attached_file);
			?>
            <p class="attach"> Attachement</p>
            
             <ul class="file_list2"> 
            	<?php
                for($a=0;$a<count($attached_file_array);$a++)
				{
					$att_path = $attached_file_array[$a];
					$att_path_arr = explode('/',$att_path);
					
				?>
                 <li> <a href="<?php echo $attached_file_array[$a];?>" target="_blank"><?php echo $att_path_arr[count($att_path_arr)-1];?></a> </li>
                <?php
				}
				?>
             </ul>
			<?php }?>
	    </div> <!-- // postcontent -->
		<?php /*?> <?php $attached_file = get_post_meta($post->ID, 'attached_file', $single = true);
		if($attached_file)
		{
		?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Attachment : <a href="<?php echo $attached_file;?>" target="_blank">Click here</a>
		<?php }?><?php */?>
        <div class="bottom_of_entry">&nbsp;
          </div>

    <?php 

		    if ( ( is_home() || is_front_page() ) ) $withcomments = true;

		    

		    if ( is_single() )

		        comments_template();

		    else

		        comments_template('/inline-comments.php');

		    

            // Only append comment form to  first post with open comments

            if( ( !isset($form_visible) || !$form_visible ) && !is_single() && 'open' == $post->comment_status ):

                $form_visible = true;

        ?>

        <div style="display:none">

            <div id="respond" style="display:none">

            <?php require dirname( __FILE__ ) . '/comment-form.php'; ?>

            </div> <!-- #respond -->

        </div> <!-- #wp-temp-form-div -->        

<?php

            endif; // if open comment_status

?>

    </li>