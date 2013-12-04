/*
 * cdigital_bar.js
 *
 * Copyright (C) 2010  Marcos Maia Lopes <marcosmlopes01@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

$j = jQuery;

$j(function() {
    // Add suport to placeholder attribute on older browsers
    if( !supports_input_placeholder() ) {
        $j('*[placeholder]').each(function() {
            $j(this).val($j(this).attr('placeholder'));
        });

        $j('*[placeholder]').resetDefaultValue();
        $j('*[placeholder]').parents('form').bind('submit', function() {
            $('*[placeholder]', $j(this)).each(function() {
                if( $j(this).val() == $j(this).attr('placeholder') )
                    $j(this).val('');
            });
        });
    }

    // Handle header login button action
    $j('.login a, .search-which > span').click(function(e) {
        var $parent = $j(this).parent();
        e.preventDefault();
        $parent.toggleClass('active');
        $j('input[type=text]', $parent).focus();        
        $j('.window', $parent).mouseup(function() {
            return false;
        });
        $j(document).unbind().bind('mouseup focusin', function(e) {
            if( $j(e.target).parents('.window').length == 0 ) {
                $parent.removeClass('active');
                $j(document).unbind();
            }
        });
    });

    // Handle new-activity button action
    $j('.nav-bar .new-activity').click(function() {
        if( $j('.modal.activity').length == 0 ) {
            var target = 'blah';
            var nav = $j('<div class="modal-nav clearfix">')
                .append('<div class="handle"><ul class="tabs left"><li class="active"><a href="#new-activity">Postar atualização</a></li></ul></div>'
                + '<a href="#" class="close right">Close</a>');
            var modal = $j('<div class="modal activity">')
                .append(nav)
                .append('<div id="new-activity"></div>');
                
            var form = $j('.nav-bar form#whats-new-form').clone();
            $j('#new-activity', modal).append(form.show());
            $j('body').prepend(modal);
            $j('.modal.activity #activity-text').focus();
            $j('.modal.activity').draggable({
                handle: '.handle'
            });

            $j('.modal.activity .close').live('click', function() {
                $j('.modal.activity').remove();
                return false;
            });

            var value = $j('.modal.activity option:selected').text();
            $j('.modal.activity span').text(value);
            $j('.modal.activity select').change(function() {
                value = $j('.modal.activity option:selected').text();
                $j('span', $j(this).parent()).text(value);
            });
        } else {
            if($j('.modal').is(':hidden')) {
                $j('.modal.activity').show();
                $j('.modal.activity textarea').focus();
            } else {
                $j('.modal.activity').hide();
            }
        }
	
        return false;
    });

    // Validate and send new activity form
    $j('.modal.activity #new-activity form button').live('click', function(e) {
        e.preventDefault();
        
        var $this = $j(this);
        var form = $j(this).parents('form');
        var button = $j(this);
        
        $this.addClass('loading');
        
        /* Default POST values */
        var object = '';
        var item_id = $j("#whats-new-post-in", form).val();
        var content = $j('#activity-text', form);
        var wpnonce = $j("input#_wpnonce_post_update", form).val()
        
        content.attr('disabled', 'disabled');
        button.attr('disabled', 'disabled');
        
        /* Set object for non-profile posts */
        if(item_id > 0) {
          object = $j("#whats-new-post-object").val();
        }
        
        $j.ajax({
            url: 'http://culturadigital.br/wp-load.php',
            type: 'POST',
            data: {
              'action': 'post_update',
              '_wpnonce_post_update': wpnonce,
              'content': content.val(),
              'object': object,
              'item_id': item_id
            },
            success: function(response) {
              $this.removeClass('loading');
              
              content.attr('disabled', '');
              button.attr('disabled', '');
              
              /* Check for errors and append if found. */
              if(response[0] + response[1] == '-1') {
                $j('.modal.activity').remove();
                $j('.nav-bar .middle .right').append(response.substr(2, response.length));
                
               setTimeout('$j(".nav-bar #message").fadeOut("fast").remove()', 4000);
              } else {
                $j('.modal.activity').remove();
                $j('.nav-bar .middle .right').append('<div id="message" class="success">Atividade postada.</div>');
                
                setTimeout('$j(".nav-bar #message").fadeOut("fast").remove()', 4000);
              }
            }
        });
    });

    // Escape Key Press for cancelling comment forms
    // Author: buddypress
    $j(document).keydown( function(e) {
        e = e || window.event;
        if (e.target)
            element = e.target;
        else if (e.srcElement)
            element = e.srcElement;

        if( element.nodeType == 3)
            element = element.parentNode;

        if( e.ctrlKey == true || e.altKey == true || e.metaKey == true )
            return;

        var keyCode = (e.keyCode) ? e.keyCode : e.which;

        if ( keyCode == 27 ) {
            if (element.tagName == 'INPUT') {
                if ( $j(element).attr('id') == 'activity-text' )
                    $j('.modal.activity').remove();
            }
        }
    });
});

/**
 * jQuery resetDefaultValue plugin
 * @version 0.9.1
 * @author Leandro Vieira Pinho 
 */
jQuery.fn.resetDefaultValue = function() {
    function _clearDefaultValue() {
        var _$ = $j(this);
        if ( _$.val() == _$.attr('placeholder') ) {
            _$.val('');
        }
    };
    function _resetDefaultValue() {
        var _$ = $j(this);
        if ( _$.val() == '' ) {
            _$.val(_$.attr('placeholder'));
        }
    };
    return this.click(_clearDefaultValue).focus(_clearDefaultValue)
    .blur(_resetDefaultValue);
}

/**
 * Verify if browser supports placeholder attr 
 */
function supports_input_placeholder() {
    var i = document.createElement('input');
    return 'placeholder' in i;
}
