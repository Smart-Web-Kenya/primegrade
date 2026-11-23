@extends('layouts.site')

@php
  $page = \App\Models\Cms\Page::query()->published()->where('slug', 'contact')->first();
  $c = $page?->content ?? [];
@endphp

@section('title', $page?->seo_title ?? 'Contact | '.$settings['site_name'])
@section('description', $page?->seo_description ?? ($page?->excerpt ?? 'Contact The Prime Grade School office or request admission.'))

@section('content')
<main>
<section class="page-hero">
 <div class="wrap">
  <p class="breadcrumbs"><a href="{{ route('home') }}">Home</a> / Contact</p>
  <p class="kicker">Contact &amp; Location</p>
  <h1>{{ $page?->title ?? 'Get in touch' }}</h1>
  <p>{{ $page?->excerpt ?? 'Call, email, or send a message to the school office.' }}</p>
  <div class="cta" style="margin-top:1rem">
   <a class="btn" href="#contact-form">Send a message</a>
   <a class="btn ghost" href="#admission-form">Request admission</a>
  </div>
 </div>
</section>

@if($settings['admission_notice'])
<div class="alert"><div class="wrap">
 <strong>{{ $settings['admission_notice'] }}</strong>
 <a href="tel:{{ $settings['phone_link'] }}">Call {{ $settings['phone'] }}</a>
</div></div>
@endif

<section class="band-cream">
 <div class="wrap">
  <p class="kicker">School office</p>
  <h2>{{ $c['office_heading'] ?? 'Contact details' }}</h2>
  <dl class="detail-list">
   <div><dt>Phone</dt><dd><a href="tel:{{ $settings['phone_link'] }}">{{ $settings['phone'] }}</a></dd></div>
   <div><dt>Email</dt><dd><a href="mailto:{{ $settings['email'] }}">{{ $settings['email'] }}</a></dd></div>
   <div><dt>Website</dt><dd><a href="https://{{ ltrim($settings['website'], 'https://') }}">{{ $settings['website'] }}</a></dd></div>
   <div><dt>P.O. Box</dt><dd>{{ $settings['postal_address'] }}</dd></div>
   <div><dt>Location</dt><dd>{{ $settings['location'] }}<small>{{ $settings['location_detail'] }}</small></dd></div>
  </dl>
  <div class="cta" style="margin-top:1.5rem">
   <a class="btn" href="tel:{{ $settings['phone_link'] }}">Call the school</a>
   <a class="btn navy" href="mailto:{{ $settings['email'] }}">Email the office</a>
  </div>
 </div>
</section>

<section class="band-white" id="contact-form">
 <div class="wrap form-split">
  <div>
   <p class="kicker">Write to us</p>
   <h2>Contact form</h2>
   <p class="lead">Send a general enquiry to the school office. We will reply by phone or email.</p>
  </div>
  <livewire:forms.contact-form />
 </div>
</section>

<section class="band-cream" id="admission-form">
 <div class="wrap form-split">
  <div>
   <p class="kicker">Enrolment</p>
   <h2>Request for admission</h2>
   <p class="lead">Share your details and the grade you are applying for. The admissions team will follow up with next steps.</p>
  </div>
  <livewire:forms.admission-form />
 </div>
</section>

<section class="band-white">
 <div class="wrap">
  <p class="kicker">Find us</p>
  <h2>{{ $c['location_heading'] ?? 'Location' }}</h2>
  <p class="lead">{{ $c['location_text'] ?? ($settings['location'].', '.$settings['location_detail']) }}</p>
  <div class="map-frame" role="img" aria-label="Map placeholder for {{ $settings['location'] }}" style="margin-top:1.25rem;max-height:22rem">
   <div>
    <strong>{{ $settings['location'] }}</strong>
    {{ $settings['location_detail'] }}
   </div>
  </div>
 </div>
</section>
</main>
@endsection
