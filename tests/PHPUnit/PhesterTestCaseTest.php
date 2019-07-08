<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Wikimedia\Phester\PHPUnit\PhesterTestCase;

/**
 * @covers \Wikimedia\Phester\PHPUnit\PhesterTestCase
 */
class PhesterTestCaseTest extends PhesterTestCase {

	public function __construct() {
		parent::__construct( __FILE__ );
	}

	/**
	 * @param string $file
	 *
	 * @return array
	 */
	protected function loadInstructionData( $file ) {
		return [
			'suite' => 'PHPUnit integration',
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
			new Response( 303 ),

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