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

  .about{
    position:relative;
    background:#ffffff;
    font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
    color:#111111;
  }

  .about *{ box-sizing:border-box; }

  .about-bar{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:24px;
    padding:34px 60px;
    border-bottom:1px solid #e6e6e6;
  }

  .about-bar h2{
    font-size:24px;
    font-weight:700;
    margin:0 0 6px;
    color:#111111;
  }

  .about-bar p{
    font-size:16px;
    color:#5a5a5a;
    margin:0;
  }

  .about-cta{
    display:inline-flex;
    align-items:center;
    gap:8px;
    color:#111111;
    text-decoration:none;
    font-size:17px;
    font-weight:600;
    white-space:nowrap;
    padding-top:4px;
    transition:gap 0.2s ease, color 0.2s ease;
  }
  .about-cta .arrow{
    color:#E8792D;
    font-size:19px;
    transition:transform 0.2s ease;
  }
  .about-cta:hover{ gap:12px; color:#E8792D; }
  .about-cta:hover .arrow{ transform:translateX(2px); }

  /* ===== About body: fixed, equal, reduced height for image + content ===== */
  .about-body{
    display:flex;
    align-items:stretch;
    gap:48px;
    padding:50px 60px 70px;
  }

  .about-photo{
    flex:0 0 45%;
    border-radius:20px;
    overflow:hidden;
    transition:box-shadow 0.3s ease;
    background-position:center;
    background-size:cover;
    box-shadow:0 18px 36px rgba(0,0,0,0.14);
  }

  .about-photo:hover{
    box-shadow:0 22px 44px rgba(0,0,0,0.18);
  }

  .about-content{
    flex:1;
    min-width:0;
    height:500px;                 /* same fixed height as photo */
    display:flex;
    flex-direction:column;
    justify-content:flex-start;
    border-radius:14px;
    padding:32px 34px;
    overflow:hidden;
  }

.about-eyebrow{
  margin-top:20px;
  position:relative;
  color:var(--orange);
  font-weight:700;
  font-size:20px;
  margin:0 0 1px;
  padding-left:24px;
  flex-shrink:0;
}

.about-eyebrow::before{
  content:"";
  position:absolute;
  left:0;
  top:50%;
  transform:translateY(-50%);
  width:16px;
  height:3px;
  background:var(--orange);
  border-radius:2px;
}

.about-heading{
  font-size:clamp(20px, 3vw, 32px);   /* was fixed 32px — now scales */
  line-height:1.2;
  font-weight:700;
  letter-spacing:-0.3px;
  color:#111111;
  margin:0 0 14px;
  flex-shrink:0;
}

.about-text{
  overflow:hidden;
  display:flex;
  flex-direction:column;
  gap:12px;
  transition:max-height 0.35s ease;
  padding-left:40px;
  max-height:400px;
}

.about-text p{
  font-size:18px;
  line-height:1.6;
  color:#3d3d3d;
  margin:0;
}

/* ===== Tablet ===== */
@media (max-width: 1024px){
  .about-bar{ padding:30px 40px; }
  .about-body{ padding:44px 40px 60px; gap:36px; }
  .about-photo,
  .about-content{ height:300px; }
  .about-content{ padding:28px 28px; }
  .about-text{ max-height:200px; padding-left:28px; }
  .about-text p{ font-size:16px; }
}

/* ===== Small tablet / large phone — 2 rows ===== */
@media (max-width: 900px){
  .about-bar{
    flex-direction:column;
    gap:14px;
    padding:26px 24px;
  }

  .about-cta{ align-self:flex-start; }

  .about-body{
    display:flex;
    flex-direction:column;
    align-items:stretch;
    gap:24px;
    padding:36px 24px 48px;
  }

  .about-photo{
    flex:none;
    width:100%;
    height:240px;
    order:1;
    box-shadow:0 8px 20px rgba(0,0,0,0.12);
  }

  .about-content{
    flex:none;
    width:100%;
    height:auto;
    max-height:none;      /* was 340px — let content breathe on mobile */
    padding:26px 24px;
    order:2;
  }

  .about-eyebrow{ margin-top:0; font-size:17px; padding-left:20px; }
  .about-heading{ margin-bottom:12px; }

  .about-text{
  max-height:none;
  padding-left:20px;
}
  .about-text p{ font-size:16px; }
}

/* ===== Phones ===== */
@media (max-width: 600px){
  .about-bar h2{ font-size:20px; }
  .about-bar p{ font-size:14.5px; }
  .about-cta{ font-size:15px; }

  .about-photo{ height:200px; border-radius:14px; }
  .about-content{ padding:22px 18px; border-radius:12px; }

  .about-eyebrow{ font-size:15px; margin-bottom:10px; padding-left:18px; }
  .about-eyebrow::before{ width:14px; }

  .about-heading{ font-size:clamp(18px, 6vw, 24px); margin-bottom:10px; }

  .about-text{ gap:12px; max-height:none; padding-left:0; }
  .about-text p{ font-size:14.5px; }
}

/* ===== Very small phones ===== */
@media (max-width: 380px){
  .about-body{ padding:30px 14px 40px; }
  .about-photo{ height:170px; }
  .about-content{ padding:18px 14px; }
  .about-heading{ font-size:18px; }
  .about-text{ max-height:none; }
  .about-text p{ font-size:14px; }
}

  /* scroll-reveal motion */
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

  .stats{
  position:relative;
  background:linear-gradient(135deg, var(--orange) 0%, #f0893f 100%);
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:60px 60px;
  overflow:hidden;
}

.stats::before{
  content:"";
  position:absolute;
  top:-120px; right:-120px;
  width:300px; height:300px;
  border-radius:50%;
  background:rgba(255,255,255,0.08);
  pointer-events:none;
}
.stats::after{
  content:"";
  position:absolute;
  bottom:-160px; left:-100px;
  width:300px; height:300px;
  border-radius:50%;
  background:rgba(255,255,255,0.06);
  pointer-events:none;
}

.stats-inner{
  position:relative;
  max-width:1300px;
  margin:0 auto;
  text-align:center;
}

.stats-eyebrow{
  color:rgba(255,255,255,0.85);
  font-weight:700;
  font-size:14px;
  letter-spacing:1.2px;
  text-transform:uppercase;
  margin:0 0 12px;
}

.stats-heading{
  color:#ffffff;
  font-size:clamp(24px, 3vw, 36px);
  font-weight:700;
  line-height:1.2;
  margin:0 0 38px;
}

.stats-row{
  position:relative;
  display:flex;
  gap:28px;
}

.stats-item{
  position:relative;
  flex:1;
  min-width:0;
  text-align:center;
  padding:20px 20px 24px;
  background:rgba(255,255,255,0.97);
  backdrop-filter:blur(6px);
  border-radius:28px;
  box-shadow:0 14px 34px rgba(0,0,0,0.14);
  border:1px solid rgba(255,255,255,0.5);
  overflow:hidden;
  transition:transform 0.4s cubic-bezier(0.16,1,0.3,1), box-shadow 0.4s cubic-bezier(0.16,1,0.3,1);
}
.stats-item::before{
  content:"";
  position:absolute;
  inset:0;
  background:linear-gradient(135deg, rgba(232,121,45,0.08), rgba(232,121,45,0));
  opacity:0;
  transition:opacity 0.4s ease;
  pointer-events:none;
}
.stats-item:hover{
  transform:scale(1.06);
}
.stats-item:hover::before{
  opacity:1;
}

.stats-item:hover .stats-icon-line{ transform:scaleX(1); opacity:1; }

.stats-value{
  font-size:44px;
  font-weight:800;
  color:var(--navy);
  line-height:1;
  margin:0 0 14px;
  letter-spacing:-0.5px;
}

.stats-label{
  font-size:13.5px;
  font-weight:700;
  letter-spacing:0.5px;
  text-transform:uppercase;
  color:#7a7a7a;
  margin:0;
  line-height:1.4;
}

/* Reveal animation (framer-motion style entrance) */
.stats-item.reveal{
  opacity:0;
  transform:translateY(40px) scale(0.94);
  transition:opacity 0.7s cubic-bezier(0.16,1,0.3,1), transform 0.7s cubic-bezier(0.16,1,0.3,1);
}
.stats-item.reveal.in-view{
  opacity:1;
  transform:translateY(0) scale(1);
}
.stats-item.reveal.in-view:hover{
  transform:scale(1.06);
}
.stats-dots{
  display:none;
  justify-content:center;
  gap:8px;
  margin-top:24px;
}

.stats-dot{
  width:8px;
  height:8px;
  border-radius:50%;
  background:rgba(255,255,255,0.45);
  border:none;
  padding:0;
  cursor:pointer;
  transition:background 0.2s ease, transform 0.2s ease;
}
.stats-dot.active{
  background:#ffffff;
  transform:scale(1.3);
}

@media (prefers-reduced-motion: reduce){
  .stats-item, .stats-item.reveal{ transition:none !important; transform:none !important; opacity:1 !important; }
}

/* ===== Tablet ===== */
@media (max-width: 1024px){
  .stats{ padding:70px 40px; }
  .stats-row{ gap:18px; }
  .stats-item{ padding:32px 16px 28px; }
  .stats-value{ font-size:36px; }
}

/* ===== Small tablet — wrap to 2 columns ===== */
@media (max-width: 900px) and (min-width: 601px){
  .stats{ padding:60px 24px; }
  .stats-row{ flex-wrap:wrap; row-gap:18px; }
  .stats-item{ flex:0 0 calc(50% - 9px); }
  .stats-heading{ margin-bottom:36px; }
}
/* ===== Phones — auto-sliding carousel (matches services carousel) ===== */
@media (max-width: 600px){
  .stats{ padding:48px 0 44px; }
  .stats-inner{ padding:0; }
  .stats-heading{ margin-bottom:30px; padding:0 24px; }
  .stats-eyebrow{ padding:0 24px; }

  .stats-row-wrap{
    position:relative;
    width:100%;
    overflow:hidden;
  }

  .stats-row-wrap::before,
  .stats-row-wrap::after{
    content:"";
    position:absolute;
    top:0;
    bottom:0;
    width:28px;
    z-index:3;
    pointer-events:none;
  }

  .stats-row-wrap::before{
    left:0;
    background:linear-gradient(to right, var(--orange) 0%, rgba(232,121,45,0) 100%);
  }

  .stats-row-wrap::after{
    right:0;
    background:linear-gradient(to left, #f0893f 0%, rgba(240,137,63,0) 100%);
  }

  .stats-row{
    display:flex !important;
    flex-wrap:nowrap !important;
    overflow-x:hidden;
    scroll-snap-type:x mandatory;
    gap:16px;
    padding:4px 24px;
    margin:0;
    row-gap:0 !important;
  }

  .stats-item{
    flex:0 0 100% !important;
    max-width:100%;
    scroll-snap-align:center;
  }

  .stats-dots{ display:flex; padding:0 24px; }
}
@media (max-width: 380px){
  .stats-item{ flex:0 0 100% !important; padding:28px 16px 24px; }
  .stats-value{ font-size:32px; }
}

.services{
  position:relative;
  background:#ffffff;
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:80px 60px 100px;
}

.services *{ box-sizing:border-box; }

.services-eyebrow{
  color:#E8792D;
  font-weight:700;
  font-size:19px;
  margin:0 0 14px;
}

.services-heading{
  font-size:clamp(28px, 3vw, 40px);
  line-height:1.2;
  font-weight:500;
  color:#111111;
  margin:0 0 22px;
  white-space:pre-line;
}

.services-sub{
  font-size:17px;
  line-height:1.6;
  color:#4a4a4a;
  margin:0 0 34px;
}

.services-photo{
  width:100%;
  height:350px;
  aspect-ratio: 4 / 3;
  border-radius:24px;
  overflow:hidden;
  margin-bottom:18px;
  background-color:#e8e8e8;
  background-position:center;
  background-size:cover;
  background-repeat:no-repeat;
  transition:box-shadow 0.4s ease;
}
.services-inner{
  display:flex;
  align-items:flex-start;
  gap:70px;
}

.services-left{
  flex:1 1 0;
  min-width:0;
  position:sticky;
  top:40px;
  align-self:flex-start;
  max-height:calc(100vh - 80px);
}

.services-right{
  flex:1 1 0;
  min-width:0;
  max-width:50%;
  max-height:calc(100vh - 80px);
  overflow-y:auto;
  overflow-x:hidden;
  scrollbar-width:none;
  -ms-overflow-style:none;
}

.services-right::-webkit-scrollbar{
  display:none;
  width:0;
  height:0;
}
.services-photo:hover{
  box-shadow:0 20px 44px rgba(0,0,0,0.18);
}

.services-grid{
  display:grid;
  grid-template-columns:repeat(2, 1fr);
  gap:36px 28px;
}

.service-card{
  min-width:0;
  padding:14px;
  border-radius:20px;
  transition:transform 0.4s cubic-bezier(0.16,1,0.3,1), box-shadow 0.4s ease, background 0.4s ease;
}

.service-card:hover{
  transform:translateY(-8px);
  box-shadow:0 20px 40px rgba(0,0,0,0.10);
  background:#fafafa;
}

.service-card:hover .service-photo{
  transform:scale(1.06);
}

.service-card:hover .service-title{
  color:var(--orange);
}

.service-photo{
  width:80%;
  aspect-ratio: 16 / 9;
  height:170px;
  border-radius:24px;
  overflow:hidden;
  margin-bottom:18px;
  background-color:#e8e8e8;
  background-position:center;
  background-size:cover;
  background-repeat:no-repeat;
  transition:transform 0.5s cubic-bezier(0.16,1,0.3,1);
}

.service-title{
  font-size:19px;
  line-height:1.3;
  font-weight:700;
  color:#111111;
  margin:0 0 10px;
}

.service-desc{
  font-size:14.5px;
  line-height:1.55;
  color:#5a5a5a;
  margin:0 0 14px;
  display:-webkit-box;
  -webkit-line-clamp:3;
  -webkit-box-orient:vertical;
  overflow:hidden;
}

.service-link{
  display:inline-flex;
  align-items:center;
  gap:8px;
  color:#111111;
  text-decoration:none;
  font-size:13px;
  font-weight:700;
  letter-spacing:0.4px;
  transition:gap 0.2s ease, color 0.2s ease;
}
.service-link .arrow{
  color:#E8792D;
  font-size:16px;
  transition:transform 0.2s ease;
}
.service-link:hover{ gap:12px; color:#E8792D; }
.service-link:hover .arrow{ transform:translateX(3px); }

.services-empty{
  font-size:15px;
  color:#8a8a8a;
  padding:20px 0;
}

/* ===== Mobile auto-scroll carousel dots ===== */
.services-dots{
  display:none;
  justify-content:center;
  gap:8px;
  margin-top:20px;
}

.services-dot{
  width:8px;
  height:8px;
  border-radius:50%;
  background:#dcdcdc;
  border:none;
  padding:0;
  cursor:pointer;
  transition:background 0.2s ease, transform 0.2s ease;
}
.services-dot:hover{
  background:#f0b98a;
  transform:scale(1.15);
}
.services-dot.active{
  background:var(--orange);
  transform:scale(1.3);
}

@media (max-width: 1100px){
  .services-grid{ grid-template-columns:repeat(2, 1fr); }
}

@media (max-width: 900px){
  .services{ padding:56px 24px 70px; }
  .services-inner{ flex-direction:column; gap:44px; }
  .services-left{
    position:static;
    width:100%;
    max-width:100%;
    flex-basis:auto;
  }
  .services-right{
  flex:1 1 0;
  min-width:0;
  max-width:50%;
  max-height:calc(100vh - 80px);
  overflow-y:auto;
  overflow-x:hidden;
  scrollbar-width:none;      /* Firefox */
  -ms-overflow-style:none;   /* old Edge/IE */
}
  .services-heading{
    word-break:break-word;
    overflow-wrap:break-word;
  }
  .services-sub{
    word-break:break-word;
    overflow-wrap:break-word;
  }
.services-grid{
  display:grid;
  grid-template-columns:repeat(2, 1fr);
  gap:36px 28px;
  width:100%;
  max-width:100%;
}
}

/* ===== Phones — single card auto-sliding carousel ===== */
@media (max-width: 560px){
  .services-heading{ font-size:clamp(22px, 6.5vw, 28px); }
  .services-sub{ font-size:15px; line-height:1.55; }
  .services-photo{ height:220px; }

  .services-grid{
    display:flex;
    flex-wrap:nowrap;
    overflow-x:hidden;
    scroll-snap-type:x mandatory;
    gap:16px;
    padding:4px 4px 4px;
    margin:0;
    width:100%;
  }

  .service-card{
    flex:0 0 100%;
    max-width:100%;
    scroll-snap-align:center;
  }

  .service-photo{ width:100%; max-height:220px; }

  .services-dots{ display:flex; }
}
.trusted{
  position:relative;
  background:linear-gradient(135deg, var(--orange) 0%, #f0893f 100%);
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  padding:40px 30px 60px;
  text-align:center;
  overflow:hidden;
}

.trusted *{ box-sizing:border-box; }   /* fixes padding-not-showing bug */

.trusted::before{
  content:"";
  position:absolute;
  top:-100px; left:-100px;
  width:280px; height:280px;
  border-radius:50%;
  background:rgba(255,255,255,0.07);
  pointer-events:none;
}
.trusted::after{
  content:"";
  position:absolute;
  bottom:-140px; right:-80px;
  width:260px; height:260px;
  border-radius:50%;
  background:rgba(255,255,255,0.06);
  pointer-events:none;
}

.trusted-eyebrow{
  position:relative;
  color:#ffffff;
  font-weight:700;
  font-size:14px;
  letter-spacing:1.2px;
  text-transform:uppercase;
  margin:0 0 12px;
}

.trusted-heading{
  position:relative;
  color:#ffffff;
  font-weight:700;
  font-size:clamp(26px, 3.6vw, 42px);
  line-height:1.2;
  margin:0 0 16px;
}

.trusted-sub{
  position:relative;
  color:rgba(255,255,255,0.92);
  font-size:16px;
  line-height:1.55;
  max-width:560px;
  margin:0 auto 48px;
}

.trusted-eyebrow.reveal,
.trusted-heading.reveal,
.trusted-sub.reveal{
  opacity:0;
  transform:translateY(28px);
  transition:opacity 0.7s cubic-bezier(0.16,1,0.3,1), transform 0.7s cubic-bezier(0.16,1,0.3,1);
}
.trusted-eyebrow.reveal.in-view,
.trusted-heading.reveal.in-view,
.trusted-sub.reveal.in-view{
  opacity:1;
  transform:translateY(0);
}

.trusted-track-wrap{
  position:relative;
  width:100%;
  max-width:1200px;
  margin:0 auto;
  padding:8px 0;
  overflow:hidden;

  -webkit-mask-image:linear-gradient(
    90deg,
    transparent 0%,
    #000 8%,
    #000 92%,
    transparent 100%
  );

  mask-image:linear-gradient(
    90deg,
    transparent 0%,
    #000 8%,
    #000 92%,
    transparent 100%
  );
}

.trusted-track{
  display:flex;

  gap:36px;
  width:max-content;
  will-change:transform;
}

.trusted-track::-webkit-scrollbar{
  display:none;
  height:0;
}

.trusted-logo{
  flex:0 0 auto;
  width:210px;
  height:120px;
  background:#ffffff;
  border-radius:25px;
  display:flex;
  align-items:center;
  justify-content:center;
  padding:18px 32px;
  overflow:hidden;          /* NEW — contains any oversized image instead of letting it spill out */
  box-shadow:0 12px 28px rgba(0,0,0,0.15);
  transition:transform 0.35s cubic-bezier(0.16,1,0.3,1), box-shadow 0.35s ease;
}

.trusted-logo:hover{
  transform:translateY(-6px) scale(1.08);
  box-shadow:0 20px 40px rgba(0,0,0,0.22);
}

.trusted-logo img{
  max-width:100%;
  max-height:100%;
  min-width:0;              /* NEW — overrides flex-item default min-width:auto, lets image actually shrink to fit */
  min-height:0;              /* NEW — same fix for the vertical axis */
  object-fit:contain;
  filter:grayscale(15%);
  opacity:0.9;
  transition:filter 0.3s ease, opacity 0.3s ease;
}

.trusted-logo:hover img{
  filter:grayscale(0%);
  opacity:1;
}
@media (prefers-reduced-motion: reduce){
  .trusted-eyebrow.reveal,
  .trusted-heading.reveal,
  .trusted-sub.reveal{
    opacity:1 !important;
    transform:none !important;
    transition:none !important;
  }
}

/* ===== Tablet ===== */
@media (max-width: 1024px){
  .trusted{ padding:56px 40px 52px; }
  .trusted-track-wrap{ padding:8px 40px; }
  .trusted-logo{ width:140px; height:68px; padding:16px 26px; }
}

@media (max-width: 700px){
  .trusted{ padding:48px 24px 44px; }
  .trusted-sub{ margin-bottom:36px; }
  .trusted-track-wrap{ padding:6px 24px; }
  .trusted-track{ gap:22px; }
  .trusted-logo{ width:120px; height:58px; padding:12px 20px; border-radius:12px; }
}

@media (max-width: 380px){
  .trusted-track-wrap{ padding:6px 16px; }
  .trusted-logo{ width:104px; height:52px; padding:10px 16px; }
}
.why{
    position:relative;
    background:linear-gradient(180deg, #fafafa 0%, #ffffff 12%);
    font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
    padding:90px 60px 110px;
    overflow:hidden;
  }

  .why::before{
    content:"";
    position:absolute;
    top:-180px;
    right:-180px;
    width:420px;
    height:420px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(232,121,45,0.06) 0%, rgba(232,121,45,0) 70%);
    pointer-events:none;
  }

  .why *{ box-sizing:border-box; }

  .why-top{ position:relative; margin-bottom:60px; }

  .why-eyebrow{
    color:var(--orange);
    font-weight:700;
    font-size:19px;
    margin:0 0 14px;
  }

  .why-heading{
    font-size:clamp(30px, 3.6vw, 46px);
    line-height:1.2;
    font-weight:400;
    color:#111111;
    margin:0 0 26px;
  }

  .why-sub{
    font-size:17px;
    line-height:1.6;
    color:#333333;
    max-width:480px;
    margin:0;
  }

   .why-body{
    position:relative;
    display:flex;
    align-items:stretch;
    gap:44px;
  }

  .why-tabs-box{
    flex:0 0 270px;
    background:linear-gradient(165deg, var(--orange) 0%, #d1691f 100%);
    border-radius:20px;
    padding:36px 28px;
    box-shadow:0 20px 44px rgba(232,121,45,0.28);
  }

  .why-tabs{
    list-style:none;
    margin:0;
    padding:0;
    display:flex;
    flex-direction:column;
    gap:26px;
  }

  .why-tab{
    position:relative;
    display:flex;
    align-items:center;
    gap:14px;
    background:none;
    border:none;
    padding:0 0 0 0;
    font-family:inherit;
    font-size:18px;
    font-weight:600;
    letter-spacing:0.2px;
    color:rgba(255,255,255,0.55);
    cursor:pointer;
    text-align:left;
    width:100%;
    transition:color 0.2s ease, padding-left 0.25s ease;
  }

  .why-tab-num{
    font-size:13px;
    font-weight:700;
    opacity:0.85;
    min-width:20px;
  }

  .why-tab::before{
    content:"";
    display:inline-block;
    width:0;
    height:2px;
    background:var(--white);
    transition:width 0.25s ease;
  }

  .why-tab.active{
    color:var(--white);
    padding-left:4px;
  }
  .why-tab.active::before{ width:16px; }
  .why-tab:hover{ color:rgba(255,255,255,0.85); }
  .why-tabs-box::before{
    content:"";
  }

  .why-tab{
    padding-bottom:2px;
  }

  .why-tab.active .why-tab-num{
    opacity:1;
  }
  .why-content-wrap{
    position:relative;
    flex:1;
    min-width:0;
    display:flex;
    align-items:center;
    overflow:hidden;
  }

  .why-panel{
    display:none;
    align-items:center;
    gap:38px;
    width:100%;
  }

  .why-panel.is-active{ display:flex; }

  .why-panel.is-leaving{
    display:flex;
    position:absolute;
    top:0; left:0;
    width:100%;
    animation: whySlideOutLeft 0.45s ease forwards;
  }

  .why-panel.is-entering{
    animation: whySlideInRight 0.45s ease forwards;
  }

  @keyframes whySlideOutLeft{
    from{ transform:translateX(0); opacity:1; }
    to{ transform:translateX(-50px); opacity:0; }
  }
  @keyframes whySlideInRight{
    from{ transform:translateX(50px); opacity:0; }
    to{ transform:translateX(0); opacity:1; }
  }

  .why-panel-text{ flex:0 0 38%; min-width:220px; max-width:38%; }

  .why-panel-eyebrow{
    color:var(--orange);
    font-weight:700;
    font-size:13px;
    letter-spacing:0.6px;
    margin:0 0 14px;
  }

  .why-panel-heading{
    font-size:clamp(24px, 2.4vw, 32px);
    line-height:1.22;
    font-weight:600;
    color:#111111;
    margin:0 0 16px;
    letter-spacing:-0.3px;
  }

  .why-panel-desc{
    font-size:15.5px;
    line-height:1.68;
    color:#5a5a5a;
    margin:0 0 26px;
    max-width:none;
  }

  .why-panel-link{
    display:inline-flex;
    align-items:center;
    gap:10px;
    color:#111111;
    text-decoration:none;
    font-size:13px;
    font-weight:700;
    letter-spacing:0.6px;
    transition:gap 0.2s ease, color 0.2s ease;
  }
  .why-panel-link .arrow{ color:var(--orange); font-size:16px; transition:transform 0.2s ease; }
  .why-panel-link:hover{ gap:14px; color:var(--orange); }
  .why-panel-link:hover .arrow{ transform:translateX(3px); }

    .why-panel-photo{
    position:relative;
    flex:1;
    min-width:0;
    max-width:none;
    height:400px;
    border-radius:20px;
    overflow:hidden;
    background: linear-gradient(165deg, #d9a441 0%, #b97a2e 35%, #5c4326 65%, #2a2016 100%);
    box-shadow:0 24px 50px rgba(0,0,0,0.16);
    transition:transform 0.5s cubic-bezier(0.16,1,0.3,1), box-shadow 0.5s ease;
  }

  .why-panel-photo::after{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(180deg, rgba(0,0,0,0) 55%, rgba(0,0,0,0.35) 100%);
    pointer-events:none;
  }

  .why-panel-photo:hover{
    transform:translateY(-6px);
    box-shadow:0 32px 60px rgba(0,0,0,0.22);
  }

  .why-panel-badge{
    position:absolute;
    bottom:20px;
    left:20px;
    z-index:2;
    display:inline-flex;
    align-items:center;
    gap:8px;
    background:rgba(255,255,255,0.95);
    backdrop-filter:blur(4px);
    border-radius:12px;
    padding:10px 16px;
    font-size:12.5px;
    font-weight:800;
    letter-spacing:0.4px;
    color:var(--navy);
    text-transform:uppercase;
  }

  .why-panel-badge .num{
    color:var(--orange);
    font-size:14px;
  }

   @media (max-width: 1100px){
    .why-content-wrap{ overflow:visible; }
    .why-panel{ flex-direction:column; align-items:flex-start; gap:26px; }
    .why-panel-text{ max-width:none; flex-basis:auto; width:100%; }
    .why-panel-photo{ width:100%; flex-basis:auto; height:320px; }
  }

  @media (max-width: 900px){
    .why{ padding:64px 24px 70px; }
    .why-body{ flex-direction:column; gap:32px; }
    .why-tabs-box{ flex-basis:auto; width:100%; padding:28px 24px; }
    .why-tabs{ flex-direction:row; flex-wrap:wrap; gap:16px 28px; }
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
  flex:1 1 0;
  min-width:0;
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
  flex:1 1 0;
  min-width:0;
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

@include('web.header')

<section class="about">
  <div class="about-bar">
    <div>
      <h2>Trusted expertise for critical energy assets.</h2>
      <p>Professional solutions focused on safety, reliability and operational continuity.</p>
    </div>
    <a href="{{ url('#') }}" class="about-cta">
      Request Information <span class="arrow">&#8594;</span>
    </a>
  </div>

  <div class="about-body">
    <div
      class="about-photo reveal reveal-left"
      role="img"
      aria-label="{{ $about->title ?? null }}"
      @if(!empty($about->image))
        style="background-image: linear-gradient(120deg, rgba(30,30,35,0.55), rgba(30,30,35,0.15) 55%, rgba(232,121,45,0.35)), url('{{ asset('storage/' . $about->image) }}');"
      @endif
    ></div>

    <div class="about-content reveal reveal-right">
      <p class="about-eyebrow">About EIS</p>
      <h3 class="about-heading">{{ $about->title ?? null }}</h3>

      <div class="about-text-wrap">
        <div class="about-text" id="aboutText">
            {!! $about->description ?? null !!}
        </div>
        <div class="about-text-fade" id="aboutTextFade"></div>
      </div>
    </div>
  </div>
</section>

<section class="stats">
  <div class="stats-inner">
    <p class="stats-eyebrow reveal">OUR CORE STRENGTH</p>
    <h2 class="stats-heading reveal">Standards You Can Stand On</h2>
    


    <div class="stats-row-wrap">
      <div class="stats-row" id="statsRow">
        @foreach (($stat->items ?? []) as $index => $item)
          <div class="stats-item reveal reveal-delay-{{ min($index, 3) }}">
            <div class="stats-icon-line"></div>
            <p class="stats-value" data-count-to="{{ preg_replace('/[^0-9]/', '', $item['value']) }}" data-count-suffix="{{ preg_replace('/[0-9]/', '', $item['value']) }}">{{ $item['value'] }}</p>
            <p class="stats-label">{{ $item['label'] }}</p>
          </div>
        @endforeach
      </div>
    </div>

    <div class="stats-dots" id="statsDots"></div>
  </div>
</section>

<section class="services">
  <div class="services-inner">

    <div class="services-left">
      <p class="services-eyebrow">{{ $serviceSection->label ?? 'Our Services' }}</p>
      <h2 class="services-heading">{{ $serviceSection->heading ?? 'Specialized inspection for drilling and oilfield equipment' }}</h2>
      <p class="services-sub">{{ $serviceSection->description ?? 'Specialized services designed to improve asset integrity, safety and performance' }}</p>

      @if(!empty($serviceSection->image))
        <div
          class="services-photo"
          style="background-image:url('{{ asset('storage/' . $serviceSection->image) }}')"
          role="img"
          aria-label="{{ $serviceSection->heading ?? 'Our services' }}"
        ></div>
      @else
        <div class="services-photo" role="img" aria-label="Offshore oil and gas platform"></div>
      @endif
    </div>

    <div class="services-right">
      @if(($services ?? collect())->isNotEmpty())
  <div class="services-grid" id="servicesGrid">
    @foreach ($services as $service)
      <div class="service-card">
        <div
          class="service-photo"
          style="background-image:url('{{ $service->banner_image ? Storage::url($service->banner_image) : asset('images/hero_image.jpeg') }}')"
          role="img"
          aria-label="{{ $service->banner_title }}"
        ></div>
        <h3 class="service-title">{{ $service->banner_title }}</h3>
        <p class="service-desc">{{ $service->banner_description }}</p>
        <a href="{{ route('service.details', $service->slug) }}" class="service-link">READ MORE <span class="arrow">&#8594;</span></a>
      </div>
    @endforeach
  </div>

  <div class="services-dots" id="servicesDots"></div>
@else
  <p class="services-empty">No services published yet.</p>
@endif
    </div>

  </div>
</section>

@php
   $trustedLogos = collect(optional($clientSection)->images ?? []);
@endphp

<section class="trusted">
  <p class="trusted-eyebrow reveal">Trusted By</p>
  <h2 class="trusted-heading reveal reveal-delay-1">Field, On-Site &amp; Shop-Based Inspection</h2>
  <p class="trusted-sub reveal reveal-delay-2">EIS supports clients with equipped inspection facilities and field-ready systems for oilfield operations.</p>

  <div class="trusted-track-wrap">
    <div class="trusted-track" id="trustedTrack">
      @for ($i = 0; $i < 4; $i++)
    @foreach ($trustedLogos as $logo)
        <div class="trusted-logo">
            <img src="{{ asset('storage/' . $logo) }}"
                 alt="Client logo"
                 loading="lazy">
        </div>
    @endforeach
@endfor
    </div>
  </div>
</section>

@php
  $whyItems = collect($whyChooseUs->items ?? [])->map(function ($item, $index) {
      return [
          'number'  => str_pad($index + 1, 2, '0', STR_PAD_LEFT),
          'label'   => $item['title'] ?? '',
          'heading' => $item['subheading'] ?? '',
          'desc'    => $item['description'] ?? '',
          'image'   => $item['image'] ?? null,
      ];
  })->values()->all();
@endphp

<section class="why">
  <div class="why-top">
  <div class="why-top-left reveal reveal-left">
      <p class="why-eyebrow">Why Choose EIS Ltd</p>
      <h2 class="why-heading">{{ $whyChooseUs->heading ?? 'Inspection Expertise You Can Rely On' }}</h2>
    </div>
    <div class="why-top-right reveal reveal-right">
      <p class="why-sub">{{ $whyChooseUs->description ?? 'Specialized oil and gas inspection services backed by qualified personnel, modern non-destructive testing equipment and practical field experience' }}</p>
    </div>
</div>

  <div class="why-body">
    <div class="why-tabs-box">
      <ul class="why-tabs" id="whyTabs">
        @foreach ($whyItems as $index => $item)
          <li>
            <button type="button" class="why-tab{{ $index === 0 ? ' active' : '' }}" data-why-index="{{ $index }}">
              <span class="why-tab-num">{{ $item['number'] }}</span>
              <span class="why-tab-label">{{ strtoupper($item['label']) }}</span>
            </button>
          </li>
        @endforeach
      </ul>
    </div>

    <div class="why-content-wrap" id="whyPanels">
      @foreach ($whyItems as $index => $item)
        <div class="why-panel{{ $index === 0 ? ' is-active' : '' }}" data-why-index="{{ $index }}">
          <div class="why-panel-text">
            <p class="why-panel-eyebrow">{{ strtoupper($item['label']) }}</p>
            <h3 class="why-panel-heading">{!! nl2br(e($item['heading'])) !!}</h3>
            <p class="why-panel-desc">{{ $item['desc'] }}</p>
          </div>
          <div
  class="why-panel-photo"
  role="img"
  aria-label="{{ $item['label'] }}"
  @if(!empty($item['image']))
    style="background-image:url('{{ asset('storage/' . $item['image']) }}'); background-size:cover; background-position:center;"
  @endif
>
  <span class="why-panel-badge">
    <span class="num">{{ $item['number'] }}</span> {{ strtoupper($item['label']) }}
  </span>
</div>
        </div>
      @endforeach
    </div>
  </div>
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
      <h1 class="presence-heading">Erbil</h1>
      <p class="presence-desc">
        {{-- EIS Ltd has offices in Erbil, Iraq. providing access to oilfield services, machine shops, port facilities, storage and logistics operations. --}}
      {{-- EIS Ltd has offices in Erbil, Iraq, providing access to oilfield services, machine shops, port facilities, storage, and logistics operations. Our Erbil base supports clients across the Kurdistan region with equipment inspection, certification, and maintenance services, backed by a team of qualified inspectors and technicians. From lifting equipment to pressure testing, we help operators keep projects running safely and on schedule, with fast turnaround and direct access to regional supply chains. --}}
    
    EIS Ltd has offices in Erbil, Iraq, providing access to oilfield services, machine shops, port facilities, storage, and logistics operations. Our Erbil base supports clients across the Kurdistan region with equipment inspection, certification, and maintenance services, delivered by qualified inspectors and technicians with fast turnaround and direct access to regional supply chains.</p>
      <a href="{{ url('/contact') }}" class="presence-cta">Contact EIS</a>
    </div>

  </div>
</section>

@include('web.layout.footer')

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const whyTabs = document.querySelectorAll('#whyTabs .why-tab');
    let whyAnimating = false;

    whyTabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        if (whyAnimating) return;
        const index = tab.getAttribute('data-why-index');
        const currentPanel = document.querySelector('#whyPanels .why-panel.is-active');
        const nextPanel = document.querySelector('#whyPanels .why-panel[data-why-index="' + index + '"]');
        if (!nextPanel || nextPanel === currentPanel) return;

        whyAnimating = true;
        whyTabs.forEach(function (t) { t.classList.remove('active'); });
        tab.classList.add('active');

        if (currentPanel) {
          currentPanel.classList.remove('is-active');
          currentPanel.classList.add('is-leaving');
        }
        nextPanel.classList.add('is-active', 'is-entering');

        setTimeout(function () {
          if (currentPanel) currentPanel.classList.remove('is-leaving');
          nextPanel.classList.remove('is-entering');
          whyAnimating = false;
        }, 450);
      });
    });

    // Continuous left-scrolling logo marquee
(function () {
  const track = document.getElementById('trustedTrack');
  if (!track) return;

  let speed = 1.4;
  let isPaused = false;
  let offset = 0;
  let halfWidth = track.scrollWidth / 2; 

  function tick() {
    if (!isPaused) {
      offset += speed;
      if (offset >= halfWidth) {
        offset -= halfWidth;   // seamless reset once one full copy has scrolled past
      }
      track.style.transform = 'translateX(' + (-offset) + 'px)';
    }
    requestAnimationFrame(tick);
  }

  track.addEventListener('mouseenter', function () { isPaused = true; });
  track.addEventListener('mouseleave', function () { isPaused = false; });

  window.addEventListener('load', function () {
    halfWidth = track.scrollWidth / 2;
  });

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (!prefersReducedMotion) {
    requestAnimationFrame(tick);
  }
})();
    // About "show more" toggle
    const aboutText = document.getElementById('aboutText');
    const aboutFade = document.getElementById('aboutTextFade');
    const aboutBtn = document.getElementById('aboutShowMore');

    if (aboutText && aboutBtn) {
      if (aboutText.scrollHeight > aboutText.clientHeight + 4) {
        aboutBtn.style.display = 'inline-flex';

        aboutBtn.addEventListener('click', function () {
          const isOpen = aboutText.classList.toggle('expanded');
          aboutBtn.classList.toggle('is-open', isOpen);
          aboutBtn.querySelector('.label').textContent = isOpen ? 'Show less' : 'Show more';
        });
      } else if (aboutFade) {
        aboutFade.style.display = 'none';
      }
    }

    (function () {
  const row = document.getElementById('statsRow');
  const dotsWrap = document.getElementById('statsDots');
  if (!row || !dotsWrap) return;

  const items = Array.from(row.children);
  if (items.length <= 1) return;

  // Build dots
  items.forEach(function (_, i) {
    const dot = document.createElement('button');
    dot.type = 'button';
    dot.className = 'stats-dot' + (i === 0 ? ' active' : '');
    dot.setAttribute('aria-label', 'Go to stat ' + (i + 1));
    dot.addEventListener('click', function () {
      scrollToIndex(i);
      resetAutoplay();
    });
    dotsWrap.appendChild(dot);
  });

  const dots = Array.from(dotsWrap.children);
  let current = 0;
  let autoplayTimer;
  let isMobile = window.matchMedia('(max-width: 600px)').matches;

  function scrollToIndex(i) {
    const item = items[i];
    if (!item) return;
    row.scrollTo({ left: item.offsetLeft - row.offsetLeft, behavior: 'smooth' });
    current = i;
    dots.forEach(function (d, idx) { d.classList.toggle('active', idx === i); });
  }

  function startAutoplay() {
    if (!isMobile) return;
    clearInterval(autoplayTimer);
    autoplayTimer = setInterval(function () {
      const next = (current + 1) % items.length;
      scrollToIndex(next);
    }, 3200);
  }

  function resetAutoplay() {
    clearInterval(autoplayTimer);
    startAutoplay();
  }

  // Pause autoplay while the user is actively touching/scrolling
  let scrollTimeout;
  row.addEventListener('scroll', function () {
    clearInterval(autoplayTimer);
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(function () {
      // Snap "current" to nearest item after manual scroll settles
      const rowLeft = row.scrollLeft;
      let closest = 0;
      let closestDist = Infinity;
      items.forEach(function (item, i) {
        const dist = Math.abs(item.offsetLeft - row.offsetLeft - rowLeft);
        if (dist < closestDist) { closestDist = dist; closest = i; }
      });
      current = closest;
      dots.forEach(function (d, idx) { d.classList.toggle('active', idx === closest); });
      startAutoplay();
    }, 150);
  });

  window.addEventListener('resize', function () {
    isMobile = window.matchMedia('(max-width: 600px)').matches;
    if (isMobile) startAutoplay();
    else clearInterval(autoplayTimer);
  });

  startAutoplay();
})();
  });

  function animateCount(el) {
    const target = parseInt(el.getAttribute('data-count-to'), 10);
    const suffix = el.getAttribute('data-count-suffix') || '';
    if (!target || isNaN(target)) return;
    const duration = 900;
    const start = performance.now();

    function tick(now) {
      const progress = Math.min((now - start) / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3);
      el.textContent = Math.round(eased * target) + suffix;
      if (progress < 1) requestAnimationFrame(tick);
      else el.textContent = target + suffix;
    }
    requestAnimationFrame(tick);
  }

  const revealEls = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && revealEls.length) {
    const revealObserver = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
          const counter = entry.target.querySelector('[data-count-to]');
          if (counter) animateCount(counter);
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.2, rootMargin: '0px 0px -60px 0px' });

    revealEls.forEach(function (el) { revealObserver.observe(el); });
  } else {
    revealEls.forEach(function (el) { el.classList.add('in-view'); });
    document.querySelectorAll('[data-count-to]').forEach(animateCount);
  }


  (function () {
    const left = document.querySelector('.services-left');
    const right = document.querySelector('.services-right');

    if (!left || !right) return;

    let isSyncing = false;

    left.addEventListener('wheel', function (e) {
        if (window.innerWidth <= 900) return;

        e.preventDefault();

        if (isSyncing) return;

        isSyncing = true;

        right.scrollTop += e.deltaY;

        requestAnimationFrame(function () {
            isSyncing = false;
        });
    }, { passive: false });
})();

  (function () {
  const row = document.getElementById('servicesGrid');
  const dotsWrap = document.getElementById('servicesDots');
  if (!row || !dotsWrap) return;

  const items = Array.from(row.children);
  if (items.length <= 1) return;

  items.forEach(function (_, i) {
    const dot = document.createElement('button');
    dot.type = 'button';
    dot.className = 'services-dot' + (i === 0 ? ' active' : '');
    dot.setAttribute('aria-label', 'Go to service ' + (i + 1));
    dot.addEventListener('click', function () {
      scrollToIndex(i);
      resetAutoplay();
    });
    dotsWrap.appendChild(dot);
  });

  const dots = Array.from(dotsWrap.children);
  let current = 0;
  let autoplayTimer;
  let isMobile = window.matchMedia('(max-width: 560px)').matches;

  function scrollToIndex(i) {
    const item = items[i];
    if (!item) return;
    row.scrollTo({ left: item.offsetLeft - row.offsetLeft, behavior: 'smooth' });
    current = i;
    dots.forEach(function (d, idx) { d.classList.toggle('active', idx === i); });
  }

  function startAutoplay() {
    if (!isMobile) return;
    clearInterval(autoplayTimer);
    autoplayTimer = setInterval(function () {
      const next = (current + 1) % items.length;
      scrollToIndex(next);
    }, 3500);
  }

  function resetAutoplay() {
    clearInterval(autoplayTimer);
    startAutoplay();
  }

  let scrollTimeout;
  row.addEventListener('scroll', function () {
    clearInterval(autoplayTimer);
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(function () {
      const rowLeft = row.scrollLeft;
      let closest = 0;
      let closestDist = Infinity;
      items.forEach(function (item, i) {
        const dist = Math.abs(item.offsetLeft - row.offsetLeft - rowLeft);
        if (dist < closestDist) { closestDist = dist; closest = i; }
      });
      current = closest;
      dots.forEach(function (d, idx) { d.classList.toggle('active', idx === closest); });
      startAutoplay();
    }, 150);
  });

  window.addEventListener('resize', function () {
    isMobile = window.matchMedia('(max-width: 560px)').matches;
    if (isMobile) startAutoplay();
    else clearInterval(autoplayTimer);
  });

  startAutoplay();
})();
</script>

@endsection