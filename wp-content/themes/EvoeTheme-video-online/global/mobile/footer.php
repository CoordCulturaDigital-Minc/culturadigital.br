<div id="footer">
    <ul id="menu" class="mid">
        <li><a href="<?php bloginfo('url'); ?>" title="Home"><?php _e('Home', 'evoeTheme')?></a></li>
        <?php wp_list_pages('title_li='); ?>
        <li><a href="#header" title="Topo"><?php _e('Top', 'evoeTheme')?></a></li>
    </ul>
    <?php wp_footer(); ?>
</div>
