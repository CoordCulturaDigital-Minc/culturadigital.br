<?php /* Fusion/digitalnature */ ?>
<?php get_header(); ?>

 <!-- mid content -->
 <div id="mid-content">

  <?php
   // global $wp_query;
   // $curauth = $wp_query->get_queried_object();

   if(isset($_GET['author_name'])) : $curauth = get_userdatabylogin($author_name);
    else : $curauth = get_userdata(intval($author));
   endif;

  ?>

  <h1><?php echo $curauth->display_name; ?></h1>

  <div class="profile">
    <div class="avatar left"><?php echo get_avatar($curauth->user_email, '128', $avatar); ?></div>
    <div class="info">
    <p>
    <?php
     if($curauth->user_description<>''): echo $curauth->user_description;
     else: _e("This user hasn't shared any biographical information","fusion");
     endif;
    ?>
    </p>
     <?php
      if(($curauth->user_url<>'http://') && ($curauth->user_url<>'')) echo '<p class="im">'.__('Homepage:','fusion').' <a href="'.$curauth->user_url.'">'.$curauth->user_url.'</a></p>';
      if($curauth->yim<>'') echo '<p class="im">'.__('Yahoo Messenger:','fusion').' <a class="im_yahoo" href="ymsgr:sendIM?'.$curauth->yim.'">'.$curauth->yim.'</a></p>';
      if($curauth->jabber<>'') echo '<p class="im">'.__('Jabber/GTalk:','fusion').' <a class="im_jabber" href="gtalk:chat?jid='.$curauth->jabber.'">'.$curauth->jabber.'</a></p>';
      if($curauth->aim<>'') echo '<p class="im">'.__('AIM:','fusion').' <a class="im_aim" href="aim:goIM?screenname='.$curauth->aim.'">'.$curauth->aim.'</a></p>';
     ?>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>
  <br />

  <?php if (have_posts()): ?>
  <h2 class="pagetitle"><?php printf(__('Posts by %s', 'fusion'), $curauth->display_name); ?></h2>
  <?php while (have_posts()) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" <?php if (function_exists("post_class")) post_class(); else print 'class="post"'; ?>>
  		<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
        <small><?php the_time(get_the_time(get_option('date_format')).' - '.get_the_time(get_option('time_format'))) ?></small>
  		<p class="postmetadata"><?php the_tags(__('Tags: ','fusion'), ', ', '<br />'); ?> <?php printf(__('Posted in %s','fusion'), get_the_category_list(', '));?>  | <?php edit_post_link(__('Edit','fusion'), '', ' | '); ?>  <?php comments_popup_link(__('No Comments','fusion'), __('1 Comment','fusion'), __('% Comments','fusion')); ?></p>
  	</div>
        <?php endwhile; ?>
  <div class="navigation" id="pagenavi">
     	<?php if(function_exists('wp_pagenavi')) : ?>
  <?php wp_pagenavi() ?>
     	<?php else : ?>
  	<div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries','fusion')) ?></div>
  	<div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;','fusion')) ?></div>
            <div class="clear"></div>
        <?php endif; ?>
  </div>
  <?php else : ?>
  <p class="error"><?php _e('No posts found by this author.','fusion'); ?></p>
  <?php endif; ?>

 </div>
 <!-- mid content -->

</div>
<!-- /mid -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
