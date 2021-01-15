<?php

namespace LiteFramework\Database;

use LiteFramework\Http\Request;
use LiteFramework\Database\Database;
use LiteFramework\Exceptions\DatabaseException;

class QueryBuilder
{
	/**
	 * @var string
	 */
	public $tableName;

	/**
	 * @var string
	 */
	public $query;

	/**
	 * @var bool
	 */
	public $hasWhere;

	/**
	 * @var bool
	 */
	public $hasHaving;

	/**
	 * @var array
	 */
	public $params = [];

	/**
	 * @var bool
	 */
	public $hasOrder;

	/**
	 * QueryBuilder Constructor
	 *
	 * @param string $tableName
	 */
	public function __construct(string $tableName)
	{
		$this->tableName = $tableName;
		$this->query     = '';
		$this->hasWhere  = false;
		$this->hasHaving = false;
		$this->hasOrder  = false;
		$this->params    = [];
	}

	public function clear()
	{
		$this->query     = '';
		$this->hasWhere  = false;
		$this->hasHaving = false;
		$this->hasOrder  = false;
		$this->params    = [];
	}

	/**
	 * @param string $table
	 */
	public function setTable(string $table)
	{
		$this->tableName = $table;
	}

	/**
	 * Cheking if the tableName is set
	 *
	 * @return void
	 */
	private function checkTable(): void
	{

		if (!$this->tableName) {
			throw new DatabaseException('Unknow table name');
		}

	}

	/**
	 * Getting the query code
	 *
	 * @return string
	 */
	private function getQuery(): string
	{
		return preg_replace('/\s+/', ' ', $this->query);
	}

	/**
	 * getting parameters
	 *
	 * @return array
	 */
	private function getParams(): array
	{
		return $this->params;
	}

	/**
	 * selec columns
	 *
	 * @param  $colums
	 * @return self
	 */
	public function select(...$colums): self
	{
		$this->checkTable();
		$colums      = !empty(func_get_args()) ? implode(', ', func_get_args()) : '*';
		$this->query = "SELECT {$colums} FROM {$this->tableName} ";

		return $this;
	}

	/**
	 * insert new row
	 *
	 * @param  $colums
	 * @return self
	 */
	public function insert(array $data): self
	{
		$this->query .= " INSERT INTO `{$this->tableName}`";
		$keys          = [];
		$questionMarks = [];
		$values        = [];

		foreach ($data as $key => $value) {
			$keys[]          = $key;
			$questionMarks[] = '?';
			$this->params[]  = $value;
		}

		$this->query .= '(' . implode(', ', $keys) . ')';
		$this->query .= ' VALUES(' . implode(', ', $questionMarks) . ')';

		return $this;
	}

	/**
	 * update a row
	 *
	 * @param  $colums
	 * @return self
	 */
	public function update(array $data): self
	{
		$this->query .= " UPDATE `{$this->tableName}` SET ";
		$keys          = [];
		$questionMarks = [];
		$values        = [];

		foreach ($data as $key => $value) {
			$keys[]         = "{$key} = ?";
			$this->params[] = $value;
		}

		$this->query .= implode(', ', $keys);

		return $this;
	}

	/**
	 * delete a row
	 *
	 * @param string $column
	 * @param string $operator
	 * @param string $value
	 */
	public function delete(string $column, string $operator, string $value)
	{
		$this->query .= "DELETE FROM `{$this->tableName}` ";
		$this->where($column, $operator, $value);

		return $this;
	}

	/**
	 * join tables
	 *
	 * @param string $table
	 * @param string $condition1
	 * @param string $operator
	 * @param string $condition2
	 * @param string $type
	 */
	public function join(
		string $table,
		string $condition1,
		string $operator,
		string $condition2,
		string $type = 'INNER'
	): self{
		$this->query .= $type . ' JOIN ';
		$this->query .= $table . ' ON ' . $condition1 . ' ' . $operator . ' ' . $condition2 . ' ';

		return $this;
	}

	/**
	 * join tables with inner join
	 *
	 * @param  string $table
	 * @param  string $condition1
	 * @param  string $operator
	 * @param  string $condition2
	 * @return self
	 */
	public function innerJoin(
		string $table,
		string $condition1,
		string $operator,
		string $condition2
	): self {
		return $this->join($table, $condition1, $operator, $condition2, 'INNER');
	}

	/**
	 * join tables with left join
	 *
	 * @param  string $table
	 * @param  string $condition1
	 * @param  string $operator
	 * @param  string $condition2
	 * @return self
	 */
	public function leftJoin(
		string $table,
		string $condition1,
		string $operator,
		string $condition2
	): self {
		return $this->join($table, $condition1, $operator, $condition2, 'LEFT');
	}

	/**
	 * join tables with right join
	 *
	 * @param  string $table
	 * @param  string $condition1
	 * @param  string $operator
	 * @param  string $condition2
	 * @return self
	 */
	public function rightJoin(
		string $table,
		string $condition1,
		string $operator,
		string $condition2
	): self {
		return $this->join($table, $condition1, $operator, $condition2, 'RIGHT');
	}

	/**
	 * Where condition
	 *
	 * @param string $column
	 * @param string $operator
	 * @param string $value
	 * @param string $type
	 */
	public function where(string $column, string $operator, $value,  ? string $type = null) : self
	{
		$condition = " `{$column}` {$operator} ? ";

		if (!$this->hasWhere) {
			$this->query .= ' WHERE ' . $condition . ' ';
		} else {

			if (!$type) {
				$this->query .= ' AND ' . $condition . ' ';
			} else {
				$this->query .= ' ' . strtoupper($type) . ' ' . $condition . ' ';
			}

		}

		$this->params[] = htmlspecialchars($value);
		$this->hasWhere = true;

		return $this;
	}

	/**
	 * adding or where to query
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  string $value
	 * @return self
	 */
	public function orWhere(string $column, string $operator, string $value): self
	{
		return $this->where($column, $operator, $value, 'or');
	}

	/**
	 * Group by
	 *
	 * @return self
	 */
	public function groupBy()
	{
		$this->query .= ' GROUP BY ' . implode(', ', func_get_args()) . ' ';

		return $this;
	}

	/**
	 * Having
	 *
	 * @param  string      $column
	 * @param  string      $operator
	 * @param  string      $value
	 * @param  null|string $type
	 * @return self
	 */
	public function having(string $column, string $operator, string $value,  ? string $type = null) : self
	{
		$having = "`{$column}` {$operator} ? ";

		if (!$this->hasHaving) {
			$this->query .= ' HAVING ' . $having;
		} else {
			$this->query .= ' AND ' . $having;
		}

		$this->params[]  = htmlspecialchars($value);
		$this->hasHaving = true;

		return $this;
	}

	/**
	 * Order By
	 *
	 * @param  sting      $column
	 * @param  nul|string $type
	 * @return self
	 */
	public function orderBy(string $column,  ? string $type = null)
	{
		$separator = $this->hasOrder ? ' , ' : ' ORDER BY ';
		$type      = strtoupper($type);
		$type      = (!is_null($type) && in_array($type, ['ASC', 'DESC'])) ? $type : 'ASC';
		$this->query .= $separator . ' ' . $column . ' ' . $type;
		$this->hasOrder = true;

		return $this;
	}

	/**
	 * limit
	 *
	 * @param  int    $limit
	 * @return self
	 */
	public function limit(int $limit)
	{
		$this->query .= " LIMIT {$limit} ";

		return $this;
	}

	/**
	 * Offset
	 *
	 * @param  int    $offset
	 * @return self
	 */
	public function offset(int $offset)
	{
		$this->query .= " OFFSET {$offset} ";

		return $this;
	}

	/**
	 * execute
	 *
	 * @param $fetchMode
	 */
	public function execute()
	{
		$result = Database::getConeection()->prepare($this->getQuery());
		$result->execute($this->getParams());

		return $result;
	}

	/**
	 * getting results
	 *
	 * @return mixed
	 */
	public function get()
	{
		return $this->execute()->fetchAll();
	}

	/**
	 * get one result
	 *
	 * @return mixed
	 */
	public function first()
	{
		return $this->execute()->fetch();
	}

	/**
	 * execute statement without results
	 *
	 * @return mixed
	 */
	public function set()
	{
		return $this->execute();
	}

	/**
	 * @return mixed
	 */
	public function count()
	{
		return $this->execute()->rowCount();
	}

	/**
	 * @param int $itemPerPage
	 */
	public function paginate(int $itemPerPage = 15)
	{
		$results     = $this->execute();
		$pages       = ceil($results->rowCount() / $itemPerPage);
		$page        = Request::get('page');
		$currentPage = (!is_numeric($page) || Request::get('page') < 1) ? '1' : $page;
		$offset      = ($currentPage - 1) * $itemPerPage;
		$this->limit($itemPerPage);
		$this->offset($offset);
		$data = $this->execute()->fetchAll();

		return [
			'data'          => $data,
			'item_per_page' => $itemPerPage,
			'pages'         => $pages,
			'current_page'  => $currentPage
		];
	}

}
