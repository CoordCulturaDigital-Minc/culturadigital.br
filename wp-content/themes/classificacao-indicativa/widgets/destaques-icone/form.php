<label for="<?php echo $this->get_field_id('modelo'); ?>">
	<?php echo _x('Modelo', 'widget-destaque', 'classificacao-indicativa'); ?>
</label>

<select name="<?php echo $this->get_field_name('modelo'); ?>" id="<?php echo $this->get_field_id('modelo'); ?>">
	<option <?php echo ($instance['modelo'] == 'azul') ? 'selected="selected"' : ''; ?>  value="azul"><?php echo _x('Azul', 'widget-destaque', 'classificacao-indicativa'); ?></option>
	<option <?php echo ($instance['modelo'] == 'verde') ? 'selected="selected"' : ''; ?>  value="verde"><?php echo _x('Verde', 'widget-destaque', 'classicacao-indicativa'); ?></option>
	<option <?php echo ($instance['modelo'] == 'vermelho') ? 'selected="selected"' : ''; ?>  value="vermelho"><?php echo _x('Vermelho', 'widget-destaque', 'classificacao-indicativa'); ?></option>
</select>

<p>
	<label for=""><?php echo _x('Título', 'widget-destaque', 'classificacao-indicativa'); ?></label>
	<input type="text" name="<?php echo $this->get_field_name('titulo'); ?>" value="<?php echo ($instance['titulo']) ? $instance['titulo'] : ''; ?>"/>
</p>

<p>
	<label for=""><?php echo _x('Link', 'widget-destaque', 'classificacao-indicativa'); ?></label>
	<input type="text" name="<?php echo $this->get_field_name('link'); ?>" value="<?php echo ($instance['link']) ? $instance['link'] : ''; ?>"/>
</p>

<p>
	<label for=""><?php echo _x('Descrição', 'widget-destaque', 'classificacao-indicativa'); ?></label>
	<textarea style="width: 230px;" name="<?php echo $this->get_field_name('descricao'); ?>" id="" cols="30" rows="10"><?php echo ($instance['descricao']) ? $instance['descricao'] : ''; ?></textarea>
</p>