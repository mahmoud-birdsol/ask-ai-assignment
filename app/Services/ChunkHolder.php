<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ChunkHolder
{
    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private mixed $url;

    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private mixed $key;

    /**
     * Create a new instance of the class.
     */
    public function __construct()
    {
        $this->url = config('services.chunk_holder.url');
        $this->key = config('services.chunk_holder.key');

        $this->headers = [
            'Accept', 'application/json',
            'Content-Type', 'application/json',
            'X-API-Key' => $this->key,
        ];
    }

    /**
     * Get the answers chunks.
     *
     * @param  array  $chunks
     * @param  float  $confidence
     * @return array
     */
    public function ask(array $chunks, float $confidence = 0): array
    {
        $this->authenticate();

        if ($confidence) {
            $chunks = array_filter($chunks, fn($chunk) => $chunk['confidence'] > 70);
        }

        $response = [];

        foreach ($chunks as $chunk) {
            $response[] = [
                'content' => Http::withHeaders($this->headers)->get($this->url.'/chunks/'.$chunk['chunkId'])->json(),
                'confidence' => number_format($chunk['confidence'], 2),
            ];
        }

        return $response;
    }

    /**
     * Authenticate with the api.
     *
     * @return void
     */
    private function authenticate(): void
    {
        $response = Http::withHeaders($this->headers)->post($this->url.'/auth/generate-token');

        $this->headers['Authorization'] = $response->json('token');
    }
}
