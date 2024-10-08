<?php
namespace PhpTags;

/**
 * @coversNothing
 */
class GenericTest extends \MediaWikiIntegrationTestCase {

	public function testRun_constant_1() {
		$this->assertEquals(
				Runtime::runSource('echo PHPTAGS_WIDGETS_VERSION;'),
				array( \ExtensionRegistry::getInstance()->getAllThings()['PhpTags Widgets']['version'] )
			);
	}

}
