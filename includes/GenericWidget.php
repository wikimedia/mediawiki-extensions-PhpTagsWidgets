<?php
namespace PhpTags;

/**
 * Description of GenericWidget
 *
 * @author pastakhov
 */
class GenericWidget extends GenericObject {

	protected static $classPrefix;
	protected static $classN = 1;
	protected static $addedModules = array();
	protected $classID;
	const PROP = 0;
	const DATA = 1;
	const GENERAL_ATTRIBS = 2;
	const PRIVATE_PROP = 3;

	function __construct( $name, $value = null ) {
		if ( self::$classPrefix === null ) {
			self::$classPrefix = 'pw-' . base_convert( mt_rand(), 10, 36 ) . '-';
			\PhpTags::$globalVariablesScript['Widgets']['prefix'] = self::$classPrefix;
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
				$handler = "b_$prop";
				$this->$handler( $val );
			}
		}
		return true;
	}

	function getString() {}

	public function getClassName() {
		return self::$classPrefix . $this->classID;
	}

	public function toString() {
		$element = 'div';
		$attribs = $this->value[self::GENERAL_ATTRIBS];
		$class = $this->getClassName();
		$attribs['class'] = isset( $attribs['class'] ) ? "$class {$attribs['class']}" : $class;
		return \Html::rawElement( $element, $attribs, $this->getString() );
	}

	public function __toString() {
		return $this->toString();
	}

	/**
	 *
	 * @param array $values
	 */
	protected function addToData( $values ) {
		\PhpTags::$globalVariablesScript['Widgets']['data'][(string)$this->classID] = $values;
	}

	/**
	 *
	 * @param mixed $command
	 */
	protected function addToOnReady( $command ) {
		$this->addModule( 'ext.PhpTagsWidgets.onReady', false );
		\PhpTags::$globalVariablesScript['Widgets']['onReady'][ (string)$this->classID ] = $command;
	}

	public function m_disable() {
		unset( \PhpTags::$globalVariablesScript['Widgets']['onReady'][ (string)$this->classID ] );
	}

	protected function addModule( $module, $wait = true ) {
		static $output = false;
		if ( isset( self::$addedModules[$module] ) ) { // Module is already added
			return;
		}
		if ( $output === false ) {
			$output = \PhpTags\Runtime::getParser()->getOutput();
		}
		$output->addModules( $module );
		self::$addedModules[$module] = true;
		if ( $wait ) {
			\PhpTags::$globalVariablesScript['Widgets']['wait'][] = $module;
		}
	}

	private function checkGenericProperty( $property, &$value ) {
		$arguments = array( &$value );
		$expects = false;

		switch ( strtolower( $property ) ) {
			case 'class':
			case 'dir':
			case 'style':
				$expects = \PhpTags\Hooks::TYPE_STRING;
				break;
		}

		$check = parent::checkArguments( $this->name, $property, $arguments, array($expects) );
		if ( $check === true ) {
			return true;
		}
		if ( $check instanceof \PhpTags\PhpTagsException && $check->getCode() === \PhpTags\PhpTagsException::WARNING_EXPECTS_PARAMETER ) {
			\PhpTags\Runtime::$transit[PHPTAGS_TRANSIT_EXCEPTION][] = new \PhpTags\PhpTagsException( \PhpTags\PhpTagsException::NOTICE_EXPECTS_PROPERTY, $check->params );
		}
		return false;
	}

	public function __call( $name, $arguments ) {
		list ( $callType, $tmp ) = explode( '_', $name, 2 );
		$subname = strtolower( $tmp );
		$property = $subname;

		switch ( $subname ) {
			case 'class':
			case 'dir':
			case 'style':
				if ( $callType === 'p' ) { // get property
					return isset( $this->value[self::GENERAL_ATTRIBS][$property] ) ? $this->value[self::GENERAL_ATTRIBS][$property] : null;
				} elseif ( $callType === 'b' ) { // set property
					if ( $arguments[0] === null ) {
						unset( $this->value[self::GENERAL_ATTRIBS][$property] );
					} elseif ( $this->checkGenericProperty( $subname, $arguments[0] ) ) {
						$this->value[self::GENERAL_ATTRIBS][$property] = $arguments[0];
					}
					return $this;
				}
				break;
		}
		return parent::__call( $name, $arguments );
	}
}
