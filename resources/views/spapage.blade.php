<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta property="og:title" content="{{ $title }}" />
    <meta property="og:description" content="{{ $description }}" />
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:title" content="{{ $title }}" />
    <meta name="description" content="{{ $description }}" />
    <title>{{ $title }}</title>
</head>
<body>
<noscript>You need to enable JavaScript to run this app.</noscript>
<div id="root"></div>
<script src="{{ $app_js_path }}"></script>
<script src="{{ $vendor_js_path }}"></script>
</body>
</html>
