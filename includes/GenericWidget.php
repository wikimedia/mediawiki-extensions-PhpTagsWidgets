<?php
namespace PhpTags;

/**
 * Description of GenericWidget
 *
 * @author pastakhov
 */
class GenericWidget extends GenericObject {

	public static $classPrefix;
	protected static $classN = 1;
	protected static $addedModules = array();
	protected $classID;
	protected $element = 'div';

	const PROP = 0;
	const DATA = 1;
	const GENERAL_ATTRIBS = 2;
	const PRIVATE_PROP = 3;

	function __construct( $name, $value = null ) {
		if ( self::$classPrefix === null ) {
			self::$classPrefix = 'pw-' . base_convert( mt_rand(), 10, 36 ) . '-';
		}
		$this->classID = self::$classN++;

		if ( $value === null ) {
			$value = array( array(), array(), array(), array() );
		}
		parent::__construct( $name, $value );
	}

	function m___construct( $properties = null ) {
		if ( $properties === null ) {
			return true;
		} elseif ( is_array( $properties ) ) {
			foreach ( $properties as $prop => $val ) {
				Hooks::callSetObjectsProperty( $prop, $this, $val );
			}
		}
		return true;
	}

	public function getString() {}

	public function getCssClassName() {
		if ( ! isset( \PhpTags::$globalVariablesScript['Widgets']['prefix'] ) ) {
			\PhpTags::$globalVariablesScript['Widgets']['prefix'] = self::$classPrefix;
		}
		return self::$classPrefix . $this->classID;
	}

	public function toString() {
		$attribs = $this->value[self::GENERAL_ATTRIBS];
		$class = $this->getCssClassName();
		$attribs['class'] = isset( $attribs['class'] ) ? "$class {$attribs['class']}" : $class;
		return \Html::rawElement( $this->element, $attribs, $this->getString() );
	}

	public function __toString() {
		return $this->toString();
	}

	/**
	 *
	 * @param array $values
	 */
	protected function addData( $values ) {
		\PhpTags::$globalVariablesScript['Widgets']['data'][(string)$this->classID] = $values;
	}

	protected function addModules( $modules, $command = false ) {
		static $output = false;
		if ( $output === false ) {
			$output = \PhpTags\Runtime::$parser->getOutput();
		}

		$arrayModules = (array)$modules;
		sort( $arrayModules );
		$needToAdd = array();
		foreach ( $arrayModules as $m ) {
			if ( false === isset( self::$addedModules[$m] ) ) {
				self::$addedModules[$m] = true;
				$needToAdd[] = $m;
			}
		}
		if ( $needToAdd ) {
			$output->addModules( $needToAdd );
		}

		if ( $command ) {
			$md = md5( serialize( $arrayModules ) );
			if ( false === isset( \PhpTags::$globalVariablesScript['Widgets']['whenReady'][$md] ) ) {
				\PhpTags::$globalVariablesScript['Widgets']['whenReady'][$md]['modules'] = $arrayModules;
				$this->addModules( 'ext.PhpTagsWidgets.onReady', false );
			}
			\PhpTags::$globalVariablesScript['Widgets']['whenReady'][$md]['fn'][ (string)$this->classID ] = $command;
		}
	}

	public function __call( $name, $arguments ) {
		list ( $callType, $tmp ) = explode( '_', $name, 2 );
		$subname = strtolower( $tmp );
		$property = $subname;

		switch ( $subname ) {
			case 'class':
			case 'dir':
			case 'lang':
			case 'style':
			case 'title':
				if ( $callType === 'p' ) { // get property
					return isset( $this->value[self::GENERAL_ATTRIBS][$property] ) ? $this->value[self::GENERAL_ATTRIBS][$property] : null;
				} elseif ( $callType === 'b' ) { // set property
					if ( $arguments[0] === null ) {
						unset( $this->value[self::GENERAL_ATTRIBS][$property] );
					} else {
						$this->value[self::GENERAL_ATTRIBS][$property] = $arguments[0];
					}
					return;
				}
				break;
		}
		return parent::__call( $name, $arguments );
	}

	public function b_class( $value ) {
		if ( $value === null ) {
			unset( $this->value[self::GENERAL_ATTRIBS]['class'] );
			return;
		}
		if ( is_array( $value ) ) {
			$classes = $value;
		} else {
			$classes = array_filter( explode( ' ', (string)$value ) );
		}
		$this->value[self::GENERAL_ATTRIBS]['class'] = implode( ' ', array_map( '\Sanitizer::escapeClass', $classes ) );
	}

}
