<?php

namespace Rixxi\WatchProcess\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console;
use Symfony\Component\Process\Process;


class WatchCommand extends Console\Command\Command
{

	const DEFAULT_OUTPUT_CHUNK_SIZE = '512k';

	/** @var \Rixxi\WatchProcess\Model */
	private $model;


	protected function configure()
	{
		$this
			->setName('process:watch')
			->setDescription('Watches command.')
			->addArgument('command-line', InputArgument::REQUIRED, 'Shell command to be executed and watched')
			->addOption('chunk-size', 'c', InputOption::VALUE_REQUIRED, 'Chunk output in bytes optionally suffixed with k or M.', self::DEFAULT_OUTPUT_CHUNK_SIZE)
			->addOption('instance', 'i', InputOption::VALUE_REQUIRED, 'Id of created Rixxi\\Process\\Entities\\IProcess instance.')
			->setHelp(<<<EOT
Launches command and logs its output and some other data to entity
EOT
			);
	}


	protected function initialize(InputInterface $input, OutputInterface $output)
	{
		parent::initialize($input, $output);
		$this->model = $this->getHelper('container')->getByType('Rixxi\Process\Model');
	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$commandLine = $input->getArgument('command-line');
		if (!$this->tryParseChunkSize($input->getOption('chunk-size'), $chunkSize)) {
			$output->writeln('<error>Chunk size must be number optionally suffixed with k or M.</error>');
			return 1;
		}

		$model = $this->model;
		if ($id = $input->getOption('instance')) {
			if (!$entity = $model->getById($id)) {
				$output->writeln('<error>Rixxi\\Process\\Entities\\IProcess instance does not exist.</error>');
				return 1;
			}

		} else {
			$entity = $model->create($commandLine);
		}

		$process = new Process($commandLine);
		$process->setTimeout(NULL);
		$process->setIdleTimeout(NULL);
		$exitCode = $process->run(function ($type, $output) use ($entity, $model, $chunkSize) {
			if (strlen($output) > $chunkSize) {
				$output = str_split($output, $chunkSize);
			} else {
				$output = array($output);
			}
			foreach ($output as $chunk) {
				$model->append($entity, $chunk, $type === Process::ERR);
			}
		});
		$model->finish($entity, $exitCode);
	}


	private function tryParseChunkSize($value, &$chunkSize)
	{
		if (!preg_match('/^[0-9]+([kM])?$/i', $value, $matches)) {
			return FALSE;
		}
		$chunkSize = $matches[0] * (isset($matches[1]) ? ($matches[1] === 'k' ? 1024 : 1024 * 1024) : 1);
		return TRUE;
	}

}
