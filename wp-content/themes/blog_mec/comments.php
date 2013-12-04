<?php
if( !empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) ) {
  die ('Please do not load this page directly. Thanks!');
}

if( post_password_required() ) {
  echo '<p class="nocomments">Esse post é protegido por senha. Entre com a senha para ver os comentários.</p>';

  return;
}
?>

<div id="comments" class="comentar comments">
  <?php if ( have_comments() ) : ?>
    <h3><img src="<?php bloginfo('stylesheet_directory') ?>/global/img/ico_comments.png" width="16" height="15" alt="Comentários" /><?php comments_number( 'Nenhum comentário','(1) Comentário','(%) Comentários' );?></h3>

    <?php wp_list_comments('type=comment&callback=mytheme_comment'); ?>
  <?php else : ?>
    <p class="nocomments">Nenhum comentário!</p>
  <?php endif; ?>

  <?php if( comments_open() ) : ?>
    <div id="respond" class="infoComment">
      <?php if( get_option('comment_registration') && !is_user_logged_in() ) : ?>
        <p>Você precisa estar <a href="<?php echo wp_login_url( get_permalink() ); ?>">logado</a> para postar um comentário.</p>
      <?php else : ?>
		
      <h3>Comentar</h3>

      <?php if( !empty($user_ID) ) : ?>
        <div class="avatar">
          <?php global $bp; bp_loggedin_user_avatar( 'type=thumb&width=48&height=48' ); ?>
        </div>
      <?php else : ?>
        <div class="avatar">
          <img src="<?php bloginfo('stylesheet_directory') ?>/global/img/imageAvatarAnonimo.jpg" width="48" height="48" alt="Imagem de exibição do usuário XPTO" />
        </div>
      <?php endif ?>

      <div class="userComment">
        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
          <ul>
          <?php if( !empty($user_ID) ) : ?>
            <li><label><span>Mensagem:</span><textarea cols="50" rows="10" name="comment" id="mensagem">Seu comentário...</textarea></label></li>
          <?php else : ?>
            <li><label><span>Nome:</span><input name="author" id="name" type="text" value="Seu nome" id="author" /></label></li>
            <li><label><span>E-mail:</span><input name="email" id="email" type="text" value="Seu e-mail (não será divulgado)" /></label></li>
            <li><label><span>Mensagem:</span><textarea cols="50" rows="10" name="comment" id="mensagem">Seu comentário...</textarea></label></li>
          <?php endif; ?>
            <li><label><input type="submit" name="submit" value="Comentar" id="enviar" /></label></li>
          </ul>

          <?php do_action('comment_form', $post->ID); ?>
          <?php comment_id_fields(); ?>
        </form>
      </div>
    <?php endif ?>
    </div><!-- infoComment -->
  <?php endif; ?>

</div>
