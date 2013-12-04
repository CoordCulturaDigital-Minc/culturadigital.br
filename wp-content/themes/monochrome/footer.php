   <ul id="copyright">
    <li style="background:none;">
                <?php
                        global $wpdb;
                        $post_datetimes = $wpdb->get_row($wpdb->prepare("SELECT YEAR(min(post_date_gmt)) AS firstyear, YEAR(max(post_date_gmt)) AS lastyear FROM $wpdb->posts WHERE post_date_gmt > 1970"));
                        if ($post_datetimes) {
                                $firstpost_year = $post_datetimes->firstyear;
                                $lastpost_year = $post_datetimes->lastyear;

                                $copyright = __('Copyright &copy;&nbsp; ', 'monochrome') . $firstpost_year;
                                if($firstpost_year != $lastpost_year) {
                                        $copyright .= '-'. $lastpost_year;
                                }
                                $copyright .= ' ';

                                echo $copyright;
                        }
                ?>
    &nbsp;<a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></li>
    <li><a href="http://www.mono-lab.net/">Theme designed by mono-lab</a></li>
    <li><a href="http://wordpress.org/">Powered by WordPress</a></li>
   </ul>
  </div>
 
</div><!-- #wrapper end -->

<?php $options = get_option('mc_options'); if ($options['pagetop']) : ?>
<div id="return_top">
 <a href="#wrapper">&nbsp;</a>
</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>