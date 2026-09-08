<style>
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
    padding:22px 60px;
    flex-wrap:wrap;
  }

  .site-navbar .brand{
    display:flex;
    align-items:center;
    gap:14px;
    min-width:0;
  }

  .site-navbar .brand-logo{
    height:54px;
    width:auto;
    max-width:60vw;
    display:block;
  }

  .site-navbar .nav-links{
    display:flex;
    align-items:center;
    gap:44px;
    list-style:none;
    margin:0;
    padding:0;
  }

  .site-navbar .nav-links a{
    color:var(--white);
    text-decoration:none;
    font-size:16px;
    font-weight:500;
    opacity:0.95;
    transition:opacity 0.2s ease;
    white-space:nowrap;
  }
  .site-navbar .nav-links a:hover,
  .site-navbar .nav-links a:focus-visible{
    opacity:1;
    text-decoration:underline;
    text-underline-offset:6px;
    text-decoration-color:var(--orange);
  }

  .site-navbar .nav-toggle{ display:none; }

  /* ===== Tablet ===== */
  @media (max-width: 1024px){
    .site-navbar .navbar{ padding:20px 40px; }
  }

  /* ===== Small tablet / large phone ===== */
  @media (max-width: 900px){
    .site-navbar .navbar{ padding:18px 24px; }
    .site-navbar .nav-links{
      position:absolute;
      top:76px; left:0; right:0;
      flex-direction:column;
      align-items:flex-start;
      background:rgba(10,20,20,0.96);
      padding:20px 24px;
      gap:20px;
      display:none;
    }
    .site-navbar .nav-links.open{ display:flex; }
    .site-navbar .nav-toggle{
      display:block;
      background:none;
      border:none;
      color:var(--white);
      font-size:26px;
      cursor:pointer;
      line-height:1;
      padding:4px 8px;
    }
  }

  /* ===== Phones ===== */
  @media (max-width: 600px){
    .site-navbar .navbar{ padding:16px 18px; }
    .site-navbar .brand-logo{ height:40px; }
  }
</style>

<header class="site-navbar">
  <nav class="navbar">
    <div class="brand">
      <img src="{{ asset('images/hero_logo.png') }}" alt="Energy Inspection Services Ltd" class="brand-logo">
    </div>

    <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation" aria-expanded="false">&#9776;</button>

    <ul class="nav-links" id="navLinks">
      <li><a href="{{ url('/') }}">Home</a></li>
      <li><a href="{{ url('/about') }}">About Us</a></li>
      <li><a href="{{ url('/services') }}">Services</a></li>
      <li><a href="{{ url('/contact') }}">Contact</a></li>
    </ul>
  </nav>
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
  });
</script>