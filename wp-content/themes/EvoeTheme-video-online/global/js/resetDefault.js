jQuery( function()
{
	jQuery.fn.resetDefaultValue = function()
	{
		function _clearDefaultValue() {
			var _jQuery = jQuery(this);
			if ( _jQuery.val() == this.defaultValue ) { _jQuery.val('').addClass( "init" ); }
		};
		function _resetDefaultValue() {
			var _jQuery = jQuery(this);
			if ( _jQuery.val() == '' ) { _jQuery.val(this.defaultValue).removeClass( "init" ); }
		};
		return this.click(_clearDefaultValue).focus(_clearDefaultValue).blur(_resetDefaultValue)
	};
});