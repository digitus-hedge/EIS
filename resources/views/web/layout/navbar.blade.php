<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
.site-header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 30;
    transition: transform 0.35s ease, background 0.3s ease, box-shadow 0.3s ease;

    background: linear-gradient(
        to bottom,
        rgba(10, 20, 20, 0.30) 0%,
        rgba(10, 20, 20, 0.45) 55%,
        rgba(10, 20, 20, 0) 100%
    );
}

.site-header.is-hidden{
  transform:translateY(-100%);
}

.site-header.is-scrolled{
  background:#ffffff;
  box-shadow:0 4px 18px rgba(0,0,0,0.1);
}

/* Nav text colors: light by default (over hero), dark once scrolled */
.site-navbar .nav-links a,
.site-navbar .nav-toggle{
  color:rgba(255,255,255,0.92);
  transition:color 0.3s ease;
}

.site-header.is-scrolled .site-navbar .nav-links a,
.site-header.is-scrolled .site-navbar .nav-toggle{
  color:#1a1a1a;
}

/* ===== Top info bar — always solid orange, independent of scroll state ===== */
.topbar{
  position:relative;
  background:linear-gradient(90deg, var(--orange) 0%, var(--orange-dark) 100%);
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:3px 60px;
  box-shadow:0 2px 8px rgba(0,0,0,0.12);
}

.topbar *{ box-sizing:border-box; }

.topbar-left{
  display:flex;
  align-items:center;
  gap:0;
  flex-wrap:wrap;
}

.topbar-left span{
  display:flex;
  align-items:center;
  gap:8px;
  color:#ffffff;
  font-size:13px;
  font-weight:500;
  white-space:nowrap;
  padding:0 20px;
  position:relative;
}

/* thin dividers between topbar items instead of a plain gap */
.topbar-left span:not(:last-child)::after{
  content:"";
  position:absolute;
  right:0;
  top:50%;
  transform:translateY(-50%);
  width:1px;
  height:14px;
  background:rgba(255,255,255,0.35);
}

.topbar-left span:first-child{
  padding-left:0;
}

.topbar-left i{
  color:#ffffff;
  font-size:13px;
  opacity:0.95;
}

.topbar-right{
  display:flex;
  align-items:center;
  gap:14px;
}

.topbar-right a{
  color:#ffffff;
  font-size:14px;
  width:30px;
  height:30px;
  border-radius:50%;
  background:rgba(255,255,255,0.15);
  display:flex;
  align-items:center;
  justify-content:center;
  transition:background 0.2s ease, transform 0.2s ease;
}
.topbar-right a:hover{
  background:rgba(255,255,255,0.3);
  transform:translateY(-2px);
}

@media (max-width: 1024px){
  .topbar{ padding:7px 40px; }
}

@media (max-width: 900px){
  .topbar{ padding:6px 24px; }
  .topbar-left span:first-child{ display:none; } /* hide address in the desktop topbar row on tablets */
}

@media (max-width: 600px){
  .topbar{ display:none; } /* hidden on phones — moved into the hamburger menu instead */
}

/* ===== Main navbar ===== */
.site-navbar{
  position:relative;
  z-index:20;
  background:transparent;
  font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
}

.site-navbar *{ box-sizing:border-box; }

.site-navbar .navbar{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:8px 60px;
  flex-wrap:wrap;
}

.site-navbar .brand{
  position:relative;
  display:flex;
  align-items:center;
  gap:14px;
  min-width:0;
}

.site-navbar .brand-logo{
  height:90px;
  width:auto;
  max-width:60vw;
  display:block;
}

.brand-logo-scrolled{
  position:absolute;
  top:0;
  left:0;
  opacity:0;
  pointer-events:none;
  transition:opacity 0.3s ease;
}

.site-header.is-scrolled .brand-logo-top{
  opacity:0;
}

.site-header.is-scrolled .brand-logo-scrolled{
  opacity:1;
  pointer-events:auto;
}

.site-navbar .nav-links{
  display:flex;
  align-items:center;
  gap:40px;
  list-style:none;
  margin:0;
  padding:0;
}

.site-navbar .nav-links a{
  position:relative;
  text-decoration:none;
  font-size:15.5px;
  font-weight:600;
  letter-spacing:0.2px;
  padding:4px 0;
  transition:color 0.2s ease;
}

.site-navbar .nav-links a::after{
  content:"";
  position:absolute;
  left:0;
  bottom:0;
  width:0;
  height:2px;
  background:var(--orange);
  transition:width 0.25s ease;
}

.site-navbar .nav-links a:hover::after,
.site-navbar .nav-links a.active::after{
  width:100%;
}

.site-navbar .nav-links a.active{
  color:var(--orange);
}

.site-header.is-scrolled .site-navbar .nav-links a.active{
  color:var(--orange);
}

.site-navbar .nav-toggle{ display:none; }

/* ===== Mobile-only info block inside the hamburger menu ===== */
.nav-mobile-info{
  display:none;
}

/* ===== Mobile-only CTA buttons inside the hamburger menu ===== */
.nav-mobile-cta{
  display:none;
}

/* ===== Tablet ===== */
@media (max-width: 1024px){
  .site-navbar .navbar{ padding:11px 40px; }
}

/* ===== Small tablet / large phone — hamburger menu ===== */
@media (max-width: 900px){
  .site-navbar .navbar{ padding:10px 24px; }

  .site-navbar .nav-links{
    position:absolute;
    top:calc(100% + 1px);
    left:0; right:0;
    max-height:calc(100vh - 76px);
    overflow-y:auto;
    flex-direction:column;
    align-items:stretch;
    background:#ffffff;
    box-shadow:0 12px 28px rgba(0,0,0,0.15);
    padding:24px 24px 20px;
    gap:18px;
    display:none;
  }
  .site-navbar .nav-links.open{ display:flex; }

  /* Force dark text + orange active state on the white mobile menu, regardless of scroll state */
  .site-navbar .nav-links a{
    color:#1a1a1a !important;
    font-size:17px;
    padding:6px 0;
    border-bottom:1px solid #eee;
  }
  .site-navbar .nav-links a::after{ display:none; }
  .site-navbar .nav-links a.active{
    color:var(--orange) !important;
  }
  .site-navbar .nav-links li:last-of-type a{
    border-bottom:none;
  }

  .site-navbar .nav-toggle{
    display:block;
    background:none;
    border:none;
    font-size:24px;
    cursor:pointer;
    line-height:1;
    padding:4px 8px;
    border-radius:6px;
    transition:background 0.2s ease;
  }

  .site-header.is-scrolled .site-navbar .nav-toggle{
    color:#1a1a1a;
  }

  /* Contact + socials block, only visible inside the open mobile menu */
  .nav-mobile-info{
    display:block;
    margin-top:6px;
    padding-top:18px;
    border-top:1px solid #eee;
  }

  .nav-mobile-info-item{
    display:flex;
    align-items:center;
    gap:10px;
    color:#4a4a4a;
    font-size:14px;
    margin-bottom:12px;
  }
  .nav-mobile-info-item i{
    color:var(--orange);
    font-size:15px;
    flex-shrink:0;
  }

  .nav-mobile-socials{
    display:flex;
    align-items:center;
    gap:14px;
    margin-top:10px;
  }
  .nav-mobile-socials a{
    color:#ffffff !important;
    width:34px;
    height:34px;
    border-radius:50%;
    background:var(--orange);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:16px;
    transition:background 0.2s ease, transform 0.2s ease;
  }
  .nav-mobile-socials a:hover{
    background:var(--orange-dark) !important;
    transform:translateY(-2px);
  }

  /* Contact Us / Explore Services buttons — mobile hamburger menu only */
  .nav-mobile-cta{
    display:flex;
    flex-direction:column;
    gap:12px;
    margin-top:18px;
    padding-top:18px;
    border-top:1px solid #eee;
  }

  .nav-mobile-cta a{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:100%;
    padding:13px 20px;
    border-radius:30px;
    font-size:15px;
    font-weight:700;
    text-decoration:none;
    border:2px solid transparent;
    transition:transform 0.15s ease, background 0.2s ease, color 0.2s ease;
  }

  .nav-mobile-cta a.btn-primary{
    background:var(--orange) !important;
    color:#ffffff !important;
    border-color:var(--orange);
  }
  .nav-mobile-cta a.btn-primary:hover{
    background:var(--orange-dark) !important;
  }

  .nav-mobile-cta a.btn-secondary{
    background:transparent !important;
    color:#1a1a1a !important;
    border-color:#dcdcdc;
  }
  .nav-mobile-cta a.btn-secondary:hover{
    background:#f5f5f5 !important;
  }
}

/* ===== Phones ===== */
@media (max-width: 600px){
  .site-navbar .navbar{ padding:10px 18px; }
  .site-navbar .brand-logo{ height:36px; }

  .site-navbar .nav-links{
    top:calc(100% + 1px);
    max-height:calc(100vh - 64px);
  }
}
</style>

<header class="site-header" id="siteHeader">
  <div class="topbar">
    <div class="topbar-left">
      <span><i class="bi bi-geo-alt"></i> Gazna Road, Ankawa, Erbil, Iraq</span>
      <span><i class="bi bi-telephone"></i> +964 662 575316</span>
      <span><i class="bi bi-envelope"></i> info@eisltd.com</span>
    </div>
    <div class="topbar-right">
      <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
      <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
      <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
    </div>
  </div>

  <div class="site-navbar">
    <nav class="navbar">
      <div class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="Energy Inspection Services Ltd" class="brand-logo brand-logo-top">
        <img src="{{ asset('images/hero_logo.png') }}" alt="Energy Inspection Services Ltd" class="brand-logo brand-logo-scrolled">
      </div>

      <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation" aria-expanded="false">&#9776;</button>

      <ul class="nav-links" id="navLinks">
        <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
        <li><a href="{{ url('/about') }}" class="{{ request()->is('about') ? 'active' : '' }}">About Us</a></li>
         <li><a href="{{ url('/services') }}" class="{{ request()->is('services*') ? 'active' : '' }}">Services</a></li>
        <li><a href="{{ url('/contact') }}" class="{{ request()->is('contact') ? 'active' : '' }}">Contact</a></li>

        <li class="nav-mobile-cta">
          <a href="{{ url('/contact') }}" class="btn-primary">Contact Us Now</a>
          <a href="{{ url('/services') }}" class="btn-secondary">Explore Services</a>
        </li>

        <li class="nav-mobile-info">
          <div class="nav-mobile-info-item">
            <i class="bi bi-geo-alt"></i> Gazna Road, Ankawa, Erbil, Iraq
          </div>
          <div class="nav-mobile-info-item">
            <i class="bi bi-telephone"></i> +964 662 575316
          </div>
          <div class="nav-mobile-info-item">
            <i class="bi bi-envelope"></i> info@eisltd.com
          </div>
          <div class="nav-mobile-socials">
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
          </div>
        </li>
      </ul>
    </nav>
  </div>
</header>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('navToggle');
    const links = document.getElementById('navLinks');
    if (toggle && links) {
      toggle.addEventListener('click', function () {
        const isOpen = links.classList.toggle('open');
        toggle.setAttribute('aria-expanded', isOpen);
      });
    }

    // Hide header on scroll down, reveal on scroll up
    const header = document.getElementById('siteHeader');
    if (header) {
      let lastScrollY = window.scrollY;
      let ticking = false;
      const hideThreshold = 80;

      function updateHeader() {
        const currentScrollY = window.scrollY;

        if (currentScrollY <= hideThreshold) {
          header.classList.remove('is-hidden');
        } else if (currentScrollY > lastScrollY) {
          header.classList.add('is-hidden');
        } else {
          header.classList.remove('is-hidden');
        }

        header.classList.toggle('is-scrolled', currentScrollY > hideThreshold);

        lastScrollY = currentScrollY;
        ticking = false;
      }

      window.addEventListener('scroll', function () {
        if (!ticking) {
          window.requestAnimationFrame(updateHeader);
          ticking = true;
        }
      });
    }
  });
</script>