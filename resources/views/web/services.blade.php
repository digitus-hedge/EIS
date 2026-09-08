<style>
:root{
    --orange: #E8792D;
    --orange-dark: #C4611E;
    --navy: #1B2A2E;
    --cream: #F5F1E8;
    --white: #FFFFFF;
  }
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
    line-height:1.20;
    font-weight:800;
    letter-spacing:0.5px;
    margin:0 70px 0;
    text-shadow: 0 2px 24px rgba(0,0,0,0.35);
    word-break:break-word;
  }

  .hero .lede{
    color:var(--white);
    font-size:25px;
    font-weight:700px;
    max-width:640px;
    margin:0 70px 20px;
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

  :root{
    --orange: #E8792D;
    --orange-dark: #C4611E;
    --navy: #050505;
  }

  .services-intro{
    position:relative;
    background:#ffffff;
    font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
    padding:60px 30px 90px 60px;
  }

  .services-intro *{ box-sizing:border-box; }

  .services-intro-inner{
    position:relative;
    display:flex;
    align-items:flex-start;
    gap:64px;
    margin:0 auto;
  }

  .services-intro-left{
    flex:0 0 50%;
    max-width:50%;
  }

  .services-intro-eyebrow{
    color:var(--orange);
    font-weight:700;
    font-size:25px;
    margin:0 0 16px;
  }

  .services-intro-heading{
    font-size:55px;
    line-height:1.25;
    font-weight:600;
    color:#111111;
    margin:0;
  }

  .services-intro-divider{
    flex:0 0 1px;
    align-self:stretch;
    background:linear-gradient(to bottom, transparent, #d8d8d8 12%, #d8d8d8 88%, transparent);
  }

  .services-intro-right{
    flex:1;
    min-width:0;
    padding-top:6px;
  }

  .services-intro-desc{
    font-size:20px;
    color:#111111;
    margin:0;
    max-width:640px;
  }

  /* ===== scroll-reveal motion ===== */
  .reveal{
    opacity:0;
    transform:translateY(32px);
    transition:opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    will-change:opacity, transform;
  }
  .reveal.reveal-left{ transform:translateX(-40px); }
  .reveal.reveal-right{ transform:translateX(40px); transition-delay:0.15s; }
  .reveal.in-view{
    opacity:1;
    transform:translateY(0) translateX(0);
  }

  @media (prefers-reduced-motion: reduce){
    .reveal, .reveal.in-view{
      opacity:1 !important;
      transform:none !important;
      transition:none !important;
    }
  }

  /* ===== Tablet ===== */
  @media (max-width: 1024px){
    .services-intro{ padding:64px 40px 70px; }
    .services-intro-inner{ gap:44px; }
  }

  /* ===== Small tablet — stack columns, divider becomes horizontal ===== */
  @media (max-width: 900px){
    .services-intro{ padding:56px 24px 60px; }
    .services-intro-inner{
      flex-direction:column;
      gap:28px;
    }
    .services-intro-left,
    .services-intro-right{
      flex:none;
      max-width:none;
      width:100%;
    }
    .services-intro-divider{
      flex:none;
      align-self:auto;
      width:100%;
      height:1px;
      background:linear-gradient(to right, transparent, #d8d8d8 8%, #d8d8d8 92%, transparent);
    }
    .services-intro-right{ padding-top:0; }
    .services-intro-desc{ max-width:none; }
  }

  /* ===== Phones ===== */
  @media (max-width: 600px){
    .services-intro{ padding:46px 18px 50px; }
    .services-intro-eyebrow{ font-size:16px; margin-bottom:12px; }
    .services-intro-heading{ font-size:clamp(24px, 6.5vw, 30px); }
    .services-intro-desc{ font-size:15.5px; line-height:1.7; }
    .services-intro-inner{ gap:22px; }
  }

  /* ===== Very small phones ===== */
  @media (max-width: 380px){
    .services-intro{ padding:38px 14px 44px; }
    .services-intro-heading{ font-size:22px; }
    .services-intro-desc{ font-size:15px; }
  }

   .inspect{
    position:relative;
    background:#ffffff;
    font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
    padding:80px 60px 100px;
  }

  .inspect *{ box-sizing:border-box; }

  .inspect-header{
    display:flex;
    align-items:flex-start;
    gap:64px;
    margin-bottom:56px;
  }

  .inspect-header-left{
    flex:0 0 42%;
    max-width:42%;
  }

  .inspect-heading{
    font-size:clamp(30px, 3.8vw, 46px);
    line-height:1.15;
    font-weight:400;
    color:#111111;
    margin:0 0 16px;
  }

  .inspect-sub{
    font-size:17px;
    line-height:1.6;
    color:#4a4a4a;
    margin:0;
    max-width:420px;
  }

  .inspect-header-divider{
    flex:0 0 1px;
    align-self:stretch;
    background:linear-gradient(to bottom, transparent, #d8d8d8 12%, #d8d8d8 88%, transparent);
  }

  .inspect-header-right{
    flex:1;
    display:flex;
    align-items:center;
    justify-content:flex-end;
    padding-top:6px;
  }

  .inspect-count{
    font-size:16px;
    color:#333333;
  }
  .inspect-count strong{ color:#111111; font-weight:800; }

  /* ===== Grid ===== */
  .inspect-grid{
    display:grid;
    grid-template-columns:repeat(4, 1fr);
    gap:28px;
  }

  .inspect-card{
    background:#ffffff;
    border-radius:22px;
    border:1px solid #ececec;
    overflow:hidden;
    box-shadow:0 8px 22px rgba(0,0,0,0.05);
    transition:transform 0.4s cubic-bezier(0.16,1,0.3,1), box-shadow 0.4s ease;
    display:flex;
    flex-direction:column;
  }

  .inspect-card:hover{
    transform:translateY(-6px);
    box-shadow:0 20px 40px rgba(0,0,0,0.12);
  }

  .inspect-card-photo{
    width:100%;
    aspect-ratio: 4 / 3;
    background-color:#e8e8e8;
    background-position:center;
    background-size:cover;
    background-repeat:no-repeat;
    transition:transform 0.6s cubic-bezier(0.16,1,0.3,1);
    overflow:hidden;
  }

  .inspect-card:hover .inspect-card-photo{
    transform:scale(1.06);
  }

  .inspect-card-body{
    padding:22px 22px 24px;
    display:flex;
    flex-direction:column;
    flex:1;
  }

  .inspect-card-title{
    font-size:22px;
    line-height:1.28;
    font-weight:700;
    color:#111111;
    margin:0 0 12px;
  }

  .inspect-card-desc{
    font-size:14.5px;
    line-height:1.6;
    color:#5a5a5a;
    margin:0 0 18px;
    flex:1;
  }

  .inspect-card-tag{
    display:inline-block;
    background:#f0f0f0;
    color:#333333;
    font-size:13.5px;
    font-weight:600;
    border-radius:12px;
    padding:14px 16px;
  }

  /* ===== reveal motion ===== */
  .reveal{
    opacity:0;
    transform:translateY(32px);
    transition:opacity 0.7s cubic-bezier(0.16,1,0.3,1), transform 0.7s cubic-bezier(0.16,1,0.3,1);
    will-change:opacity, transform;
  }
  .reveal.in-view{ opacity:1; transform:translateY(0); }
  .reveal-delay-1{ transition-delay:0.08s; }
  .reveal-delay-2{ transition-delay:0.16s; }
  .reveal-delay-3{ transition-delay:0.24s; }

  @media (prefers-reduced-motion: reduce){
    .reveal, .reveal.in-view{ opacity:1 !important; transform:none !important; transition:none !important; }
    .inspect-card, .inspect-card-photo{ transition:none !important; }
  }

  /* ===== Pagination ===== */
  .inspect-pagination{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    margin-top:56px;
    padding-top:32px;
    border-top:1px solid #ececec;
  }

  .inspect-page-arrow{
    width:38px;
    height:38px;
    border-radius:50%;
    border:1px solid #dcdcdc;
    background:#ffffff;
    color:#555555;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:16px;
    text-decoration:none;
    cursor:pointer;
    transition:border-color 0.2s ease, color 0.2s ease, background 0.2s ease;
  }
  .inspect-page-arrow:hover{
    border-color:var(--orange);
    color:var(--orange);
  }
  .inspect-page-arrow.disabled{
    opacity:0.35;
    pointer-events:none;
  }

  .inspect-page-num{
    min-width:38px;
    height:38px;
    padding:0 6px;
    border-radius:10px;
    border:1px solid #dcdcdc;
    background:#ffffff;
    color:#333333;
    font-size:15px;
    font-weight:700;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    transition:background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
  }
  .inspect-page-num:hover{
    border-color:var(--orange);
    color:var(--orange);
  }
  .inspect-page-num.active{
    background:var(--orange);
    border-color:var(--orange);
    color:#ffffff;
  }

  /* ===== Tablet ===== */
  @media (max-width: 1100px){
    .inspect-grid{ grid-template-columns:repeat(3, 1fr); }
  }

  @media (max-width: 1024px){
    .inspect{ padding:64px 40px 80px; }
    .inspect-header{ gap:44px; margin-bottom:44px; }
  }

  /* ===== Small tablet — stack header, 2-col grid ===== */
  @media (max-width: 900px){
    .inspect{ padding:56px 24px 70px; }
    .inspect-header{ flex-direction:column; gap:22px; }
    .inspect-header-left,
    .inspect-header-right{ flex:none; max-width:none; width:100%; justify-content:flex-start; }
    .inspect-header-divider{
      flex:none; align-self:auto; width:100%; height:1px;
      background:linear-gradient(to right, transparent, #d8d8d8 8%, #d8d8d8 92%, transparent);
    }
    .inspect-grid{ grid-template-columns:repeat(2, 1fr); gap:22px; }
  }

  /* ===== Phones ===== */
  @media (max-width: 560px){
    .inspect{ padding:44px 16px 56px; }
    .inspect-heading{ font-size:clamp(24px, 7vw, 30px); }
    .inspect-sub{ font-size:15px; }
    .inspect-grid{ grid-template-columns:1fr; gap:20px; }
    .inspect-card-title{ font-size:19px; }
    .inspect-pagination{ margin-top:40px; gap:8px; flex-wrap:wrap; }
  }

  .presence{
  position:relative;
  background:#ffffff;
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:10px 60px 100px;
  overflow:hidden;
}

.presence *{ box-sizing:border-box; }

.presence-inner{
  position:relative;
  display:flex;
  align-items:center;
  gap:70px;
}

/* ===== Photo side (50%) ===== */
.presence-photo-wrap{
  position:relative;
  flex:0 0 50%;
  max-width:50%;
}


.presence-photo{
  position:relative;
  z-index:1;
  width:100%;
  height:450px;
  aspect-ratio: 4 / 3.4;
  border-radius:24px;
  overflow:hidden;
  box-shadow:0 26px 52px rgba(0,0,0,0.18);
  background-image:
    url('{{ asset('images/about.jpeg') }}');
  background-position:center;
  background-size:cover;
  background-repeat:no-repeat;
  transition:transform 0.5s cubic-bezier(0.16,1,0.3,1), box-shadow 0.5s ease;
}

.presence-photo-wrap:hover .presence-photo{
  transform:translateY(-8px);
  box-shadow:0 34px 64px rgba(0,0,0,0.24);
}
.presence-photo-wrap:hover::before{
  top:-14px;
  left:-14px;
}

.presence-badge{
  position:absolute;
  bottom:-26px;
  right:-20px;
  z-index:2;
  display:flex;
  align-items:center;
  gap:14px;
  background:#ffffff;
  border-radius:18px;
  padding:18px 24px;
  box-shadow:0 18px 36px rgba(0,0,0,0.16);
}

.presence-badge-icon{
  flex:0 0 auto;
  width:44px;
  height:44px;
  border-radius:50%;
  background:var(--orange);
  color:#ffffff;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:19px;
  font-weight:800;
}

.presence-badge-text strong{
  display:block;
  font-size:20px;
  font-weight:800;
  color:var(--navy);
  line-height:1.1;
}
.presence-badge-text span{
  display:block;
  font-size:11.5px;
  font-weight:700;
  letter-spacing:0.5px;
  text-transform:uppercase;
  color:#8a8a8a;
  margin-top:2px;
}

/* ===== Content side (50%) ===== */
.presence-content{
  flex:0 0 50%;
  max-width:50%;
  margin-left:50px;
}

.presence-eyebrow{
  color:#E8792D;
  font-weight:700;
  font-size:24px;
  margin:0 0 14px;
}

.presence-heading{
  font-size:48px;
  font-weight:600;
  color:#111111;
  margin:0 0 26px;
}

.presence-desc{
  font-size:20px;
  line-height:1.6;
  color:#333333;
  max-width:480px;
  margin:0 0 32px;
}

.presence-cta{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  padding:15px 30px;
  border-radius:30px;
  background:var(--orange);
  color:#ffffff;
  font-size:20px;
  font-weight:700;
  text-decoration:none;
  transition:background 0.2s ease, transform 0.15s ease;
}
.presence-cta:hover{
  background:var(--orange-dark);
  transform:translateY(-2px);
}

@media (max-width: 900px){
  .presence{ padding:56px 24px 64px; }
  .presence-inner{ flex-direction:column; gap:56px; }
  .presence-photo-wrap,
  .presence-content{ flex-basis:auto; max-width:none; width:100%; }
  .presence-photo-wrap::before{ width:60%; height:60%; }
  .presence-badge{ right:12px; bottom:-20px; padding:14px 18px; }
}

@media (max-width: 480px){
  .presence-photo-wrap::before{ display:none; }
  .presence-badge{ position:static; margin-top:16px; display:inline-flex; }
}
</style>

@php
  // Collect whichever banner images are populated into one array
  $heroImages = collect([$banner->image_1 ?? null, $banner->image_2 ?? null, $banner->image_3 ?? null])
      ->filter()
      ->values();

  // Fallback to a default static image if no banner images exist in DB
  if ($heroImages->isEmpty()) {
      $heroImages = collect(['images/hero_image.jpeg']);
  }
@endphp

<section class="hero">
@include('web.layout.navbar')

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

  <div class="rig-decor" aria-hidden="true"></div>
  <div class="hero-figure" aria-hidden="true"></div>

  <div class="hero-content">
    <div class="hero-inner">
    <h6 class="lede">{{ $banner->description ?? 'API And DS-1/NS-1 Certified Inspection' }}</h6>
      <h1>{{ $banner->title ?? 'Inspection Services for the Oil and' }}</h1>
      <h1>{{ $banner->title ?? 'Inspection Services for the Oil and Gas Industry' }}</h1>
    </div>
  </div>
</section>
<section class="services-intro">
  <div class="services-intro-inner">

    <div class="services-intro-left reveal reveal-left">
      <p class="services-intro-eyebrow">Our Services</p>
      <h1 class="services-intro-heading">Advanced Inspection for<br> Critical Equipment</h1>
    </div>

    <div class="services-intro-divider" aria-hidden="true"></div>

    <div class="services-intro-right reveal reveal-right">
      <p class="services-intro-desc">
        Energy Inspection Services provides reliable, standards-driven inspection and repair solutions for the Oil &amp; Gas industry, supporting clients in maintaining the safety, integrity, and performance of their critical equipment. From field and on-site inspections to fully equipped shop-based operations, our qualified and experienced professionals deliver comprehensive inspection services using modern non-destructive testing (NDT) technologies and established industry standards. With a strong focus on precision, safety, quality, and operational reliability, we help identify potential equipment issues, maintain compliance, and support our clients in achieving safer and more efficient Oil &amp; Gas operations.
      </p>
    </div>

  </div>
</section>

@php
  $services = [
    ['title' => 'Premium thread inspection', 'image' => 'images/services/premium-thread.jpg', 'description' => 'Full thread inspection across our premium connection range, run by inspectors qualified to a minimum of ASNT Level 2 across five NDT methods.', 'spec_tag' => 'ASNT Level 2 · EMI / MPI / Die-Pen / Eddy Current'],
    ['title' => 'Drill pipe inspection', 'image' => 'images/services/drill-pipe.jpg', 'description' => 'High-speed transverse flaw detection using three Techscope EZW-11 units with Hall-effect wall monitoring, calibrated on site for each pipe size.', 'spec_tag' => 'Techscope EZW-11 · 2 3/8"–6 5/8"'],
    ['title' => 'Ultrasonic flaw detection', 'image' => 'images/services/ultrasonic-flaw.jpg', 'description' => 'Digital ultrasonic inspection of drill pipe end areas using multi-probe shear wave heads for accurate, repeatable flaw detection.', 'spec_tag' => 'GE Krautkramer USN-60 · shear wave'],
    ['title' => 'Magnetic particle inspection', 'image' => 'images/services/magnetic-particle.jpg', 'description' => 'MPI of BHA assemblies, drilling tools and equipment, with dedicated magnetising and de-magnetising coils to keep field strength within spec.', 'spec_tag' => '7 black-light MPI units'],
    ['title' => 'Dye penetrant inspection', 'image' => 'images/services/dye-penetrant.jpg', 'description' => 'Die-penetrant testing for non-carbon steel components and equipment where magnetic methods can\'t be used.', 'spec_tag' => 'Non-ferrous & non-carbon steel'],
    ['title' => 'Tubing & casing inspection', 'image' => 'images/services/tubing-casing.jpg', 'description' => 'Full-length EMI inspection of tubing to 5" OD, alongside coupling and end-area electromagnetic inspection.', 'spec_tag' => 'Full length · up to 5" OD'],
    ['title' => 'Full length drifting', 'image' => 'images/services/full-length-drifting.jpg', 'description' => 'Full-length drifting of tubulars to all sizes and weights, using Teflon or steel drifts as specified by the operator.', 'spec_tag' => 'Teflon & steel drifts, all sizes'],
    ['title' => 'Wall thickness verification', 'image' => 'images/services/wall-thickness.jpg', 'description' => 'Point and full-body wall thickness checks to confirm tubulars remain within minimum service limits before returning to stock.', 'spec_tag' => 'Ultrasonic wall gauging'],
    ['title' => 'Hardbanding inspection', 'image' => 'images/services/hardbanding.jpg', 'description' => 'Visual and dimensional inspection of hardbanding condition on tool joints to confirm coverage and wear limits are within spec.', 'spec_tag' => 'Visual & dimensional'],
    ['title' => 'Slip & tong die inspection', 'image' => 'images/services/slip-tong.jpg', 'description' => 'Condition and dimensional checks on slip and tong dies to ensure grip integrity and prevent pipe damage during handling.', 'spec_tag' => 'Dimensional gauging'],
    ['title' => 'Visual & dimensional inspection', 'image' => 'images/services/visual-dimensional.jpg', 'description' => 'General visual and dimensional inspection of drilling and downhole equipment against OEM and API tolerances.', 'spec_tag' => 'API tolerance verification'],
    ['title' => 'BOP & pressure equipment inspection', 'image' => 'images/services/bop-pressure.jpg', 'description' => 'Inspection support for BOP components and pressure-control equipment to confirm condition ahead of recertification.', 'spec_tag' => 'Pressure equipment support'],
  ];

  $servicesPerPage = 8;
  $servicesTotal = count($services);
@endphp

<section class="inspect">

  <div class="inspect-header">
    <div class="inspect-header-left reveal">
      <h2 class="inspect-heading">What we inspect</h2>
      <p class="inspect-sub">Twelve core inspection and testing disciplines, run from fully equipped inspection sheds across our locations.</p>
    </div>

    <div class="inspect-header-divider"></div>

    <div class="inspect-header-right reveal reveal-delay-1">
      <p class="inspect-count" id="inspectCount">
        Showing <strong id="inspectCountFrom">1</strong>–<strong id="inspectCountTo">{{ min($servicesPerPage, $servicesTotal) }}</strong> of <strong>{{ $servicesTotal }}</strong> services
      </p>
    </div>
  </div>

  <div class="inspect-grid" id="inspectGrid">
    @foreach ($services as $index => $service)
      <div
        class="inspect-card reveal reveal-delay-{{ min($index % 4, 3) }}"
        data-page="{{ intdiv($index, $servicesPerPage) + 1 }}"
        style="{{ $index >= $servicesPerPage ? 'display:none;' : '' }}"
      >
        <div
          class="inspect-card-photo"
          style="background-image:url('{{ asset($service['image']) }}')"
          role="img"
          aria-label="{{ $service['title'] }}"
        ></div>
        <div class="inspect-card-body">
          <h3 class="inspect-card-title">{{ $service['title'] }}</h3>
          <p class="inspect-card-desc">{{ $service['description'] }}</p>
          @if(!empty($service['spec_tag']))
            <span class="inspect-card-tag">{{ $service['spec_tag'] }}</span>
          @endif
        </div>
      </div>
    @endforeach
  </div>

  @php $totalPages = (int) ceil($servicesTotal / $servicesPerPage); @endphp

  @if ($totalPages > 1)
    <nav class="inspect-pagination" id="inspectPagination" aria-label="Services pagination" data-total-pages="{{ $totalPages }}" data-per-page="{{ $servicesPerPage }}" data-total-items="{{ $servicesTotal }}">
      <button type="button" class="inspect-page-arrow disabled" id="inspectPrev" aria-label="Previous page">&#8249;</button>

      @for ($page = 1; $page <= $totalPages; $page++)
        <button
          type="button"
          class="inspect-page-num {{ $page === 1 ? 'active' : '' }}"
          data-page="{{ $page }}"
        >{{ $page }}</button>
      @endfor

      <button type="button" class="inspect-page-arrow {{ $totalPages <= 1 ? 'disabled' : '' }}" id="inspectNext" aria-label="Next page">&#8250;</button>
    </nav>
  @endif

</section>
<section class="presence">
  <div class="presence-inner">

    <div class="presence-photo-wrap reveal reveal-left">
      <div
        class="presence-photo"
        role="img"
        aria-label="EIS inspectors reviewing plans on site"
      ></div>
    </div>

    <div class="presence-content reveal reveal-right">
      <p class="presence-eyebrow">Regional Presence</p>
      <h1 class="presence-heading">Erbil &amp; Dubai</h1>
      <p class="presence-desc">EIS Ltd has offices in Erbil, Iraq and the Jebel Ali Free Zone in Dubai, providing access to oilfield services, machine shops, port facilities, storage and logistics operations.</p>
      <a href="{{ url('/contact') }}" class="presence-cta">Contact EIS</a>
    </div>

  </div>
</section>

@include('web.layout.footer')

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

  // ===== Scroll-reveal for ALL .reveal elements on this page =====
  document.addEventListener('DOMContentLoaded', function () {
    const revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window && revealEls.length) {
      const revealObserver = new IntersectionObserver(function (entries, obs) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('in-view');
            obs.unobserve(entry.target);
          }
        });
      }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });

      revealEls.forEach(function (el) { revealObserver.observe(el); });
    } else {
      revealEls.forEach(function (el) { el.classList.add('in-view'); });
    }
  });

  document.addEventListener('DOMContentLoaded', function () {
    const pagination = document.getElementById('inspectPagination');
    if (!pagination) return;

    const cards = Array.from(document.querySelectorAll('#inspectGrid .inspect-card'));
    const pageButtons = Array.from(pagination.querySelectorAll('.inspect-page-num'));
    const prevBtn = document.getElementById('inspectPrev');
    const nextBtn = document.getElementById('inspectNext');
    const countFrom = document.getElementById('inspectCountFrom');
    const countTo = document.getElementById('inspectCountTo');

    const totalPages = parseInt(pagination.dataset.totalPages, 10);
    const perPage = parseInt(pagination.dataset.perPage, 10);
    const totalItems = parseInt(pagination.dataset.totalItems, 10);
    let currentPage = 1;

    function showPage(page) {
      currentPage = page;

      cards.forEach(function (card) {
        const cardPage = parseInt(card.dataset.page, 10);
        const show = cardPage === page;
        card.style.display = show ? '' : 'none';
        if (show) {
          card.classList.remove('in-view');
          void card.offsetWidth;
          requestAnimationFrame(function () { card.classList.add('in-view'); });
        }
      });

      pageButtons.forEach(function (btn) {
        btn.classList.toggle('active', parseInt(btn.dataset.page, 10) === page);
      });

      prevBtn.classList.toggle('disabled', page === 1);
      nextBtn.classList.toggle('disabled', page === totalPages);

      const from = (page - 1) * perPage + 1;
      const to = Math.min(page * perPage, totalItems);
      countFrom.textContent = from;
      countTo.textContent = to;

      pagination.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    pageButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        showPage(parseInt(btn.dataset.page, 10));
      });
    });

    prevBtn.addEventListener('click', function () {
      if (currentPage > 1) showPage(currentPage - 1);
    });

    nextBtn.addEventListener('click', function () {
      if (currentPage < totalPages) showPage(currentPage + 1);
    });
  });
</script>