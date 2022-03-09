<?php

namespace Raydragneel\Herauth\Entities;

use CodeIgniter\Entity\Entity;

class BaseHerauthEntity extends Entity
{
	public function __construct(array $data = null)
	{
		parent::__construct($data);
	}
}