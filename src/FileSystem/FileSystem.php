<?php

namespace LiteFramework\FileSystem;

use LiteFramework\Exceptions\FileSystemException;

class FileSystem
{
	const ROOT   = ROOT_DIR;
	const VIEWS  = ROOT_DIR . DS . 'views';
	const ROUTES = ROOT_DIR . DS . 'routes';
	const APP    = ROOT_DIR . DS . 'app';
	const CONFIG = ROOT_DIR . DS . 'config';

	/**
	 * FileSystem Constructor
	 */
	public function __construct() {}

	/**
	 * Getting proper path
	 *
	 * @param  string   $path
	 * @return string
	 */
	public static function getPath(string $path): string
	{
		$path = static::ROOT . DS . trim($path, '/');
		$path = str_replace(['\\', '/'], DS, $path);

		return $path;
	}

	/**
	 * Cheking if the given path exist
	 *
	 * @param  string $path
	 * @return bool
	 */
	public static function has(string $path): bool
	{
		return file_exists(static::getPath($path));
	}

	/**
	 * Require the given file
	 *
	 * @param  string $path
	 * @return void
	 */
	public static function requireFile(string $path)
	{

		if (static::has($path)) {
			return require_once static::getPath($path);
		}

		throw new FileSystemException("failed to require $path");
	}

	/**
	 * Include the given file
	 *
	 * @param  string $path
	 * @return void
	 */
	public static function includeFile(string $path)
	{

		if (static::has($path)) {
			return include_once static::getPath($path);
		}

		throw new FileSystemException("failed to include $path");
	}

	/**
	 * Require all file of the given directory
	 *
	 * @param  string $directory
	 * @return void
	 */
	public static function requireDirectory(string $directory)
	{
		$files = array_diff(scandir(static::getPath($directory)), ['.', '..']);

		foreach ($files as $file) {
			$fileName = $directory . DS . $file;

			if (static::has($fileName)) {
				require_once static::getPath($directory) . DS . $file;
			}

		}

	}

	/**
	 * Include all file of the given directory
	 *
	 * @param  string $directory
	 * @return void
	 */
	public static function includeDirectory(string $directory)
	{
		$files = array_diff(scandir(static::getPath($directory)), ['.', '..']);

		foreach ($files as $file) {
			$fileName = $directory . DS . $file;

			if (static::has($fileName)) {
				include static::getPath($directory) . DS . $file;
			}

		}

	}

}
