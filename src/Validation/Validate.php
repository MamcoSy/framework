<?php

namespace LiteFramework\Validation;

use LiteFramework\Url\Url;
use LiteFramework\Http\Request;
use Rakit\Validation\Validator;
use LiteFramework\Globals\Session;

/**
 *
 */
class Validate
{
	private function __construct() {}

	/**
	 * @param array $rules
	 * @param $json
	 */
	public function validate(array $rules, $json = false)
	{
		$validator = new Validator;

		$validation = $validator->make($_POST + $_FILES, $rules);
		
		$validation->validate();

		if ($validation->fails()) {
			$errors = $validation->errors();
			if ($json) {
				return ['errors' => $errors->firstOfAll()];
			} else {
				Session::set('errors', $errors);
				Session::set('old', Request::all());

				return Url::redirect(Url::previous());
			}
		}
	}
}
