@extends('web.layout.app')
@section('title', $service->meta_title ?? $service->banner_title ?? 'Service - Energy Inspection Services Ltd')
@section('meta_description', $service->meta_description ?? $service->banner_description ?? 'Specialized oil and gas inspection services from Energy Inspection Services Ltd.')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@1,500;1,600&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange: #E8792D;
            --orange-dark: #C4611E;
            --navy: #1B2A2E;
            --cream: #F5F1E8;
            --white: #FFFFFF;
        }

        .hero {
    position: relative;
    min-height: 100vh;
    min-height: 100svh;
    display: flex;
    flex-direction: column;
    background:#0a1414;
    overflow: hidden;
    font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
}

.hero * {
    box-sizing: border-box;
}

.hero-slides {
    position: absolute;
    inset: 0;
    z-index: 0;
}

.hero-slide {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: fill;      /* was cover — now never crops */
    opacity: 0;
    transform:scale(1.06);
    transition: opacity 1.4s ease, transform 8s ease;
}

.hero-slide.active {
    opacity: 1;
    z-index: 1;
    transform:scale(1);
}

.hero-video{
  position:absolute;
  top:0;
  left:0;
  right:0;
  bottom:0;
  width:100%;
  height:100%;
  object-fit:cover;
  object-position:center;
  z-index:1;
  display:block;
}

/* ===== Layered gradient overlay for image banners only ===== */
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

.hero.has-video::before,
.hero.has-video::after{
  display:none;
}

.hero.has-video h1,
.hero.has-video .eyebrow,
.hero.has-video .lede{
  text-shadow: 0 2px 12px rgba(0,0,0,0.75);
}

.hero .rig-decor {
    z-index: 2;
}

.hero .hero-content {
    position: relative;
    z-index: 5;
    flex: 1;
    display: flex;
    align-items: flex-end;
    justify-content: flex-start;
    padding: 0 90px 90px;
}

.hero .hero-inner {
    max-width: 760px;
}

.hero .eyebrow{
  position:relative;
  display:inline-flex;
  align-items:center;
  gap:12px;
  color: var(--cream);
  font-family:'Cormorant Garamond', 'Segoe UI', serif;
  font-style:italic;
  font-weight:600;
  font-size:20px;
  letter-spacing:0.4px;
  margin-bottom: 22px;
}

.hero .eyebrow::before{
  content:"";
  width:34px;
  height:2px;
  background:var(--orange);
  display:inline-block;
}

.hero h1 {
    color: var(--white);
    font-size: clamp(28px, 4.2vw, 60px);
    line-height: 1.15;
    font-weight: 800;
    letter-spacing: -0.5px;
    margin: 0 0 20px;
    text-shadow: 0 4px 32px rgba(0, 0, 0, 0.55);
    word-break: break-word;
}

.hero .lede {
    color: rgba(255,255,255,0.92);
    font-size: clamp(16px, 1.6vw, 20px);
    font-weight: 500;
    max-width: 640px;
    margin: 0 0 10px;
    line-height: 1.55;
}

@media (max-width: 1024px) {
   .hero .hero-content {
    padding: 0 50px 70px;
}
}

@media (max-width: 900px) {
   .hero .hero-content {
    padding: 0 24px 56px;
}
}

@media (max-width: 600px) {
   .hero {
    min-height: auto;
}

    .hero .hero-content {
    padding: 110px 20px 40px;
    align-items: flex-start;
}

.hero .eyebrow {
    font-size: 19px;
    margin-bottom: 12px;
}

.hero h1 {
    margin-bottom: 16px;
}

.hero .lede {
    margin-bottom: 0;
}

.hero-vignette{ box-shadow:inset 0 0 80px rgba(0,0,0,0.28); }
}

@media (max-width: 380px) {
   .hero .hero-content {
    padding: 100px 16px 32px;
}

.hero .eyebrow {
    font-size: 17px;
}
}

@media (prefers-reduced-motion: reduce) {
    .hero-slide {
        transition: opacity 1.2s ease;
        transform:none !important;
    }
}

        .service-overview {
            position: relative;
            background: #ffffff;
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            padding: 30px 60px 30px;
        }

        .service-overview * {
            box-sizing: border-box;
        }

        .overview-inner {
            display: flex;
            align-items: center;
            /* vertical centering against the image */
            gap: 180px;
            margin: 0 auto;
        }

        .overview-left{
        flex:0 0 38%;
        max-width:38%;
        }

        .overview-eyebrow {
            color: var(--orange);
            font-weight: 700;
            font-size: 22px;
            margin: 0 0 16px;
        }

        .overview-heading {
            font-size: clamp(34px, 4vw, 52px);
            line-height: 1.15;
            font-weight: 700;
            color: #111111;
            margin: 0 0 26px;
        }

        .overview-desc {
            position: relative;
            max-height: 220px;
            overflow: hidden;
            transition: max-height 0.35s ease;
        }

        .overview-desc.expanded {
            max-height: 340px;
            overflow-y: auto;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .overview-desc.expanded::-webkit-scrollbar {
            width: 0;
            height: 0;
            display: none;
        }

        .overview-desc p {
            font-size: 22px;
            line-height: 1.75;
            color: #111111;
            margin: 0 40px 30px;
        }

        .overview-desc p:last-child {
            margin-bottom: 0;
        }

        .overview-desc::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 56px;
            background: linear-gradient(to top, #ffffff 0%, rgba(255,255,255,0) 100%);
            pointer-events: none;
            transition: opacity 0.25s ease;
        }

        .overview-desc.expanded::after {
            opacity: 0;
        }

        .overview-view-more {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin: 16px 40px 0;
            background: none;
            border: none;
            padding: 0;
            color: var(--orange);
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }

        .overview-view-more:hover {
            color: var(--orange-dark);
        }

        .overview-view-more-arrow {
            display: inline-block;
            transition: transform 0.3s ease;
            font-size: 12px;
        }

        .overview-view-more.expanded .overview-view-more-arrow {
            transform: rotate(180deg);
        }
        .overview-right{
        flex:1;
        max-width:760px; /* wider */
        }

        .overview-photo{
          width:100%;
          aspect-ratio: 760 / 500;
          border-radius:20px;
          background-color:#e8e8e8;
          background-position:center;
          background-size:100% 100%;   /* was cover — stretches to fill, no cropping */
          background-repeat:no-repeat;
          transition:transform 0.6s cubic-bezier(0.16,1,0.3,1), box-shadow 0.6s ease;
          margin-top:30px;
      }

        .overview-photo:hover {
            transform: translateY(-6px);
        }

        .overview-photo-placeholder {
            background-image: url('{{ asset('images/placeholder.jpg') }}');
        }

        @media (max-width: 900px) {
            .service-overview {
                padding: 64px 24px 74px;
            }

            .overview-inner {
                flex-direction: column;
                gap: 40px;
            }

            .overview-left,
            .overview-right {
                flex: none;
                max-width: none;
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .overview-photo {
                border-radius: 16px;
            }

            .overview-heading {
                font-size: 28px;
            }

            .overview-desc p {
                font-size: 16px;
            }

            .overview-desc {
                max-height: 170px;
            }

            .overview-desc.expanded {
                max-height: 280px;
            }
        }

        /* ===== Overview: stop slide-in from causing horizontal scroll ===== */
.service-overview{ overflow:hidden; }

/* ===== Process section (mirror of Overview: image left, text right) ===== */
.service-process{
  position:relative;
  background:#ffffff;
  font-family:'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:30px 60px 60px;
  overflow:hidden;
}
.service-process *{ box-sizing:border-box; }

.process-inner{
  display:flex;
  align-items:center;
  gap:180px;
  margin:0 auto;
}
.process-left{ flex:1; max-width:760px; }
.process-right{ flex:0 0 38%; max-width:38%; }

/* ===== Slide-in from sides (Overview + Process) ===== */
.slide-in-left,
.slide-in-right{
  opacity:0;
  transition:opacity 0.9s cubic-bezier(0.16,1,0.3,1), transform 0.9s cubic-bezier(0.16,1,0.3,1);
  will-change:opacity, transform;
}
.slide-in-left{ transform:translateX(-80px); }
.slide-in-right{ transform:translateX(80px); transition-delay:0.15s; }

@media (max-width: 900px){
  .slide-in-left{ transform:translateX(-40px); }
  .slide-in-right{ transform:translateX(40px); transition-delay:0.1s; }
}

.slide-in-left.in-view,
.slide-in-right.in-view{
  opacity:1;
  transform:translateX(0);
}

@media (prefers-reduced-motion: reduce){
  .overview-photo{ transition:none; }
  .slide-in-left,
  .slide-in-right{
    opacity:1 !important;
    transform:none !important;
    transition:none !important;
  }
}

/* ===== Responsive: Overview + Process ===== */
@media (max-width: 1280px){
  .overview-inner,
  .process-inner{ gap:80px; }
}

@media (max-width: 1100px){
  .overview-inner,
  .process-inner{ gap:50px; }
  .overview-left,
  .process-right{ flex-basis:44%; max-width:44%; }
  .overview-desc p{ font-size:19px; margin:0 20px 24px; }
  .overview-view-more{ margin:14px 20px 0; }
}

@media (max-width: 900px){
  .service-process{ padding:40px 24px 64px; }
  .process-inner{ flex-direction:column; gap:32px; }
  .process-left,
  .process-right{ flex:none; max-width:none; width:100%; }
  .process-right{ order:-1; }             /* text first, image below, same as Overview on mobile */

  .overview-inner{ gap:32px; }
  .overview-left{ flex:none; max-width:none; width:100%; }
  .overview-desc p{ font-size:17px; margin:0 0 20px; }
  .overview-view-more{ margin:12px 0 0; }
  .overview-photo{ margin-top:0; }
  .overview-eyebrow{ font-size:18px; margin-bottom:10px; }
  .overview-heading{ margin-bottom:18px; }
}

@media (max-width: 480px){
  .service-overview{ padding:48px 16px 40px; }
  .service-process{ padding:32px 16px 48px; }
  .overview-desc p{ font-size:16px; line-height:1.7; }
}

.features{
  position:relative;
  background:#ffffff;
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:60px 60px 100px;
}

.features *{ box-sizing:border-box; }

.features-inner{ margin:0 auto; }

.features-top{ margin-bottom:44px; }

.features-eyebrow{
  color:var(--orange);
  font-weight:700;
  font-size:19px;
  margin:0 0 12px;
}

.features-heading{
  font-size:clamp(30px, 3.6vw, 46px);
  line-height:1.2;
  font-weight:400;
  color:#111111;
  margin:0;
}

.features-grid{
  position:relative;
  display:grid;
  grid-template-columns:repeat(4, 1fr);
  gap:28px;
}

.feature-card{
  position:relative;
  background:#ffffff;
  border:1px solid #ececec;
  border-radius:20px;
  padding:34px 26px;
  box-shadow:0 8px 22px rgba(0,0,0,0.04);
  overflow:hidden;
  transition:transform 0.4s cubic-bezier(0.16,1,0.3,1), box-shadow 0.4s ease, border-color 0.4s ease;
}

/* subtle orange glow sweep on hover */
.feature-card::before{
  content:"";
  position:absolute;
  top:-60%;
  left:-60%;
  width:220%;
  height:220%;
  background:radial-gradient(circle, rgba(232,121,45,0.08) 0%, transparent 60%);
  opacity:0;
  transform:scale(0.7);
  transition:opacity 0.5s ease, transform 0.5s ease;
  pointer-events:none;
}

.feature-card:hover{
  transform:translateY(-8px);
  box-shadow:0 24px 46px rgba(0,0,0,0.12);
  border-color:rgba(232,121,45,0.35);
}

.feature-card:hover::before{
  opacity:1;
  transform:scale(1);
}

.feature-icon{
  position:relative;
  width:56px;
  height:56px;
  margin-bottom:22px;
  display:flex;
  align-items:center;
  justify-content:center;
  transition:transform 0.45s cubic-bezier(0.34,1.56,0.64,1);
}

.feature-card:hover .feature-icon{
  transform:scale(1.15) rotate(-6deg);
}

.feature-icon img{
  width:100%;
  height:100%;
  object-fit:contain;
  position:relative;
  z-index:1;
}

.feature-title{
  position:relative;
  font-size:19px;
  line-height:1.35;
  font-weight:700;
  color:#111111;
  margin:0 0 12px;
  transition:color 0.3s ease;
}

.feature-card:hover .feature-title{
  color:var(--orange-dark);
}

.feature-desc{
  position:relative;
  font-size:14.5px;
  line-height:1.6;
  color:#5a5a5a;
  margin:0;
}

/* ===== Entrance animation (icon pops in after the card settles) ===== */
.feature-card.reveal{
  opacity:0;
  transform:translateY(40px) scale(0.96);
  transition:opacity 0.7s cubic-bezier(0.16,1,0.3,1), transform 0.7s cubic-bezier(0.16,1,0.3,1);
}

.feature-card.reveal.in-view{
  opacity:1;
  transform:translateY(0) scale(1);
}

.feature-card.reveal .feature-icon{
  opacity:0;
  transform:scale(0.4) rotate(-20deg);
}

.feature-card.reveal.in-view .feature-icon{
  animation: featureIconPop 0.6s cubic-bezier(0.34,1.56,0.64,1) 0.25s forwards;
}

@keyframes featureIconPop{
  0%{ opacity:0; transform:scale(0.4) rotate(-20deg); }
  60%{ opacity:1; transform:scale(1.15) rotate(4deg); }
  100%{ opacity:1; transform:scale(1) rotate(0deg); }
}

.reveal-delay-1{ transition-delay:0.1s; }
.reveal-delay-2{ transition-delay:0.2s; }
.reveal-delay-3{ transition-delay:0.3s; }

@media (prefers-reduced-motion: reduce){
  .feature-card,
  .feature-card::before,
  .feature-icon,
  .feature-title{ transition:none !important; animation:none !important; }
  .feature-card.reveal .feature-icon{ opacity:1; transform:none; }
}

@media (max-width: 1100px){
  .features-grid{ grid-template-columns:repeat(2, 1fr); }
}

@media (max-width: 900px){
  .features{ padding:56px 24px 64px; }
  .features-grid{ gap:22px; }
}

@media (max-width: 600px){
  .features-grid{ grid-template-columns:1fr; }
  .features-heading{ font-size:clamp(24px, 6.5vw, 30px); }
}

@media (max-width: 480px){
  .features{ padding:44px 16px 56px; }
  .feature-card{ padding:26px 20px; }
}

.related-services{
  position:relative;
  background:#fafafa;
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:80px 60px 100px;
  overflow:hidden;
}

.related-services *{ box-sizing:border-box; }

.related-inner{ margin:0 auto; }

.related-top{
  display:flex;
  align-items:flex-end;
  justify-content:space-between;
  gap:24px;
  margin-bottom:48px;
  flex-wrap:wrap;
}

.related-eyebrow{
  color:var(--orange);
  font-weight:700;
  font-size:19px;
  margin:0 0 12px;
}

.related-heading{
  font-size:clamp(28px, 3.4vw, 42px);
  line-height:1.2;
  font-weight:400;
  color:#111111;
  margin:0;
}

.related-view-all{
  display:inline-flex;
  align-items:center;
  gap:10px;
  padding:14px 26px;
  border-radius:30px;
  border:2px solid var(--orange);
  color:var(--orange);
  font-size:15px;
  font-weight:700;
  text-decoration:none;
  white-space:nowrap;
  transition:background 0.3s ease, color 0.3s ease, transform 0.25s ease, box-shadow 0.3s ease;
}

.related-view-all-arrow{
  display:inline-block;
  transition:transform 0.3s cubic-bezier(0.34,1.56,0.64,1);
}

.related-view-all:hover{
  background:var(--orange);
  color:#ffffff;
  transform:translateY(-3px);
  box-shadow:0 14px 28px rgba(232,121,45,0.28);
}

.related-view-all:hover .related-view-all-arrow{
  transform:translateX(6px);
}

.related-grid{
  display:grid;
  grid-template-columns:repeat(4, 1fr);
  gap:28px;
}

.related-card{
  position:relative;
  display:flex;
  flex-direction:column;
  background:#ffffff;
  border-radius:22px;
  overflow:hidden;
  text-decoration:none;
  box-shadow:0 10px 26px rgba(0,0,0,0.06);
  transition:transform 0.45s cubic-bezier(0.16,1,0.3,1), box-shadow 0.45s ease;
}

.related-card:hover{
  transform:translateY(-10px);
  box-shadow:0 30px 54px rgba(0,0,0,0.16);
}

.related-card-photo{
  width:100%;
  aspect-ratio: 4 / 3;
  overflow:hidden;
}

.related-card-photo-img{
  width:100%;
  height:100%;
  object-fit: fill;
  display:block;
  transition:transform 0.7s cubic-bezier(0.16,1,0.3,1);
}

.related-card:hover .related-card-photo-img{
  transform:scale(1.08);
}

.related-card-body{
  padding:24px 22px 26px;
  display:flex;
  flex-direction:column;
  flex:1;
}

.related-card-title{
  font-size:19px;
  line-height:1.3;
  font-weight:700;
  color:#111111;
  margin:0 0 10px;
  transition:color 0.3s ease;
}

.related-card:hover .related-card-title{
  color:var(--orange-dark);
}

.related-card-desc{
  font-size:14px;
  line-height:1.6;
  color:#5a5a5a;
  margin:0 0 18px;
  flex:1;
}

.related-card-link{
  display:inline-flex;
  align-items:center;
  gap:8px;
  font-size:13.5px;
  font-weight:700;
  color:var(--orange);
}

.related-card-arrow{
  display:inline-block;
  transition:transform 0.3s cubic-bezier(0.34,1.56,0.64,1);
}

.related-card:hover .related-card-arrow{
  transform:translateX(6px);
}

/* Entrance animation, reusing the page's .reveal system */
.related-card.reveal{
  opacity:0;
  transform:translateY(46px) scale(0.96);
  transition:opacity 0.75s cubic-bezier(0.16,1,0.3,1), transform 0.75s cubic-bezier(0.16,1,0.3,1);
}

.related-card.reveal.in-view{
  opacity:1;
  transform:translateY(0) scale(1);
}

@media (prefers-reduced-motion: reduce){
  .related-card,
  .related-card-photo,
  .related-view-all,
  .related-view-all-arrow,
  .related-card-arrow{ transition:none !important; }
}

@media (max-width: 1100px){
  .related-grid{ grid-template-columns:repeat(2, 1fr); }
}

@media (max-width: 900px){
  .related-services{ padding:64px 24px 70px; }
  .related-top{ flex-direction:column; align-items:flex-start; gap:20px; }
  .related-grid{ gap:22px; }
}

@media (max-width: 600px){
  .related-grid{ grid-template-columns:1fr; }
  .related-heading{ font-size:clamp(24px, 6.5vw, 30px); }
  .related-view-all{ width:100%; justify-content:center; }
}

@media (max-width: 480px){
  .related-services{ padding:48px 16px 56px; }
  .related-card-body{ padding:20px 18px 22px; }
}

.inspection-cta{
  position:relative;
  background:#ffffff;
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:80px 60px 100px;
}

.inspection-cta *{ box-sizing:border-box; }

.inspection-cta-inner{
  display:flex;
  align-items:center;
  gap:80px;
  margin:0 auto;
}

.inspection-cta-photo{
  flex:0 0 46%;
  max-width:46%;
  aspect-ratio: 16 / 11;
  border-radius:20px;
  background-color:#e8e8e8;
  background-position:center;
  background-size:cover;
  background-repeat:no-repeat;
  box-shadow:0 24px 48px rgba(0,0,0,0.14);
  transition:transform 0.5s cubic-bezier(0.16,1,0.3,1), box-shadow 0.5s ease;
}

.inspection-cta-photo:hover{
  transform:translateY(-6px);
  box-shadow:0 32px 60px rgba(0,0,0,0.2);
}

.inspection-cta-content{ flex:1; min-width:0; }

.inspection-cta-eyebrow{
  color:var(--orange);
  font-weight:700;
  font-size:19px;
  margin:0 0 16px;
}

.inspection-cta-heading{
  font-size:clamp(30px, 3.6vw, 46px);
  line-height:1.2;
  font-weight:400;
  color:#111111;
  margin:0 0 24px;
}

.inspection-cta-desc{
  font-size:17px;
  line-height:1.65;
  color:#4a4a4a;
  max-width:560px;
  margin:0 0 34px;
}

.inspection-cta-btn{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  padding:17px 34px;
  border-radius:30px;
  background:var(--orange);
  color:#ffffff;
  font-size:16px;
  font-weight:700;
  text-decoration:none;
  transition:background 0.25s ease, transform 0.2s ease, box-shadow 0.3s ease;
}

.inspection-cta-btn:hover{
  background:var(--orange-dark);
  transform:translateY(-3px);
  box-shadow:0 16px 32px rgba(232,121,45,0.3);
}

/* Reveal animation, reusing the page's .reveal system */
.inspection-cta-photo.reveal{
  opacity:0;
  transform:translateX(-40px) scale(0.97);
  transition:opacity 0.85s cubic-bezier(0.16,1,0.3,1), transform 0.85s cubic-bezier(0.16,1,0.3,1);
}

.inspection-cta-content.reveal{
  opacity:0;
  transform:translateX(40px);
  transition:opacity 0.85s cubic-bezier(0.16,1,0.3,1), transform 0.85s cubic-bezier(0.16,1,0.3,1);
}

.inspection-cta-photo.reveal.in-view,
.inspection-cta-content.reveal.in-view{
  opacity:1;
  transform:translateX(0) scale(1);
}

@media (prefers-reduced-motion: reduce){
  .inspection-cta-photo,
  .inspection-cta-btn{ transition:none !important; }
  .inspection-cta-photo.reveal,
  .inspection-cta-content.reveal,
  .inspection-cta-photo.reveal.in-view,
  .inspection-cta-content.reveal.in-view{
    opacity:1 !important;
    transform:none !important;
  }
}

@media (max-width: 900px){
  .inspection-cta{ padding:64px 24px 70px; }
  .inspection-cta-inner{ flex-direction:column; gap:40px; }
  .inspection-cta-photo,
  .inspection-cta-content{ flex-basis:auto; max-width:none; width:100%; }
}

@media (max-width: 600px){
  .inspection-cta-heading{ font-size:clamp(24px, 6.5vw, 30px); }
  .inspection-cta-btn{ width:100%; }
}

@media (max-width: 480px){
  .inspection-cta{ padding:48px 16px 56px; }
  .inspection-cta-photo{ aspect-ratio:4/3; }
}

.gallery-section{
  position:relative;
  background:#FCEFE3; /* light orange */
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:30px 60px 60px;
  overflow:hidden;
}

.gallery-section *{ box-sizing:border-box; }

.gallery-inner{ margin:0 auto; }

.gallery-top{
  margin-bottom:44px;
  padding:0 60px;
}

.gallery-eyebrow{
  color:var(--orange);
  font-weight:700;
  font-size:19px;
  margin:0 0 12px;
}

.gallery-heading{
  font-size:clamp(30px, 3.6vw, 46px);
  line-height:1.2;
  font-weight:400;
  color:#111111;
  margin:0;
}

/* ===== Auto-scrolling single row ===== */
.gallery-marquee{
  position:relative;
  width:100%;
  overflow:hidden;
  -webkit-mask-image: linear-gradient(to right, transparent 0, #000 60px, #000 calc(100% - 60px), transparent 100%);
  mask-image: linear-gradient(to right, transparent 0, #000 60px, #000 calc(100% - 60px), transparent 100%);
}

.gallery-track{
  display:flex;
  gap:24px;
  width:max-content;
  animation: galleryScroll var(--gallery-duration, 40s) linear infinite;
}

.gallery-marquee:hover .gallery-track{
  animation-play-state:paused;
}

@keyframes galleryScroll{
  from{ transform:translateX(0); }
  to{ transform:translateX(-50%); }
}

.gallery-item{
  flex:0 0 auto;
  width:340px;
  height:260px;
  border-radius:16px;
  overflow:hidden;
  cursor:pointer;
  box-shadow:0 10px 26px rgba(0,0,0,0.08);
  transition:transform 0.4s cubic-bezier(0.16,1,0.3,1), box-shadow 0.4s ease;
  position:relative;
}

.gallery-item:hover{
  transform:translateY(-6px);
  box-shadow:0 22px 44px rgba(0,0,0,0.18);
  z-index:2;
}

.gallery-item img{
  display:block;
  width:100%;
  height:100%;
  object-fit:cover;
  transition:transform 0.7s cubic-bezier(0.16,1,0.3,1);
}

.gallery-item:hover img{
  transform:scale(1.08);
}

.gallery-item-overlay{
  position:absolute;
  inset:0;
  background:linear-gradient(to top, rgba(20,20,20,0.55) 0%, transparent 45%);
  opacity:0;
  transition:opacity 0.35s ease;
  display:flex;
  align-items:flex-end;
  padding:16px;
}

.gallery-item:hover .gallery-item-overlay{
  opacity:1;
}

.gallery-item-zoom{
  width:38px;
  height:38px;
  border-radius:50%;
  background:rgba(255,255,255,0.95);
  display:flex;
  align-items:center;
  justify-content:center;
  color:var(--navy);
  font-size:15px;
  margin-left:auto;
  box-shadow:0 6px 16px rgba(0,0,0,0.2);
}

.gallery-top.reveal{
  opacity:0;
  transform:translateY(24px);
  transition:opacity 0.7s cubic-bezier(0.16,1,0.3,1), transform 0.7s cubic-bezier(0.16,1,0.3,1);
}

.gallery-top.reveal.in-view{
  opacity:1;
  transform:translateY(0);
}

/* ===== Lightbox ===== */
.gallery-lightbox{
  position:fixed;
  inset:0;
  background:rgba(10,20,20,0.92);
  z-index:9999;
  display:none;
  align-items:center;
  justify-content:center;
  padding:40px;
}

.gallery-lightbox.active{ display:flex; }

.gallery-lightbox img{
  max-width:90vw;
  max-height:85vh;
  border-radius:10px;
  box-shadow:0 30px 70px rgba(0,0,0,0.5);
  animation:lightboxPop 0.3s cubic-bezier(0.34,1.56,0.64,1);
}

@keyframes lightboxPop{
  0%{ opacity:0; transform:scale(0.92); }
  100%{ opacity:1; transform:scale(1); }
}

.gallery-lightbox-close{
  position:absolute; top:28px; right:36px;
  width:44px; height:44px; border-radius:50%;
  background:rgba(255,255,255,0.12);
  border:1px solid rgba(255,255,255,0.3);
  color:#fff; font-size:20px;
  display:flex; align-items:center; justify-content:center;
  cursor:pointer;
  transition:background 0.2s ease, transform 0.2s ease;
}

.gallery-lightbox-close:hover{
  background:rgba(255,255,255,0.25);
  transform:rotate(90deg);
}

.gallery-lightbox-nav{
  position:absolute; top:50%; transform:translateY(-50%);
  width:50px; height:50px; border-radius:50%;
  background:rgba(255,255,255,0.12);
  border:1px solid rgba(255,255,255,0.3);
  color:#fff; font-size:20px;
  display:flex; align-items:center; justify-content:center;
  cursor:pointer;
  transition:background 0.2s ease;
}

.gallery-lightbox-nav:hover{ background:rgba(255,255,255,0.25); }
.gallery-lightbox-prev{ left:30px; }
.gallery-lightbox-next{ right:30px; }

/* ===== Responsive ===== */
@media (max-width: 900px){
  .gallery-section{ padding:24px 0 70px; }
  .gallery-top{ padding:0 24px; }
  .gallery-item{ width:260px; height:200px; }
}

@media (max-width: 600px){
  .gallery-heading{ font-size:clamp(24px, 6.5vw, 30px); }
  .gallery-item{ width:220px; height:170px; border-radius:14px; }
  .gallery-track{ gap:16px; }
  .gallery-lightbox-nav{ width:40px; height:40px; font-size:16px; }
  .gallery-lightbox-prev{ left:12px; }
  .gallery-lightbox-next{ right:12px; }
  .gallery-lightbox-close{ top:16px; right:16px; }
}

@media (max-width: 420px){
  .gallery-item{ width:180px; height:140px; }
}

@media (prefers-reduced-motion: reduce){
  .gallery-track{ animation:none !important; }
  .gallery-marquee{ overflow-x:auto; -webkit-overflow-scrolling:touch; }
  .gallery-item,
  .gallery-item img,
  .gallery-item-overlay,
  .gallery-lightbox-close,
  .gallery-top.reveal{ transition:none !important; }
  .gallery-top.reveal{ opacity:1 !important; transform:none !important; }
}
/* ===== Keep Overview / Process text inside its column ===== */
.overview-left,
.overview-right,
.process-left,
.process-right{
  min-width:0;                 /* lets flex columns shrink instead of overflowing */
}

.overview-heading,
.overview-desc p{
  overflow-wrap:anywhere;      /* breaks very long words instead of spilling out */
  word-break:break-word;
}
/* ===== CKEditor rich text inside Overview / Process description ===== */
.overview-desc h2,
.overview-desc h3,
.overview-desc h4{
  margin:0 40px 14px;
  line-height:1.3;
  font-weight:700;
  color:#111111;
}
.overview-desc h2{ font-size:28px; }
.overview-desc h3{ font-size:24px; }
.overview-desc h4{ font-size:21px; }

.overview-desc ul,
.overview-desc ol{
  margin:0 40px 24px;
  padding-left:24px;
  font-size:20px;
  line-height:1.7;
  color:#111111;
}
.overview-desc ul{ list-style:disc; }
.overview-desc ol{ list-style:decimal; }
.overview-desc li{ margin-bottom:6px; }

.overview-desc blockquote{
  margin:0 40px 24px;
  padding-left:16px;
  border-left:3px solid var(--orange);
  font-style:italic;
  color:#444444;
}
.overview-desc blockquote p{ margin-left:0; margin-right:0; }

.overview-desc a{ color:var(--orange); text-decoration:underline; }
.overview-desc strong,
.overview-desc b{ font-weight:700; }
.overview-desc em,
.overview-desc i{ font-style:italic; }

@media (max-width: 1100px){
  .overview-desc h2, .overview-desc h3, .overview-desc h4,
  .overview-desc ul, .overview-desc ol,
  .overview-desc blockquote{ margin-left:20px; margin-right:20px; }
}

@media (max-width: 900px){
  .overview-desc h2, .overview-desc h3, .overview-desc h4,
  .overview-desc ul, .overview-desc ol,
  .overview-desc blockquote{ margin-left:0; margin-right:0; }
  .overview-desc ul, .overview-desc ol{ font-size:17px; }
  .overview-desc h2{ font-size:24px; }
  .overview-desc h3{ font-size:21px; }
  .overview-desc h4{ font-size:19px; }
}

@media (max-width: 480px){
  .overview-desc ul, .overview-desc ol{ font-size:16px; }
}
</style>

<section class="hero @if(!empty($service->banner_video)) has-video @endif">
    @include('web.layout.navbar')

    @if (!empty($service->banner_video))
        {{-- Video takes priority over the static banner image --}}
        <video
            class="hero-video"
            src="{{ Storage::url($service->banner_video) }}"
            poster="{{ $service->banner_image ? Storage::url($service->banner_image) : '' }}"
            autoplay
            muted
            loop
            playsinline
            preload="auto"
        ></video>
    @elseif ($service->banner_image)
        <div class="hero-slides">
            <img src="{{ Storage::url($service->banner_image) }}" class="hero-slide active"
                alt="{{ $service->banner_title ?? 'Service banner' }}">
        </div>
    @else
        <div class="hero-slides">
            <img src="{{ asset('images/hero_image.jpeg') }}" class="hero-slide active" alt="Service banner">
        </div>
    @endif

    @if (empty($service->banner_video))
        <div class="hero-vignette" aria-hidden="true"></div>
    @endif

    <div class="rig-decor" aria-hidden="true"></div>
    <div class="hero-figure" aria-hidden="true"></div>

    <div class="hero-content">
        <div class="hero-inner">
            <p class="eyebrow">Quality. Safety. Reliability.</p>
            <h1>{{ $service->banner_title }}</h1>
            @if ($service->banner_description)
                <p class="lede">{{ $service->banner_description }}</p>
            @endif
        </div>
    </div>
</section>

    <section class="service-overview">
        <div class="overview-inner">

            <div class="overview-left slide-in-left">
                <p class="overview-eyebrow">Overview</p>
                <h2 class="overview-heading">{{ $service->overview_title }}</h2>

                @if ($service->overview_description)
                    <div class="overview-desc" id="overviewDesc">
                        @foreach (preg_split('/\r\n\r\n|\n\n/', trim($service->overview_description)) as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                    <button type="button" class="overview-view-more" id="overviewViewMoreBtn"
                            data-more-text="View More" data-less-text="View Less">
                        <span class="overview-view-more-label">View More</span>
                        <span class="overview-view-more-arrow">&#9662;</span>
                    </button>
                @endif
            </div>

            <div class="overview-right slide-in-right">
                @if ($service->overview_image)
                    <div class="overview-photo" style="background-image:url('{{ Storage::url($service->overview_image) }}')"
                        role="img" aria-label="{{ $service->overview_title }}"></div>
                @else
                    <div class="overview-photo overview-photo-placeholder"></div>
                @endif
            </div>

        </div>
    </section>
    
@if ($service->process_title || $service->process_description || $service->process_image)
<section class="service-process">
    <div class="process-inner">

        {{-- Left: image --}}
        <div class="process-left slide-in-left">
            @if ($service->process_image)
                <div class="overview-photo" style="background-image:url('{{ Storage::url($service->process_image) }}')"
                    role="img" aria-label="{{ $service->process_title }}"></div>
            @else
                <div class="overview-photo overview-photo-placeholder"></div>
            @endif
        </div>

        {{-- Right: title + description --}}
        <div class="process-right slide-in-right">
            <p class="overview-eyebrow">Our Process</p>
            <h2 class="overview-heading">{{ $service->process_title }}</h2>

            @if ($service->process_description)
                @php
                  $processDesc = $service->process_description;
                  $processIsHtml = $processDesc !== strip_tags($processDesc);
              @endphp
              <div class="overview-desc" id="processDesc">
                  @if ($processIsHtml)
                      {!! $processDesc !!}
                  @else
                      {{-- Old plain-text descriptions saved before CKEditor --}}
                      @foreach (preg_split('/\r\n\r\n|\n\n/', trim($processDesc)) as $paragraph)
                          <p>{{ $paragraph }}</p>
                      @endforeach
                  @endif
              </div>
                <button type="button" class="overview-view-more" id="processViewMoreBtn"
                        data-more-text="View More" data-less-text="View Less">
                    <span class="overview-view-more-label">View More</span>
                    <span class="overview-view-more-arrow">&#9662;</span>
                </button>
            @endif
        </div>

    </div>
</section>
@endif
@if (!empty($service->features))
<section class="features">
  <div class="features-inner">
    <div class="features-top reveal reveal-left">
      <p class="features-eyebrow">Key Features &amp; Benefits</p>
      <h2 class="features-heading">{{ $service->features_heading }}</h2>
    </div>

    <div class="features-grid">
      @foreach ($service->features as $index => $feature)
        <div class="feature-card reveal reveal-delay-{{ min($index, 3) }}">
          @if (!empty($feature['icon']))
            <div class="feature-icon">
              <img src="{{ Storage::url($feature['icon']) }}" alt="{{ $feature['title'] }}">
            </div>
          @endif
          <h3 class="feature-title">{{ $feature['title'] }}</h3>
          <p class="feature-desc">{{ $feature['description'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif
@if (!empty($service->gallery))
@php
  $galleryImages = collect($service->gallery)->filter(fn($item) => !empty($item['image']))->values();
@endphp

@if ($galleryImages->count())
<section class="gallery-section">
  <div class="gallery-inner">
    <div class="gallery-top reveal reveal-left">
      <p class="gallery-eyebrow">Gallery</p>
      <h2 class="gallery-heading">See the Work in Action</h2>
    </div>

    <div class="gallery-marquee">
      <div class="gallery-track" id="galleryTrack">
        {{-- First pass --}}
        @foreach ($galleryImages as $index => $item)
          <div class="gallery-item" data-full="{{ Storage::url($item['image']) }}">
            <img src="{{ Storage::url($item['image']) }}"
                 alt="{{ $service->banner_title ?? 'Service gallery image' }} {{ $index + 1 }}"
                 loading="lazy">
            <div class="gallery-item-overlay">
              <span class="gallery-item-zoom">&#9906;</span>
            </div>
          </div>
        @endforeach

        {{-- Duplicate pass for seamless looping (only needed if scrolling) --}}
        @if ($galleryImages->count() > 1)
          @foreach ($galleryImages as $index => $item)
            <div class="gallery-item" data-full="{{ Storage::url($item['image']) }}" aria-hidden="true">
              <img src="{{ Storage::url($item['image']) }}"
                   alt=""
                   loading="lazy">
              <div class="gallery-item-overlay">
                <span class="gallery-item-zoom">&#9906;</span>
              </div>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </div>
</section>

<div class="gallery-lightbox" id="galleryLightbox">
  <button type="button" class="gallery-lightbox-close" id="galleryLightboxClose" aria-label="Close">&times;</button>
  <button type="button" class="gallery-lightbox-nav gallery-lightbox-prev" id="galleryLightboxPrev" aria-label="Previous">&#10094;</button>
  <img id="galleryLightboxImg" src="" alt="">
  <button type="button" class="gallery-lightbox-nav gallery-lightbox-next" id="galleryLightboxNext" aria-label="Next">&#10095;</button>
</div>
@endif
@endif
@if ($relatedServices->count())
<section class="related-services">
  <div class="related-inner">

    <div class="related-top reveal reveal-left">
      <div class="related-top-text">
        <p class="related-eyebrow">Explore More</p>
        <h2 class="related-heading">Other Inspection Services</h2>
      </div>
      <a href="{{ route('services') }}" class="related-view-all reveal reveal-right">
        View All Services
        <span class="related-view-all-arrow">&#8594;</span>
      </a>
    </div>

    <div class="related-grid">
      @foreach ($relatedServices as $index => $related)
        <a href="{{ route('service.details', $related->slug) }}"
          class="related-card reveal reveal-delay-{{ min($index, 3) }}">
          <div class="related-card-photo">
            <img src="{{ $related->banner_image ? Storage::url($related->banner_image) : asset('images/hero_image.jpeg') }}"
                alt="{{ $related->banner_title }}"
                class="related-card-photo-img">
          </div>
          <div class="related-card-body">
            <h3 class="related-card-title">{{ $related->banner_title }}</h3>
            <p class="related-card-desc">{{ Str::limit($related->banner_description, 90) }}</p>
            <span class="related-card-link">
              View details
              <span class="related-card-arrow">&#8594;</span>
            </span>
          </div>
        </a>
      @endforeach
    </div>

  </div>
</section>
@endif

<section class="inspection-cta">
  <div class="inspection-cta-inner">

    <div class="inspection-cta-photo reveal reveal-left"
         style="background-image:url('{{ asset('images/hero_image.jpeg') }}')"
         role="img" aria-label="Inspector reviewing equipment on site"></div>

    <div class="inspection-cta-content reveal reveal-right reveal-delay-1">
      <p class="inspection-cta-eyebrow">Start a Conversation</p>
      <h2 class="inspection-cta-heading">Need a Reliable Inspection<br>Solution?</h2>
      <p class="inspection-cta-desc">
        Tell us about your equipment, inspection requirement or project scope.
        Our team can help identify the appropriate inspection service and
        support your next operation.
      </p>
      <a href="{{ url('/contact') }}" class="inspection-cta-btn">Request a Consultation</a>
    </div>

  </div>
</section>
@include('web.layout.footer')

    <script>
document.addEventListener('DOMContentLoaded', function () {

    // ===== View More / View Less (Overview + Process) =====
    function initViewMore(descId, btnId) {
        const desc = document.getElementById(descId);
        const btn = document.getElementById(btnId);
        if (!desc || !btn) return;

        if (desc.scrollHeight <= desc.clientHeight + 5) {
            btn.style.display = 'none';
            desc.classList.add('expanded'); // removes the fade when text is short
            return;
        }

        const label = btn.querySelector('.overview-view-more-label');
        btn.addEventListener('click', function () {
            const isExpanded = desc.classList.toggle('expanded');
            btn.classList.toggle('expanded', isExpanded);
            label.textContent = isExpanded ? btn.dataset.lessText : btn.dataset.moreText;
            if (!isExpanded) desc.scrollTop = 0;
        });
    }

    initViewMore('overviewDesc', 'overviewViewMoreBtn');
    initViewMore('processDesc', 'processViewMoreBtn');

    // ===== Reveal + slide-in from sides (Overview, Process, Features, Gallery, Related, CTA) =====
    const animatedEls = document.querySelectorAll('.reveal, .slide-in-left, .slide-in-right');

    if ('IntersectionObserver' in window && animatedEls.length) {
        const observer = new IntersectionObserver(function (entries, obs) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    obs.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -60px 0px'
        });

        animatedEls.forEach(function (el) { observer.observe(el); });
    } else {
        animatedEls.forEach(function (el) { el.classList.add('in-view'); });
    }

    // ===== Gallery marquee + lightbox =====
    const galleryTrack = document.getElementById('galleryTrack');
    const lightbox = document.getElementById('galleryLightbox');

    if (galleryTrack && lightbox) {
        const allItems = Array.from(galleryTrack.querySelectorAll('.gallery-item'));
        const uniqueItems = allItems.filter(el => !el.hasAttribute('aria-hidden'));
        const uniqueCount = uniqueItems.length;

        galleryTrack.style.setProperty('--gallery-duration', Math.max(20, uniqueCount * 6) + 's');
        if (uniqueCount <= 1) galleryTrack.style.animation = 'none';

        const lightboxImg = document.getElementById('galleryLightboxImg');
        const closeBtn = document.getElementById('galleryLightboxClose');
        const prevBtn = document.getElementById('galleryLightboxPrev');
        const nextBtn = document.getElementById('galleryLightboxNext');
        const uniqueUrls = uniqueItems.map(el => el.getAttribute('data-full'));
        let currentIndex = 0;

        function openLightbox(url) {
            currentIndex = Math.max(0, uniqueUrls.indexOf(url));
            lightboxImg.src = uniqueUrls[currentIndex];
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        }

        function showRelative(offset) {
            currentIndex = (currentIndex + offset + uniqueUrls.length) % uniqueUrls.length;
            lightboxImg.src = uniqueUrls[currentIndex];
        }

        allItems.forEach(function (item) {
            item.addEventListener('click', function () {
                openLightbox(item.getAttribute('data-full'));
            });
        });

        closeBtn.addEventListener('click', closeLightbox);
        prevBtn.addEventListener('click', function () { showRelative(-1); });
        nextBtn.addEventListener('click', function () { showRelative(1); });

        lightbox.addEventListener('click', function (e) {
            if (e.target === lightbox) closeLightbox();
        });

        document.addEventListener('keydown', function (e) {
            if (!lightbox.classList.contains('active')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') showRelative(-1);
            if (e.key === 'ArrowRight') showRelative(1);
        });
    }
});
</script>
@endsection
