@php
    $productName = $productName ?? config('lifehub.name', 'LifeHub AI Workspace');
    $tagline = $tagline ?? config('lifehub.tagline', 'All-in-One AI & Digital Workspace');
    $defaultTitle = $productName . ' — ' . $tagline;

    // Check if view supplied custom title
    $rawTitle = trim($__env->yieldContent('title'));
    if (!empty($rawTitle)) {
        // If view already formatted with product name, avoid duplicating
        if (str_contains($rawTitle, $productName)) {
            $finalTitle = $rawTitle;
        } else {
            $finalTitle = $rawTitle . ' — ' . $productName;
        }
    } else {
        $finalTitle = $defaultTitle;
    }

    $rawDescription = trim($__env->yieldContent('description'));
    $finalDescription = !empty($rawDescription) ? $rawDescription : ($defaultDescription ?? config('lifehub.default_description'));

    $canonicalUrl = url()->current();

    // Author: use real developer name if configured, otherwise descriptive fallback
    $developerName = config('lifehub.developer.name');
    $authorName = (!empty($developerName) && !str_starts_with($developerName, '['))
        ? $developerName
        : 'Developer of ' . $productName;
@endphp

<title>{{ $finalTitle }}</title>
<meta name="description" content="{{ $finalDescription }}">
<meta name="author" content="{{ $authorName }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ $canonicalUrl }}">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="website">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:title" content="{{ $finalTitle }}">
<meta property="og:description" content="{{ $finalDescription }}">
<meta property="og:site_name" content="{{ $productName }}">
<meta property="og:locale" content="id_ID">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $canonicalUrl }}">
<meta name="twitter:title" content="{{ $finalTitle }}">
<meta name="twitter:description" content="{{ $finalDescription }}">

