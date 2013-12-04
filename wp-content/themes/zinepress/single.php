<?php get_header(); ?>

	<div id="content" class="widecolumn">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">
			<h2><a href="<?php echo get_permalink() ?>" class="single" rel="bookmark" title="Permanent Link: <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			<span class="undertitle">Publicado por <?php the_author(); ?> dia <?php the_time('j') ?> de <?php the_time('F') ?> de <?php the_time('Y') ?></span>

			<div class="entry">
				<?php the_content('<p class="serif">Continue lendo este artigo &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Páginas:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				<?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>

				<p class="postmeta">
					<small>
						Este artigo foi publicado
						<?php /* This is commented, because it requires a little adjusting sometimes.
							You'll need to download this plugin, and follow the instructions:
							http://binarybonsai.com/archives/2004/08/17/time-since-plugin/ */
							/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
						em <?php the_time('j') ?> de <?php the_time('F') ?> de <?php the_time('Y') ?> ás <?php the_time() ?>
						na(s) categoria(s) <?php the_category(', ') ?>.
						Você poderá acompanhar todas as respostas dia RSS feed <?php comments_rss_link('RSS 2.0'); ?>.

						<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Both Comments and Pings are open ?>
							Você pode <a href="#respond">deixar seu comentário</a>, ou <a href="<?php trackback_url(); ?>" rel="trackback"> dar trackback</a>.

						<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Only Pings are Open ?>
							Este artigo foi fechado mas você poderá <a href="<?php trackback_url(); ?> " rel="trackback">dar trackback</a>.

						<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Comments are open, Pings are not ?>
							Você poderá comentar. Pings não são permitidos.

						<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Neither Comments, nor Pings are open ?>
							Comentários e pings estão fechados.

						<?php } edit_post_link('Editar.','',''); ?>

					</small>
				</p>

			</div>
		</div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p>Desculpe mas nada foi encontrado.</p>

<?php endif; ?>

	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
