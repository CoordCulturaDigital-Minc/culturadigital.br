<?php
/*
Plugin Name: Widget: Blog Content
Plugin URI: http://xemele.cultura.gov.br/
Description: Show the content of the current page
Version: 0.1
Author: Equipe Web MinC
Author URI: http://xemele.cultura.gov.br/
*/

class widget_blog_content
{
  // ATRIBUTOS ////////////////////////////////////////////////////////////////////////////////////
  
  // METODOS //////////////////////////////////////////////////////////////////////////////////////
  /************************************************************************************************
    
  ************************************************************************************************/
  function display_blog_content($args)
  {
    // Extraindo as variáveis dos widgets
    extract($args);
    
    // Posts
    ?>
  
    <?php print $before_widget; ?>
    <?php print $before_title . 'Posts' . $after_title; ?> 
    
    <?php if(is_page()) : ?>
      <?php if(have_posts()) : the_post(); ?>
        <a href="javascript:window.history.go(-1)" class="bold">&laquo; voltar</a>
		
		<?php edit_post_link('<div class="editarArtigo">Editar</div>'); ?>
		
        <p class="infoPost"><?php the_time('d/m/Y - H\hi'); ?></p>
        <h1 class="titulo"><?php the_title(); ?></h1>
        <?php the_content(); ?>
      
      <?php else : ?>
        <a href="javascript:window.history.go(-1)" class="bold">&laquo; voltar</a>
        <h1 class="titulo">404</h1>
        <h3 class="chamada">Não encontramos o que você procura.</h3>
      <?php endif; ?>
    
    <?php elseif(is_single()) : ?>
      <?php if(have_posts()) : the_post(); ?>
        <a href="javascript:window.history.go(-1)" class="bold">&laquo; voltar</a>
		<?php edit_post_link('<div class="editarArtigo">Editar</div>'); ?>
        <p class="infoPost">/ <?php the_category(' '); ?> <?php the_time('d/m/Y - H\hi'); ?></p>
        <h1 class="titulo"><?php the_title(); ?></h1>
        <h3 class="chamada"><?php if(!empty($post->excerpt)) the_excerpt(); ?></h3>
		<p class="publicadoPor">publicado por <span class="nomeAutor"><?php the_author_posts_link(); ?></span></p>
        <?php the_content(); ?>
		
		<div class="singleComentario">
		<?php comments_template(); ?>
      	</div>
	  
      <?php else : ?>
        <a href="javascript:window.history.go(-1)" class="bold">&laquo; voltar</a>
        <h1 class="titulo">404</h1>
        <h3 class="chamada">Não encontramos o que você procura.</h3>
      <?php endif; ?>
      
    <?php else : ?>
      <?php if(have_posts()) : ?>
        <?php if(is_category()) : ?>
        <h1 class="titulo">Categoria &raquo; <span class="tituloAssunto"><?php single_cat_title(); ?></span></h1>
        <p class="descricaoCategory">Confira abaixo as últimas publicações.</p>
        
        <?php elseif(is_tag()) : ?>
        <h1 class="titulo">Tag &raquo; <span class="tituloAssunto"><?php single_tag_title(); ?></span></h1>
        <p class="descricaoCategory">Confira abaixo as últimas publicações.</p>
        
        <?php elseif(is_day()) : ?>
        <h1 class="titulo">Arquivos do dia &raquo; <span class="tituloAssunto"><?php print get_the_time('d, F Y'); ?></span></h1>
        <p class="descricaoCategory">Confira abaixo as últimas publicações.</p>
        
        <?php elseif(is_month()) : ?>
        <h1 class="titulo">Arquivos do mês &raquo; <span class="tituloAssunto"><?php print get_the_time('F, Y'); ?></span></h1>
        <p class="descricaoCategory">Confira abaixo as últimas publicações.</p>
        
        <?php elseif(is_year()) : ?>
        <h1 class="titulo">Arquivos do ano &raquo; <span class="tituloAssunto"><?php print get_the_time('Y'); ?></span></h1>
        <p class="descricaoCategory">Confira abaixo as últimas publicações.</p>
        
        <?php elseif(is_author()) : ?>
        <?php $current_author = get_userdata(intval($author)); ?>
        <h1 class="titulo">Arquivos do autor &raquo; <span class="tituloAssunto"><?php print $current_author->user_nicename; ?></span></h1>
        <p class="descricaoCategory">Confira abaixo as últimas publicações.</p>
		<p>Descrição do autor: </p>
        
        <?php elseif(is_search()) : ?>
        <h1 class="titulo">Resultados de <span class="tituloAssunto">&quot;<?php the_search_query(); ?>&quot;</span></h1>
        <p class="descricaoCategory">Confira abaixo as últimas publicações.</p>
        
        <?php endif; ?>
        
        <?php while(have_posts()) : the_post(); ?>
          <div class="post">
		  <?php edit_post_link('<div class="editarArtigo">Editar</div>'); ?>
            <?php the_thumb('thumbnail', 'align="left"'); ?>
            <p><strong>Publicado em</strong> <?php the_time('d \d\e F \d\e Y'); ?></p>
            <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
            <p><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_excerpt(); ?></a></p>
          </div>
		  
        <?php endwhile; ?>
        
		 <?php if(function_exists('wp_pagenavi')) wp_pagenavi(); ?>
		
      <?php else : ?>
        <a href="javascript:window.history.go(-1)" class="bold">&laquo; voltar</a>
        <h1 class="titulo">404</h1>
        <h3 class="chamada">Não encontramos o que você procura.</h3>
      <?php endif; ?>
    <?php endif; ?>
    
    <?php print $after_widget; ?>
    
    <?php
  }
  
  // CONSTRUTOR ///////////////////////////////////////////////////////////////////////////////////
  /************************************************************************************************
    
  ************************************************************************************************/
  function widget_blog_content()
  {
    // Registrando o widget
    register_sidebar_widget('Blog Content', array(&$this, 'display_blog_content'));
  }
  
  // DESTRUTOR ////////////////////////////////////////////////////////////////////////////////////
  
}

$widget_blog_content = new widget_blog_content();

?>
