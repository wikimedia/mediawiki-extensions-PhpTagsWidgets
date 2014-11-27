/* global window, mw, $ */
if(window.mw){
	var mwPhpTagsWidgetsDoIt = function ( $fn ) {
		for ( var $k in $fn ) {
			if ( $fn.hasOwnProperty($k) ) {
				$.fn[ $fn[$k] ].apply( $( '.' + $prefix + $k ) , $phptagsWidgets.data[$k] );
			}
		}
	},
			$phptagsWidgets = mw.config.get( 'ext.phptags.Widgets' ),
			$prefix = $phptagsWidgets.prefix,
			$whenReady = $phptagsWidgets.whenReady;

	if ( $whenReady ) {
		for ( var $key in $whenReady ) {
			if ( $whenReady.hasOwnProperty($key) ) {
				$.when(
					$whenReady[$key].fn,
					$.ready,
					mw.loader.using( $whenReady[$key].modules )
				).done(
					mwPhpTagsWidgetsDoIt
				);
			}
		}
	}
	
	// The code below is needed only for compatibility with the previous version,
	// Without it pages from the cache will be broken :-(
	if ( $phptagsWidgets.wait && $phptagsWidgets.onReady ) {
		$.when(
			$.ready,
			mw.loader.using( $phptagsWidgets.wait )
		).done(
			function () {
				for ( var $key in $phptagsWidgets.onReady ) {
					if ( $phptagsWidgets.onReady.hasOwnProperty($key) ) {
						$.fn[ $phptagsWidgets.onReady[$key] ].apply( $( '.' + $prefix + $key ) , $phptagsWidgets.data[$key] );
					}
				}
			}
		);
	}
}
