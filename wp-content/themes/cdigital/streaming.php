<?php
/*
* Template Name: Streaming
*
* streaming.php - This file have the html template
*
* Copyright (C) 2010  Ministério da Cultura Brasileira
* Copyright (C) 2010  Marcos Maia Lopes <marcosmlopes01@gmail.com>
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head>
        <title>Cultura Digital</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   		<?php do_action( 'bp_head' ) ?>
   		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
   		<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/global/streaming/css/streaming.css" type="text/css" media="screen" />
        <link rel="icon" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" type="image/x-icon" />
        <?php if ( function_exists( 'bp_sitewide_activity_feed_link' ) ) : ?>
			<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> | <?php _e('Site Wide Activity RSS Feed', 'buddypress' ) ?>" href="<?php bp_sitewide_activity_feed_link() ?>" />
		<?php endif; ?>
		<?php if ( function_exists( 'bp_member_activity_feed_link' ) && bp_is_member() ) : ?>
			<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> | <?php bp_displayed_user_fullname() ?> | <?php _e( 'Activity RSS Feed', 'buddypress' ) ?>" href="<?php bp_member_activity_feed_link() ?>" />
		<?php endif; ?>
		<?php if ( function_exists( 'bp_group_activity_feed_link' ) && bp_is_group() ) : ?>
			<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> | <?php bp_current_group_name() ?> | <?php _e( 'Group Activity RSS Feed', 'buddypress' ) ?>" href="<?php bp_group_activity_feed_link() ?>" />
		<?php endif; ?>
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> <?php _e( 'Blog Posts RSS Feed', 'buddypress' ) ?>" href="<?php bloginfo('rss2_url'); ?>" />
		<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> <?php _e( 'Blog Posts Atom Feed', 'buddypress' ) ?>" href="<?php bloginfo('atom_url'); ?>" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <!--[if IE 7]>
			<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/global/css/ie7.css" />
        <![endif]-->
        <?php
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-tabs', 'jquery');
			wp_enqueue_script('', get_bloginfo('stylesheet_directory').'/global/js/jquery.tooltip.min.js', 'jquery');
			wp_enqueue_script('functions', get_bloginfo('template_directory').'/global/js/functions.js', 'jquery');
			wp_enqueue_script('template', get_bloginfo('template_directory').'/global/streaming/js/jquery.template.js', 'jquery');
			wp_enqueue_script('prettyDate', get_bloginfo('template_directory').'/global/streaming/js/pretty.js', 'jquery');
			wp_enqueue_script('streaming', get_bloginfo('template_directory').'/global/streaming/js/streaming.js', 'jquery');
			wp_enqueue_script('flowplayer', get_bloginfo('template_directory').'/global/streaming/flowplayer/flowplayer-3.2.4.min.js', 'jquery');
			if ( is_singular() and comments_open() and (get_option('thread_comments') == 1) or is_page() and comments_open() and (get_option('thread_comments') == 1) ) wp_enqueue_script( 'comment-reply' );
			wp_head();
		?>
    </head>
    
    <body <?php body_class() ?>>
        <div id="general">
            <div id="header">
                <div class="middle">
                	<div class="tit">
                    	<?php if(is_single() || is_page()) : ?>
                        <h2 class="title">
                        	<a href="<?php echo site_url() ?>" title="<?php bp_site_name() ?>"><?php bp_site_name() ?></a>
                        </h2>
                        <?php else : ?>
                        <h1 class="title">
                        	<a href="<?php echo site_url() ?>" title="<?php bp_site_name() ?>"><?php bp_site_name() ?></a>
                        </h1>
                        <?php endif; ?>
                        <a href="#content" class="displayNone">Skip to content</a>
					</div>
                    
			        <div class="login">
			            <div class="outer">
                            <form action="<?php echo bp_search_form_action() ?>" method="post" id="search-form">
                                <label for="search-terms">Pesquisar:</label>
                                <input type="text" id="search-terms" class="inputDefault" name="search-terms" value="" />
                                <?php echo bp_search_form_type_select() ?>
                                <input type="submit" name="search-submit" id="search-submit" class="submitDefault" value="buscar" />
                                <?php wp_nonce_field( 'bp_search_form' ) ?>
                            </form>
					        <div class="clear"></div>
					    </div>
			        </div>
				</div>		
			
			    <div class="menu">
		            <div class="middle">
		                <ul class="nav">
               				<li<?php if ( bp_is_page( 'home' ) && !is_page() ) : ?> class="current_page_item"<?php endif; ?>>
                				<a href="<?php echo site_url() ?>" title="<?php _e( 'Home', 'buddypress' ) ?>"><?php _e( 'Home', 'buddypress' ) ?></a>
                            </li>
            				<?php wp_list_pages( 'title_li=&exclude=1896,1919,1921' ); ?>
                            <li><a href="<?php bloginfo('url'); ?>/blog/2009/09/26/baixe-o-livro-culturadigital-br/">Download do livro</a></li>
                            <li><a href="<?php bloginfo('url'); ?>/blog/category/labblog/">Notícias</a></li>
							<?php do_action( 'bp_nav_items' ); ?>
		                </ul>
                        <ul class="bpNav">
                            <li<?php if ( bp_is_page( BP_MEMBERS_SLUG ) || bp_is_member() ) : ?> class="selected"<?php endif; ?>>
                                <a href="<?php echo site_url() ?>/<?php echo BP_MEMBERS_SLUG ?>/" title="<?php _e( 'Members', 'buddypress' ) ?>"><?php _e( 'Members', 'buddypress' ) ?></a>
                            </li>
                        
							<?php if ( bp_is_active( 'activity' ) ) : ?>
                                <li<?php if ( bp_is_page( BP_ACTIVITY_SLUG ) ) : ?> class="selected"<?php endif; ?>>
                                    <a href="<?php echo site_url() ?>/<?php echo BP_ACTIVITY_SLUG ?>/" title="<?php _e( 'Activity', 'buddypress' ) ?>">Fluxo de atividades</a>
                                </li>
                            <?php endif; ?>
                            
							<?php if ( bp_is_active( 'groups' ) ) : ?>
                                <li<?php if ( bp_is_page( BP_GROUPS_SLUG ) || bp_is_group() ) : ?> class="selected"<?php endif; ?>>
                                    <a href="<?php echo site_url() ?>/<?php echo BP_GROUPS_SLUG ?>/" title="<?php _e( 'Groups', 'buddypress' ) ?>"><?php _e( 'Groups', 'buddypress' ) ?></a>
                                </li>
            
                                <?php if ( bp_is_active( 'forums' ) && bp_is_active( 'groups' ) && ( function_exists( 'bp_forums_is_installed_correctly' ) && !(int) bp_get_option( 'bp-disable-forum-directory' ) ) && bp_forums_is_installed_correctly() ) : ?>
                                    <li<?php if ( bp_is_page( BP_FORUMS_SLUG ) ) : ?> class="selected"<?php endif; ?>>
                                        <a href="<?php echo site_url() ?>/<?php echo BP_FORUMS_SLUG ?>/" title="<?php _e( 'Forums', 'buddypress' ) ?>"><?php _e( 'Forums', 'buddypress' ) ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                            
							<?php if ( bp_is_active( 'blogs' ) && bp_core_is_multisite() ) : ?>
                                <li<?php if ( bp_is_page( BP_BLOGS_SLUG ) ) : ?> class="selected"<?php endif; ?>>
                                    <a href="<?php echo site_url() ?>/<?php echo BP_BLOGS_SLUG ?>/" title="<?php _e( 'Blogs', 'buddypress' ) ?>"><?php _e( 'Blogs', 'buddypress' ) ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
		            </div>
			    </div>		
            </div>

    <div id="content" class="clear">
      <div class="middle">
	<div class="streaming">
	  <?php if( have_posts() ) : while( have_posts() ) : the_post() ?>
          <?php
	    $flash_file = get_post_custom_values('flash_file');
	    $audio = get_post_custom_values('audio_url');
            $html5 = get_post_custom_values('html5_url');
            $flash = get_post_custom_values('flash_url');
	  ?>
	  <div class="head">
	    <h2>Ao vivo - <?php the_title() ?></h2>
	      <p>Selecione o Streaming:
              <a href="<?php echo get_permalink(3166); ?>" <?php if($post->ID != 3167) : ?>class="active"<?php endif; ?>>Seminário Internacional</a>
	      <a href="<?php echo get_permalink(3167); ?>" <?php if($post->ID == 3167) : ?>class="active"<?php endif; ?>>Experiências de Cultura Digital</a></p>
	  </div>

	  <div class="media">
	    <?php $mode = $_GET['mode']; ?>
            <?php if($mode == '') $mode = 'flash' ?>
            <?php if($mode == 'audio' && $audio != '') : ?>
	    <div class="video"><object id="fmp256" type="application/x-shockwave-flash" data="http://memelab.com.br/radio/minicaster.swf" width="620" height="70"><param name="movie" value="http://memelab.com.br/radio/minicaster.swf" /><param name="wmode" value="transparent" /><div class="stirfry"><h4>Minicaster Radio Playhead</h4><p>To listen you must <a href="http://www.macromedia.com/go/getflashplayer/" title="Click here to install the Flash browser plugin from Macromedia">install Flash Player</a>. Visit <a href="http://www.draftlight.net/dnex/mp3player/minicaster/" title="Draftlight Networks">Draftlight Networks</a> for more info.</p></div></object></div>
	    
            <?php elseif($mode == 'html5' && $html5 != '') : ?>
	    <div id="videoplayer" class="video"><video width="620" height="385" controls autobuffer src="<?php echo $html5[0] ?>"'></video></div>
	    <?php else : ?>
	    <div class="video"><a href="http://e1h13.simplecdn.net/flowplayer/flowplayer.flv" style="display:block;width:620px;height:385px" id="videoplayer"></a></div>
	    <script type="text/javascript">
              $f("videoplayer", "<?php bloginfo('stylesheet_directory') ?>/global/streaming/flowplayer/flowplayer-3.2.5.swf", {
	        clip: {
		  url: '<?php echo $flash_file[0] ?>',
		  live: true,
		  // configure clip to use influxis as our provider, it uses our rtmp plugin
		  provider: 'influxis'
	        },

	        // streaming plugins are configured under the plugins node
	        plugins: {
		  // here is our rtpm plugin configuration
		  influxis: {
		    url: '<?php bloginfo('stylesheet_directory') ?>/global/streaming/flowplayer/flowplayer.rtmp-3.2.3.swf',
		    // netConnectionUrl defines where the streams are found
		    netConnectionUrl: '<?php echo $flash[0] ?>'
		  }
   	        }
              });
	    </script>
	    <?php endif; ?>

	    <div class="video-meta">
	      <p>Visualizar em:</p>
	      <ul>
                <?php if($flash != '') : ?>
		  <li class="pt <?php if($mode == 'flash') echo 'active' ?>"><a href="<?php the_permalink() ?>">FLASH</a></li>
                <?php endif; ?>
                <?php if($html5 != '') : ?>
		  <li class="en <?php if($mode == 'html5') echo 'active' ?>"><a href="<?php the_permalink() ?>?mode=html5">HTML5</a></li>
                <?php endif; ?>
                <?php if($audio != '') : ?>
		  <li class="audio <?php if($mode == 'audio') echo 'active' ?>"><a href="<?php the_permalink() ?>?mode=audio">Apenas áudio</a></li>
                <?php endif; ?>
                <?php if($audio != '' || $flash != '' || $html5 != '') : ?>
		  <li class="embbed"><a href="#">Incorporar</a></li>
                <?php endif; ?>
	      </ul>
	    </div>

	    <div class="embbed">
	      <textarea></textarea>
	    </div>

            <?php if(get_the_content() != '') : ?>
            <div class="agenda">
              <h3>Programação do dia</h3>

              <div class="postContent">
                <?php the_content() ?>
              </div>

              <div class="bottom">
                <a href="http://culturadigital.br/forum2010/programacao/" class="more">Ver programação completa &raquo;</a>
              </div>
            </div>
            <?php endif; ?>

	    <div class="flickr loading">
	      <h3>Galeria de fotos</h3>

	      <div class="carousel">
		<script id="flickr" type="text/x-jquery-tmpl">
		  <li>
		    <a href="http://www.flickr.com/photos/${user}/${id}"
		    class="thumbnail" title="${title}">
		      <img
		      src="http://farm${farm}.static.flickr.com/${server}/${id}_${secret}_t.jpg"
		      alt="${title}" width="100" height="75" />
		    </a>
	          </li>
                </script>
		
		<ul></ul>
		
		<div class="buttons">
		  <a href="#" class="prev">Previous</a>
		  <a href="#" class="next">Next</a>
		</div>
	      </div>

	      <div class="bottom">
		<a href="http://www.flickr.com/search/?q=culturadigitalbr&m=tags" class="more">Ver mais &raquo;</a>
	      </div>
	    </div>
	  </div>

	  <div class="social-network">
            <p class="blogs">Acesse o Blog do Fórum 2010 - A rede das redes: <span><a href="http://culturadigital.br/forum2010/">Em português</a> | <a href="http://culturadigital.br/braziliandigitalculture/">In English</a></span></p>
	    <div class="twitter loading">
	      <h3>#culturadigitalbr</h3>

	      <script id="tweets" type="text/x-jquery-tmpl">
	        <li id="${id}">
	          <a href="http://twitter.com/${username}" class="thumbnail">
	            <img src="${avatar}" alt="${username}" width="48" height="48" />
	          </a>
                  <p>
	            <a href="http://twitter.com/${username}">${username}</a>:
	            {{html
                    content.parseURL().parseUsername().parseHashtag()}}
                    <span>${time}</span>
	          </p>
                </li>
              </script>

	      <ul class="twitter"></ul>
	    </div>
	    
	    <div class="bottom">
	      <a href="http://twitter.com/culturadigital" class="follow">Siga o Cultura Digital.br</a>
	      <a href="http://twitter.com/search?q=culturadigitalbr" class="more">Ver mais &raquo;</a>
	    </div>
	  </div>
          <?php endwhile; endif; ?>
	</div>
      </div>
   </div>

<?php get_footer() ?>
