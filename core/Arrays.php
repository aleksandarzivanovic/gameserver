<?php

class Arrays {

    public static function prepare(&$array) {
        

        if (!is_array($array)) {
            VariableFactory::assignValue($array, new VariableAssignment(VariablesTypes::VARIABLE_TYPE_ARRAY));
        }

        return true;
    }

    public static function getValue($array, &$value, $key = null) {
        if ($key) {
            if (array_key_exists($key, $array)) {
                $value = $array[$key];
            } else {
                throw new Exception('Undefined key "' . $key . '".');
            }
        } else {
            $value = end($array);
        }
    }

    public static function setValue(&$array, $value, $key = null) {

        if (is_null($key)) {
            $array[] = $value;
        } else {
            $array[$key] = $value;
        }

        return true;
    }

    public static function removeKey(&$array, $key) {
        if (!is_array($key)) {

            if (array_key_exists($key, $array)) {
                
                $return = [
                    'status' => 200,
                    'return' => $array[$key]
                ];
                
                unset($array[$key]);
            } else {
                
                $return = [
                    'status' => 101,
                    'error' => 'Key' . $key . ' does not exists in array.'
                ];
                
            }
        } else {
            
            foreach ($key as $keyID) {
                if (!array_key_exists($keyID, $array)) {
                    $return[] = ['status' => 101, 'error' => 'Key ' . $keyID . ' does not exists.'];
                } else {
                    $return[] = $array[$key];
                    unset($array[$keyID]);
                }
            }
        }

        return $return;
    }

}
