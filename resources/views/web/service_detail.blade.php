@extends('web.layout.app')

@section('content')
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
            background:
                radial-gradient(ellipse at 70% 25%, rgba(120, 190, 190, 0.35), transparent 20%),
                linear-gradient(100deg, rgba(10, 20, 20, 0.82) 0%, rgba(10, 20, 20, 0.35) 42%, rgba(60, 90, 90, 0.15) 60%, rgba(10, 20, 20, 0.55) 100%);
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
            object-fit: cover;
            opacity: 0;
            transition: opacity 1.2s ease;
        }

        .hero-slide.active {
            opacity: 1;
            z-index: 1;
        }

        .hero .rig-decor {
            z-index: 2;
        }

        .hero .hero-content {
            position: relative;
            z-index: 5;
            flex: 1;
            display: flex;
            align-items: center;
            padding: 0 90px;
        }

        .hero .hero-inner {
            max-width: 760px;
        }

        .hero .eyebrow {
            color: var(--cream);
            font-size: 19px;
            font-weight: 600;
            margin-bottom: 22px;
            letter-spacing: 0.2px;
        }

        .hero h1 {
            color: var(--white);
            font-size: clamp(28px, 4.2vw, 60px);
            line-height: 1.20;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin: 0 70px 0;
            text-shadow: 0 2px 24px rgba(0, 0, 0, 0.35);
            word-break: break-word;
        }

        .hero .lede {
            color: var(--white);
            font-size: 25px;
            font-weight: 700px;
            max-width: 640px;
            margin: 0 70px 20px;
        }

        @media (max-width: 1024px) {
            .hero .hero-content {
                padding: 0 50px;
            }
        }

        @media (max-width: 900px) {
            .hero .hero-content {
                padding: 0 24px;
            }
        }

        @media (max-width: 600px) {
            .hero {
                min-height: auto;
            }

            .hero .hero-content {
                padding: 60px 20px 60px;
                align-items: flex-start;
            }

            .hero .eyebrow {
                font-size: 15px;
                margin-bottom: 14px;
            }

            .hero h1 {
                margin-bottom: 18px;
            }

            .hero .lede {
                margin-bottom: 26px;
            }
        }

        @media (max-width: 380px) {
            .hero .hero-content {
                padding: 48px 16px 48px;
            }

            .hero .eyebrow {
                font-size: 14px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .hero-slide {
                transition: none;
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

        .overview-desc p {
            font-size: 22px;
            line-height: 1.75;
            color: #111111;
            margin: 0 40px 30px;
        }

        .overview-desc p:last-child {
            margin-bottom: 0;
        }
        .overview-right{
        flex:1;
        max-width:760px; /* wider */
        }

        .overview-photo{
            width:100%;
            aspect-ratio: 760 / 500; /* wider, same height as before */
            border-radius:20px;
            background-color:#e8e8e8;
            background-position:center;
            background-size:cover;
            background-repeat:no-repeat;
            box-shadow:0 20px 44px rgba(0,0,0,0.14);
            transition:transform 0.6s cubic-bezier(0.16,1,0.3,1), box-shadow 0.6s ease;
            margin-top:30px;
            }

        .overview-photo:hover {
            transform: translateY(-6px);
            box-shadow: 0 28px 56px rgba(0, 0, 0, 0.2);
        }

        .overview-photo-placeholder {
            background-image: url('{{ asset('images/placeholder.jpg') }}');
        }

        /* ===== Reveal animation ===== */
        .overview-left.reveal {
            opacity: 0;
            transform: translateX(-40px);
            transition: opacity 0.9s cubic-bezier(0.16, 1, 0.3, 1), transform 0.9s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .overview-right.reveal {
            opacity: 0;
            transform: translateX(40px) scale(0.97);
            transition: opacity 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.15s, transform 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.15s;
        }

        .overview-left.reveal.in-view,
        .overview-right.reveal.in-view {
            opacity: 1;
            transform: translateX(0) scale(1);
        }

        @media (prefers-reduced-motion: reduce) {
            .overview-photo {
                transition: none;
            }

            .overview-left.reveal,
            .overview-right.reveal,
            .overview-left.reveal.in-view,
            .overview-right.reveal.in-view {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
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
        }


        .process{
  position:relative;
  background:#ffffff;
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:60px 60px 60px;
}

.process *{ box-sizing:border-box; }

.process-inner{ margin:0 auto; }

.process-top{ margin-bottom:40px; }

.process-eyebrow{
  color:var(--orange);
  font-weight:700;
  font-size:19px;
  margin:0 0 14px;
}

.process-heading{
  font-size:clamp(30px, 3.6vw, 46px);
  line-height:1.2;
  font-weight:400;
  color:#111111;
  margin-left:30px;
}

.process-video{
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

.process-video:hover{ box-shadow:0 32px 64px rgba(0,0,0,0.28); }

.process-video img,
.process-video video{
  display:block;
  width:100%;
  height:100%;
  object-fit:cover;
  transition:transform 0.8s cubic-bezier(0.16,1,0.3,1);
}

.process-video:hover img{ transform:scale(1.08); }

.process-play{
  position:absolute;
  top:50%; left:50%;
  transform:translate(-50%, -50%) scale(1);
  width:78px; height:78px;
  border-radius:50%;
  background:rgba(255,255,255,0.95);
  display:flex; align-items:center; justify-content:center;
  box-shadow:0 12px 30px rgba(0,0,0,0.25);
  transition:transform 0.35s cubic-bezier(0.34,1.56,0.64,1), background 0.25s ease;
  z-index:2;
}

.process-play::before{
  content:"";
  width:0; height:0;
  border-style:solid;
  border-width:12px 0 12px 20px;
  border-color:transparent transparent transparent var(--orange);
  margin-left:6px;
}

.process-video:hover .process-play{
  transform:translate(-50%, -50%) scale(1.12);
  background:var(--orange);
}
.process-video:hover .process-play::before{
  border-color:transparent transparent transparent var(--white);
}

.process-play-ring{
  position:absolute;
  top:50%; left:50%;
  transform:translate(-50%, -50%);
  width:78px; height:78px;
  border-radius:50%;
  border:2px solid rgba(255,255,255,0.7);
  animation: processPulse 2.4s ease-out infinite;
  z-index:1;
}

@keyframes processPulse{
  0%{ transform:translate(-50%, -50%) scale(1); opacity:0.8; }
  100%{ transform:translate(-50%, -50%) scale(1.9); opacity:0; }
}

.process-video-overlay{
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

.process-video:hover .process-video-overlay{
  opacity:1;
  transform:translateY(0);
}

.process-video-title{
  color:var(--white);
  font-size:22px;
  font-weight:700;
  margin:0 0 8px;
}

.process-video-desc{
  color:rgba(255,255,255,0.9);
  font-size:15px;
  line-height:1.5;
  margin:0;
  max-width:600px;
}

.process-video.is-playing .process-play,
.process-video.is-playing .process-play-ring,
.process-video.is-playing .process-video-overlay{
  display:none;
}

/* Carousel */
.process-carousel{
  position:relative;
  display:flex;
  align-items:center;
  gap:20px;
}

.process-track-wrap{
  flex:1;
  overflow:hidden;
  max-width:900px;
  margin:0 auto;
}

.process-track{
  display:flex;
  gap:28px;
  transition:transform 0.5s cubic-bezier(0.16,1,0.3,1);
}

.process-card{
  position:relative;
  flex:0 0 calc((100% - 56px) / 3);
  aspect-ratio: 4 / 3;
  border-radius:18px;
  overflow:hidden;
  cursor:pointer;
  box-shadow:0 14px 34px rgba(0,0,0,0.14);
  transition:box-shadow 0.3s ease;
}

.process-card:hover{ box-shadow:0 22px 46px rgba(0,0,0,0.22); }

.process-card img,
.process-card video{
  display:block;
  width:100%;
  height:100%;
  object-fit:cover;
  transition:transform 0.7s cubic-bezier(0.16,1,0.3,1);
}

.process-card:hover img{ transform:scale(1.12); }

.process-card-play{
  position:absolute;
  top:50%; left:50%;
  transform:translate(-50%, -50%) scale(1);
  width:48px; height:48px;
  border-radius:50%;
  background:rgba(255,255,255,0.95);
  display:flex; align-items:center; justify-content:center;
  box-shadow:0 8px 20px rgba(0,0,0,0.25);
  transition:transform 0.3s cubic-bezier(0.34,1.56,0.64,1), background 0.2s ease;
  z-index:2;
}

.process-card-play::before{
  content:"";
  width:0; height:0;
  border-style:solid;
  border-width:8px 0 8px 13px;
  border-color:transparent transparent transparent var(--orange);
  margin-left:4px;
}

.process-card:hover .process-card-play{
  transform:translate(-50%, -50%) scale(1.12);
  background:var(--orange);
}
.process-card:hover .process-card-play::before{
  border-color:transparent transparent transparent var(--white);
}

.process-card-overlay{
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

.process-card:hover .process-card-overlay{
  opacity:1;
  transform:translateY(0);
}

.process-card-title{
  color:var(--white);
  font-size:17px;
  font-weight:700;
  margin:0 0 6px;
}

.process-card-desc{
  color:rgba(255,255,255,0.85);
  font-size:13.5px;
  line-height:1.45;
  margin:0;
}

.process-nav{
  flex:0 0 auto;
  width:44px; height:44px;
  border-radius:50%;
  background:#ffffff;
  border:1px solid #e6e6e6;
  display:flex; align-items:center; justify-content:center;
  cursor:pointer;
  color:#111111;
  font-size:18px;
  transition:background 0.2s ease, color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
}

.process-nav:hover{
  background:var(--orange);
  border-color:var(--orange);
  color:var(--white);
  transform:translateY(-2px);
}

.process-nav:disabled{
  opacity:0.35;
  cursor:default;
  pointer-events:none;
}

/* ===== Mobile responsiveness ===== */
@media (max-width: 900px){
  .process{ padding:64px 24px 70px; }
  .process-video{ aspect-ratio:4/3; margin-bottom:40px; }
  .process-card{ flex:0 0 calc((100% - 28px) / 2); }
}

@media (max-width: 600px){
  .process-card{ flex:0 0 100%; }
  .process-video-title{ font-size:18px; }
  .process-video-desc{ font-size:13.5px; }
}

@media (max-width: 480px){
  .process{ padding:48px 16px 56px; }
  .process-heading{ font-size:26px; }
  .process-nav{ width:38px; height:38px; font-size:15px; }
}

@media (prefers-reduced-motion: reduce){
  .process-play-ring{ animation:none; }
  .process-video img, .process-card img{ transition:none; }
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
  background-color:#e8e8e8;
  background-position:center;
  background-size:cover;
  background-repeat:no-repeat;
  transition:transform 0.7s cubic-bezier(0.16,1,0.3,1);
}

.related-card:hover .related-card-photo{
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
    </style>

    <section class="hero">
        @include('web.layout.navbar')

        @if ($service->banner_image)
            <div class="hero-slides">
                <img src="{{ Storage::url($service->banner_image) }}" class="hero-slide active"
                    alt="{{ $service->banner_title ?? 'Service banner' }}">
            </div>
        @else
            <div class="hero-slides">
                <img src="{{ asset('images/hero_image.jpeg') }}" class="hero-slide active" alt="Service banner">
            </div>
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

            <div class="overview-left reveal reveal-left">
                <p class="overview-eyebrow">Overview</p>
                <h2 class="overview-heading">{{ $service->overview_title }}</h2>

                @if ($service->overview_description)
                    <div class="overview-desc">
                        @foreach (preg_split('/\r\n\r\n|\n\n/', trim($service->overview_description)) as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="overview-right reveal reveal-right">
                @if ($service->overview_image)
                    <div class="overview-photo" style="background-image:url('{{ Storage::url($service->overview_image) }}')"
                        role="img" aria-label="{{ $service->overview_title }}"></div>
                @else
                    <div class="overview-photo overview-photo-placeholder"></div>
                @endif
            </div>

        </div>
    </section>
    @php
  $processSteps = collect($service->process ?? [])->filter(fn($step) => !empty($step['video']))->values();
  $mainProcess = $processSteps->first();
  $otherProcesses = $processSteps->slice(1)->values();
@endphp

@if ($mainProcess)
<section class="process">
  <div class="process-inner">
    <div class="process-top reveal reveal-left">
      <p class="process-eyebrow">Our Process</p>
      <h2 class="process-heading">Step by step, from<br>inspection to report</h2>
    </div>

    <div class="process-video reveal reveal-delay-1"
         id="processVideo"
         data-video-url="{{ Storage::url($mainProcess['video']) }}"
         data-title="Step 1"
         data-desc="{{ $mainProcess['description'] }}">
      <img src="{{ $mainProcess['thumbnail'] ? Storage::url($mainProcess['thumbnail']) : asset('images/process_placeholder.jpg') }}" alt="Step 1">
      <div class="process-play-ring"></div>
      <div class="process-play" aria-label="Play video"></div>
      <div class="process-video-overlay">
        <div>
          <p class="process-video-title">Step 1</p>
          <p class="process-video-desc">{{ $mainProcess['description'] }}</p>
        </div>
      </div>
    </div>

    @if ($otherProcesses->count())
      <div class="process-carousel reveal reveal-delay-2">
        <button type="button" class="process-nav" id="processPrev" aria-label="Previous">&#10094;</button>

        <div class="process-track-wrap">
          <div class="process-track" id="processTrack">
            @foreach ($otherProcesses as $index => $step)
              <div class="process-card"
                   data-video-url="{{ Storage::url($step['video']) }}"
                   data-title="Step {{ $index + 2 }}"
                   data-desc="{{ $step['description'] }}">
                <img src="{{ $step['thumbnail'] ? Storage::url($step['thumbnail']) : asset('images/process_placeholder.jpg') }}" alt="Step {{ $index + 2 }}">
                <div class="process-card-play"></div>
                <div class="process-card-overlay">
                  <div>
                    <p class="process-card-title">Step {{ $index + 2 }}</p>
                    <p class="process-card-desc">{{ Str::limit($step['description'], 90) }}</p>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <button type="button" class="process-nav" id="processNext" aria-label="Next">&#10095;</button>
      </div>
    @endif
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
          <div class="related-card-photo"
               style="background-image:url('{{ $related->banner_image ? Storage::url($related->banner_image) : asset('images/hero_image.jpeg') }}')"></div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const revealEls = document.querySelectorAll('.reveal');
            if ('IntersectionObserver' in window && revealEls.length) {
                const revealObserver = new IntersectionObserver(function(entries, obs) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('in-view');
                            obs.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.15,
                    rootMargin: '0px 0px -60px 0px'
                });

                revealEls.forEach(function(el) {
                    revealObserver.observe(el);
                });
            } else {
                revealEls.forEach(function(el) {
                    el.classList.add('in-view');
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
  const track = document.getElementById('processTrack');
  const prevBtn = document.getElementById('processPrev');
  const nextBtn = document.getElementById('processNext');

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

  const processVideo = document.getElementById('processVideo');

  function loadMainProcessVideo(source) {
    if (!processVideo) return;

    const videoUrl = source.getAttribute('data-video-url');
    const title = source.getAttribute('data-title') || '';
    const desc = source.getAttribute('data-desc') || '';

    const titleEl = processVideo.querySelector('.process-video-title');
    const descEl = processVideo.querySelector('.process-video-desc');
    if (titleEl) titleEl.textContent = title;
    if (descEl) descEl.textContent = desc;

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

    const existingVideo = processVideo.querySelector('video');
    if (existingVideo) existingVideo.remove();

    processVideo.prepend(videoEl);
    processVideo.classList.add('is-playing');

    videoEl.addEventListener('ended', function () {
      processVideo.classList.remove('is-playing');
    });

    processVideo.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  if (processVideo) {
    processVideo.addEventListener('click', function () {
      loadMainProcessVideo(processVideo);
    });
  }

  document.querySelectorAll('.process-card').forEach(function (card) {
    card.addEventListener('click', function () {
      loadMainProcessVideo(card);
    });
  });
});
    </script>
@endsection
