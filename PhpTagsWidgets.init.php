<?php

class PhpTagsWidgetsInit {

	public static function initializeRuntime() {
		\PhpTags\Hooks::setObjects(
				array(
					'Slick' => 'WidgetSlick',
				)
			);

		return true;
	}

}
