function utcw_change() {
	  
  jQuery("div[id$='set_chooser']").addClass('utcw-hidden');
  jQuery("div[id$='span_chooser']").addClass('utcw-hidden');

	jQuery("input[id$='color_set']:checked").each(function() {  	  
	  jQuery("div[id$='set_chooser']").removeClass('utcw-hidden');
	});

	jQuery("input[id$='color_span']:checked").each(function() {
	  jQuery("div[id$='span_chooser']").removeClass('utcw-hidden');
	});	  
}
