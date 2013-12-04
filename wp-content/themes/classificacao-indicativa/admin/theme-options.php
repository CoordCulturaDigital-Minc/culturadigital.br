<div class="wrap">

<div id="icon-options-general" class="icon32"><br></div>

<h2>Configurações do tema</h2>

<?php if($this->theme_options_save()) : ?>
<div id="setting-error-settings_updated" class="updated settings-error"> 
	<p><strong>Configurações salvas.</strong></p>
</div>
<?php endif; ?>

<form method="post" action="#">
<?php $options = $this->get_custom_options(); ?>
<?php foreach($options as $label => $key) : ?>

<p>
<label><strong><?php echo $label; ?></strong></label><Br />
<input type="text" size="100" value="<?php echo get_option($key); ?>" name="<?php echo $key; ?>"/>
</p>

<?php endforeach; ?>

<?php wp_editor(stripslashes(get_option('_ethymos_conteudo_rodape')), '_ethymos_conteudo_rodape'); ?>

	<input type="hidden" name="theme-options-form" value="1" />
	<input type="submit" value="Salvar" class="button button-primary"/>
</form>
</div>