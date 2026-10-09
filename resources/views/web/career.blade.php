@extends('web.layout.app')
@section('title', $careerPage->meta_title ?? 'Careers - Energy Inspection Services Ltd')
@section('meta_description', $careerPage->meta_description ?? 'Current job openings at Energy Inspection Services Ltd. Read the role details and send your CV online to join our oil and gas inspection team.')
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

/* ===== Hero (same as the other pages) ===== */
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

.hero-slides{ position:absolute; inset:0; z-index:0; }

.hero-slide{
  position:absolute;
  inset:0;
  width:100%;
  height:100%;
  object-fit:cover;
  object-position:center 30%;
  opacity:0;
  transform:scale(1.06);
  transition:opacity 1.4s ease, transform 8s ease;
}

.hero-slide.active{
  opacity:1;
  z-index:1;
  transform:scale(1);
}
.hero-video{
  position:absolute;
  inset:0;
  width:100%;
  height:100%;
  object-fit:cover;
  object-position:center;
  z-index:1;
  display:block;
}

/* When a video is playing, drop the dark overlays */
.hero.has-video::before,
.hero.has-video::after{ display:none; }

.hero.has-video h1,
.hero.has-video .eyebrow,
.hero.has-video .lede{ text-shadow:0 2px 12px rgba(0,0,0,0.75); }
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
  margin:0 0 22px;
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
  line-height:1.15;
  font-weight:800;
  letter-spacing:-0.5px;
  margin:0 0 20px;
  text-shadow:0 4px 32px rgba(0,0,0,0.55);
  word-break:break-word;
}

.hero .lede{
  color:rgba(255,255,255,0.92);
  font-size:clamp(16px, 1.6vw, 20px);
  font-weight:500;
  max-width:640px;
  margin:0;
  line-height:1.55;
}

@media (max-width: 1024px){
  .hero .hero-content{ padding:0 50px 70px; }
}

@media (max-width: 900px){
  .hero .hero-content{ padding:0 24px 56px; }
}

@media (max-width: 600px){
  .hero{ min-height:auto; }
  .hero .hero-content{
    padding:110px 20px 40px;
    align-items:flex-start;
  }
  .hero .eyebrow{ font-size:19px; margin-bottom:12px; }
  .hero h1{ margin-bottom:16px; font-size:clamp(26px, 7vw, 34px); }
  .hero-vignette{ box-shadow:inset 0 0 80px rgba(0,0,0,0.28); }
}

@media (max-width: 380px){
  .hero .hero-content{ padding:100px 16px 32px; }
  .hero .eyebrow{ font-size:17px; }
}

/* ===== Intro ===== */
.career-intro{
  position:relative;
  background:#ffffff;
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:90px 60px 80px;
}

.career-intro *{ box-sizing:border-box; }

.career-intro-inner{
  display:flex;
  align-items:flex-start;
  gap:64px;
  margin:0 auto;
}

.career-intro-left{
  flex:0 0 50%;
  max-width:50%;
}

.career-intro-eyebrow{
  color:var(--orange);
  font-weight:700;
  font-size:22px;
  margin:0 0 16px;
}

.career-intro-heading{
  font-size:clamp(28px, 3.6vw, 52px);
  line-height:1.22;
  font-weight:600;
  color:#111111;
  margin:0;
}

.career-intro-divider{
  flex:0 0 1px;
  align-self:stretch;
  background:linear-gradient(to bottom, transparent, #d8d8d8 12%, #d8d8d8 88%, transparent);
}

.career-intro-right{
  flex:1;
  min-width:0;
  padding-top:6px;
}

.career-intro-desc{
  font-size:19px;
  line-height:1.7;
  color:#111111;
  margin:0;
  max-width:640px;
}
.career-intro-desc p{ margin:0 0 14px; }
.career-intro-desc ul,
.career-intro-desc ol{ margin:0 0 14px; padding-left:22px; }
.career-intro-desc > :last-child{ margin-bottom:0; }
/* ===== Openings ===== */
.openings{
  position:relative;
  background:#faf8f5;
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:90px 60px 110px;
  scroll-margin-top:90px;
}

.openings *{ box-sizing:border-box; }

.openings-top{
  text-align:center;
  margin-bottom:44px;
}

.openings-eyebrow{
  color:var(--orange);
  font-weight:700;
  font-size:15px;
  letter-spacing:1px;
  text-transform:uppercase;
  margin:0 0 14px;
}

.openings-heading{
  font-size:clamp(28px, 3.6vw, 42px);
  line-height:1.2;
  font-weight:700;
  color:#111111;
  margin:0 0 16px;
}

.openings-sub{
  font-size:15.5px;
  line-height:1.6;
  color:#666666;
  margin:0 auto;
  max-width:560px;
}

.career-flash{
  max-width:1240px;
  margin:0 auto 24px;
  background:#e9f9ee;
  border:1px solid #b7e6c4;
  color:#1e7a3c;
  border-radius:12px;
  padding:16px 22px;
  font-size:15px;
  font-weight:600;
}

.openings-list{
  max-width:1240px;
  margin:0 auto;
  background:#ffffff;
  border:1px solid #ececec;
  border-radius:22px;
  box-shadow:0 8px 22px rgba(0,0,0,0.05);
  overflow:hidden;
}

.opening + .opening{ border-top:1px solid #ececec; }

.opening-head{
  display:flex;
  align-items:center;
  gap:24px;
  padding:0 32px;
  transition:background 0.25s ease;
}

.opening-head:hover,
.opening.open .opening-head{ background:#fffaf5; }

.opening-toggle{
  flex:1;
  min-width:0;
  display:grid;
  grid-template-columns:minmax(0, 1.5fr) minmax(0, 1fr) 22px;
  align-items:center;
  gap:24px;
  padding:28px 0;
  background:none;
  border:0;
  font:inherit;
  color:inherit;
  text-align:left;
  cursor:pointer;
}

.opening-title{
  font-size:20px;
  line-height:1.3;
  font-weight:700;
  color:#111111;
  overflow-wrap:anywhere;
  transition:color 0.2s ease;
}

.opening-toggle:hover .opening-title,
.opening.open .opening-title{ color:var(--orange-dark); }

.opening-loc{
  display:inline-flex;
  align-items:center;
  gap:8px;
  min-width:0;
  font-size:15.5px;
  color:#5a5a5a;
  overflow-wrap:anywhere;
}

.opening-loc svg{ flex:0 0 auto; color:var(--orange); }

.opening-chevron{
  display:flex;
  color:#8a8a8a;
  transition:transform 0.3s ease, color 0.2s ease;
}

.opening.open .opening-chevron{
  transform:rotate(180deg);
  color:var(--orange);
}

.opening-apply{
  flex:0 0 auto;
  padding:12px 24px;
  border-radius:30px;
  border:1.5px solid var(--orange);
  background:#ffffff;
  color:var(--orange-dark);
  font-family:inherit;
  font-size:15px;
  font-weight:700;
  white-space:nowrap;
  cursor:pointer;
  transition:background 0.2s ease, color 0.2s ease, transform 0.15s ease;
}

.opening-apply:hover{
  background:var(--orange);
  color:#ffffff;
  transform:translateY(-2px);
}

.opening-toggle:focus-visible,
.opening-apply:focus-visible,
.opening-body-cta:focus-visible,
.career-modal-close:focus-visible,
.cf-submit:focus-visible,
.career-success-close:focus-visible{
  outline:3px solid rgba(232,121,45,0.5);
  outline-offset:3px;
}

/* expanding panel */
.opening-panel{
  display:grid;
  grid-template-rows:0fr;
  transition:grid-template-rows 0.4s cubic-bezier(0.16,1,0.3,1);
}

.opening.open .opening-panel{ grid-template-rows:1fr; }

.opening-panel-inner{
  overflow:hidden;
  visibility:hidden;
  transition:visibility 0s 0.4s;
}

.opening.open .opening-panel-inner{
  visibility:visible;
  transition-delay:0s;
}

.opening-body{
  padding:22px 32px 34px;
  max-width:920px;
}

.career-desc{
  font-size:16px;
  line-height:1.7;
  color:#3d3d3d;
  overflow-wrap:anywhere;
}

.career-desc p{ margin:0 0 14px; }

.career-desc h2,
.career-desc h3,
.career-desc h4{
  color:#111111;
  line-height:1.3;
  margin:22px 0 10px;
}

.career-desc h2{ font-size:20px; }
.career-desc h3{ font-size:18px; }
.career-desc h4{ font-size:16px; }

.career-desc > :first-child{ margin-top:0; }

.career-desc ul,
.career-desc ol{
  margin:0 0 14px;
  padding-left:22px;
}

.career-desc li{ margin-bottom:6px; }
.career-desc li::marker{ color:var(--orange); }

.career-desc a{ color:var(--orange-dark); }

.career-desc blockquote{
  margin:0 0 14px;
  padding:12px 18px;
  background:#fff7f0;
  border-left:3px solid var(--orange);
  border-radius:0 10px 10px 0;
}

.career-desc-empty{
  font-size:15.5px;
  color:#666666;
  margin:0;
}

.opening-body-cta{
  display:inline-flex;
  align-items:center;
  gap:10px;
  margin-top:10px;
  padding:14px 28px;
  border:none;
  border-radius:30px;
  background:var(--orange);
  color:#ffffff;
  font-family:inherit;
  font-size:15.5px;
  font-weight:700;
  cursor:pointer;
  transition:background 0.2s ease, transform 0.15s ease;
}

.opening-body-cta:hover{
  background:var(--orange-dark);
  transform:translateY(-2px);
}

.openings-empty{
  max-width:640px;
  margin:0 auto;
  background:#ffffff;
  border:1px solid #ececec;
  border-radius:22px;
  padding:48px 32px;
  text-align:center;
}

.openings-empty h3{
  font-size:20px;
  font-weight:700;
  color:#111111;
  margin:0 0 10px;
}

.openings-empty p{
  font-size:15.5px;
  line-height:1.6;
  color:#666666;
  margin:0;
}

/* ===== Reveal ===== */
.reveal{
  opacity:0;
  transform:translateY(32px);
  transition:opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}
.reveal.reveal-left{ transform:translateX(-40px); }
.reveal.reveal-right{ transform:translateX(40px); transition-delay:0.15s; }
.reveal.in-view{
  opacity:1;
  transform:translateY(0) translateX(0);
}

/* ===== Apply modal ===== */
html.career-lock{ overflow:hidden; }

.career-modal{
  border:0;
  padding:0;
  width:min(880px, calc(100vw - 32px));
  max-width:none;
  max-height:calc(100vh - 32px);
  max-height:calc(100dvh - 32px);
  overflow:auto;
  border-radius:22px;
  background:#ffffff;
  color:#111111;
  box-shadow:0 30px 80px rgba(0,0,0,0.35);
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
}

.career-modal *{ box-sizing:border-box; }

.career-modal::backdrop{
  background:rgba(10,20,20,0.62);
  backdrop-filter:blur(3px);
}

.career-modal[open]{ animation:careerModalIn 0.3s cubic-bezier(0.16,1,0.3,1); }

@keyframes careerModalIn{
  from{ opacity:0; transform:translateY(18px) scale(0.98); }
  to{ opacity:1; transform:translateY(0) scale(1); }
}

.career-modal-inner{
  position:relative;
  padding:44px 48px 46px;
}

.career-modal-close{
  position:absolute;
  top:18px;
  right:18px;
  width:42px;
  height:42px;
  border-radius:50%;
  border:1px solid #e6e6e6;
  background:#ffffff;
  color:#555555;
  display:flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
  transition:border-color 0.2s ease, color 0.2s ease;
}

.career-modal-close:hover{
  border-color:var(--orange);
  color:var(--orange);
}

.career-modal-title{
  font-size:clamp(26px, 3vw, 34px);
  line-height:1.2;
  font-weight:800;
  margin:0 0 20px;
  padding-right:48px;
}

.career-modal-title::after{
  content:"";
  display:block;
  width:54px;
  height:3px;
  margin-top:14px;
  background:var(--orange);
  border-radius:2px;
}

.career-modal-note{
  font-size:14.5px;
  line-height:1.6;
  color:#5a5a5a;
  margin:0 0 26px;
}

.cf-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:20px 24px;
}

.cf-field{
  display:flex;
  flex-direction:column;
  min-width:0;
}

.cf-full{ grid-column:1 / -1; }

.cf-field label{
  font-size:13.5px;
  font-weight:700;
  color:#333333;
  margin-bottom:7px;
}

.cf-req{ color:var(--orange); }

.cf-field input,
.cf-field select,
.cf-field textarea{
  width:100%;
  padding:14px 16px;
  border:1.5px solid #EAD5BC;
  border-radius:12px;
  font-size:15px;
  font-family:inherit;
  color:#111111;
  background:#ffffff;
  outline:none;
  transition:border-color 0.25s ease, box-shadow 0.25s ease;
}

.cf-field input::placeholder,
.cf-field textarea::placeholder{ color:#a3a3a3; }

.cf-field input:focus,
.cf-field select:focus,
.cf-field textarea:focus{
  border-color:var(--orange);
  box-shadow:0 0 0 4px rgba(232,121,45,0.12);
}

.cf-field input[readonly]{
  background:#faf6f1;
  color:#555555;
  cursor:default;
}

.cf-field select{
  appearance:none;
  -webkit-appearance:none;
  padding-right:44px;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23E8792D' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
  background-repeat:no-repeat;
  background-position:right 16px center;
  background-size:16px 16px;
  cursor:pointer;
}

.cf-field textarea{
  resize:vertical;
  min-height:130px;
}

.cf-field input[type="file"]{
  padding:11px 14px;
  border-style:dashed;
  cursor:pointer;
}

.cf-field input[type="file"]::file-selector-button{
  margin-right:14px;
  padding:9px 16px;
  border:0;
  border-radius:20px;
  background:rgba(232,121,45,0.12);
  color:var(--orange-dark);
  font-family:inherit;
  font-size:14px;
  font-weight:700;
  cursor:pointer;
}

.cf-field .is-invalid{
  border-color:#e0554a;
  background:#fff8f7;
}

.cf-error{
  color:#c0392b;
  font-size:12.5px;
  font-weight:600;
  margin-top:6px;
}

.cf-error:empty{ display:none; }

/* hidden trap field for spam bots */
.cf-hp{
  position:absolute;
  left:-9999px;
  width:1px;
  height:1px;
  overflow:hidden;
}

.cf-actions{
  display:flex;
  align-items:center;
  gap:16px;
  flex-wrap:wrap;
  margin-top:28px;
}

.cf-submit{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  padding:17px 40px;
  border:none;
  border-radius:30px;
  background:linear-gradient(135deg, var(--orange) 0%, var(--orange-dark) 100%);
  color:#ffffff;
  font-family:inherit;
  font-size:15px;
  font-weight:700;
  letter-spacing:0.6px;
  text-transform:uppercase;
  cursor:pointer;
  box-shadow:0 14px 30px rgba(232,121,45,0.28);
  transition:transform 0.25s ease, box-shadow 0.25s ease, opacity 0.2s ease;
}

.cf-submit:hover{
  transform:translateY(-3px);
  box-shadow:0 20px 40px rgba(232,121,45,0.38);
}

.cf-submit:disabled{
  opacity:0.6;
  cursor:not-allowed;
  transform:none;
  box-shadow:none;
}

.cf-status{
  font-size:14px;
  font-weight:600;
  color:#a11f1f;
  margin:0;
}

.cf-status:empty{ display:none; }

/* success state */
.career-success{
  display:none;
  text-align:center;
  padding:12px 0 4px;
}

.career-modal.sent .career-form,
.career-modal.sent .career-modal-note{ display:none; }

.career-modal.sent .career-success{ display:block; }

.career-success-icon{
  width:64px;
  height:64px;
  margin:0 auto 18px;
  border-radius:50%;
  background:#e9f9ee;
  color:#1e7a3c;
  display:flex;
  align-items:center;
  justify-content:center;
}

.career-success h3{
  font-size:22px;
  font-weight:700;
  margin:0 0 10px;
}

.career-success p{
  font-size:15.5px;
  line-height:1.6;
  color:#5a5a5a;
  margin:0 auto 24px;
  max-width:440px;
}

.career-success-close{
  padding:13px 30px;
  border-radius:30px;
  border:1.5px solid var(--orange);
  background:#ffffff;
  color:var(--orange-dark);
  font-family:inherit;
  font-size:15px;
  font-weight:700;
  cursor:pointer;
  transition:background 0.2s ease, color 0.2s ease;
}

.career-success-close:hover{
  background:var(--orange);
  color:#ffffff;
}

/* ===== Responsive ===== */
@media (max-width: 1024px){
  .career-intro{ padding:64px 40px 60px; }
  .career-intro-inner{ gap:44px; }
  .openings{ padding:70px 40px 90px; }
}

@media (max-width: 900px){
  .career-intro{ padding:56px 24px 52px; }
  .career-intro-inner{
    flex-direction:column;
    gap:28px;
  }
  .career-intro-left,
  .career-intro-right{
    flex:none;
    max-width:none;
    width:100%;
  }
  .career-intro-divider{
    flex:none;
    align-self:auto;
    width:100%;
    height:1px;
    background:linear-gradient(to right, transparent, #d8d8d8 8%, #d8d8d8 92%, transparent);
  }
  .career-intro-right{ padding-top:0; }
  .career-intro-desc{ max-width:none; font-size:17px; }

  .openings{ padding:60px 24px 76px; }
  .opening-head{ padding:0 24px; gap:18px; }
  .opening-toggle{ gap:18px; padding:24px 0; }
  .opening-body{ padding:20px 24px 28px; }
}

@media (max-width: 700px){
  .opening-head{
    flex-direction:column;
    align-items:stretch;
    gap:0;
    padding:0 20px 20px;
  }
  .opening-toggle{
    grid-template-columns:minmax(0, 1fr) 22px;
    grid-template-areas:"title chev" "loc chev";
    gap:8px 14px;
    padding:22px 0 16px;
  }
  .opening-title{ grid-area:title; font-size:18px; }
  .opening-loc{ grid-area:loc; font-size:14.5px; }
  .opening-chevron{ grid-area:chev; align-self:start; margin-top:3px; }
  .opening-apply{ align-self:flex-start; }
  .opening-body{ padding:18px 20px 24px; }
  .openings-list{ border-radius:18px; }

  .career-modal{ border-radius:18px; }
  .career-modal-inner{ padding:34px 20px 30px; }
  .cf-grid{ grid-template-columns:1fr; gap:18px; }
  .cf-field input,
  .cf-field select,
  .cf-field textarea{ font-size:16px; }   /* stops iPhone zooming into the field */
  .cf-submit{ width:100%; }
}

@media (max-width: 600px){
  .career-intro{ padding:46px 18px 44px; }
  .career-intro-eyebrow{ font-size:16px; margin-bottom:12px; }
  .career-intro-heading{ font-size:clamp(24px, 6.5vw, 30px); }
  .career-intro-desc{ font-size:15.5px; }
  .openings{ padding:48px 16px 60px; }
  .openings-top{ margin-bottom:32px; }
  .openings-heading{ font-size:clamp(24px, 6.5vw, 30px); }
}

@media (max-width: 380px){
  .opening-head{ padding:0 16px 18px; }
  .opening-body{ padding:16px 16px 22px; }
  .career-modal-inner{ padding:30px 16px 26px; }
}

@media (prefers-reduced-motion: reduce){
  .reveal, .reveal.in-view{
    opacity:1 !important;
    transform:none !important;
    transition:none !important;
  }
  .hero-slide{ transition:opacity 1.2s ease; transform:none !important; }
  .opening-panel,
  .opening-chevron,
  .opening-apply,
  .opening-body-cta,
  .cf-submit{ transition:none !important; }
  .career-modal[open]{ animation:none; }
}
</style>

{{-- ===== Hero ===== --}}
@php
  $bannerTitle = $careerPage->banner_title ?? 'Careers at Energy Inspection Services';
  // no saved record yet: show the default line; saved record: show only what the admin typed
  $bannerDesc  = $careerPage
      ? $careerPage->banner_description
      : 'Build your career with a team that keeps critical oil and gas equipment safe, reliable and ready for work.';
@endphp

<section class="hero @if(!empty($careerPage->banner_video)) has-video @endif">
@include('web.layout.navbar')

  @if (!empty($careerPage->banner_video))
    {{-- Video takes priority over the banner image --}}
    <video
      class="hero-video"
      src="{{ asset('storage/' . $careerPage->banner_video) }}"
      poster="{{ !empty($careerPage->banner) ? asset('storage/' . $careerPage->banner) : '' }}"
      autoplay
      muted
      loop
      playsinline
      preload="auto"
    ></video>
  @elseif (!empty($careerPage->banner))
    <div class="hero-slides">
      <img src="{{ asset('storage/' . $careerPage->banner) }}" class="hero-slide active" alt="{{ $bannerTitle }}">
    </div>
  @else
    <div class="hero-slides">
      <img src="{{ asset('images/hero_image.jpeg') }}" class="hero-slide active" alt="Career banner">
    </div>
  @endif

  @if (empty($careerPage->banner_video))
    <div class="hero-vignette" aria-hidden="true"></div>
  @endif

  <div class="hero-content">
    <div class="hero-inner">
      <p class="eyebrow">Join Our Team</p>
      <h1>{{ $bannerTitle }}</h1>
      @if (!empty($bannerDesc))
        <p class="lede">{{ $bannerDesc }}</p>
      @endif
    </div>
  </div>
</section>

{{-- ===== Intro ===== --}}
@php
  $careerTitle = $careerPage->career_title ?? 'Work where precision and safety matter';
  $careerDesc  = (string) ($careerPage->career_description
      ?? 'Energy Inspection Services Ltd welcomes qualified inspectors, technicians and support staff who take pride in careful, standards-driven work. Browse the current openings below, read the details of each role and send us your CV online.');

  // Works for both kinds of admin field: formatted text is cleaned to safe tags,
  // plain text is escaped and keeps its line breaks.
  $careerDescHtml = $careerDesc !== strip_tags($careerDesc)
      ? \App\Models\Career::cleanHtml($careerDesc)
      : nl2br(e($careerDesc));
@endphp

<section class="career-intro">
  <div class="career-intro-inner">

    <div class="career-intro-left reveal reveal-left">
      <p class="career-intro-eyebrow">Careers</p>
      <h2 class="career-intro-heading">{{ $careerTitle }}</h2>
    </div>

    <div class="career-intro-divider" aria-hidden="true"></div>

    <div class="career-intro-right reveal reveal-right">
      <div class="career-intro-desc">{!! $careerDescHtml !!}</div>   {{-- was a <p> with fixed text --}}
    </div>

  </div>
</section>

{{-- ===== Current openings ===== --}}
<section class="openings" id="openings">

  <div class="openings-top reveal">
    <p class="openings-eyebrow">Current Openings</p>
    <h2 class="openings-heading">Find your role at EIS</h2>
    <p class="openings-sub">Select a position to read the details, then apply online.</p>
  </div>

  @if (session('career_success'))
    <div class="career-flash" role="status">{{ session('career_success') }}</div>
  @endif

  @if ($careers->isNotEmpty())
    <div class="openings-list reveal">
      @foreach ($careers as $career)
        <div class="opening">
          <div class="opening-head">
            <button
              type="button"
              class="opening-toggle"
              id="opening-btn-{{ $career->id }}"
              aria-expanded="false"
              aria-controls="opening-panel-{{ $career->id }}"
            >
              <span class="opening-title">{{ $career->title }}</span>
              <span class="opening-loc">
                @if ($career->location)
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-6.1-7-11a7 7 0 0 1 14 0c0 4.9-7 11-7 11z"/><circle cx="12" cy="10" r="2.6"/></svg>
                  {{ $career->location }}
                @endif
              </span>
              <span class="opening-chevron" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
              </span>
            </button>

            <button
              type="button"
              class="opening-apply"
              data-apply
              data-career-id="{{ $career->id }}"
              data-career-title="{{ $career->title }}"
              data-career-location="{{ $career->location }}"
            >Apply Now</button>
          </div>

          <div
            class="opening-panel"
            id="opening-panel-{{ $career->id }}"
            role="region"
            aria-labelledby="opening-btn-{{ $career->id }}"
          >
            <div class="opening-panel-inner">
              <div class="opening-body">
                @if (trim(strip_tags((string) $career->description)) !== '')
                  {{-- The Career model cleans the description before saving, so it is safe to print as HTML --}}
                  <div class="career-desc">{!! $career->description !!}</div>
                @else
                  <p class="career-desc-empty">More details about this role will be shared during the application process.</p>
                @endif

                <button
                  type="button"
                  class="opening-body-cta"
                  data-apply
                  data-career-id="{{ $career->id }}"
                  data-career-title="{{ $career->title }}"
                  data-career-location="{{ $career->location }}"
                >Apply for this role <span aria-hidden="true">&#8594;</span></button>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="openings-empty reveal">
      <h3>No open positions right now</h3>
      <p>We do not have any openings at the moment. Please check this page again soon.</p>
    </div>
  @endif

</section>

{{-- ===== Apply modal ===== --}}
<dialog class="career-modal" id="careerModal" aria-labelledby="careerModalTitle">
  <div class="career-modal-inner">

    <button type="button" class="career-modal-close" id="careerModalClose" aria-label="Close">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
    </button>

    <h2 class="career-modal-title" id="careerModalTitle">Submit CV</h2>
    <p class="career-modal-note">We accept CVs in .pdf or .doc format only. File size limit: 3 MB.</p>

    <form class="career-form" id="careerForm" method="POST" action="{{ route('career.apply') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="career_id" id="cfCareerId" value="">

      {{-- Trap for spam bots: real visitors never see or fill this --}}
      <div class="cf-hp" aria-hidden="true">
        <label for="cfWebsite">Website</label>
        <input type="text" name="website" id="cfWebsite" tabindex="-1" autocomplete="off">
      </div>

      <div class="cf-grid">
        <div class="cf-field">
          <label for="cfName">Name <span class="cf-req">*</span></label>
          <input type="text" id="cfName" name="name" maxlength="120" autocomplete="name" required>
          <span class="cf-error" data-error-for="name"></span>
        </div>

        <div class="cf-field">
          <label for="cfPost">Post <span class="cf-req">*</span></label>
          <input type="text" id="cfPost" name="apply_for" maxlength="150" readonly required>
          <span class="cf-error" data-error-for="apply_for"></span>
        </div>

        <div class="cf-field">
          <label for="cfEmail">E-mail <span class="cf-req">*</span></label>
          <input type="email" id="cfEmail" name="email" maxlength="150" autocomplete="email" required>
          <span class="cf-error" data-error-for="email"></span>
        </div>

        <div class="cf-field">
          <label for="cfPhone">Phone <span class="cf-req">*</span></label>
          <input type="tel" id="cfPhone" name="phone" maxlength="30" inputmode="tel" autocomplete="tel"
                 pattern="[0-9+\-\s()]{6,30}" placeholder="+964 750 123 4567" required>
          <span class="cf-error" data-error-for="phone"></span>
        </div>

        <div class="cf-field">
          <label for="cfNationality">Nationality</label>
          <input type="text" id="cfNationality" name="nationality" maxlength="100" autocomplete="country-name">
          <span class="cf-error" data-error-for="nationality"></span>
        </div>

        <div class="cf-field">
          <label for="cfLocation">Location</label>
            <select id="cfLocation" name="location">
            <option value="">Select Location</option>
            <option value="India">India</option>      {{-- was a @foreach over $locations --}}
            <option value="UAE">UAE</option>
            <option value="KSA">KSA</option>
            <option value="Kuwait">Kuwait</option>
            <option value="Qatar">Qatar</option>
          </select>
          <span class="cf-error" data-error-for="location"></span>
        </div>

        <div class="cf-field cf-full">
          <label for="cfMessage">Your Message</label>
          <textarea id="cfMessage" name="message" rows="5" maxlength="2000"></textarea>
          <span class="cf-error" data-error-for="message"></span>
        </div>

        <div class="cf-field cf-full">
          <label for="cfCv">Resume / CV <span class="cf-req">*</span></label>
          <input type="file" id="cfCv" name="cv"
                 accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                 required>
          <span class="cf-error" data-error-for="cv"></span>
        </div>
      </div>

      <div class="cf-actions">
        <button type="submit" class="cf-submit" id="cfSubmit">Send Application</button>
        <p class="cf-status" id="cfStatus" role="alert"></p>
      </div>
    </form>

    <div class="career-success" role="status">
      <div class="career-success-icon" aria-hidden="true">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
      </div>
      <h3>Application sent</h3>
      <p>Thank you. We have received your application and our team will contact you if your profile matches the role.</p>
      <button type="button" class="career-success-close" id="careerSuccessClose">Close</button>
    </div>

  </div>
</dialog>

@include('web.layout.footer')

<script>
  document.addEventListener('DOMContentLoaded', function () {

    /* ----- Scroll reveal (same as the other pages) ----- */
    const revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window && revealEls.length) {
      const revealObserver = new IntersectionObserver(function (entries, obs) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('in-view');
            obs.unobserve(entry.target);
          }
        });
      }, { threshold: 0.1, rootMargin: '0px 0px -60px 0px' });

      revealEls.forEach(function (el) { revealObserver.observe(el); });
    } else {
      revealEls.forEach(function (el) { el.classList.add('in-view'); });
    }

    /* ----- Openings: click a row to expand it (one open at a time) ----- */
    const openings = Array.from(document.querySelectorAll('.opening'));
    openings.forEach(function (item) {
      const toggle = item.querySelector('.opening-toggle');
      toggle.addEventListener('click', function () {
        const willOpen = !item.classList.contains('open');
        openings.forEach(function (other) {
          other.classList.remove('open');
          other.querySelector('.opening-toggle').setAttribute('aria-expanded', 'false');
        });
        if (willOpen) {
          item.classList.add('open');
          toggle.setAttribute('aria-expanded', 'true');
        }
      });
    });

    /* ----- Apply modal ----- */
    const modal = document.getElementById('careerModal');
    const form = document.getElementById('careerForm');
    if (!modal || !form) return;

    const fields = {
      career_id: document.getElementById('cfCareerId'),
      name: document.getElementById('cfName'),
      apply_for: document.getElementById('cfPost'),
      email: document.getElementById('cfEmail'),
      phone: document.getElementById('cfPhone'),
      nationality: document.getElementById('cfNationality'),
      location: document.getElementById('cfLocation'),
      message: document.getElementById('cfMessage'),
      cv: document.getElementById('cfCv')
    };
    const submitBtn = document.getElementById('cfSubmit');
    const statusEl = document.getElementById('cfStatus');
    const successClose = document.getElementById('careerSuccessClose');
    const MAX_CV_BYTES = 3 * 1024 * 1024;
    let lastTrigger = null;

    function clearErrors() {
      form.querySelectorAll('.cf-error').forEach(function (el) { el.textContent = ''; });
      form.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });
      statusEl.textContent = '';
    }

    function showErrors(errors) {
      let first = null;
      Object.keys(errors).forEach(function (key) {
        const messages = [].concat(errors[key]);
        const slot = form.querySelector('[data-error-for="' + key + '"]');
        const field = fields[key];
        if (slot) slot.textContent = messages[0] || '';
        if (field) {
          field.classList.add('is-invalid');
          if (!first) first = field;
        }
        if (!slot) statusEl.textContent = messages[0] || '';
      });
      if (first) first.focus();
    }

    function setSending(sending) {
      submitBtn.disabled = sending;
      submitBtn.textContent = sending ? 'Sending...' : 'Send Application';
    }

    function openModal(trigger) {
      lastTrigger = trigger;
      modal.classList.remove('sent');
      form.reset();
      clearErrors();
      setSending(false);

      fields.career_id.value = trigger.dataset.careerId || '';
      fields.apply_for.value = trigger.dataset.careerTitle || '';

      if (typeof modal.showModal === 'function') modal.showModal();
      else modal.setAttribute('open', '');
      document.documentElement.classList.add('career-lock');
      modal.scrollTop = 0;
      fields.name.focus();
    }

    function closeModal() {
      if (typeof modal.close === 'function') modal.close();
      else modal.removeAttribute('open');
      onClosed();
    }

    function onClosed() {
      document.documentElement.classList.remove('career-lock');
      if (lastTrigger) { lastTrigger.focus(); lastTrigger = null; }
    }

    document.querySelectorAll('[data-apply]').forEach(function (btn) {
      btn.addEventListener('click', function () { openModal(btn); });
    });

    document.getElementById('careerModalClose').addEventListener('click', closeModal);
    successClose.addEventListener('click', closeModal);
    modal.addEventListener('close', onClosed);                 // Esc key
    modal.addEventListener('click', function (e) {             // click on the dark backdrop
      if (e.target === modal) closeModal();
    });

    /* Phone: digits, spaces, +, -, ( ) only (same rule as the contact form) */
    fields.phone.addEventListener('input', function () {
      const cleaned = fields.phone.value.replace(/[^0-9+\-\s()]/g, '');
      if (cleaned !== fields.phone.value) fields.phone.value = cleaned;
    });

    /* Send without reloading the page, so the chosen CV is not lost on an error */
    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      clearErrors();

      const file = fields.cv.files[0];
      if (file) {
        if (!/\.(pdf|doc|docx)$/i.test(file.name)) {
          showErrors({ cv: ['Please upload a PDF or Word file (.pdf, .doc or .docx).'] });
          return;
        }
        if (file.size > MAX_CV_BYTES) {
          showErrors({ cv: ['This file is larger than 3 MB. Please choose a smaller file.'] });
          return;
        }
      }

      setSending(true);
      try {
        const res = await fetch(form.action, {
          method: 'POST',
          headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          body: new FormData(form)
        });

        if (res.status === 422) {
          const data = await res.json();
          showErrors(data.errors || {});
          return;
        }
        if (res.status === 419) {
          statusEl.textContent = 'Your session has expired. Please refresh the page and try again.';
          return;
        }
        if (res.status === 429) {
          statusEl.textContent = 'Too many attempts. Please wait a minute and try again.';
          return;
        }
        if (res.status === 413) {
          showErrors({ cv: ['This file is too large. Please choose a file under 3 MB.'] });
          return;
        }
        if (!res.ok) throw new Error('Request failed: ' + res.status);

        modal.classList.add('sent');
        modal.scrollTop = 0;
        successClose.focus();
      } catch (err) {
        statusEl.textContent = 'Something went wrong while sending. Please try again in a moment.';
      } finally {
        setSending(false);
      }
    });
  });
</script>
@endsection