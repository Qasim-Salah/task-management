<?php


namespace App\Http\Controllers\API\V1;


use Illuminate\Support\Arr;

class ResponseBuilder
{
    public static function success($data, $message = 'OK', $status = 200, $code = 0)
    {
        return response()->json([
            'success' => true,
            'status' => $status,
            'code' => $code,
            'locale' => app()->getLocale(),
            'message' => $message,
            'data' => $data
        ], $status, []);
    }

    public static function successWithPagination($data, $links, $meta, $message = 'OK', $status = 200, $code = 0)
    {
        return response()->json([
            'success' => true,
            'status' => $status,
            'code' => $code,
            'locale' => app()->getLocale(),
            'message' => $message,
            'data' => $data,
            'links' => $links,
            'meta' => $meta,
        ], $status, []);
    }

    public static function successWithAdditional($data, $additionalKey = '', $additionalValue = '', $message = 'OK', $status = 200, $code = 0)
    {
        $collection = collect(['items' => $data]);
        $merged = $collection->merge([$additionalKey => $additionalValue]);

        return response()->json([
            'success' => true,
            'status' => $status,
            'code' => $code,
            'locale' => app()->getLocale(),
            'message' => $message,
            'data' => $merged,
        ], $status, []);
    }

    public static function error($message, $status = 422, $code = null)
    {
        return response()->json([
            'success' => false,
            'status' => $status,
            'code' => $code,
            'locale' => app()->getLocale(),
            'message' => $message,
            'data' => null
        ], $status, []);
    }


}
