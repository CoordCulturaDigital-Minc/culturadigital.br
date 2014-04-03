jQuery(document).ready(function($){
        $('.meta-box-sortables .head-wrap .hndle').click(function(){
                $(this).parent().next().toggle();
                return false;
        }).parent().next().hide();

        // Toggle all
        $('.toggle-all').click(function(){
                $('.meta-box-sortables .head-wrap').next().toggle();
                return false;
        })

        // Show/Hide the slider_type specific options
        $('input[name="graphene_settings\\[slider_type\\]"]').change(function(){
                $('[class*="row_slider_type"]').hide();
                $('.row_slider_type_'+$(this).val()).fadeIn();
        });

        // Show/Hide home page panes specific options
        $('input[name="graphene_settings\\[show_post_type\\]"]').change(function(){
                $('[id*="row_show_post_type"]').hide();
                $('#row_show_post_type_'+$(this).val()).fadeIn();
                if ($(this).val()=='cat-latest-posts'){
                        $('#row_show_post_type_latest-posts').fadeIn();
                }
        });

        // To hide/show complete section
        $('input[data-toggleOptions]').change(function(){
                var target = $(this).attr('rel');
                if ( target )
                        $('.'+target).fadeToggle();
                else
                        $(this).closest('table').next().fadeToggle();
        });

        // To Show/Hide the widget hooks
        $('a.toggle-widget-hooks').click(function(){
                $(this).closest('li').find('li.widget-hooks').fadeToggle();
                return false;
        });
		
		// Select all
		$('.select-all').click(function(){
			grapheneSelectText( $(this).prop('rel') );
			return false;
		});
		
		// Display spinning wheel when options form is submitted
		$('.left-wrap input[type="submit"]').click(function(){
			ajaxload = '<img src="' + graphene_uri + '/images/ajax-loader.gif" alt="Working..." class="ajaxload" />';
			if ( $(this).parents('.panel-wrap').length > 0 )
				$(this).parent().prepend(ajaxload);
			else
				$(this).parent().append(ajaxload);
		});
		$('<img/>')[0].src = graphene_uri + '/images/ajax-loader.gif';
		
		// Save options via AJAX
		$('#graphene-options-form').submit(function(){
			
			var data = $(this).serialize();
			data = data.replace('action=update', 'action=graphene_ajax_update');
			
			$.post(ajaxurl, data, function(response) {
				$('.ajaxload').remove();
				graphene_show_message(response);
				
				if ( response.search('error') == -1 ) t = 1000
				else t = 4000;
				t = setTimeout('graphene_fade_message()', t);
			});
			
			return false;
		});
				
		/* Improve <select> elements */
		if (grapheneAdminScript.is_rtl == false ){
			$('.chzn-select').each(function(){
				var chosenOptions = new Object();
				chosenOptions.disable_search_threshold = 10;
				chosenOptions.allow_single_deselect = true;
				chosenOptions.no_results_text = grapheneAdminScript.chosen_no_search_result;
				if ($(this).attr('multiple')) chosenOptions.width = '100%';
				else chosenOptions.width = '250px';
				
				$(this).chosen(chosenOptions);
			});
		}

        
        // Non-essential options display settings
        /* Disabled for now, until proper API is implemented for feature pointers in WordPress core
        var nonEssentialOptions = grapheneGetCookie('graphene-neod'); // neod = Non Essential Options Display
        if (nonEssentialOptions == 'true'){
                $('.non-essential-option, .toggle-essential-options, .nav-tab-advanced').show();
                $('.toggle-all-options').hide();
        } else {
                $('.non-essential-option, .toggle-essential-options, .nav-tab-advanced').hide();
                $('.toggle-all-options').show();
        }

        $('.toggle-essential-options').click(function(){
                grapheneSetCookie('graphene-neod', false, 100);
                $('.non-essential-option, .toggle-essential-options, .nav-tab-advanced').hide();
                $('.toggle-all-options').show();
                return false;
        });
        $('.toggle-all-options').click(function(){
                grapheneSetCookie('graphene-neod', true, 100);
                $('.non-essential-option, .toggle-essential-options, .nav-tab-advanced').show();
                $('.toggle-all-options').hide();
                return false;
        });
        */
       
        // Remember the opened options panes
        $('.meta-box-sortables .head-wrap .hndle, .toggle-all').click(function(){
                var postboxes = $('.left-wrap .postbox');
                var openboxes = new Array();
                $('.left-wrap .panel-wrap:visible').each(function(index){   
                        var openbox = $(this).parents('.postbox');
                        openboxes.push(postboxes.index(openbox));                        
                });                    
                grapheneSetCookie('graphene-tab-'+graphene_tab+'-boxes', openboxes.join(','), 100);                    
        });

        // reopen the previous options panes
        var oldopenboxes = grapheneGetCookie('graphene-tab-'+graphene_tab+'-boxes');                
        if (oldopenboxes != null && oldopenboxes != '') {
                var boxindexes = oldopenboxes.split(',');                    
                for (var boxindex in boxindexes){                            
                        $('.left-wrap .postbox:eq('+boxindexes[boxindex]+')').children('.panel-wrap').show();
                }
        }
        
        
        // To support the Media Uploader/Gallery picker in the theme options
        var uploadparent = 0;
        var old_send_to_editor = window.send_to_editor;
        var old_tb_remove = window.tb_remove;

        $('.upload_image_button').click(function(){
            uploadparent = jQuery(this).closest('td');
            tb_show('', 'media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true');
            return false;
        });

        window.tb_remove = function() {
            uploadparent = 0;
            old_tb_remove();
        }

        window.send_to_editor = function(html) {
            if(uploadparent){              
                imgurl = jQuery('img',html).attr('src');
                uploadparent.find('input[type="text"]').attr('value', imgurl);
                tb_remove();
            } else {
                old_send_to_editor();
            }
        }
        
                
        /* Reordering and admin for the social profile links */
        if (graphene_tab == 'general'){
							
			$('#socialprofile-sortable').sortable({ items: '.socialprofile-table', placeholder: 'socialprofile-dragging', opacity: .8 });
			function delete_socialprofile() {
				$(this).closest('table').remove();
				return false;
			}
			$('.socialprofile-del').bind('click', delete_socialprofile);                
			$('#new-socialprofile-type').change(function(){                    
				if ($('#new-socialprofile-type').val() != 'custom') {
					$('#new-socialprofile-iconurl').closest('tr').hide(); // the the custom icon url input
					$('#new-socialprofile-title').val($('#new-socialprofile-type option').filter(":selected").text()); // prefill the title for the user                        
				} else {
					$('#new-socialprofile-iconurl').closest('tr').show();
				}
				if ($('#new-socialprofile-type').val() != 'rss') { $('#new-socialprofile-url-description').hide(); } 
				else { $('#new-socialprofile-url-description').show(); }
			});
			$('#new-socialprofile-add').click(function(){
					var spData = $('#new-socialprofile-data').data();
					var $spType = $('#new-socialprofile-type');
					var $spName = $('#new-socialprofile-type option').filter(":selected").html();
					var $spTitle = $('#new-socialprofile-title');
					var $spUrl = $('#new-socialprofile-url');
					var $spIconUrl = $('#new-socialprofile-iconurl');
					if ($spType.val() == '-') { $spType.focus(); }
					else if (!$spTitle.val()) { $spTitle.focus(); }
					else if ($spType.val() != 'rss' && !$spUrl.val()) { $spUrl.focus(); }
					else if ($spType.val() == 'custom' && !$spIconUrl.val()){ $spIconUrl.focus(); }
					else {
							var ix = $('#socialprofile-next-index').val();

							var i18n_title = $spName;
							var rowspan = 2;
							if ( $spType.val() != 'custom' )
									var icon_url = spData.iconUrl + $spType.val() + '.png';
							else
									var icon_url = $spIconUrl.val();
							var icon = '<div class="mysocial social-'+$spType.val()+'"><img class="mysocial-icon" src="' + icon_url + '" alt="" /></div>';
							var extraCustom = '';
							if ($spType.val() == 'rss'){
									extraCustom = '<br /><span class="description">'+ $('#new-socialprofile-url-description').text() + '</span>';
							}
							else if ($spType.val() == spData.customTitle){
									rowspan = 3;
									icon = '<img class="mysocial-icon" src="'+$spIconUrl.val()+' " />';
									extraCustom = '\
													</td>\
											</tr>\
											<tr>\
													<th class="small-row">'+spData.textIconUrl+'</th>\
													<td><input type="text" name="graphene_settings[social_profiles]['+ix+'][icon_url]" value="'+$spIconUrl.val()+'" class="widefat code" />';
							}

							$('#socialprofile-sortable').append(
									'<table class="form-table socialprofile-table">\
											<tr>\
													<th scope="row" rowspan="'+rowspan+'" class="small-row">\
															'+i18n_title+'<br />\
															<input type="hidden" name="graphene_settings[social_profiles]['+ix+'][type]" value="'+$spType.val()+'" />\
															<input type="hidden" name="graphene_settings[social_profiles]['+ix+'][name]" value="'+$spName+'" />\
															'+icon+'\
													</th>\
													<th class="small-row">'+spData.textTitleAttr+'</th>\
													<td><input type="text" name="graphene_settings[social_profiles]['+ix+'][title]" value="'+$spTitle.val()+'"  class="widefat code"/>\
											</tr>\
											<tr>\
													<th class="small-row">'+spData.textUrl+'</th>\
													<td>\
															<input type="text" name="graphene_settings[social_profiles]['+ix+'][url]" value="'+$spUrl.val()+'" class="widefat code" />\
															'+extraCustom+'\
															<br /><span class="delete"><a href="#" class="socialprofile-del">'+spData.textDelete+'</a></span>\
													</td>\
											</tr>\
									</table>'
							);

							// reset the new form
							$('#socialprofile-next-index').val(ix+1);
							$('option:first', $spType).attr('selected', 'selected');
							$spTitle.val('');
							$spUrl.val('');
							$spIconUrl.val('').closest('tr').hide();
							// rebind the del click event
							$('.socialprofile-del').unbind('click');
							$('.socialprofile-del').bind('click', delete_socialprofile);                        
					}
					return false;
			});
        } // end of graphene_tab 'general'
                               		
        /* jQuery UI Slider for the column widths options */
        if ( graphene_tab == 'display' ) {
                                              		
			var gutter = graphene_settings.gutter_width;
			var grid_width = parseFloat( graphene_settings.grid_width );
			var container_width = parseFloat( graphene_settings.container_width );
			var container = container_width - gutter * 2;
			var content_2col = parseFloat( graphene_settings.column_width.two_col.content );
			var sidebar_left_3col = parseFloat( graphene_settings.column_width.three_col.sidebar_left );
			var sidebar_right_3col = parseFloat( graphene_settings.column_width.three_col.sidebar_right );

			/* Container */
			$( '#container_width-slider' ).slider({
				min: 800,
				max: 1400,
				step: 5,
				value: container_width,
				slide: function( event, ui ) {
					$( '#container_width' ).val( ui.value );
					container_width = ui.value;
					$( '.column_width-max-legend' ).html( ui.value + ' px' );
					grid_width = (ui.value - gutter * 32) / 16;
					$( '#grid_width' ).val( grid_width );
					container = container_width - gutter * 2;

					sidebar_2col = Math.round( grid_width * 5 + gutter * 8 );
					sidebar_3col = Math.round( grid_width * 4 + gutter * 6 );

					/* Update the two-column width settings */
					$( "#column_width_2col-slider" ).slider( "option", "max", container - gutter );
					$( "#column_width_2col-slider" ).slider( "option", "value", container - sidebar_2col - gutter );
					$( "#column_width_2col_sidebar" ).val( sidebar_2col );
					$( "#column_width_2col_content" ).val( container - sidebar_2col - gutter * 2 );

					/* Update the three-column width settings */
					$( "#column_width-slider" ).slider( "option", "max", ui.value - gutter * 2 );
					$( "#column_width-slider" ).slider( "option", "values", [ sidebar_3col, ui.value - sidebar_3col - gutter * 2] );
					$( "#column_width_sidebar_left" ).val( sidebar_3col );
					$( "#column_width_sidebar_right" ).val( sidebar_3col );
					$( "#column_width_content" ).val( container - 2*sidebar_3col - gutter * 4 );
				}	
			});

			/* Two-column mode */
			$( '#column_width_2col-slider' ).slider({
				min: gutter,
				max: container - gutter,
				value: content_2col + gutter,
				step: 5,
				slide: function( event, ui ) {
					sidebar_2col = container - ui.value - gutter;
					content_2col = ui.value - gutter;

					$( "#column_width_2col_sidebar" ).val( sidebar_2col );
					$( "#column_width_2col_content" ).val( content_2col );
				}
			});

			/* Three-column mode */
			$( '#column_width-slider' ).slider({
				range: true,
				min: 0,
				max: container,
				values: [ sidebar_left_3col, container - sidebar_right_3col ],
				step: 5,
				slide: function( event, ui ) {
					sidebar_left = ui.values[0];
					sidebar_right = container - ui.values[1];
					content = container - sidebar_left - sidebar_right - gutter * 4;

					$( "#column_width_sidebar_left" ).val( sidebar_left );
					$( "#column_width_sidebar_right" ).val( sidebar_right );
					$( "#column_width_content" ).val( content );
				}
			});

        } // end of graphene_tab 'display'
		
		
		/* Colour options */
		if ( graphene_tab == 'colours' ) {
			
			// Save/delete colour presets
			$('.save-colour-preset, .delete-colour-preset').click(function(){
				
				var colourPreset = new Object();
				
				if( $(this).attr('data-colourPresetAction') == 'save' ){
					var presetName = ''
					while ( presetName == '' ) {
						presetName = prompt( grapheneAdminScript.preset_name );
						if ( presetName == null ) return false;
						if ( presetName == '' ) alert( grapheneAdminScript.preset_name_req );
					}
					colourPreset.action = 'save';
					colourPreset.preset = presetName;
				}
				
				if( $(this).attr('data-colourPresetAction') == 'delete' ){
					var currentPreset = $('.colour-presets').val();
					if ( confirm( grapheneAdminScript.preset_delete_confirm + ' ' + $('.colour-presets option:selected').text() ) == false ) return false;
					
					colourPreset.action = 'delete';
					colourPreset.preset = currentPreset;
				}
				
				/* Save/delete the preset via AJAX */
				var data = $('#graphene-options-form').clone()
								.prepend('<input name="colour_preset_action" value="' + colourPreset.action + '" />')
								.prepend('<input name="colour_preset_name" value="' + colourPreset.preset + '" />')
								.serialize();
				
				data = data.replace('action=update', 'action=graphene_ajax_update_preset');
				
				$.post(ajaxurl, data, function(response) {
					$('.ajaxload').remove();
					graphene_show_message(response);
					
					if ( response.search('error') == -1 ) {
						t = 1000;
						if ( colourPreset.action == 'delete' ) {
							$('.colour-presets option[value="' + currentPreset + '"]').remove();
							$('.colour-preset-' + currentPreset).remove();
							delete graphene_colour_presets.currentPreset;
						}
						if ( colourPreset.action == 'save' ) location.reload(true);
					} else t = 4000;
					t = setTimeout('graphene_fade_message()', t);
				});
				
				return false;
			});
			
			/* Farbtastic colour picker */
			$('div.colorpicker').each(function(){
				var $this = $(this);
				$this.hide();                    
				$this.farbtastic($this.siblings('input.color'));
			});                    
			$('input.color')
				.focusin(function(){ $(this).siblings('div.colorpicker').show(); })
				.focusout(function(){ $(this).siblings('div.colorpicker').hide(); });    
			
			$('.clear-color').click(function(){
				$(this).siblings('input.color').attr('value', '').removeAttr('style');
				return false;
			});
			
			// Top bar preview
			$('#top_bar_top_bg, #top_bar_bottom_bg, #top_bar_border, #top_bar_header_border, #picker_top_bar_top_bg div, #picker_top_bar_bottom_bg div, #picker_top_bar_border div, #picker_top_bar_header_border div').bind('mouseup keyup', function(){
					var bgTop = $.farbtastic('#picker_top_bar_top_bg').color;
					var bgBottom = $.farbtastic('#picker_top_bar_bottom_bg').color;
					var border = $.farbtastic('#picker_top_bar_border').color;
					var headerBorder = $.farbtastic('#picker_top_bar_header_border').color;
					$('.top-bar-preview .top-bar').attr('style', '\
							background: ' + bgTop + ';\
							background: -moz-linear-gradient(' + bgTop + ', ' + bgBottom + ');\
							background: -webkit-linear-gradient(' + bgTop + ', ' + bgBottom + ');\
							background: linear-gradient(' + bgTop + ', ' + bgBottom + ');\
							border-color: ' + border + ';\
					');
					$('.top-bar-preview .header').attr('style', 'border-color: ' + headerBorder + ';');
			});
			
			// Primary menu preview (top level)
			$('#menu_primary_top_bg, #menu_primary_bottom_bg, #menu_primary_border, #menu_primary_item, #menu_primary_description, #picker_menu_primary_top_bg div, #picker_menu_primary_bottom_bg div, #picker_menu_primary_border div, #picker_menu_primary_item div, #picker_menu_primary_description div').bind('mouseup keyup', function(){
					var bgTop = $.farbtastic('#picker_menu_primary_top_bg').color;
					var bgBottom = $.farbtastic('#picker_menu_primary_bottom_bg').color;
					var menuBorder = $.farbtastic('#picker_menu_primary_border').color;
					var menuItem = $.farbtastic('#picker_menu_primary_item').color;
					var menuDesc = $.farbtastic('#picker_menu_primary_description').color;
					$('#header-menu-wrap').attr('style', '\
							background: ' + bgTop + ';\
							background: -moz-linear-gradient(' + bgTop + ', ' + bgBottom + ');\
							background: -webkit-linear-gradient(' + bgTop + ', ' + bgBottom + ');\
							background: linear-gradient(' + bgTop + ', ' + bgBottom + ');\
							border-color: ' + menuBorder + ';\
					');
					$('#header-menu .normal-menu-item a').attr('style', 'color: ' + menuItem + ';');
					$('#header-menu .normal-menu-item a span').attr('style', 'color: ' + menuDesc + ';');
			});
			
			$('#menu_primary_active_top_bg, #menu_primary_active_bottom_bg, #menu_primary_active_item, #menu_primary_active_description, #picker_menu_primary_active_top_bg div, #picker_menu_primary_active_bottom_bg div, #picker_menu_primary_active_item div, #picker_menu_primary_active_description div').bind('mouseup keyup', function(){
					var bgTop = $.farbtastic('#picker_menu_primary_active_top_bg').color;
					var bgBottom = $.farbtastic('#picker_menu_primary_active_bottom_bg').color;
					var menuItem = $.farbtastic('#picker_menu_primary_active_item').color;
					var menuDesc = $.farbtastic('#picker_menu_primary_active_description').color;
					
					$('#header-menu .current-menu-item').attr('style', '\
							background: ' + bgTop + ';\
							background: -moz-linear-gradient(' + bgTop + ', ' + bgBottom + ');\
							background: -webkit-linear-gradient(' + bgTop + ', ' + bgBottom + ');\
							background: linear-gradient(' + bgTop + ', ' + bgBottom + ');\
					');
					$('#header-menu .current-menu-item a').attr('style', 'color: ' + menuItem + ';');
					$('#header-menu .current-menu-item a span').attr('style', 'color: ' + menuDesc + ';');
			});
			
			
			// Primary menu preview (sub-level)
			$('#menu_primary_dd_top_bg, #menu_primary_dd_bottom_bg, #menu_primary_dd_item, #menu_primary_dd_shadow, #picker_menu_primary_dd_top_bg div, #picker_menu_primary_dd_bottom_bg div, #picker_menu_primary_dd_item div, #picker_menu_primary_dd_shadow div').bind('mouseup keyup', function(){
					var bgTop = $.farbtastic('#picker_menu_primary_dd_top_bg').color;
					var bgBottom = $.farbtastic('#picker_menu_primary_dd_bottom_bg').color;
					var menuItem = $.farbtastic('#picker_menu_primary_dd_item').color;
					var shadow = $.farbtastic('#picker_menu_primary_dd_shadow').color;
					
					$('.primary-menu-preview .sub-menu .normal-menu-item').attr('style', '\
							background: ' + bgTop + ';\
							background: -moz-linear-gradient(' + bgTop + ', ' + bgBottom + ');\
							background: -webkit-linear-gradient(' + bgTop + ', ' + bgBottom + ');\
							background: linear-gradient(' + bgTop + ', ' + bgBottom + ');\
					');
					$('.primary-menu-preview .sub-menu .normal-menu-item a').attr('style', 'color: ' + menuItem + ';');
					$('.primary-menu-preview .sub-menu').attr('style', 'box-shadow: 0 1px 2px ' + shadow + ';');
			});
			
			$('#menu_primary_dd_active_top_bg, #menu_primary_dd_active_bottom_bg, #menu_primary_dd_active_item, #picker_menu_primary_dd_active_top_bg div, #picker_menu_primary_dd_active_bottom_bg div, #picker_menu_primary_dd_active_item div').bind('mouseup keyup', function(){
					var bgTop = $.farbtastic('#picker_menu_primary_dd_active_top_bg').color;
					var bgBottom = $.farbtastic('#picker_menu_primary_dd_active_bottom_bg').color;
					var menuItem = $.farbtastic('#picker_menu_primary_dd_active_item').color;
					
					$('.primary-menu-preview .sub-menu .current-menu-item').attr('style', '\
							background: ' + bgTop + ';\
							background: -moz-linear-gradient(' + bgTop + ', ' + bgBottom + ');\
							background: -webkit-linear-gradient(' + bgTop + ', ' + bgBottom + ');\
							background: linear-gradient(' + bgTop + ', ' + bgBottom + ');\
					');
					$('.primary-menu-preview .sub-menu .current-menu-item a').attr('style', 'color: ' + menuItem + ';');
			});
			
			// Secondary menu preview (top level)
			$('#menu_sec_bg, #menu_sec_border, #menu_sec_item, #picker_menu_sec_bg div, #picker_menu_sec_border div, #picker_menu_sec_item div').bind('mouseup keyup', function(){
					var bg = $.farbtastic('#picker_menu_sec_bg').color;
					var menuBorder = $.farbtastic('#picker_menu_sec_border').color;
					var menuItem = $.farbtastic('#picker_menu_sec_item').color;
					$('#secondary-menu-wrap').attr('style', '\
							background: ' + bg + ';\
							border-color: ' + menuBorder + ';\
					');
					$('#secondary-menu-wrap .normal-menu-item a').attr('style', 'color: ' + menuItem + ';');
			});
			
			$('#menu_sec_active_bg, #menu_sec_active_item, #picker_menu_sec_active_bg div, #picker_menu_sec_active_item div').bind('mouseup keyup', function(){
					var bg = $.farbtastic('#picker_menu_sec_active_bg').color;
					var menuItem = $.farbtastic('#picker_menu_sec_active_item').color;
					$('#secondary-menu-wrap .current-menu-item').attr('style', 'background: ' + bg + ';');
					$('#secondary-menu-wrap .current-menu-item a').attr('style', 'color: ' + menuItem + ';');
			});
			
			// Secondary menu preview (sub-level)
			$('#menu_sec_dd_top_bg, #menu_sec_dd_bottom_bg, #menu_sec_dd_item, #menu_sec_dd_shadow, #picker_menu_sec_dd_top_bg div, #picker_menu_sec_dd_bottom_bg div, #picker_menu_sec_dd_item div, #picker_menu_sec_dd_shadow div').bind('mouseup keyup', function(){
					var bgTop = $.farbtastic('#picker_menu_sec_dd_top_bg').color;
					var bgBottom = $.farbtastic('#picker_menu_sec_dd_bottom_bg').color;
					var menuItem = $.farbtastic('#picker_menu_sec_dd_item').color;
					var shadow = $.farbtastic('#picker_menu_sec_dd_shadow').color;
					
					$('.secondary-menu-preview .sub-menu .normal-menu-item').attr('style', '\
							background: ' + bgTop + ';\
							background: -moz-linear-gradient(' + bgTop + ', ' + bgBottom + ');\
							background: -webkit-linear-gradient(' + bgTop + ', ' + bgBottom + ');\
							background: linear-gradient(' + bgTop + ', ' + bgBottom + ');\
					');
					$('.secondary-menu-preview .sub-menu .normal-menu-item a').attr('style', 'color: ' + menuItem + ';');
					$('.secondary-menu-preview .sub-menu').attr('style', 'box-shadow: 0 1px 2px ' + shadow + ';');
			});
			
			$('#menu_sec_dd_active_top_bg, #menu_sec_dd_active_bottom_bg, #menu_sec_dd_active_item, #picker_menu_sec_dd_active_top_bg div, #picker_menu_sec_dd_active_bottom_bg div, #picker_menu_sec_dd_active_item div').bind('mouseup keyup', function(){
					var bgTop = $.farbtastic('#picker_menu_sec_dd_active_top_bg').color;
					var bgBottom = $.farbtastic('#picker_menu_sec_dd_active_bottom_bg').color;
					var menuItem = $.farbtastic('#picker_menu_sec_dd_active_item').color;
					
					$('.secondary-menu-preview .sub-menu .current-menu-item').attr('style', '\
							background: ' + bgTop + ';\
							background: -moz-linear-gradient(' + bgTop + ', ' + bgBottom + ');\
							background: -webkit-linear-gradient(' + bgTop + ', ' + bgBottom + ');\
							background: linear-gradient(' + bgTop + ', ' + bgBottom + ');\
					');
					$('.secondary-menu-preview .sub-menu .current-menu-item a').attr('style', 'color: ' + menuItem + ';');
			});
			

			// The widget preview
			$('#picker_bg_widget_header_border div, #picker_bg_widget_title div, #picker_bg_widget_title_textshadow div, #picker_bg_widget_header_top div, #picker_bg_widget_header_bottom div, #bg_widget_header_border, #bg_widget_title, #bg_widget_title_textshadow, #bg_widget_header_top, #bg_widget_header_bottom').bind('mouseup keyup', function(){
					var borderColor = $.farbtastic('#picker_bg_widget_header_border').color;
					var titleColor = $.farbtastic('#picker_bg_widget_title').color;
					var shadowColor = $.farbtastic('#picker_bg_widget_title_textshadow').color;
					var topColor = $.farbtastic('#picker_bg_widget_header_top').color;
					var bottomColor = $.farbtastic('#picker_bg_widget_header_bottom').color;                        
					$('.sidebar-wrap h3').attr('style', '\
							background: ' + topColor + ';\
							background: -moz-linear-gradient(' + topColor + ', ' + bottomColor + ');\
							background: -webkit-linear-gradient(' + topColor + ', ' + bottomColor + ');\
							background: linear-gradient(' + topColor + ', ' + bottomColor + ');\
							border-color: ' + borderColor + ';\
							color: ' + titleColor + ';\
							text-shadow: 0 -1px 0 ' + shadowColor + ';\
					');
			});
			$('#picker_bg_widget_item div, #picker_bg_widget_box_shadow div, #bg_widget_item, #bg_widget_box_shadow').bind('mouseup keyup', function(){
					var bgColor = $.farbtastic('#picker_bg_widget_item').color;
					var shadowColor = $.farbtastic('#picker_bg_widget_box_shadow').color;
					$('.sidebar-wrap').attr('style', '\
							background: ' + bgColor + ';\
							-moz-box-shadow: 0 0 5px ' + shadowColor + ';\
							-webkit-box-shadow: 0 0 5px ' + shadowColor + ';\
							box-shadow: 0 0 5px ' + shadowColor + ';\
					');
			});
			$('#picker_bg_widget_list div, #bg_widget_list').bind('mouseup keyup', function(){
					$('.sidebar ul li').attr('style', 'border-color: ' + $.farbtastic('#picker_bg_widget_list').color + ';');
			});

			// The slider background preview
			$('#picker_bg_slider_top div, #picker_bg_slider_bottom div, #bg_slider_top, #bg_slider_bottom').bind('mouseup keyup', function(){
					var colorTop = $.farbtastic('#picker_bg_slider_top').color;
					var colorBottom = $.farbtastic('#picker_bg_slider_bottom').color;
					$('#grad-box').attr('style', '\
							background: ' + colorTop + ';\
							background: linear-gradient(left top, ' + colorTop + ', ' + colorBottom + ');\
							background: -moz-linear-gradient(left top, ' + colorTop + ', ' + colorBottom + ');\
							background: -webkit-linear-gradient(left top, ' + colorTop + ', ' + colorBottom + ');\
					');
			});

			// Block button preview
			$('#picker_bg_button div, #picker_bg_button_label div, #picker_bg_button_label_textshadow div, #picker_bg_button_box_shadow div, #bg_button, #bg_button_label, #bg_button_label_textshadow, #bg_button_box_shadow').bind('mouseup keyup', function(){
					var bgColor = $.farbtastic('#picker_bg_button').color;
					var textColor = $.farbtastic('#picker_bg_button_label').color;
					var textshadowColor = $.farbtastic('#picker_bg_button_label_textshadow').color;
					var boxshadowColor = $.farbtastic('#picker_bg_button_box_shadow').color;
					R = hexToR(bgColor) - 35;
					G = hexToG(bgColor) - 35;
					B = hexToB(bgColor) - 35;
					color_bottom = 'rgb(' + R + ', ' + G + ', ' + B + ')';

					$('.block-button').attr('style', '\
									background: ' + bgColor + ';\
									background: -moz-linear-gradient(' + bgColor + ', ' + color_bottom + ');\
									background: -webkit-linear-gradient(' + bgColor + ', ' + color_bottom + ');\
									background: linear-gradient(' + bgColor + ', ' + color_bottom + ');\
									border-color: ' + color_bottom + ';\
									text-shadow: 0 -1px 0 ' + textshadowColor + ';\
									color: ' + textColor + ';\
									-moz-box-shadow: 0 1px 2px ' + boxshadowColor + ';\
									-webkit-box-shadow: 0 1px 2px ' + boxshadowColor + ';\
									box-shadow: 0 1px 2px ' + boxshadowColor + ';\
					');
			});

			// Archive title preview
			$('#picker_bg_archive_left div, #picker_bg_archive_right div, #bg_archive_left, #bg_archive_right').bind('mouseup keyup', function(){
					var leftColor = $.farbtastic('#picker_bg_archive_left').color;
					var rightColor = $.farbtastic('#picker_bg_archive_right').color;
					$('.page-title').attr('style', '\
							background: ' + leftColor + ';\
							background: linear-gradient(left top, ' + leftColor + ', ' + rightColor + ');\
							background: -moz-linear-gradient(left top, ' + leftColor + ', ' + rightColor + ');\
							background: -webkit-linear-gradient(left top, ' + leftColor + ', ' + rightColor + ');\
					');
			});
			$('#picker_bg_archive_text div, #bg_archive_text').bind('mouseup keyup', function(){
					$('.page-title span').css('color', $.farbtastic('#picker_bg_archive_text').color);
			});
			$('#picker_bg_archive_label div, #bg_archive_label').bind('mouseup keyup', function(){
					$('.page-title').css('color', $.farbtastic('#picker_bg_archive_label').color);
			});
			$('#picker_bg_archive_textshadow div, #bg_archive_textshadow').bind('mouseup keyup', function(){
					$('.page-title').css('text-shadow', '0 -1px 0 ' + $.farbtastic('#picker_bg_archive_textshadow').color);
			});
			
			// Footer
			$('#footer_bg, #picker_footer_bg div').bind('mouseup keyup', function(){
				$('.graphene-footer').css('background-color', $.farbtastic('#picker_footer_bg').color );
			});
			$('#footer_heading, #picker_footer_heading div').bind('mouseup keyup', function(){
				$('.graphene-copyright .heading').css('color', $.farbtastic('#picker_footer_heading').color );
			});
			$('#footer_heading, #picker_footer_heading div').bind('mouseup keyup', function(){
				$('.graphene-copyright .heading').css('color', $.farbtastic('#picker_footer_heading').color );
			});
			$('#footer_text, #picker_footer_text div').bind('mouseup keyup', function(){
				$('.graphene-footer').css('color', $.farbtastic('#picker_footer_text').color );
			});
			$('#footer_link, #footer_submenu_text, #picker_footer_link div, #picker_footer_submenu_text div').bind('mouseup keyup', function(){
				$('.graphene-footer a, .graphene-footer a:visited').css('color', $.farbtastic('#picker_footer_link').color );
				$('.footer-menu .sub-menu li a, .footer-menu .sub-menu li a:visited').css('color', $.farbtastic('#picker_footer_submenu_text').color );
			});
			$('#footer_submenu_border, #picker_footer_submenu_border div').bind('mouseup keyup', function(){
				$('.footer-menu-wrap ul.sub-menu').css('border-color', $.farbtastic('#picker_footer_submenu_border').color );
			});
			

			// Apply colour preset                
			$('select.colour-presets').bind('keyup change', function(){                        
					var presetName = $('.colour-presets').val().replace( /-/g, '_' );
					colour_preset = $.parseJSON( graphene_colour_presets[presetName] );
					for ( var option_name in colour_preset ){
							$elm = $('#' + option_name).siblings('.colorpicker');
							$.farbtastic($elm).setColor(colour_preset[option_name]);                                
					}
					$('.colorpicker div').trigger('mouseup');
			});
			
		} // end of graphene_tab 'colours'
});


function hexToR(h) {
    if ( h.length == 4 )
        return parseInt((cutHex(h)).substring(0,1)+(cutHex(h)).substring(0,1),16);
    if ( h.length == 7 )
        return parseInt((cutHex(h)).substring(0,2),16);
}
function hexToG(h) {
    if ( h.length == 4 )
        return parseInt((cutHex(h)).substring(1,2)+(cutHex(h)).substring(1,2),16);
    if ( h.length == 7 )
        return parseInt((cutHex(h)).substring(2,4),16);
}
function hexToB(h) {
    if ( h.length == 4 )
        return parseInt((cutHex(h)).substring(2,3)+(cutHex(h)).substring(2,3),16);
    if ( h.length == 7 )
        return parseInt((cutHex(h)).substring(4,6),16);
}
function cutHex(h) {return (h.charAt(0)=="#") ? h.substring(1,7):h}

function grapheneCheckFile(f,type){
	type = (typeof type === "undefined") ? 'options' : type;
	f = f.elements;
	if (/.*\.(txt)$/.test(f['upload'].value.toLowerCase()))
	return true;
	if ( type == 'options' ) alert(grapheneAdminScript.import_select_file);
	else if ( type == 'colours' ) alert(grapheneAdminScript.preset_select_file);
	f['upload'].focus();
	return false;
};

function grapheneSetCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function grapheneGetCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function grapheneDeleteCookie(name) {
    grapheneSetCookie(name,"",-1);
}

function grapheneSelectText(element) {
	var doc = document;
	var text = doc.getElementById(element);    

	if (doc.body.createTextRange) { // ms
		var range = doc.body.createTextRange();
		range.moveToElementText(text);
		range.select();
	} else if (window.getSelection) { // moz, opera, webkit
		var selection = window.getSelection();            
		var range = doc.createRange();
		range.selectNodeContents(text);
		selection.removeAllRanges();
		selection.addRange(range);
	}
}

function graphene_show_message(response) {
	jQuery('.graphene-ajax-response').html(response).fadeIn(400);
}

function graphene_fade_message() {
	jQuery('.graphene-ajax-response').fadeOut(1000);	
	clearTimeout(t);
}