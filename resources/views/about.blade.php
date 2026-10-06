@extends('layouts.app')

@section('title', 'Tentang Developer')
@section('description', 'Profil dan informasi developer pembuat LifeHub AI Workspace — Student Developer bidang Software Engineering.')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-8 py-10 sm:py-14 space-y-10">

    {{-- Breadcrumb & Title --}}
    <div class="space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#5300B7]/15 border border-[#5300B7]/30 text-[#C4B5FD] text-xs font-semibold">
            <span class="w-1.5 h-1.5 rounded-full bg-[#5300B7]"></span>
            Entity Identity & Developer Profile
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
            Tentang Developer
        </h1>
        <p class="text-sm text-[#8E8E93] max-w-2xl leading-relaxed">
            Mengenal profil pengembang di balik pembuatan dan arsitektur produk software <strong class="text-white">LifeHub AI Workspace</strong>.
        </p>
    </div>

    {{-- Entity Relationship Overview Card --}}
    <div class="p-6 rounded-2xl bg-[#15151A] border border-[#26262F] relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 rounded-full bg-[#5300B7]/10 blur-3xl pointer-events-none"></div>

        <span class="text-[11px] font-bold text-[#A78BFA] uppercase tracking-wider block mb-3">Hubungan Entitas Software & Developer</span>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 p-4 rounded-xl bg-[#1D1D24] border border-[#2B2B36]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#5300B7] flex items-center justify-center text-white shrink-0 shadow-sm">
                    <svg class="w-5 h-5" viewBox="0 0 48 48" fill="none" stroke="currentColor">
                        <path d="M24 6L38 14V34L24 42L10 34V14L24 6Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                        <path d="M10 14L24 22L38 14" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                        <path d="M24 22V42" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                        <circle cx="24" cy="22" r="3" fill="#FFFFFF" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-white">LifeHub AI Workspace</h2>
                    <span class="text-[11px] text-[#8E8E93]">SoftwareApplication (Web-based)</span>
                </div>
            </div>

            <div class="flex items-center gap-2 self-center text-xs font-semibold text-[#8E8E93]">
                <span>dikembangkan oleh</span>
                <svg class="w-4 h-4 text-[#A78BFA]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#262630] border border-[#373746] flex items-center justify-center text-[#C4B5FD] shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    @php
                        $devName = config('lifehub.developer.name');
                        $isNamePlaceholder = str_starts_with($devName, '[');
                    @endphp
                    <h3 class="text-sm font-bold text-white">{{ $isNamePlaceholder ? 'Developer LifeHub' : $devName }}</h3>
                    <span class="text-[11px] text-[#8E8E93]">Student Developer</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid: Developer Identity & Other Projects --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left 2 Columns: Detailed Identity --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="p-6 sm:p-8 rounded-2xl bg-[#15151A] border border-[#26262F] space-y-6">
                <div class="flex items-start justify-between gap-4 pb-6 border-b border-[#26262F]">
                    <div>
                        <span class="text-xs font-bold text-[#A78BFA] uppercase tracking-wider block mb-1">Biodata Pengembang</span>
                        <h2 class="text-xl sm:text-2xl font-bold text-white">
                            {{ config('lifehub.developer.name') }}
                        </h2>
                        <p class="text-xs text-[#8E8E93] mt-1">
                            Alias / Username: <span class="font-mono text-white">{{ config('lifehub.developer.alias') }}</span>
                        </p>
                    </div>

                    <div class="px-3 py-1 rounded-lg bg-[#5300B7]/20 border border-[#5300B7]/40 text-[#C4B5FD] text-xs font-semibold shrink-0">
                        {{ config('lifehub.developer.role') }}
                    </div>
                </div>

                {{-- Key Facts --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-4 rounded-xl bg-[#1B1B22] border border-[#2B2B36] space-y-1">
                        <span class="text-[#8E8E93] block">Bidang Keahlian</span>
                        <span class="font-semibold text-white text-sm block">{{ config('lifehub.developer.field') }}</span>
                    </div>

                    <div class="p-4 rounded-xl bg-[#1B1B22] border border-[#2B2B36] space-y-1">
                        <span class="text-[#8E8E93] block">Asal / Daerah</span>
                        <span class="font-semibold text-white text-sm block">{{ config('lifehub.developer.origin') }}</span>
                    </div>

                    <div class="p-4 rounded-xl bg-[#1B1B22] border border-[#2B2B36] space-y-1">
                        <span class="text-[#8E8E93] block">Spesialisasi Teknologi</span>
                        <span class="font-semibold text-white text-sm block">Full-Stack PHP, Laravel & Web AI</span>
                    </div>
                </div>

                {{-- Competencies / Skills --}}
                <div class="space-y-2 pt-2">
                    <span class="text-xs font-bold text-[#8E8E93] uppercase tracking-wider block">Keahlian & Fokus Teknis (knowsAbout)</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach(config('lifehub.developer.skills', []) as $skill)
                            <span class="px-3 py-1.5 rounded-lg bg-[#22222B] border border-[#2F2F3D] text-xs font-medium text-[#E4E4E7]">
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Public Profiles / Social Media --}}
                <div class="space-y-3 pt-4 border-t border-[#26262F]">
                    <span class="text-xs font-bold text-[#8E8E93] uppercase tracking-wider block">Profil Publik & Sosial Media Resmi</span>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        {{-- GitHub --}}
                        @php
                            $githubUrl = config('lifehub.developer.github');
                            $hasGithub = !empty($githubUrl) && !str_starts_with($githubUrl, '[') && filter_var($githubUrl, FILTER_VALIDATE_URL);
                        @endphp
                        @if($hasGithub)
                            <a href="{{ $githubUrl }}" target="_blank" rel="noopener noreferrer"
                               class="flex items-center gap-2.5 p-3 rounded-xl bg-[#1E1E26] hover:bg-[#272733] border border-[#2E2E3C] transition group">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                                </svg>
                                <div>
                                    <span class="text-xs font-bold text-white block">GitHub</span>
                                    <span class="text-[10px] text-[#A78BFA] group-hover:underline">Buka Profil &rarr;</span>
                                </div>
                            </a>
                        @else
                            <div class="flex items-center gap-2.5 p-3 rounded-xl bg-[#191920] border border-[#252530] text-[#6B7280]">
                                <svg class="w-4 h-4 text-[#6B7280]" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                                </svg>
                                <div>
                                    <span class="text-xs font-semibold text-[#8E8E93] block">GitHub</span>
                                    <span class="text-[10px] text-[#6B7280]">Menunggu verifikasi</span>
                                </div>
                            </div>
                        @endif

                        {{-- Instagram --}}
                        @php
                            $igUrl = config('lifehub.developer.instagram');
                            $hasIg = !empty($igUrl) && !str_starts_with($igUrl, '[') && filter_var($igUrl, FILTER_VALIDATE_URL);
                        @endphp
                        @if($hasIg)
                            <a href="{{ $igUrl }}" target="_blank" rel="noopener noreferrer"
                               class="flex items-center gap-2.5 p-3 rounded-xl bg-[#1E1E26] hover:bg-[#272733] border border-[#2E2E3C] transition group">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <span class="text-xs font-bold text-white block">Instagram</span>
                                    <span class="text-[10px] text-[#A78BFA] group-hover:underline">Buka Profil &rarr;</span>
                                </div>
                            </a>
                        @else
                            <div class="flex items-center gap-2.5 p-3 rounded-xl bg-[#191920] border border-[#252530] text-[#6B7280]">
                                <svg class="w-4 h-4 text-[#6B7280]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <span class="text-xs font-semibold text-[#8E8E93] block">Instagram</span>
                                    <span class="text-[10px] text-[#6B7280]">Menunggu verifikasi</span>
                                </div>
                            </div>
                        @endif

                        {{-- TikTok --}}
                        @php
                            $tiktokUrl = config('lifehub.developer.tiktok');
                            $hasTiktok = !empty($tiktokUrl) && !str_starts_with($tiktokUrl, '[') && filter_var($tiktokUrl, FILTER_VALIDATE_URL);
                        @endphp
                        @if($hasTiktok)
                            <a href="{{ $tiktokUrl }}" target="_blank" rel="noopener noreferrer"
                               class="flex items-center gap-2.5 p-3 rounded-xl bg-[#1E1E26] hover:bg-[#272733] border border-[#2E2E3C] transition group">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <span class="text-xs font-bold text-white block">TikTok</span>
                                    <span class="text-[10px] text-[#A78BFA] group-hover:underline">Buka Profil &rarr;</span>
                                </div>
                            </a>
                        @else
                            <div class="flex items-center gap-2.5 p-3 rounded-xl bg-[#191920] border border-[#252530] text-[#6B7280]">
                                <svg class="w-4 h-4 text-[#6B7280]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <span class="text-xs font-semibold text-[#8E8E93] block">TikTok</span>
                                    <span class="text-[10px] text-[#6B7280]">Menunggu verifikasi</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Other Projects & Software Stack --}}
        <div class="space-y-6">

            {{-- Other Projects Card --}}
            <div class="p-6 rounded-2xl bg-[#15151A] border border-[#26262F] space-y-4">
                <div>
                    <span class="text-xs font-bold text-[#A78BFA] uppercase tracking-wider block mb-1">Portfolio</span>
                    <h3 class="text-base font-bold text-white">Project Lain</h3>
                    <p class="text-xs text-[#8E8E93] mt-0.5">Karya software lain yang terafiliasi dengan pengembang.</p>
                </div>

                <div class="space-y-3">
                    @foreach(config('lifehub.developer.other_projects', []) as $proj)
                        <div class="p-3.5 rounded-xl bg-[#1C1C23] border border-[#2A2A35] space-y-1">
                            <div class="flex items-center justify-between gap-2">
                                <h4 class="text-xs font-bold text-white">{{ $proj['name'] }}</h4>
                                @if(!empty($proj['url']))
                                    <a href="{{ $proj['url'] }}" target="_blank" rel="noopener noreferrer"
                                       class="text-[10px] font-semibold text-[#A78BFA] hover:underline flex items-center gap-0.5">
                                        <span>Buka</span>
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                            <p class="text-[11px] text-[#8E8E93] leading-relaxed">{{ $proj['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Product Summary Card --}}
            <div class="p-6 rounded-2xl bg-[#15151A] border border-[#26262F] space-y-3">
                <span class="text-xs font-bold text-[#A78BFA] uppercase tracking-wider block">Spesifikasi Software</span>
                <h3 class="text-base font-bold text-white">LifeHub AI Workspace</h3>

                <ul class="space-y-2 text-xs text-[#8E8E93]">
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#5300B7]"></span>
                        <span>Framework: <strong class="text-white">Laravel 13 (PHP 8.4)</strong></span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#5300B7]"></span>
                        <span>Frontend: <strong class="text-white">Tailwind CSS & Blade</strong></span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#5300B7]"></span>
                        <span>Database: <strong class="text-white">SQLite / Multi-tenant Business</strong></span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#5300B7]"></span>
                        <span>Lisensi: <strong class="text-white">Proprietary Software Product</strong></span>
                    </li>
                </ul>

                <div class="pt-3 border-t border-[#26262F]">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#C4B5FD] hover:underline">
                        <span>Kembali ke Beranda LifeHub</span>
                        &rarr;
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection