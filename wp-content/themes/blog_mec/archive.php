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

    <?php if( is_category() ) : ?>
      <h1 class="searchResults">Posts da categoria "<?php single_cat_title(); ?>"</h1>
    <?php elseif( is_tag() ) : ?>
      <h1 class="searchResults">Posts com a tag "<?php single_tag_title(); ?>"</h1>
    <?php elseif( is_day() ) : ?>
      <h1 class="searchResults">Posts de "<?php the_time('d, m, Y') ?>"</h1>
    <?php elseif( is_month() ) : ?>
      <h1 class="searchResults">Posts de "<?php the_time('F, Y') ?>"</h1>
    <?php elseif( is_year() ) : ?>
      <h1 class="searchResults">Posts de "<?php the_time('Y') ?>"</h1>
    <?php elseif( is_author() ) : ?>
      <h1 class="searchResults">Posts do autor "<?php the_author_link() ?>"</h1>
    <?php endif; ?>

    <?php if( have_posts() ) : ?>
      <!-- inicio POST -->
      <?php while( have_posts() ) : the_post() ?>
        <div id="box_post">
          <h2><?php the_title() ?></h2>
          <h3><?php the_time('d \d\e F \d\e Y') ?></h3>
        
          <?php the_excerpt() ?>
  
          <div id="navegacao_post">
            <div id="abre_artigo">
              <img src="<?php bloginfo('stylesheet_directory') ?>/global/img/ico_continue.png" width="16" height="16" alt="Continue Lendo" />
              <a href="<?php the_permalink() ?>">Leia o artigo completo</a>
            </div>
            <div id="comments">
              <img src="<?php bloginfo('stylesheet_directory') ?>/global/img/ico_comments.png" width="16" height="15" alt="Comentários" />
              <a href="<?php comments_link() ?>">(<?php comments_number(0, 1, '%') ?>) Coment&aacute;rios</a>
            </div>
          </div>
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

<?php get_footer(); ?>