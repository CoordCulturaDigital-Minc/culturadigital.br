    <div class="sidebar">
	<?php
		if (is_home())
		{
			if( function_exists('dynamic_sidebar') )
			{
				if ( !dynamic_sidebar('home') and !is_active_sidebar('home') )
				{
					my_register_widget( 'WP_Widget_Search', __('Pesquisar', 'evoeTheme'));
					my_register_widget( 'WP_Widget_Categories', __('Categorias', 'evoeTheme'));
				}
			}
		}
		elseif (is_archive())
		{
			if( function_exists('dynamic_sidebar') )
			{
				if ( !dynamic_sidebar('archive') and !is_active_sidebar('archive') )
				{
					my_register_widget( 'WP_Widget_Search', __('Pesquisar', 'evoeTheme'));
					my_register_widget( 'WP_Widget_Categories', __('Categorias', 'evoeTheme'));
				}
			}
		}
		elseif (is_page())
		{
			if( function_exists('dynamic_sidebar') )
			{
				if ( !dynamic_sidebar('page') and !is_active_sidebar('page') )
				{
					my_register_widget( 'WP_Widget_Search', __('Pesquisar', 'evoeTheme'));
					my_register_widget( 'WP_Widget_Categories', __('Categorias', 'evoeTheme'));
				}
			}
		}
		elseif (is_single())
		{
			if( function_exists('dynamic_sidebar') )
			{
				if ( !dynamic_sidebar('single') and !is_active_sidebar('single') )
				{
					my_register_widget( 'WP_Widget_Search', __('Pesquisar', 'evoeTheme'));
					my_register_widget( 'WP_Widget_Categories', __('Categorias', 'evoeTheme'));
				}
			}
		}
		else
		{
			if( function_exists('dynamic_sidebar') )
			{
				if ( !dynamic_sidebar('home') and !is_active_sidebar('home') )
				{
					my_register_widget( 'WP_Widget_Search', __('Pesquisar', 'evoeTheme'));
					my_register_widget( 'WP_Widget_Categories', __('Categorias', 'evoeTheme'));
				}
			}
		}
	?>	
    </div>
