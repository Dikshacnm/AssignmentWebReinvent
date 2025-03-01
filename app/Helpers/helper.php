<?php

if (!function_exists('sendSuccessResponse')) {
    /**
     * Send a success response.
     *
     * @param string $message
     * @param mixed $data
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    function sendSuccessResponse(string $message, $data = null, int $status = 200) 
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data ?? '',
        ], $status);
    }
}

if(!function_exists('sendErrorResponse')){

    function sendErrorResponse(string $message,$data=null,int $status = 400 )
    {
         return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data ?? '',
        ], $status);
    }
}