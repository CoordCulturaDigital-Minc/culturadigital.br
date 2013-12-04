/* JS for the group wiki admin screen */

function wikiGroupAdminPageDelete( wiki_page_id ){

	jQuery.post(ajaxurl, {
		action:'bp_wiki_group_admin_page_delete',
		'cookie':encodeURIComponent(document.cookie),
		'wiki_page_id':wiki_page_id
	}, function(response) {  
		jQuery('#wiki-page-item-'+wiki_page_id).remove();
	});
}


/**
 * wikiGroupAdminPageCreate()
 *
 * Creates a new wiki page with the default group page settings
 */
function wikiGroupAdminPageCreate() {
	
	var wiki_page_title = jQuery('#wiki-page-title-create').val();
	
	jQuery('#bp-wiki-group-admin-page-create-button').removeAttr('onclick');
	
	if ( wiki_page_title ) {
	
		jQuery.post(ajaxurl, {
			action:'bp_wiki_group_admin_page_create',
			'cookie':encodeURIComponent(document.cookie),
			'wiki_page_title':wiki_page_title
		}, function(response) {  
			jQuery(response).appendTo('#bp-wiki-group-admin-pages-list');
		});
		
	}
	
	jQuery('#wiki-page-title-create').removeAttr('value');
	
	jQuery('#bp-wiki-group-admin-page-create-button').bind('click', function(){wikiGroupAdminPageCreate();return false;});

}