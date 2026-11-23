@extends('layouts.site')

@php
  $page = \App\Models\Cms\Page::query()->published()->where('slug', 'news')->first();
  $c = $page->content ?? [];
  $announcements = \App\Models\Cms\Announcement::query()->published()->orderBy('sort_order')->get();
@endphp

@section('title', $page->seo_title ?? 'News | '.$settings['site_name'])
@section('description', $page->seo_description ?? ($page->excerpt ?? ''))

@section('content')
<main>
<section class="page-hero">
 <div class="wrap">
  <p class="breadcrumbs"><a href="{{ route('home') }}">Home</a> / News</p>
  <p class="kicker">News, Events &amp; Announcements</p>
  <h1>{{ $page->title ?? 'School updates' }}</h1>
  <p>{{ $page->excerpt }}</p>
 </div>
</section>

<section class="band-cream">
 <div class="wrap">
  <p class="kicker">Current notices</p>
  <h2>{{ $c['heading'] ?? 'Announcements' }}</h2>
  <div class="news-list">
   @forelse($announcements as $item)
    <article class="news-item">
     <time @if($item->published_at) datetime="{{ $item->published_at->toDateString() }}" @endif>{{ $item->category ?: 'Update' }}</time>
     <h3>{{ $item->title }}</h3>
     <p>{{ $item->body ?: $item->excerpt }}</p>
     @if($item->cta_text && $item->cta_url)
      <p><a class="btn {{ str_contains($item->cta_url, 'mailto') || str_starts_with($item->cta_url, '/') ? 'navy' : '' }}" href="{{ str_starts_with($item->cta_url, 'http') || str_starts_with($item->cta_url, 'tel:') || str_starts_with($item->cta_url, 'mailto:') ? $item->cta_url : url($item->cta_url) }}">{{ $item->cta_text }}</a></p>
     @endif
    </article>
   @empty
    <article class="news-item">
     <time>Admissions</time>
     <h3>{{ $settings['admission_notice'] }}</h3>
     <p>Call or visit the school office to enrol.</p>
     <p><a class="btn" href="tel:{{ $settings['phone_link'] }}">Call {{ $settings['phone'] }}</a></p>
    </article>
   @endforelse
  </div>
 </div>
</section>

<section class="band-white">
 <div class="wrap contact-grid">
  <div>
   <p class="kicker">For parents</p>
   <h2>{{ $c['parents_heading'] ?? 'Stay in touch' }}</h2>
   <p class="lead">{{ $c['parents_text'] ?? 'For admission notices and general information, call or email the school office.' }}</p>
   <div class="cta" style="margin-top:1rem">
    <a class="btn" href="tel:{{ $settings['phone_link'] }}">{{ $settings['phone'] }}</a>
    <a class="btn navy" href="mailto:{{ $settings['email'] }}">Email the office</a>
   </div>
  </div>
  <aside class="panel">
   <h3>Quick links</h3>
   <p style="margin-bottom:.85rem">Explore programmes and plan a visit.</p>
   <ul style="margin-left:1.1rem;color:#3a4654;display:grid;gap:.4rem">
    <li><a href="{{ route('academics') }}">Academic programs</a></li>
    <li><a href="{{ route('facilities') }}">Facilities &amp; school life</a></li>
    <li><a href="{{ route('contact') }}">Contact &amp; location</a></li>
   </ul>
  </aside>
 </div>
</section>
</main>
@endsection
