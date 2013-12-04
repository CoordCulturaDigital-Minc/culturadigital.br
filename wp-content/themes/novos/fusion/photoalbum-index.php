<?php
/*
Template Name: Photo Album


*/
global $TanTanFlickrPlugin;
if (!is_object($TanTanFlickrPlugin)) wp_die('Flickr Photo Album plugin is not installed / activated!');

get_header();
?>

<!-- mid content -->
<div id="mid-content">
 
   <?php
    include($tpl = $TanTanFlickrPlugin->getDisplayTemplate($photoTemplate));

    // uncomment this line to print out the template being used
    // echo 'Photo Album Template: '.$tpl;
   ?>

   <?php if (!is_object($Silas)):?>
   <div class="flickr-meta-links center">
     Powered by the <a href="http://tantannoodles.com/toolkit/photo-album/">Flickr Photo Album</a> plugin for WordPress.
    </div>
   <?php endif; ?>

</div>
<!-- mid content -->
</div>
<!-- /mid -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>



