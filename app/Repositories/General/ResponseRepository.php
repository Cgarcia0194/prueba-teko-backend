<?php

namespace App\Repositories\General;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ResponseRepository
{
    /**
     * ResponseSuccess
     *
     * Returns the success data and message if there is any error
     *
     * @param object $data
     * @param string $message
     * @param integer $status_code
     * @return Response
     */
    public static function Response($data, $status_code = JsonResponse::HTTP_OK)
    {
        return response()->json($data, $status_code);
    }
}
