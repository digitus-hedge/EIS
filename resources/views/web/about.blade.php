@extends('web.layout.app')

@section('content')
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
    align-items:flex-end;
    justify-content:flex-start;
    padding:0 90px 90px;
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
    .hero .hero-content{ padding:0 50px 70px; }
  }

  /* ===== Small tablet / large phone ===== */
   @media (max-width: 900px){
    .hero .hero-content{ padding:0 24px 56px; }
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
.footprint-inner{ margin:0 auto; }
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

.footprint-map{
  flex:0 0 48%;
  aspect-ratio: 4 / 2;   /* was 4 / 3.4 — lower second number = shorter map */
  border-radius:26px;
  overflow:hidden;
  box-shadow:0 20px 44px rgba(0,0,0,0.12);
  transition:box-shadow 0.3s ease;
}
.footprint-map:hover{ box-shadow:0 28px 56px rgba(0,0,0,0.2); }
#footprintMap{ width:100%; height:100%; }

/* Red pin marker */
.footprint-pin{
  width:30px; height:42px;
  display:flex; align-items:center; justify-content:center;
  cursor:pointer;
  filter: drop-shadow(0 6px 10px rgba(0,0,0,0.35));
  transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1);
}
.footprint-pin.is-active{ transform: scale(1.25) translateY(-3px); }
.footprint-pin svg{ display:block; }

.footprint-locations{
  flex:1;
  min-width:0;
  position:relative;
  min-height:480px;
}

.footprint-locations::-webkit-scrollbar{ width:6px; }
.footprint-locations::-webkit-scrollbar-track{ background:#f0f0f0; border-radius:10px; }
.footprint-locations::-webkit-scrollbar-thumb{ background:var(--orange); border-radius:10px; }

.footprint-location-panel{
  display:none;
  flex-direction:column;
  gap:28px;
}
.footprint-location-panel.is-active{ display:flex; }
.footprint-location-panel.is-leaving{
  display:flex;
  position:absolute;
  top:0; left:0; width:100%;
  animation: footprintSlideOutLeft 0.4s ease forwards;
}
.footprint-location-panel.is-entering{
  animation: footprintSlideInRight 0.4s ease forwards;
}
@keyframes footprintSlideOutLeft{
  from{ transform:translateX(0); opacity:1; }
  to{ transform:translateX(-30px); opacity:0; }
}
@keyframes footprintSlideInRight{
  from{ transform:translateX(30px); opacity:0; }
  to{ transform:translateX(0); opacity:1; }
}

.footprint-panel-title{
  font-size:20px;
  font-weight:700;
  color:var(--orange);
  margin:0;
}
.footprint-offices-viewport{
  overflow:hidden;
}

.footprint-offices-track{
  display:flex;
  transition:transform 0.5s cubic-bezier(0.16,1,0.3,1);
}

.footprint-office-page{
  flex:0 0 100%;
  display:flex;
  flex-direction:column;
  gap:30px;
  min-height:460px; /* keeps 2-office pages and 1-office pages the same height */
}

.footprint-dots{
  display:flex;
  gap:9px;
  margin-top:20px;
}

.footprint-dot{
  width:9px;
  height:9px;
  border-radius:50%;
  background:#e0e0e0;
  border:none;
  padding:0;
  cursor:pointer;
  transition:background 0.2s ease, transform 0.2s ease;
}

.footprint-dot.active{
  background:var(--orange);
  transform:scale(1.25);
}
.footprint-location{
  display:flex;
  align-items:center;
  gap:38px;
}
.footprint-location-photo{
  flex:0 0 320px;
  height:220px;   /* was 200px */
  border-radius:26px;
  overflow:hidden;
  background:#f2f2f2;
  box-shadow:0 14px 30px rgba(0,0,0,0.14);
  transition:box-shadow 0.3s ease, transform 0.3s ease;
}
.footprint-location-photo:hover{ box-shadow:0 20px 40px rgba(232,121,45,0.22); transform:translateY(-4px); }
.footprint-location-photo img{ display:block; width:100%; height:100%; object-fit:cover; transition:transform 0.6s cubic-bezier(0.16,1,0.3,1); }
.footprint-location-photo:hover img{ transform:scale(1.1); }

.footprint-location-text{ flex:1; min-width:0; }
.footprint-location-title{ font-size:22px; font-weight:600; color:#111111; margin:0 0 8px; }
.footprint-location-desc{ font-size:16px; line-height:1.5; color:#4a4a4a; margin:0; }

@media (max-width: 1100px){
  .footprint-body{ flex-direction:column; }
  .footprint-body::before{ display:none; }
  .footprint-map{ flex-basis:auto; width:100%; aspect-ratio:16/9; }  /* was 16/10 */
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
  padding:20px 60px 100px;
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
.operation-card-play{
  position:absolute;
  top:50%;
  left:50%;
  transform:translate(-50%, -50%) scale(1);
  width:48px;
  height:48px;
  border-radius:50%;
  background:rgba(255,255,255,0.95);
  display:flex;
  align-items:center;
  justify-content:center;
  box-shadow:0 8px 20px rgba(0,0,0,0.25);
  transition:transform 0.3s cubic-bezier(0.34,1.56,0.64,1), background 0.2s ease;
  z-index:2;
}

.operation-card-play::before{
  content:"";
  width:0;
  height:0;
  border-style:solid;
  border-width:8px 0 8px 13px;
  border-color:transparent transparent transparent var(--orange);
  margin-left:4px;
}

.operation-card:hover .operation-card-play{
  transform:translate(-50%, -50%) scale(1.12);
  background:var(--orange);
}
.operation-card:hover .operation-card-play::before{
  border-color:transparent transparent transparent var(--white);
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
.operation-video-overlay{
  position:absolute;
  inset:0;
  display:flex;
  align-items:flex-end;
  padding:30px;
  background:linear-gradient(to top, rgba(20,20,20,0.85) 0%, rgba(20,20,20,0.15) 55%, transparent 100%);
  opacity:0;
  transform:translateY(10px);
  transition:opacity 0.35s ease, transform 0.35s ease;
  z-index:3;
  pointer-events:none;
}

.operation-video:hover .operation-video-overlay{
  opacity:1;
  transform:translateY(0);
}

.operation-video-title{
  color:var(--white);
  font-size:22px;
  font-weight:700;
  margin:0 0 8px;
}

.operation-video-desc{
  color:rgba(255,255,255,0.9);
  font-size:15px;
  line-height:1.5;
  margin:0;
  max-width:600px;
}
.operation-video.is-playing .operation-play,
.operation-video.is-playing .operation-play-ring,
.operation-video.is-playing .operation-video-overlay{
  display:none;
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
  align-items:flex-end;
  gap:10px;
  background:none;
  border:none;
  padding:0;
  font-family:inherit;
  font-size:20px;
  font-weight:600;
  letter-spacing:0.2px;
  color:#a3a3a3;
  cursor:pointer;
  text-align:left;
  transition:color 0.2s ease;
}

.capability-tab .label-text{
  flex:0 1 auto;
  min-width:0;
}

.capability-tab .arrow{
  flex-shrink:0;
  color:var(--orange);
  font-size:28px;
  font-weight:700;
  line-height:1;
  opacity:0.65;
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
  min-height:400px;
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
<section class="hero">
@include('web.layout.navbar')

  @if ($about && $about->banner)
    <div class="hero-slides">
      <img
        src="{{ asset('storage/' . $about->banner) }}"
        class="hero-slide active"
        alt="{{ $about->title ?? 'Banner image' }}"
      >
    </div>
  @endif

  <div class="rig-decor" aria-hidden="true"></div>
  <div class="hero-figure" aria-hidden="true"></div>

  <div class="hero-content">
    <div class="hero-inner">
      <p class="eyebrow">Quality. Safety. Reliability.</p>
      <h1>{{ $about->title ?? '' }}</h1>
    </div>
  </div>
</section>
<section class="about-intro">
  <div class="about-intro-inner">
    <div class="about-intro-left reveal reveal-left">
      <p class="about-intro-eyebrow">About EIS</p>
      <h2 class="about-intro-heading">{{ $about->about_title ?? '' }}</h2>
    </div>

    <div class="about-intro-right reveal reveal-right reveal-delay-1">
      <p>{{ $about->about_desc ?? '' }}</p>
    </div>
  </div>
</section>

<section class="who-we-are">
  <div class="who-we-are-inner">
    <div class="who-we-are-left reveal reveal-left">
      <h2 class="who-we-are-heading">Who We Are</h2>
      <p>{{ $about->who_we_are_desc ?? '' }}</p>
    </div>

    <div class="who-we-are-photo reveal reveal-right reveal-delay-1"
         style="background-image: linear-gradient(120deg, rgba(30,30,35,0.4), rgba(30,30,35,0.1) 55%, rgba(232,121,45,0.25)), url('{{ $about && $about->image ? asset('storage/' . $about->image) : '' }}');"
         role="img" aria-label="EIS inspectors reviewing plans on site"></div>
  </div>
</section>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<section class="footprint">
  <div class="footprint-inner">
    <div class="footprint-top reveal reveal-left">
      <p class="footprint-eyebrow">Our Regional Footprint</p>
      <h2 class="footprint-heading">Strategically positioned<br>across key energy markets</h2>
    </div>

    <div class="footprint-body">
      <div class="footprint-map reveal reveal-left reveal-delay-1">
        <div id="footprintMap"></div>
      </div>

      <div class="footprint-locations" id="footprintLocations">
  @forelse ($locations as $index => $location)
    @php $officeChunks = $location->offices->chunk(2)->values(); @endphp
    <div class="footprint-location-panel{{ $index === 0 ? ' is-active' : '' }}" data-location-id="{{ $location->id }}">
      <h3 class="footprint-panel-title">{{ $location->title }}</h3>

      <div class="footprint-offices-viewport">
        <div class="footprint-offices-track" data-office-track>
          @foreach ($officeChunks as $chunk)
            <div class="footprint-office-page">
              @foreach ($chunk as $office)
                <div class="footprint-location reveal reveal-right">
                  <div class="footprint-location-photo">
                    @if ($office->image)
                      <img src="{{ asset('storage/' . $office->image) }}" alt="{{ $office->title }}">
                    @endif
                  </div>
                  <div class="footprint-location-text">
                    <h4 class="footprint-location-title">{{ $office->title }}</h4>
                    <p class="footprint-location-desc">{{ $office->description }}</p>
                  </div>
                </div>
              @endforeach
            </div>
          @endforeach
        </div>
      </div>

      @if ($officeChunks->count() > 1)
        <div class="footprint-dots" data-office-dots>
          @foreach ($officeChunks as $i => $chunk)
            <button type="button" class="footprint-dot{{ $i === 0 ? ' active' : '' }}" data-page-index="{{ $i }}" aria-label="Show offices page {{ $i + 1 }}"></button>
          @endforeach
        </div>
      @endif
    </div>
  @empty
    <p style="color:#888;">No locations added yet.</p>
  @endforelse
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

    @if ($mainVideo)
      <div class="operation-video reveal reveal-delay-1"
           id="operationVideo"
           data-video-url="{{ $mainVideo->video ? asset('storage/' . $mainVideo->video) : '' }}"
           data-thumbnail-url="{{ $mainVideo->thumbnail ? asset('storage/' . $mainVideo->thumbnail) : '' }}"
           data-title="{{ $mainVideo->title }}"
           data-desc="{{ $mainVideo->description }}">
        @if ($mainVideo->thumbnail)
          <img src="{{ asset('storage/' . $mainVideo->thumbnail) }}" alt="{{ $mainVideo->title }}">
        @endif
        <div class="operation-play-ring"></div>
        <div class="operation-play" aria-label="Play video"></div>
        <div class="operation-video-overlay">
          <div>
            <p class="operation-video-title">{{ $mainVideo->title }}</p>
            <p class="operation-video-desc">{{ $mainVideo->description }}</p>
          </div>
        </div>
      </div>
    @endif

    @if ($operationVideos->count())
      <div class="operation-carousel reveal reveal-delay-2">
        <button type="button" class="operation-nav" id="operationPrev" aria-label="Previous">&#10094;</button>

        <div class="operation-track-wrap">
          <div class="operation-track" id="operationTrack">
            @foreach ($operationVideos as $clip)
              <div class="operation-card"
            data-video-url="{{ $clip->video ? asset('storage/' . $clip->video) : '' }}"
            data-thumbnail-url="{{ $clip->thumbnail ? asset('storage/' . $clip->thumbnail) : '' }}"
            data-title="{{ $clip->title }}"
            data-desc="{{ $clip->description }}">
          @if ($clip->thumbnail)
            <img src="{{ asset('storage/' . $clip->thumbnail) }}" alt="{{ $clip->title }}">
          @endif
          <div class="operation-card-play"></div>
          <div class="operation-card-overlay">
            <div>
              <p class="operation-card-title">{{ $clip->title }}</p>
              <p class="operation-card-desc">{{ $clip->description }}</p>
            </div>
          </div>
        </div>
            @endforeach
          </div>
        </div>

        <button type="button" class="operation-nav" id="operationNext" aria-label="Next">&#10095;</button>
      </div>
    @endif
  </div>
</section>

<section class="capability">
  <div class="capability-inner">
    <div class="capability-top reveal reveal-left">
      <p class="capability-eyebrow">Why Choose Us</p>
      <h2 class="capability-heading">Technical capability with<br>a safety-first mindset.</h2>
    </div>

   @if (!empty($capabilityItems))
  <div class="capability-body">
    <ul class="capability-tabs" id="capabilityTabs">
      @foreach ($capabilityItems as $index => $item)
        <li>
          <button type="button" class="capability-tab{{ $index === 0 ? ' active' : '' }}" data-capability-index="{{ $index }}">
  <span class="label-text">{{ sprintf('%02d', $index + 1) }} / {{ $item['subheading'] ?? '' }}</span>
  <span class="arrow">&#8594;</span>
</button>
        </li>
      @endforeach
    </ul>

    <div class="capability-content-wrap" id="capabilityPanels">
      @foreach ($capabilityItems as $index => $item)
        <div class="capability-panel{{ $index === 0 ? ' is-active' : '' }}" data-capability-index="{{ $index }}">
          <div class="capability-panel-text">
            <p class="capability-panel-desc">{{ $item['description'] ?? '' }}</p>
          </div>
          <div class="capability-panel-photo">
            @if (!empty($item['image']))
              <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['subheading'] ?? '' }}">
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endif
  </div>
</section>
<section class="cta-support">
  <div class="cta-support-inner">
    <div class="cta-support-photo reveal reveal-left"
     style="background-image: linear-gradient(120deg, rgba(30,30,35,0.4), rgba(30,30,35,0.1) 55%, rgba(232,121,45,0.25)), url('{{ $about && $about->image ? asset('storage/' . $about->image) : asset('images/cta_support.jpeg') }}');"
     role="img" aria-label="EIS inspectors reviewing plans on site"></div>

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

  function playInPlace(container) {
  const videoUrl = container.getAttribute('data-video-url');
  if (!videoUrl) return;

  const videoEl = document.createElement('video');
  videoEl.src = videoUrl;
  videoEl.controls = true;
  videoEl.autoplay = true;
  videoEl.style.width = '100%';
  videoEl.style.height = '100%';
  videoEl.style.objectFit = 'cover';
  videoEl.style.display = 'block';

  container.innerHTML = '';
  container.appendChild(videoEl);
}

const operationVideo = document.getElementById('operationVideo');

function loadMainVideo(source) {
  if (!operationVideo) return;

  const videoUrl = source.getAttribute('data-video-url');
  const thumbUrl = source.getAttribute('data-thumbnail-url');
  const title = source.getAttribute('data-title') || '';
  const desc = source.getAttribute('data-desc') || '';

  const titleEl = operationVideo.querySelector('.operation-video-title');
  const descEl = operationVideo.querySelector('.operation-video-desc');
  if (titleEl) titleEl.textContent = title;
  if (descEl) descEl.textContent = desc;
  operationVideo.setAttribute('data-video-url', videoUrl || '');
  operationVideo.setAttribute('data-thumbnail-url', thumbUrl || '');
  operationVideo.setAttribute('data-title', title);
  operationVideo.setAttribute('data-desc', desc);

  if (!videoUrl) return;

  const videoEl = document.createElement('video');
  videoEl.src = videoUrl;
  videoEl.controls = true;
  videoEl.autoplay = true;
  videoEl.style.position = 'absolute';
  videoEl.style.inset = '0';
  videoEl.style.width = '100%';
  videoEl.style.height = '100%';
  videoEl.style.objectFit = 'cover';
  videoEl.style.zIndex = '1';

  const existingVideo = operationVideo.querySelector('video');
  if (existingVideo) existingVideo.remove();

  operationVideo.prepend(videoEl);
  operationVideo.classList.add('is-playing');   // NEW — hides the play button

  videoEl.addEventListener('ended', function () {
    operationVideo.classList.remove('is-playing');   // NEW — show play button again once it finishes
  });

  operationVideo.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

if (operationVideo) {
  operationVideo.addEventListener('click', function () {
    loadMainVideo(operationVideo);
  });
}

document.querySelectorAll('.operation-card').forEach(function (card) {
  card.addEventListener('click', function () {
    loadMainVideo(card);
  });
});
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
@php
  $footprintMapData = $locations->map(function ($loc) {
      return [
          'id'  => $loc->id,
          'lat' => (float) $loc->latitude,
          'lng' => (float) $loc->longitude,
      ];
  });
@endphp

<script>
document.addEventListener('DOMContentLoaded', function () {
  const mapEl = document.getElementById('footprintMap');
  const locationsEl = document.getElementById('footprintLocations');

  const locations = @json($footprintMapData);
  if (!mapEl || !locations.length) return;

  const map = L.map('footprintMap', { scrollWheelZoom: false });

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors',
      maxZoom: 19,
  }).addTo(map);

  function initOfficeCarousels() {
    document.querySelectorAll('.footprint-location-panel').forEach(function (panel) {
        const track = panel.querySelector('[data-office-track]');
        const dotsWrap = panel.querySelector('[data-office-dots]');
        if (!track) return;

        const pages = track.children.length;
        if (pages <= 1) return;

        let index = 0;
        let interval = null;

        function goTo(i) {
            index = (i + pages) % pages;
            track.style.transform = 'translateX(-' + (index * 100) + '%)';
            if (dotsWrap) {
                dotsWrap.querySelectorAll('.footprint-dot').forEach(function (dot, di) {
                    dot.classList.toggle('active', di === index);
                });
            }
        }

        function start() {
            stop();
            interval = setInterval(function () { goTo(index + 1); }, 4000);
        }

        function stop() {
            if (interval) clearInterval(interval);
            interval = null;
        }

        if (dotsWrap) {
            dotsWrap.querySelectorAll('.footprint-dot').forEach(function (dot) {
                dot.addEventListener('click', function () {
                    goTo(parseInt(dot.getAttribute('data-page-index'), 10));
                    start(); // restart the timer after a manual click
                });
            });
        }

        panel._carouselStart = start;
        panel._carouselStop = stop;
    });

    // Start auto-sliding for whichever panel is active on page load
    document.querySelectorAll('.footprint-location-panel.is-active').forEach(function (p) {
        if (p._carouselStart) p._carouselStart();
    });
}

initOfficeCarousels();

  function pinIcon(active) {
      return L.divIcon({
          className: '',
          html: '<div class="footprint-pin' + (active ? ' is-active' : '') + '">' +
              '<svg width="30" height="42" viewBox="0 0 30 42" xmlns="http://www.w3.org/2000/svg">' +
              '<path d="M15 0C6.7 0 0 6.7 0 15c0 11.25 15 27 15 27s15-15.75 15-27C30 6.7 23.3 0 15 0z" fill="#E63946"/>' +
              '<circle cx="15" cy="15" r="6" fill="#fff"/>' +
              '</svg></div>',
          iconSize: [30, 42],
          iconAnchor: [15, 42],
      });
  }

  const markers = {};
  const bounds = [];

  locations.forEach(function (loc, i) {
      const marker = L.marker([loc.lat, loc.lng], { icon: pinIcon(i === 0) }).addTo(map);
      marker.on('click', function () { setActiveLocation(loc.id); });
      markers[loc.id] = marker;
      bounds.push([loc.lat, loc.lng]);
  });

  if (bounds.length > 1) {
    map.fitBounds(bounds, { padding: [40, 40] });
} else {
    map.setView(bounds[0], 11);
}


  let activeId = locations[0].id;
  let animating = false;

  function setActiveLocation(id) {
    if (animating || String(id) === String(activeId)) return;

    const currentPanel = document.querySelector('.footprint-location-panel.is-active');
    const nextPanel = document.querySelector('.footprint-location-panel[data-location-id="' + id + '"]');
    if (!nextPanel) return;

    animating = true;

    if (currentPanel && currentPanel._carouselStop) currentPanel._carouselStop();   // NEW

    Object.keys(markers).forEach(function (mid) {
        markers[mid].setIcon(pinIcon(String(mid) === String(id)));
    });

    if (currentPanel) {
        currentPanel.classList.remove('is-active');
        currentPanel.classList.add('is-leaving');
    }
    nextPanel.classList.add('is-active', 'is-entering');

    if (nextPanel._carouselStart) nextPanel._carouselStart();   // NEW

    setTimeout(function () {
        if (currentPanel) currentPanel.classList.remove('is-leaving');
        nextPanel.classList.remove('is-entering');
        animating = false;
    }, 400);

    activeId = id;
}
});
</script>
@endsection