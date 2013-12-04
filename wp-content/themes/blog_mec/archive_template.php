<?php /* Template name: Archive template */ ?>
<?php get_header() ?>

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

    <h1 class="searchResults">Matérias</h1>

    <?php
      $paged = get_query_var('paged');

      $args = array(
        'paged' => $paged,
        'caller_get_posts' => 1,
        'post_type' => 'post'
      );
    ?>

    <?php query_posts( $args ) ?>

    <?php if( have_posts() ) : ?>
      <!-- inicio POST -->
      <?php while( have_posts() ) : the_post() ?>
        <div id="box_post">
          <h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
        </div>
      <?php endwhile ?>
      <!-- FIM POST -->
    <?php else : ?>
      <p>Nada encontrado!</p>
    <?php endif ?>

    <div id="paginacao">
      <div id="pag_recente">
        <p class="button">
          <?php previous_posts_link('Post mais recente') ?>
        </p>
      </div>
      <div id="pag_antiga">
        <p class="button">
          <?php next_posts_link('Post mais antigo'); ?>
        </p>
      </div>
    </div>
  </div>
<!-- fim  coluna direita -->

<?php get_footer() ?>