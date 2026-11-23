@extends('layouts.site')

@php
  $page = \App\Models\Cms\Page::query()->published()->where('slug', 'facilities')->first();
  $c = $page->content ?? [];
  $learning = \App\Models\Cms\FacilityItem::query()->published()->where('category', 'learning')->orderBy('sort_order')->get();
  $arts = \App\Models\Cms\FacilityItem::query()->published()->where('category', 'arts')->orderBy('sort_order')->get();
  $transport = \App\Models\Cms\FacilityItem::query()->published()->where('category', 'transport')->orderBy('sort_order')->get();
  $gallery = \App\Models\Cms\GalleryImage::query()->published()->orderBy('sort_order')->get();
@endphp

@section('title', $page->seo_title ?? 'Facilities | '.$settings['site_name'])
@section('description', $page->seo_description ?? ($page->excerpt ?? ''))

@section('content')
<main>
<section class="page-hero">
 <div class="wrap">
  <p class="breadcrumbs"><a href="{{ route('home') }}">Home</a> / Facilities</p>
  <p class="kicker">Facilities &amp; Co-curricular</p>
  <h1>{{ $page->title ?? 'Life at Prime Grade' }}</h1>
  <p>{{ $page->excerpt }}</p>
 </div>
</section>

<section class="life band-cream">
 <div class="wrap">
  <p class="kicker">Learning spaces</p>
  <h2>{{ $c['learning_heading'] ?? 'Classrooms, library and digital learning' }}</h2>
  <article class="life-lead">
   <h3>{{ $c['staff_heading'] ?? 'Dedicated Teachers, Individual Attention' }}</h3>
   <p>{{ $c['staff_body'] ?? '' }}</p>
  </article>
  <div class="features">
   @foreach($learning->merge($transport) as $item)
    <article class="feature"><h3>{{ $item->title }}</h3><p>{{ $item->description }}</p></article>
   @endforeach
  </div>
 </div>
</section>

<section class="boarding">
 <div class="wrap">
  <div>
   <p class="kicker" style="color:var(--gold)">Boarding &amp; meals</p>
   <h2><span>Boarding</span> facilities</h2>
   <div class="levels">
    <span>Grades 4 to 6</span>
    <span>Junior School: Grades 7 to 9</span>
   </div>
   <p class="lead">{{ $c['boarding_body'] ?? '' }}</p>
  </div>
  <figure>
   <img src="{{ asset('assets/compound.jpg') }}" alt="Pupils lined up on the school compound" loading="lazy">
  </figure>
 </div>
</section>

<section class="band-soft">
 <div class="wrap">
  <p class="kicker">Holistic development</p>
  <h2>{{ $c['activities_heading'] ?? 'Arts, sport and clubs' }}</h2>
  <p class="lead">{{ $c['activities_lead'] ?? '' }}</p>
  <div class="panel-grid">
   @foreach($arts as $item)
    <article class="panel"><h3>{{ $item->title }}</h3><p>{{ $item->description }}</p></article>
   @endforeach
  </div>
 </div>
</section>

<section class="gallery" aria-label="Photo gallery">
 <div class="wrap">
  <p class="kicker">Campus moments</p>
  <div class="strip">
   @foreach($gallery as $image)
    <figure>
     <img src="{{ $media->url($image->image_path) }}" alt="{{ $image->alt_text ?: $image->title }}" loading="lazy">
     @if($image->caption)<figcaption>{{ $image->caption }}</figcaption>@endif
    </figure>
   @endforeach
  </div>
  <div class="cta" style="margin-top:1.25rem">
   <a class="btn" href="{{ route('contact') }}">Plan a school visit</a>
   <a class="btn ghost" href="{{ route('academics') }}">View academic programs</a>
  </div>
 </div>
</section>
</main>
@endsection
