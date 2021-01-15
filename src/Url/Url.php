<?php

namespace LiteFramework\Url;

use LiteFramework\Http\Server;
use LiteFramework\Http\Request;

class Url
{

	/**
	 * Url Constructor
	 */
	private function __construct() {}

	/**
	 * return full url path
	 *
	 * @param  string   $path
	 * @return string
	 */
	public static function path(string $path): string
	{
		return Request::baseUrl() . '/' . trim($path, '/');
	}

	/**
	 * Return the previous url
	 *
	 * @return string
	 */
	public static function previous(): string
	{
		return Server::get('HTTP_REFERER');
	}

	/**
	 * Redirect to new url
	 *
	 * @param  $path
	 * @return void
	 */
	public static function redirect(string $path): void
	{
		header('location: ' . $path);
		exit;
	}
}
