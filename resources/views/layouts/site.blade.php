<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>@yield('title', ($settings['site_name'] ?? 'The Prime Grade School').' | '.($settings['site_tagline'] ?? 'Education with Character'))</title>
<meta name="description" content="@yield('description', 'Enrolment is open at The Prime Grade School, Borabu, Nyamira County.')">
<meta name="theme-color" content="#0b2c4e">
<link rel="icon" href="{{ $media->url($settings['logo'] ?? 'assets/logo.png') ?: asset('assets/logo.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700;9..144,800&family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/site.css') }}">
@stack('head')
</head>
<body>
<header>
 <div class="bar">
  <a class="brand" href="{{ url('/') }}">
   <img src="{{ $media->url($settings['logo'] ?? null) ?: asset('assets/logo.png') }}" alt="{{ $settings['site_name'] }} crest">
   <span><b>{{ $settings['site_name'] }}</b><small>{{ $settings['site_tagline'] }}</small></span>
  </a>
  <button class="menu" aria-expanded="false" aria-controls="nav">Menu</button>
  <nav id="nav" aria-label="Main">
   @foreach($menuItems as $item)
    @php
      $url = str_starts_with($item->url, 'http') ? $item->url : url($item->url === '/' ? '/' : trim($item->url, '/'));
      $path = trim(parse_url($item->url, PHP_URL_PATH) ?? $item->url, '/');
      $current = $path === '' ? request()->is('/') : request()->is($path) || request()->is($path.'/*');
    @endphp
    <a href="{{ $url }}" @if($current) aria-current="page" @endif>{{ $item->label }}</a>
   @endforeach
  </nav>
 </div>
</header>

@yield('content')

<footer>
 <div class="wrap">
  <div class="foot">
   <div class="foot-brand">
    <div class="foot-brand-inner">
     <img src="{{ $media->url($settings['logo'] ?? null) ?: asset('assets/logo.png') }}" alt="">
     <div>
      <b>{{ $settings['site_name'] }}</b>
      <p>{{ $settings['footer_description'] }}</p>
     </div>
    </div>
   </div>
   <div>
    <h3>Explore</h3>
    <ul>
     @foreach($menuItems->where('label', '!=', 'Home') as $item)
      <li><a href="{{ url(trim($item->url, '/') ?: '/') }}">{{ $item->label }}</a></li>
     @endforeach
    </ul>
   </div>
   <div>
    <h3>Visit or call</h3>
    <div class="foot-meta">
     <a href="tel:{{ $settings['phone_link'] }}">{{ $settings['phone'] }}</a><br>
     <a href="mailto:{{ $settings['email'] }}">{{ $settings['email'] }}</a><br>
     {{ $settings['location'] }}<br>
     {{ $settings['location_detail'] }}
    </div>
   </div>
  </div>
  <div class="foot-base">
   <span>{{ $settings['copyright'] }}</span>
   <span>Powered by <a href="{{ $settings['powered_by_url'] }}" target="_blank" rel="noopener noreferrer">{{ $settings['powered_by_text'] }}</a></span>
  </div>
 </div>
</footer>
<script src="{{ asset('js/site.js') }}"></script>
@stack('scripts')
</body>
</html>
