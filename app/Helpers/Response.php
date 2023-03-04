<?php

namespace App\Helpers;

class Response
{
    public static function success($data, $message = null, $metadata = null, $statusCode = null)
    {
        return response(
            [
                'message' => $message ?: "Success.",
                'metadata' => $metadata ?: [],
                'data' => $data,
            ],
            $statusCode ?: 200
        );
    }

    public static function fail($message = null, $statusCode = null)
    {
        return response(
            [
                'message' => $message ?: "Failed.",
            ],
            $statusCode ?: 500
        );
    }

}
