<?php

function smarty_modifier_json_encode($params, $result = null){
        switch(gettype($params)){
            case    'array':
                    $tmp = array();
                    foreach($params as $key => $value) {
                        if(($value = JSON::encode($value)) !== '')
                            array_push($tmp, JSON::encode(strval($key)).':'.$value);
                    };
                    $result = '{'.implode(',', $tmp).'}';
                    break;
            case    'boolean':
                    $result = $params ? 'true' : 'false';
                    break;
            case    'double':
            case    'float':
            case    'integer':
                    $result = $result !== null ? strftime('%Y-%m-%dT%H:%M:%S', $params) : strval($params);
                    break;
            case    'NULL':
                    $result = 'null';
                    break;
            case    'string':
                    $i = create_function('&$e, $p, $l', 'return intval(substr($e, $p, $l));');
                    if(preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}$/', $params))
                        $result = mktime($i($params, 11, 2), $i($params, 14, 2), $i($params, 17, 2), $i($params, 5, 2), $i($params, 9, 2), $i($params, 0, 4));
                    break;
            case    'object':
                    $tmp = array();
                    if(is_object($result)) {
                        foreach($params as $key => $value)
                            $result->$key = $value;
                    } else {
                        $result = get_object_vars($params);
                        foreach($result as $key => $value) {
                            if(($value = JSON::encode($value)) !== '')
                                array_push($tmp, JSON::encode($key).':'.$value);
                        };
                        $result = '{'.implode(',', $tmp).'}';
                    }
                    break;
        }
        return $result;
    }



?>
