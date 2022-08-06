<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected function ok(
        $data = \null,
        int $status = JsonResponse::HTTP_OK,
        ?string $message = \null,
        array $headers = []
    ): JsonResponse {
        $response = [
            'ok' => \true,
        ];

        if ($data !== \null) {
            $response['data'] = $data;
        }

        if ($message !== \null) {
            $response['message'] = $message;
        }

        return new JsonResponse($response, $status, $headers);
    }

    protected function error(
        ?string $message = \null,
        int $status = JsonResponse::HTTP_BAD_REQUEST,
        ?array $errors = \null,
        array $headers = []
    ): JsonResponse {
        $response = [
            'ok' => \false,
        ];

        if ($message !== \null) {
            $response['message'] = $message;
        }

        if ($errors !== \null) {
            $response['errors'] = $errors;
        }

        return new JsonResponse($response, $status, $headers);
    }
}
