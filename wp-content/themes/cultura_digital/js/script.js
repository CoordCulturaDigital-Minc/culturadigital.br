jQuery(function(){
	
	// Droppy
	jQuery('#nav').droppy({speed: 1});
	
	// Criar Abas
	jQuery('.box1-1 .widgettitle').each(function(){
		var item = jQuery(this).html();
		var link = jQuery(this).parent().attr('id');
		
		// Remover lixo do item
		item = item.replace(/<[^>]*>/g, "");
		
		jQuery('.box1-1 ul:first').append('<li><a href="#' + link + '"><span>' + item + '</span></a></li>');
	});
	
	jQuery('.box1-2 .widgettitle').each(function(){
		var item = jQuery(this).html();
		var link = jQuery(this).parent().attr('id');
		
		// Remover lixo do item
		item = item.replace(/<[^>]*>/g, "");
		
		jQuery('.box1-2 ul:first').append('<li><a href="#' + link + '"><span>' + item + '</span></a></li>');
	});
	
	jQuery('.box2-1 .widgettitle').each(function(){
		var item = jQuery(this).html();
		var link = jQuery(this).parent().attr('id');
		
		// Remover lixo do item
		item = item.replace(/<[^>]*>/g, "");
		
		jQuery('.box2-1 ul:first').append('<li><a href="#' + link + '"><span>' + item + '</span></a></li>');
	});
	
	jQuery('.box3-1 .widgettitle').each(function(){
		var item = jQuery(this).html();
		var link = jQuery(this).parent().attr('id');
		
		// Remover lixo do item
		item = item.replace(/<[^>]*>/g, "");
		
		jQuery('.box3-1 ul:first').append('<li><a href="#' + link + '"><span>' + item + '</span></a></li>');
	});
	
	// Ativar Abas
	jQuery('.box1-1').tabs();
	jQuery('.box1-2').tabs();
	jQuery('.box2-1').tabs();
	jQuery('.box3-1').tabs();
	
	// Retirar a borda do último item de cada lista
	jQuery(".widget ul li:last-child").addClass("noborder");
	
	// Tooltip nos membros da home
	jQuery(".item-avatar a").tooltip({ 
		track: true, 
		delay: 0, 
		showURL: false, 
		showBody: " - ", 
		fade: 250 
	});

	
	// Voltar ao topo
	jQuery('.top a').click(function(){
		backtotop();
		
		return false;
	});
	
});
