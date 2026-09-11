<style>
  .hero{
    position:relative;
    min-height:100vh;
    min-height:100svh;
    display:flex;
    flex-direction:column;
    background:
      radial-gradient(ellipse at 15% 85%, rgba(10,10,10,0.55), transparent 45%),
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

  /* ===== Content block — anchored bottom-left on ALL screen sizes ===== */
  .hero .hero-content{
    position:relative;
    z-index:5;
    flex:1;
    display:flex;
    align-items:flex-end;
    justify-content:flex-start;
    padding:0 90px 90px;
  }

  .hero .hero-inner{
    max-width:760px;
    animation: heroFadeUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) both;
  }

  @keyframes heroFadeUp{
    from{ opacity:0; transform:translateY(28px); }
    to{ opacity:1; transform:translateY(0); }
  }

  .hero .eyebrow{
    position:relative;
    display:inline-flex;
    align-items:center;
    gap:10px;
    color:var(--cream);
    font-size:15px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:1.6px;
    margin-bottom:20px;
  }

  .hero .eyebrow::before{
    content:"";
    display:inline-block;
    width:36px;
    height:2px;
    background:var(--orange);
    border-radius:2px;
  }

  .hero h1{
    color:var(--white);
    font-size:clamp(28px, 4.2vw, 58px);
    line-height:1.12;
    font-weight:800;
    letter-spacing:-0.5px;
    margin:0 0 22px;
    text-shadow: 0 4px 28px rgba(0,0,0,0.45);
    word-break:break-word;
  }

  .hero .lede{
    color:rgba(255,255,255,0.92);
    font-size:clamp(15px, 1.5vw, 18px);
    line-height:1.6;
    max-width:600px;
    margin:0 0 34px;
  }

  .hero .cta-row{
    display:flex;
    gap:16px;
    flex-wrap:wrap;
  }

  .hero .btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    padding:16px 32px;
    border-radius:30px;
    font-size:16px;
    font-weight:700;
    text-decoration:none;
    transition: transform 0.2s ease, background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
    cursor:pointer;
    border:2px solid transparent;
    white-space:nowrap;
  }

  .hero .btn-primary{
    background:var(--orange);
    color:var(--white);
    box-shadow:0 10px 26px rgba(232,121,45,0.35);
  }
  .hero .btn-primary:hover{
    background:var(--orange-dark);
    transform:translateY(-3px);
    box-shadow:0 14px 32px rgba(232,121,45,0.45);
  }

  .hero .btn-secondary{
    background:rgba(255,255,255,0.06);
    border-color:rgba(255,255,255,0.75);
    color:var(--white);
    backdrop-filter:blur(2px);
  }
  .hero .btn-secondary:hover{
    background:rgba(255,255,255,0.16);
    transform:translateY(-3px);
  }

  .hero .btn-secondary .arrow{
    transition:transform 0.2s ease;
  }
  .hero .btn-secondary:hover .arrow{
    transform:translateX(3px);
  }

  /* ===== Tablet ===== */
  @media (max-width: 1024px){
    .hero .hero-content{ padding:0 50px 70px; }
  }

  /* ===== Small tablet / large phone ===== */
  @media (max-width: 900px){
    .hero .hero-content{ padding:0 24px 56px; }
    .hero-dots{ left:24px; bottom:22px; }

    /* Buttons already live in the hamburger menu at this breakpoint — avoid showing them twice */
    .hero .cta-row{ display:none; }
  }

  /* ===== Phones ===== */
  @media (max-width: 600px){
    .hero{
      min-height:60vh;
      min-height:60svh;
    }
    .hero .hero-content{ padding:60px 20px 48px; }
    .hero .eyebrow{ font-size:13px; margin-bottom:14px; }
    .hero h1{ margin-bottom:16px; }
    .hero .lede{ margin-bottom:0; }
    .hero-dots{ left:20px; bottom:16px; }
    .hero-dot{ width:9px; height:9px; }
  }

  /* ===== Very small phones ===== */
  @media (max-width: 380px){
    .hero .hero-content{ padding:48px 16px 40px; }
    .hero .eyebrow{ font-size:12px; }
  }

  @media (prefers-reduced-motion: reduce){
    .hero-slide{ transition:none; }
    .hero .btn{ transition:none; }
    .hero .hero-inner{ animation:none; }
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
      // No static fallback image — if empty, the section just shows the gradient background.
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
      @if (!empty($banner->title))
        <p class="eyebrow">Engineering, Inspection, Energy</p>
        <h1>{{ $banner->title }}</h1>
      @endif

      @if (!empty($banner->description))
        <p class="lede">{{ $banner->description }}</p>
      @endif

      <div class="cta-row">
        <a href="{{ url('/contact') }}" class="btn btn-primary">Contact Us Now</a>
        <a href="{{ url('/services') }}" class="btn btn-secondary">Explore Services <span class="arrow">&#8594;</span></a>
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