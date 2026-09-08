<style>
  .hero{
    position:relative;
    min-height:100vh;
    min-height:100svh;
    display:flex;
    flex-direction:column;
    background:
      radial-gradient(ellipse at 70% 25%, rgba(120,190,190,0.35), transparent 20%),
      linear-gradient(100deg, rgba(10,20,20,0.82) 0%, rgba(10,20,20,0.35) 42%, rgba(60,90,90,0.15) 60%, rgba(10,20,20,0.55) 100%);
    overflow:hidden;
    font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  }

  .hero *{ box-sizing:border-box; }

  .hero-slides{
    position:absolute;
    inset:0;
    z-index:0;
  }

  .hero-slide{
    position:absolute;
    inset:0;
    width:100%;
    height:100%;
    object-fit:cover;
    opacity:0;
    transition:opacity 1.2s ease;
  }

  .hero-slide.active{
    opacity:1;
    z-index:1;
  }

  /* Video background fills the same space as image slides */
  .hero-video{
    position:absolute;
    inset:0;
    width:100%;
    height:100%;
    object-fit:cover;
    z-index:1;
  }

  .hero-dots{
    position:absolute;
    left:60px;
    bottom:36px;
    z-index:10;
    display:flex;
    gap:10px;
  }

  .hero-dot{
    width:11px;
    height:11px;
    border-radius:50%;
    background:rgba(255,255,255,0.45);
    border:none;
    padding:0;
    cursor:pointer;
    transition:background 0.2s ease, transform 0.2s ease;
  }

  .hero-dot.active{
    background:var(--orange);
    transform:scale(1.15);
  }

  .hero .rig-decor{
    z-index:2;
  }

  .hero .hero-content{
    position:relative;
    z-index:5;
    flex:1;
    display:flex;
    align-items:center;
    padding:0 90px;
  }

  .hero .hero-inner{ max-width:760px; }

  .hero .eyebrow{
    color:var(--cream);
    font-size:19px;
    font-weight:600;
    margin-bottom:22px;
    letter-spacing:0.2px;
  }

  .hero h1{
    color:var(--white);
    font-size:clamp(28px, 4.2vw, 60px);
    line-height:1.12;
    font-weight:800;
    letter-spacing:-0.5px;
    margin:0 0 26px;
    text-shadow: 0 2px 24px rgba(0,0,0,0.35);
    word-break:break-word;
  }

  .hero .lede{
    color:rgba(255,255,255,0.92);
    font-size:clamp(15px, 1.6vw, 19px);
    line-height:1.55;
    max-width:640px;
    margin:0 0 36px;
  }

  .hero .cta-row{
    display:flex;
    gap:18px;
    flex-wrap:wrap;
  }

  .hero .btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:16px 30px;
    border-radius:30px;
    font-size:16px;
    font-weight:700;
    text-decoration:none;
    transition: transform 0.15s ease, background 0.2s ease, color 0.2s ease;
    cursor:pointer;
    border:2px solid transparent;
    white-space:nowrap;
  }

  .hero .btn-primary{
    background:var(--orange);
    color:var(--white);
  }
  .hero .btn-primary:hover{
    background:var(--orange-dark);
    transform:translateY(-2px);
  }

  .hero .btn-secondary{
    background:transparent;
    border-color:rgba(255,255,255,0.75);
    color:var(--white);
  }
  .hero .btn-secondary:hover{
    background:rgba(255,255,255,0.12);
    transform:translateY(-2px);
  }

  /* ===== Tablet ===== */
  @media (max-width: 1024px){
    .hero .hero-content{ padding:0 50px; }
  }

  /* ===== Small tablet / large phone ===== */
  @media (max-width: 900px){
    .hero .hero-content{ padding:0 24px; }
    .hero-dots{ left:24px; bottom:22px; }
  }

  /* ===== Phones ===== */
  @media (max-width: 600px){
    .hero{ min-height:auto; }
    .hero .hero-content{
      padding:60px 20px 60px;
      align-items:flex-start;
    }
    .hero .eyebrow{ font-size:15px; margin-bottom:14px; }
    .hero h1{ margin-bottom:18px; }
    .hero .lede{ margin-bottom:26px; }
    .hero .cta-row{
      flex-direction:column;
      width:100%;
    }
    .hero .btn{
      width:100%;
      padding:15px 24px;
    }
    .hero-dots{ left:20px; bottom:16px; }
    .hero-dot{ width:9px; height:9px; }
  }

  /* ===== Very small phones ===== */
  @media (max-width: 380px){
    .hero .hero-content{ padding:48px 16px 48px; }
    .hero .eyebrow{ font-size:14px; }
    .hero .btn{ font-size:14px; padding:14px 20px; }
  }

  @media (prefers-reduced-motion: reduce){
    .hero-slide{ transition:none; }
    .hero .btn{ transition:none; }
  }
</style>

@php
  // Does the backend have a video for this banner?
  $heroVideo = $banner->video ?? null;

  // Only build the image slideshow if there is NO video
  $heroImages = collect([]);
  if (empty($heroVideo)) {
      $heroImages = collect([$banner->image_1 ?? null, $banner->image_2 ?? null, $banner->image_3 ?? null])
          ->filter()
          ->values();
      // No static fallback image anymore — if empty, the section just shows the gradient background.
  }
@endphp

<section class="hero">
@include('web.layout.navbar')

  @if (!empty($heroVideo))
    {{-- Video takes priority over images --}}
    <video
      class="hero-video"
      src="{{ Str::startsWith($heroVideo, 'images/') ? asset($heroVideo) : asset('storage/' . $heroVideo) }}"
      autoplay
      muted
      loop
      playsinline
      preload="auto"
    ></video>
  @elseif ($heroImages->isNotEmpty())
    <div class="hero-slides">
      @foreach ($heroImages as $index => $image)
        <img
          src="{{ Str::startsWith($image, 'images/') ? asset($image) : asset('storage/' . $image) }}"
          class="hero-slide @if($index === 0) active @endif"
          alt="{{ $banner->title ?? 'Banner image' }}"
        >
      @endforeach
    </div>

    @if ($heroImages->count() > 1)
      <div class="hero-dots" id="heroDots">
        @foreach ($heroImages as $index => $image)
          <button
            type="button"
            class="hero-dot @if($index === 0) active @endif"
            data-slide="{{ $index }}"
            aria-label="Slide {{ $index + 1 }}"
          ></button>
        @endforeach
      </div>
    @endif
  @endif

  <div class="rig-decor" aria-hidden="true"></div>
  <div class="hero-figure" aria-hidden="true"></div>

  <div class="hero-content">
    <div class="hero-inner">
      <p class="eyebrow">Engineering, Inspection, Energy</p>
      <h1>{{ $banner->title ?? 'Inspection Services for the Oil and Gas Industry' }}</h1>
      <p class="lede">{{ $banner->description ?? 'Established in Erbil, Iraq, EIS Ltd provides on-site, field and shop-based inspection services for the Oil and Gas Industries, supported by qualified personnel and modern non-destructive testing equipment.' }}</p>
      <div class="cta-row">
        <a href="{{ url('/contact') }}" class="btn btn-primary">Contact Us Now</a>
        <a href="{{ url('/services') }}" class="btn btn-secondary">Explore Services</a>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const heroSlides = document.querySelectorAll('.hero-slide');
    const heroDots = document.querySelectorAll('.hero-dot');
    let heroIndex = 0;
    let heroTimer;

    function showHeroSlide(index) {
      heroSlides.forEach(function (s) { s.classList.remove('active'); });
      heroDots.forEach(function (d) { d.classList.remove('active'); });
      heroSlides[index].classList.add('active');
      if (heroDots[index]) heroDots[index].classList.add('active');
      heroIndex = index;
    }

    function startHeroAutoplay() {
      clearInterval(heroTimer);
      heroTimer = setInterval(function () {
        showHeroSlide((heroIndex + 1) % heroSlides.length);
      }, 6000);
    }

    // Only runs when the image slideshow is present (i.e. no video)
    if (heroSlides.length > 1) {
      heroDots.forEach(function (dot) {
        dot.addEventListener('click', function () {
          showHeroSlide(parseInt(dot.getAttribute('data-slide'), 10));
          startHeroAutoplay();
        });
      });
      startHeroAutoplay();
    }
  });
</script>