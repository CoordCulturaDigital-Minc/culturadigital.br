<?php
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) {
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {
			?>

			<div class="post oneline">
				<p>This post is password protected. Enter the password to view comments.</p>
			</div>

			<?php
			return;
		}
	}
?>

	<div id="infoblock">
	
		<h2><?php comments_number('Adicionar comentário', 'Um comentário', '% comentários' ); ?></h2>
	
	</div>

<?php if ($comments): ?>

	<?php foreach ($comments as $comment): ?>
	
		<div class="post">
			<p><cite><?php comment_author_link() ?></cite> escreveu:</p>
			<?php comment_text() ?>
			<?php if ($comment->comment_approved == '0') : ?>
			<p><em>Seu comentário está aguardando moderação.</em></p>
			<?php endif; ?>
			<p class="singleline commentfoot">Publicado em <?php the_time('d'); ?> de <?php the_time('F'); ?> de <?php the_time('Y'); ?></p>
		</div>
		
	<?php endforeach; ?>
	
<?php else: ?>

	<?php if ('open' == $post->comment_status): ?>
	
		<div class="post oneline">
			<p>Seja o primeiro a <a href="<?php the_permalink() ?><?php mopr_check_permalink(); ?>postcomment=true">comentar</a>.</p>
		</div>
		
	 <?php else: ?>
		
		<div class="post oneline">
			<p>Desculpe, os comentários foram fechados.</p>
		</div>
		
	<?php endif; ?>

<?php endif; ?>

		<div id="postfoot">
			<p><a href="<?php the_permalink() ?>">Voltar ao artigo</a></p>
		</div>
		
		<div id="comments">
			<p><a href="<?php the_permalink() ?><?php mopr_check_permalink(); ?>postcomment=true">postar</a> comentário.</p>
		</div>
