<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function success(string $message = ''): array
    {
        return [
            'status' => 'success',
            'message' => $message ?? "It was done right"
        ];
    }

    protected function test_result(array $output = []): array
    {
        return [
            'status' => 'success',
            'message' => $message ?? "It was done right"
        ];
    }


    protected function error(string $message = ''): array
    {
        return [
            'status' => 'danger',
            'message' => $message ?? "It didn't happen"
        ];
    }
}
