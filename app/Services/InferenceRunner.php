<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class InferenceRunner
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
        $this->url = config('services.inference_runner.url');
        $this->key = config('services.inference_runner.key');

        $this->headers = [
            'Accept', 'application/json',
            'Content-Type', 'application/json',
            'X-API-Key' => $this->key,
        ];
    }

    /**
     * Run the inference check.
     *
     * @param  string  $question
     * @return array
     */
    public function run(string $question): array
    {
        $response = Http::withHeaders($this->headers)->post($this->url . '/ask', [
            'question' => $question,
        ]);

        return $response->json('chunks');
    }
}
