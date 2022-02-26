<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function response($data = [], $status = 200, array $header = [], $option = 0)
    {
        return response()->json($data, $status, $header, $option);
    }

    public function responseMessage($message, $status = 200, $header = [], $option = 0)
    {
        return $this->response([
            'message' => $message,
        ], $status, $header, $option);
    }

    public function responseUnauthenticated($message = "Unauthenticated")
    {
        return $this->responseMessage($message, 401);
    }

    public function responseNotFound($message = "Not Found")
    {
        return $this->responseMessage($message, 404);
    }
}
