<?php
/**
 * Main entry point for the PhpTags Widgets extension.
 *
 * @link https://www.mediawiki.org/wiki/Extension:PhpTags_Widgets Documentation
 * @file PhpTagsFunctions.php
 * @defgroup PhpTags
 * @ingroup Extensions
 * @author Pavel Astakhov <pastakhov@yandex.ru>
 * @licence GNU General Public Licence 2.0 or later
 */

// Check to see if we are being called as an extension or directly
if ( !defined('MEDIAWIKI') ) {
	die( 'This file is an extension to MediaWiki and thus not a valid entry point.' );
}

if ( !defined( 'PHPTAGS_VERSION' ) ) {
	die( 'ERROR: The <a href="https://www.mediawiki.org/wiki/Extension:PhpTags">extension PhpTags</a> must be installed for the extension PhpTags Widgets to run!' );
}

$needVersion = '3.9.0';
if ( version_compare( PHPTAGS_VERSION, $needVersion, '<' ) ) {
	die(
		'<b>Error:</b> This version of extension PhpTags Widgets needs <a href="https://www.mediawiki.org/wiki/Extension:PhpTags">PhpTags</a> ' . $needVersion . ' or later.
		You are currently using version ' . PHPTAGS_VERSION . '.<br />'
	);
}

if ( PHPTAGS_HOOK_RELEASE != 5 ) {
	die (
			'<b>Error:</b> This version of extension PhpTags Widgets is not compatible to current version of the PhpTags extension.'
	);
}

define( 'PHPTAGS_WIDGETS_VERSION' , '1.3.3' );

// Register this extension on Special:Version
$wgExtensionCredits['phptags'][] = array(
	'path'				=> __FILE__,
	'name'				=> 'PhpTags Widgets',
	'version'			=> PHPTAGS_WIDGETS_VERSION,
	'url'				=> 'https://www.mediawiki.org/wiki/Extension:PhpTags_Widgets',
	'author'			=> '[https://www.mediawiki.org/wiki/User:Pastakhov Pavel Astakhov]',
	'descriptionmsg'	=> 'phptagswidgets-desc'
);

// Allow translations for this extension
$wgMessagesDirs['PhpTagsWidgets'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['PhpTagsWidgets'] = __DIR__ . '/PhpTagsWidgets.i18n.php';

// Specify the function that will initialize the parser function.
/**
 * @codeCoverageIgnore
 */
$wgHooks['PhpTagsRuntimeFirstInit'][] = 'PhpTagsWidgetsInit::initializeRuntime';

// Preparing classes for autoloading
$wgAutoloadClasses['PhpTagsWidgetsInit'] = __DIR__ . '/PhpTagsWidgets.init.php';

$wgAutoloadClasses['PhpTagsWidgetsFunc'] = __DIR__ . '/includes/Functions.php';
$wgAutoloadClasses['PhpTags\\GenericWidget'] = __DIR__ . '/includes/GenericWidget.php';
$wgAutoloadClasses['PhpTagsObjects\\WidgetSlick'] = __DIR__ . '/includes/WidgetSlick.php';
$wgAutoloadClasses['PhpTagsObjects\\WidgetFontAwesome'] = __DIR__ . '/includes/WidgetFontAwesome.php';
$wgAutoloadClasses['PhpTagsObjects\\WidgetFontAwesomeIcon'] = __DIR__ . '/includes/WidgetFontAwesomeIcon.php';
$wgAutoloadClasses['PhpTagsObjects\\WidgetVega'] = __DIR__ . '/includes/WidgetVega.php';

/**
 * Add files to phpunit test
 * @codeCoverageIgnore
 */
$wgHooks['UnitTestsList'][] = function ( &$files ) {
	$testDir = __DIR__ . '/tests/phpunit';
	$files = array_merge( $files, glob( "$testDir/*Test.php" ) );
	\PhpTags\GenericWidget::$classPrefix = 'UnitTest';
	return true;
};

$wgParserTestFiles[] = __DIR__ . '/tests/parser/PhpTagsWidgetsTests.txt';

$tpl = array(
	'group' => 'PhpTagsWidgets',
	'localBasePath' => __DIR__ . '/resources',
	'remoteExtPath' => 'PhpTagsWidgets/resources',
	'targets' => array( 'mobile', 'desktop' ),
);

$wgResourceModules['ext.PhpTagsWidgets.onReady'] = array(
	'scripts' => 'ext.pw.onReady.js',
) + $tpl;

$wgResourceModules['ext.PhpTagsWidgets.slick'] = array(
	'scripts' => 'libs/slick/slick.js',
	'styles' => 'libs/slick/slick.css',
	//'dependencies' => array( 'jquery' ),
) + $tpl;

$wgResourceModules['ext.PhpTagsWidgets.FontAwesome'] = array(
	'styles' => 'libs/font-awesome/css/font-awesome.css',
) + $tpl;

$wgResourceModules['ext.PhpTagsWidgets.libs.d3'] = array(
	'scripts' => 'libs/d3/d3.js',
) + $tpl;

$wgResourceModules['ext.PhpTagsWidgets.libs.topojson'] = array(
	'scripts' => 'libs/topojson/topojson.js',
) + $tpl;

$wgResourceModules['ext.PhpTagsWidgets.vega'] = array(
	'scripts' => array(
		'libs/vega/vega.js',
		'ext.pw.vega.js'
		),
	'dependencies' => array( 'ext.PhpTagsWidgets.libs.d3', 'ext.PhpTagsWidgets.libs.topojson' ),
) + $tpl;

/**
 * Array of domains that the vega code is allowed to pull data from.
 * An empty array disables any external data (inline only).
 * If false, there are no restrictions and it will be opened for the XSS attacks!
 */
$wgPhpTagsWidgetVegaDomainWhiteList = array();
