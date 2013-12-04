<div class="sidebar">
    <div class="right-column right-column-1 tabs">
		<?php dynamic_sidebar('right-column-1'); ?>
    </div>
 
	<?php global $user_ID ,$userdata; ?>
	<?php if( empty( $user_ID ) ) : ?>

	<a href="/registrar-na-rede/"><img src="<?php bloginfo( stylesheet_directory ); ?>/global/img/bg/participe-cadastre-se.png" border="0" alt="Cadastre-se e participe!" class="participe" align="middle" width="99%"/></a> 

	<?php else : ?>
	<?php endif; ?>	 
 
    
    <div class="right-column right-column-2 tabs">
		<?php dynamic_sidebar('right-column-2'); ?>
    </div>
    
    <div class="right-column right-column-3 tabs">
		<?php dynamic_sidebar('right-column-3'); ?>
    </div>
	
    <div class="right-column right-column-4 tabs">
		<?php dynamic_sidebar('right-column-4'); ?>
    </div>
	
    <div class="right-column right-column-5 tabs">
		<?php dynamic_sidebar('right-column-5'); ?>
    </div>

    <div class="right-column right-column-6 tabs">
		<?php dynamic_sidebar('right-column-6'); ?>
    </div>				
	
</div>

