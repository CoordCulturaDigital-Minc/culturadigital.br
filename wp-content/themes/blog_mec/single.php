<?php get_header(); ?>

<!-- inicio coluna direita -->
  <div id="coluna_direita">

    <div id="topo">

      <h1><a href="<?php bloginfo('url') ?>"><img src="<?php bloginfo('stylesheet_directory') ?>/global/img/tit_festival.png" alt="Festival de Arte e Cultura da Rede Federal - 24 e 28 de Novembro de 2010" /></a></h1>
  
      <div id ="menu_principal">
        <ul>
          <?php wp_list_pages('title_li='); ?>
        </ul>
      </div>

    </div>

    <?php if( have_posts() ) : ?>
      <!-- inicio POST -->
      <?php while( have_posts() ) : the_post() ?>
        <div id="box_post">
          <h2><?php the_title() ?></h2>
          <h3><?php the_time('d \d\e F \d\e Y') ?></h3>
        
          <?php the_content() ?>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <?php comments_template('/comments.php', true); ?>
        </div>
      <?php endwhile ?>
      <!-- FIM POST -->
    <?php endif ?>
  </div>
<!-- fim  coluna direita -->

<?php get_footer(); ?>