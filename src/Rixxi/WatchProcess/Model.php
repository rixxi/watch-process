<?php

namespace Rixxi\WatchProcess;

use Kdyby;
use Nette;
use Rixxi;


class Model extends Nette\Object
{

	/** @var \Kdyby\Doctrine\EntityDao */
	private $dao;


	public function __construct(Kdyby\Doctrine\EntityDao $dao)
	{
		$this->dao = $dao;
	}


	/**
	 * @param string $commandLine
	 * @return Entities\IProcess
	 */
	public function create($commandLine)
	{
		$process = $this->createProcessEntity();
		$process->setCommandLine($commandLine);
		$process->setExitCode(NULL);
		$process->setRunning(TRUE);
		$process->setStarted(new \DateTime);
		$process->setFinished(NULL);
		$this->dao->save($process);
		return $process;
	}


	/**
	 * @param mixed $id
	 * @return Entities\IProcess|null
	 */
	public function getById($id)
	{
		return $this->dao->find($id);
	}


	public function append(Entities\IProcess $process, $output, $error = FALSE)
	{
		$process->append($output, $error);
		$this->dao->save($process);
	}


	public function finish(Entities\IProcess $process, $exitCode = 0)
	{
		$process->setFinished(new \DateTime);
		$process->setExitCode($exitCode);
		$process->setRunning(FALSE);
		$this->dao->save($process);
	}


	/**
	 * @return Entities\IProcess
	 */
	private function createProcessEntity()
	{
		$className = $this->dao->getClassName();
		return new $className;
	}

}
