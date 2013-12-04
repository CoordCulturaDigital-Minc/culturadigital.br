<div id="footer">
  <div id="footer-in">
    <p> &copy;  <?php the_time('Y'); ?> <?php bloginfo(); ?>  <br />
      Aggregator Theme by <a href="http://templatic.com">Premium Wordpress Themes</a> </p>
      
       
	   
      <ul class="flink">
         <?php wp_list_pages('title_li=&depth=1&include=' . get_option('ptthemes_footerpages') .'&sort_column=menu_order'); ?>
       </ul>
        <?php wp_footer(); ?>
  </div>
</div>
</body>
</html>