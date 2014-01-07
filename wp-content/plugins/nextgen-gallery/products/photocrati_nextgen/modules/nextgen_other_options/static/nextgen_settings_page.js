jQuery(function($){

    $('select.select2').select2();
    $('label.tooltip, span.tooltip').tooltip();

	/**** LIGHTBOX EFFECT TAB ****/

    $('tbody#lightbox_library_' + $('#lightbox_library').find(':selected').data('library-name') + '_basic').removeClass('hidden');

    // Only show the currently selected library; hide the rest
    $('#lightbox_library').change(function() {
        var name = $(this).find(':selected').data('library-name');
        if ($('#lightbox_library_advanced_toggle').data('currently-hidden') == false) {
            $('tbody.lightbox_library_advanced_settings').addClass('hidden');
            $('tbody#lightbox_library_' + name + '_advanced').removeClass('hidden');
        }

        $('tbody.lightbox_library_settings').addClass('hidden');
        $('tbody#lightbox_library_' + name + '_basic').removeClass('hidden');

	}).change();

    // "Show Advanced Settings" toggle; only display the currently selected library
    $('#lightbox_library_advanced_toggle').bind('click', function(event) {
        event.preventDefault();
        if ($(this).data('currently-hidden') == true) {
            var name = $('#lightbox_library').find(':selected').data('library-name');
            $('tbody#lightbox_library_' + name + '_advanced').removeClass('hidden');
            $(this).data('currently-hidden', false);

        } else if($(this).data('currently-hidden') == false) {
            $('tbody.lightbox_library_advanced_settings').addClass('hidden');
            $(this).data('currently-hidden', true);
        }
    });

	/**** WATERMARK TAB ****/

	// Configure the watermark customization link
	$('#watermark_customization').attr('rel', 'watermark_'+$('#watermark_source').val()+'_source');

	// Configure the button to switch from watermark text to image
	$('#watermark_source').change(function(){
		$('#'+$('#watermark_customization').attr('rel')).css('display', '').addClass('hidden');
		if (!$('#'+$(this).val()).hasClass('hidden')) {
			$('#'+$(this).val()).removeClass('hidden');
		}
		$('#watermark_customization').attr('rel', 'watermark_'+$('#watermark_source').val()+'_source').click();
	});

    // Don't show any Watermark fields unless Watermarks are enabled
    $('#watermark_source').change(function(){
        var value = $(this).val();

        $('.watermark_field').each(function(){
            if (value == 0) {
                $(this).fadeOut().addClass('hidden');
            }
            else {
                $(this).fadeIn().removeClass('hidden');
            }
        });
    }).change();


    // sends the current settings to a special ajax endpoint which saves them, regenerates the url, and then reverts
    // to the old settings. this submits the form and forces a refresh of the image through the time parameter
    $('#nextgen_settings_preview_refresh').click(function(event) {
        event.preventDefault();

        var form = $(this).parents('form:first');
        var self = $(this);
        var orig_html = $(self).html();

        $(self).attr('disabled', 'disabled').html('Processing...');
        $('body').css('cursor', 'wait');

        $.ajax({
            type: form.attr('method'),
            url: $(this).data('refresh-url'),
            data: form.serialize()+"&action=get_watermark_preview_url",
            dataType: 'json',
            success: function(data) {
                var img = self.prev();
                var src = data.thumbnail_url;
                queryPos = src.indexOf('?');
                if (queryPos != -1) {
                    src = src.substring(0, queryPos);
                }

                img.attr('src', src + '?' + new Date().getTime());
                $(self).removeAttr('disabled').html(orig_html);
                $('body').css('cursor', 'default');
            },
            error: function(xob, err, code) {        
                $(self).removeAttr('disabled').html(orig_html);
                $('body').css('cursor', 'default');
            }
        });
        
        return false;
    });
	/**** STYLES TAB ****/

	// When the selected stylesheet changes, fetch it's contents
	$('#activated_stylesheet').change(function(){
		var selected = $(this).find(':selected');
		var data = {
			action:		'get_stylesheet_contents',
			cssfile:	selected.val()
		};
		$.post(photocrati_ajax.url, data, function(res) {
			if (typeof res !== 'object') res = JSON.parse(res);
			$('#cssfile_contents').val(res.error ? res.error : res.contents);
			var status = $('#writable_identicator');
			if (res.writable) status.text(status.attr('writable_label')+' '+res.writepath);
			else status.text(status.attr('readonly_label'));
		});
	}).change();
});
