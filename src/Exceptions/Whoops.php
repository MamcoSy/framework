<?php

namespace LiteFramework\Exceptions;

class Whoops
{
	/**
	 * Whoops Constructor
	 */
	private function __construct() {}

	/**
	 * Handle errors
	 *
	 * @return void
	 */
	public static function handle(): void
	{
		$whoops = new \Whoops\Run();
		$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
		$whoops->register();
	}
}
