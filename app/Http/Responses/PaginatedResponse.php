<?php

namespace App\Http\Responses;

use App\Contracts\ApiCode;
use App\Http\Responses\BaseResponse;

class PaginatedResponse extends BaseResponse
{
    public function __construct($data, $message = null)
    {
        $this->addCustomAttribute('code', ApiCode::SUCCESS);
        $this->addCustomAttribute('status', true);
        $this->transformResponse($data, $message);
    }

    public function transformResponse(array $data, ?string $message)
    {
        if (is_null($data)) {
            parent::__construct(200, $data, null, $message);
        } else {
            parent::__construct(200, $data['data'], null, $message);
            $this->addCustomAttribute('meta', $data['meta']);
            foreach (array_diff_assoc($data, ['meta', 'data']) as $key => $value) {
                $this->addCustomAttribute($key, $value);
            }
        }
    }
}
