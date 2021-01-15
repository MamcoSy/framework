<?php

namespace LiteFramework\Router;

use LiteFramework\Http\Request;
use LiteFramework\Exceptions\RouterException;

class Router
{
	/**
	 * Collection of all routes
	 *
	 * @var array
	 */
	private static $routeCollection = [];

	/**
	 * Route url prefix
	 *
	 * @var string
	 */
	private static $routePrefix = null;

	/**
	 * Route middleware
	 *
	 * @var string
	 */
	private static $routeMiddleware = null;

	/**
	 * Router Constructor
	 */
	private function __construct() {}

	/**
	 * Adding new route
	 *
	 * @param  string                $routeType
	 * @param  string                $routeUrl
	 * @param  string|callable|array $routeCallback
	 * @return void
	 */
	public static function addRoute(string $routeType, string $routeUrl, $routeCallback): void
	{

		foreach (explode('|', $routeType, ) as $type) {
			static::$routeCollection[strtoupper($type)][] = [
				'url'        => rtrim(static::$routePrefix . '/' . trim($routeUrl, '/'), '/'),
				'callback'   => $routeCallback,
				'middleware' => trim(static::$routeMiddleware, '|')
			];
		}

	}

	/**
	 * Adding new GET route
	 *
	 * @param  string                $routeUrl
	 * @param  string|callable|array $routeCallback
	 * @return void
	 */
	public static function get(string $routeUrl, $routeCallback): void
	{
		static::addRoute('GET', $routeUrl, $routeCallback);
	}

	/**
	 * Adding new POST route
	 *
	 * @param  string                $routeUrl
	 * @param  string|callable|array $routeCallback
	 * @return void
	 */
	public static function post(string $routeUrl, $routeCallback): void
	{
		static::addRoute('POST', $routeUrl, $routeCallback);
	}

	/**
	 * Adding new GET and POST route
	 *
	 * @param  string                $routeUrl
	 * @param  string|callable|array $routeCallback
	 * @return void
	 */
	public static function any(string $routeUrl, $routeCallback): void
	{
		static::addRoute('GET|POST', $routeUrl, $routeCallback);
	}

	/**
	 * Get all routes
	 *
	 * @param  string  $type
	 * @return array
	 */
	public static function getRouteCollection(string $type)
	{
		return static::$routeCollection[$type];
	}

	/**
	 * Grouping routes with given prefix
	 *
	 * @param  string      $routePrefix
	 * @param  $callback
	 * @return void
	 */
	public static function group(string $routePrefix, callable $callback): void
	{
		$parentPrefix        = static::$routePrefix;
		static::$routePrefix = rtrim($parentPrefix . $routePrefix);
		call_user_func($callback);
		static::$routePrefix = $parentPrefix;
	}

	/**
	 * Grouping routes with given prefix
	 *
	 * @param  string      $routePrefix
	 * @param  $callback
	 * @return void
	 */
	public static function middleware(string $routeMiddleware, callable $callback): void
	{
		$parentMiddleware        = static::$routeMiddleware;
		static::$routeMiddleware = $parentMiddleware . '|' . $routeMiddleware;
		call_user_func($callback);
		static::$routeMiddleware = $parentMiddleware;
	}

	/**
	 * Match routes with request and invoke them
	 *
	 * @return mixed
	 */
	public static function handle()
	{
		$requestUrl = Request::url();
		$matched    = false;

		foreach (static::getRouteCollection(Request::method()) as $route) {
			$utlToMatch = preg_replace('/\/{(.*?)}/', '/(.*?)', $route['url']);
			$utlToMatch = "#^{$utlToMatch}$#";

			if (preg_match($utlToMatch, $requestUrl, $parameters)) {
				$matched = true;
				array_shift($parameters);
				$parameters = array_values($parameters);

				foreach ($parameters as $value) {

					if (strpos($value, '/')) {
						$matched = false;
					}

				}

				if ($matched) {
					return static::invoke($route, $parameters);
				}

			}

		}

		return render('errors.404');

	}

	/**
	 * Invoke a route
	 *
	 * @param  array   $route
	 * @return mixed
	 */
	public static function invoke(array $route, array $parameters)
	{
		static::executeMiddleware($route);

		if (is_callable($route['callback']) || is_array($route['callback'])) {
			return call_user_func_array($route['callback'], $parameters);
		}

		if (is_string($route['callback'])) {

			if (strpos($route['callback'], '@')) {
				list($controllerName, $method) = explode('@', $route['callback']);
				$fullControllerName            = 'App\\Controllers\\' . $controllerName;

				if (class_exists($fullControllerName)) {
					$controllerObject = new $fullControllerName();

					if (method_exists($controllerObject, $method)) {
						return call_user_func_array([$controllerObject, $method], $parameters);
					}

					throw new RouterException("method $method does not exist in $fullControllerName");
				}

				throw new RouterException("Class $fullControllerName does not exist for route {$route['url']}");
			}

			throw new RouterException("Invalid callback for url {$route['url']}");
		}

		throw new RouterException("Invalid callback for url {$route['url']}");

	}

	/**
	 * Execute route middleware
	 *
	 * @param  array  $route
	 * @return void
	 */
	public static function executeMiddleware(array $route): void
	{

		if ($route['middleware']) {

			foreach (explode('|', $route['middleware']) as $middleware) {
				$middleware = 'App\\Middlewares\\' . $middleware;

				if (class_exists($middleware)) {
					$middlewareObject = new $middleware();

					if (method_exists($middlewareObject, 'handle')) {
						call_user_func_array([$middleware, 'handle'], []);
					} else {
						throw new RouterException("Middleware '{$route['middleware']}' does not have 'handle' method");
					}

				} else {
					throw new RouterException("Middleware '{$route['middleware']}' does not exist for url '{$route['url']}'");
				}

			}

		}

	}

}
