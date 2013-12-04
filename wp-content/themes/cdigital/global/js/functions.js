// functions.js

jQuery(function()
{
	// Desativa sidebar sem widget
	jQuery('div.tabs').each(function()
	{
		if(!jQuery('*', this).length)
		{
			jQuery(this).hide();
		}
	});

    // Widgets in tabs
	jQuery('div.tabs').each(function()
	{
		if(jQuery(this).children().hasClass('widget'))
		jQuery('<ul class="tabs"></ul>').prependTo(jQuery(this));
	});

    jQuery('div.widget h2.widgettitle').each(function(a)
	{
		jQuery('<li class="' + a + '"></li>').appendTo(jQuery(this).parents('div.tabs').find('ul.tabs'));
		jQuery('<a href="#' + jQuery(this).parent().attr('id') + '">' + jQuery(this).text() + '</a>').appendTo(jQuery(this).parents('div.tabs').find('ul.tabs li.'+a));
		jQuery(this).remove();
	});

	jQuery('div.tabs').tabs();

	//deprecated
	//jQuery('div.hlTabs').tabs({ fx: { opacity: 'toggle' } }).tabs('rotate', 5000);

	/*** Inicio headlines ***/
	jQuery('div.hlTabs').tabs();

	// oculta todos slides menos o primeiro
	jQuery('div.hlTabs div:gt(0)').hide();

	// apartir do primeiro, até os próximos
	var duration_cycle = setInterval(function(){ jQuery('div.hlTabs div:first').hide().next().fadeIn('slow','linear').end().appendTo('div.hlTabs');}, 4000);

	// se selecionar o slide, ocultar os outros slides e parar o ciclo
	jQuery(".hlTabs a.ui-tabs-anchor").click(function(){
		jQuery('div.hlTabs div').each( function(){
			if( 'false' == jQuery(this).attr('aria-expanded'))
				jQuery(this).hide();
		});
		clearTimeout(duration_cycle);
	});
	/*** fim headlines ***/

	jQuery('div#content').fadeIn(300);

	jQuery('div.widget_bp_core_members_widget, div.widget_bp_groups_widget').append('<div class="clear"></div>');

	jQuery('div#header ul.nav li ul li:odd, div.widget_activity ul.activity-list li:odd, div.main ul.item-list li:odd, body.profile #content div.main div.profile ul.profileFields li:odd, .posts-list ul.posts li:odd').not('.load-more').addClass('odd');

	// Adciona um bullet no menu dropdown
	jQuery('div#header ul.nav li').each(function()
	{
		if(jQuery('> ul', jQuery(this)).length)
		{
			jQuery(this).addClass('dropdown');
		}
	});

	// Reset Value Default
    jQuery.fn.resetDefaultValue = function()
    {
	    function _clearDefaultValue() {
		    var _$ = jQuery(this);
		    if ( _$.val() == this.defaultValue ) { _$.val(''); jQuery(this).addClass('active'); }
	    };
	    function _resetDefaultValue() {
		    var _$ = jQuery(this);
		    if ( _$.val() == '' ) { _$.val(this.defaultValue); jQuery(this).removeClass('active'); }
	    };
	    return this.click(_clearDefaultValue).focus(_clearDefaultValue).blur(_resetDefaultValue);
    }

	jQuery('div#header input:not(input.submitDefault), div.widget_login input:not(input.submitDefault), div.comments div.inputDefault *').resetDefaultValue();

	var formWhatsNew = (jQuery('form#whats-new-form textarea').val() === undefined ? '' : jQuery('form#whats-new-form textarea').val() );

	if( formWhatsNew.indexOf('O que há de novo ') != -1 ) jQuery('form#whats-new-form textarea').resetDefaultValue();

	// fix jquery tooltip
	jQuery(".item-list, .item-list .item-list").ajaxComplete(function()
	{
		jQuery("div.widget_bp_groups_widget img.avatar, div.widget_bp_core_members_widget img.avatar").tooltip({
			delay: 0,
			track: true,
			showURL: false,
			bodyHandler: function() {
				var title = jQuery(this).parent().parent().siblings().find('div.item-title a').text();
				var membros = jQuery(this).parent().parent().siblings().find('div.item-meta span').text();
				return '<h3>'+ title +'</h3>' + '<p>' + membros + '</p>';
			}
		});
	});

	jQuery("div.widget_bp_groups_widget img.avatar, div.widget_bp_core_members_widget img.avatar").tooltip({
		delay: 0,
		track: true,
		showURL: false,
		bodyHandler: function() {
			var title = jQuery(this).parent().parent().siblings().find('div.item-title a').text();
			var membros = jQuery(this).parent().parent().siblings().find('div.item-meta span').text();
			return '<h3>'+ title +'</h3>' + '<p>' + membros + '</p>';
		}
	});

	// Ajax pagination
	jQuery('div.widget_customPosts .more').click(function()
	{
		var $this = jQuery(this);
		var url = $this.attr('href');

		$this.addClass('loading');

		jQuery('div.widget_customPosts ul').load(url + '#labblog li', function(response)
		{
			jQuery('div.widget_customPosts ul li').remove();

			jQuery(response).find('div.widget_customPosts ul li').appendTo('div.widget_customPosts ul');

			var paged = jQuery(response).find('div.widget_customPosts .more').attr('href');

			$this.attr('href', paged);

			$this.removeClass('loading');
		});

		return false;
	});

	// Widget Login Validation
	jQuery('div.widget_login .submitDefault').not('div.widget_register .submitDefault').click(function()
	{
		var $this = jQuery(this);
		var form = jQuery('div.widget_login form');
		var login = jQuery('div.widget_login div.login input').val();
		var pw = jQuery('div.widget_login div.pw input').val();
		var erro = 0;

		if(login == 'Nome do usuário' || !login || pw == 'Senha' || !pw)
		{
			if(!jQuery('div.widget_login div.error').length)
			{
				jQuery('div.widget_login').prepend('<div class="error" style="display:none">Preencha todos os campos!</div>');
				jQuery('div.widget_login div.error').slideDown('fast');
			}
			else
			{
				jQuery('div.widget_login div.error').remove();
				jQuery('div.widget_login').prepend('<div class="error" style="display:none">Preencha todos os campos!</div>');
				jQuery('div.widget_login div.error').slideDown('fast');
			}

			erro++;
		}
		else
			erro = 0;

		if(erro == 0)
		{
			$this.addClass('loading');
			jQuery.post(form.attr('action'), form.serialize(), function(response)
			{
				if(jQuery(response).find('div#login_error').length)
				{
					var erro = jQuery(response).find('div#login_error');

					if(!jQuery('div.widget_login div.error').length)
					{
						jQuery('div.widget_login').prepend('<div class="error" style="display:none">' + erro.html() + '</div>');
						jQuery('div.widget_login div.error').slideDown('fast');
						jQuery('div.widget_login div.login input').val('Nome do usuário');
						jQuery('div.widget_login div.pw input').val('Senha');
					}
					else
					{
						jQuery('div.widget_login div.error').remove();
						jQuery('div.widget_login').prepend('<div class="error" style="display:none">' + erro.html() + '</div>');
						jQuery('div.widget_login div.error').slideDown('fast');
						jQuery('div.widget_login div.login input').val('Nome do usuário');
						jQuery('div.widget_login div.pw input').val('Senha');
					}
					$this.removeClass('loading');
				}
				else
				{
					document.location.href = document.location.href;
				}
			});
		}
		//form.submit();

		return false;
	});

	jQuery('ul.activity-list, ul.item-list').ajaxComplete(function()
	{
		jQuery('div.widget_activity ul.activity-list li, div.main ul.item-list li').removeClass('odd');
		jQuery('div.widget_activity ul.activity-list li:odd, div.main ul.item-list li:odd').not('.load-more').addClass('odd');
	});
});
