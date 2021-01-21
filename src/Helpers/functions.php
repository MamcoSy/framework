<?php

use LiteFramework\Config\Config;

if (!function_exists('config')) {
	/**
	 * get a configuration
	 *
	 * @param  string     $research
	 * @param  $default
	 * @return mixed
	 */
	function config(string $research, $default = null)
	{
		return Config::get($research, $default);
	}

}

if (!function_exists('dd')) {
	/**
	 * dump and die
	 *
	 * @param  string $element
	 * @return void
	 */
	function dd($element): void
	{
		echo '<pre>';
		var_dump($element);
		echo '</pre>';
		die;
	}

}

if (!function_exists('dump')) {
	/**
	 * dump and die
	 *
	 * @param  string $element
	 * @return void
	 */
	function dump($element): void
	{
		echo '<pre>';
		var_dump($element);
		echo '</pre>';
	}

}

if (!function_exists('render')) {
	/**
	 * @param string $view
	 * @param array $data
	 */
	function render(string $view, array $data = [])
	{
		return LiteFramework\View\View::render($view, $data);
	}

}

if (!function_exists('request')) {
	/**
	 * @param string $key
	 */
	function request(string $key)
	{
		return LiteFramework\Http\Request::value(null, $key);
	}
}

if (!function_exists('redirect')) {
	/**
	 * @param string $path
	 */
	function redirect(string $path)
	{
		return LiteFramework\Url\Url::redirect($path);
	}
}

if (!function_exists('previous')) {
	/**
	 *
	 */
	function previous()
	{
		return LiteFramework\Url\Url::previous();
	}
}

if (!function_exists('url')) {
	/**
	 * @param string $path
	 */
	function url(string $path)
	{
		return LiteFramework\Url\Url::path($path);
	}
}

if (!function_exists('asset')) {
	/**
	 * @param string $path
	 */
	function asset(string $path)
	{
		return LiteFramework\Url\Url::path($path);
	}
}

if (!function_exists('session')) {
	/**
	 * @param string $key
	 */
	function session(string $key)
	{
		return LiteFramework\Globals\Session::get($key);
	}
}

if (!function_exists('flash')) {
	/**
	 * @param string $key
	 */
	function flash(string $key)
	{
		return LiteFramework\Globals\Session::flash($key);
	}
}

if (!function_exists('auth')) {
	/**
	 * @param string $table
	 */
	function auth(string $table)
	{
		$auth = LiteFramework\Globals\Session::get($table) ?? LiteFramework\Globals\Cookie::get($table);

		return LiteFramework\Database\Database::table($table)->where('id', '=', $auth)->first();
	}
}
