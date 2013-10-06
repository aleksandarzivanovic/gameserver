<?php

class VariablesTypes {

	const VARIABLE_BAD_QUERY_ID = 10001;
	const VARIABLE_GOOD_QUERY_ID = 10002;
	const VARIABLE_TYPE_ARRAY = 90001;

	public static $_types = [
		self::VARIABLE_BAD_QUERY_ID => [
			'message' => 'Invalid Quert ID',
			'extras' => ['status' => 0010],
			'value' => -1,
		],
		self::VARIABLE_GOOD_QUERY_ID => [
			'message' => 'Query start offset.',
			'extras' => ['status' => 200],
			'value' => 0,
		],
		self::VARIABLE_TYPE_ARRAY => [
			'message' => 'Converted to array',
			'extras' => ['status' => 200],
			'value' => [],
		],
	];
	/*
	public static function setVari (array $options) {
		foreach (static::$_types as $key => $output) {
			if (isset ($options[$key])) {

				if (!isset ($output['value'])) {
					$output['value'] = $key;
				}

				$return[] = $output;
			}
		}

		if (count ($return) == 1) {
			$return = $return[0];
		}

		return (object) $return;
	}
	*/

}