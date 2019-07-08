<?php

namespace Wikimedia\Phester\PHPUnit;

use GuzzleHttp\Client;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestResult;
use Symfony\Component\Yaml\Yaml;
use Wikimedia\Phester\Instructions;
use Wikimedia\Phester\TestSuite;

/**
 * PHPUnit wrapper for phester tests.
 * @package Wikimedia\Phester\PHPUnit
 */
abstract class PhesterTestCase extends TestCase {

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var string|null
	 */
	private $baseUri = null;

	/**
	 * @param string $path
	 */
	public function __construct( $path ) {
		$this->path = $path;
	}

	public function runBare(): void {
		$this->baseUri = $this->getBaseUri();
		$result = $this->getResult();

		if ( $this->baseUri === false ) {
			$result->addError( $this, new \RuntimeException( 'Environment variable PHESTER_BASE_URI is not set' ), 0 );

			return $result;
		}

		if ( $result === null ) {
			$result = new TestResult();
		}

		// TODO: if $path is a directory, find files
		$files = [ $this->path ];

		foreach ( $files as $file ) {
			$instructionData = $this->loadInstructionData( $file );
			$logger = new Logger( "Phester" ); // TODO: fail when errors are logged??
			$phesterResult = new PHPUnitResultAdapter( $this, $result );
			$client = $this->newHttpClient();
			$testSuite = new TestSuite( new Instructions( $instructionData ), $phesterResult, $logger, $client );

			// TODO: $testSuiteOutput should be class with methods for formatting as plain text or html or
			// json
			$testSuite->run();

			if ( $result->shouldStop() ) {
				break;
			}
		}
	}

	public function count(): int {
		// TODO: if $path is a directory, countfiles
		return 1;
	}

	/**
	 * Returns the HTTP client to use for testing.
	 *
	 * Subclasses may implement this to provide a mock.
	 *
	 * @return Client
	 */
	protected function newHttpClient() {
		return new Client( [ 'base_uri' => $this->baseUri ] );
	}

	/**
	 * Returns the base URI to use when building request URLs.
	 *
	 * Subclasses that override getHttpClient() may want to override this method as well to return a dummy.
	 *
	 * @return string|false
	 */
	protected function getBaseUri() {
		return getenv( 'PHESTER_BASE_URI' );
	}

	/**
	 * Loads phester test instruction data from the given file.
	 *
	 * Subclasses may implement this to provide fake data.
	 *
	 * @param string $file
	 *
	 * @return array
	 */
	protected function loadInstructionData( $file ) {
		return Yaml::parseFile( $file, Yaml::PARSE_CUSTOM_TAGS );
	}

}