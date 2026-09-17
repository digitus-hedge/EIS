@extends('web.layout.app')
@section('title', $contact->meta_title ?? 'Contact Us - Energy Inspection Services Ltd')
@section('meta_description', $contact->meta_description ?? 'Get in touch with Energy Inspection Services Ltd for oil and gas inspection services in Erbil, Iraq.')

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

/* When a video is playing, drop all dark overlays and let it show clean */
.hero.has-video::before,
.hero.has-video::after{
    display:none;
}

.hero.has-video h1,
.hero.has-video .eyebrow,
.hero.has-video .lede{
    text-shadow: 0 2px 12px rgba(0,0,0,0.75);
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
    margin-bottom:22px;
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
    font-size:clamp(28px, 4.2vw, 56px);
    line-height:1.20;
    font-weight:800;
    letter-spacing:0.5px;
    margin:0 0 20px;
    text-shadow: 0 2px 24px rgba(0,0,0,0.35);
    word-break:break-word;
}

.hero .lede{
    color:rgba(255,255,255,0.92);
    font-size:clamp(15px, 1.5vw, 18px);
    line-height:1.6;
    max-width:600px;
    margin:0;
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
    .hero h1{ margin-bottom:16px; }
    .hero .lede{ margin-bottom:0; }
    .hero-vignette{ box-shadow:inset 0 0 80px rgba(0,0,0,0.28); }
}

@media (max-width: 380px){
    .hero .hero-content{ padding:100px 16px 32px; }
    .hero .eyebrow{ font-size:17px; }
}

@media (prefers-reduced-motion: reduce){
    .hero-slide{ transition:opacity 1.2s ease; transform:none !important; }
}

    /* ===== Contact info section ===== */
    .contact-info{
        position:relative;
        background:#ffffff;
        font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
        padding:80px 60px 60px;
    }

    .contact-info *{ box-sizing:border-box; }

    .contact-info-inner{
        display:grid;
        grid-template-columns:repeat(3, 1fr);
        gap:28px;
        max-width:1300px;
        margin:0 auto;
    }

    .contact-card{
        background:#ffffff;
        border:1px solid #ececec;
        border-radius:20px;
        padding:36px 30px;
        box-shadow:0 8px 22px rgba(0,0,0,0.05);
        transition:transform 0.35s cubic-bezier(0.16,1,0.3,1), box-shadow 0.35s ease;
    }

    .contact-card:hover{
        transform:translateY(-6px);
        box-shadow:0 20px 40px rgba(0,0,0,0.1);
    }

    .contact-icon{
        width:54px;
        height:54px;
        border-radius:50%;
        background:rgba(232,121,45,0.12);
        color:var(--orange);
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:22px;
        margin-bottom:20px;
    }

    .contact-card-title{
        font-size:15px;
        font-weight:700;
        text-transform:uppercase;
        letter-spacing:0.5px;
        color:#888;
        margin:0 0 10px;
    }

    .contact-card-value{
        font-size:19px;
        font-weight:600;
        color:#111111;
        margin:0;
        word-break:break-word;
        text-decoration:none;
        display:block;
    }

    a.contact-card-value:hover{ color:var(--orange); }

    @media (max-width: 900px){
        .contact-info{ padding:56px 24px 64px; }
        .contact-info-inner{ grid-template-columns:1fr; gap:20px; }
    }

    @media (max-width: 480px){
        .contact-info{ padding:44px 16px 50px; }
        .contact-card{ padding:28px 22px; }
    }

    /* ===== Reveal animation ===== */
    .reveal{
        opacity:0;
        transform:translateY(32px);
        transition:opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal.in-view{ opacity:1; transform:translateY(0); }
    .reveal-delay-1{ transition-delay:0.1s; }
    .reveal-delay-2{ transition-delay:0.2s; }

    @media (prefers-reduced-motion: reduce){
        .reveal, .reveal.in-view{ opacity:1 !important; transform:none !important; transition:none !important; }
    }

    .get-in-touch{
        position:relative;
        background:#ffffff;
        font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
        padding:90px 60px 40px;
    }

    .get-in-touch *{ box-sizing:border-box; }

    .get-in-touch-inner{
        display:flex;
        align-items:stretch;
        max-width:1400px;
        margin:0 auto;
        border-radius:26px;
        overflow:hidden;
        box-shadow:0 24px 50px rgba(0,0,0,0.14);
    }

    .get-in-touch-panel{
        flex:0 0 46%;
        max-width:46%;
        background:var(--orange);
        color:#ffffff;
        padding:56px 50px;
        display:flex;
        flex-direction:column;
        justify-content:center;
    }

    .get-in-touch-eyebrow{
        font-size:16px;
        font-weight:700;
        margin:0 0 16px;
        color:rgba(255,255,255,0.95);
    }

    .get-in-touch-heading{
        font-size:clamp(28px, 3vw, 38px);
        font-weight:800;
        line-height:1.2;
        margin:0 0 18px;
    }

    .get-in-touch-desc{
        font-size:17px;
        line-height:1.6;
        color:rgba(255,255,255,0.92);
        margin:0 0 34px;
        max-width:400px;
    }

    .get-in-touch-list{
        display:flex;
        flex-direction:column;
        gap:26px;
    }

    .get-in-touch-item{
        display:flex;
        align-items:flex-start;
        gap:16px;
    }

    .get-in-touch-icon{
        flex:0 0 auto;
        width:42px;
        height:42px;
        border-radius:50%;
        border:1.5px solid rgba(255,255,255,0.7);
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:16px;
        color:#ffffff;
    }

    .get-in-touch-label{
        font-size:14px;
        font-weight:700;
        margin:0 0 4px;
        color:#ffffff;
    }

    .get-in-touch-value{
        font-size:14.5px;
        color:rgba(255,255,255,0.92);
        text-decoration:underline;
        text-underline-offset:2px;
        margin:0;
        display:inline-block;
    }

    a.get-in-touch-value:hover{ color:#ffffff; }

    .get-in-touch-address{
        text-decoration:none;
        line-height:1.5;
    }

    .get-in-touch-photo{
        flex:1;
        min-height:420px;
        background-color:#e8e8e8;
        background-position:center;
        background-size:cover;
        background-repeat:no-repeat;
    }

    .get-in-touch-inner.reveal{
        opacity:0;
        transform:translateY(40px);
        transition:opacity 0.85s cubic-bezier(0.16,1,0.3,1), transform 0.85s cubic-bezier(0.16,1,0.3,1);
    }

    .get-in-touch-inner.reveal.in-view{
        opacity:1;
        transform:translateY(0);
    }

    @media (prefers-reduced-motion: reduce){
        .get-in-touch-inner.reveal,
        .get-in-touch-inner.reveal.in-view{
            opacity:1 !important;
            transform:none !important;
            transition:none !important;
        }
    }

    @media (max-width: 900px){
        .get-in-touch{ padding:50px 24px 20px; }
        .get-in-touch-inner{ flex-direction:column; border-radius:20px; }
        .get-in-touch-panel,
        .get-in-touch-photo{ flex-basis:auto; max-width:none; width:100%; }
        .get-in-touch-panel{ padding:40px 28px; }
        .get-in-touch-photo{ min-height:280px; }
    }

    @media (max-width: 480px){
        .get-in-touch{ padding:36px 16px 16px; }
        .get-in-touch-panel{ padding:32px 22px; }
        .get-in-touch-heading{ font-size:26px; }
    }

   .enquiry{
    position:relative;
    background:#ffffff;
    font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
    padding:80px 60px 110px;
    overflow:hidden;
}

.enquiry *{ box-sizing:border-box; }

.enquiry::before{
    content:"";
    position:absolute;
    top:-160px;
    right:-160px;
    width:380px;
    height:380px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(232,121,45,0.06) 0%, transparent 70%);
    pointer-events:none;
}

.enquiry::after{
    content:"";
    position:absolute;
    bottom:-180px;
    left:-140px;
    width:340px;
    height:340px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(232,121,45,0.05) 0%, transparent 70%);
    pointer-events:none;
}

.enquiry-inner{
    position:relative;
    max-width:1200px;
    margin:0 auto;
}

.enquiry-top{
    text-align:center;
    margin-bottom:44px;
}

.enquiry-eyebrow{
    color:var(--orange);
    font-weight:700;
    font-size:15px;
    letter-spacing:1px;
    text-transform:uppercase;
    margin:0 0 14px;
}

.enquiry-heading{
    font-size:clamp(28px, 3.6vw, 42px);
    line-height:1.2;
    font-weight:700;
    color:#111111;
    margin:0 0 16px;
}

.enquiry-sub{
    font-size:15.5px;
    line-height:1.6;
    color:#666666;
    margin:0;
}

.required-star{
    color:var(--orange);
}

.enquiry-success{
    display:flex;
    align-items:center;
    gap:10px;
    background:#e9f9ee;
    border:1px solid #b7e6c4;
    color:#1e7a3c;
    border-radius:12px;
    padding:16px 22px;
    font-size:15px;
    font-weight:600;
    margin-bottom:28px;
    animation: enquiryPop 0.5s cubic-bezier(0.34,1.56,0.64,1);
}

.enquiry-error{
    background:#fdecec;
    border:1px solid #f3b6b6;
    color:#a11f1f;
    border-radius:12px;
    padding:16px 22px;
    font-size:14.5px;
    margin-bottom:28px;
    animation: enquiryPop 0.5s cubic-bezier(0.34,1.56,0.64,1);
}

.enquiry-error ul{ margin:0; padding-left:18px; }

@keyframes enquiryPop{
    from{ opacity:0; transform:translateY(-10px) scale(0.97); }
    to{ opacity:1; transform:translateY(0) scale(1); }
}

/* ===== Card wrapper ===== */
.enquiry-card{
    position:relative;
    background:linear-gradient(165deg, #FFF3E7 0%, #FFEAD6 100%);
    border-radius:28px;
    padding:48px 50px;
    box-shadow:
        0 30px 70px rgba(0,0,0,0.08),
        0 4px 14px rgba(0,0,0,0.04),
        0 0 0 1px rgba(232,121,45,0.08);
    border:1px solid #F0D8BE;
}

.enquiry-form{
    display:flex;
    flex-direction:column;
    gap:26px;
}

.enquiry-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:24px;
}

.enquiry-row-single{
    grid-template-columns:1fr;
}

/* ===== Field with icon + floating label ===== */
.enquiry-field{
    position:relative;
    display:flex;
    flex-direction:column;
}

.enquiry-field-icon{
    position:absolute;
    left:18px;
    top:18px;
    color:#b8b8b8;
    font-size:16px;
    transition:color 0.25s ease;
    pointer-events:none;
    z-index:2;
}

.enquiry-field textarea ~ .enquiry-field-icon{
    top:18px;
}

.enquiry-field label{
    position:absolute;
    left:46px;
    top:16px;
    font-size:15px;
    color:#999999;
    pointer-events:none;
    transition:all 0.22s cubic-bezier(0.16,1,0.3,1);
    background:#ffffff;
    padding:0 4px;
}

.enquiry-field input,
.enquiry-field textarea{
    width:100%;
    padding:18px 18px 18px 46px;
    border:1.5px solid #EAD5BC;
    border-radius:12px;
    font-size:15px;
    font-family:inherit;
    outline:none;
    background:#ffffff;
    color:#111111;
    transition:border-color 0.25s ease, box-shadow 0.25s ease;
}

.enquiry-field textarea{
    resize:vertical;
    min-height:150px;
    padding-top:18px;
}

.enquiry-field input:focus,
.enquiry-field textarea:focus{
    border-color:var(--orange);
    box-shadow:0 0 0 4px rgba(232,121,45,0.1);
}

.enquiry-field input:focus ~ .enquiry-field-icon,
.enquiry-field textarea:focus ~ .enquiry-field-icon{
    color:var(--orange);
}

/* Floating label: shrinks up when focused OR has content */
.enquiry-field input:focus ~ label,
.enquiry-field input:not(:placeholder-shown) ~ label,
.enquiry-field textarea:focus ~ label,
.enquiry-field textarea:not(:placeholder-shown) ~ label{
    top:-9px;
    left:38px;
    font-size:12px;
    font-weight:700;
    color:var(--orange);
}

.enquiry-actions{
    display:flex;
    justify-content:flex-end;
    margin-top:6px;
}

.enquiry-submit{
    position:relative;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    padding:17px 44px;
    border:none;
    border-radius:30px;
    background:linear-gradient(135deg, var(--orange) 0%, var(--orange-dark) 100%);
    color:#ffffff;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
    overflow:hidden;
    transition:transform 0.25s ease, box-shadow 0.25s ease;
    box-shadow:0 14px 30px rgba(232,121,45,0.28);
}

.enquiry-submit::before{
    content:"";
    position:absolute;
    top:0;
    left:-75%;
    width:50%;
    height:100%;
    background:linear-gradient(120deg, transparent, rgba(255,255,255,0.4), transparent);
    transform:skewX(-20deg);
    transition:left 0.6s ease;
}

.enquiry-submit:hover{
    transform:translateY(-3px);
    box-shadow:0 20px 40px rgba(232,121,45,0.38);
}

.enquiry-submit:hover::before{
    left:125%;
}

.enquiry-submit .arrow{
    transition:transform 0.25s ease;
}
.enquiry-submit:hover .arrow{
    transform:translateX(4px);
}

/* ===== Entrance animation, staggered per field ===== */
.enquiry-card.reveal{
    opacity:0;
    transform:translateY(36px);
    transition:opacity 0.8s cubic-bezier(0.16,1,0.3,1), transform 0.8s cubic-bezier(0.16,1,0.3,1);
}
.enquiry-card.reveal.in-view{
    opacity:1;
    transform:translateY(0);
}

.enquiry-field{
    opacity:0;
    transform:translateY(16px);
    transition:opacity 0.5s ease, transform 0.5s ease;
}
.enquiry-card.reveal.in-view .enquiry-field{
    opacity:1;
    transform:translateY(0);
}
.enquiry-card.reveal.in-view .enquiry-row:nth-of-type(1) .enquiry-field:nth-child(1){ transition-delay:0.1s; }
.enquiry-card.reveal.in-view .enquiry-row:nth-of-type(1) .enquiry-field:nth-child(2){ transition-delay:0.18s; }
.enquiry-card.reveal.in-view .enquiry-row:nth-of-type(2) .enquiry-field{ transition-delay:0.26s; }
.enquiry-card.reveal.in-view .enquiry-row:nth-of-type(3) .enquiry-field:nth-child(1){ transition-delay:0.34s; }
.enquiry-card.reveal.in-view .enquiry-row:nth-of-type(3) .enquiry-field:nth-child(2){ transition-delay:0.4s; }
.enquiry-card.reveal.in-view .enquiry-row:nth-of-type(4) .enquiry-field{ transition-delay:0.46s; }

@media (prefers-reduced-motion: reduce){
    .enquiry-submit,
    .enquiry-submit::before,
    .enquiry-card.reveal,
    .enquiry-field{
        transition:none !important;
        animation:none !important;
        opacity:1 !important;
        transform:none !important;
    }
}

@media (max-width: 900px){
    .enquiry{ padding:60px 24px 70px; }
    .enquiry-card{ padding:36px 28px; border-radius:22px; }
    .enquiry-row{ grid-template-columns:1fr; gap:22px; }
}

@media (max-width: 600px){
    .enquiry{ padding:48px 18px 56px; }
    .enquiry-heading{ font-size:clamp(24px, 6.5vw, 30px); }
    .enquiry-card{ padding:28px 20px; border-radius:18px; }
    .enquiry-actions{ justify-content:stretch; }
    .enquiry-submit{ width:100%; }
}

@media (max-width: 380px){
    .enquiry-card{ padding:22px 16px; }
    .enquiry-field input,
    .enquiry-field textarea{ padding:16px 16px 16px 42px; font-size:14px; }
    .enquiry-field-icon{ font-size:14px; left:16px; top:17px; }
}
</style>

<section class="hero @if(!empty($contact->banner_video)) has-video @endif">
@include('web.layout.navbar')

    @if (!empty($contact->banner_video))
        <video
            class="hero-video"
            src="{{ Storage::url($contact->banner_video) }}"
            poster="{{ $contact->banner_image ? Storage::url($contact->banner_image) : '' }}"
            autoplay
            muted
            loop
            playsinline
            preload="auto"
        ></video>
    @elseif ($contact && $contact->banner_image)
        <div class="hero-slides">
            <img
                src="{{ Storage::url($contact->banner_image) }}"
                class="hero-slide active"
                alt="{{ $contact->banner_title ?? 'Contact banner' }}"
            >
        </div>
    @else
        <div class="hero-slides">
            <img src="{{ asset('images/hero_image.jpeg') }}" class="hero-slide active" alt="Contact banner">
        </div>
    @endif

    @if (empty($contact->banner_video))
        <div class="hero-vignette" aria-hidden="true"></div>
    @endif

    <div class="hero-content">
        <div class="hero-inner">
            <p class="eyebrow">Get in Touch</p>
            <h1>{{ $contact->banner_title ?? 'Contact Us' }}</h1>
            @if (!empty($contact->banner_description))
                <p class="lede">{{ $contact->banner_description }}</p>
            @endif
        </div>
    </div>
</section>

<section class="get-in-touch">
    <div class="get-in-touch-inner reveal">
        <div class="get-in-touch-panel">
            <p class="get-in-touch-eyebrow">Need any help?</p>
            <h2 class="get-in-touch-heading">Get in touch with us</h2>
            <p class="get-in-touch-desc">
                To contact Energy Inspection Services Ltd, use the details below,
                or send us a message through the enquiry form.
            </p>

            <div class="get-in-touch-list">
                @if ($contact && $contact->phone)
                    <div class="get-in-touch-item">
                        <span class="get-in-touch-icon"><i class="bi bi-telephone-fill"></i></span>
                        <div>
                            <p class="get-in-touch-label">Phone</p>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact->phone) }}" class="get-in-touch-value">{{ $contact->phone }}</a>
                        </div>
                    </div>
                @endif

                @if ($contact && $contact->email)
                    <div class="get-in-touch-item">
                        <span class="get-in-touch-icon"><i class="bi bi-envelope-fill"></i></span>
                        <div>
                            <p class="get-in-touch-label">Email</p>
                            <a href="mailto:{{ $contact->email }}" class="get-in-touch-value">{{ $contact->email }}</a>
                        </div>
                    </div>
                @endif

                @if ($contact && $contact->address)
                    <div class="get-in-touch-item">
                        <span class="get-in-touch-icon"><i class="bi bi-geo-alt-fill"></i></span>
                        <div>
                            <p class="get-in-touch-label">Address</p>
                            <p class="get-in-touch-value get-in-touch-address">{{ $contact->address }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="get-in-touch-photo"
             style="background-image:url('{{ $contact && $contact->contact_image ? Storage::url($contact->contact_image) : asset('images/hero_image.jpeg') }}')"></div>
    </div>
</section>

<section class="enquiry">
    <div class="enquiry-inner">

        <div class="enquiry-top reveal">
            <p class="enquiry-eyebrow">General Enquiries</p>
            <h2 class="enquiry-heading">Send us an enquiry</h2>
            <p class="enquiry-sub">
                Complete the form below and our team will review your enquiry.
            </p>
        </div>

        @if (session('success'))
            <div class="enquiry-success reveal">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="enquiry-error reveal">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="enquiry-card reveal">
            <form class="enquiry-form" method="POST" action="{{ route('enquiry.store') }}">
                @csrf

                <div class="enquiry-row">
                    <div class="enquiry-field">
                        <i class="bi bi-person enquiry-field-icon"></i>
                        <input type="text" id="fullName" name="full_name" value="{{ old('full_name') }}" placeholder=" ">
                        <label for="fullName">Full name</label>
                    </div>
                    <div class="enquiry-field">
                        <i class="bi bi-envelope enquiry-field-icon"></i>
                        <input type="email" id="emailAddress" name="email" value="{{ old('email') }}" placeholder=" ">
                        <label for="emailAddress">Email address</label>
                    </div>
                </div>

                <div class="enquiry-row enquiry-row-single">
                    <div class="enquiry-field">
                        <i class="bi bi-geo-alt enquiry-field-icon"></i>
                        <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder=" " required>
                        <label for="address">Address</label>
                    </div>
                </div>

                <div class="enquiry-row">
                    <div class="enquiry-field">
                        <i class="bi bi-building enquiry-field-icon"></i>
                        <input type="text" id="townCity" name="town_city" value="{{ old('town_city') }}" placeholder=" ">
                        <label for="townCity">Town/City</label>
                    </div>
                    <div class="enquiry-field">
                        <i class="bi bi-flag enquiry-field-icon"></i>
                        <input type="text" id="country" name="country" value="{{ old('country') }}" placeholder=" ">
                        <label for="country">Country</label>
                    </div>
                </div>

                <div class="enquiry-row enquiry-row-single">
                    <div class="enquiry-field">
                        <i class="bi bi-chat-left-text enquiry-field-icon"></i>
                        <textarea id="comments" name="comments" rows="6" placeholder=" ">{{ old('comments') }}</textarea>
                        <label for="comments">Comments</label>
                    </div>
                </div>

                <div class="enquiry-actions">
                    <button type="submit" class="enquiry-submit">
                        Submit Enquiry <span class="arrow">&#8594;</span>
                    </button>
                </div>
            </form>
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
</script>
@endsection