
<style>
  :root{
    --orange: #E8792D;
    --orange-dark: #C4611E;
    --navy: #1B2A2E;
    --cream: #F5F1E8;
    --white: #FFFFFF;
  }

  /* scroll-reveal motion (shared with home.blade.php) */
  .reveal{
    opacity:0;
    transform:translateY(36px);
    transition:opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    will-change:opacity, transform;
  }
  .reveal.reveal-left{ transform:translateX(-48px); }
  .reveal.reveal-right{ transform:translateX(48px); }
  .reveal.in-view{
    opacity:1;
    transform:translateY(0) translateX(0);
  }
  .reveal-delay-1{ transition-delay:0.12s; }
  .reveal-delay-2{ transition-delay:0.24s; }
  .reveal-delay-3{ transition-delay:0.36s; }

  @media (prefers-reduced-motion: reduce){
    .reveal, .reveal.in-view{
      opacity:1 !important;
      transform:none !important;
      transition:none !important;
    }
  }
</style>

@include('web.header')

<style>
  .about-intro{
    position:relative;
    background:#ffffff;
    font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
    padding:90px 60px 100px;
  }

  .about-intro *{ box-sizing:border-box; }

  .about-intro-inner{
    position:relative;
    display:flex;
    align-items:flex-start;
    gap:70px;
    margin:0 auto;
  }

  /* subtle vertical divider between the two columns */
  .about-intro-inner::before{
    content:"";
    position:absolute;
    top:0;
    bottom:0;
    left:30%;
    width:1px;
    background:#e6e6e6;
    pointer-events:none;
  }

  .about-intro-left{
    flex:0 0 30%;
  }

  .about-intro-eyebrow{
    color:var(--orange);
    font-weight:700;
    font-size:22px;
    margin:0 0 18px;
  }

  .about-intro-heading{
    font-size:clamp(28px, 3vw, 40px);
    line-height:1.25;
    font-weight:600;
    color:#111111;
    margin:0;
  }

  .about-intro-right{
    flex:1;
    min-width:0;
    padding-left:70px;
  }

  .about-intro-right p{
    font-size:18px;
    line-height:1.65;
    color:#333333;
    margin:0;
  }
  .about-intro-right p:last-child{ margin-top:20px; }

  @media (max-width: 900px){
    .about-intro{ padding:64px 24px 70px; }
    .about-intro-inner{ flex-direction:column; gap:36px; }
    .about-intro-inner::before{ display:none; }
    .about-intro-left{ flex-basis:auto; max-width:none; }
    .about-intro-right{ padding-left:0; }
  }

 .who-we-are{
  position:relative;
  background:#ffffff;
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:60px 60px 100px;
}

.who-we-are *{ box-sizing:border-box; }

.who-we-are-inner{
  position:relative;
  display:flex;
  align-items:flex-start;
  gap:70px;
  margin:0 auto;
}

/* subtle vertical divider between the two columns, matching .about-intro divider style */
.who-we-are-inner::before{
  content:"";
  position:absolute;
  top:0;
  bottom:0;
  left:40%;
  width:1px;
  background:#e6e6e6;
  pointer-events:none;
}

.who-we-are-left{
  flex:0 0 40%;
}

.who-we-are-heading{
  font-size:clamp(28px, 3vw, 40px);
  line-height:1.25;
  font-weight:700;
  color:#111111;
  margin:0 0 30px;
}

.who-we-are-left p{
  font-size:17px;
  line-height:1.65;
  color:#333333;
  margin:0 0 24px;
}
.who-we-are-left p:last-child{ margin-bottom:0; }

.who-we-are-photo{
  flex:1;
  min-width:0;
  height:450px;
  border-radius:10px;
  overflow:hidden;
  box-shadow:0 24px 48px rgba(0,0,0,0.16);
  transition:box-shadow 0.3s ease;
  background:
    linear-gradient(120deg, rgba(30,30,35,0.4), rgba(30,30,35,0.1) 55%, rgba(232,121,45,0.25)),
    url('{{ asset('images/who_we_are.jpeg') }}');
  background-position:center;
  background-size:cover;
}

.who-we-are-photo:hover{
  box-shadow:0 28px 56px rgba(0,0,0,0.22);
}

@media (max-width: 900px){
  .who-we-are{ padding:64px 24px 70px; }
  .who-we-are-inner{ flex-direction:column; gap:36px; }
  .who-we-are-inner::before{ display:none; }
  .who-we-are-left{ flex-basis:auto; max-width:none; }
  .who-we-are-photo{ height:auto; aspect-ratio:4/3; width:100%; }
}
.footprint{
  position:relative;
  background:#ffffff;
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:60px 60px 100px;
}

.footprint *{ box-sizing:border-box; }

.footprint-inner{
  margin:0 auto;
}

.footprint-top{ margin-bottom:50px; }

.footprint-eyebrow{
  color:var(--orange);
  font-weight:700;
  font-size:19px;
  margin:0 0 14px;
}

.footprint-heading{
  font-size:clamp(30px, 3.6vw, 46px);
  line-height:1.2;
  font-weight:400;
  color:#111111;
  margin:0;
}

.footprint-body{
  position:relative;
  display:flex;
  align-items:stretch;
  gap:50px;
}

.footprint-body::before{
  content:"";
  position:absolute;
  top:0;
  bottom:0;
  left:48%;
  width:1px;
  background:#e6e6e6;
  pointer-events:none;
}

.footprint-map{
  flex:0 0 48%;
  border-radius:26px;
  overflow:hidden;
  box-shadow:0 20px 44px rgba(0,0,0,0.12);
  transition:box-shadow 0.3s ease;
}

.footprint-map:hover{
  box-shadow:0 28px 56px rgba(0,0,0,0.2);
}

.footprint-map img{
  display:block;
  width:100%;
  height:100%;
  object-fit:cover;
  transition:transform 0.6s cubic-bezier(0.16,1,0.3,1);
}

.footprint-map:hover img{
  transform:scale(1.06);
}

.footprint-locations{
  flex:1;
  min-width:0;
  display:flex;
  flex-direction:column;
  gap:32px;
  padding-left:0;
}

.footprint-location{
  display:flex;
  align-items:center;
  gap:38px;
}

.footprint-location-photo{
  flex:0 0 320px;
  height:200px;
  border-radius:26px;
  overflow:hidden;
  box-shadow:0 14px 30px rgba(0,0,0,0.14);
  transition:box-shadow 0.3s ease, transform 0.3s ease;
}

.footprint-location-photo:hover{
  box-shadow:0 20px 40px rgba(232,121,45,0.22);
  transform:translateY(-4px);
}

.footprint-location-photo img{
  display:block;
  width:100%;
  height:100%;
  object-fit:cover;
  transition:transform 0.6s cubic-bezier(0.16,1,0.3,1);
}

.footprint-location-photo:hover img{
  transform:scale(1.1);
}

.footprint-location-text{ flex:1; min-width:0; }

.footprint-location-title{
  font-size:24px;
  font-weight:600;
  color:#111111;
  margin:0 0 8px;
}

.footprint-location-desc{
  font-size:16px;
  line-height:1.5;
  color:#4a4a4a;
  margin:0;
}
.footprint-location-desc strong{ color:#111111; font-weight:600; }

@media (max-width: 1100px){
  .footprint-body{ flex-direction:column; }
  .footprint-body::before{ display:none; }
  .footprint-map{ flex-basis:auto; width:100%; aspect-ratio:16/10; }
  .footprint-locations{ padding-left:0; }
}

@media (max-width: 700px){
  .footprint{ padding:64px 24px 70px; }
  .footprint-location{ flex-direction:column; align-items:flex-start; gap:16px; }
  .footprint-location-photo{ flex-basis:auto; width:100%; height:180px; }
}
.operation{
  position:relative;
  background:#ffffff;
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:60px 60px 100px;
}

.operation *{ box-sizing:border-box; }

.operation-inner{
  margin:0 auto;
}

.operation-top{ margin-bottom:40px; }

.operation-eyebrow{
  color:var(--orange);
  font-weight:700;
  font-size:19px;
  margin:0 0 14px;
}

.operation-heading{
  font-size:clamp(30px, 3.6vw, 46px);
  line-height:1.2;
  font-weight:400;
  color:#111111;
  margin:0;
}

/* Main video thumbnail */
.operation-video{
  position:relative;
  width:100%;
  aspect-ratio: 16 / 7.2;
  border-radius:20px;
  overflow:hidden;
  box-shadow:0 24px 48px rgba(0,0,0,0.18);
  cursor:pointer;
  margin-bottom:60px;
  transition:box-shadow 0.35s ease;
}

.operation-video:hover{
  box-shadow:0 32px 64px rgba(0,0,0,0.28);
}

.operation-video img{
  display:block;
  width:100%;
  height:100%;
  object-fit:cover;
  transition:transform 0.8s cubic-bezier(0.16,1,0.3,1);
}

.operation-video:hover img{
  transform:scale(1.08);
}

.operation-play{
  position:absolute;
  top:50%;
  left:50%;
  transform:translate(-50%, -50%) scale(1);
  width:78px;
  height:78px;
  border-radius:50%;
  background:rgba(255,255,255,0.95);
  display:flex;
  align-items:center;
  justify-content:center;
  box-shadow:0 12px 30px rgba(0,0,0,0.25);
  transition:transform 0.35s cubic-bezier(0.34,1.56,0.64,1), background 0.25s ease;
  z-index:2;
}

.operation-play::before{
  content:"";
  width:0;
  height:0;
  border-style:solid;
  border-width:12px 0 12px 20px;
  border-color:transparent transparent transparent var(--orange);
  margin-left:6px;
}

.operation-video:hover .operation-play{
  transform:translate(-50%, -50%) scale(1.12);
  background:var(--orange);
}
.operation-video:hover .operation-play::before{
  border-color:transparent transparent transparent var(--white);
}

/* Pulsing ring around the play button, always visible */
.operation-play-ring{
  position:absolute;
  top:50%;
  left:50%;
  transform:translate(-50%, -50%);
  width:78px;
  height:78px;
  border-radius:50%;
  border:2px solid rgba(255,255,255,0.7);
  animation: operationPulse 2.4s ease-out infinite;
  z-index:1;
}

@keyframes operationPulse{
  0%{ transform:translate(-50%, -50%) scale(1); opacity:0.8; }
  100%{ transform:translate(-50%, -50%) scale(1.9); opacity:0; }
}

/* Carousel */
.operation-carousel{
  position:relative;
  display:flex;
  align-items:center;
  gap:20px;
}

.operation-track-wrap{
  flex:1;
  overflow:hidden;
   max-width:900px;   /* NEW — constrains overall carousel width */
  margin:0 auto; 
}

.operation-track{
  display:flex;
  gap:28px;
  transition:transform 0.5s cubic-bezier(0.16,1,0.3,1);
}

.operation-card{
  position:relative;
  flex:0 0 calc((100% - 56px) / 3);
  aspect-ratio: 4 / 3;
  border-radius:18px;
  overflow:hidden;
  cursor:pointer;
  box-shadow:0 14px 34px rgba(0,0,0,0.14);
  transition:box-shadow 0.3s ease;
}

.operation-card:hover{
  box-shadow:0 22px 46px rgba(0,0,0,0.22);
}

.operation-card img{
  display:block;
  width:100%;
  height:100%;
  object-fit:cover;
  transition:transform 0.7s cubic-bezier(0.16,1,0.3,1);
}

.operation-card:hover img{
  transform:scale(1.12);
}

.operation-card-overlay{
  position:absolute;
  inset:0;
  display:flex;
  align-items:flex-end;
  padding:22px;
  background:linear-gradient(to top, rgba(20,20,20,0.85) 0%, rgba(20,20,20,0.15) 55%, transparent 100%);
  opacity:0;
  transform:translateY(10px);
  transition:opacity 0.35s ease, transform 0.35s ease;
}

.operation-card:hover .operation-card-overlay{
  opacity:1;
  transform:translateY(0);
}

.operation-card-title{
  color:var(--white);
  font-size:17px;
  font-weight:700;
  margin:0 0 6px;
}

.operation-card-desc{
  color:rgba(255,255,255,0.85);
  font-size:13.5px;
  line-height:1.45;
  margin:0;
}

.operation-nav{
  flex:0 0 auto;
  width:44px;
  height:44px;
  border-radius:50%;
  background:#ffffff;
  border:1px solid #e6e6e6;
  display:flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
  color:#111111;
  font-size:18px;
  transition:background 0.2s ease, color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
}

.operation-nav:hover{
  background:var(--orange);
  border-color:var(--orange);
  color:var(--white);
  transform:translateY(-2px);
}

.operation-nav:disabled{
  opacity:0.35;
  cursor:default;
  pointer-events:none;
}

@media (max-width: 900px){
  .operation{ padding:64px 24px 70px; }
  .operation-video{ aspect-ratio:4/3; margin-bottom:40px; }
  .operation-card{ flex:0 0 calc((100% - 28px) / 2); }
}

@media (max-width: 600px){
  .operation-card{ flex:0 0 100%; }
}

@media (prefers-reduced-motion: reduce){
  .operation-play-ring{ animation:none; }
  .operation-video img, .operation-card img{ transition:none; }
}

.capability{
  position:relative;
  background:#ffffff;
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:60px 60px 100px;
}

.capability *{ box-sizing:border-box; }

.capability-inner{
  margin:0 auto;
}

.capability-top{ margin-bottom:60px; }

.capability-eyebrow{
  color:var(--orange);
  font-weight:700;
  font-size:19px;
  margin:0 0 14px;
}

.capability-heading{
  font-size:clamp(30px, 3.6vw, 46px);
  line-height:1.2;
  font-weight:400;
  color:#111111;
  margin:0;
}

.capability-body{
  position:relative;
  display:flex;
  align-items:flex-start;
  gap:50px;
}

.capability-body::before{
  content:"";
  position:absolute;
  top:0;
  bottom:0;
  left:22%;
  width:1px;
  background:#e6e6e6;
  pointer-events:none;
}

/* Tabs list — plain style (no colored box) */
.capability-tabs{
  list-style:none;
  margin:0;
  padding:0;
  flex:0 0 22%;
  display:flex;
  flex-direction:column;
  gap:22px;
}

.capability-tab{
  display:flex;
  align-items:center;
  gap:10px;
  background:none;
  border:none;
  padding:0;
  font-family:inherit;
  font-size:16px;
  font-weight:600;
  letter-spacing:0.2px;
  color:#a3a3a3;
  cursor:pointer;
  text-align:left;
  transition:color 0.2s ease;
}

.capability-tab .arrow{
  color:var(--orange);
  font-size:16px;
  opacity:0.45;
  transition:opacity 0.2s ease, transform 0.2s ease;
}

.capability-tab:hover{ color:#555555; }
.capability-tab.active{ color:#111111; }
.capability-tab.active .arrow{ opacity:1; transform:translateX(3px); }

/* Content area: text + photo, sliding together */
.capability-content-wrap{
  position:relative;
  flex:1;
  display:flex;
  align-items:flex-start;
  gap:60px;
  padding-left:70px;
  overflow:hidden;
}

.capability-panel{
  display:none;
  align-items:flex-start;
  gap:60px;
  width:100%;
}

.capability-panel.is-active{ display:flex; }

.capability-panel.is-leaving{
  display:flex;
  position:absolute;
  top:0; left:70px;
  width:calc(100% - 70px);
  animation: capabilitySlideOutLeft 0.45s ease forwards;
}

.capability-panel.is-entering{
  animation: capabilitySlideInRight 0.45s ease forwards;
}

@keyframes capabilitySlideOutLeft{
  from{ transform:translateX(0); opacity:1; }
  to{ transform:translateX(-40px); opacity:0; }
}
@keyframes capabilitySlideInRight{
  from{ transform:translateX(40px); opacity:0; }
  to{ transform:translateX(0); opacity:1; }
}

.capability-panel-text{
  flex:1;
  min-width:0;
}

.capability-panel-desc{
  font-size:17px;
  line-height:1.65;
  color:#333333;
  margin:0;
}

.capability-panel-photo{
  flex:0 0 42%;
  max-width:480px;
  aspect-ratio: 5 / 3.4;
  border-radius:14px;
  overflow:hidden;
  box-shadow:0 20px 44px rgba(0,0,0,0.14);
  background: linear-gradient(165deg, #d9a441 0%, #b97a2e 35%, #5c4326 65%, #2a2016 100%);
}

.capability-panel-photo img{
  display:block;
  width:100%;
  height:100%;
  object-fit:cover;
}

@media (max-width: 1100px){
  .capability-body{ flex-direction:column; gap:36px; }
  .capability-body::before{ display:none; }
  .capability-tabs{
    flex-basis:auto;
    flex-direction:row;
    flex-wrap:wrap;
    gap:16px 28px;
  }
  .capability-content-wrap{ padding-left:0; overflow:visible; }
  .capability-panel{ flex-direction:column; gap:30px; }
  .capability-panel-photo{ max-width:none; width:100%; flex-basis:auto; }
}

@media (max-width: 900px){
  .capability{ padding:64px 24px 70px; }
}

.cta-support{
  position:relative;
  background:#ffffff;
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:60px 60px 90px;
}

.cta-support *{ box-sizing:border-box; }

.cta-support-inner{
  position:relative;
  display:flex;
  align-items:center;
  gap:70px;
  margin:0 auto;
}

.cta-support-photo{
  flex:0 0 50%;
  height:400px;
  aspect-ratio: 5 / 3.1;
  border-radius:10px;
  overflow:hidden;
  box-shadow:0 24px 48px rgba(0,0,0,0.16);
  transition:box-shadow 0.3s ease;
  background:
    linear-gradient(120deg, rgba(30,30,35,0.4), rgba(30,30,35,0.1) 55%, rgba(232,121,45,0.25)),
    url('{{ asset('images/cta_support.jpeg') }}');
  background-position:center;
  background-size:cover;
}

.cta-support-photo:hover{
  box-shadow:0 28px 56px rgba(0,0,0,0.22);
}

.cta-support-content{ flex:1; min-width:280px; }

.cta-support-eyebrow{
  color:var(--orange);
  font-weight:700;
  font-size:17px;
  margin:0 0 14px;
}

.cta-support-heading{
  font-size:clamp(28px, 3.4vw, 44px);
  line-height:1.15;
  font-weight:500;
  color:#111111;
  margin:0 0 26px;
}

.cta-support-desc{
  font-size:17px;
  line-height:1.6;
  color:#333333;
  margin:0 0 32px;
}

.cta-support-btn{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  padding:15px 30px;
  border-radius:30px;
  background:var(--orange);
  color:#ffffff;
  font-size:16px;
  font-weight:700;
  text-decoration:none;
  transition:background 0.2s ease, transform 0.15s ease;
}
.cta-support-btn:hover{
  background:var(--orange-dark);
  transform:translateY(-2px);
}

@media (max-width: 900px){
  .cta-support{ padding:56px 24px 64px; }
  .cta-support-inner{ flex-direction:column; gap:36px; }
  .cta-support-photo{ flex-basis:auto; width:100%; max-width:none; }
}
</style>

<section class="about-intro">
  <div class="about-intro-inner">
    <div class="about-intro-left reveal reveal-left">
      <p class="about-intro-eyebrow">About EIS</p>
      <h2 class="about-intro-heading">Experience built around integrity, safety and performance</h2>
    </div>

    <div class="about-intro-right reveal reveal-right reveal-delay-1">
      <p>Energy Inspection Services (EIS Ltd) is an Oil &amp; Gas inspection company with its headquarters and inspection operations in Erbil, Iraq. Our work is focused on helping energy operators and service companies maintain the integrity and reliability of critical equipment through professional inspection, non-destructive testing and repair support.</p>
      <p>With field and shop capabilities, qualified personnel and modern inspection equipment, EIS delivers practical solutions where they matter most — at the asset, at the facility and at the client's site.</p>
    </div>
  </div>
</section>

<section class="who-we-are">
  <div class="who-we-are-inner">
    <div class="who-we-are-left reveal reveal-left">
      <h2 class="who-we-are-heading">Who We Are</h2>
      <p>Energy Inspection Services (EIS Ltd) is a specialized Oil &amp; Gas inspection company headquartered in Erbil, Iraq, providing professional inspection and technical support services to energy operators, drilling contractors, and oilfield service companies across the region.</p>
      <p>Our work is focused on helping clients maintain the integrity, safety, and performance of critical equipment through comprehensive inspection, non-destructive testing (NDT), equipment assessment, and repair support. We combine industry knowledge, technical expertise, and established inspection practices to identify potential issues and support informed maintenance and operational decisions.</p>
      <p>With field and workshop capabilities, qualified personnel, modern inspection equipment, and a strong commitment to quality and safety, EIS delivers practical and dependable solutions wherever they are needed — at the asset, within our inspection facilities, or directly at the client's site.</p>
    </div>

    <div class="who-we-are-photo reveal reveal-right reveal-delay-1" role="img" aria-label="EIS inspectors reviewing plans on site"></div>
  </div>
</section>
<section class="footprint">
  <div class="footprint-inner">
    <div class="footprint-top reveal reveal-left">
      <p class="footprint-eyebrow">Our Regional Footprint</p>
      <h2 class="footprint-heading">Strategically positioned<br>across key energy markets</h2>
    </div>

    <div class="footprint-body">
      <div class="footprint-map reveal reveal-left reveal-delay-1">
        <img src="{{ asset('images/erbil_map.jpeg') }}" alt="Map showing EIS location in Erbil, Iraq">
      </div>

      <div class="footprint-locations">
        <div class="footprint-location reveal reveal-right reveal-delay-1">
          <div class="footprint-location-photo">
            <img src="{{ asset('images/erbil_facility.jpeg') }}" alt="EIS facility in Erbil, Iraq">
          </div>
          <div class="footprint-location-text">
            <h3 class="footprint-location-title">Erbil, Iraq</h3>
            <p class="footprint-location-desc">Headquarters &amp; inspection operations</p>
          </div>
        </div>

        <div class="footprint-location reveal reveal-right reveal-delay-2">
          <div class="footprint-location-photo">
            <img src="{{ asset('images/dubai_facility.jpeg') }}" alt="EIS facility in Dubai, UAE">
          </div>
          <div class="footprint-location-text">
            <h3 class="footprint-location-title">Dubai, UAE</h3>
            <p class="footprint-location-desc">Regional operations &amp; client support</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@php
  $operationClips = [
      [
          'image' => 'operation1.jpeg',
          'title' => 'Smart Monitoring',
          'desc' => 'Digital tracking and real-time data supporting every inspection.',
      ],
      [
          'image' => 'operation2.jpeg',
          'title' => 'Offshore Platforms',
          'desc' => 'Field inspections carried out on live offshore installations.',
      ],
      [
          'image' => 'operation3.jpeg',
          'title' => 'Field Technicians',
          'desc' => 'Qualified inspectors working directly on client sites.',
      ],
  ];
@endphp

<section class="operation">
  <div class="operation-inner">
    <div class="operation-top reveal reveal-left">
      <p class="operation-eyebrow">See The Operation</p>
      <h2 class="operation-heading">Inside our inspection process</h2>
    </div>

    <div class="operation-video reveal reveal-delay-1" id="operationVideo">
      <img src="{{ asset('images/operation_main.jpeg') }}" alt="Inside our inspection process">
      <div class="operation-play-ring"></div>
      <div class="operation-play" aria-label="Play video"></div>
    </div>

    <div class="operation-carousel reveal reveal-delay-2">
      <button type="button" class="operation-nav" id="operationPrev" aria-label="Previous">&#10094;</button>

      <div class="operation-track-wrap">
        <div class="operation-track" id="operationTrack">
          @foreach ($operationClips as $clip)
            <div class="operation-card">
              <img src="{{ asset('images/' . $clip['image']) }}" alt="{{ $clip['title'] }}">
              <div class="operation-card-overlay">
                <div>
                  <p class="operation-card-title">{{ $clip['title'] }}</p>
                  <p class="operation-card-desc">{{ $clip['desc'] }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <button type="button" class="operation-nav" id="operationNext" aria-label="Next">&#10095;</button>
    </div>
  </div>
</section>

@php
  $capabilityItems = [
      [
          'number' => '01',
          'label' => 'Oil & Gas Inspection',
          'desc' => 'Specialized inspection services for critical equipment, tools, and components used throughout Oil & Gas operations. Our experienced inspection team applies proven inspection methods and industry practices to assess equipment condition, identify potential defects, wear, or damage, and support the safe and reliable performance of essential assets. From routine inspections to detailed equipment assessments, EIS provides practical solutions that help clients improve equipment integrity, operational safety, reliability, and service life.',
      ],
      [
          'number' => '02',
          'label' => 'Non-Destructive Testing',
          'desc' => 'Modern non-destructive testing methods including magnetic particle, dye penetrant, and ultrasonic thickness testing, carried out by API and ASNT Level 2 qualified personnel to identify surface and subsurface defects without compromising equipment integrity.',
      ],
      [
          'number' => '03',
          'label' => 'Traceable Reporting',
          'desc' => 'Every inspection is backed by clear, defensible documentation, giving clients a traceable record of findings, measurements, and recommendations that supports compliance and long-term asset history.',
      ],
      [
          'number' => '04',
          'label' => 'Repair Support',
          'desc' => 'Equipped inspection sheds and repair facilities mean issues identified during inspection can often be addressed on the spot, reducing downtime and keeping operations moving.',
      ],
      [
          'number' => '05',
          'label' => 'Quality & Compliance',
          'desc' => 'Inspections are carried out to established industry standards and procedures, ensuring consistent, defensible results that meet client and regulatory compliance requirements across every job.',
      ],
      [
          'number' => '06',
          'label' => 'Regional Support',
          'desc' => 'With offices in Erbil, Iraq and Dubai, UAE, EIS provides responsive field, on-site, and shop-based support across key energy markets in the region.',
      ],
  ];
@endphp

<section class="capability">
  <div class="capability-inner">
    <div class="capability-top reveal reveal-left">
      <p class="capability-eyebrow">What Sets Us Apart</p>
      <h2 class="capability-heading">Technical capability with<br>a safety-first mindset.</h2>
    </div>

    <div class="capability-body">
      <ul class="capability-tabs" id="capabilityTabs">
        @foreach ($capabilityItems as $index => $item)
          <li>
            <button type="button" class="capability-tab{{ $index === 0 ? ' active' : '' }}" data-capability-index="{{ $index }}">
              {{ $item['number'] }} / {{ $item['label'] }} <span class="arrow">&#8594;</span>
            </button>
          </li>
        @endforeach
      </ul>

      <div class="capability-content-wrap" id="capabilityPanels">
        @foreach ($capabilityItems as $index => $item)
          <div class="capability-panel{{ $index === 0 ? ' is-active' : '' }}" data-capability-index="{{ $index }}">
            <div class="capability-panel-text">
              <p class="capability-panel-desc">{{ $item['desc'] }}</p>
            </div>
            <div class="capability-panel-photo">
              <img src="{{ asset('images/capability/' . $item['number'] . '.jpeg') }}" alt="{{ $item['label'] }}">
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
<section class="cta-support">
  <div class="cta-support-inner">
    <div class="cta-support-photo reveal reveal-left" role="img" aria-label="EIS inspectors reviewing plans on site"></div>

    <div class="cta-support-content reveal reveal-right reveal-delay-1">
      <p class="cta-support-eyebrow">Work With EIS</p>
      <h2 class="cta-support-heading">Need reliable<br>inspection support?</h2>
      <p class="cta-support-desc">Specialized inspection services for critical Oil &amp; Gas equipment and components. Our experienced team uses proven inspection methods to identify defects, wear, and damage, helping clients maintain asset integrity, safety, reliability, and operational efficiency.</p>
      <a href="{{ url('/contact') }}" class="cta-support-btn">Request Information</a>
    </div>
  </div>
</section>

@include('web.layout.footer')

<script>
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
      }, { threshold: 0.2, rootMargin: '0px 0px -60px 0px' });

      revealEls.forEach(function (el) { revealObserver.observe(el); });
    } else {
      revealEls.forEach(function (el) { el.classList.add('in-view'); });
    }
  });

  document.addEventListener('DOMContentLoaded', function () {
  const track = document.getElementById('operationTrack');
  const prevBtn = document.getElementById('operationPrev');
  const nextBtn = document.getElementById('operationNext');

  if (track && prevBtn && nextBtn) {
    const cards = track.children;
    let visibleCount = 3;
    let index = 0;

    function getVisibleCount() {
      if (window.innerWidth <= 600) return 1;
      if (window.innerWidth <= 900) return 2;
      return 3;
    }

    function update() {
      visibleCount = getVisibleCount();
      const maxIndex = Math.max(0, cards.length - visibleCount);
      index = Math.min(index, maxIndex);
      const cardWidth = cards[0].getBoundingClientRect().width;
      const gap = 28;
      track.style.transform = 'translateX(' + (-(cardWidth + gap) * index) + 'px)';
      prevBtn.disabled = index === 0;
      nextBtn.disabled = index >= maxIndex;
    }

    prevBtn.addEventListener('click', function () {
      index = Math.max(0, index - 1);
      update();
    });

    nextBtn.addEventListener('click', function () {
      const maxIndex = Math.max(0, cards.length - visibleCount);
      index = Math.min(maxIndex, index + 1);
      update();
    });

    window.addEventListener('resize', update);
    update();
  }

  const operationVideo = document.getElementById('operationVideo');
  if (operationVideo) {
    operationVideo.addEventListener('click', function () {
      // Hook up your actual video/modal logic here
      console.log('Play video clicked');
    });
  }
});

const capabilityTabs = document.querySelectorAll('#capabilityTabs .capability-tab');
let capabilityAnimating = false;

capabilityTabs.forEach(function (tab) {
  tab.addEventListener('click', function () {
    if (capabilityAnimating) return;
    const index = tab.getAttribute('data-capability-index');
    const currentPanel = document.querySelector('#capabilityPanels .capability-panel.is-active');
    const nextPanel = document.querySelector('#capabilityPanels .capability-panel[data-capability-index="' + index + '"]');
    if (!nextPanel || nextPanel === currentPanel) return;

    capabilityAnimating = true;
    capabilityTabs.forEach(function (t) { t.classList.remove('active'); });
    tab.classList.add('active');

    if (currentPanel) {
      currentPanel.classList.remove('is-active');
      currentPanel.classList.add('is-leaving');
    }
    nextPanel.classList.add('is-active', 'is-entering');

    setTimeout(function () {
      if (currentPanel) currentPanel.classList.remove('is-leaving');
      nextPanel.classList.remove('is-entering');
      capabilityAnimating = false;
    }, 450);
  });
});
</script>