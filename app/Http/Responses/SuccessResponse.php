<?php

namespace App\Http\Responses;

use App\Contracts\ApiCode;
use App\Http\Responses\BaseResponse;

class SuccessResponse extends BaseResponse
{
    public function __construct($data, $message = null)
    {
        $this->addCustomAttribute('code', ApiCode::SUCCESS);
        $this->addCustomAttribute('status', true);
        parent::__construct(200, $data, null, $message);
    }
}
