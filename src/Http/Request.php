<?php

namespace LiteFramework\Http;

class Request
{
	/**
	 * @var string
	 */
	private static $baseUrl;

	/**
	 * @var string
	 */
	private static $scriptDirectory;

	/**
	 * @var string
	 */
	private static $url;

	/**
	 * @var string
	 */
	private static $queryString;

	/**
	 * @var string
	 */
	private static $fullUrl;

	/**
	 * Request Constructor
	 */
	private function __construct() {}

	/**
	 * Set the value of base url
	 *
	 * @return void
	 */
	public static function setBaseUrl(): void
	{
		static::$scriptDirectory = str_replace('\\', '/', dirname(Server::get('SCRIPT_NAME')));
		$protocol                = Server::get('REQUEST_SCHEME') . '://';
		$hostname                = Server::get('HTTP_HOST');
		static::$baseUrl         = $protocol . $hostname . static::$scriptDirectory;
	}

	/**
	 * Set the value of full url
	 *
	 * @return void
	 */
	public static function setFullUrl(): void
	{
		$requestUri      = urldecode(Server::get('REQUEST_URI'));
		static::$fullUrl = rtrim(preg_replace('#^' . static::$scriptDirectory . '#', '', $requestUri), '/');
	}

	/**
	 * Set the value of url
	 *
	 * @return void
	 */
	public static function setUrl(): void
	{

		if (strpos(static::$fullUrl, '?')) {
			static::$url = substr(static::$fullUrl, 0, strpos(static::$fullUrl, '?'));
		} else {
			static::$url = static::$fullUrl;
		}

	}

	/**
	 * Set the value of query string
	 *
	 * @return void
	 */
	public static function setQueryString(): void
	{

		if (strpos(static::$fullUrl, '?')) {
			static::$queryString = substr(static::$fullUrl, strpos(static::$fullUrl, '?') + 1, strlen(static::$fullUrl));
		} else {
			static::$queryString = '';
		}

	}

	/**
	 * Handle the reqquest
	 *
	 * @return void
	 */
	public static function handle(): void
	{
		static::setBaseUrl();
		static::setFullUrl();
		static::setUrl();
		static::setQueryString();
	}

	/**
	 * Get the value of baseUrl
	 *
	 * @return string
	 */
	public static function baseUrl(): string
	{
		return static::$baseUrl;
	}

	/**
	 * Get the value of url
	 *
	 * @return string
	 */
	public static function url(): string
	{
		return static::$url;
	}

	/**
	 * Get the value of queryString
	 *
	 * @return string
	 */
	public static function queryString(): string
	{
		return static::$queryString;
	}

	/**
	 * Get the value of fullUrl
	 *
	 * @return string
	 */
	public static function fullUrl(): string
	{
		return static::$fullUrl;
	}

	/**
	 * Get the value of scriptDirectory
	 *
	 * @return string
	 */
	public static function scriptDirectory(): string
	{
		return static::$scriptDirectory;
	}

	/**
	 * Get the value of request method
	 *
	 * @return string
	 */
	public static function method(): string
	{
		return strtoupper(Server::get('REQUEST_METHOD'));
	}

	/**
	 * checking if the given key exist int the given global
	 *
	 * @param  array  $global
	 * @param  string $key
	 * @return bool
	 */
	public static function has(array $global, string $key): bool
	{
		return array_key_exists($key, $global);
	}

	/**
	 * get a value of the given key in the give global
	 *
	 * @param  array        $global
	 * @param  string       $key
	 * @return null|mixed
	 */
	public static function value(?array $global, string $key)
	{
		$global = isset($global) ? $global : $_REQUEST;

		return static::has($global, $key) ? $global[$key] : null;
	}

	/**
	 * Get a value from $_GET Global
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public static function get(string $key)
	{
		return static::value($_GET, $key);
	}

	/**
	 * Get a value from $_POST Global
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public static function post(string $key)
	{
		return static::value($_POST, $key);
	}

	/**
	 * set a value in request Globals
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return mixed
	 */
	public static function set(string $key, $value)
	{
		$_REQUEST[$key] = $value;
		$_GET[$key]     = $value;
		$_POST[$key]    = $value;

		return $value;
	}

	/**
	 * get all request data
	 *
	 * @return array
	 */
	public static function all(): array
	{
		return $_REQUEST;
	}

}
