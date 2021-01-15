<?php

namespace LiteFramework\Globals;

class Cookie
{
	/**
	 * Cookie Constructor
	 *
	 * @return void
	 */
	private function __constrcut() {}

	/**
	 * Get all cookie data
	 *
	 * @return array
	 */
	public static function all(): array
	{
		return $_COOKIE;
	}

	/**
	 * cheking if a key exist inside the cookie golbal
	 *
	 * @param  string $key
	 * @return bool
	 */
	public static function has(string $key): bool
	{
		return isset($_COOKIE[$key]);
	}

	/**
	 * Geting a value in the cookie by his key
	 *
	 * @param  string       $key
	 * @return null|mixed
	 */
	public static function get(string $key)
	{
		return static::has($key) ? $_COOKIE[$key] : null;
	}

	/**
	 * setting new cookie key and value
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return mixed
	 */
	public static function set(string $key, $value)
	{
		$expireTime = time() * (1 * 365 * 24 * 60 * 60);
		setcookie($key, $value, $expireTime, '/', '', false, true);

		return $value;
	}

	/**
	 * Removing a cookie key
	 *
	 * @param string $key
	 */
	public static function remove(string $key): void
	{
		setcookie($key, null, -1, '/');
	}

	/**
	 * Destroy current cokkie
	 *
	 * @return void
	 */
	public static function destroy(): void
	{

		foreach (static::all() as $key => $value) {
			static::remove($key);
		}

	}

}
