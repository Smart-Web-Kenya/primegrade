@extends('layouts.site')

@php
  $page = \App\Models\Cms\Page::query()->published()->where('slug', 'about')->first();
  $c = $page->content ?? [];
  $principles = \App\Models\Cms\Principle::query()->published()->orderBy('sort_order')->get();
  $approach = $c['approach'] ?? [];
  $building = asset('assets/building.jpg');
@endphp

@section('title', $page->seo_title ?? 'About the School | '.$settings['site_name'])
@section('description', $page->seo_description ?? ($page->excerpt ?? ''))

@section('content')
<main>
<section class="page-hero">
 <div class="wrap">
  <p class="breadcrumbs"><a href="{{ route('home') }}">Home</a> / About</p>
  <p class="kicker">About the School</p>
  <h1>{{ $page->title ?? $settings['school_motto'] }}</h1>
  <p>{{ $page->excerpt }}</p>
 </div>
</section>

<section class="band-cream">
 <div class="wrap split">
  <div class="prose">
   <p class="kicker">Who we are</p>
   <h2>{{ $c['intro_heading'] ?? 'A place where every child grows in confidence and character' }}</h2>
   <p>{{ $c['intro'] ?? '' }}</p>
   @if(!empty($c['quote']))
    <blockquote>{{ $c['quote'] }}</blockquote>
   @endif
   @if(!empty($c['signature']))
    <p>{{ $c['signature'] }}</p>
   @endif
  </div>
  <img src="{{ $building }}" alt="The classroom building of {{ $settings['site_name'] }}" loading="lazy">
 </div>
</section>

<section class="motto">
 <div class="wrap">
  <div>
   <p class="kicker" style="color:var(--gold)">Identity</p>
   <h2>Vision, mission <span>and</span> motto</h2>
   <p class="lead" style="color:#d7e0ea">These statements guide our teaching, school culture and commitment to learner welfare.</p>
  </div>
  <dl class="vm">
   <div><dt>OUR MOTTO</dt><dd>{{ $settings['school_motto'] }}</dd></div>
   <div><dt>VISION</dt><dd>{{ $settings['school_vision'] }}</dd></div>
   <div><dt>MISSION</dt><dd>{{ $settings['school_mission'] }}</dd></div>
  </dl>
 </div>
</section>

<section class="band-soft">
 <div class="wrap">
  <p class="kicker">Teaching philosophy &amp; staff</p>
  <h2>How we support learner development</h2>
  <article class="life-lead" style="margin-top:1.25rem">
   <h3>{{ $c['staff_heading'] ?? 'Dedicated Teachers, Individual Attention' }}</h3>
   <p>{{ $c['staff_body'] ?? '' }}</p>
  </article>
  @if(count($approach))
  <div class="panel-grid">
   @foreach($approach as $item)
    <article class="panel">
     <h3>{{ $item['title'] ?? '' }}</h3>
     <p>{{ $item['body'] ?? '' }}</p>
    </article>
   @endforeach
  </div>
  @endif
  <div class="cta" style="margin-top:1.35rem">
   <a class="btn navy" href="{{ route('academics') }}">View academic programs</a>
   <a class="btn" href="{{ route('contact') }}">Contact the school</a>
  </div>
 </div>
</section>

<section class="points band-white" id="program">
 <div class="wrap">
  <p class="kicker">Guiding principles</p>
  <h2>The Prime Grade 10 Point Program</h2>
  <p class="lead">Our core values for school culture, discipline and learner welfare.</p>
  <ol>
   @foreach($principles as $principle)
    <li><div><h3>{{ $principle->title }}</h3><p>{{ $principle->body }}</p></div></li>
   @endforeach
  </ol>
  <p class="sign">{{ $c['signature'] ?? 'Prime Grade School: Shaping character, building knowledge and nurturing talent.' }}</p>
 </div>
</section>
</main>
@endsection
