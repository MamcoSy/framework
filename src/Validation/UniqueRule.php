<?php

namespace LiteFramework\Validation\Rules;

use Rakit\Validation\Rule;
use LiteFramework\Database\Database;

class UniqueRule extends Rule
{
	/**
	 * @var string
	 */
	protected $message = ':attribute :value has been used';

	/**
	 * @var array
	 */
	protected $fillableParams = ['table', 'column', 'except'];

	/**
	 * @var mixed
	 */
	protected $pdo;

	/**
	 * @param $value
	 */
	public function check($value): bool
	{
		// make sure required parameters exists
		$this->requireParameters(['table', 'column']);

		// getting parameters
		$column = $this->parameter('column');
		$table  = $this->parameter('table');
		$except = $this->parameter('except');

		if ($except and $except == $value) {
			return true;
		}

		// do query
		$stmt = Database::getConeection()->prepare("select count(*) as count from `{$table}` where `{$column}` = :value");
		$stmt->bindParam(':value', $value);
		$stmt->execute();
		$data = $stmt->fetch(PDO::FETCH_ASSOC);

		// true for valid, false for invalid

		return intval($data['count']) === 0;
	}
}
