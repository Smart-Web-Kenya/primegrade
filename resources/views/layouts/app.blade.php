<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} · {{ config('app.name', 'Prime Grade School') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-100">
@php
    $cmsSubmenus = [
        ['route' => 'cms.dashboard', 'label' => 'CMS Dashboard', 'match' => 'cms.dashboard'],
        ['route' => 'cms.settings', 'label' => 'Website Settings', 'match' => 'cms.settings'],
        ['route' => 'cms.pages.index', 'label' => 'Pages', 'match' => 'cms.pages.*'],
        ['route' => 'cms.menus', 'label' => 'Navigation Menus', 'match' => 'cms.menus'],
        ['route' => 'cms.announcements.index', 'label' => 'Announcements', 'match' => 'cms.announcements.*'],
        ['route' => 'cms.programs.index', 'label' => 'Academic Programs', 'match' => 'cms.programs.*'],
        ['route' => 'cms.facilities.index', 'label' => 'Facilities', 'match' => 'cms.facilities.*'],
        ['route' => 'cms.gallery.index', 'label' => 'Gallery', 'match' => 'cms.gallery.*'],
        ['route' => 'cms.principles.index', 'label' => '10 Point Program', 'match' => 'cms.principles.*'],
        ['route' => 'cms.media', 'label' => 'Media Library', 'match' => 'cms.media'],
        ['route' => 'cms.submissions.index', 'label' => 'Form Submissions', 'match' => 'cms.submissions.*'],
    ];
    $cmsSectionActive = request()->routeIs('cms.*');
    $adminLogoPath = \App\Models\Cms\Setting::get('logo', 'assets/logo.png') ?: 'assets/logo.png';
    $adminLogoUrl = asset(ltrim($adminLogoPath, '/'));
@endphp

<div
    x-data="{ sidebarOpen: false, cmsOpen: {{ $cmsSectionActive ? 'true' : 'false' }} }"
    class="h-screen overflow-hidden lg:grid lg:grid-cols-[16rem_minmax(0,1fr)]"
>
    <div
        x-show="sidebarOpen"
        x-transition.opacity
        class="fixed inset-0 z-40 bg-slate-900/50 lg:hidden"
        @click="sidebarOpen = false"
        style="display: none;"
    ></div>

    <aside
        class="fixed inset-y-0 left-0 z-50 flex h-screen w-64 flex-col overflow-hidden bg-slate-900 text-white transition-transform duration-200 lg:static lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    >
        <div class="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-slate-700 px-3">
            <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-2.5 font-semibold tracking-tight" wire:navigate>
                <img
                    src="{{ $adminLogoUrl }}"
                    alt="{{ config('app.name') }} logo"
                    class="h-9 w-9 shrink-0 rounded-md object-contain p-0.5"
                >
                <span class="min-w-0">
                    <span class="block truncate">{{ config('app.name') }}</span>
                    <span class="mt-0.5 block text-xs font-normal text-slate-400">Admin Dashboard</span>
                </span>
            </a>
            <button type="button" class="shrink-0 rounded p-1 text-slate-300 hover:bg-slate-800 lg:hidden" @click="sidebarOpen = false" aria-label="Close menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="slim-scroll min-h-0 flex-1 overflow-y-auto overscroll-contain px-3 py-4 space-y-1">
            <a
                href="{{ route('dashboard') }}"
                wire:navigate
                @click="sidebarOpen = false"
                class="block rounded-md px-3 py-2 text-sm {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
            >Dashboard</a>

            <div>
                <button
                    type="button"
                    @click="cmsOpen = ! cmsOpen"
                    class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm {{ $cmsSectionActive ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                >
                    <span>CMS</span>
                    <svg class="h-4 w-4 shrink-0 transition-transform" :class="{ 'rotate-180': cmsOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="cmsOpen" class="mt-1 space-y-1 border-l border-slate-700 ms-3 ps-2" style="display: none;">
                    @foreach ($cmsSubmenus as $link)
                        <a
                            href="{{ route($link['route']) }}"
                            wire:navigate
                            @click="sidebarOpen = false"
                            class="block rounded-md px-3 py-2 text-sm {{ request()->routeIs($link['match']) ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
                        >{{ $link['label'] }}</a>
                    @endforeach
                </div>
            </div>
        </nav>

        <div class="shrink-0 border-t border-slate-700 p-3">
            <a href="{{ url('/') }}" target="_blank" class="mb-2 block rounded-md px-3 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">View website ↗</a>
            <a href="{{ route('profile') }}" wire:navigate class="mb-2 block rounded-md px-3 py-2 text-sm {{ request()->routeIs('profile') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full rounded-md px-3 py-2 text-left text-sm text-red-300 hover:bg-slate-800 hover:text-red-200">Log out</button>
            </form>
        </div>
    </aside>

    <div class="flex min-h-0 min-w-0 flex-col overflow-y-auto overscroll-contain">
        <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-slate-200 bg-white px-4 shadow-sm sm:px-6">
            <div class="flex items-center gap-3">
                <button type="button" class="rounded-md p-2 text-slate-600 hover:bg-slate-100 lg:hidden" @click="sidebarOpen = true" aria-label="Open menu">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    @isset($header)
                        {{ $header }}
                    @else
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Admin</p>
                        <h1 class="text-sm font-semibold text-slate-900 sm:text-base">Content Management</h1>
                    @endisset
                </div>
            </div>
            <div class="text-right text-sm">
                <p class="font-medium text-slate-800">{{ auth()->user()->name }}</p>
                <p class="hidden text-xs text-slate-500 sm:block">{{ auth()->user()->email }}</p>
            </div>
        </header>

        <main class="p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
