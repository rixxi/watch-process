<?php

namespace Rixxi\WatchProcess\Entities;

use Doctrine\ORM\Mapping as ORM;
use Nette;
use Kdyby;


/**
 * @ORM\Entity
 * @property-read \DateTime $created
 * @property-read int $number
 * @property-read string $value
 * @method setProcess(Process $value)
 * @method setCreated(\DateTime $value)
 * @method setNumber(int $value)
 * @method setValue(string $value)
 * @method setError(string $value)
 */
class ProcessOutput extends Kdyby\Doctrine\Entities\IdentifiedEntity
{

	/**
	 * @ORM\ManyToOne(targetEntity="Rixxi\WatchProcess\Entities\Process", inversedBy="outputs")
	 */
	protected $process;

	/**
	 * @ORM\Column(type="datetime")
	 * @var \DateTime
	 */
	protected $created;

	/**
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	protected $number;

	/**
	 * @ORM\Column(type="boolean")
	 * @var bool
	 */
	protected $error;

	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	protected $value;

}
