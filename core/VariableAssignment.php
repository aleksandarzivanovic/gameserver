<?php

class VariableAssignment {

	public $value, $message, $extra;

	public function __construct ($typeID = null){

		if ($typeID === null) {
			return true;
		}

		$callbackReturn = NULL;

		if (is_array($typeID) && array_key_exists('callback', $typeID)) {
			if (is_callable($typeID['callback'])) {
				$callback = $typeID['callback'];

				if (isset ($typeID['args']) && is_array ($typeID['args'])) {
					$args = $typeID['args'];
				} else if (!is_array ($typeID['args'])) {
					$args[] = $typeID['args'];
				} else if (!isset ($type['args'])) {
					$callbackReturn = (object) call_user_func($callback);
				}

				if (strtolower (gettype($callbackReturn)) !== 'object') {
					$return = (object) call_user_func_array($callback, $args);
				} else {
					(object) $return = $callbackReturn;
				}

				unset ($this->value);
				if (isset ($return->value)) {
					$this->value = $return->value;
				}

				unset ($this->message);
				if (isset ($return->message)) {
					$this->message = $return->message;
				}

				unset ($this->extra);
				if (isset ($return->extra)) {
					$this->extra = $return->extra;
				}

				return $this;
			} else {
				throw new Exception('Callback is not callable.');
			}
		} else {

			if (array_key_exists($typeID, VariablesTypes::$_types)) {

				$callbackReturn = (object) VariablesTypes::$_types[$typeID];

				unset ($this->value);
				if (isset ($callbackReturn->value)) {
					$this->value = $callbackReturn->value;
				}

				unset ($this->message);
				if ( isset ($callbackReturn->message)) {
					$this->message = $callbackReturn->message;
				}

				unset ($this->extra);
				if (isset ($callbackReturn->extra) && is_array ($callbackReturn->extra)) {
					$this->extra = $callbackReturn->extra;
				}

				if (gettype ($callbackReturn) == 'object')
					unset ($callbackReturn);

				return $this;

			} else {
				throw new Exception ('Unknown type.');
			}
		}
	}

	public function getValue () {
		return $this->value;
	}

	public function getMessage () {
		return $this->message;
	}

	public function getExtra () {
		return $this->extra;
	}
	public function printMessage () {
		var_dump ($this->message);
		return true;
	}
}