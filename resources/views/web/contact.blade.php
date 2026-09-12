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

    .hero{
        position:relative;
        min-height:60vh;
        min-height:60svh;
        display:flex;
        flex-direction:column;
        background:
            radial-gradient(ellipse at 70% 25%, rgba(120,190,190,0.35), transparent 20%),
            linear-gradient(100deg, rgba(10,20,20,0.82) 0%, rgba(10,20,20,0.35) 42%, rgba(60,90,90,0.15) 60%, rgba(10,20,20,0.55) 100%);
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
        opacity:0;
        transition:opacity 1.2s ease;
    }

    .hero-slide.active{ opacity:1; z-index:1; }

    .hero .hero-content{
        position:relative;
        z-index:5;
        flex:1;
        display:flex;
        align-items:center;
        padding:0 90px;
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
        font-size:clamp(28px, 4.2vw, 56px);
        line-height:1.20;
        font-weight:800;
        letter-spacing:0.5px;
        margin:0;
        text-shadow: 0 2px 24px rgba(0,0,0,0.35);
        word-break:break-word;
    }

    @media (max-width: 1024px){ .hero .hero-content{ padding:0 50px; } }
    @media (max-width: 900px){ .hero .hero-content{ padding:0 24px; } }

    @media (max-width: 600px){
        .hero{ min-height:40vh; min-height:40svh; }
        .hero .hero-content{ padding:60px 20px 60px; align-items:flex-start; }
        .hero .eyebrow{ font-size:15px; margin-bottom:14px; }
    }

    @media (max-width: 380px){
        .hero .hero-content{ padding:48px 16px 48px; }
        .hero .eyebrow{ font-size:14px; }
    }

    @media (prefers-reduced-motion: reduce){ .hero-slide{ transition:none; } }

    /* ===== Contact info section ===== */
    .contact-info{
        position:relative;
        background:#ffffff;
        font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
        padding:80px 60px 100px;
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
    padding:30px 60px 40px;
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

/* Reveal animation */
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
    padding:60px 60px 100px;
}

.enquiry *{ box-sizing:border-box; }

.enquiry-inner{
    max-width:1060px;
    margin:0 auto;
}

.enquiry-top{
    text-align:center;
    margin-bottom:44px;
}

.enquiry-eyebrow{
    color:var(--orange);
    font-weight:700;
    font-size:19px;
    margin:0 0 14px;
}

.enquiry-heading{
    font-size:clamp(30px, 3.6vw, 46px);
    line-height:1.2;
    font-weight:400;
    color:#111111;
    margin:0 0 18px;
}

.enquiry-sub{
    font-size:15.5px;
    line-height:1.6;
    color:#444444;
    margin:0;
}

.required-star{
    color:#e8792d;
}

.enquiry-form{
    display:flex;
    flex-direction:column;
    gap:26px;
}

.enquiry-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:26px;
}

.enquiry-row-single{
    grid-template-columns:1fr;
}

.enquiry-field{
    display:flex;
    flex-direction:column;
}

.enquiry-field label{
    font-size:16px;
    color:#444444;
    margin-bottom:10px;
}

.enquiry-field input[type="text"],
.enquiry-field input[type="email"],
.enquiry-field select,
.enquiry-field textarea{
    width:100%;
    padding:14px 16px;
    border:1px solid #dcdcdc;
    border-radius:8px;
    font-size:15px;
    font-family:inherit;
    outline:none;
    background:#ffffff;
    color:#111111;
    transition:border-color 0.2s ease, box-shadow 0.2s ease;
}

.enquiry-field select{
    appearance:none;
    -webkit-appearance:none;
    background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='9' viewBox='0 0 14 9'><path d='M1 1l6 6 6-6' stroke='%23999999' stroke-width='1.6' fill='none' fill-rule='evenodd'/></svg>");
    background-repeat:no-repeat;
    background-position:right 16px center;
    padding-right:40px;
    cursor:pointer;
}

.enquiry-field textarea{
    resize:vertical;
    min-height:170px;
}

.enquiry-field input:focus,
.enquiry-field select:focus,
.enquiry-field textarea:focus{
    border-color:var(--orange);
    box-shadow:0 0 0 3px rgba(232,121,45,0.12);
}

.enquiry-field input::placeholder,
.enquiry-field textarea::placeholder{
    color:#aaaaaa;
}

.enquiry-actions{
    display:flex;
    justify-content:flex-end;
}

.enquiry-submit{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:16px 46px;
    border:none;
    border-radius:30px;
    background:var(--orange);
    color:#ffffff;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
    transition:background 0.25s ease, transform 0.2s ease, box-shadow 0.3s ease;
}

.enquiry-submit:hover{
    background:var(--orange-dark);
    transform:translateY(-3px);
    box-shadow:0 16px 32px rgba(232,121,45,0.3);
}

@media (max-width: 900px){
    .enquiry{ padding:50px 24px 60px; }
    .enquiry-row{ grid-template-columns:1fr; gap:22px; }
}

@media (max-width: 600px){
    .enquiry-heading{ font-size:clamp(24px, 6.5vw, 30px); }
    .enquiry-actions{ justify-content:stretch; }
    .enquiry-submit{ width:100%; }
}

@media (max-width: 480px){
    .enquiry{ padding:40px 16px 48px; }
    .enquiry-field input,
    .enquiry-field select,
    .enquiry-field textarea{ padding:12px 14px; }
}
</style>

<section class="hero">
@include('web.layout.navbar')

    @if ($contact && $contact->banner_image)
        <div class="hero-slides">
            <img src="{{ Storage::url($contact->banner_image) }}" class="hero-slide active"
                alt="{{ $contact->banner_title ?? 'Contact banner' }}">
        </div>
    @else
        <div class="hero-slides">
            <img src="{{ asset('images/hero_image.jpeg') }}" class="hero-slide active" alt="Contact banner">
        </div>
    @endif

    <div class="hero-content">
        <div class="hero-inner">
            <p class="eyebrow">Get in Touch</p>
            <h1>{{ $contact->banner_title ?? 'Contact Us' }}</h1>
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
            <p class="enquiry-eyebrow">General enquiries</p>
            <h2 class="enquiry-heading">Send us an enquiry</h2>
            <p class="enquiry-sub">
                Complete the form below and our team will review your enquiry.<br>
                Fields marked with <span class="required-star">*</span> are required.
            </p>
        </div>

        <form class="enquiry-form reveal reveal-delay-1">
            <div class="enquiry-row">
                <div class="enquiry-field">
                    <label for="fullName">Full name</label>
                    <input type="text" id="fullName" name="full_name">
                </div>
                <div class="enquiry-field">
                    <label for="emailAddress">Email address</label>
                    <input type="email" id="emailAddress" name="email">
                </div>
            </div>

            <div class="enquiry-row enquiry-row-single">
                <div class="enquiry-field">
                    <label for="address">Address <span class="required-star">*</span></label>
                    <input type="text" id="address" name="address" required>
                </div>
            </div>

            <div class="enquiry-row">
                <div class="enquiry-field">
                    <label for="townCity">Town/City</label>
                    <select id="townCity" name="town_city">
                        <option value="" selected></option>
                    </select>
                </div>
                <div class="enquiry-field">
                    <label for="country">Country</label>
                    <select id="country" name="country">
                        <option value="" selected></option>
                    </select>
                </div>
            </div>

            <div class="enquiry-row enquiry-row-single">
                <div class="enquiry-field">
                    <textarea name="comments" rows="6" placeholder="Comments..."></textarea>
                </div>
            </div>

            <div class="enquiry-actions">
                <button type="submit" class="enquiry-submit">Submit</button>
            </div>
        </form>

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