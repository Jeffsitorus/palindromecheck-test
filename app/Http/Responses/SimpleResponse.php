<?php

namespace App\Http\Responses;

class SimpleResponse extends BaseResponse
{
    public function __construct($data, $message = null)
    {
        if (is_null($message)) {
            parent::__construct(200, $data);
        } else {
            parent::__construct(200, $data, null, $message);
        }
    }
}