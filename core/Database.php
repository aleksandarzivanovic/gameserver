<?php


class Database {

	protected static $_query;
	protected static $_lastPreparedQuery;
	protected static $_lastExecutedQuery;
	protected static $_insertID;
	protected static $_numRows;
	protected static $_affectedRows;
	protected static $_queryErrors;
	protected static $_outLogInformations;

	public static function prepare ($query) {
		if (isset ($id))
			unset ($id);
			
		$id = self::newQueryID();

		static::$_lastPreparedQuery = [
			'id' => $id,
			'query' => $query
		];

		if (!is_array(static::$_query)) {
			Arrays::prepare (static::$_query);
		}

		Arrays::setValue (static::$_query, ['query' => $query], (int) $id);

		return (int) $id;
	}

	public static function set ($placeholder, $value, $query_id = null) {

		$setQueryID = ($query_id == null ? (int) static::$_lastPreparedQuery['id'] : (int) $query_id);
		$tmpValue = '';

		if (is_array ($placeholder)) {

			$pHolder['placeholders'] = [];

			foreach ($placeholder as $placeHolderKey => $placeHolderValue) {

				$tmpValue = $value[$placeHolderKey];
				self::clearValue ($tmpValue);

				$pHolder['placeholder'][$placeHolderKey] = $tmpValue;

			}
		} else {

			self::clearValue($value);

			$pHolder['placeholders'][$placeholder] = $value;

		}

		Arrays::setValue (static::$_query, $pHolder, $setQueryID);
		
		return (int) $setQueryID;
	}

	private static function freeID ($key = null) {

		if (!is_array(static::$_query))
			return 0;

		$tmpKey = $key;

		if ($key === null) {
			for ($i = 0; $i < count(static::$_query); $i++) {

				if (!isset (static::$_query[$i])) {
					$tmpKey = $i;
					break;

				}

			}

		} else {

			if (isset (static::$_query[(int) $key])) {
				unset (static::$_query[(int) $key]);
			
				if (!isset (static::$_query[(int) $key])) {
					$tmpKey = (int) $key;
				} else {
					throw new Exception ('Clearing query id ' . $key . ' failed.');
				}
			}
		}

		return $tmpKey;
	}

	private static function escapeString ($string) {
		return $string;
	}

	private static function newQueryID () {

		$num = (is_array (static::$_query) ? count(static::$_query) : 0 );

		if ($num) {
			for ($i = 0; $i <= $num; $i++) {
				if (!isset (static::$_query[$i]))
					return (int) $i;
			}
		} else {
			return (int) 0;
		}

	}

	private static function clearValue (&$value) {
		$type = gettype($value);

		switch ($type) {
			case 'integer':
				$value = (int) $value;
				break;
			case 'boolean':
				$value = (bool) $value;
				break;
			case 'double':
				$value = (double) $value;
				break;
			case 'string':
				$value = self::escapeString (trim ($value));
				break;
			case 'NULL':
				$value = NULL;
				break;
			case 'array':
			case 'object':
			case 'resource':
			default:
				throw new Exception ('Invalid value type.');
				break;
		}

		return true;
	}

	public static function _queries () {
		var_dump(static::$_query);
	}
}
