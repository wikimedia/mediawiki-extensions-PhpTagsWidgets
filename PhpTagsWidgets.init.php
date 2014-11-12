<?php

class PhpTagsWidgetsInit {

	public static function initializeRuntime() {
		\PhpTags\Hooks::setObjects(
				array(
					'Slick' => 'WidgetSlick',
					'FontAwesome' => 'WidgetFontAwesome',
					'FA' => 'WidgetFontAwesome', // alias of FontAwesome
					'FontAwesomeIcon' => 'WidgetFontAwesomeIcon',
				)
			);
		\PhpTags\Hooks::setFunctions( 'PhpTagsWidgetsFunc', self::getFunctions() );
		return true;
	}

	public static function getFunctions() {
		return array(
			'fa', // alias of FontAwesome
			'fontawesome',
			'slick',
		);
	}

}
