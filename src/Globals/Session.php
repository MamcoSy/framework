<?php

namespace LiteFramework\Globals;

class Session
{
	/**
	 * Session Constructor
	 *
	 * @return void
	 */
	private function __constrcut() {}

	/**
	 * Start new Session
	 *
	 * @return void
	 */
	public static function start(): void
	{

		if (!session_id()) {
			ini_set('session.use_only_cookies', 1);
			session_start();
		}

	}

	/**
	 * Get all sesion data
	 *
	 * @return array
	 */
	public static function all(): array
	{
		return $_SESSION;
	}

	/**
	 * cheking if a key exist inside the session golbal
	 *
	 * @param  string $key
	 * @return bool
	 */
	public static function has(string $key): bool
	{
		return isset($_SESSION[$key]);
	}

	/**
	 * Geting a value inthe session by his key
	 *
	 * @param  string       $key
	 * @return null|mixed
	 */
	public static function get(string $key)
	{
		return static::has($key) ? $_SESSION[$key] : null;
	}

	/**
	 * setting new session key and value
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return mixed
	 */
	public static function set(string $key, $value)
	{
		$_SESSION[$key] = $value;

		return $value;
	}

	/**
	 * Removing a session key
	 *
	 * @param string $key
	 */
	public static function remove(string $key): void
	{
		unset($_SESSION[$key]);
	}

	/**
	 * Flash a session key
	 *
	 * @param  string       $key
	 * @return null|mixed
	 */
	public static function flash(string $key)
	{
		$value = null;

		if (static::has($key)) {
			$value = static::get($key);
			static::remove($key);
		}

		return $value;
	}

	/**
	 * Destroy current session
	 *
	 * @return void
	 */
	public static function destroy(): void
	{

		foreach (static::all() as $key => $value) {
			static::remove($key);
		}

		session_destroy();
	}

}
