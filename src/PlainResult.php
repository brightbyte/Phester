<?php

namespace Wikimedia\Phester;

class PlainResult implements Result {

	/** @var string[] */
	private $lines = [];

	/**
	 * @return string[]
	 */
	public function getLines() {
		return $this->lines;
	}

	/**
	 * @param string|string[] $message
	 */
	private function log( $message, $prefix = '' ) {
		foreach ( (array)$message as $m ) {
			$this->lines[] = "$prefix$m";
		}
	}

	public function init( $name, $description ) {
		$this->log( "- Suite: " . $name );
	}

	public function logOutcome( $description, array $errors ) {
		if ( $errors ) {
			$this->log( "! $description failed:" );
			$this->log( $errors, "\t" );
		}
	}

	/**
	 * Signal that this result object is complete.
	 */
	public function close() {
		// noop
	}
}