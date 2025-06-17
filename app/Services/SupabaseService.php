<?php

namespace App\Services;

use GuzzleHttp\Client;

class SupabaseService
{
    protected Client $client;
    protected string $url;

    public function __construct()
    {
        $this->url = rtrim(env('SUPABASE_URL'), '/') . '/rest/v1/';

        $this->client = new Client([
            'base_uri' => $this->url,
            'headers' => [
                'apikey' => env('SUPABASE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_KEY'),
                'Accept' => 'application/json',
                'Prefer' => 'count=exact' // ini penting untuk enable count
            ]
        ]);
    }

    // Ambil semua data dari satu tabel
    public function getAll(string $table): array
    {
        try {
            $response = $this->client->get($table);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Throwable $e) {
            logger()->error('Supabase Guzzle Error: ' . $e->getMessage(), [
                'table' => $table
            ]);
            return [];
        }
    }

    // Ambil data dengan kolom tertentu
    public function get(string $table, array $columns = ['*'])
    {
        $query = http_build_query([
            'select' => implode(',', $columns),
        ]);

        return $this->client->get("$table?$query");
    }

    // Ambil data dengan filter WHERE
    public function getWithFilter(string $table, array $columns = ['*'], array $filters = []): array
    {
        $query = [
            'select' => implode(',', $columns)
        ];

        foreach ($filters as $key => $value) {
            $query[$key] = 'eq.' . $value;
        }

        try {
            $response = $this->client->get($table . '?' . http_build_query($query));
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Throwable $e) {
            logger()->error('Supabase Guzzle Error: ' . $e->getMessage(), [
                'table' => $table,
                'filters' => $filters
            ]);
            return [];
        }
    }

    // Method baru: hitung jumlah baris dengan kondisi
    public function countRows(string $table, array $filters = []): int
    {
        $query = [];

        foreach ($filters as $key => $value) {
            $query[$key] = 'eq.' . $value;
        }

        try {
            $response = $this->client->request('GET', $table . '?' . http_build_query($query), [
                'headers' => ['Prefer' => 'count=exact']
            ]);

            $count = $response->getHeaderLine('Content-Range');
            // Format: items 0-9/25 → kita ambil yang setelah '/'
            return (int) explode('/', $count)[1];
        } catch (\Throwable $e) {
            logger()->error('Supabase Count Error: ' . $e->getMessage(), [
                'table' => $table,
                'filters' => $filters
            ]);
            return 0;
        }
    }

    public function getClient()
    {
        return $this->client;
    }
}
