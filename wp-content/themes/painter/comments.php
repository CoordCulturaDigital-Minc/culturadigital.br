<?php if( !comments_open() ) return false; ?>

<div class="section section_comments">
	<div class="head">
		<h1><?php comments_number( __( 'Leave a Reply!', 'painter' ), __( '1 Comment', 'painter' ), __( '% Comments', 'painter' ) ); ?></h1>
	</div>
	<div class="body">
		<?php
			global $current_user;

			$args = array(
				'title_reply'         => __( 'What do you think?', 'painter' ),
				'comment_notes_after' => '',
				'comment_field'       => '<textarea id="comment" name="comment" rows="5" aria-required="true" placeholder="' . __( 'comment', 'painter' ) . '"></textarea>',
				'logged_in_as'        => '<h2 class="comment_author">' . $current_user->display_name . '</h2>'
			);

			comment_form( $args );
		?>

		<?php if( have_comments() ) : ?>
			<ul class="comments">
				<?php wp_list_comments( array( 'callback' => 'painter_comments' ) ); ?>
			</ul>
		<?php endif; ?>
	</div>

	<div class="foot">
		<div class="pagination">
			<?php paginate_comments_links(); ?>
		</div>
	</div>

	<div class="clear"></div>
</div>
<div class="clear"></div>