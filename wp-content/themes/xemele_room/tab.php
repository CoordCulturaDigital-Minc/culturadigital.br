		
<div class="tabber">

<div class="tabbertab">
<h2>VIDEO</h2>
		     	<?php $vid = get_option('xemele_video'); echo stripslashes($vid); ?>
  
</div>
<div class="tabbertab">

<h2> Nuvem de Tags </h2>
     		 
	
<?php if(function_exists('wp_cumulus_insert')) { ?>
<?php  wp_cumulus_insert(); ?>
<?php } else { ?>
<p> Ative Wp-cumulus plugin Para ver a nuvem de Tags em Flash! </p>
<?php } ?> 


   </div>

   <div class="tabbertab">

<h2>Posts Recentes</h2>
<li><ul>
<?php
$myposts = get_posts('numberposts=10&offset=0');
foreach($myposts as $post) :
?>
<li><a href="<?php the_permalink(); ?>"><?php the_title();?></a></li>
<?php endforeach; ?></ul></li>

</div>




</div>
	

		
