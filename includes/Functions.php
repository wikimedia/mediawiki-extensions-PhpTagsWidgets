<?php

class PhpTagsWidgetsFunc extends PhpTags\GenericFunction {

	public static function f_fa() {
		return \PhpTags\Hooks::createObject( func_get_args(), 'FontAwesomeIcon' );
	}

	public static function f_fontawesome() {
		return \PhpTags\Hooks::createObject( func_get_args(), 'FontAwesomeIcon' );
	}

	public static function f_slick() {
		return \PhpTags\Hooks::createObject( func_get_args(), 'Slick' );
	}

	public static function f_vega() {
		return \PhpTags\Hooks::createObject( func_get_args(), 'Vega' );
	}

}
