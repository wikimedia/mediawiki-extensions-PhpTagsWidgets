<?php
namespace PhpTagsObjects;

/**
 * Description of WidgetVega
 * @see http://kenwheeler.github.io/slick/
 *
 * @author pastakhov
 */
class WidgetVega extends \PhpTags\GenericWidget {

	public function getString() {
		global $wgPhpTagsWidgetVegaDomainWhiteList;
		static $domainWhiteListWasAdded = false;

		if ( false === $domainWhiteListWasAdded ) {
			$domainWhiteListWasAdded = true;
			\PhpTags::$globalVariablesScript['Widgets']['vega']['domainWhiteList'] = $wgPhpTagsWidgetVegaDomainWhiteList;
		}
		$this->addModules( 'ext.PhpTagsWidgets.vega', 'extpwVega' );
		$this->addData( array( $this->value[self::PROP] ) );
	}

	private function checkProperty( $property, &$value ) {
		$arguments = array( &$value );
		$expects = false;

		switch ( $property ) {
			case 'width':
			case 'height':
				$expects = \PhpTags\Hooks::TYPE_INT;
				break;
			case 'axes':
			case 'data':
			case 'legends':
			case 'marks':
			case 'scales':
			case 'viewport':
				$expects = \PhpTags\Hooks::TYPE_ARRAY;
				break;
			case 'padding':
				$expects = \PhpTags\Hooks::TYPE_NOT_OBJECT;
				break;
			case 'name':
				$expects = \PhpTags\Hooks::TYPE_STRING;
				break;
			default:
				return true;
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
		$property = strtolower( $tmp );

		switch ( $property ) {
			case 'axes':
			case 'data':
			case 'height':
			case 'legends':
			case 'marks':
			case 'name':
			case 'padding':
			case 'scales':
			case 'viewport':
			case 'width':
				if ( $callType === 'p' ) { // get property
					return isset( $this->value[self::PROP][$property] ) ? $this->value[self::PROP][$property] : null;
				} elseif ( $callType === 'b' ) { // set property
					if ( $arguments[0] === null ) {
						unset( $this->value[self::PROP][$property] );
					} elseif ( $this->checkProperty( $property, $arguments[0] ) ) {
						$this->value[self::PROP][$property] = $arguments[0];
					}
					return;
				}
				break;
		}
		return parent::__call( $name, $arguments );
	}

}
