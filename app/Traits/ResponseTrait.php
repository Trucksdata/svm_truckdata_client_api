<?php

namespace App\Traits;

trait ResponseTrait
{
    public function successResponse($message, $data = null, $status = 200)
    {
        $response = [
            'status' => 'success',
            'message' => $message,
        ];

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $response[$key] = $value;
            }
        } else if ($data) {
            $response['data'] = $data;
        }

        return response($response, $status);
    }

    public function errorResponse($message, $status = 400)
    {
        $response = [
            'status' => 'error',
            'code' => $status,
            'message' => $message,
        ];

        return response($response, $status);
    }
}
