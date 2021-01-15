<?php

namespace LiteFramework\Database;

use PDO;
use LiteFramework\Database\QueryBuilder;
use LiteFramework\Exceptions\DatabaseException;

class Database
{
	/**
	 * @var PDO
	 */
	protected static $connexion = null;

	/**
	 * @var QueryBuilder
	 */
	protected static $queryBuilderInstance = null;

	/**
	 * Database Constructor
	 */
	private function __construct() {}

	/**
	 * get database instance
	 *
	 * @param  string $table
	 * @return self
	 */
	public static function getQueryBuilderInstance(string $table): QueryBuilder
	{

		if (static::$queryBuilderInstance === null) {
			static::$queryBuilderInstance = new QueryBuilder($table);
		} else {
			static::$queryBuilderInstance->clear();
			static::$queryBuilderInstance->setTable($table);
		}

		return static::$queryBuilderInstance;
	}

	/**
	 * Getting connection to the database
	 *
	 * @return PDO
	 */
	public static function getConeection(): PDO
	{

		if (static::$connexion === null) {
			$dsn = 'mysql:dbname=' . config('database.name');
			$dsn .= ';host=' . config('database.host');
			$dsn .= ';port=' . config('database.port');
			$user   = config('database.user');
			$pswd   = config('database.pswd');
			$params = [
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . config('database.charset') . ' COLLATE ' . config('database.collation')
			];

			try {
				static::$connexion = new PDO($dsn, $user, $pswd, $params);
			} catch (DatabaseException $e) {
				throw new DatabaseException('Failed to connect');
			}

		}

		return static::$connexion;
	}

	/**
	 * select table
	 *
	 * @param  string         $table
	 * @return QueryBuilder
	 */
	public static function table(string $table): QueryBuilder
	{
		return static::getQueryBuilderInstance($table);
	}

}
