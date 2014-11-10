/* global window, mw, $ */
if(window.mw){
	var $phptagsWidgets = mw.config.get( 'ext.phptags.Widgets' );
	$.when(
		$.ready,
		mw.loader.using( $phptagsWidgets.wait )
	).done(
		function () {
			var $prefix = $phptagsWidgets.prefix;
			if ( !$phptagsWidgets.onReady ) {
				return;
			}
			for ( var $key in $phptagsWidgets.onReady ) {
				$.fn[ $phptagsWidgets.onReady[$key] ].apply( $( '.' + $prefix + $key ) , $phptagsWidgets.data[$key] );
			}
		}
	);
}
