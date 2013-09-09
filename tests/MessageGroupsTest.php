<?php
/**
 * Unit tests.
 *
 * @author Niklas Laxström
 * @copyright Copyright © 2012 Niklas Laxström
 * @file
 * @license GPL-2.0+
 */

/**
 * @group Database
 */
class MessageGroupsTest extends MediaWikiTestCase {
	protected function setUp() {
		parent::setUp();

		$conf = array(
			__DIR__ . '/data/ParentGroups.yaml',
		);

		global $wgHooks;
		$this->setMwGlobals( array(
			'wgHooks' => $wgHooks,
			'wgTranslateCC' => array(),
			'wgTranslateMessageIndex' => array( 'DatabaseMessageIndex' ),
			'wgTranslateWorkflowStates' => false,
			'wgEnablePageTranslation' => false,
			'wgTranslateGroupFiles' => $conf,
			'wgTranslateTranslationServices' => array(),
		) );
		$wgHooks['TranslatePostInitGroups'] = array();
		MessageGroups::clearCache();
		MessageIndexRebuildJob::newJob()->run();
	}

	/**
	 * @dataProvider provideGroups
	 */
	public function testGetParentGroups( $expected, $target ) {
		$group = MessageGroups::getGroup( $target );
		$got = MessageGroups::getParentGroups( $group );
		$this->assertEquals( $expected, $got );
	}

	public static function provideGroups() {
		$cases = array();
		$cases[] = array(
			array( array( 'root1' ), array( 'root2' ) ),
			'twoparents'
		);

		$cases[] = array(
			array( array( 'root3', 'sub1' ), array( 'root3', 'sub2' ) ),
			'oneparent-twopaths'
		);

		$cases[] = array(
			array(
				array( 'root4' ),
				array( 'root4', 'nested1' ),
				array( 'root4', 'nested1', 'nested2' ),
				array( 'root4', 'nested2' ),
			),
			'multilevelnested'
		);

		return $cases;
	}

	public function testHaveSingleSourceLanguage() {
		$this->setMwGlobals( array(
			'wgTranslateGroupFiles' => array( __DIR__ . '/data/MixedSourceLanguageGroups.yaml' ),
		) );
		MessageGroups::clearCache();

		$enGroup1 = MessageGroups::getGroup( 'EnglishGroup1' );
		$enGroup2 = MessageGroups::getGroup( 'EnglishGroup2' );
		$teGroup1 = MessageGroups::getGroup( 'TeluguGroup1' );

		$this->assertTrue( MessageGroups::haveSingleSourceLanguage(
			array( $enGroup1, $enGroup2 ) )
		);
		$this->assertFalse( MessageGroups::haveSingleSourceLanguage(
			array( $enGroup1, $enGroup2, $teGroup1 ) )
		);
	}
}
