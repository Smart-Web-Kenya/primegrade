@extends('layouts.site')

@php
  $page = \App\Models\Cms\Page::query()->published()->where('slug', 'academics')->first();
  $c = $page->content ?? [];
  $programs = \App\Models\Cms\Program::query()->published()->orderBy('sort_order')->get();
  $learning = \App\Models\Cms\FacilityItem::query()->published()->where('category', 'learning')->orderBy('sort_order')->get();
@endphp

@section('title', $page->seo_title ?? 'Academic Programs | '.$settings['site_name'])
@section('description', $page->seo_description ?? ($page->excerpt ?? ''))

@section('content')
<main>
<section class="page-hero">
 <div class="wrap">
  <p class="breadcrumbs"><a href="{{ route('home') }}">Home</a> / Academics</p>
  <p class="kicker">Academic Programs</p>
  <h1>{{ $page->title ?? 'Grades offered' }}</h1>
  <p>{{ $page->excerpt }}</p>
 </div>
</section>

<section class="band-cream">
 <div class="wrap">
  <p class="kicker">Pathways</p>
  <h2>{{ $c['pathways_heading'] ?? 'Our educational structure' }}</h2>
  <p class="lead">{{ $c['pathways_lead'] ?? '' }}</p>

  @foreach($programs as $program)
  <article class="program" id="{{ $program->slug }}">
   <h3>{{ $program->title }}</h3>
   <p class="lead">{{ $program->grades_label }}</p>
   @if($program->meta_tags)
   <div class="meta">
    @foreach($program->meta_tags as $tag)
     <span>{{ $tag }}</span>
    @endforeach
   </div>
   @endif
   <p>{{ $program->summary }}</p>
   @if($program->bullets)
   <ul>
    @foreach($program->bullets as $bullet)
     <li>{{ $bullet }}</li>
    @endforeach
   </ul>
   @endif
  </article>
  @endforeach
 </div>
</section>

<section class="boarding">
 <div class="wrap">
  <div>
   <p class="kicker" style="color:var(--gold)">Residence</p>
   <h2><span>Boarding</span> available</h2>
   <div class="levels">
    <span>Grades 4 to 6</span>
    <span>Junior School: Grades 7 to 9</span>
   </div>
   <p class="lead">{{ $c['boarding'] ?? '' }}</p>
  </div>
  <figure>
   <img src="{{ asset('assets/compound.jpg') }}" alt="Pupils lined up on the school compound" loading="lazy">
  </figure>
 </div>
</section>

<section class="band-soft">
 <div class="wrap">
  <p class="kicker">Approach</p>
  <h2>{{ $c['environment_heading'] ?? 'Learning environment and development' }}</h2>
  <p class="lead">{{ $c['environment_lead'] ?? '' }}</p>
  <div class="panel-grid">
   @foreach($learning->take(4) as $item)
    <article class="panel"><h3>{{ $item->title }}</h3><p>{{ $item->description }}</p></article>
   @endforeach
  </div>
  <div class="cta" style="margin-top:1.35rem">
   <a class="btn navy" href="{{ route('facilities') }}">Explore facilities</a>
   <a class="btn" href="{{ route('contact') }}">Enquire about enrolment</a>
  </div>
 </div>
</section>
</main>
@endsection
