<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

        if (!empty($post->post_password)) { // if there's a password
            if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
				?>
				
				<p class="nocomments"><?php _e("Este post est&aacute;. Entre com sua senha para ver os coment&aacute;rios."); ?><p>
				
				<?php
				return;
            }
        }

		/* This variable is for alternating comment background */
		$oddcomment = 'alt';
?>

<!-- You can start editing here. -->

<?php if ($comments) : ?>
	<h3 id="comments" style="margin-left:10px;"><?php comments_number('Nenhum Coment&aacute;rio &#187;', '1 Coment&aacute;rio &#187;', '% Coment&aacute;rios &#187;'); ?> para &#8220;<?php the_title(); ?>&#8221;</h3> 

	<ol class="commentlist">
	
	<?php $commentcounter = 0; ?>
	<?php foreach ($comments as $comment) : ?>
		<?php $commentcounter++; ?>
		<li class="<?php echo $oddcomment; /* Style differently if comment author is blog author */ if ($comment->comment_author_email == get_the_author_email()) { echo ' authorcomment'; } ?>" id="comment-<?php comment_ID() ?>">
			<div class="cmtinfo"><em><?php edit_comment_link('editar','',''); ?> em <?php comment_date('d M Y') ?> &agrave;s <?php comment_time() ?></em><small class="commentmetadata"><a href="#comment-<?php comment_ID() ?>" title=""><span class="commentnum"><?php echo $commentcounter; ?></span></a></small><cite><?php comment_author_link() ?></cite></div>
			<?php if ($comment->comment_approved == '0') : ?>
			<em>Seu coment&aacute;rio est&aacute; aguardando ser aprovado.</em><br />
			<?php endif; ?>			
			<?php comment_text() ?>			
		</li>

	<?php /* Changes every other comment to a different class */	
		if ('alt' == $oddcomment) $oddcomment = '';
		else $oddcomment = 'alt';
	?>

	<?php endforeach; /* end for each comment */ ?>

	</ol>

 <?php else : // this is displayed if there are no comments so far ?>

  <?php if ('open' == $post-> comment_status) : ?> 
		<!-- If comments are open, but there are no comments. -->
		
	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Coment&aacute;rios fechados.</p>
		
	    <?php endif; ?>
<?php endif; ?>
<div class="post-content">
<p>
<?php if ($post->ping_status == "open") { ?>
	<span class="trackback"><a href="<?php trackback_url(display); ?>">Trackback URI</a></span> | 
<?php } ?>
<?php if ($post-> comment_status == "open") {?>
	<span class="commentsfeed"><?php comments_rss_link('RSS dos coment&aacute;rios'); ?></span>
<?php }; ?>
</p>
</div>

<?php if ('open' == $post-> comment_status) : ?>
	  <h2 id="respond" style="margin-left:10px;">Avisos</h2>
		<p style="margin-left:10px;">Os itens com asterisco (*) s&atilde;o campos de preenchimento obrigat&oacute;rio.<br />
	Todos devem se identificar atrav&eacute;s do e-mail v&aacute;lido.<br />
	Os e-mails dos usu&aacute;rios n&atilde;o ser&atilde;o divulgados no site.<br />
	Os coment&aacute;rios est&atilde;o sujeitos &agrave; modera&ccedil;&atilde;o.<br /></p>
<h3 id="respond" style="margin-left:10px;">Deixe um coment&aacute;rio </h3>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>Voc&ecirc; deve est&aacute; <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logado</a> para postar um coment&aacute;rio. </p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p>Logado como <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account') ?>">Logout &raquo;</a></p>

<?php else : ?>

<p><input class="textbox" type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
<label for="author"><small>Nome <?php if ($req) _e('*'); ?></small></label>
</p>

<p><input class="textbox" type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
<label for="email"><small>E-mail <?php if ($req) _e('*'); ?></small></label>
</p>

<p><input class="textbox" type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
<label for="url"><small>Site</small></label>
</p>

<?php endif; ?>

<!--<p><small><strong>XHTML:</strong> You can use these tags: <?php echo allowed_tags(); ?></small></p>-->

<p><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></p>

<p><input name="submit" type="submit" id="submit" tabindex="5" value="Comentar" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p>
<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>