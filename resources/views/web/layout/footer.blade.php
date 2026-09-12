{{--
    resources/views/partials/footer.blade.php

    Usage:
      Save this file to resources/views/partials/footer.blade.php
      then include it inside home.blade.php (or any page) with:

          @include('partials.footer')

    Edit the contact details / address / nav links below directly.
--}}

<style>
  .site-footer{
    position:relative;
    background:var(--orange, #E8792D);
    color:#ffffff;
    font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
    overflow-x:hidden;
  }

  .site-footer *{ box-sizing:border-box; }

  .footer-top{
    display:flex;
    align-items:center;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:24px;
    padding:22px 60px;
    border-bottom:1px solid rgba(255,255,255,0.25);
  }

  .footer-brand{
    display:flex;
    align-items:center;
    gap:14px;
    min-width:0;
  }

  .footer-brand-logo{
    height:54px;
    width:auto;
    max-width:60vw;
    display:block;
  }

  .footer-nav{
    display:flex;
    align-items:center;
    gap:34px;
    list-style:none;
    margin:0;
    padding:0;
    flex-wrap:wrap;
  }

  .footer-nav a{
    color:#ffffff;
    text-decoration:none;
    font-size:16px;
    font-weight:600;
    opacity:0.95;
    white-space:nowrap;
  }
  .footer-nav a:hover,
  .footer-nav a:focus-visible{
    opacity:1;
    text-decoration:underline;
    text-underline-offset:5px;
  }

  .footer-social{
    display:flex;
    align-items:center;
    gap:12px;
    flex-shrink:0;
  }

  .footer-social a{
    width:38px;
    height:38px;
    border-radius:50%;
    background:#ffffff;
    color:var(--orange, #E8792D);
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    transition:transform 0.15s ease, background 0.2s ease;
  }
  .footer-social a:hover{
    transform:translateY(-2px);
    background:#f4e9dd;
  }
  .footer-social svg{ width:18px; height:18px; fill:currentColor; }

  .footer-bottom{
    position:relative;
    display:flex;
    align-items:flex-start;
    gap:40px;
    padding:44px 60px 56px;
    flex-wrap:wrap;
  }

  .footer-col{ flex:1; min-width:200px; }
.footer-col.about-col{ flex:0 0 30%; }
.footer-col.contact-col{ flex:0 0 22%; }

@media (min-width: 901px){
  .footer-col,
  .footer-col.about-col,
  .footer-col.contact-col{ text-align:left; }
}

  .footer-col h3{
    font-size:16px;
    font-weight:700;
    line-height:1.4;
    margin:0 0 4px;
    color:#ffffff;
  }

  .footer-col .tagline-line{
    font-size:16px;
    line-height:1.4;
    margin:0 0 26px;
    color:#ffffff;
  }

  .footer-copyright{
    font-size:14.5px;
    color:rgba(255,255,255,0.9);
    margin:0;
  }

  .footer-desc{
    font-size:15.5px;
    line-height:1.6;
    color:#ffffff;
    font-weight:600;
    margin:0 0 22px;
    max-width:460px;
  }

  .footer-link{
    color:#ffffff;
    font-weight:700;
    font-size:15.5px;
    text-decoration:none;
  }
  .footer-link:hover{ text-decoration:underline; }

  .footer-label{
    font-size:13px;
    font-weight:700;
    letter-spacing:0.5px;
    text-transform:uppercase;
    color:rgba(255,255,255,0.85);
    margin:0 0 12px;
  }

  .footer-col p.footer-line{
    font-size:15.5px;
    line-height:1.5;
    color:#ffffff;
    margin:0 0 6px;
    word-break:break-word;
  }

  .footer-col p.footer-line a{
    color:#ffffff;
    text-decoration:none;
  }
  .footer-col p.footer-line a:hover{ text-decoration:underline; }

  .footer-contact-block{ margin-bottom:26px; }
  .footer-contact-block:last-child{ margin-bottom:0; }

  .footer-to-top{
    position:absolute;
    right:60px;
    bottom:16px;
    width:44px;
    height:44px;
    border-radius:8px;
    background:#1b1b1b;
    color:#ffffff;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    font-size:18px;
    transition:transform 0.15s ease, background 0.2s ease;
    flex-shrink:0;
  }
  .footer-to-top:hover{
    background:#000000;
    transform:translateY(-2px);
  }
  .footer-legal{
  border-top:1px solid rgba(255,255,255,0.25);
  padding:16px 60px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:16px;
  flex-wrap:wrap;
}

.footer-legal p{
  font-size:13px;
  color:rgba(255,255,255,0.85);
  margin:0;
}

.footer-legal-links{
  display:flex;
  align-items:center;
  gap:16px;
  list-style:none;
  margin:0;
  padding:0;
}

.footer-legal-links a{
  color:rgba(255,255,255,0.85);
  text-decoration:none;
  font-size:13px;
}
.footer-legal-links a:hover{
  color:#ffffff;
  text-decoration:underline;
}
  /* ===== Tablet ===== */
  @media (max-width: 1024px){
    .footer-top{ padding:20px 40px; }
    .footer-bottom{ padding:40px 40px 56px; gap:28px; }
    .footer-nav{ gap:22px; }
  }

  /* ===== Small tablet / phones — stacked, centered, tidy ===== */
  @media (max-width: 900px){
    .footer-top{
      flex-direction:column;
      padding:26px 24px;
      justify-content:center;
      text-align:center;
      gap:18px;
    }

    .footer-brand{ justify-content:center; }

    .footer-nav{
      justify-content:center;
      gap:14px 22px;
    }

    .footer-social{ justify-content:center; }

    .footer-bottom{
      flex-direction:column;
      align-items:center;
      text-align:center;
      gap:30px;
      padding:38px 24px 100px;
    }

    .footer-col,
    .footer-col.about-col,
    .footer-col.contact-col{
      flex:none;
      width:100%;
      max-width:420px;
      min-width:0;
    }

    .footer-desc{ margin-left:auto; margin-right:auto; }

    .footer-contact-block{ text-align:center; }

    .footer-to-top{
      right:auto;
      left:50%;
      bottom:24px;
      transform:translateX(-50%);
    }
    .footer-to-top:hover{
      transform:translateX(-50%) translateY(-2px);
    }
    .footer-legal{
  flex-direction:column;
  text-align:center;
  padding:16px 24px;
  gap:10px;
}
  }

  /* ===== Phones ===== */
  @media (max-width: 600px){
    .footer-brand-logo{ height:40px; }

    .footer-nav{
      flex-direction:column;
      gap:12px;
    }

    .footer-social{ margin-top:4px; }

    .footer-bottom{ padding:34px 20px 96px; gap:26px; }

    .footer-col h3{ font-size:15.5px; }
    .footer-desc{ max-width:100%; font-size:15px; }
  }

  /* ===== Very small phones ===== */
  @media (max-width: 380px){
    .footer-top{ padding:20px 16px; }
    .footer-bottom{ padding:28px 16px 88px; }
    .footer-col h3{ font-size:15px; }
    .footer-desc,
    .footer-col p.footer-line,
    .footer-link{ font-size:14.5px; }
    .footer-to-top{ width:40px; height:40px; bottom:20px; }
  }
</style>

<footer class="site-footer">
  <div class="footer-top">
    <div class="footer-brand">
      <img src="{{ asset('images/footer_logo.png') }}" alt="Energy Inspection Services Ltd" class="footer-brand-logo">
    </div>

    <ul class="footer-nav">
      <li><a href="{{ url('/') }}">Home</a></li>
      <li><a href="{{ url('/about') }}">About Us</a></li>
      <li><a href="{{ url('/services') }}">Services</a></li>
      <li><a href="{{ url('/contact') }}">Contact</a></li>
      <li><a href="{{ url('/privacy') }}">Privacy</a> | <a href="{{ url('/terms') }}">Terms</a></li>
    </ul>

    <div class="footer-social">
      <a href="https://linkedin.com" target="_blank" rel="noopener" aria-label="LinkedIn">
        <svg viewBox="0 0 24 24"><path d="M20.45 20.45h-3.55v-5.57c0-1.33-.02-3.04-1.85-3.04-1.86 0-2.15 1.45-2.15 2.94v5.67H9.35V9h3.41v1.56h.05c.47-.9 1.63-1.85 3.36-1.85 3.6 0 4.27 2.37 4.27 5.45v6.29zM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12zM7.12 20.45H3.56V9h3.56v11.45z"/></svg>
      </a>
      <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook">
        <svg viewBox="0 0 24 24"><path d="M13.5 21v-7.9h2.65l.4-3.08h-3.05V8.06c0-.89.25-1.5 1.52-1.5h1.63V3.84A21.9 21.9 0 0 0 14.3 3.7c-2.35 0-3.96 1.44-3.96 4.07v2.27H7.68v3.08h2.66V21h3.16z"/></svg>
      </a>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="footer-col about-col">
      <h3>EIS ENERGY INSPECTION SERVICES LTD</h3>
      <p class="tagline-line">Inspection services for the Oil &amp; Gas Industries.</p>
    </div>

    <div class="footer-col">
      <p class="footer-desc">From our roots in Erbil, Iraq, EIS has expanded throughout the Middle East, providing clients with dependable inspection support for critical drilling and oilfield equipment.</p>
      <a href="{{ url('/human-rights-policy') }}" class="footer-link">Human Rights Policy</a>
    </div>

    <div class="footer-col contact-col">
      <div class="footer-contact-block">
        <p class="footer-label">Contact Us</p>
        <p class="footer-line"><a href="tel:+964662575316">+964 662 575316</a></p>
        <p class="footer-line"><a href="mailto:info@eisltd.com">info@eisltd.com</a></p>
      </div>

      <div class="footer-contact-block">
        <p class="footer-label">Head Office</p>
        <p class="footer-line">Gazna Road, Ankawa,<br>Erbil, Iraq</p>
      </div>
    </div>

    <a href="#top" class="footer-to-top" aria-label="Back to top">&#8593;</a>
  </div>
    <div class="footer-legal">
    <p>&copy;{{ date('Y') }} Energy Inspection Services Ltd. All rights reserved.</p>
    <ul class="footer-legal-links">
      <li><a href="{{ url('/privacy') }}">Privacy Policy</a></li>
      <li><a href="{{ url('/terms') }}">Terms of Service</a></li>
    </ul>
  </div>
</footer>