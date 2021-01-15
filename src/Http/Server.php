<?php

namespace LiteFramework\Http;

class Server
{
	/**
	 * Server Constructor
	 */
	public function __construct() {}

	/**
	 * Get all server data
	 *
	 * @return array
	 */
	public static function all(): array
	{
		return $_SERVER;
	}

	/**
	 * cheking if a key exist inside the Server golbal
	 *
	 * @param  string $key
	 * @return bool
	 */
	public static function has(string $key): bool
	{
		return isset($_SERVER[$key]);
	}

	/**
	 * Geting a value int he server by the given key
	 *
	 * @param  string       $key
	 * @return null|mixed
	 */
	public static function get(string $key)
	{
		return static::has($key) ? $_SERVER[$key] : null;
	}

	/**
	 *
	 *
	 * @param  string         $path
	 * @return string|array
	 */
	public static function pathInfo(string $path)
	{
		return pathinfo($path);
	}
}
