<?php

class VariableFactory {
	// TypeFactory::assignType ($tmpValue, new ParameterType (ParameterType::BAD_QYERY_ID));
	public static function assignValue (&$parameterType, VariableAssignment $type) {
		if ($type instanceof VariableAssignment) {
			$parameterType = $type->getValue();
		} else {
			throw new Exception('Variable type must be instance of VariableAssignment');
		}
	}

	public static function assingMessage (&$parameterType, VariableAssignment $type) {
		if ($type instanceof VariableAssignment) {
			$parameterType = $type->getMessage();
		} else {
			throw new Exception('Variable type must be callback or instance of VariableAssignment');
		}
	}

	public static function assignExtras(&$parameterType, VariableAssignment $type) {
		if ($type instanceof VariableAssignment) {
			$parameterType = $type->getExtra();
		}
	}
}