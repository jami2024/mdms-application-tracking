<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('document_url')) {

    function document_url($path)
    {
        if (empty($path)) {
            return null;
        }

        $file = ltrim($path, '/');

        $citizenUrl = rtrim(config('services.document_app.url'), '/') . '/storage/' . $file;
        $headers = @get_headers($citizenUrl);

        if ($headers && str_contains($headers[0], '200')) {
            return $citizenUrl;
        }

        if (Storage::disk('public')->exists($file)) {
            return Storage::disk('public')->url($file);
        }

        return null;
    }
}
