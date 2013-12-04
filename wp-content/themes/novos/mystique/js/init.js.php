<?php /* Mystique/digitalnature */

  $mystique_options = get_option('mystique');
  header("content-type: application/x-javascript");
  header("Expires: Mon, 25 Dec 1989 02:00:00 GMT");
  header("Cache-Control: no-cache");
  header("Pragma: no-cache");

?>

  var $lang=new Array();
  $lang[0]="<?php _e("Posting. Please wait...", "mystique"); // comment post in progress ?>";
  $lang[1]="<?php _e("Your comment was added.", "mystique"); // comment post complete ?>";
  $lang[2]="<?php _e("Post another comment", "mystique"); // another comment? ?>";

  // init
  jQuery(document).ready(function ($) {
  if (isIE6) {
    jQuery('#page').append("<div class='crap-browser-warning'><?php _e("You're using a old and buggy browser. Switch to a <a href='http://www.mozilla.com/firefox/'>normal browser</a> or consider <a href='http://www.microsoft.com/windows/internet-explorer'>upgrading your Internet Explorer</a> to the latest version","mystique"); ?></div>");
  }
  jQuery('ul.navigation').superfish({ autoArrows: true });

  webshot("a.websnapr", "webshot");

  <?php if($mystique_options['lightbox']): // enable fancyBox for any link with rel="lightbox" and on links which references end in image extensions (jpg, gif, png) ?>
  jQuery("a[rel='lightbox'], a[href$='.jpg'], a[href$='.jpeg'], a[href$='.gif'], a[href$='.png'], a[href$='.JPG'], a[href$='.JPEG'], a[href$='.GIF'], a[href$='.PNG']").fancyboxlite({
    'zoomSpeedIn': 333,
    'zoomSpeedOut': 333,
    'zoomSpeedChange': 133,
    'easingIn': 'easeOutQuart',
    'easingOut': 'easeInQuart',
    'overlayShow': true,
    'overlayOpacity': 0.75
  });
  <?php endif; ?>


  // layout controls
  fontControl("#pageControls", "body", 10, 18);
  //pageWidthControl("#pageControls", ".page-content", '100%', '940px', '1200px');
  webshot("a.websnapr", "webshot");
  jQuery(".post-tabs").minitabs({
    content: '.sections',
    nav: '.tabs',
    effect: 'top',
    speed: 333,
    cookies: false
  });

  jQuery(".sidebar-tabs").minitabs({
    content: '.sections',
    nav: '.box-tabs',
    effect: 'slide',
    speed: 150
  });

  jQuery("ul.menuList .cat-item").bubble({
    timeout: 6000
  });
  jQuery(".shareThis, .bubble-trigger").bubble({
    offset: 16,
    timeout: 0
  });

  jQuery("#pageControls").bubble({
    offset: 30
  });
  jQuery('ul.menuList li a').nudge({
    property: 'padding',
    direction: 'left',
    amount: 6,
    duration: 166
  });
  jQuery('a.nav-extra').nudge({
    property: 'marginTop',
    direction: '',
    amount: -18,
    duration: 166
  });

  // fade effect
  if (!isIE) {
    jQuery('.fadeThis').append('<span class="hover"></span>').each(function () {
      var jQueryspan = jQuery('> span.hover', this).css('opacity', 0);
      jQuery(this).hover(function () {
        jQueryspan.stop().fadeTo(333, 1);
      },
      function () {
        jQueryspan.stop().fadeTo(333, 0);
      });
    });
  }
  jQuery("#footer-blocks.withSlider").loopedSlider();
  jQuery("#featured-content.withSlider").loopedSlider({
    autoStart: <?php echo (int)get_mystique_option('featured_timeout')*1000; ?>,
    autoHeight: false
  }); // scroll to top
  jQuery("a#goTop").click(function () {
    jQuery('html').animate({
      scrollTop: 0
    },
    'slow');
  });
  jQuery('.clearField').clearField({
    blurClass: 'clearFieldBlurred',
    activeClass: 'clearFieldActive'
  });

  jQuery("a#show-author-info").livequery("click", function () {
    jQuery("#author-info").slideFade('toggle',333,'easeOutQuart');
  });

  setup_comment_controls();
  <?php if($mystique_options['ajax_comments']): ?>setup_comment_ajax();<?php endif; ?>

  jQuery('a.print').click(function() {
      jQuery('.post.single').printElement({printMode:'popup'});

 return false;
});

  // set accessibility roles on some elements trough js (to not break the xhtml markup)
  jQuery("#navigation").attr("role", "navigation");
  jQuery("#primary-content").attr("role", "main");
  jQuery("#sidebar").attr("role", "complementary");
  jQuery("#searchform").attr("role", "search");

<?php if($_GET['preview'] == 1): ?>jQuery('body').addGrid(12, {img_path: '<?php echo THEME_URL; ?>/admin/images/'});<?php endif; ?>
<?php do_action('mystique_jquery_init'); ?>


});



