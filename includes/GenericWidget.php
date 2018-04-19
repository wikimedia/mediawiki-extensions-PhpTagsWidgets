<?php
namespace PhpTags;

/**
 * Description of GenericWidget
 *
 * @author Pavel Astakhov <pastakhov@yandex.ru>
 * @licence GNU General Public Licence 2.0 or later
 */
class GenericWidget extends GenericObject {

	public static $classPrefix;
	protected static $classN = 1;
	protected static $addedModules = array();
	protected $classID;
	protected $element = 'div';

	const GENERAL_ATTRIBS = 0; // Must be safe (sanitized before set) for usage in HTML!!!
	const GENERAL_PROP = 1;
	const PROP = 2; // For children
	const PRIVATE_PROP = 3; // For children
	const DATA = 4; // For children

	function __construct( $objectName, $objectKey, $value = null ) {
		if ( self::$classPrefix === null ) {
			self::$classPrefix = 'pw-' . base_convert( mt_rand(), 10, 36 ) . '-';
		}
		$this->classID = self::$classN++;

		$initValue = [
			self::GENERAL_ATTRIBS => [],
			self::GENERAL_PROP => [],
			self::PROP => [],
			self::PRIVATE_PROP => [],
			self::DATA => [],
		];

		if ( is_array( $value ) ) {
			$value = $value + $initValue;
		} else {
			$value = $initValue;
		}

		parent::__construct( $objectName, $objectKey, $value );
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
		if ( !defined( 'MW_PHPUNIT_TEST' ) && !defined( 'MW_PARSER_TEST' ) && // Ignore in UnitTests
			empty( Renderer::$globalVariablesScript['Widgets']['prefix'] )
		) {
			Renderer::$globalVariablesScript['Widgets']['prefix'] = self::$classPrefix;
		}
		return self::$classPrefix . $this->classID;
	}

	/**
	 *
	 * @return array
	 */
	public function getAttribs() {
		$attribs = $this->value[self::GENERAL_ATTRIBS];
		$props = $this->value[self::GENERAL_PROP];

		$class = $this->getCssClassName();
		if ( $class ) {
			self::addToAttrib( $attribs, 'class', $class );
		}

		if ( isset( $props['backgroundImage'] ) ) {
			self::addToAttrib( $attribs, 'style', "background-image: {$props['backgroundImage']};" );
		}
		return $attribs;
	}

	protected static function addToAttrib( &$attribs, $index, $string ) {
		if ( isset( $attribs[$index] ) ) {
			$attribs[$index] = $string . ' ' . $attribs[$index];
		} else {
			$attribs[$index] = $string;
		}
	}

	public function toString() {
		$string = $this->getString();
		if ( $string ) {
			$parser = \PhpTags\Renderer::getParser();
			$frame = \PhpTags\Renderer::getFrame();
			$content = $parser->recursiveTagParse( $string, $frame );
		} else {
			$content = '';
		}

		$element = \Html::rawElement( $this->element, $this->getAttribs(), $content );
		return \PhpTags\Renderer::insertStripItem( $element );
	}

	public function __toString() {
		return $this->toString();
	}

	/**
	 *
	 * @param array $values
	 */
	protected function addData( $values ) {
		Renderer::$globalVariablesScript['Widgets']['data'][(string)$this->classID] = $values;
	}

	protected function addModules( $modules, $command = false ) {
		static $output = false;
		if ( $output === false ) {
			$output = Renderer::getParser()->getOutput();
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
			if ( false === isset( Renderer::$globalVariablesScript['Widgets']['whenReady'][$md] ) ) {
				Renderer::$globalVariablesScript['Widgets']['whenReady'][$md]['modules'] = $arrayModules;
				$this->addModules( 'ext.PhpTagsWidgets.onReady', false );
			}
			Renderer::$globalVariablesScript['Widgets']['whenReady'][$md]['fn'][ (string)$this->classID ] = $command;
		}
	}

	public function __call( $name, $arguments ) {
		list ( $callType, $tmp ) = explode( '_', $name, 2 );
		$subname = strtolower( $tmp );

		switch ( $subname ) {
			case 'class':
			case 'dir':
			case 'lang':
			case 'style':
			case 'title':
				if ( $callType === 'p' ) { // get property
					return $this->getTagAttribute( $subname );
				} elseif ( $callType === 'b' ) { // set property
					return $this->setTagAttribute( $subname, $arguments[0] );
				}
				break;
		}

		if ( strncmp( 'data', $subname, 4 ) === 0 ) { // allow data* properties
			if ( $callType === 'p' ) { // get property
				return $this->getTagAttribute( $subname );
			} elseif ( $callType === 'b' ) { // set property
				return $this->setTagAttribute( $subname, $arguments[0] );
			}
		}

		return parent::__call( $name, $arguments );
	}

	protected function getTagAttribute( $property ) {
		if ( isset( $this->value[self::GENERAL_ATTRIBS][$property] ) ) {
			return $this->value[self::GENERAL_ATTRIBS][$property];
		}
		return null;
	}

	protected function setTagAttribute( $property, $value ) {
		if ( $value === null ) {
			unset( $this->value[self::GENERAL_ATTRIBS][$property] );
		} else {
			$validAttr = \Sanitizer::validateTagAttributes( [$property=>$value], $this->element );
			if ( isset( $validAttr[$property] ) || array_key_exists( $property, $validAttr ) ) {
				$this->value[self::GENERAL_ATTRIBS][$property] = $validAttr[$property];
			} else {
				Runtime::pushException( new HookException( 'invalid attribute', HookException::EXCEPTION_NOTICE ) );
			}
		}
	}

	public function b_class( $value ) {
		if ( $value !== null ) {
			if ( !is_array( $value ) ) {
				$value = array_filter( explode( ' ', (string)$value ) );
			}
			$value = implode( ' ', array_map( '\Sanitizer::escapeClass', $value ) );
		}
		$this->setTagAttribute( 'class', $value );
	}

	public function p_backgroundImage() {
		return isset( $this->value[self::GENERAL_PROP]['backgroundImage'] ) ? $this->value[self::GENERAL_PROP]['backgroundImage'] : null;
	}

	public function b_backgroundImage( $value ) {
		if ( $value instanceof \PhpTagsObjects\WidgetImage ) {
			$url = $value->p_url();
			if ( $url ) {
				$value = "url('$url')";
			} else {
				$value = null;
			}
		} elseif ( $value !== 'none' || $value !== 'inherit' || $value !== null ) {
			\PhpTags\Runtime::pushException( new \PhpTags\HookException( 'Unexpected value, expected strings "none", "inherit" or an Image object') );
			$value = null;
		}

		if ( $value ) {
			$this->value[self::GENERAL_PROP]['backgroundImage'] = $value;
		} else {
			unset( $this->value[self::GENERAL_PROP]['backgroundImage'] );
		}
	}

}
