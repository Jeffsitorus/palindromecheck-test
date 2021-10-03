<?php

namespace App\Http\Responses;

class WholePaginationResponse extends PaginationResponse
{
    public function __construct($data, $errors = null, $message = 'Success')
    {
        $total = count($data);
        $limit = $total;
        $offset = 0;

        parent::__construct($data, $total, $total, $offset, $limit, $errors, $message);
    }
}
