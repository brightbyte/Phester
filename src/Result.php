<?php

namespace Wikimedia\Phester;

interface Result {

	/**
	 * Initializes the result object.
	 * Must be called exactly once.
	 * Must be called before any call to logOutcome.
	 *
	 * @param string $name The suite's name.
	 * @param string $description The suite's description.
	 */
	public function init( $name, $description );

	/**
	 * Logs the outcome of an interaction.
	 * May be called multiple times.
	 * Must not be called before init(), or after close().
	 *
	 * @param string $description a description of the interaction.
	 * @param string[] $errors Error messages, if any.
	 */
	public function logOutcome( $description, array $errors );

	/**
	 * Signal that this result object is complete.
	 * Must be called exactly once.
	 * Must be called after all calls to logOutcome.
	 */
	public function close();
}