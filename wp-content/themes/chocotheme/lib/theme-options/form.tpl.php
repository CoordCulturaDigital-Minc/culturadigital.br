<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2><?php echo $this->title ?></h2>
	<?php if (!empty($this->header_navigation) && count($this->header_navigation) > 1) : ?>
	<ul id="theme_head_nav">
		<?php $i = 0; foreach ($this->header_navigation as $href => $title) : ?>
			<li class="<?php echo ($i + 1 == count($this->header_navigation)) ? 'last' : '';?>">
				<?php if ((isset($_GET['view']) && $_GET['view'] == $href) || (!isset($_GET['view']) && $i == 0)) : ?>
					<strong><?php echo $title?></strong>
				<?php else : ?>
					<a href="<?php echo preg_replace('~&view\=.[^&]*~', '', $_SERVER['REQUEST_URI']) . '&view=' . $href?>"><?php echo $title?></a>
				<?php endif; ?>
			</li>
		<?php $i ++; endforeach; ?>
	</ul>
	<div class="cl">&nbsp;</div>
	<?php endif; ?>
	
	<?php if (!empty($this->tpl_vars['errors'])) : ?>
		<div id="message" class="updated below-h2" style="background-color: #ffc6c6; border-color: red;">
			<?php foreach ($this->tpl_vars['errors'] as $err) : ?>
				<p><strong><?php echo $err['label']?></strong>: <?php echo $err['error']?></p>
			<?php endforeach; ?>
		</div>
	<?php elseif (isset($this->tpl_vars['saved'])): ?>
		<div id="message" class="updated fade below-h2" style="background-color: rgb(255, 251, 204);">
			<p>Settings Saved</p>
		</div>
	<?php endif; ?>

	<?php if (!empty($this->tpl_vars["errors"])): ?>
		<ul class="">
			<?php foreach ($this->tpl_vars["errors"] as $err): ?>
				<li><?php echo $err ?></li>
			<?php endforeach ?>
		</ul>
	<?php endif ?>
	<form method="post" class="" enctype="multipart/form-data">
		<table border="0" cellspacing="0" cellpadding="0" class="form-table">
			<?php foreach ($this->options as $option): ?>
				<?php echo $option->render() ?>
			<?php endforeach ?>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="Save"></td>
			</tr>
		</table>
	</form>
</div>