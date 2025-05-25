/**
 * @param $
 * @param mw
 * @param vg
 */
( function ( $, mw, vg ) {
	'use strict';

	$.fn.extpwVega = function ( $data ) {
		vg.config.safeMode = true;
		vg.config.domainWhiteList = mw.config.get( 'ext.phptags.Widgets' ).vega.domainWhiteList;
		this.each( ( index, element ) => {
			vg.parse.spec( $data, ( chart ) => {
				chart( { el: element } ).update();
			} );
		} );
	};

}( jQuery, mediaWiki, vg ) );
