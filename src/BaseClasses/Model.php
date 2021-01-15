<?php

namespace LiteFramework\BaseClasses;

use LiteFramework\Database\Database;

abstract class Model
{
	protected static string $tableName;

	public static function all():  ? array
	{
		return Database::table(static::$tableName)->select()->get();
	}

	/**
	 * @param int $id
	 */
	public static function find(int $id) :  ? array
	{
		return Database::table(static::$tableName)->select()->where('id', '=', $id)->get();
	}

	/**
	 * @param array $data
	 */
	public static function insert(array $data)
	{
		Database::table(static::$tableName)->insert($data)->set();
	}

	/**
	 * @param array $data
	 */
	public static function update(int $id, array $data)
	{
		Database::table(static::$tableName)->update($data)->where('id', '=', $id)->set();
	}

	/**
	 * @param int $id
	 */
	public static function delete(int $id)
	{
		Database::table(static::$tableName)->delete('id', '=', $id)->set();
	}
}
