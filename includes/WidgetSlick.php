<?php
namespace PhpTagsObjects;

/**
 * Description of WidgetsSlick
 * @see http://kenwheeler.github.io/slick/
 *
 * @author pastakhov
 */
class WidgetSlick extends \PhpTags\GenericWidget {

	protected static $modules = false;
	protected static $trueCase = array(
		'adaptiveheight' => 'adaptiveHeight',
		'autoplayspeed' => 'autoplaySpeed',
		'centermode' => 'centerMode',
		'centerpadding' => 'centerPadding',
		'cssease' => 'cssEase',
		'initialslide' => 'initialSlide',
		'focusonselect' => 'focusOnSelect',
		'pauseonhover' => 'pauseOnHover',
		'pauseondotshover' => 'pauseOnDotsHover',
		'slidestoshow' => 'slidesToShow',
		'slidestoscroll' => 'slidesToScroll',
		'swipetoslide' => 'swipeToSlide',
		'touchmove' => 'touchMove',
		'touchthreshold' => 'touchThreshold',
		'usecss' => 'useCSS',
		'variablewidth' => 'variableWidth',
	);

	public function m___construct( $properties = null, $value = null ) {
		$this->value[self::DATA] = $value;
		return parent::m___construct( $properties );
	}

	public function m_enable() {
		$this->addToOnReady( 'slick' );
	}

	public function toString() {
		$this->m_enable();
		return parent::toString();
	}

	public function getString() {
		$this->addModule( 'ext.PhpTagsWidgets.slick' );
		$this->addToData( array( $this->value[self::PROP] ) );
		$data = $this->value[self::DATA];
		if ( is_array( $data ) ) {
			$data = '<div>' . implode( '</div><div>', $this->value[self::DATA] ) . '</div>';
		}
		return $data;
	}

	private function checkProperty( $property, &$value ) {
		$arguments = array( &$value );
		$expects = false;

		switch ( $property ) {
			case 'accessibility':
			case 'adaptiveHeight':
			case 'autoplay':
			case 'arrows':
			case 'centerMode':
			case 'dots':
			case 'draggable':
//			case 'fade': do not allow it!
			case 'focusOnSelect':
			case 'infinite':
			case 'pauseOnHover':
			case 'pauseOnDotsHover':
			case 'swipe':
			case 'swipeToSlide':
			case 'touchMove':
			case 'variableWidth':
			case 'vertical':
			case 'rtl':
			case 'useCSS':
				$expects = \PhpTags\Hooks::TYPE_BOOL;
				break;
			case 'autoplaySpeed':
			case 'initialSlide':
			case 'slidesToShow':
			case 'slidesToScroll':
			case 'speed':
			case 'touchThreshold':
				$expects = \PhpTags\Hooks::TYPE_INT;
				break;
			case 'centerPadding':
// @todo	case 'appendArrows':
// @todo	case 'prevArrow':
// @todo	case 'nextArrow':
			case 'cssEase':
// @???todo case 'customPaging': ???
// @todo	case 'easing':
//			case 'lazyLoad':
			case 'slide':
				$expects = \PhpTags\Hooks::TYPE_STRING;
				break;
			case 'asnavfor':
				$expects = 'Slick';
				break;
// @todo	case 'responsive':
//				$expects = \PhpTags\Hooks::TYPE_ARRAY;
//				break;
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
		$subname = strtolower( $tmp );
		$property = $subname;

		switch ( $subname ) {
			case 'adaptiveheight':
			case 'autoplayspeed':
			case 'centermode':
			case 'centerpadding':
			case 'cssease':
			case 'focusonselect':
			case 'initialslide':
			case 'pauseonhover':
			case 'pauseondotshover':
			case 'slidestoshow':
			case 'slidestoscroll':
			case 'swipetoslide':
			case 'touchmove':
			case 'touchthreshold':
			case 'usecss':
			case 'variablewidth':
				$property = self::$trueCase[$subname];
				// break is not necessary here
			case 'accessibility':
			case 'autoplay':
			case 'arrows':
			case 'dots':
			case 'draggable':
//			case 'fade': do not allow it!
//			case 'easing':
			case 'infinite':
			case 'swipe':
			case 'vertical':
			case 'rtl':
			case 'slide':
			case 'speed':
				if ( $callType === 'p' ) { // get property
					return isset( $this->value[self::PROP][$property] ) ? $this->value[self::PROP][$property] : null;
				} elseif ( $callType === 'b' ) { // set property
					if ( $arguments[0] === null ) {
						unset( $this->value[self::PROP][$property] );
					} elseif ( $this->checkProperty( $property, $arguments[0] ) ) {
						$this->value[self::PROP][$property] = $arguments[0];
					}
					return $this;
				}
				break;
		}
		return parent::__call( $name, $arguments );
	}

	/**
	 * Set property centerPadding
	 * @param string $value
	 * @return \PhpTagsObjects\WidgetSlick
	 */
	public function b_centerPadding( $value ) {
		$matches = array();
		if ( $value === null ) {
			unset( $this->value[self::PROP]['centerPadding'] );
		} elseif( $this->checkProperty( 'centerPadding', $value ) ) {
			if ( preg_match( '/\d+(?:px|%)/i', $value, $matches ) ) {
				$this->value[self::PROP]['centerPadding'] = $matches[0];
			} else {
				// @todo error message
			}
		}
		return $this;
	}

	/**
	 * Set property rtl
	 * @param bool $value
	 */
	public function b_rtl( $value ) {
		if ( $value === null ) {
			unset( $this->value[self::PROP]['rtl'] );
			$this->b_dir( null );
		} elseif( $this->checkProperty( 'rtl', $value ) ) {
			$this->value[self::PROP]['rtl'] = $value;
			if ( $value ) {
				$this->b_dir( 'rtl' );
			}
		}
		return $this;
	}

	/**
	 *
	 * @param \PhpTagsObjects\WidgetSlick $value
	 * @return \PhpTagsObjects\WidgetSlick
	 */
	public function b_asNavFor( $value ) {
		if( $value !== null && !$this->checkProperty( 'asNavFor', $value ) ) {
			$value = null;
		}
		$oldValue = isset($this->value[self::PRIVATE_PROP]['asNavFor']) ? $this->value[self::PRIVATE_PROP]['asNavFor'] : null;
		if ( $oldValue === $value ) {
			return;
		}

		$this->setAsNavFor( $value );

		if ( $oldValue instanceof WidgetSlick ) {
			$oldValue->setAsNavFor( null );
			if ( $value !== null ) {
				$oldValue->setAsNavFor( $this );
			}
		}
		if ( $value instanceof WidgetSlick ) {
			$value->setAsNavFor( $this );
		}
		return $this;
	}

	public function p_asNavFor() {
		return isset($this->value[self::PRIVATE_PROP]['asNavFor']) ? $this->value[self::PRIVATE_PROP]['asNavFor'] : null;
	}

	public function setAsNavFor ( $value ) {
		if ( $value === null ) {
			unset( $this->value[self::PRIVATE_PROP]['asNavFor'] );
			unset( $this->value[self::PROP]['asNavFor'] );
		} else {
			$this->value[self::PRIVATE_PROP]['asNavFor'] = $value;
			$this->value[self::PROP]['asNavFor'] = '.' . $value->getClassName();
		}
	}

}
