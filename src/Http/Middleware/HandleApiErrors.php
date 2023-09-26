<?php
namespace Furkanakkulak\Rapidapitest\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class HandleApiErrors
{
    public function handle($request, Closure $next)
    {
        $apiKey = env('RAPIDAPI_KEY');

        if (empty($apiKey)) {
            return new JsonResponse(['error' => 'RAPIDAPI_KEY is missing. Please add it to your .env file.'], 500);
        }

        $response = $next($request);

        if ($response->isClientError() || $response->isServerError()) {
            $errorMessage = $this->extractApiErrorMessage($response);
            return new JsonResponse(['error' => $errorMessage ?: 'Unknown error occurred. Please check your API KEY'], $response->getStatusCode());
        }

        return $response;
    }

    private function extractApiErrorMessage($response)
    {
        try {
            $responseData = json_decode($response->getContent(), true);

            if (isset($responseData['error'])) {
                return $responseData['error'];
            }
        } catch (\Exception $e) {
            // JSON dönüşüm hatası
        }

        return null;
    }
}
