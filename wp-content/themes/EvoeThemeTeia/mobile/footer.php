<div id="footer">
    <ul id="menu" class="mid">
        <li><a href="<?php bloginfo('url'); ?>" title="Home">Home</a></li>
        <?php wp_list_pages('title_li='); ?>
        <li><a href="#header" title="Topo">Topo</a></li>
    </ul>
    <?php wp_footer(); ?>
</div>
