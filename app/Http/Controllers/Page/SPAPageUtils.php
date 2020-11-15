<?php

namespace App\Http\Controllers\Page;

use Illuminate\Support\Facades\File;

class SPAPageUtils
{
    public static function renderSPAPage(array $options = [])
    {
        $raw_manifest = File::get(storage_path('app/manifest.json'));
        $manifest = json_decode($raw_manifest, true);
        return view('spapage', [
            'app_js_path' => $manifest['app.js'] ?? null,
            'app_css_path' => $manifest['app.css'] ?? null,
            'vendor_js_path' => $manifest['vendor.js'] ?? null,
            'vendor_css_path' => $manifest['vendor.css'] ?? null,
            'title' => $options['title'] ?? 'コンカフェカレンダー',
            'og_title' => $options['og_title'] ?? 'コンカフェカレンダー',
            'description' => $options['description'] ?? 'コンカフェカレンダーはキャストの出勤情報などが調べられるウェブサイトです',
        ]);
    }
}
