<?php
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (__('Por favor, não carregue essa página diretamente. Obrigado!', 'evoeTheme'));

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('Esse post é protegido por senha. Entre com a senha para ver os comentários.', 'evoeTheme'); ?></p>
	<?php
		return;
	}
	
	foreach ($comments as $comment) :
	$comment_type  = get_comment_type();
	if($comment_type != 'comment') {
?>
<div class='pings'>
	<h3><?php _e('Trackback/Pingback:', 'evoeTheme'); ?></h3>
    <ul>
		<?php wp_list_comments('type=pings&callback=mytheme_pings'); ?>
    </ul>
</div>
<?php break;  } endforeach; ?>
<div class="comentar comments">
	<?php if ( comments_open() ) : ?>
    <div id="respond">
	    <?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
	    <p><?php _e('Você precisa estar', 'evoeTheme'); ?> <a href="<?php echo wp_login_url( get_permalink() ); ?>"><?php _e('logado', 'evoeTheme'); ?></a> <?php _e('para postar um comentário.', 'evoeTheme'); ?></p>
	    <?php else : ?>
		
        <div class="respondTit">
            <h3><?php _e('Deixe seu comentário', 'evoeTheme'); ?></h3>
            <a class="avatar" href="http://en.gravatar.com/" target="_blank"><?php _e('colocar avatar', 'evoeTheme'); ?></a>
        </div>
        <div class="authorComment">
            <img class="fotoPerfil" src="<?php bloginfo("template_directory") ?>/global/img/graph/graph_content_fotoPerfil.jpg" alt="" width="80" height="80" />
        </div>
	    <span class="comentario"></span>
	    <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
	        <ul>
	            <li><textarea class="disabled" cols="50" rows="10" name="comment" id="comment"><?php _e('Seu comentário...', 'evoeTheme'); ?></textarea></li>

		    <?php if ( is_user_logged_in() ) : ?>
		    	<li><?php _e('Logado como', 'evoeTheme'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out">Log out &raquo;</a></li>
		    <?php else : ?>

	            <li><input class="nome disabled" name="author" type="text" value="<?php _e('Seu nome', 'evoeTheme'); ?>" id="author" /></li>
	            <li><input class="eMail disabled" name="email" id="email" type="text" value="<?php _e('Seu e-mail (não será divulgado)', 'evoeTheme'); ?>" /></li>
				<li><input class="eMail disabled" name="url" id="url" type="text" value="<?php _e('Seu website (opcional)', 'evoeTheme'); ?>" /></li>

		    <?php endif; ?>
	        </ul>
	        <button type="submit" name="submit" id="submit"><?php _e('Comentar', 'evoeTheme'); ?></button>
            <div id="cancel-comment-reply">
                <small><?php cancel_comment_reply_link( __('Cancel') ) ?></small>
            </div>
			<?php do_action('comment_form', $post->ID); ?>
	        <?php comment_id_fields(); ?>
	    </form>
	    <?php endif; ?>
	</div>
	<?php endif; ?>

	<?php if ( have_comments() ) : ?>
		<h3 class="comentarios"><?php comments_number( __('Nenhum comentário', 'evoeTheme'),__('Comentários (1)', 'evoeTheme'),__('Comentários (%)', 'evoeTheme') );?></h3>
		<ul class="comment-list comentarios">
		<?php wp_list_comments('type=comment&callback=mytheme_comment'); ?>
		</ul>
	<?php else : ?>
		<?php if ( comments_open() ) : ?>
		<?php else : ?>
			<p class="nocomments"><?php _e('A postagem de comentários está fechado pela administração.', 'evoeTheme'); ?></p>
		<?php endif; ?>
	<?php endif; ?>
    <div class="clear"></div>
</div>
