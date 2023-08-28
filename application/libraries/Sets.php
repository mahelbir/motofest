<?php

use Noodlehaus\Config;

class Sets extends Config
{
	/**
	 * @var string
	 */
	private $file;

	/**
	 * @param string $file
	 */
	public function __construct(string $file = 'settings')
	{
		$this->file = APPPATH . $file . '.json';
		parent::__construct($this->file);
	}

	/**
	 * @return void
	 */
	public function save(): void
	{
		$this->toFile($this->file);
	}

}
