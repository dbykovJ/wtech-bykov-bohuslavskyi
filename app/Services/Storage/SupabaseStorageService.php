<?php

namespace App\Services\Storage;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SupabaseStorageService
{
    private string $baseUrl;
    private string $bucket;
    private string $serviceKey;

    public function __construct()
    {
        $ref              = config('services.supabase.project_ref');
        $this->bucket     = config('services.supabase.bucket');
        $this->serviceKey = config('services.supabase.service_key');
        $this->baseUrl    = "https://{$ref}.supabase.co/storage/v1";
    }

    public function upload(UploadedFile $file): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->serviceKey,
            'Content-Type'  => $file->getMimeType(),
        ])->withBody(file_get_contents($file->getRealPath()), $file->getMimeType())
          ->post("{$this->baseUrl}/object/{$this->bucket}/{$filename}");

        if (! $response->successful()) {
            throw new \RuntimeException('Supabase Storage upload failed: ' . $response->body());
        }

        return $filename;
    }

    public function delete(string $filename): void
    {
        Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->serviceKey,
        ])->delete("{$this->baseUrl}/object/{$this->bucket}/{$filename}");
    }

    public function url(string $filename): string
    {
        return "{$this->baseUrl}/object/public/{$this->bucket}/{$filename}";
    }
}
