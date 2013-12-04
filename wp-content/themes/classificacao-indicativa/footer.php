<div id="footer">
	<div class="container">
		<div class="row">
			<div class="span12 menu">
				<?php wp_nav_menu(array('theme_location' => 'rodape')); ?>
			</div>
			<div class="span12">
				<div class="conteudo-rodape">
					<?php echo stripslashes(get_option("_ethymos_conteudo_rodape")); ?>
				</div>
			</div>
			<div class="assinatura-governo span12">
				
			</div>
		</div>
	</div>
	
</div>

<?php wp_footer(); ?>
</body>
</html>