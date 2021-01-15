<?php

namespace LiteFramework\Config;

use LiteFramework\FileSystem\FileSystem;

class Config
{
	/**
	 * @var array
	 */
	private static $data = [];

	/**
	 * @var array
	 */
	private static $default = null;

	/**
	 * Load Configuration data
	 *
	 * @param  $file
	 * @return void
	 */
	public static function load($directory): void
	{
		$files = array_diff(scandir(FileSystem::getPath($directory)), ['.', '..']);

		foreach ($files as $file) {
			$fileName                = str_replace('.php', '', $file);
			static::$data[$fileName] = FileSystem::requireFile($directory . DS . $file);
		}

	}

	/**
	 * Getting configuration
	 *
	 * @param  string     $research
	 * @param  $default
	 * @return mixed
	 */
	public static function get(string $research, $default = null)
	{
		static::$default = $default;

		$segments = explode('.', $research);
		$data     = static::$data;

		foreach ($segments as $key) {

			if (isset($data[$key])) {
				$data = $data[$key];
			} else {
				$data = static::$default;
				break;
			}

		}

		return $data;
	}

}
