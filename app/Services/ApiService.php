<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiService {
    
    public function get(string $endpoint): mixed {
        try {
            $response = Http::timeout(5)
                ->withToken(config('app.api_token'))
                ->get(config('app.api_token') . $endpoint);
            if ($response->successful()) return $response->json();
            return $response;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [];
        }
    }

}