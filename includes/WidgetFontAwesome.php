<?php
namespace PhpTagsObjects;

/**
 * Description of WidgetFontAwesome
 * @see http://fortawesome.github.io/Font-Awesome/
 *
 * @author pastakhov
 */
class WidgetFontAwesome extends \PhpTags\GenericWidget {

	public static function __callStatic( $name, $arguments ) {
		list ( $callType, $tmp ) = explode( '_', $name, 2 );

		if ( $callType == 'c' ) {
			$prop = str_replace( '_', '-', $tmp );
			$icon = \PhpTags\Hooks::createObject( array( $prop ), 'FontAwesomeIcon', false );
			if ( $icon !== false ) {
				return $icon;
			}
		}
		return parent::__callStatic( $name, $arguments );
	}

}
