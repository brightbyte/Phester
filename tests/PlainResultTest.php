<?php

use PHPUnit\Framework\TestCase;
use Wikimedia\Phester\PlainResult;

/**
 * @covers \Wikimedia\Phester\PlainResult
 */
class PlainResultTest extends TestCase {

	public function testGetLines() {
		$result = new PlainResult();

		$result->init( 'Foo', 'whatever' );
		$result->logOutcome( 'Bla bla', [
			'things',
			'stuff'
		] );
		$result->logOutcome( 'Bla bla bla', [] );
		$result->logOutcome( 'Bla bla bla bla', [
			'crud'
		] );
		$result->close();

		$expected = [
			'- Suite: Foo',
			'! Bla bla failed:',
			"\tthings",
			"\tstuff",
			'! Bla bla bla bla failed:',
			"\tcrud",
		];
		$this->assertEquals( $expected, $result->getLines() );
	}

}
