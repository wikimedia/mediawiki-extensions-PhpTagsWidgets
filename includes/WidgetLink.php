<?php
namespace PhpTagsObjects;

/**
 * Description of WidgetLink
 *
 * @author Pavel Astakhov <pastakhov@yandex.ru>
 * @licence GNU General Public Licence 2.0 or later
 */
class WidgetLink extends \PhpTags\GenericWidget {

	protected $element = 'a';

	public function m___construct( $title = null, $text = null, $properties = null ) {
		if ( $title instanceof \PhpTags\GenericObject ) {
			if ( $title->value instanceof \Title ) {
				$title = $title->value;
			} else {
				throw new \PhpTags\HookException( 'Title object expected' );
			}
		} else if ( $title !== false ) {
			$t = \PhpTags\Hooks::createObject( array($title), 'WTitle', 'wtitle' );
			if ( $t->value instanceof \Title ) {
				$title = $t->value;
			} else {
				$title = false;
			}
		}

		if ( $title ) {
			$this->value[self::GENERAL_ATTRIBS]['href'] = $title->getLinkURL();
		} else {
			$this->value[self::GENERAL_ATTRIBS]['href'] = '#';
		}

		$this->value[self::DATA]['title'] = $title;
		$this->value[self::DATA] = $text;
		return parent::m___construct( $properties );
	}

	public function getString() {
		$parser = \PhpTags\Renderer::getParser();
		$frame = \PhpTags\Renderer::getFrame();

		return $parser->recursiveTagParse( $this->value[self::DATA], $frame );
	}

	public function toString() {
		$html = parent::toString();
		return \PhpTags\Renderer::insertStripItem( $html );
	}

}
