<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Wikimedia\Phester\PHPUnit\PhesterTestSuite;

/**
 * @covers \Wikimedia\Phester\PHPUnit\PhesterTestSuite
 */
class PhesterTestSuiteTest extends PhesterTestSuite {

	/**
	 * Subclasses must implement this to construct a instance of themselves,
	 * pointing at the desired path.
	 *
	 * @return PhesterTestSuite
	 */
	public static function suite() {
		return new self( __FILE__ );
	}

	/**
	 * @param string $file
	 *
	 * @return array
	 */
	protected function loadInstructionData( $file ) {
		return [
			'suite' => 'PHPUnit integration',
			'description' => 'Just a dummy test suite',
			'tests' => [
				[
					'description' => 'one',
					'interaction' => [
						[
							'request' => [ 'method' => 'get', ],
							'response' => [ 'status' => 200 ],
						],
					],
				],
				[
					'description' => 'two',
					'interaction' => [
						[
							'request' => [ 'method' => 'get', ],
							'response' => [ 'status' => 200 ],
						],
						[
							'request' => [ 'method' => 'get', ],
							'response' => [ 'status' => 200 ],
						],
					],
				],
				[
					'description' => 'three',
					'interaction' => [
						[
							'request' => [ 'method' => 'get', ],
							'response' => [ 'status' => 200 ],
						],
					],
				]
			]
		];
	}

	/**
	 * @return Client
	 */
	protected function newHttpClient() {
		$responses = [
			new Response( 200 ),

			new Response( 200 ),
			new Response( 200 ),

			new Response( 200 ),
		];
		return new Client( [ 'handler' => new MockHandler( $responses ), 'base_uri' => $this->getBaseUri() ] );
	}

	/**
	 * @return string|false
	 */
	protected function getBaseUri() {
		return 'https://www.mediawiki.org/w/';
	}

}