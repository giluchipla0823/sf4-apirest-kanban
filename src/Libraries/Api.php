<?php

namespace App\Libraries;

class Api
{
    CONST IDX_JSON_CODE = 'code';
    CONST IDX_JSON_MESSAGE = 'message';
    CONST IDX_JSON_DATA = 'data';
    CONST IDX_JSON_ERRORS = 'errors';

    public function makeResponse($data, string $message, int $code, array $extras = []): array {
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