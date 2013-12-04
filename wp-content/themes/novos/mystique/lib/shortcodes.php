<?php /* Mystique/digitalnature */


// output a arbitrary widget
function mystique_widget($atts){
  global $wp_widget_factory;
  extract(shortcode_atts(array(
   'class' => FALSE
  ), $atts));

  ob_start();
  $class = wp_specialchars($class);

  if (!is_a($wp_widget_factory->widgets[$class], 'WP_Widget')):
    $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
    if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
     return '<p class="error">'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct","mystique"),'<strong>'.$class.'</strong>').'</p>';
    else:
      $class = $wp_class;
    endif;
  endif;

  $instance = array(); // other attributes
  foreach($atts as $att=>$val):
   if ($att!="class") $instance[wp_specialchars($att)]=wp_specialchars($val);
  endforeach;

  $id = $class;
  $classname = $wp_widget_factory->widgets[$class]->widget_options['classname'];
  if(!$classname) $classname = $id;

  if(isset($instance['widget_id'])) $id= $instance['widget_id'];

  the_widget($class, $instance, array('widget_id'=>'arbitrary-instance-'.$id,'before_widget' => '<div class="arbitrary-block block-'.$classname.'">','after_widget' => '</div>','before_title' => '<h2 class="title">','after_title' => '</h2>'));
  $output = ob_get_contents();
  ob_end_clean();

  return $output;
}


// based on the register template from the Hybrid theme, by Justin Tadlock
function mystique_register_form($atts){
  extract(shortcode_atts(array(
   'align' => 'left',
   'inline' => 0
  ), $atts));
  require_once(ABSPATH.WPINC.'/registration.php');
  $registration = get_option('users_can_register');

  $labels = array(
   'user-name' => __('User name (required)', 'mystique'),
   'url' => __('Website', 'mystique'),
   'pass1' => __('Password (required)', 'mystique'),
   'pass2' => __('Verify password (required)', 'mystique'),
   'email' => __('E-mail (required)', 'mystique'),
   'first-name' => __('First name', 'mystique'),
   'last-name' => __('Last name', 'mystique'),
   'description' => __('Profile info', 'mystique'),
  );

  /* If user registered, input info. */
  if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) && $_POST['action'] == 'adduser'):
	$userdata = array(
      'user_pass' => esc_attr($_POST['pass1']),
      'user_login' => ($_POST['user-name'] != $labels['user-name']) ? esc_attr($_POST['user-name']) : '',
      'user_url' => ($_POST['url'] != $labels['url']) ? esc_attr($_POST['url']) : '',
      'user_email' => ($_POST['email'] != $labels['email']) ? esc_attr($_POST['email']) : '',
      'first_name' => ($_POST['first-name'] != $labels['first-name']) ? esc_attr($_POST['first-name']) : '',
      'last_name' => ($_POST['last-name'] != $labels['last-name']) ? esc_attr($_POST['last-name']) : '',
      'description' => ($_POST['description'] != $labels['description']) ? esc_attr($_POST['description']) : '',
      'role' => get_option('default_role'),
    );

    $valid = (!username_exists($userdata['user_login']) && !email_exists($userdata['user_email']) && $userdata['user_login']) && ($userdata['user_email']) && ($_POST['pass1']) && ($_POST['pass1'] == $_POST['pass2']);

	if ($valid):
      $new_user = wp_insert_user($userdata);
	else:
      if (!$userdata['user_login']) $error = __('Please type a user name', 'mystique');
      elseif (username_exists($userdata['user_login'])) $error = __('User name already exists','mystique');
      elseif (!$userdata['user_email']) $error = __('E-mail address is required.', 'mystique');
      elseif (email_exists($userdata['user_email'])) $error = __('E-mail address already in use','mystique');
      elseif (!$_POST['pass1']) $error = __('You must enter a password.', 'mystique');
      elseif (!$_POST['pass2']) $error = __('Please verify the password', 'mystique');
      elseif ($_POST['pass1'] !== $_POST['pass2']) $error = __('Passwords do not match', 'mystique');
      else $error = __('User registration failed.  Please try again.', 'mystique');
    endif;
  endif;
  $jquery = get_mystique_option('jquery');
  ob_start(); ?>
  <?php if(!$inline): ?><div class="clear-block"><?php endif; ?>
  <div class="form register align<?php echo wp_specialchars($align); ?>">
  <?php if (is_user_logged_in() && !current_user_can('create_users')) : ?>
   <p class="error">
     <?php printf(__('You are logged in as <a href="%1$s" title="%2$s">%2$s</a>. You don\'t need another account :)', 'mystique'), get_author_posts_url($curauth->ID), $user_identity); ?> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out of this account', 'mystique'); ?>"><?php _e('Logout &raquo;', 'mystique'); ?></a>
   </p>
  <?php elseif ($new_user): ?>
   <p class="altText">
    <?php
     if (current_user_can('create_users'))  printf(__('A user account for %1$s has been created.', 'mystique'), '<strong>'.$_POST['user-name'].'</strong>');
     else printf(__('Thank you for registering, %1$s.', 'mystique'), $_POST['user-name']);
    ?>
   </p>
  <?php else:
    if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif;
    if ($registration || current_user_can('create_users')): ?>
    <form method="post" id="adduser" action="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
     <fieldset>
      <div class="row">
        <?php if(!$jquery): ?><label for="user-name"> <?php echo $labels['user-name']; ?> </label><?php endif; ?>
        <input class="text <?php echo (!$_POST['user-name'] || wp_specialchars($_POST['user-name']) == $labels['user-name']) ? 'clearField' : ''; ?>" name="user-name" type="text" size="30" id="user-name" value="<?php echo $error ? wp_specialchars($_POST['user-name'], 1) : ($jquery ? $labels['user-name'] : ''); ?>" />
      </div>

      <div class="row">
        <?php if(!$jquery): ?><label for="first-name"> <?php echo $labels['first-name']; ?> </label><?php endif; ?>
      	<input class="text <?php echo (!$_POST['first-name'] || wp_specialchars($_POST['first-name']) == $labels['first-name']) ? 'clearField' : ''; ?>" name="first-name" type="text" size="30" id="first-name" value="<?php echo $error ? wp_specialchars($_POST['first-name'], 1) : ($jquery ? $labels['first-name'] : ''); ?>" />
      </div>

      <div class="row">
        <?php if(!$jquery): ?><label for="last-name"> <?php echo $labels['last-name']; ?> </label><?php endif; ?>
      	<input class="text <?php echo (!$_POST['last-name'] || wp_specialchars($_POST['last-name']) == $labels['last-name']) ? 'clearField' : ''; ?>" name="last-name" type="text" size="30" id="last-name" value="<?php echo $error ? wp_specialchars($_POST['last-name'], 1) : ($jquery ? $labels['last-name'] : ''); ?>" />
      </div>

      <div class="row">
        <?php if(!$jquery): ?><label for="email"> <?php echo $labels['email']; ?> </label><?php endif; ?>
      	<input class="text <?php echo (!$_POST['email'] || wp_specialchars($_POST['email']) == $labels['email']) ? 'clearField' : ''; ?>" name="email" type="text" size="30" id="email" value="<?php echo $error ? wp_specialchars($_POST['email'], 1) : ($jquery ? $labels['email'] : ''); ?>" />
      </div>

      <div class="row">
        <?php if(!$jquery): ?><label for="url"> <?php echo $labels['url']; ?> </label><?php endif; ?>
      	<input class="text <?php echo (!$_POST['url'] || wp_specialchars($_POST['url']) == $labels['url']) ? 'clearField' : ''; ?>" name="url" type="text" size="30" id="url" value="<?php echo $error ? wp_specialchars($_POST['url'], 1) : ($jquery ? $labels['url'] : ''); ?>" />
      </div>

      <?php if (apply_filters('show_password_fields', true)): ?>
      <div class="row">
        <?php if(!$jquery): ?><label for="pass1"> <?php echo $labels['pass1']; ?> </label><?php else: ?><input class="password label hidden clearField" type="text" value="<?php echo $labels['pass1']; ?>" size="30" alt="pass1" /><?php endif; ?>
      	<input class="password text" name="pass1" size="30" type="password" id="pass1"  />
      </div>

      <div class="row">
        <?php if(!$jquery): ?><label for="pass2"> <?php echo $labels['pass2']; ?> </label><?php else: ?><input class="password label hidden clearField" type="text" value="<?php echo $labels['pass2']; ?>" size="30" alt="pass2" /><?php endif; ?>
      	<input class="password text" name="pass2" size="30" type="password" id="pass2"  />
      </div>
      <?php endif; ?>

      <div class="row">
        <?php if(!$jquery): ?><label for="description"> <?php echo $labels['description']; ?> </label><?php endif; ?>
      	<textarea class="textarea <?php echo (!$_POST['description'] || wp_specialchars($_POST['description']) == $labels['description']) ? 'clearField' : ''; ?>" name="description" id="description" rows="10" cols="50"><?php echo $error ? wp_specialchars($_POST['description'], 1) : ($jquery ? $labels['description'] : ''); ?></textarea>
      </div>

      <div class="row">
      	<?php echo $referer; ?>
      	<input name="adduser" type="submit" id="addusersub" class="submit button" value="<?php if (current_user_can('create_users')) _e('Add User', 'mystique'); else _e('Register', 'mystique'); ?>" />
      	<?php wp_nonce_field('add-user') ?>
      	<input name="action" type="hidden" id="action" value="adduser" />
      </div>
     </fieldset>
    </form>
    <?php endif; ?>
  <?php endif; ?>
  </div>
  <?php if(!$inline): ?></div><?php endif; ?>
  <?php
  $output = ob_get_contents();
  ob_end_clean();

  return $output;
}

function mystique_googlechart($atts){
  extract(shortcode_atts(array(
   'data' => '',
   'colors' => '',
   'size' => '400x200',
   'bg' => 'ffffff',
   'title' => '',
   'labels' => '',
   'advanced' => '',
   'type' => 'pie'
  ), $atts));

  switch ($type) {
   case 'line': $charttype = 'lc'; break;
   case 'xyline': $charttype = 'lxy'; break;
   case 'sparkline': $charttype = 'ls'; break;
   case 'meter': $charttype = 'gom'; break;
   case 'scatter': $charttype = 's'; break;
   case 'venn': $charttype = 'v'; break;
   case 'pie': $charttype = 'p3'; break;
   case 'pie2d': $charttype = 'p'; break;
   default: $charttype = $type; break;
  }

  if ($title) $string .= '&chtt='.$title.'';
  if ($labels) $string .= '&chl='.$labels.'';
  if ($colors) $string .= '&chco='.$colors.'';
  $string .= '&chs='.$size.'';
  $string .= '&chd=t:'.$data.'';
  $string .= '&chf='.$bg.'';
  return '<img title="'.wp_specialchars($title).'" src="http://chart.apis.google.com/chart?cht='.wp_specialchars($charttype).''.wp_specialchars($string).wp_specialchars($advanced).'" alt="'.wp_specialchars($title).'" />';
}

function mystique_queryposts($atts){
  extract(shortcode_atts( array(
   'category_id' => '',
   'category_name' => '',
   'tag' => '',
   'day' => '',
   'month' => '',
   'year' => '',
   'count' => '5',
   'author_id' => '',
   'author_name' => '',
   'order_by' => 'date',
  ), $atts));

  $output = '';
  $query = array();

  if ($category_id != '') $query[] = 'cat=' .$category_id;
  if ($category_name != '') $query[] = 'category_name=' .$category_name;
  if ($tag != '') $query[] = 'tag=' . $tag;
  if ($day != '') $query[] = 'day=' . $day;
  if ($month != '') $query[] = 'monthnum=' . $month;
  if ($year != '') $query[] = 'year=' . $year;
  if ($count) $query[] = 'posts_per_page=' .$count;
  if ($author_id != '') $query[] = 'author=' . $author_id;
  if ($author_name != '') $query[] = 'author_name=' . $author_name;
  if ($order_by) $query[] = 'orderby=' . $order_by;

  ob_start();

  $backup = $post;
  $posts = new WP_Query(implode('&',$query));
  if ($posts->have_posts()):
   while ($posts->have_posts()):
    $posts->the_post();
    mystique_post();
   endwhile;
  else:
   echo '<p class="error">[query] '.__("No posts found matching the arguments", "mystique").'</p>';
  endif;

  $post = $backup;
  wp_reset_query();

  $output = ob_get_contents();
  ob_end_clean();

  return $output;
}

// member/visitor only content - based on http://justintadlock.com/archives/2009/05/09/using-shortcodes-to-show-members-only-content
function mystique_memberonlycontent($atts, $content = null){
  if (is_user_logged_in() && !is_null($content) && !is_feed()) return (current_user_can('unfiltered_html')) ? $content : mystique_strip_tags_attributes($content);
  return '';
}

function mystique_visitoronlycontent($atts, $content = null){
  if ((!is_user_logged_in() && !is_null($content)) || is_feed()) return (current_user_can('unfiltered_html')) ? $content : mystique_strip_tags_attributes($content);
  return '';
}

function mystique_subscribe_rss(){
  return '<a class="button rss-subscribe" href="'. get_bloginfo('rss2_url') .'" title="'. __('RSS Feeds','mystique') .'">'. __('RSS Feeds','mystique') .'</a>';
}

function mystique_tinyurl($atts){
  extract(shortcode_atts(array(
   'url' => '',
   'title' => '',
   'rel' => 'nofollow'
  ), $atts));
  if(!$title) $title = $url;
  return '<a href="'.wp_specialchars(mystique_getTinyUrl($url)).'" rel="'.wp_specialchars($rel).'">'.wp_specialchars($title).'</a>';
}

// ads
function mystique_advertisment($atts){
  extract(shortcode_atts(array(
   'code' => 1,
   'align' => 'left',
   'inline' => 0
  ), $atts));
  $ad = get_mystique_option('ad_code_'.$code);
  if(!empty($ad)):
   $ad = '<div class="ad align'.wp_specialchars($align).'">'.$ad.'</div>';
   if(!$inline) $ad = '<div class="clear-block">'.$ad.'</div>';
   return $ad;
  else:
   return '<p class="error"><strong>[ad]</strong> '.sprintf(__("Empty ad slot (#%s)!","mystique"),wp_specialchars($code)).'</p>';
  endif;
}

function mystique_go_to_top(){
  return sprintf('<a id="goTop" class="button js-link">'.__('Top','mystique').'</a>');
}

function mystique_theme_link(){
  return sprintf('<a class="theme-link" href="%1$s" title ="Mystique %2$s" rel="designer">Mystique</a>', THEME_URI, THEME_VERSION);
}

function mystique_credit(){
  return sprintf(__('%1$s theme by %2$s | Powered by %3$s', 'mystique'), '<abbr title="'.THEME_NAME.' '.THEME_VERSION.'">Mystique</abbr>','<a href="http://digitalnature.ro">digitalnature</a>', '<a href="http://wordpress.org/">WordPress</a>');
}

function mystique_copyright() {
  return sprintf('<span class="copyright"><span class="text">%1$s</span> <span class="the-year">%2$s</span> <a class="blog-title" href="%3$s" title="%4$s">%4$s</a></span>',
                  __('Copyright &copy;', 'mystique'),
                  date('Y'),
                  get_bloginfo('url'),
                  get_bloginfo('name'));
}


function mystique_wp_link(){
  return '<a class="wp-link" href="http://WordPress.org/" title="WordPress" rel="generator">WordPress</a>';
}

// login
function mystique_login_link(){
  if (is_user_logged_in()) return sprintf('<a class="login-link" href="%1$s">%2$s</a>',admin_url(),__('Site Admin'));
  else return sprintf('<a class="login-link" href="%1$s">%2$s</a>',wp_login_url(),__('Log in'));
}

// blog title
function mystique_blog_title(){
  return '<span class="blog-title">' . get_bloginfo('name') . '</span>';
}

// validate xhtml
function mystique_validate_xhtml(){
  return '<a class="button valid-xhtml" href="http://validator.w3.org/check?uri=referer" title="Valid XHTML">XHTML 1.1</a>';
}

// validate css
function mystique_validate_css(){
  return '<a class="button valid-css" href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3" title="Valid CSS">CSS 3.0</a>';
}

function mystique_theme_name(){ return THEME_NAME; }

function mystique_theme_author(){ return THEME_AUTHOR; }

function mystique_theme_uri(){ return THEME_URI; }



//    Google Toolbar 3.0.x/4.0.x Pagerank Checksum Algorithm
//    Author's webpage:
//    http://pagerank.gamesaga.net/
function mystique_pagerank($atts){
  function CheckHash($Hashnum){
    $CheckByte = 0;
    $Flag = 0;
    $HashStr = sprintf('%u', $Hashnum) ;
    $length = strlen($HashStr);
    for ($i = $length - 1;  $i >= 0;  $i --) {
      $Re = $HashStr{$i};
      if (1 === ($Flag % 2)) {
        $Re += $Re;
        $Re = (int)($Re / 10) + ($Re % 10);
      }
      $CheckByte += $Re;
      $Flag ++;
    }

    $CheckByte %= 10;
    if (0 !== $CheckByte) {
      $CheckByte = 10 - $CheckByte;
      if (1 === ($Flag % 2) ) {
       if (1 === ($CheckByte % 2)) {
        $CheckByte += 9;
       }
       $CheckByte >>= 1;
      }
     }
    return '7'.$CheckByte.$HashStr;
  }

  function HashURL($String){
    function StrToNum($Str, $Check, $Magic){
      $Int32Unit = 4294967296;  // 2^32
      $length = strlen($Str);
      for ($i = 0; $i < $length; $i++) {
        $Check *= $Magic;
        //If the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31),
        //  the result of converting to integer is undefined
        //  refer to http://www.php.net/manual/en/language.types.integer.php
        if ($Check >= $Int32Unit) {
          $Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
          //if the check less than -2^31
          $Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
        }
        $Check += ord($Str{$i});
      }
      return $Check;
    }
    $Check1 = StrToNum($String, 0x1505, 0x21);
    $Check2 = StrToNum($String, 0, 0x1003F);

    $Check1 >>= 2;
    $Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
    $Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
    $Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);

    $T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
    $T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );

    return ($T1 | $T2);
  }

  extract(shortcode_atts(array('url' => get_bloginfo('url')), $atts));
  $pagerank = 0;
  if (false === ($pagerank = get_transient('pr_'+md5($url)))):
    $query="http://toolbarqueries.google.com/search?client=navclient-auto&ch=".CheckHash(HashURL($url)). "&features=Rank&q=info:".$url."&num=100&filter=0";
    $request = new WP_Http;
    $result = $request->request($query);
    $pos = strpos($result['body'], "Rank_");
    if($pos === false); else $pagerank = substr($result['body'], $pos + 9);
    set_transient('pr_'+md5($url), $pagerank, 60*60*24); // 24 hours
  endif;

  $output = '<div class="pagerank button" title="Google PageRank &trade;">';
  $output.= sprintf(__("PR %s","mystique"), $pagerank);
  $output.= '<div class="pagerank-frame">';
  $output.= '<div class="pagerank-bar" style="width:'.(((int)$pagerank/10)*100).'%"></div>';
  $output.= '</div></div>';
  return $output;

}



add_shortcode('widget','mystique_widget');
add_shortcode('register_form','mystique_register_form');
add_shortcode('googlechart', 'mystique_googlechart');
add_shortcode('query', 'mystique_queryposts');
add_shortcode('member', 'mystique_memberonlycontent');
add_shortcode('visitor', 'mystique_visitoronlycontent');
add_shortcode('rss', 'mystique_subscribe_rss');
add_shortcode('tinyurl', 'mystique_tinyurl');
add_shortcode('ad', 'mystique_advertisment');
add_shortcode('top', 'mystique_go_to_top');
add_shortcode('theme-link', 'mystique_theme_link');
add_shortcode('credit', 'mystique_credit');
add_shortcode('copyright', 'mystique_copyright');
add_shortcode('wp-link', 'mystique_wp_link');
add_shortcode('login-link', 'mystique_login_link');
add_shortcode('blog-title', 'mystique_blog_title');
add_shortcode('xhtml', 'mystique_validate_xhtml');
add_shortcode('css', 'mystique_validate_css');
add_shortcode('theme-name', 'mystique_theme_name');
add_shortcode('theme-author', 'mystique_theme_author');
add_shortcode('theme-uri', 'mystique_theme_uri');
add_shortcode('page-rank', 'mystique_pagerank');

add_filter('widget_text', 'do_shortcode'); // Allow [SHORTCODES] in Widgets
?>