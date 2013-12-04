<?php get_header();?>

<div id="content">
  <div id="content-main">
  <!-- POSTS NORMAIS -->
  <?php if ($posts) {
				$AsideId = get_settings('blog_asideid');
				function ml_hack($str)
				{
					return preg_replace('|</ul>\s*<ul class="asides">|', '', $str);
				}
				ob_start('ml_hack');
				foreach($posts as $post)
				{
					start_wp();
				?>
  <?php if ( in_category($AsideId) && !is_single() ) : ?>
  <ul class="asides">
    <li id="p<?php the_ID(); ?>"> <?php echo wptexturize($post->post_content); ?> <br/>
      <p class="postmetadata">
        <?php comments_popup_link('(0)', '(1)','(%)')?>
        | <a href="<?php the_permalink(); ?>" title="Permalink: <?php echo wptexturize(strip_tags(stripslashes($post->post_title), '')); ?>" rel="bookmark">#</a>
        <?php edit_post_link('(edit)'); ?>
      </p>
    </li>
  </ul>
  <?php else: // If it's a regular post or a permalink page ?>
  <div class="post" id="post-<?php the_ID(); ?>">
    <div class="posttitle">
      <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>" style="color:0033666;">
        <?php the_title(); ?>
        </a></h2>
      <p class="post-info">
        <?php the_time('d') ?>/<?php the_time('m') ?>/<?php the_time('Y') ?> - postado por <?php the_author_posts_link() ?>
        <?php edit_post_link('Edit', '', ' | '); ?>
      </p>
    </div>
    <div class="entry">
       <?php 
		ob_start();
		the_content("Leia mais >>");
		$conteudo = ob_get_contents();
		ob_end_clean();
	  	
		preg_match("/<img src=\"(.*)\" alt=\"(.*)\" \/>/", $conteudo, $imagem);
		$conteudo = preg_replace("/<img src=\"(.*)\" alt=\"(.*)\" \/>/", "",$conteudo);
		
		$img = "";
		if(!empty($imagem[0]))
			$img = substr_replace($imagem[0], " width=\"128px\"", 5, 0);
		
		ob_start();
		the_permalink();
		$link = ob_get_contents();
		ob_end_clean();
		
		ob_start();
		_e('Link permanente para');
		$e = ob_get_contents();
		ob_end_clean();
		
		ob_start();
		the_title();
		$titulo = ob_get_contents();
		ob_end_clean();
		
		print "<span class=\"imgdest\">".$img."</span>".$conteudo." <br class=\"clear\" />";
		
	?>
      <?php wp_link_pages(); ?>
    </div>
    <p class="postmetadata">Categoria(s):
      <?php the_category(', ') ?>
      |
      <?php comments_popup_link('Nenhum Coment&aacute;rio &#187;', '1 Coment&aacute;rio &#187;', '% Coment&aacute;rios &#187;'); ?>
    </p>
    <?php comments_template(); ?>
  </div>
  <?php endif; // end if in category ?>
  <?php
				}
			}
			else
			{ ?>
  <h2 class="center">N&atilde;o encontrado </h2>
  <p class="center">Desculpe, mas o que voc&ecirc; estava procurando n&atilde;o est&aacute; aqui.</p>
  <?php }
		?>
  <?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>
  <!-- <p align="center"><?php posts_nav_link(' - ','&#171; Newer Posts','Older Posts &#187;') ?></p> -->
</div>
<!-- end id:content-main -->
<?php get_sidebar();?>
<?php get_footer(); ?>
