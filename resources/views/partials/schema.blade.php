@php
    $baseUrl = rtrim(config('app.url', url('/')), '/');
    $currentUrl = url()->current();

    $productName = config('lifehub.name', 'LifeHub AI Workspace');
    $tagline = config('lifehub.tagline', 'All-in-One AI & Digital Workspace');
    $defaultDescription = config('lifehub.default_description');

    // Page-specific overrides
    $rawPageTitle = trim($__env->yieldContent('title'));
    $pageTitle = !empty($rawPageTitle)
        ? (str_contains($rawPageTitle, $productName) ? $rawPageTitle : $rawPageTitle . ' — ' . $productName)
        : $productName . ' — ' . $tagline;

    $rawPageDescription = trim($__env->yieldContent('description'));
    $pageDescription = !empty($rawPageDescription) ? $rawPageDescription : $defaultDescription;

    // Developer details from centralized config
    $developerConfig = config('lifehub.developer', []);
    $rawDevName = $developerConfig['name'] ?? '[ISI NAMA LENGKAP]';
    $rawDevAlias = $developerConfig['alias'] ?? '[ISI ALIAS]';

    $hasRealName = !empty($rawDevName) && !str_starts_with($rawDevName, '[');
    $hasRealAlias = !empty($rawDevAlias) && !str_starts_with($rawDevAlias, '[');

    if ($hasRealName) {
        $devDisplayName = $rawDevName;
    } elseif ($hasRealAlias) {
        $devDisplayName = $rawDevAlias;
    } else {
        $devDisplayName = 'Developer of ' . $productName;
    }

    // Collect verified social/profile URLs (exclude empty or placeholder values)
    $sameAs = [];
    foreach (['github', 'instagram', 'tiktok'] as $platform) {
        $url = $developerConfig[$platform] ?? null;
        if (!empty($url) && !str_starts_with($url, '[') && filter_var($url, FILTER_VALIDATE_URL)) {
            $sameAs[] = $url;
        }
    }

    // About URL
    $aboutUrl = route('about');

    // 1. WebSite Entity
    $websiteEntity = [
        '@type' => 'WebSite',
        '@id' => $baseUrl . '/#website',
        'url' => $baseUrl . '/',
        'name' => $productName,
        'description' => $defaultDescription,
        'inLanguage' => 'id-ID',
    ];

    // 2. WebPage Entity
    $webpageEntity = [
        '@type' => 'WebPage',
        '@id' => $currentUrl . '#webpage',
        'url' => $currentUrl,
        'name' => $pageTitle,
        'description' => $pageDescription,
        'isPartOf' => [
            '@id' => $baseUrl . '/#website',
        ],
        'inLanguage' => 'id-ID',
    ];

    // 3. Person Entity (Developer)
    $personEntity = [
        '@type' => 'Person',
        '@id' => $baseUrl . '/#developer',
        'name' => $devDisplayName,
        'jobTitle' => $developerConfig['role'] ?? 'Student Developer',
        'description' => 'Student Developer bidang ' . ($developerConfig['field'] ?? 'Software Engineering') . ', pengembang software ' . $productName . '.',
        'knowsAbout' => $developerConfig['skills'] ?? [
            'Software Engineering',
            'Web Development',
            'PHP',
            'Laravel',
            'JavaScript',
            'Artificial Intelligence',
        ],
        'url' => $aboutUrl,
    ];

    if ($hasRealName && $hasRealAlias) {
        $personEntity['alternateName'] = $rawDevAlias;
    }

    if (!empty($sameAs)) {
        $personEntity['sameAs'] = $sameAs;
    }

    // 4. SoftwareApplication Entity
    $softwareEntity = [
        '@type' => 'SoftwareApplication',
        '@id' => $baseUrl . '/#software',
        'name' => $productName,
        'description' => $defaultDescription,
        'url' => $baseUrl . '/',
        'applicationCategory' => 'BusinessApplication',
        'operatingSystem' => 'All (Web-based application)',
        'featureList' => config('lifehub.features', []),
        'author' => [
            '@id' => $baseUrl . '/#developer',
        ],
    ];

    $graph = [
        $websiteEntity,
        $webpageEntity,
        $softwareEntity,
        $personEntity,
    ];

    $schemaJson = json_encode([
        '@context' => 'https://schema.org',
        '@graph' => $graph,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp

<script type="application/ld+json">
{!! $schemaJson !!}
</script>