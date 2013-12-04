<?php /* Mystique/digitalnature */


function display_twitter_data($id, $twituser, $twitcount, $twitdata = false){
  $error = false;
  if(!$twitdata):
   if (false === ($twitdata = get_transient('mystique_twitter_'.$id))):
    require_once(ABSPATH.'wp-includes/class-snoopy.php');
    $snoopy = new Snoopy;
    $response = @$snoopy->fetch("http://twitter.com/users/show/".$twituser.".json");
    if ($response) $userdata = json_decode($snoopy->results, true); else $error = true;
    $response = @$snoopy->fetch("http://twitter.com/statuses/user_timeline/".$twituser.".json");
    if ($response) $tweets = json_decode($snoopy->results, true); else $error = true;
    if(!$error):
      // for php < 5 (included JSON returns object)
      $userdata = mystique_objectToArray($userdata);
      $tweets = mystique_objectToArray($tweets);

      $twitdata = array();

      $twitdata['user']['profile_image_url'] = $userdata['profile_image_url'];
      $twitdata['user']['name'] = $userdata['name'];
      $twitdata['user']['screen_name'] = $userdata['screen_name'];
      $twitdata['user']['followers_count'] = $userdata['followers_count'];
      $i = 0;
      foreach($tweets as $tweet):
        $twitdata['tweets'][$i]['text'] = $tweet['text'];
        $twitdata['tweets'][$i]['created_at'] = $tweet['created_at'];
        $twitdata['tweets'][$i]['id'] = $tweet['id'];
        $i++;
      endforeach;
      set_transient('mystique_twitter_'.$id, $twitdata, 60); // keep the data cached 60 seconds
    endif;
   endif;
  endif;

  // only show if the twitter data from the database is newer than 6 hours
  if(!$error && is_array($twitdata['tweets'])): ?>
   <div class="clear-block">
     <div class="avatar"><img src="<?php echo $twitdata['user']['profile_image_url']; ?>" alt="<?php echo $twitdata['user']['name']; ?>" /></div>
     <div class="info"><a href="http://www.twitter.com/<?php echo $twituser; ?>"><?php echo $twitdata['user']['name']; ?> </a><br /><span class="followers"> <?php printf(__("%s followers","mystique"),$twitdata['user']['followers_count']); ?></span></div>
   </div>

   <ul>
    <?php
      $i = 0;
      foreach($twitdata['tweets'] as $tweet):
        $pattern = '/\@(\w+)/';
        $replace = '<a rel="nofollow" href="http://twitter.com/$1">@$1</a>';
        $tweet['text'] = preg_replace($pattern, $replace , $tweet['text']);
        $tweet['text'] = make_clickable($tweet['text']);

        // remove +XXXX
        $tweettime = substr_replace($tweet['created_at'],'',strpos($tweet['created_at'],"+"),5);

        $link = "http://twitter.com/".$twitdata['user']['screen_name']."/statuses/".$tweet['id'];
        echo '<li><span class="entry">' . $tweet['text'] .'<a class="date" href="'.$link.'" rel="nofollow">'.mystique_timeSince(abs(strtotime($tweettime . " GMT")),time()). '</a></span></li>';
        $i++;
        if ($i == $twitcount) break;
      endforeach;
    ?>
   </ul>

  <?php else: ?>
   <p class="error"><?php _e("Error while retrieving tweets (Twitter down?)","mystique"); ?></p>
  <?php endif;
}

function get_twitter_data(){
  if($_GET['get_twitter_data'] == 1):
    display_twitter_data(wp_specialchars($_GET['widget_id']), wp_specialchars($_GET['twituser']), wp_specialchars($_GET['twitcount']));
    die();
  endif;
}
add_action('init', 'get_twitter_data');


// categories widget

class SidebarTabsWidget extends WP_Widget{
    function SidebarTabsWidget(){
      $widget_ops = array('classname' => 'sidebar_tabs', 'description' => __( "Displays links to different sections grouped in tabs","mystique") );
      $control_ops = array('width' => 300);
      $this->WP_Widget(false, __('Sidebar tabs','mystique'), $widget_ops, $control_ops);
    }

    function widget($args, $instance){

      extract($args);
	  $orderby = empty( $instance['orderby'] ) ? 'name' : $instance['orderby'];
      $postcount = isset($instance['postcount']) ? $instance['postcount'] : false;
      $showcategories = isset($instance['showcategories']) ? $instance['showcategories'] : false;
      $showtags = isset($instance['showtags']) ? $instance['showtags'] : false;
      $showarchives = isset($instance['showarchives']) ? $instance['showarchives'] : false;
      $showpopular = isset($instance['showpopular']) ? $instance['showpopular'] : false;
      $showrecentcomm = isset($instance['showrecentcomm']) ? $instance['showrecentcomm'] : false;
      $popularpostnumber = empty($instance['popularpostnumber']) ? '10' : $instance['popularpostnumber'];
      $commentnumber = empty($instance['commentnumber']) ? '8' : $instance['commentnumber'];
      $id = $args['widget_id'];
      $jquery = get_mystique_option('jquery');

      echo $before_widget; ?>
      <?php if($jquery): // no tab navigation if jquery is disabled ?>
      <!-- tabbed content -->
      <div class="tabbed-content sidebar-tabs clear-block" id="<?php echo $id; ?>">

       <!-- tab navigation (items must be in reverse order because of the tab-design) -->
       <ul class="box-tabs clear-block">
          <?php if($showrecentcomm && WP_VERSION > 2.8): ?><li class="recentcomm"><a href="#<?php echo $id; ?>-section-recentcomments" title="<?php _e('Recent comments','mystique'); ?>"><span><?php _e('Recent comments','mystique'); ?></span></a></li><?php endif; ?>
          <?php if($showpopular): ?><li class="popular"><a href="#<?php echo $id; ?>-section-popular" title="<?php _e('Popular posts','mystique'); ?>"><span><?php _e('Popular posts','mystique'); ?></span></a></li><?php endif; ?>
          <?php if($showarchives): ?><li class="archives"><a href="#<?php echo $id; ?>-section-archives" title="<?php _e('Archives','mystique'); ?>"><span><?php _e('Archives','mystique'); ?></span></a></li><?php endif; ?>
          <?php if($showtags): ?><li class="tags"><a href="#<?php echo $id; ?>-section-tags" title="<?php _e('Tags','mystique'); ?>"><span><?php _e('Tags','mystique'); ?></span></a></li><?php endif; ?>
          <?php if($showcategories): ?><li class="categories"><a href="#<?php echo $id; ?>-section-categories" title="<?php _e('Categories','mystique'); ?>"><span><?php _e('Categories','mystique'); ?></span></a></li><?php endif; ?>
       </ul>
       <!-- /tab nav -->

       <!-- tab sections -->
       <div class="sections">

        <?php if($showcategories): ?>
        <!-- comments -->

         <div class="box section" id="<?php echo $id; ?>-section-categories">
          <div class="box-top-left"><div class="box-top-right"></div></div>
          <div class="box-main">
           <div class="box-content">
            <ul class="menuList categories">
             <?php
              if($postcount):
               echo preg_replace('@\<li([^>]*)>\<a([^>]*)>(.*?)\<\/a> \(\<a ([^>]*)([^>]*)>XML\<\/a>\) \((.*?)\)@i', '<li $1><a$2 class="fadeThis"><span class="entry">$3 <span class="details inline">($6)</span></span></a><a class="rss bubble" $4></a>', wp_list_categories('orderby='.$orderby.'&show_count=1&echo=0&title_li=&feed=XML'));
              else:
               echo preg_replace('@\<li([^>]*)>\<a([^>]*)>(.*?)\<\/a> \(\<a ([^>]*) ([^>]*)>(.*?)\<\/a>\)@i', '<li $1><a$2 class="fadeThis"><span class="entry">$3</span></a><a class="rss bubble" $4></a>', wp_list_categories('orderby='.$orderby.'&show_count=0&echo=0&title_li=&feed=XML'));
              endif;
             ?>
            </ul>
           </div>
          </div>
         </div>

        <?php endif; ?>

        <?php if($showtags): ?>

         <div class="box section" id="<?php echo $id; ?>-section-tags">
          <div class="box-top-left"><div class="box-top-right"></div></div>
          <div class="box-main">
           <div class="box-content">
            <div class="tag-cloud">
             <?php wp_tag_cloud(apply_filters('widget_tag_cloud_args', array())); ?>
            </div>
           </div>
          </div>
         </div>

       <?php endif; ?>

        <?php if($showarchives): ?>

         <div class="box section" id="<?php echo $id; ?>-section-archives">
          <div class="box-top-left"><div class="box-top-right"></div></div>
          <div class="box-main">
           <div class="box-content">
            <ul class="menuList">
      	     <?php //wp_get_archives(apply_filters('widget_archives_args', array('type' => 'monthly', 'show_post_count' => $postcount)));
              if($postcount):
               echo preg_replace('@\<li>\<a([^>]*)>(.*?)\<\/a>([^>]*)\((.*?)\)@i', '<li><a$1 class="fadeThis"><span class="entry">$2 <span class="details inline">($4)</span></span></a>', wp_get_archives(apply_filters('widget_archives_args', array('type' => 'monthly', 'show_post_count' => $postcount, 'echo' => 0))));
              else:
               echo preg_replace('@\<li>\<a([^>]*)>(.*?)\<\/a>@i', '<li><a$1 class="fadeThis"><span class="entry">$2</span></a>', wp_get_archives(apply_filters('widget_archives_args', array('type' => 'monthly', 'show_post_count' => $postcount, 'echo' => 0))));
              endif;
             ?>
            </ul>
           </div>
          </div>
         </div>

       <?php endif; ?>

        <?php if($showpopular): ?>

         <div class="box section" id="<?php echo $id; ?>-section-popular">
          <div class="box-top-left"><div class="box-top-right"></div></div>
          <div class="box-main">
           <div class="box-content">
            <?php
             $popular = new WP_Query('orderby=comment_count&caller_get_posts=1&posts_per_page='.$popularpostnumber);
	         if ($popular->have_posts()): ?>
              <ul class="menuList"><?php
              while ($popular->have_posts()):
               $popular->the_post(); ?>
               <li><a class="fadeThis" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><span class="entry"><?php the_title(); ?> <span class="details inline">(<?php comments_number('0','1','%'); ?>)</span></span></a></li><?php
              endwhile; ?>
              </ul> <?php
             else:
               _e("Didn't find any posts popular enough :(","mystique");
             endif;
             wp_reset_query();
             ?>
           </div>
          </div>
         </div>

       <?php endif; ?>

       <?php if($showrecentcomm && WP_VERSION > 2.8): ?>

         <div class="box section" id="<?php echo $id; ?>-section-recentcomments">
          <div class="box-top-left"><div class="box-top-right"></div></div>
          <div class="box-main">
           <div class="box-content">

            <ul class="menuList recentcomm">
            <?php
              $comments = get_comments('number='.$commentnumber.'&status=approve');
              //$true_comment_count = 0;
              foreach($comments as $comment):
               $comment_type = get_comment_type();
                //if($comment_type == 'comment'):
                //$true_comment_count = $true_comment_count+1;
                //$comm_title = get_the_title($comment->comment_post_ID);
                $comm_content = get_comment($comment->comment_ID, ARRAY_A); ?>
                <li class="clear-block"><a title="<?php printf(__("Posted in: %s", "mystique"), get_the_title($comment->comment_post_ID)); ?>" class="fadeThis" href="<?php echo get_comment_link($comment->comment_ID) ?>"><span class="avatar"><?php echo mystique_get_avatar($comment->comment_author_email, 32); ?></span><span class="entry"><?php echo($comment->comment_author)?>: <span class="details"> <?php echo mystique_strip_string(100, $comm_content['comment_content']); ?></span></span></a></li>
               <?php
                //endif;
                //if($true_comment_count == $commentnumber) break;
              endforeach; ?>
            </ul>

           </div>
          </div>
         </div>

       <?php endif; ?>

       </div>
      </div>
      <!-- tabbed content -->
      <?php else:
      echo $before_title . __("Sidebar tabs","mystique") . $after_title; ?>
      <p class="error"><?php _e("jQuery is disabled and this widget needs it","mystique"); ?></p>
      <?php endif; ?>

      <?php
      echo $after_widget;
    }

    function update($new_instance, $old_instance){
      $instance = $old_instance;
	  if (in_array($new_instance['orderby'], array('name', 'ID', 'slug','count'))) $instance['orderby'] = $new_instance['orderby'];
	  else $instance['orderby'] = 'name';

	  $instance['postcount'] = $new_instance['postcount'] ? 1 : 0;
	  $instance['showcategories'] = $new_instance['showcategories'] ? 1 : 0;
	  $instance['showtags'] = $new_instance['showtags'] ? 1 : 0;
	  $instance['showarchives'] = $new_instance['showarchives'] ? 1 : 0;
	  $instance['showpopular'] = $new_instance['showpopular'] ? 1 : 0;
	  $instance['showrecentcomm'] = $new_instance['showrecentcomm'] ? 1 : 0;
      $instance['popularpostnumber'] = strip_tags(stripslashes($new_instance['popularpostnumber']));
      $instance['commentnumber'] = strip_tags(stripslashes($new_instance['commentnumber']));
      return $instance;
    }

    function form($instance){
      // defaults
      $instance = wp_parse_args( (array) $instance, array('orderby' => 'name', 'postcount' => true, 'showcategories' => true, 'showtags' => true, 'showarchives' => true, 'showpopular' => true, 'showrecentcomm' => true, 'popularpostnumber' => '10', 'commentnumber' => '8'));
  	  $postcount = $instance['postcount'] ? 'checked="checked"' : '';
  	  $postcount = $instance['showcategories'] ? 'checked="checked"' : '';
  	  $postcount = $instance['showtags'] ? 'checked="checked"' : '';
  	  $postcount = $instance['showarchives'] ? 'checked="checked"' : '';
  	  $postcount = $instance['showpopular'] ? 'checked="checked"' : '';
  	  $postcount = $instance['showrecentcomm'] ? 'checked="checked"' : '';
      $popularpostnumber = htmlspecialchars($instance['popularpostnumber']);
      $commentnumber = htmlspecialchars($instance['commentnumber']);
      ?>
      <h3><?php _e("Tabs to show","mystique"); ?></h3>
      <fieldset style="background:#eee;padding:1em;">
      <p>
       <input class="checkbox" type="checkbox" <?php checked($instance['showcategories'], true) ?> id="<?php echo $this->get_field_id('showcategories'); ?>" name="<?php echo $this->get_field_name('showcategories'); ?>" />
       <label for="<?php echo $this->get_field_id('showcategories'); ?>"><?php _e('Categories','mystique'); ?></label>
      </p>
      <p>
       <input class="checkbox" type="checkbox" <?php checked($instance['showtags'], true) ?> id="<?php echo $this->get_field_id('showtags'); ?>" name="<?php echo $this->get_field_name('showtags'); ?>" />
       <label for="<?php echo $this->get_field_id('showtags'); ?>"><?php _e('Tags ','mystique'); ?></label>
      </p>
      <p>
       <input class="checkbox" type="checkbox" <?php checked($instance['showarchives'], true) ?> id="<?php echo $this->get_field_id('showarchives'); ?>" name="<?php echo $this->get_field_name('showarchives'); ?>" />
       <label for="<?php echo $this->get_field_id('showarchives'); ?>"><?php _e('Archives','mystique'); ?></label>
      </p>
      <p>
       <input class="checkbox" type="checkbox" <?php checked($instance['showpopular'], true) ?> id="<?php echo $this->get_field_id('showpopular'); ?>" name="<?php echo $this->get_field_name('showpopular'); ?>" />
       <label for="<?php echo $this->get_field_id('showpopular'); ?>"><?php _e('Popular posts (most commented)','mystique'); ?></label>
      </p>

      <?php if(WP_VERSION > 2.8): ?>
      <p>
       <input class="checkbox" type="checkbox" <?php checked($instance['showrecentcomm'], true) ?> id="<?php echo $this->get_field_id('showrecentcomm'); ?>" name="<?php echo $this->get_field_name('showrecentcomm'); ?>" />
       <label for="<?php echo $this->get_field_id('showrecentcomm'); ?>"><?php _e('Recent comments','mystique'); ?></label>
      </p>
      <?php endif; ?>
      </fieldset>
      <br />
      <fieldset>
      <p>
       <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e( 'Sort categories','mystique' ); ?></label>
       <select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>">
	   	<option value="name"<?php selected( $instance['orderby'], 'name' ); ?>><?php _e('by name (alphabetically)','mystique'); ?></option>
	   	<option value="ID"<?php selected( $instance['orderby'], 'ID' ); ?>><?php _e('by category ID','mystique'); ?></option>
	   	<option value="slug"<?php selected( $instance['orderby'], 'slug' ); ?>><?php _e('by category slug','mystique'); ?></option>
	   	<option value="count"<?php selected( $instance['orderby'], 'count' ); ?>><?php _e( 'by post count','mystique' ); ?></option>
	   </select>
      </p>
      <p>
       <input class="checkbox" type="checkbox" <?php checked($instance['postcount'], true) ?> id="<?php echo $this->get_field_id('postcount'); ?>" name="<?php echo $this->get_field_name('postcount'); ?>" />
       <label for="<?php echo $this->get_field_id('postcount'); ?>"><?php _e('Show post count (categories and archives)','mystique'); ?></label>
      </p>
      </fieldset>

      <fieldset>
      <p>
        <label for="<?php echo $this->get_field_name('popularpostnumber'); ?>"><?php _e('How many popular posts to show?','mystique'); ?></label>
        <input size="5" id="<?php echo $this->get_field_id('popularpostnumber'); ?>" name="<?php echo $this->get_field_name('popularpostnumber'); ?>" type="text" value="<?php echo $popularpostnumber; ?>" />
      </p>
      </fieldset>

      <fieldset>
      <p>
        <label for="<?php echo $this->get_field_name('commentnumber'); ?>"><?php _e('How many recent comments to show?','mystique'); ?></label>
        <input size="5" id="<?php echo $this->get_field_id('commentnumber'); ?>" name="<?php echo $this->get_field_name('commentnumber'); ?>" type="text" value="<?php echo $commentnumber; ?>" />
      </p>
      </fieldset>

     <?php
    }
}



// twitter widget

class TwitterWidget extends WP_Widget{

    function TwitterWidget(){
      $widget_ops = array('classname' => 'twitter', 'description' => __( "Shows your latest Twitter updates","mystique") );
      $this->WP_Widget(false, __('Twitter','mystique'), $widget_ops);
    }

    function widget($args, $instance){
      extract($args);

      $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
      $twituser = empty($instance['twituser']) ? 'Wordpress' : $instance['twituser'];
      $twitcount = empty($instance['twitcount']) ? '4' : $instance['twitcount'];
      $id = $args['widget_id'];
      $shortid = preg_replace("/[^0-9]/", '', $id);
      $nonce = wp_create_nonce('gettwitterdata');

      echo $before_widget;
      if ($title) echo $before_title . $title . $after_title;

      if(get_mystique_option('jquery')):
       //add_action('wp_footer', create_function('','echo $output;'));
      ?>

      <div class="twitter-content clear-block" id="<?php echo $id; ?>">
       <?php
        if (false === ($twitdata = get_transient('mystique_twitter_'.$shortid))): ?>

          <script type="text/javascript">
          /* <![CDATA[ */

          // init
          jQuery(document).ready(function(){
            jQuery.ajax({ // load tweets trough ajax to avoid waiting for twitter's response
             type: "GET",url: "<?php bloginfo('url'); ?>",data: { widget_id: '<?php echo $shortid; ?>', twituser: '<?php echo $twituser; ?>', twitcount: '<?php echo $twitcount; ?>', get_twitter_data: 1 },
             beforeSend: function() {jQuery("#<?php echo $id; ?> .loading").show("slow");},
             complete: function() { jQuery("#<?php echo $id; ?> .loading").hide("fast");},
             success: function(html){
               jQuery("#<?php echo $id; ?>").html(html);
               jQuery("#<?php echo $id; ?>").show("slow");

             }
            });
          });
          /* ]]> */
          </script>
          <div class="loading"><?php _e("Loading tweets...","mystique"); ?></div>

        <?php
        else:
            display_twitter_data($shortid, $twituser, $twitcount, $twitdata);
        endif; ?>
      </div>
      <?php else: ?>
      <p class="error"><?php _e("jQuery is disabled and this widget needs it","mystique"); ?></p>
      <?php endif; ?>

      <?php  if ($title): ?>
      <a class="followMe" href="http://www.twitter.com/<?php echo $instance['twituser']; ?>"><span><?php _e("Follow me on Twitter!","mystique"); ?></span></a>
      <?php endif;

      echo $after_widget;
    }

    function update($new_instance, $old_instance){
      $instance = $old_instance;
      $instance['title'] = strip_tags(stripslashes($new_instance['title']));
      $instance['twituser'] = strip_tags(stripslashes($new_instance['twituser']));
      $instance['twitcount'] = strip_tags(stripslashes($new_instance['twitcount']));
    return $instance;
    }

    function form($instance){
      // defaults
      $instance = wp_parse_args( (array) $instance, array('title'=>__('My latest tweets','mystique'), 'twituser'=>'Wordpress', 'twitcount'=>'4') );

      $title = htmlspecialchars($instance['title']);
      $twituser = htmlspecialchars($instance['twituser']);
      $twitcount = htmlspecialchars($instance['twitcount']);
      ?>
      <p>
       <label for="<?php echo $this->get_field_name('title'); ?>"> <?php _e('Title:','mystique'); ?></label>
       <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
      </p>

      <p>
       <label for="<?php echo $this->get_field_name('twituser'); ?>"><?php _e('Twitter user name:','mystique'); ?></label>
       <input class="widefat" id="<?php echo $this->get_field_id('twituser'); ?>" name="<?php echo $this->get_field_name('twituser'); ?>" type="text" value="<?php echo $twituser; ?>" />
      </p>

      <p>
       <label for="<?php echo $this->get_field_name('twitcount'); ?>"><?php _e('Number of tweets to show:','mystique'); ?></label>
       <input size="8" id="<?php echo $this->get_field_id('twitcount'); ?>" name="<?php echo $this->get_field_name('twitcount'); ?>" type="text" value="<?php echo $twitcount; ?>" />
      </p>
      <?php
    }
}



// flickr widget

class FlickrWidget extends WP_Widget{

    function FlickrWidget(){
      $widget_ops = array('classname' => 'flickr', 'description' => __( "Displays Flickr galleries (needs API key)","mystique") );
      $control_ops = array('width' => 400);
      $this->WP_Widget(false, __('Flickr Gallery','mystique'), $widget_ops, $control_ops);
    }

    function widget($args, $instance){
      extract($args);
      $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
      $apikey = empty($instance['apikey']) ? '' : $instance['apikey'];
      $numphotos = empty($instance['numphotos']) ? '16' : $instance['numphotos'];
      $photoset = empty($instance['photoset']) ? '' : $instance['photoset'];
      $userid = empty($instance['userid']) ? '' : $instance['userid'];
	  $type = empty( $instance['type'] ) ? 'name' : $instance['type'];

      $id = $args['widget_id'];
      $jquery = get_mystique_option('jquery');
      echo $before_widget;
      $icon = '<span class="icon"></span>';
      if ( $title ) echo $before_title . $title . $icon. $after_title; ?>
      <?php if ($jquery): ?>
      <script type="text/javascript">
      /* <![CDATA[ */
      jQuery(document).ready(function () {

        //var flickrAPI = '52ca4906451ca4d51e6ef9ba4758f77d';
        //var flickrPhotoset = "72157600073457403";

        jQuery(function () {
         jQuery("<?php if ($id) echo '#instance-'.$id; ?>.block-flickr .flickrGallery").flickr({
         api_key: '<?php echo $apikey; ?>',
         <?php if($type=='user'): ?>
         type: 'search',
         user_id: '<?php echo $userid; ?>',
         <?php elseif($type=='photoset'): ?>
         type: 'photoset',
         photoset_id: '<?php echo $photoset; ?>',
         <?php endif; ?>
         per_page: <?php echo $numphotos; ?>,
         callback: liteboxCallback
        });
      });

      });
      /* ]]> */
      </script>

      <div class="flickrGallery"></div>
      <?php else: ?>
      <p class="error"><?php _e("jQuery is disabled and this widget needs it","mystique"); ?></p>
      <?php endif; ?>
      <?php
      echo $after_widget;
    }

    function update($new_instance, $old_instance){
      $instance = $old_instance;
      $instance['title'] = strip_tags(stripslashes($new_instance['title']));
      $instance['apikey'] = strip_tags(stripslashes($new_instance['apikey']));
      $instance['numphotos'] = strip_tags(stripslashes($new_instance['numphotos']));
      $instance['photoset'] = strip_tags(stripslashes($new_instance['photoset']));
      $instance['userid'] = strip_tags(stripslashes($new_instance['userid']));

	  if ( in_array( $new_instance['type'], array( 'photoset', 'user') )):
		$instance['type'] = $new_instance['type'];
	  else:
		$instance['type'] = 'name';
  	  endif;

      return $instance;
    }

    function form($instance){
      // defaults
      $instance = wp_parse_args( (array) $instance, array('title'=>__('Flickr Gallery','mystique'), 'apikey'=>'', 'numphotos'=>'12', 'photoset'=>'', 'userid'=>'', 'type' => 'photoset') );
      $title = htmlspecialchars($instance['title']);
      $apikey = htmlspecialchars($instance['apikey']);
      $numphotos = htmlspecialchars($instance['numphotos']);
      $photoset = htmlspecialchars($instance['photoset']);
      $userid = htmlspecialchars($instance['userid']);
     ?>

      <p>
        <label for="<?php echo $this->get_field_name('title'); ?>"><?php _e('Title:','mystique'); ?>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title ?>" /></label>
      </p>

      <p>
        <label for="<?php echo $this->get_field_name('apikey'); ?>"> <a href="http://www.flickr.com/services/api/misc.api_keys.html" target="_blank"><?php _e('API key:','mystique'); ?></a></label>
        <input class="widefat" id="<?php echo $this->get_field_id('apikey'); ?>" name="<?php echo $this->get_field_name('apikey'); ?>" type="text" value="<?php echo $apikey; ?>" />
      </p>

      <p>
        <label for="<?php echo $this->get_field_name('numphotos'); ?>"><?php _e('Number of photos to show:','mystique'); ?></label>
        <input size="5" id="<?php echo $this->get_field_id('numphotos'); ?>" name="<?php echo $this->get_field_name('numphotos'); ?>" type="text" value="<?php echo $numphotos; ?>" />
      </p>

      <p>
       <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e( 'Gallery source:','mystique' ); ?></label>
	   <select name="<?php echo $this->get_field_name('type'); ?>" id="<?php echo $this->get_field_id('type'); ?>">
	   	<option value="photoset"<?php selected( $instance['type'], 'photoset' ); ?>><?php _e('Photoset','mystique'); ?></option>
	   	<option value="user"<?php selected( $instance['type'], 'user' ); ?>><?php _e('User photostream','mystique'); ?></option>
	   </select>
	  </p>

      <p>
        <label for="<?php echo $this->get_field_name('photoset'); ?>"><?php _e('Photoset ID:','mystique'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('photoset'); ?>" name="<?php echo $this->get_field_name('photoset'); ?>" type="text" value="<?php echo $photoset; ?>" />
      </p>

      <p>
        <label for="<?php echo $this->get_field_name('userid'); ?>"><?php printf(__('User ID (use %s to find it):','mystique'),'<a target="_blank" href="http://idgettr.com/">idGettr</a>'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('userid'); ?>" name="<?php echo $this->get_field_name('userid'); ?>" type="text" value="<?php echo $userid; ?>" />
      </p>

      <?php
    }
}



// login widget

class LoginWidget extends WP_Widget{

    function LoginWidget(){
      $widget_ops = array('classname' => 'login', 'description' => __( "Login form","mystique") );
      $this->WP_Widget(false, __('Login','mystique'), $widget_ops);
    }

    function widget($args, $instance){

      global $user_ID, $user_identity, $user_level, $user_email, $user_login;
      extract($args);
      echo $before_widget;
      echo $before_title;
      if ($user_ID):
        printf(__("Logged in: %s","mystique"),$user_identity);
        echo $after_title; ?>
        <div class="clear-block">
        <div class="avatar alignleft"><a href="<?php bloginfo('wpurl') ?>/wp-admin/profile.php"><?php echo mystique_get_avatar($user_email, 64); ?></a></div>
        <ul class="alignleft">
         <li><a href="<?php bloginfo('wpurl') ?>/wp-admin/"><?php _e("Dashboard","mystique"); ?></a></li>
         <?php if ($user_level >= 1): ?>
         <li><a href="<?php bloginfo('wpurl') ?>/wp-admin/post-new.php"><?php _e("Write","mystique"); ?></a></li>
         <li><a href="<?php bloginfo('wpurl') ?>/wp-admin/edit-comments.php"><?php _e("Comments","mystique"); ?></a></li>
         <?php endif; ?>
         <li><a href="<?php echo wp_logout_url() ?>&amp;redirect_to=<?php echo urlencode(mystique_curPageURL()); ?>"><?php _e("Log out","mystique"); ?></a></li>
        </ul>
        </div>
      <?php else:
        _e("User Login","mystique");
        echo $after_title; ?>
        <form action="<?php bloginfo('wpurl') ?>/wp-login.php" method="post">
         <fieldset>
           <label for="log"><?php _e("User","mystique"); ?></label><br /><input type="text" name="log" id="log" value="<?php echo wp_specialchars(stripslashes($user_login), 1) ?>" size="20" /><br />
           <label for="pwd"><?php _e("Password","mystique"); ?></label><br /><input type="password" name="pwd" id="pwd" size="20" /><br />
           <input type="submit" name="submit" value="<?php _e("Login","mystique"); ?>" class="button" />
           <label for="rememberme"><input name="rememberme" id="rememberme" type="checkbox" checked="checked" value="forever" /><?php _e("Remember me","mystique"); ?></label><br />
          <input type="hidden" name="redirect_to" value="<?php echo mystique_curPageURL(); ?>"/>
         </fieldset>
        </form>
        <ul>
        <?php if (get_option('users_can_register')) { ?><li><a href="<?php bloginfo('wpurl') ?>/wp-register.php"><?php _e("Register","mystique"); ?></a></li><?php } ?>
            <li><a href="<?php bloginfo('wpurl') ?>/wp-login.php?action=lostpassword"><?php _e("Lost your password?","mystique"); ?></a></li>
        </ul>
        <?php endif; ?>

      <?php
      echo $after_widget;
    }

}

class TextWidget extends WP_Widget{
  function TextWidget() {
    $widget_ops = array('classname' => 'widget_text', 'description' => (current_user_can('edit_themes') ? __('Arbitrary text, HTML, shortcodes or PHP code', 'mystique') : __('Arbitrary text, HTML or shortcodes')));
    $control_ops = array('width' => 400, 'height' => 350);
    $this->WP_Widget('text', __('Text'), $widget_ops, $control_ops);
  }

  function widget($args, $instance) {
    extract($args);
    $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
    $text = apply_filters('widget_text', $instance['text'], $instance);
    echo $before_widget;
    if (!empty($title)) echo $before_title.$title.$after_title;
    if (current_user_can('edit_themes') && !$instance['nophp']):
      ob_start();
      eval('?>'.$text);
      $text = ob_get_contents();
      ob_end_clean();
    endif;
    ?>
    <div class="textwidget"><?php echo $instance['filter'] ? wpautop($text) : $text; ?></div>
    <?php
    echo $after_widget;
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    if (current_user_can('unfiltered_html')) $instance['text'] =  $new_instance['text'];
    else $instance['text'] = stripslashes(wp_filter_post_kses(addslashes($new_instance['text']))); // wp_filter_post_kses() expects slashed
    $instance['filter'] = isset($new_instance['filter']);
    $instance['nophp'] = isset($new_instance['nophp']);
    return $instance;
  }

  function form($instance) {
    $instance = wp_parse_args((array)$instance, array('title' => '', 'text' => ''));
    $title = strip_tags($instance['title']);
    $text = format_to_edit($instance['text']);
    ?>
    <p>
     <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
     <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
     <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
    <p>
     <input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label>
     <?php if (current_user_can('edit_themes')): ?>
     <br />
     <input id="<?php echo $this->get_field_id('nophp'); ?>" name="<?php echo $this->get_field_name('nophp'); ?>" type="checkbox" <?php checked(isset($instance['nophp']) ? $instance['nophp'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('nophp'); ?>"><?php printf(__("Don't execute code between %s tags", "mystique"),"<code>&lt;?php ?&gt;</code>"); ?></label>
     <?php endif; ?>
    </p>
    <?php
  }
}

// init
function MystiqueWidgetsInit(){
  unregister_widget('WP_Widget_Text');

  register_widget('TwitterWidget');
  register_widget('SidebarTabsWidget');
  register_widget('FlickrWidget');
  register_widget('LoginWidget');
  register_widget('TextWidget');
}

add_action('widgets_init', 'MystiqueWidgetsInit');
?>