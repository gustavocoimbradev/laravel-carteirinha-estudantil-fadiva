<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiService {
    
    public function get(string $endpoint): mixed {
        try {
            $response = Http::timeout(5)
                ->withoutVerifying()
                ->withToken(config('app.api_token'))
                ->get(config('app.api_url') . $endpoint);
            if ($response->successful()) return $response->json();
            return $response;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [];
        }
    }

    public function post(string $endpoint, $payload, $type = 'body'): mixed 
    {
        try {
            $request = Http::timeout(5)
                ->withoutVerifying()
                ->withToken(config('app.api_token'));
            if ($type === 'form') {
                $request->asForm();
            }
            $response = $request->post(config('app.api_url') . $endpoint, $payload);
            if ($response->successful()) {
                return $response->json();
            }
            return $response;
        } catch (\Exception $e) {
            Log::error("Erro na requisição POST: " . $e->getMessage());
            return [];
        }
    }

}