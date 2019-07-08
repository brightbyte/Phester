<?php

namespace Wikimedia\Phester\PHPUnit;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use Wikimedia\Phester\Result;

class PHPUnitResultAdapter implements Result {

	/** @var Test */
	private $test;

	/** @var TestResult */
	private $phpunitResult;

	/**
	 * PHPUnitResultAdapter constructor.
	 *
	 * @param Test $test
	 * @param TestResult $phpunitResult
	 */
	public function __construct( Test $test, TestResult $phpunitResult ) {
		$this->test = $test;
		$this->phpunitResult = $phpunitResult;
	}

	/**
	 * Sets the name of the suite the result belongs to.
	 * Must be called before any call to logOutcome.
	 *
	 * @param string $name
	 * @param string $description
	 */
	public function init( $name, $description ) {
		// noop
	}

	/**
	 * Logs the outcome of an interaction.
	 *
	 * @param string $description a description of the interaction.
	 * @param string[] $errors Error messages, if any.
	 */
	public function logOutcome( $description, array $errors ) {
		$this->phpunitResult->startTest( $this->test );
		foreach ( $errors as $e ) {
			$this->phpunitResult->addFailure(
				$this->test,
				new AssertionFailedError( "$e (in $description)" ),
				0
			);
		}
		$this->phpunitResult->endTest( $this->test, 0 );
	}

	/**
	 * Signal that this result object is complete.
	 */
	public function close() {
		// noop
	}
}