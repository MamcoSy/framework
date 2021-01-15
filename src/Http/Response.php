<?php

namespace LiteFramework\Http;

class Response
{
	/**
	 * Output the response
	 *
	 * @param  $data
	 * @return void
	 */
	public static function output($data): void
	{

		if (!$data) {
			return;
		}

		if (!is_string($data)) {
			http_response_code(200);
			header('Content-Type: text/json; charset=utf-8');
			echo json_encode($data);
			exit();
		}

		http_response_code(200);
		header('Content-Type: text/html; charset=utf-8');
		echo $data;
		exit();
	}

}
