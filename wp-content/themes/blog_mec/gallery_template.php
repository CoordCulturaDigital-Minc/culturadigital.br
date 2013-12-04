<?php /* Template name: Flickr gallery */ ?>
<?php get_header() ?>

<!-- inicio coluna direita -->
  <div id="coluna_direita" class="gallery">

    <div id="topo">

      <h1><a href="<?php bloginfo('url') ?>"><img src="<?php bloginfo('stylesheet_directory') ?>/global/img/tit_festival.png" alt="Festival de Arte e Cultura da Rede Federal - 24 e 28 de Novembro de 2010" /></a></h1>
  
      <div id ="menu_principal">
        <ul>
          <?php wp_list_pages('title_li='); ?>
        </ul>
      </div>

    </div>

    <!-- inicio POST -->
      <div id="box_post">
        <h2><?php the_title() ?></h2>

        <?php 
          if( file_exists(ABSPATH . WPINC . '/feed.php') ) {
            include_once(ABSPATH . WPINC . '/feed.php');
          }

          $flickr_rss_url = 'http://api.flickr.com/services/feeds/photos_public.gne?id=52837386@N03&lang=pt-br&format=rss_200';
          $items = 50;
	
          $rss = fetch_feed( $flickr_rss_url );

          if( is_array( $rss->get_items() ) ) {
            $items = $rss->get_item_quantity($items); 
            $items = $rss->get_items(0, $items);

            while( list( $key, $photo ) = each( $items ) ) {
              preg_match_all("/<IMG.+?SRC=[\"']([^\"']+)/si",$photo->get_description(),$sub,PREG_SET_ORDER);
              preg_match_all("/.*/",$photo->get_enclosure()->get_link(),$sub_url, PREG_SET_ORDER);
              $photo_url = $sub_url[0][0];
              $photo_url_s = str_replace( "_m.jpg", "_s.jpg", $sub[0][1] );
              $out .= "<li><a href='{$photo_url}'><img alt='".wp_specialchars( $photo->get_title(), true )."' title='".wp_specialchars( $photo->get_title(), true )."' src='$photo_url_s' /></a></li>";
            }
          }
	?>

       <!-- Start of Flickr Badge -->
       <?php the_content(); ?>
       <ul class="gallery">
         <?php echo $out ?>
       </ul>
       <!-- End of Flickr Badge -->
    </div>
    <!-- FIM POST -->

  </div>
<!-- fim  coluna direita -->

<?php get_footer() ?>