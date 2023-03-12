<?php

namespace App\Http\Services;

use Illuminate\Http\JsonResponse;

class AppServices
{
    public function responseApi(bool $status, string $type, string $content, mixed $data): JsonResponse
    {
        $codes = [
            'success' => 200,
            'error' => 500,
        ];

        return response()->json(
            [
                "transaction" => ["status" => $status],
                "message" => [
                    "type" => $type,
                    "content" => $content,
                    "code" => $codes[$type]
                ],
                "data" => $data
            ],
            $codes[$type]
        );
    }
}
