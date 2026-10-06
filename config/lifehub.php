<?php

return [
    /*
    |--------------------------------------------------------------------------
    | LifeHub AI Workspace - Product Identity
    |--------------------------------------------------------------------------
    */
    'name' => env('LIFEHUB_APP_NAME', 'LifeHub AI Workspace'),
    'tagline' => 'All-in-One AI & Digital Workspace',
    'default_description' => 'LifeHub AI Workspace adalah workspace digital all-in-one dengan asisten AI, smart docs, sistem kasir POS, dan otomatisasi produktivitas bisnis.',

    /*
    |--------------------------------------------------------------------------
    | Verified Software Application Features (Directly from Source Code)
    |--------------------------------------------------------------------------
    | Only features verified to exist and function within the codebase:
    | - LifeHub AI: AI Assistant & Chat, Smart Docs & Notes, Workflows
    | - Kasira: Point of Sale (POS), Barcode Scanner, Stok & Produk,
    |   Shift Kasir, Pelanggan, Supplier, Piutang, Pembayaran QRIS, Laporan
    */
    'features' => [
        'AI Assistant & Interactive Chat',
        'Smart Docs & Notulen Kerja Digital',
        'Productivity Workflows & Integrasi Tools',
        'Sistem Kasir & Point of Sale (POS) Kasira',
        'Barcode Scanner Kamera untuk Transaksi Cepat',
        'Manajemen Produk & Inventaris Stok Real-Time',
        'Manajemen Shift Kasir & Rekonsiliasi Kas',
        'Manajemen Data Pelanggan & Riwayat Belanja',
        'Manajemen Supplier & Catatan Piutang Usaha',
        'Integrasi Pembayaran QRIS Dinamis Otomatis',
        'Laporan Penjualan & Ekspor Laporan CSV',
        'Multi-platform Workspace Selector',
    ],

    /*
    |--------------------------------------------------------------------------
    | Developer Profile Data
    |--------------------------------------------------------------------------
    | Note: Instagram/TikTok default to null until filled manually by the
    | developer. Do not fabricate URLs for them.
    */
    'developer' => [
        'name' => env('DEVELOPER_NAME', 'Reski Setiawan'),
        'alias' => env('DEVELOPER_ALIAS', 'reskisetiawan301210-ai'),
        'role' => 'Student Developer',
        'field' => 'Software Engineering',
        'origin' => env('DEVELOPER_ORIGIN', 'Majalengka, Jawa Barat'),

        // Public profiles (only active if valid URL is provided)
        'github' => env('DEVELOPER_GITHUB_URL', 'https://github.com/reskisetiawan301210-ai'),
        'instagram' => env('DEVELOPER_INSTAGRAM_URL', null),
        'tiktok' => env('DEVELOPER_TIKTOK_URL', null),

        // Verified other projects
        'other_projects' => [
            [
                'name' => 'Aura Global',
                'url' => 'https://auraglobal.my.id',
                'description' => 'Platform layanan digital dan solusi teknologi modern.',
            ],
            [
                'name' => 'ElrsBot',
                'url' => null,
                'description' => 'Automated bot utility untuk automasi alur kerja dan produktivitas.',
            ],
            [
                'name' => 'Code Mastery Academy',
                'url' => null,
                'description' => 'Platform edukasi dan penguasaan coding untuk software engineering.',
            ],
        ],

        // Core competencies based on project tech stack
        'skills' => [
            'Software Engineering',
            'Web Development',
            'PHP',
            'Laravel',
            'JavaScript',
            'Tailwind CSS',
            'Artificial Intelligence',
        ],
    ],
];
