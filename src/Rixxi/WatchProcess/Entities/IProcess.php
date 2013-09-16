<?php

namespace Rixxi\WatchProcess\Entities;


interface IProcess
{

	/**
	 * @param string $output
	 * @param bool $error
	 * @return void
	 */
	function append($output, $error);

	/**
	 * @return string
	 */
	function getOutput();

	/**
	 * @param int|NULL $value
	 * @return void
	 */
	function setExitCode($value);

	/**
	 * @param bool $value
	 * @return void
	 */
	function setRunning($value);

	/**
	 * @param string $value
	 * @return void
	 */
	function setCommandLine($value);

	/**
	 * @param \DateTime $value
	 * @return void
	 */
	function setStarted($value);

	/**
	 * @param \DateTime|NULL $value
	 * @return void
	 */
	function setFinished($value);

	/**
	 * @return mixed
	 */
	function getId();

}
