<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiService {
    
    public function get(string $endpoint): mixed {
        try {
            $response = Http::timeout(5)
                ->withToken(env('API_TOKEN'))
                ->get(env('API_URL') . $endpoint);
            if ($response->successful()) return $response->json();
            return $response;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [];
        }
    }

}