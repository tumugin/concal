<?php

namespace App\Http\Controllers\Page;

use Illuminate\Support\Facades\File;

class SPAPageUtils
{
    public static function renderSPAPage(array $options = [])
    {
        $raw_manifest = File::get('storage/app/public/manifest.json');
        $manifest = json_decode($raw_manifest, true);
        return view('spapage', [
            'app_js_path' => $manifest['app.js'],
            'vendor_js_path' => $manifest['vendor.js'],
            'title' => $options['title'] ?? 'コンカフェカレンダー',
            'description' => $options['description'] ?? 'コンカフェカレンダーはキャストの出勤情報などが調べられるウェブサイトです',
        ]);
    }
}
