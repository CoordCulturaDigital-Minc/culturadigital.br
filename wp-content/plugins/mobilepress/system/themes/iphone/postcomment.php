			<div id="infoblock">
			
				<h2>Comentar</h2>
			
			</div>
			
			<?php if (comments_open()): ?>
			
				<?php if (get_option('comment_registration') && !$user_ID): ?>
				
					<div class="post">
						<p>Você precisa estar <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logado</a> para publicar um comentário.</p>
					</div>
				
				<?php else: ?>
				
					<div class="post">
					
						<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">
						
							<?php if ($user_ID): ?>
							
							<p>Logado como <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out">Log out</a></p>
							
							<?php else: ?>
							
							<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="14" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
							<label for="author"><small>Nome <?php if ($req) echo "(obrigatório)"; ?></small></label></p>
							
							<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="14" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
							<label for="email"><small>E-mail (não será divulgado) <?php if ($req) echo "(Obrigatório)"; ?></small></label></p>
							
							<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="14" tabindex="3" />
							<label for="url"><small>Website</small></label></p>
							
							<?php endif; ?>
							
						<p><textarea name="comment" id="comment" cols="40" rows="10" tabindex="4"></textarea></p>
						<p>
							<input name="submit" type="submit" id="submit" tabindex="5" value="Comentar" />
							<input type="hidden" name="comment_post_ID" value="<?php CommentID(); ?>" />
						</p>
						<?php do_action('comment_form', $post->ID); ?>
						
						</form>
						
					</div>

					<?php endif; ?>
			
			<?php else: ?>
			
				<div class="post">
					<p>Desculpe, os comentários para este artigo foram fechados.</p>
				</div>
				
			<?php endif; ?>