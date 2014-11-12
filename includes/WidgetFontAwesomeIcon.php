<?php
namespace PhpTagsObjects;

/**
 * Description of WidgetFontAwesomeIcon
 * @see http://fortawesome.github.io/Font-Awesome/
 *
 * @author pastakhov
 */
class WidgetFontAwesomeIcon extends \PhpTags\GenericWidget {

	protected $element = 'i';

	public function getClassName() {
		return 'fa fa-' . $this->value[self::DATA] . ' ' . \implode( ' ', array_values( $this->value[self::PROP] ) );
	}

	public function getString() {
		$this->addModule( 'ext.PhpTagsWidgets.FontAwesome', false );
	}

	public function m___construct( $value = null, $properties = null ) {
		$value = strtolower( $value );
		if ( !isset( self::$icons[$value] ) ) {
			return false;
		}
		$this->value[self::DATA] = $value;

		if ( $properties !== null ) {
			foreach ( $properties as $key => $prop ) {
				$prop = strtolower( $prop );
				if ( (int)$key === $key ) {
					if ( isset( self::$options[$prop] ) ) {
						$this->value[self::PROP][ self::$options[$prop] ] = "fa-$prop";
					} else {
						\PhpTags\Runtime::$transit[PHPTAGS_TRANSIT_EXCEPTION][] = new \PhpTags\PhpTagsException( \PhpTags\PhpTagsException::NOTICE_UNDEFINED_PROPERTY, array($this->name, $prop) );
					}
				} else {
					$handler = "b_$key";
					$this->$handler( $prop );
				}
			}
		}
		return true;
	}

	public function __call( $name, $arguments ) {
		list ( $callType, $tmp ) = explode( '_', $name, 2 );

		if ( $callType == 'p' ) {
			if ( $tmp[0] == '_' ) {
				$tmp = substr( $tmp, 1 );
			}
			$prop = $prop = str_replace( '_', '-', strtolower( $tmp ) );
			if ( isset( self::$options[$prop] ) ) {
				$value = $this->value;
				$value[self::PROP][ self::$options[$prop] ] = "fa-$prop";
				return new self( $this->name, $value );
			}
		}

		return parent::__call( $name, $arguments );
	}

	public static function checkArguments( $object, $method, $arguments, $expects = false ) {
		switch ( $method ) {
			case '__construct':
				$expects = array(
					\PhpTags\Hooks::TYPE_STRING,
					\PhpTags\Hooks::TYPE_ARRAY,
					\PhpTags\Hooks::EXPECTS_MINIMUM_PARAMETERS => 1,
					\PhpTags\Hooks::EXPECTS_MAXIMUM_PARAMETERS => 2,
				);
				break;
		}
		return parent::checkArguments( $object, $method, $arguments, $expects );
	}

	private static $icons = array(
		'adjust' => 1,
		'anchor' => 1,
		'archive' => 1,
		'area-chart' => 1,
		'arrows' => 1,
		'arrows-h' => 1,
		'arrows-v' => 1,
		'asterisk' => 1,
		'at' => 1,
		'automobile' => 1,
		'ban' => 1,
		'bank' => 1,
		'bar-chart' => 1,
		'bar-chart-o' => 1,
		'barcode' => 1,
		'bars' => 1,
		'beer' => 1,
		'bell' => 1,
		'bell-o' => 1,
		'bell-slash' => 1,
		'bell-slash-o' => 1,
		'bicycle' => 1,
		'binoculars' => 1,
		'birthday-cake' => 1,
		'bolt' => 1,
		'bomb' => 1,
		'book' => 1,
		'bookmark' => 1,
		'bookmark-o' => 1,
		'briefcase' => 1,
		'bug' => 1,
		'building' => 1,
		'building-o' => 1,
		'bullhorn' => 1,
		'bullseye' => 1,
		'bus' => 1,
		'cab' => 1,
		'calculator' => 1,
		'calendar' => 1,
		'calendar-o' => 1,
		'camera' => 1,
		'camera-retro' => 1,
		'car' => 1,
		'caret-square-o-down' => 1,
		'caret-square-o-left' => 1,
		'caret-square-o-right' => 1,
		'caret-square-o-up' => 1,
		'cc' => 1,
		'certificate' => 1,
		'check' => 1,
		'check-circle' => 1,
		'check-circle-o' => 1,
		'check-square' => 1,
		'check-square-o' => 1,
		'child' => 1,
		'circle' => 1,
		'circle-o' => 1,
		'circle-o-notch' => 1,
		'circle-thin' => 1,
		'clock-o' => 1,
		'close' => 1,
		'cloud' => 1,
		'cloud-download' => 1,
		'cloud-upload' => 1,
		'code' => 1,
		'code-fork' => 1,
		'coffee' => 1,
		'cog' => 1,
		'cogs' => 1,
		'comment' => 1,
		'comment-o' => 1,
		'comments' => 1,
		'comments-o' => 1,
		'compass' => 1,
		'copyright' => 1,
		'credit-card' => 1,
		'crop' => 1,
		'crosshairs' => 1,
		'cube' => 1,
		'cubes' => 1,
		'cutlery' => 1,
		'dashboard' => 1,
		'database' => 1,
		'desktop' => 1,
		'dot-circle-o' => 1,
		'download' => 1,
		'edit' => 1,
		'ellipsis-h' => 1,
		'ellipsis-v' => 1,
		'envelope' => 1,
		'envelope-o' => 1,
		'envelope-square' => 1,
		'eraser' => 1,
		'exchange' => 1,
		'exclamation' => 1,
		'exclamation-circle' => 1,
		'exclamation-triangle' => 1,
		'external-link' => 1,
		'external-link-square' => 1,
		'eye' => 1,
		'eye-slash' => 1,
		'eyedropper' => 1,
		'fax' => 1,
		'female' => 1,
		'fighter-jet' => 1,
		'file-archive-o' => 1,
		'file-audio-o' => 1,
		'file-code-o' => 1,
		'file-excel-o' => 1,
		'file-image-o' => 1,
		'file-movie-o' => 1,
		'file-pdf-o' => 1,
		'file-photo-o' => 1,
		'file-picture-o' => 1,
		'file-powerpoint-o' => 1,
		'file-sound-o' => 1,
		'file-video-o' => 1,
		'file-word-o' => 1,
		'file-zip-o' => 1,
		'film' => 1,
		'filter' => 1,
		'fire' => 1,
		'fire-extinguisher' => 1,
		'flag' => 1,
		'flag-checkered' => 1,
		'flag-o' => 1,
		'flash' => 1,
		'flask' => 1,
		'folder' => 1,
		'folder-o' => 1,
		'folder-open' => 1,
		'folder-open-o' => 1,
		'frown-o' => 1,
		'futbol-o' => 1,
		'gamepad' => 1,
		'gavel' => 1,
		'gear' => 1,
		'gears' => 1,
		'gift' => 1,
		'glass' => 1,
		'globe' => 1,
		'graduation-cap' => 1,
		'group' => 1,
		'hdd-o' => 1,
		'headphones' => 1,
		'heart' => 1,
		'heart-o' => 1,
		'history' => 1,
		'home' => 1,
		'image' => 1,
		'inbox' => 1,
		'info' => 1,
		'info-circle' => 1,
		'institution' => 1,
		'key' => 1,
		'keyboard-o' => 1,
		'language' => 1,
		'laptop' => 1,
		'leaf' => 1,
		'legal' => 1,
		'lemon-o' => 1,
		'level-down' => 1,
		'level-up' => 1,
		'life-bouy' => 1,
		'life-buoy' => 1,
		'life-ring' => 1,
		'life-saver' => 1,
		'lightbulb-o' => 1,
		'line-chart' => 1,
		'location-arrow' => 1,
		'lock' => 1,
		'magic' => 1,
		'magnet' => 1,
		'mail-forward' => 1,
		'mail-reply' => 1,
		'mail-reply-all' => 1,
		'male' => 1,
		'map-marker' => 1,
		'meh-o' => 1,
		'microphone' => 1,
		'microphone-slash' => 1,
		'minus' => 1,
		'minus-circle' => 1,
		'minus-square' => 1,
		'minus-square-o' => 1,
		'mobile' => 1,
		'mobile-phone' => 1,
		'money' => 1,
		'moon-o' => 1,
		'mortar-board' => 1,
		'music' => 1,
		'navicon' => 1,
		'newspaper-o' => 1,
		'paint-brush' => 1,
		'paper-plane' => 1,
		'paper-plane-o' => 1,
		'paw' => 1,
		'pencil' => 1,
		'pencil-square' => 1,
		'pencil-square-o' => 1,
		'phone' => 1,
		'phone-square' => 1,
		'photo' => 1,
		'picture-o' => 1,
		'pie-chart' => 1,
		'plane' => 1,
		'plug' => 1,
		'plus' => 1,
		'plus-circle' => 1,
		'plus-square' => 1,
		'plus-square-o' => 1,
		'power-off' => 1,
		'print' => 1,
		'puzzle-piece' => 1,
		'qrcode' => 1,
		'question' => 1,
		'question-circle' => 1,
		'quote-left' => 1,
		'quote-right' => 1,
		'random' => 1,
		'recycle' => 1,
		'refresh' => 1,
		'remove' => 1,
		'reorder' => 1,
		'reply' => 1,
		'reply-all' => 1,
		'retweet' => 1,
		'road' => 1,
		'rocket' => 1,
		'rss' => 1,
		'rss-square' => 1,
		'search' => 1,
		'search-minus' => 1,
		'search-plus' => 1,
		'send' => 1,
		'send-o' => 1,
		'share' => 1,
		'share-alt' => 1,
		'share-alt-square' => 1,
		'share-square' => 1,
		'share-square-o' => 1,
		'shield' => 1,
		'shopping-cart' => 1,
		'sign-in' => 1,
		'sign-out' => 1,
		'signal' => 1,
		'sitemap' => 1,
		'sliders' => 1,
		'smile-o' => 1,
		'soccer-ball-o' => 1,
		'sort' => 1,
		'sort-alpha-asc' => 1,
		'sort-alpha-desc' => 1,
		'sort-amount-asc' => 1,
		'sort-amount-desc' => 1,
		'sort-asc' => 1,
		'sort-desc' => 1,
		'sort-down' => 1,
		'sort-numeric-asc' => 1,
		'sort-numeric-desc' => 1,
		'sort-up' => 1,
		'space-shuttle' => 1,
		'spinner' => 1,
		'spoon' => 1,
		'square' => 1,
		'square-o' => 1,
		'star' => 1,
		'star-half' => 1,
		'star-half-empty' => 1,
		'star-half-full' => 1,
		'star-half-o' => 1,
		'star-o' => 1,
		'suitcase' => 1,
		'sun-o' => 1,
		'support' => 1,
		'tablet' => 1,
		'tachometer' => 1,
		'tag' => 1,
		'tags' => 1,
		'tasks' => 1,
		'taxi' => 1,
		'terminal' => 1,
		'thumb-tack' => 1,
		'thumbs-down' => 1,
		'thumbs-o-down' => 1,
		'thumbs-o-up' => 1,
		'thumbs-up' => 1,
		'ticket' => 1,
		'times' => 1,
		'times-circle' => 1,
		'times-circle-o' => 1,
		'tint' => 1,
		'toggle-down' => 1,
		'toggle-left' => 1,
		'toggle-off' => 1,
		'toggle-on' => 1,
		'toggle-right' => 1,
		'toggle-up' => 1,
		'trash' => 1,
		'trash-o' => 1,
		'tree' => 1,
		'trophy' => 1,
		'truck' => 1,
		'tty' => 1,
		'umbrella' => 1,
		'university' => 1,
		'unlock' => 1,
		'unlock-alt' => 1,
		'unsorted' => 1,
		'upload' => 1,
		'user' => 1,
		'users' => 1,
		'video-camera' => 1,
		'volume-down' => 1,
		'volume-off' => 1,
		'volume-up' => 1,
		'warning' => 1,
		'wheelchair' => 1,
		'wifi' => 1,
		'wrench' => 1,
		'file' => 2,
		'file-archive-o' => 2,
		'file-audio-o' => 2,
		'file-code-o' => 2,
		'file-excel-o' => 2,
		'file-image-o' => 2,
		'file-movie-o' => 2,
		'file-o' => 2,
		'file-pdf-o' => 2,
		'file-photo-o' => 2,
		'file-picture-o' => 2,
		'file-powerpoint-o' => 2,
		'file-sound-o' => 2,
		'file-text' => 2,
		'file-text-o' => 2,
		'file-video-o' => 2,
		'file-word-o' => 2,
		'file-zip-o' => 2,
		'circle-o-notch' => 3,
		'cog' => 3,
		'gear' => 3,
		'refresh' => 3,
		'spinner' => 3,
		'check-square' => 4,
		'check-square-o' => 4,
		'circle' => 4,
		'circle-o' => 4,
		'dot-circle-o' => 4,
		'minus-square' => 4,
		'minus-square-o' => 4,
		'plus-square' => 4,
		'plus-square-o' => 4,
		'square' => 4,
		'square-o' => 4,
		'cc-amex' => 5,
		'cc-discover' => 5,
		'cc-mastercard' => 5,
		'cc-paypal' => 5,
		'cc-stripe' => 5,
		'cc-visa' => 5,
		'credit-card' => 5,
		'google-wallet' => 5,
		'paypal' => 5,
		'area-chart' => 6,
		'bar-chart' => 6,
		'bar-chart-o' => 6,
		'line-chart' => 6,
		'pie-chart' => 6,
		'bitcoin' => 7,
		'btc' => 7,
		'cny' => 7,
		'dollar' => 7,
		'eur' => 7,
		'euro' => 7,
		'gbp' => 7,
		'ils' => 7,
		'inr' => 7,
		'jpy' => 7,
		'krw' => 7,
		'money' => 7,
		'rmb' => 7,
		'rouble' => 7,
		'rub' => 7,
		'ruble' => 7,
		'rupee' => 7,
		'shekel' => 7,
		'sheqel' => 7,
		'try' => 7,
		'turkish-lira' => 7,
		'usd' => 7,
		'won' => 7,
		'yen' => 7,
		'align-center' => 8,
		'align-justify' => 8,
		'align-left' => 8,
		'align-right' => 8,
		'bold' => 8,
		'chain' => 8,
		'chain-broken' => 8,
		'clipboard' => 8,
		'columns' => 8,
		'copy' => 8,
		'cut' => 8,
		'dedent' => 8,
		'eraser' => 8,
		'file' => 8,
		'file-o' => 8,
		'file-text' => 8,
		'file-text-o' => 8,
		'files-o' => 8,
		'floppy-o' => 8,
		'font' => 8,
		'header' => 8,
		'indent' => 8,
		'italic' => 8,
		'link' => 8,
		'list' => 8,
		'list-alt' => 8,
		'list-ol' => 8,
		'list-ul' => 8,
		'outdent' => 8,
		'paperclip' => 8,
		'paragraph' => 8,
		'paste' => 8,
		'repeat' => 8,
		'rotate-left' => 8,
		'rotate-right' => 8,
		'save' => 8,
		'scissors' => 8,
		'strikethrough' => 8,
		'subscript' => 8,
		'superscript' => 8,
		'table' => 8,
		'text-height' => 8,
		'text-width' => 8,
		'th' => 8,
		'th-large' => 8,
		'th-list' => 8,
		'underline' => 8,
		'undo' => 8,
		'unlink' => 8,
		'angle-double-down' => 9,
		'angle-double-left' => 9,
		'angle-double-right' => 9,
		'angle-double-up' => 9,
		'angle-down' => 9,
		'angle-left' => 9,
		'angle-right' => 9,
		'angle-up' => 9,
		'arrow-circle-down' => 9,
		'arrow-circle-left' => 9,
		'arrow-circle-o-down' => 9,
		'arrow-circle-o-left' => 9,
		'arrow-circle-o-right' => 9,
		'arrow-circle-o-up' => 9,
		'arrow-circle-right' => 9,
		'arrow-circle-up' => 9,
		'arrow-down' => 9,
		'arrow-left' => 9,
		'arrow-right' => 9,
		'arrow-up' => 9,
		'arrows' => 9,
		'arrows-alt' => 9,
		'arrows-h' => 9,
		'arrows-v' => 9,
		'caret-down' => 9,
		'caret-left' => 9,
		'caret-right' => 9,
		'caret-square-o-down' => 9,
		'caret-square-o-left' => 9,
		'caret-square-o-right' => 9,
		'caret-square-o-up' => 9,
		'caret-up' => 9,
		'chevron-circle-down' => 9,
		'chevron-circle-left' => 9,
		'chevron-circle-right' => 9,
		'chevron-circle-up' => 9,
		'chevron-down' => 9,
		'chevron-left' => 9,
		'chevron-right' => 9,
		'chevron-up' => 9,
		'hand-o-down' => 9,
		'hand-o-left' => 9,
		'hand-o-right' => 9,
		'hand-o-up' => 9,
		'long-arrow-down' => 9,
		'long-arrow-left' => 9,
		'long-arrow-right' => 9,
		'long-arrow-up' => 9,
		'toggle-down' => 9,
		'toggle-left' => 9,
		'toggle-right' => 9,
		'toggle-up' => 9,
		'arrows-alt' => 10,
		'backward' => 10,
		'compress' => 10,
		'eject' => 10,
		'expand' => 10,
		'fast-backward' => 10,
		'fast-forward' => 10,
		'forward' => 10,
		'pause' => 10,
		'play' => 10,
		'play-circle' => 10,
		'play-circle-o' => 10,
		'step-backward' => 10,
		'step-forward' => 10,
		'stop' => 10,
		'youtube-play' => 10,
	);

	private static $options = array(
		'lg' => 1,
		'2x' => 1,
		'3x' => 1,
		'4x' => 1,
		'5x' => 1,
		'fw' => 2,
		'li' => 3,
		'border' => 4,
		'spin' => 5,
		'rotate-90' => 6,
		'rotate-180' => 6,
		'rotate-270' => 6,
		'flip-horizontal' => 7,
		'flip-vertical' => 8,
	);

}
