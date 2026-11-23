@extends('layouts.site')

@php
  $page = \App\Models\Cms\Page::query()->published()->where('slug', 'home')->first();
  $content = $page->content ?? [];
  $hero = $content['hero'] ?? [];
  $about = $content['about'] ?? [];
  $academics = $content['academics'] ?? [];
  $facilities = $content['facilities'] ?? [];
  $news = $content['news'] ?? [];
  $contact = $content['contact'] ?? [];
  $programs = \App\Models\Cms\Program::query()->published()->orderBy('sort_order')->get();
  $facilityItems = \App\Models\Cms\FacilityItem::query()->published()->orderBy('sort_order')->get();
  $gallery = \App\Models\Cms\GalleryImage::query()->published()->orderBy('sort_order')->take(3)->get();
  $announcements = \App\Models\Cms\Announcement::query()->published()->orderBy('sort_order')->take(2)->get();
  $principles = \App\Models\Cms\Principle::query()->published()->orderBy('sort_order')->take(6)->get();
  $heroImage = $media->url($hero['image'] ?? null) ?: asset('assets/building.jpg');
  $logo = $media->url($settings['logo'] ?? null) ?: asset('assets/logo.png');
@endphp

@section('title', $page->seo_title ?? (($settings['site_name'] ?? '').' | '.($settings['site_tagline'] ?? '')))
@section('description', $page->seo_description ?? ($page->excerpt ?? ''))

@section('content')
<main id="top">
<section class="hero" aria-label="Welcome">
 <img src="{{ $heroImage }}" alt="The classroom building of {{ $settings['site_name'] }}" fetchpriority="high">
 <div class="wrap">
  <div class="hero-copy">
   <p class="place">{{ $hero['eyebrow'] ?? ($settings['location'].' · Enrolment open for '.$settings['enrolment_year']) }}</p>
   <h1>{{ $hero['heading'] ?? $settings['site_name'] }}</h1>
   <p class="tagline">{{ $hero['tagline'] ?? $settings['site_tagline'] }}</p>
   <p>{{ $hero['text'] ?? $page->excerpt }}</p>
   <div class="cta">
    <a class="btn" href="{{ route('contact') }}#admission-form">Request admission</a>
    <a class="btn ghost" href="{{ route('academics') }}">Grades offered</a>
   </div>
  </div>
  <img class="crest" src="{{ $logo }}" alt="{{ $settings['site_name'] }} crest">
 </div>
</section>

@if($settings['admission_notice'])
<div class="alert"><div class="wrap">
 <strong>{{ $settings['admission_notice'] }}</strong>
 <a href="{{ route('news') }}">View announcements</a>
</div></div>
@endif

<section class="home-preview band-cream" id="about">
 <div class="wrap">
  <div class="preview-head">
   <div>
    <p class="kicker">About the school</p>
    <h2>{{ $about['heading'] ?? $settings['school_motto'] }}</h2>
    <p class="lead">{{ $about['lead'] ?? 'Our identity, values and approach to learner development.' }}</p>
   </div>
   <a class="more-link" href="{{ route('about') }}">About the school →</a>
  </div>
  <div class="preview-split">
   <div>
    <ul class="preview-points">
     @forelse($principles as $principle)
      <li><a href="{{ route('about') }}#program">{{ $principle->title }}</a></li>
     @empty
      <li><a href="{{ route('about') }}#program">Education with Character</a></li>
     @endforelse
    </ul>
    <p style="margin-top:1rem;color:#3a4654">Plus reading, talent development, leadership, service and environmental responsibility in the full 10 Point Program.</p>
   </div>
   <dl class="preview-vm">
    <div><dt>MOTTO</dt><dd>{{ $settings['school_motto'] }}</dd></div>
    <div><dt>VISION</dt><dd>{{ $settings['school_vision'] }}</dd></div>
    <div><dt>MISSION</dt><dd>{{ $settings['school_mission'] }}</dd></div>
   </dl>
  </div>
 </div>
</section>

<section class="home-preview band-white" id="grades">
 <div class="wrap">
  <div class="preview-head">
   <div>
    <p class="kicker">Academics</p>
    <h2>{{ $academics['heading'] ?? 'Grades offered' }}</h2>
    <p class="lead">{{ $academics['lead'] ?? 'Early Years, Primary and Junior School — with boarding for selected grades.' }}</p>
   </div>
   <a class="more-link" href="{{ route('academics') }}">Full academic programs →</a>
  </div>
  <div class="preview-cards">
   @foreach($programs as $index => $program)
    <a class="preview-card {{ $index === 2 ? 'gold' : '' }}" href="{{ route('academics') }}#{{ $program->slug }}">
     <h3>{{ $program->subtitle ?: $program->title }}</h3>
     <p class="meta">{{ $program->grades_label }}</p>
     <p>{{ $program->body ?: \Illuminate\Support\Str::limit($program->summary, 120) }}</p>
     <span class="go">View {{ $program->subtitle ?: $program->title }} →</span>
    </a>
   @endforeach
  </div>
 </div>
</section>

<section class="home-preview band-soft" id="life">
 <div class="wrap">
  <div class="preview-head">
   <div>
    <p class="kicker">Facilities &amp; co-curricular</p>
    <h2>{{ $facilities['heading'] ?? 'Life at Prime Grade' }}</h2>
    <p class="lead">{{ $facilities['lead'] ?? 'Spaces and activities that support learning, character and talent.' }}</p>
   </div>
   <a class="more-link" href="{{ route('facilities') }}">Facilities &amp; activities →</a>
  </div>
  <div class="preview-chips">
   @foreach($facilityItems->take(8) as $item)
    <span>{{ $item->title }}</span>
   @endforeach
  </div>
  @if($gallery->isNotEmpty())
  <div class="preview-gallery" aria-label="Campus photo preview">
   @foreach($gallery as $image)
    <figure><img src="{{ $media->url($image->image_path) ?: asset('assets/pupils.jpg') }}" alt="{{ $image->alt_text ?: $image->title }}" loading="lazy"></figure>
   @endforeach
  </div>
  @endif
 </div>
</section>

<section class="home-preview band-navy" id="news">
 <div class="wrap">
  <div class="preview-head">
   <div>
    <p class="kicker">News &amp; announcements</p>
    <h2>{{ $news['heading'] ?? 'Latest updates' }}</h2>
    <p class="lead">{{ $news['lead'] ?? 'Important notices for parents and the school community.' }}</p>
   </div>
   <a class="more-link" href="{{ route('news') }}">All news →</a>
  </div>
  <div class="preview-news">
   @forelse($announcements as $item)
    <a href="{{ route('news') }}">
     <time @if($item->published_at) datetime="{{ $item->published_at->toDateString() }}" @endif>{{ $item->category ?: 'Update' }}</time>
     <h3>{{ $item->title }}</h3>
     <p>{{ $item->excerpt }}</p>
    </a>
   @empty
    <a href="{{ route('news') }}">
     <time>Admissions</time>
     <h3>{{ $settings['admission_notice'] }}</h3>
     <p>Call or visit the school office to enrol.</p>
    </a>
   @endforelse
  </div>
 </div>
</section>

<section class="contact" id="contact">
 <div class="wrap">
  <div class="contact-copy">
   <div>
    <img class="contact-logo" src="{{ $logo }}" alt="{{ $settings['site_name'] }} crest">
    <p class="kicker">Contact</p>
    <h2>{{ $contact['heading'] ?? 'Visit or call to enrol' }}</h2>
    <p class="lead">{{ $contact['lead'] ?? ($settings['location'].' · '.$settings['location_detail']) }}</p>
   </div>
   <div class="contact-actions">
    <a class="btn" href="tel:{{ $settings['phone_link'] }}">Call {{ $settings['phone'] }}</a>
    <a class="phone" href="{{ route('contact') }}">Contact &amp; location →</a>
   </div>
  </div>
  <aside class="contact-panel" aria-label="Quick contact">
   <h3>Quick details</h3>
   <dl class="info">
    <div><dt>Phone</dt><dd><a href="tel:{{ $settings['phone_link'] }}">{{ $settings['phone'] }}</a></dd></div>
    <div><dt>Email</dt><dd><a href="mailto:{{ $settings['email'] }}">{{ $settings['email'] }}</a></dd></div>
    <div><dt>Location</dt><dd>{{ $settings['location'] }}<small>{{ $settings['location_detail'] }}</small></dd></div>
   </dl>
  </aside>
 </div>
</section>
</main>
@endsection
