<?php

namespace App\Helpers;

class JsonResponseHelper
{
    CONST IDX_JSON_CODE = 'code';
    CONST IDX_JSON_MESSAGE = 'message';
    CONST IDX_JSON_DATA = 'data';
    CONST IDX_JSON_ERRORS = 'errors';

    public static function getResponse($data, $message, $code, $extras = []){
        $response = [
            self::IDX_JSON_CODE => $code,
            self::IDX_JSON_MESSAGE => $message,
        ];

        if(!is_null($data)){
            $response[self::IDX_JSON_DATA] = $data;
        }

        $response = array_merge($response, $extras);

        return $response;
    }
}