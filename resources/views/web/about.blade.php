@extends('web.layout.app')
@section('title', $about->who_we_are_meta_title ?? 'About Us - Energy Inspection Services Ltd')
@section('meta_description', $about->who_we_are_meta_description ?? 'Learn more about Energy Inspection Services Ltd, a specialized Oil & Gas inspection provider.')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@1,500;1,600&display=swap" rel="stylesheet">
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
  background:#0a1414;
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
  object-fit:fill;
  object-position:center 30%;   /* NEW — pulls focus up slightly so subjects aren't cropped awkwardly */
  opacity:0;
  transform:scale(1.06);
  transition:opacity 1.4s ease, transform 8s ease;
}

.hero-slide.active{
  opacity:1;
  z-index:1;
  transform:scale(1);           /* slow Ken-Burns style zoom-out once active */
}

/* ===== Layered gradient overlay for a premium, cinematic look ===== */
.hero::before{
  content:"";
  position:absolute;
  inset:0;
  z-index:2;
  background:
    linear-gradient(180deg, rgba(6,14,14,0.35) 0%, rgba(6,14,14,0.08) 30%, rgba(6,14,14,0.2) 65%, rgba(6,14,14,0.62) 100%),
    linear-gradient(100deg, rgba(8,16,16,0.55) 0%, rgba(8,16,16,0.2) 45%, rgba(20,30,30,0.02) 70%);
  pointer-events:none;
}

.hero::after{
  content:"";
  position:absolute;
  left:-10%;
  bottom:-20%;
  width:60%;
  height:70%;
  z-index:2;
  background:radial-gradient(circle, rgba(232,121,45,0.14) 0%, transparent 65%);
  pointer-events:none;
}

.hero-vignette{
  position:absolute;
  inset:0;
  z-index:3;
  box-shadow:inset 0 0 130px rgba(0,0,0,0.35);
  pointer-events:none;
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
  padding:0 90px 30px;
}

.hero .hero-inner{ max-width:760px; }
.hero.has-video::before,
.hero.has-video::after{
  display:none;
}

.hero.has-video h1,
.hero.has-video .eyebrow,
.hero.has-video .lede{
  text-shadow: 0 2px 12px rgba(0,0,0,0.75);   /* stronger per-letter shadow instead of a background wash */
}
.hero .eyebrow{
  position:relative;
  display:inline-flex;
  align-items:center;
  gap:12px;
  color:var(--cream);
  font-family:'Cormorant Garamond', 'Segoe UI', serif;
  font-style:italic;
  font-weight:600;
  font-size:20px;
  letter-spacing:0.4px;
  text-transform:none;      /* was uppercase — italics read better in normal case */
  margin-bottom:22px;
}
.hero-video{
  position:absolute;
  top:0;
  left:0;
  right:0;
  bottom:0;
  width:100%;
  height:100%;
  object-fit:fill;
  object-position:center;
  z-index:1;
  display:block;
}
.hero .eyebrow::before{
  content:"";
  width:34px;
  height:2px;
  background:var(--orange);
  display:inline-block;
}

.hero h1{
  color:var(--white);
  font-size:clamp(28px, 4.2vw, 60px);
  line-height:1.12;
  font-weight:800;
  letter-spacing:-0.5px;
  margin:0 0 26px;
  text-shadow: 0 4px 32px rgba(0,0,0,0.55);
  word-break:break-word;
}

.hero .lede{
  color:rgba(255,255,255,0.92);
  font-size:clamp(15px, 1.6vw, 19px);
  line-height:1.55;
  max-width:640px;
  margin:0 0 36px;
}

@media (prefers-reduced-motion: reduce){
  .hero-slide{ transition:opacity 1.2s ease; transform:none !important; }
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
  .hero{
    min-height:auto;      /* was 100vh/100svh — now sizes to content, like the reference */
  }
  .hero .hero-content{
    padding:110px 20px 40px;
    align-items:flex-start;   /* content flows naturally instead of being pinned to bottom */
  }
  .hero .eyebrow{ font-size:19px; margin-bottom:12px; }
  .hero h1{ margin-bottom:16px; font-size:clamp(26px, 7vw, 34px); }
  .hero .lede{ margin-bottom:0; }
  .hero-vignette{ box-shadow:inset 0 0 80px rgba(0,0,0,0.28); }
}

@media (max-width: 380px){
  .hero .hero-content{ padding:100px 16px 32px; }
  .hero .eyebrow{ font-size:17px; }
}
  @media (prefers-reduced-motion: reduce){
    .hero-slide{ transition:none; }
    .hero .btn{ transition:none; }
  }

  .about-intro{
    position:relative;
    background:#ffffff;
    font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
    padding:90px 60px 30px;
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
  padding:60px 60px 30px;
}

.who-we-are *{ box-sizing:border-box; }

/* center the two columns against each other */
.who-we-are-inner{
  position:relative;
  display:flex;
  align-items:center;
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
  margin:0 0 22px;
  position:relative;
  padding-bottom:16px;
}
.who-we-are-heading::after{
  content:"";
  position:absolute;
  left:0;
  bottom:0;
  width:56px;
  height:3px;
  border-radius:2px;
  background:var(--orange);
}

.who-we-are-left p{
  font-size:17px;
  line-height:1.75;
  color:#3d3d3d;
  margin:0 0 20px;
}
.who-we-are-left p:last-child{ margin-bottom:0; }

/* ===== Photo: proper layered shadow + hover animation ===== */
.who-we-are-photo{
  position:relative;
  flex:1;
  min-width:0;
  height:450px;
  border-radius:10px;
  overflow:hidden;
  box-shadow:
    0 2px 6px rgba(0,0,0,0.08),
    0 20px 40px rgba(0,0,0,0.14),
    0 8px 50px rgba(232,121,45,0.14);
  transition:
    opacity 0.8s cubic-bezier(0.16,1,0.3,1),
    transform 0.5s cubic-bezier(0.16,1,0.3,1),
    box-shadow 0.45s ease;
}

.who-we-are-photo-img{
  position:absolute;
  inset:0;
  width:100%;
  height:100%;
  object-fit:fill;      /* was background-position/background-size — now real fill, no cropping */
  transform:scale(1.04);
  transition:transform 1.1s cubic-bezier(0.16,1,0.3,1);
}

.who-we-are-photo-overlay{
  position:absolute;
  inset:0;
  background:linear-gradient(120deg, rgba(30,30,35,0.4), rgba(30,30,35,0.1) 55%, rgba(232,121,45,0.25));
}

.who-we-are-photo:hover{
  transform:translateY(-6px);
  box-shadow:
    0 4px 8px rgba(0,0,0,0.1),
    0 28px 56px rgba(0,0,0,0.2),
    0 12px 70px rgba(232,121,45,0.24);
}
.who-we-are-photo:hover .who-we-are-photo-img{
  transform:scale(1.12);
}

@media (max-width: 900px){
  .who-we-are{ padding:64px 24px 70px; }
  .who-we-are-inner{ flex-direction:column; gap:36px; }
  .who-we-are-inner::before{ display:none; }
  .who-we-are-left{ flex-basis:auto; max-width:none; }
  .who-we-are-photo{ height:auto; aspect-ratio:4/3; width:100%; }
.who-we-are-photo:hover{ transform:none; }  /* skip the lift on touch/small screens */
}
.footprint{
  position:relative;
  background:#ffffff;
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:60px 60px 60px;
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
}
.footprint-empty{
  color:#8a8a8a;
  font-size:15px;
  padding:12px 0 0;
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
.footprint-location-photo img{ display:block; width:100%; height:100%; object-fit:fill; transition:transform 0.6s cubic-bezier(0.16,1,0.3,1); }
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
  padding:20px 60px 60px;
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
  object-fit:fill;
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
  width:60px;
  height:50px;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
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
  background: #ffffff;
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
  object-fit:fill;
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
body.cert-lightbox-open .cert-nav{
  display:none;
}
.certifications{
  position:relative;
  background:linear-gradient(180deg, #FFF6EC 0%, #FFEEDD 100%);   /* light orange wash */
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:60px 60px 60px;
  overflow:hidden;
}
.certifications *{ box-sizing:border-box; }
.certifications-inner{ max-width:1300px; margin:0 auto; }
.certifications-top{ margin-bottom:56px; }

.certifications-eyebrow{
  color:var(--orange);
  font-weight:700;
  font-size:19px;
  letter-spacing:0.3px;
  margin:0 0 14px;
  display:inline-flex;
  align-items:center;
  gap:12px;
}
.certifications-eyebrow::before{
  content:"";
  width:34px;
  height:2px;
  background:var(--orange);
  display:inline-block;
}

.certifications-heading{
  font-size:clamp(28px, 3.6vw, 46px);
  line-height:1.2;
  font-weight:700;
  color:#111111;
  margin:0;
}

/* ===== Carousel shell ===== */
.cert-carousel{
  position:relative;
  display:flex;
  align-items:center;
  gap:20px;
}

.cert-track-wrap{
  flex:1;
  overflow:hidden;
}

.cert-track{
  display:flex;
  gap:48px;   /* was 32px — more breathing room between cards */
  transition:transform 0.6s cubic-bezier(0.16,1,0.3,1);
}

.cert-item{
   flex:0 0 calc((100% - 3*48px) / 4);
  display:flex;
  flex-direction:column;
  align-items:center;
  text-align:center;
  cursor:pointer;
}

/* ===== Card: shows the WHOLE certificate, never crops it ===== */
.cert-photo{
  position:relative;
  width:100%;
  aspect-ratio: 3 / 4;
  border-radius:16px;
  overflow:hidden;
  background:#ffffff;
  border:1px solid #ececec;
  box-shadow:
    0 2px 4px rgba(0,0,0,0.04),
    0 16px 32px rgba(0,0,0,0.08);
  transition:
    transform 0.5s cubic-bezier(0.16,1,0.3,1),
    box-shadow 0.5s cubic-bezier(0.16,1,0.3,1),
    border-color 0.4s ease;
  margin-bottom:20px;
  padding:14px;
}

.cert-photo img{
  width:100%;
  height:100%;
  object-fit:contain;   /* KEY FIX — full document visible, nothing cropped */
  display:block;
  transition:transform 0.6s cubic-bezier(0.16,1,0.3,1);
}

.cert-zoom-hint{
  position:absolute;
  top:12px; right:12px;
  width:36px; height:36px;
  border-radius:50%;
  background:rgba(20,20,20,0.6);
  display:flex; align-items:center; justify-content:center;
  opacity:0;
  transform:scale(0.8);
  transition:opacity 0.3s ease, transform 0.3s ease;
  pointer-events:none;
}
.cert-item:hover .cert-zoom-hint{ opacity:1; transform:scale(1); }

.cert-shine{
  position:absolute;
  inset:0;
  background:linear-gradient(115deg, transparent 40%, rgba(255,255,255,0.5) 50%, transparent 60%);
  transform:translateX(-120%);
  pointer-events:none;
}
.cert-item:hover .cert-photo{
  transform:translateY(-10px);
  border-color:rgba(232,121,45,0.4);
  box-shadow:
    0 4px 10px rgba(0,0,0,0.06),
    0 28px 48px rgba(0,0,0,0.14),
    0 10px 40px rgba(232,121,45,0.18);
}
.cert-item:hover .cert-photo img{ transform:scale(1.04); }
.cert-item:hover .cert-shine{ animation: certShineSweep 1s ease forwards; }
@keyframes certShineSweep{
  from{ transform:translateX(-120%); }
  to{ transform:translateX(120%); }
}

.cert-title{
  font-size:15px;
  font-weight:600;
  color:#111111;
  margin:0 0 10px;
  line-height:1.4;
  word-break:break-word;
  transition:color 0.3s ease;
}
.cert-item:hover .cert-title{ color:var(--orange-dark); }

.cert-underline{
  display:block;
  width:0;
  height:2px;
  border-radius:2px;
  background:var(--orange);
  transition:width 0.4s cubic-bezier(0.16,1,0.3,1);
}
.cert-item:hover .cert-underline{ width:32px; }

/* ===== Nav arrows ===== */
.cert-nav{
  flex:0 0 auto;
  width:48px; height:48px;
  border-radius:50%;
  background:#ffffff;
  border:1px solid #e6e6e6;
  display:flex; align-items:center; justify-content:center;
  cursor:pointer;
  color:#111111;
  font-size:18px;
  box-shadow:0 8px 20px rgba(0,0,0,0.06);
  transition:background 0.2s ease, color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
}
.cert-nav:hover{
  background:var(--orange);
  border-color:var(--orange);
  color:var(--white);
  transform:translateY(-2px);
}
.cert-nav:disabled{ opacity:0.35; cursor:default; pointer-events:none; }

/* ===== Dots (mobile) ===== */
.cert-dots{
  display:none;
  justify-content:center;
  gap:9px;
  margin-top:28px;
}
.cert-dot{
  width:9px; height:9px; border-radius:50%;
  background:#e0e0e0; border:none; padding:0; cursor:pointer;
  transition:background 0.2s ease, transform 0.2s ease;
}
.cert-dot.active{ background:var(--orange); transform:scale(1.25); }

/* ===== Lightbox: view original-size certificate ===== */
.cert-lightbox{
  position:fixed; inset:0;
  background:rgba(10,10,10,0.92);
  display:none;
  align-items:center; justify-content:center;
  z-index:9999;
  padding:40px;
  opacity:0;
  transition:opacity 0.3s ease;
}
.cert-lightbox.is-open{ display:flex; opacity:1; }

.cert-lightbox-content{
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:18px;
  max-width:92vw;
  max-height:92vh;
  width:auto;
  height:auto;
}

.cert-lightbox-img{
  display:block;
  width:auto;
  height:auto;
  max-width:92vw;
  max-height:82vh;
  object-fit:contain;
  object-position:center;
  border-radius:8px;
  box-shadow:0 30px 80px rgba(0,0,0,0.5);
  transform:scale(0.96);
  transition:transform 0.3s cubic-bezier(0.16,1,0.3,1);
}
.cert-lightbox.is-open .cert-lightbox-img{ transform:scale(1); }

.cert-lightbox-title{
  color:#fff;
  font-size:17px;
  font-weight:600;
  text-align:center;
  margin:0;
  opacity:0.92;
}

.cert-lightbox-close{
  position:absolute; top:24px; right:32px;
  width:44px; height:44px; border-radius:50%;
  background:rgba(255,255,255,0.1);
  border:1px solid rgba(255,255,255,0.25);
  color:#fff; font-size:26px; line-height:1;
  display:flex; align-items:center; justify-content:center;
  cursor:pointer;
  transition:background 0.2s ease;
}
.cert-lightbox-close:hover{ background:rgba(255,255,255,0.2); }
/* ===== Responsive: 4 → 3 → 2 → 1 (with dots + autoplay) ===== */
@media (max-width: 1200px){
  .cert-item{ flex:0 0 calc((100% - 2*36px) / 3); }
  .cert-track{ gap:36px; }
}
@media (max-width: 900px){
  .certifications{ padding:72px 24px 80px; }
  .certifications-top{ margin-bottom:40px; }
  .cert-item{ flex:0 0 calc((100% - 28px) / 2); }
  .cert-track{ gap:28px; }
  .cert-nav{ width:40px; height:40px; }
}
@media (max-width: 600px){
  .certifications{ padding:56px 20px 64px; }
  .certifications-heading{ font-size:clamp(22px, 6.5vw, 30px); }
  .certifications-eyebrow{ font-size:16px; }
  .cert-item{ flex:0 0 100%; }
  .cert-track{ gap:0; }
  .cert-nav{ display:none; }
  .cert-dots{ display:flex; }
  .cert-photo{ border-radius:12px; margin-bottom:14px; padding:10px; }
  .cert-title{ font-size:13.5px; }
  .cert-lightbox{ padding:16px; }
  .cert-lightbox-close{ top:14px; right:14px; }
}
@media (hover: none){
  .cert-item:hover .cert-photo{ transform:none; box-shadow:0 2px 4px rgba(0,0,0,0.04), 0 16px 32px rgba(0,0,0,0.08); }
  .cert-item:hover .cert-photo img{ transform:none; }
  .cert-shine{ display:none; }
  .cert-zoom-hint{ opacity:1; transform:scale(1); }
}
@media (prefers-reduced-motion: reduce){
  .cert-photo, .cert-photo img, .cert-underline, .cert-track{ transition:none; }
  .cert-shine{ display:none; }
}
.cert-lightbox-content .cert-lightbox-img{
  aspect-ratio:auto !important;
  object-fit:contain !important;
  padding:0 !important;
  background:none !important;
  border:none !important;
  width:auto !important;
  height:auto !important;
  max-width:92vw !important;
  max-height:82vh !important;
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
<section class="hero @if(!empty($about->banner_video)) has-video @endif">
@include('web.layout.navbar')

  @if (!empty($about->banner_video))
   <video
  class="hero-video"
  src="{{ asset('storage/' . $about->banner_video) }}"
  poster="{{ $about->banner ? asset('storage/' . $about->banner) : '' }}"
  autoplay
  muted
  loop
  playsinline
  preload="auto"
></video>
  @elseif ($about && $about->banner)
    <div class="hero-slides">
      <img
        src="{{ asset('storage/' . $about->banner) }}"
        class="hero-slide active"
        alt="{{ $about->title ?? 'Banner image' }}"
      >
    </div>
  @endif

  @if (empty($about->banner_video))
    <div class="hero-vignette" aria-hidden="true"></div>
  @endif

  <div class="rig-decor" aria-hidden="true"></div>
  <div class="hero-figure" aria-hidden="true"></div>

  <div class="hero-content">
    <div class="hero-inner">
      <p class="eyebrow">Quality. Safety. Reliability.</p>
      <h1>{{ $about->title ?? '' }}</h1>
      @if (!empty($about->banner_description))
        <p class="lede">{{ $about->banner_description }}</p>
      @endif
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
     role="img" aria-label="EIS inspectors reviewing plans on site">
  @if($about && $about->image)
    <img src="{{ asset('storage/' . $about->image) }}"
         alt="{{ $about->title ?? '' }}"
         class="who-we-are-photo-img">
  @endif
  <div class="who-we-are-photo-overlay"></div>
</div>
  </div>
</section>

@if ($certificates->count())
<section class="certifications">
  <div class="certifications-inner">
    <div class="certifications-top reveal reveal-left">
      <p class="certifications-eyebrow">Accreditations</p>
      <h2 class="certifications-heading">EIS Accredited Certifications</h2>
    </div>

    <div class="cert-carousel reveal reveal-delay-1">
      <button type="button" class="cert-nav" id="certPrev" aria-label="Previous">&#10094;</button>

      <div class="cert-track-wrap">
        <div class="cert-track" id="certTrack">
          @foreach ($certificates as $certificate)
            <div class="cert-item" data-full="{{ Storage::url($certificate->image) }}" data-title="{{ $certificate->title }}">
              <div class="cert-photo">
                <img src="{{ Storage::url($certificate->image) }}" alt="{{ $certificate->title }}" loading="lazy">
                <div class="cert-shine" aria-hidden="true"></div>
                <div class="cert-zoom-hint" aria-hidden="true">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <circle cx="11" cy="11" r="7" stroke="#fff" stroke-width="2"/>
                    <path d="M21 21l-4.3-4.3" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <button type="button" class="cert-nav" id="certNext" aria-label="Next">&#10095;</button>
    </div>

    <div class="cert-dots" id="certDots"></div>
  </div>
</section>

<div class="cert-lightbox" id="certLightbox" aria-hidden="true">
  <button type="button" class="cert-lightbox-close" id="certLightboxClose" aria-label="Close">&times;</button>
  <div class="cert-lightbox-content">
    <img src="" alt="" id="certLightboxImg" class="cert-lightbox-img">
    <p class="cert-lightbox-title" id="certLightboxTitle"></p>
  </div>
</div>
@endif

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
  <h3 class="footprint-panel-title"></h3>

  @if ($location->offices->isEmpty())
    <p class="footprint-empty">No offices added for this location yet.</p>
  @else
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
  @endif
</div>
  @empty
    <p style="color:#888;">No locations added yet.</p>
  @endforelse
</div>
    </div>
  </div>
</section>

<section class="operation">
  <div class="operation-inner">
    <div class="operation-top reveal reveal-left">
      <p class="operation-eyebrow">See The Operation</p>
      <h2 class="operation-heading">Inside our inspection process</h2>
    </div>

    @if ($mainVideo)
      <div class="operation-video reveal reveal-delay-1"
          id="operationVideo"
          data-video-url="{{ $mainVideo->video ? asset('storage/' . $mainVideo->video) : ($mainVideo->vedio_link ?? '') }}"
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
              data-video-url="{{ $clip->video ? asset('storage/' . $clip->video) : ($clip->vedio_link ?? '') }}"
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

function isYoutubeUrl(url) {
  return /youtube\.com|youtu\.be/.test(url);
}

function toYoutubeEmbedUrl(url) {
  const match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/);
  return match ? `https://www.youtube.com/embed/${match[1]}?autoplay=1` : url;
}

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

  const existingMedia = operationVideo.querySelector('video, iframe');
  if (existingMedia) existingMedia.remove();

  let mediaEl;
  if (isYoutubeUrl(videoUrl)) {
    mediaEl = document.createElement('iframe');
    mediaEl.src = toYoutubeEmbedUrl(videoUrl);
    mediaEl.allow = 'autoplay; encrypted-media; picture-in-picture';
    mediaEl.allowFullscreen = true;
    mediaEl.style.border = '0';
  } else {
    mediaEl = document.createElement('video');
    mediaEl.src = videoUrl;
    mediaEl.controls = true;
    mediaEl.autoplay = true;
    mediaEl.style.objectFit = 'cover';
  }

  mediaEl.style.position = 'absolute';
  mediaEl.style.inset = '0';
  mediaEl.style.width = '100%';
  mediaEl.style.height = '100%';
  mediaEl.style.zIndex = '1';

  operationVideo.prepend(mediaEl);
  operationVideo.classList.add('is-playing');

  if (mediaEl.tagName === 'VIDEO') {
    mediaEl.addEventListener('ended', function () {
      operationVideo.classList.remove('is-playing');
    });
  }

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

<script>
document.addEventListener('DOMContentLoaded', function () {
  const track = document.getElementById('certTrack');
  const prevBtn = document.getElementById('certPrev');
  const nextBtn = document.getElementById('certNext');
  const dotsWrap = document.getElementById('certDots');
  const carousel = document.querySelector('.cert-carousel');
  if (!track) return;

  const items = Array.from(track.children);
  let visibleCount = 4;
  let index = 0;
  let autoplayTimer = null;

  function getVisibleCount() {
    if (window.innerWidth <= 600) return 1;
    if (window.innerWidth <= 900) return 2;
    if (window.innerWidth <= 1200) return 3;
    return 4;
  }

  function maxIndex() {
    return Math.max(0, items.length - visibleCount);
  }

  function buildDots() {
    dotsWrap.innerHTML = '';
    if (visibleCount !== 1) return; // dots only used in one-at-a-time mobile mode
    items.forEach(function (_, i) {
      const dot = document.createElement('button');
      dot.type = 'button';
      dot.className = 'cert-dot' + (i === 0 ? ' active' : '');
      dot.setAttribute('aria-label', 'Show certificate ' + (i + 1));
      dot.addEventListener('click', function () {
        index = i;
        update();
        restartAutoplay();
      });
      dotsWrap.appendChild(dot);
    });
  }

  function updateDots() {
    dotsWrap.querySelectorAll('.cert-dot').forEach(function (dot, i) {
      dot.classList.toggle('active', i === index);
    });
  }

  function update() {
    const newVisible = getVisibleCount();
    if (newVisible !== visibleCount) {
      visibleCount = newVisible;
      index = Math.min(index, maxIndex());
      buildDots();
    }
    index = Math.min(index, maxIndex());
    const cardWidth = items[0].getBoundingClientRect().width;
    const gap = parseFloat(getComputedStyle(track).gap) || 0;
    track.style.transform = 'translateX(' + (-(cardWidth + gap) * index) + 'px)';
    if (prevBtn) prevBtn.disabled = index === 0;
    if (nextBtn) nextBtn.disabled = index >= maxIndex();
    updateDots();
  }

  function goNext() {
    index = index >= maxIndex() ? 0 : index + 1; // loop back to start
    update();
  }
  function goPrev() {
    index = index <= 0 ? maxIndex() : index - 1;
    update();
  }

  function startAutoplay() {
    stopAutoplay();
    if (items.length <= visibleCount) return;
    autoplayTimer = setInterval(goNext, 4000);
  }
  function stopAutoplay() {
    if (autoplayTimer) clearInterval(autoplayTimer);
    autoplayTimer = null;
  }
  function restartAutoplay() { startAutoplay(); }

  if (prevBtn) prevBtn.addEventListener('click', function () { goPrev(); restartAutoplay(); });
  if (nextBtn) nextBtn.addEventListener('click', function () { goNext(); restartAutoplay(); });

  if (carousel) {
    carousel.addEventListener('mouseenter', stopAutoplay);
    carousel.addEventListener('mouseleave', startAutoplay);
    carousel.addEventListener('touchstart', stopAutoplay, { passive: true });
    carousel.addEventListener('touchend', startAutoplay, { passive: true });
  }

  window.addEventListener('resize', update);
  buildDots();
  update();
  startAutoplay();

  // ===== Lightbox: view original-size certificate =====
  const lightbox = document.getElementById('certLightbox');
  const lightboxImg = document.getElementById('certLightboxImg');
  const lightboxClose = document.getElementById('certLightboxClose');

  function openLightbox(src, alt, title) {
  lightboxImg.src = src;
  lightboxImg.alt = alt || '';
  document.getElementById('certLightboxTitle').textContent = title || '';
  lightbox.classList.add('is-open');
  lightbox.setAttribute('aria-hidden', 'false');
  document.body.classList.add('cert-lightbox-open');   
  document.body.style.overflow = 'hidden';
  stopAutoplay();
}

function closeLightbox() {
  lightbox.classList.remove('is-open');
  lightbox.setAttribute('aria-hidden', 'true');
  document.body.classList.remove('cert-lightbox-open');
  document.body.style.overflow = '';
  startAutoplay();
}

  items.forEach(function (item) {
  item.addEventListener('click', function () {
    const full = item.getAttribute('data-full');
    const title = item.getAttribute('data-title');
    const img = item.querySelector('img');
    openLightbox(full, img ? img.alt : '', title);
  });
});

  if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
  if (lightbox) {
    lightbox.addEventListener('click', function (e) {
      if (e.target === lightbox) closeLightbox();
    });
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeLightbox();
  });
});
</script>
@endsection