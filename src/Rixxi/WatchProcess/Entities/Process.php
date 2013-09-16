<?php

namespace Rixxi\WatchProcess\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Nette;
use Kdyby;


/**
 * @ORM\Entity
 * @property string $commandLine
 * @property int|NULL $exitCode
 * @property int $running
 * @property \DateTime $started
 * @property \DateTime|NULL $finished
 * @property string $output
 */
class Process extends Kdyby\Doctrine\Entities\IdentifiedEntity implements IProcess
{

	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	protected $commandLine;

	/**
	 * @ORM\Column(type="smallint", nullable=TRUE)
	 * @var int
	 */
	protected $exitCode;

	/**
	 * @ORM\Column(type="boolean")
	 * @var bool
	 */
	protected $running;

	/**
	 * @ORM\Column(type="datetime")
	 * @var \DateTime
	 */
	protected $started;

	/**
	 * @ORM\Column(type="datetime", nullable=TRUE)
	 * @var \DateTime
	 */
	protected $finished;

	/**
	 * @ORM\OneToMany(targetEntity="Rixxi\WatchProcess\Entities\ProcessOutput", mappedBy="process", cascade={"persist"})
	 * @ORM\OrderBy({"number" = "ASC"})
	 * @var ProcessOutput[]|ArrayCollection
	 */
	protected $outputs;


	public function append($output, $error)
	{
		$processOutput = new ProcessOutput;
		$last = $this->outputs->last();
		$processOutput->setNumber($last ? ++$last->number : 1);
		$processOutput->setCreated(new \DateTime);
		$processOutput->setValue($output);
		$processOutput->setError($error);
		$processOutput->setProcess($this);
		$this->outputs[] = $processOutput;
	}


	public function getOutput()
	{
		$result = '';
		foreach ($this->outputs as $output) {
			$result .= $output->value;
		}
		return $result;
	}


	public function setExitCode($value)
	{
		$this->exitCode = (int) $value;
	}


	public function setRunning($value)
	{
		$this->running = (bool) $value;
	}


	public function setCommandLine($value)
	{
		$this->commandLine = (string) $value;
	}


	public function setStarted($value)
	{
		$this->started = $value;
	}


	public function setFinished($value)
	{
		$this->finished = $value;
	}


	public function hasErrors()
	{
		$criteria = new Criteria;
		$criteria->where($criteria->expr()->eq('error', TRUE));
		return count($this->outputs->matching($criteria)) !== 0;
	}

	public function __construct()
	{
		$this->outputs = new ArrayCollection;
	}

}
