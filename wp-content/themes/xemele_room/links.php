<h1>Últimos Posts</h1>
<ul><?php get_archives('postbypost', 10); ?></ul>

<h1>Páginas</h1>
<ul><?php wp_list_pages('title_li='); ?></ul>

<h1>Arquivos</h1>
<ul><?php wp_get_archives('type=monthly'); ?></ul>

<h1>Categorias</h1>
<ul><?php wp_list_categories('show_count=1&title_li='); ?> </ul>
<ul>

