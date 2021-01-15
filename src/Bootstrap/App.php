<?php

namespace LiteFramework\Bootstrap;

use LiteFramework\Http\Request;
use LiteFramework\Config\Config;
use LiteFramework\Http\Response;
use LiteFramework\Router\Router;
use LiteFramework\Globals\Session;
use LiteFramework\Exceptions\Whoops;
use LiteFramework\FileSystem\FileSystem;

class App
{
	/**
	 * App Constructor
	 */
	private function __construct() {}

	public static function run(): void
	{
		// handle Errors with Whoops
		Whoops::handle();

		// loading Config
		Config::load('config');

		// Start new session
		Session::start();

		// handle Request
		Request::handle();

		// handle Routes
		FileSystem::includeDirectory('routes');
		$response = Router::handle();

		// Sending Response
		Response::output($response);
	}
}
