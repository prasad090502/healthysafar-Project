<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
// Normalise data
$plans = $plans ?? [];
?>

<!-- Bootstrap Icons (safe include; if already loaded in layout, it won’t hurt) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
  /* =========================================
     1. GLOBAL THEME VARIABLES
     ========================================= */
  :root{
    --theme-primary:#16a34a;
    --theme-primary-dark:#14532d;
    --theme-primary-soft:#dcfce7;
    --theme-accent-gold:#f59e0b;
    --theme-accent-amber:#fbcb32;
    --theme-danger:#ef4444;

    --ink-900:#111827;
    --ink-700:#374151;
    --ink-500:#6b7280;
    --ink-300:#d1d5db;

    --bg-body:#fdfdfd;
    --bg-card:#ffffff;

    --shadow-soft:0 10px 30px rgba(0,0,0,.06);
    --shadow-strong:0 24px 50px rgba(22,163,74,.20);

    --radius-lg:24px;
    --radius-md:18px;

    /* sticky offset (JS will update) */
    --hs-sticky-top: 86px;
  }

  body{
    background-color:var(--bg-body);
    color:var(--ink-900);
    font-family:"Inter",system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
  }

  /* =========================================
     2. GENERIC SECTIONS
     ========================================= */
  .hs-section{ padding-top:90px; padding-bottom:90px; position:relative; z-index:1; }
  @media (min-width:992px){ .hs-section{ padding-top:110px; padding-bottom:110px; } }

  .hs-category-section{ padding-top:80px; padding-bottom:50px; }
  @media (min-width:992px){ .hs-category-section{ padding-top:100px; padding-bottom:70px; } }

  #shop-sec{
    margin-top:10px;
    padding-top:90px;
    padding-bottom:100px;
    position:relative;
    z-index:1;
    scroll-margin-top: calc(var(--hs-sticky-top) + 22px);
  }

  .shape-mockup{ pointer-events:none; z-index:0; }

  .title-area .sec-title{
    font-weight:800;
    letter-spacing:-0.03em;
    color:var(--ink-900);
    margin-bottom:10px;
  }
  .title-area .sub-title{
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:0.12em;
    font-size:12px;
    padding:5px 14px;
    border-radius:999px;
    background:rgba(22,163,74,.08);
    color:var(--theme-primary);
    display:inline-flex;
    align-items:center;
    gap:6px;
  }
  .title-area .sub-title img{ width:16px; height:16px; }
  .hs-divider{
    width:120px; height:4px; border-radius:999px; margin:18px auto 0;
    background:linear-gradient(90deg,#49b1be,#fbcb32); opacity:.9;
  }

  /* =========================================
     3. HERO SECTION (responsive)
     ========================================= */
  .th-hero-wrapper.hero-1{
    border-radius:0 0 40px 40px;
    overflow:hidden;
    box-shadow:0 18px 40px rgba(15,23,42,.08);
  }
  .hero-inner{
    min-height:520px;
    display:flex;
    align-items:center;
    position:relative;
    padding-top:40px;
    padding-bottom:40px;
  }
  @media (min-width:992px){
    .hero-inner{ min-height:620px; padding-top:60px; padding-bottom:60px; }
  }
  .hero-style1 .hero-title{ font-weight:800; }
  .hero-img{
    flex:0 0 auto;
    max-width:780px;
    margin-left:auto;
    margin-right:0;
    position:relative;
  }
  .hero-img img{ width:100%; height:auto; display:block; object-fit:contain; }
  @media (max-width:991.98px){
    .hero-inner{ flex-direction:column-reverse; text-align:center; min-height:auto; }
    .hero-img{ max-width:420px; margin:0 auto; }
  }
  @media (max-width:575.98px){
    .hero-inner{ padding-top:30px; padding-bottom:30px; }
    .hero-style1 .hero-title{ font-size:30px; }
    .hero-img{ max-width:340px; }
  }

  /* =========================================
     4. PROMO BANNER SECTION
     ========================================= */
  .hs-promo-section{ padding-top:70px; padding-bottom:50px; }
  .hs-promo-card{
    border-radius:30px;
    padding:34px 36px;
    background:#f8efe1;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:24px;
    box-shadow:var(--shadow-soft);
    position:relative;
    overflow:hidden;
    min-height:230px;
    height:100%;
  }
  .hs-promo-card::before{
    content:"";
    position:absolute;
    inset:auto -80px -120px auto;
    width:260px; height:260px;
    background:radial-gradient(circle at center, rgba(251,203,50,.40), transparent 70%);
    opacity:.8;
    pointer-events:none;
  }
  .hs-promo-card--green{ background:#e5efe3; }
  .hs-promo-card--green::before{
    background:radial-gradient(circle at center, rgba(22,163,74,.35), transparent 70%);
  }
  .hs-promo-copy span.hs-promo-off{
    display:inline-block;
    font-size:16px;
    font-weight:600;
    color:var(--theme-accent-gold);
    margin-bottom:6px;
  }
  .hs-promo-copy h3{
    font-size:30px;
    font-weight:800;
    color:#1f2933;
    margin-bottom:18px;
  }
  .hs-promo-copy .th-btn{
    border-radius:999px;
    padding:10px 26px;
    font-size:13px;
    letter-spacing:.06em;
    text-transform:uppercase;
    font-weight:600;
    background:var(--theme-primary);
    border:none;
  }
  .hs-promo-copy .th-btn i{ font-size:12px; }
  .hs-promo-image{ max-width:260px; flex:0 0 auto; position:relative; z-index:1; }
  .hs-promo-image img{ width:100%; height:auto; display:block; }
  @media (max-width:991.98px){
    .hs-promo-card{ padding:26px 22px; }
    .hs-promo-copy h3{ font-size:26px; }
  }
  @media (max-width:767.98px){
    .hs-promo-card{ flex-direction:column-reverse; align-items:flex-start; }
    .hs-promo-image{ max-width:220px; margin-left:auto; margin-right:auto; }
  }

  /* =========================================
     5. CATEGORY CARDS – “What We’re Offering”
     ========================================= */
  .category-card{
    background:#fff;
    border-radius:22px;
    padding:30px 22px;
    text-align:center;
    border:1px solid #eef2f7;
    box-shadow:0 10px 26px rgba(15,23,42,.04);
    transition:.3s cubic-bezier(0.175,0.885,0.32,1.275);
    position:relative;
    overflow:hidden;
  }
  .category-card .box-shape{
    position:absolute;
    inset:auto -40px -80px auto;
    width:160px; height:160px;
    opacity:.4;
  }
  .category-card .box-icon{
    width:80px; height:80px;
    margin:0 auto 18px;
    background:#f8fafc;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    z-index:2;
    position:relative;
    transition:.3s;
  }
  .category-card .box-title a{
    color:var(--ink-900);
    text-decoration:none;
    font-weight:700;
    transition:.3s;
    font-size:17px;
  }
  .category-card .box-subtitle{
    transition:.3s;
    color:var(--ink-500);
    font-size:13px;
    margin-bottom:0;
  }
  .category-card:hover{
    background:#f0fdf4;
    border-color:var(--theme-primary);
    transform:translateY(-10px);
    box-shadow:var(--shadow-strong);
  }
  .category-card:hover .box-icon{ background:#fff; }
  .category-card:hover .box-title a{ color:var(--theme-primary-dark); }

  /* =========================================================
     UI POLISH (ONLY top sections) – scoped to .hs-home-top
     ========================================================= */
  .hs-home-top{
    --top-radius: 32px;
    --top-card-radius: 26px;
    --top-border: rgba(15,23,42,.08);
    --top-shadow: 0 18px 40px rgba(15,23,42,.08);
    --top-shadow-2: 0 26px 70px rgba(22,163,74,.18);
  }

  /* HERO polish */
  .hs-home-top .th-hero-wrapper.hero-1{
    border-radius:0 0 var(--top-radius) var(--top-radius);
    box-shadow:var(--top-shadow);
    position:relative;
  }
  .hs-home-top .th-hero-wrapper.hero-1::before{
    content:"";
    position:absolute;
    inset:0;
    background:
      radial-gradient(1200px 600px at 20% 20%, rgba(22,163,74,.10), transparent 55%),
      radial-gradient(900px 500px at 80% 50%, rgba(251,203,50,.10), transparent 55%),
      linear-gradient(180deg, rgba(255,255,255,.55), rgba(255,255,255,0));
    pointer-events:none;
    z-index:0;
  }
  .hs-home-top .hero-inner{ position:relative; z-index:1; gap:26px; }
  .hs-home-top .hero-style1{ max-width:560px; }
  .hs-home-top .hero-style1 .sub-title{
    background:rgba(22,163,74,.10);
    border:1px solid rgba(22,163,74,.12);
    backdrop-filter:blur(6px);
    padding:8px 14px;
  }
  .hs-home-top .hero-style1 .hero-title{
    line-height:1.05;
    letter-spacing:-0.04em;
    margin-bottom:18px;
  }
  .hs-home-top .hero-style1 .th-btn{
    border-radius:999px;
    padding:12px 26px;
    font-weight:700;
    box-shadow:0 18px 40px rgba(22,163,74,.28);
  }
  .hs-home-top .hero-img{
    filter:drop-shadow(0 24px 50px rgba(15,23,42,.18));
    transform:translateY(6px);
  }

  /* PROMO polish */
  .hs-home-top .hs-promo-card{
    border:1px solid var(--top-border);
    box-shadow:var(--top-shadow);
    transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
  }
  .hs-home-top .hs-promo-card:hover{
    transform:translateY(-6px);
    box-shadow:var(--top-shadow-2);
    border-color:rgba(22,163,74,.18);
  }

  /* CATEGORY polish */
  .hs-home-top .hs-category-section .title-area{ margin-bottom:30px; }
  .hs-home-top .category-card{
    border-radius:var(--top-card-radius);
    border:1px solid var(--top-border);
    box-shadow:0 14px 34px rgba(15,23,42,.06);
  }
  .hs-home-top .category-card:hover{
    transform:translateY(-8px);
    box-shadow:0 26px 70px rgba(22,163,74,.16);
  }

  /* =========================================================
     SUBSCRIPTIONS (smaller compact cards)
     ========================================================= */
  .hs-sub-wrap{
    position:relative;
    z-index:1;
  }

  .hs-sub-lead{
    max-width:720px;
    margin:0 auto 4px;
    color:rgba(55,65,81,.85);
    font-size:14px;
  }

  .hs-sub-grid{
    margin-top:20px;
  }

  .hs-sub-card{
    height:100%;
    border-radius:22px;
    background:#fff;
    border:1px solid rgba(15,23,42,.08);
    box-shadow:0 14px 34px rgba(15,23,42,.07);
    overflow:hidden;
    transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    display:flex;
    flex-direction:column;
  }
  .hs-sub-card:hover{
    transform:translateY(-6px);
    box-shadow:0 26px 70px rgba(22,163,74,.14);
    border-color:rgba(22,163,74,.22);
  }

  .hs-sub-img{
    position:relative;
    height:160px;               /* smaller */
    background:#f3f4f6;
    overflow:hidden;
  }
  .hs-sub-img img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
    transition:transform .25s ease;
  }
  .hs-sub-card:hover .hs-sub-img img{ transform:scale(1.05); }

  .hs-sub-badge{
    position:absolute;
    top:12px;
    left:12px;
    padding:4px 10px;
    border-radius:999px;
    font-size:11px;
    font-weight:800;
    letter-spacing:.08em;
    text-transform:uppercase;
    color:#fff;
    background:rgba(22,163,74,.95);
    box-shadow:0 10px 22px rgba(22,163,74,.24);
  }

  .hs-sub-kcal{
    position:absolute;
    right:12px;
    bottom:12px;
    padding:4px 10px;
    border-radius:999px;
    font-size:12px;
    color:#374151;
    background:rgba(255,255,255,.90);
    backdrop-filter:blur(10px);
    border:1px solid rgba(209,213,219,.55);
    display:inline-flex;
    gap:6px;
    align-items:center;
  }
  .hs-sub-kcal i{ color:var(--theme-danger); }

  .hs-sub-body{
    padding:14px 14px 12px;
    display:flex;
    flex-direction:column;
    gap:10px;
    flex:1;
  }

  .hs-sub-title{
    font-weight:800;
    font-size:15px;
    line-height:1.2;
    margin:0;
    color:var(--ink-900);
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
    min-height:36px;
  }

  .hs-sub-tagline{
    margin:0;
    color:rgba(107,114,128,.95);
    font-size:12.5px;
    line-height:1.35;
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
    min-height:34px;
  }

  .hs-sub-meta{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    margin-top:auto;
  }
  .hs-chip{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:6px 10px;
    border-radius:999px;
    font-size:12px;
    background:rgba(22,163,74,.08);
    border:1px solid rgba(22,163,74,.18);
    color:#14532d;
    font-weight:700;
    white-space:nowrap;
  }
  .hs-chip i{ font-size:13px; }

  .hs-sub-footer{
    padding:12px 14px 14px;
    border-top:1px solid rgba(15,23,42,.06);
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
  }
  .hs-price{
    display:flex;
    flex-direction:column;
    line-height:1.1;
  }
  .hs-price small{
    font-size:10px;
    letter-spacing:.10em;
    text-transform:uppercase;
    color:rgba(107,114,128,.95);
    font-weight:800;
  }
  .hs-price strong{
    font-size:18px;
    font-weight:900;
    color:var(--theme-primary-dark);
  }

  .hs-btn-subscribe{
    border-radius:999px;
    padding:9px 14px;
    font-size:12px;
    font-weight:800;
    letter-spacing:.08em;
    text-transform:uppercase;
    border:none;
    color:#fff;
    background:linear-gradient(135deg,#22c55e,#15803d);
    box-shadow:0 16px 38px rgba(22,163,74,.35);
    white-space:nowrap;
  }
  .hs-btn-subscribe:hover{
    filter:brightness(1.02);
    transform:translateY(-1px);
    box-shadow:0 18px 42px rgba(22,163,74,.45);
    color:#fff;
  }

  /* =========================================================
     STICKY FILTER BAR (for #shop-sec in .hs-home-top)
     ========================================================= */
  .hs-home-top #shop-sec .hs-filter-sticky-wrap{ position:relative; margin: 14px 0 18px; }
  .hs-home-top #shop-sec .hs-filter-sentinel{ height:1px; }

  .hs-home-top #shop-sec .hs-filter-bar{
    position:sticky;
    top:var(--hs-sticky-top);
    z-index:50;

    border-radius:999px;
    padding:10px 12px;
    background:rgba(255,255,255,.78);
    border:1px solid rgba(15,23,42,.10);
    box-shadow:0 18px 45px rgba(15,23,42,.10);
    backdrop-filter:blur(10px);

    transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease, background .18s ease;
  }
  .hs-home-top #shop-sec .hs-filter-bar.is-stuck{
    background:rgba(255,255,255,.92);
    border-color:rgba(22,163,74,.20);
    box-shadow:0 26px 70px rgba(22,163,74,.18);
    transform:translateY(-2px);
  }

  /* filter buttons style (re-using existing th-btn tab-btn look, compact) */
  .hs-home-top #shop-sec .filter-menu{ display:flex; justify-content:center; align-items:center; flex-wrap:wrap; gap:10px; margin:0; }
  .hs-home-top #shop-sec .filter-menu .th-btn.tab-btn{
    margin:0;
    padding:7px 14px;
    border-radius:999px;
    font-size:12px;
    border:1px solid rgba(15,23,42,.12);
    background:#fff;
    color:var(--ink-700);
    text-transform:uppercase;
    letter-spacing:.06em;
    font-weight:800;
    display:inline-flex;
    align-items:center;
    gap:8px;
  }
  .hs-home-top #shop-sec .filter-menu .th-btn.tab-btn.active{
    background:var(--theme-primary);
    border-color:var(--theme-primary);
    color:#fff;
    box-shadow:0 10px 22px rgba(22,163,74,.35);
  }

  @media (max-width: 767.98px){
    .hs-home-top #shop-sec .hs-filter-bar{ border-radius:18px; padding:10px 10px 8px; }
    .hs-home-top #shop-sec .filter-menu{
      justify-content:flex-start;
      flex-wrap:nowrap;
      overflow-x:auto;
      -webkit-overflow-scrolling:touch;
      padding-bottom:6px;
      gap:8px;
    }
    .hs-home-top #shop-sec .filter-menu::-webkit-scrollbar{ height:0; }
    .hs-home-top #shop-sec .filter-menu .th-btn.tab-btn{ white-space:nowrap; }
    .hs-home-top #shop-sec .hs-filter-hint{
      margin-top:6px;
      font-size:12px;
      color: rgba(55,65,81,.75);
      text-align:right;
      padding-right:4px;
    }
  }

  .hs-hidden{ display:none !important; }
</style>

<!-- ==========================
TOP UI WRAPPER (Hero + Promo + Category + Subscriptions ONLY)
=========================== -->
<div class="hs-home-top">

  <!--==============================
  Hero Area (Slider)
  ==============================-->
  <div class="th-hero-wrapper hero-1" id="hero" data-bg-src="<?= base_url('assets/img/hero/hero_bg_1_2.jpg') ?>">
    <div class="swiper th-slider" id="heroSlide1" data-slider-options='{"effect":"fade"}'>
      <div class="swiper-wrapper">

        <!-- Slide 1 -->
        <div class="swiper-slide">
          <div class="hero-inner">
            <div class="container">
              <div class="hero-style1">
                <span class="sub-title" data-ani="slideinup" data-ani-delay="0.2s">
                  <img src="<?= base_url('assets/img/theme-img/title_icon.svg') ?>" alt="shape">
                  100% Quality Foods
                </span>
                <h1 class="hero-title">
                  <span class="title1" data-ani="slideinup" data-ani-delay="0.4s">
                    <img class="title-img" src="<?= base_url('assets/img/hero/hero_title_shape.svg') ?>" alt="icon">
                    Natural <span class="text-theme">Fruits</span>
                  </span>
                  <span class="title2" data-ani="slideinup" data-ani-delay="0.5s">Vegetable</span>
                </h1>

                <!-- Smooth scroll to subscriptions -->
                <a href="#shop-sec" class="th-btn" data-scroll="shop" data-ani="slideinup" data-ani-delay="0.7s">
                  Shop Now<i class="fas fa-chevrons-right ms-2"></i>
                </a>
              </div>
            </div>

            <div class="hero-img" data-ani="slideinright" data-ani-delay="0.5s">
              <img src="<?= base_url('assets/img/healthysafar/salad3.png') ?>" alt="Image">
            </div>

            <div class="hero-shape1" data-ani="slideinup" data-ani-delay="0.5s">
              <img src="<?= base_url('assets/img/hero/hero_shape_1_1.png') ?>" alt="shape">
            </div>
            <div class="hero-shape2" data-ani="slideindown" data-ani-delay="0.6s">
              <img src="<?= base_url('assets/img/hero/hero_shape_1_2.png') ?>" alt="shape">
            </div>
            <div class="hero-shape3" data-ani="slideinleft" data-ani-delay="0.7s">
              <img src="<?= base_url('assets/img/hero/hero_shape_1_3.png') ?>" alt="shape">
            </div>
          </div>
        </div>

        <!-- Slide 2 -->
        <div class="swiper-slide">
          <div class="hero-inner">
            <div class="container">
              <div class="hero-style1">
                <span class="sub-title" data-ani="slideinup" data-ani-delay="0.2s">
                  <img src="<?= base_url('assets/img/theme-img/title_icon.svg') ?>" alt="shape">
                  100% Organic Foods
                </span>
                <h1 class="hero-title">
                  <span class="title1" data-ani="slideinup" data-ani-delay="0.4s">
                    <img class="title-img" src="<?= base_url('assets/img/hero/hero_title_shape.svg') ?>" alt="icon">
                    Organic <span class="text-theme">Juices</span>
                  </span>
                  <span class="title2" data-ani="slideinup" data-ani-delay="0.5s">For Health</span>
                </h1>

                <a href="#shop-sec" class="th-btn" data-scroll="shop" data-ani="slideinup" data-ani-delay="0.7s">
                  Shop Now<i class="fas fa-chevrons-right ms-2"></i>
                </a>
              </div>
            </div>

            <div class="hero-img" data-ani="slideinright" data-ani-delay="0.5s">
              <img src="<?= base_url('assets/img/healthysafar/abcjuice.png') ?>" alt="Image">
            </div>

            <div class="hero-shape1" data-ani="slideinup" data-ani-delay="0.5s">
              <img src="<?= base_url('assets/img/hero/hero_shape_1_1.png') ?>" alt="shape">
            </div>
            <div class="hero-shape2" data-ani="slideindown" data-ani-delay="0.6s">
              <img src="<?= base_url('assets/img/hero/hero_shape_1_2.png') ?>" alt="shape">
            </div>
            <div class="hero-shape3" data-ani="slideinleft" data-ani-delay="0.7s">
              <img src="<?= base_url('assets/img/hero/hero_shape_1_3.png') ?>" alt="shape">
            </div>
          </div>
        </div>

        <!-- Slide 3 -->
        <div class="swiper-slide">
          <div class="hero-inner">
            <div class="container">
              <div class="hero-style1">
                <span class="sub-title" data-ani="slideinup" data-ani-delay="0.2s">
                  <img src="<?= base_url('assets/img/theme-img/title_icon.svg') ?>" alt="shape">
                  100% Fresh Foods
                </span>
                <h1 class="hero-title">
                  <span class="title1" data-ani="slideinup" data-ani-delay="0.4s">
                    <img class="title-img" src="<?= base_url('assets/img/hero/hero_title_shape.svg') ?>" alt="icon">
                    Quality <span class="text-theme">Fruits</span>
                  </span>
                  <span class="title2" data-ani="slideinup" data-ani-delay="0.5s">Farming</span>
                </h1>

                <a href="#shop-sec" class="th-btn" data-scroll="shop" data-ani="slideinup" data-ani-delay="0.7s">
                  Shop Now<i class="fas fa-chevrons-right ms-2"></i>
                </a>
              </div>
            </div>

            <div class="hero-img" data-ani="slideinright" data-ani-delay="0.5s">
              <img src="<?= base_url('assets/img/healthysafar/fruit.png') ?>" alt="Image">
            </div>

            <div class="hero-shape1" data-ani="slideinup" data-ani-delay="0.5s">
              <img src="<?= base_url('assets/img/hero/hero_shape_1_1.png') ?>" alt="shape">
            </div>
            <div class="hero-shape2" data-ani="slideindown" data-ani-delay="0.6s">
              <img src="<?= base_url('assets/img/hero/hero_shape_1_2.png') ?>" alt="shape">
            </div>
            <div class="hero-shape3" data-ani="slideinleft" data-ani-delay="0.7s">
              <img src="<?= base_url('assets/img/hero/hero_shape_1_3.png') ?>" alt="shape">
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="hero-shape4">
      <img class="svg-img" src="<?= base_url('assets/img/hero/hero_shape_1_4.svg') ?>" alt="shape">
    </div>
  </div>

  <!--==============================
  Promo Banner Section (equal height cards)
  ==============================-->
  <section class="hs-promo-section">
    <div class="container">
      <div class="row g-4 align-items-stretch">
        <div class="col-lg-6 d-flex">
          <div class="hs-promo-card h-100">
            <div class="hs-promo-copy">
              <span class="hs-promo-off">Up to 50% off</span>
              <h3>Best Deals Of<br>The Week!</h3>
              <a href="#shop-sec" class="th-btn" data-scroll="shop">
                Shop Now <i class="far fa-arrow-right ms-2"></i>
              </a>
            </div>
            <div class="hs-promo-image">
              <img src="<?= base_url('assets/img/healthysafar/banner-deals.png') ?>" alt="Best Deals Of The Week">
            </div>
          </div>
        </div>

        <div class="col-lg-6 d-flex">
          <div class="hs-promo-card hs-promo-card--green h-100">
            <div class="hs-promo-copy">
              <span class="hs-promo-off">Up to 40% off</span>
              <h3>Organic Fresh<br>Vegetables</h3>
              <a href="#shop-sec" class="th-btn" data-scroll="shop">
                Shop Now <i class="far fa-arrow-right ms-2"></i>
              </a>
            </div>
            <div class="hs-promo-image">
              <img src="<?= base_url('assets/img/healthysafar/banner-veggies.png') ?>" alt="Organic Fresh Vegetables">
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!--==============================
  Category Area – What We’re Offering
  ==============================-->
  <section class="space-top hs-category-section">
    <div class="container">
      <div class="title-area text-center">
        <span class="sub-title">
          <img src="<?= base_url('assets/img/theme-img/title_icon.svg') ?>" alt="Icon">
          Food Category
        </span>
        <h2 class="sec-title">What We’re Offering</h2>
        <div class="hs-divider"></div>
      </div>

      <div class="swiper th-slider"
           data-slider-options='{"breakpoints":{"0":{"slidesPerView":1},"400":{"slidesPerView":2},"768":{"slidesPerView":3},"992":{"slidesPerView":4},"1200":{"slidesPerView":5}}}'>
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <div class="category-card">
              <div class="box-shape" data-bg-src="<?= base_url('assets/img/bg/category_card_bg.png') ?>"></div>
              <div class="box-icon"><img src="<?= base_url('assets/img/icon/category_card_1.svg') ?>" alt="Image"></div>
              <p class="box-subtitle">Product (08)</p>
              <h3 class="box-title"><a href="#shop-sec" data-scroll="shop">Fresh Salads</a></h3>
            </div>
          </div>

          <div class="swiper-slide">
            <div class="category-card">
              <div class="box-shape" data-bg-src="<?= base_url('assets/img/bg/category_card_bg.png') ?>"></div>
              <div class="box-icon"><img src="<?= base_url('assets/img/icon/category_card_2.svg') ?>" alt="Image"></div>
              <p class="box-subtitle">Product (05)</p>
              <h3 class="box-title"><a href="#shop-sec" data-scroll="shop">Healthy Juice</a></h3>
            </div>
          </div>

          <div class="swiper-slide">
            <div class="category-card">
              <div class="box-shape" data-bg-src="<?= base_url('assets/img/bg/category_card_bg.png') ?>"></div>
              <div class="box-icon"><img src="<?= base_url('assets/img/icon/category_card_3.svg') ?>" alt="Image"></div>
              <p class="box-subtitle">Product (04)</p>
              <h3 class="box-title"><a href="#shop-sec" data-scroll="shop">Smoothies</a></h3>
            </div>
          </div>

          <div class="swiper-slide">
            <div class="category-card">
              <div class="box-shape" data-bg-src="<?= base_url('assets/img/bg/category_card_bg.png') ?>"></div>
              <div class="box-icon"><img src="<?= base_url('assets/img/icon/category_card_4.svg') ?>" alt="Image"></div>
              <p class="box-subtitle">Product (07)</p>
              <h3 class="box-title"><a href="#shop-sec" data-scroll="shop">Soups</a></h3>
            </div>
          </div>

          <div class="swiper-slide">
            <div class="category-card">
              <div class="box-shape" data-bg-src="<?= base_url('assets/img/bg/category_card_bg.png') ?>"></div>
              <div class="box-icon"><img src="<?= base_url('assets/img/icon/category_card_5.svg') ?>" alt="Image"></div>
              <p class="box-subtitle">Product (10)</p>
              <h3 class="box-title"><a href="#shop-sec" data-scroll="shop">Fruit Box</a></h3>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!--==============================
  SUBSCRIPTIONS (replaces Organic Products section)
  ==============================-->
  <section class="bg-smoke2 hs-section" id="shop-sec">
    <div class="shape-mockup" data-top="0" data-left="0">
      <img src="<?= base_url('assets/img/shape/vector_shape_1.png') ?>" alt="shape">
    </div>
    <div class="shape-mockup" data-bottom="0" data-right="0">
      <img src="<?= base_url('assets/img/shape/vector_shape_2.png') ?>" alt="shape">
    </div>

    <div class="container text-center hs-sub-wrap">
      <div class="title-area text-center">
        <span class="sub-title">
          <img src="<?= base_url('assets/img/theme-img/title_icon.svg') ?>" alt="Icon">
          Meal Subscriptions
        </span>
        <h2 class="sec-title">Subscription Plans for a Healthier You</h2>
        <p class="hs-sub-lead mb-0">
          Fresh salads, juices and wholesome meals — delivered on your schedule.
          Choose a plan and start your wellness journey.
        </p>
        <div class="hs-divider"></div>
      </div>

      <!-- Sticky Filter Wrap -->
      <div class="hs-filter-sticky-wrap" id="shop-filter-wrap">
        <div class="hs-filter-sentinel" aria-hidden="true"></div>

        <div class="hs-filter-bar" id="hsFilterBar">
          <div class="filter-menu indicator-active filter-menu-active">
            <button data-filter="all" class="th-btn tab-btn active" type="button">
              <i class="bi bi-stars"></i> All
            </button>
            <button data-filter="weight-loss" class="th-btn tab-btn" type="button">
              <i class="bi bi-fire"></i> Weight Loss
            </button>
            <button data-filter="high-protein" class="th-btn tab-btn" type="button">
              <i class="bi bi-lightning-charge"></i> High Protein
            </button>
            <button data-filter="detox" class="th-btn tab-btn" type="button">
              <i class="bi bi-magic"></i> Detox / Juices
            </button>
          </div>
          <div class="hs-filter-hint d-md-none">Swipe to see categories →</div>
        </div>
      </div>

      <div class="row g-4 justify-content-center hs-sub-grid" id="hsPlansGrid">
        <?php if (empty($plans)): ?>
          <div class="col-12">
            <div class="alert alert-info mt-3 mb-0">
              Plans are coming soon. Please check back shortly.
            </div>
          </div>
        <?php else: ?>

          <?php foreach ($plans as $plan): ?>
            <?php
              $title = (string)($plan['title'] ?? 'Subscription Plan');
              $slug  = (string)($plan['slug'] ?? '');

              // Image
              $placeholder = 'https://placehold.co/900x600/e5e7eb/a3a3a3?text=Healthy+Safar';
              $imageUrl = !empty($plan['thumbnail_url'])
                ? base_url($plan['thumbnail_url'])
                : $placeholder;

              // Category tag (derive by title keywords)
              $titleLower = strtolower($title);
              $categoryTag = 'balanced';

              if (str_contains($titleLower, 'weight') || str_contains($titleLower, 'loss')) {
                $categoryTag = 'weight-loss';
              } elseif (str_contains($titleLower, 'keto') || str_contains($titleLower, 'protein') || str_contains($titleLower, 'muscle')) {
                $categoryTag = 'high-protein';
              } elseif (str_contains($titleLower, 'juice') || str_contains($titleLower, 'detox') || str_contains($titleLower, 'cleanse')) {
                $categoryTag = 'detox';
              }

              $badgeText = match ($categoryTag) {
                'weight-loss'  => 'Weight Loss',
                'high-protein' => 'High Protein',
                'detox'        => 'Detox',
                default        => 'Balanced',
              };

              // Important fields only
              $tagline = trim((string)($plan['tagline'] ?? 'Daily fresh, chef-crafted meals.'));
              if ($tagline === '') $tagline = 'Daily fresh, chef-crafted meals.';

              if (mb_strlen($tagline) > 70) $tagline = mb_substr($tagline, 0, 67) . '...';

              $kcal = (string)($plan['calories'] ?? '400-600');
              $basePrice = (float)($plan['base_price'] ?? 0);
              $priceLabel = $basePrice > 0 ? number_format($basePrice) : '—';

              // optional: duration info if you have it
              $durationText = !empty($plan['min_days']) ? ((int)$plan['min_days'] . '+ days') : '7–30 days';
            ?>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-10 hs-plan-col"
                 data-category="<?= esc($categoryTag) ?>">
              <div class="hs-sub-card">
                <div class="hs-sub-img">
                  <span class="hs-sub-badge"><?= esc($badgeText) ?></span>

                  <img src="<?= esc($imageUrl) ?>"
                       alt="<?= esc($title) ?>"
                       loading="lazy"
                       onerror="this.onerror=null; this.src='https://placehold.co/900x600/e5e7eb/9ca3af?text=Image+Not+Found';">

                  <span class="hs-sub-kcal">
                    <i class="bi bi-fire"></i> <?= esc($kcal) ?> kcal
                  </span>
                </div>

                <div class="hs-sub-body text-start">
                  <h3 class="hs-sub-title"><?= esc($title) ?></h3>
                  <p class="hs-sub-tagline"><?= esc($tagline) ?></p>

                  <div class="hs-sub-meta">
                    <span class="hs-chip" title="Recommended duration">
                      <i class="bi bi-calendar2-week"></i> <?= esc($durationText) ?>
                    </span>
                    <span class="hs-chip" title="Delivery slots">
                      <i class="bi bi-truck"></i> Lunch / Dinner
                    </span>
                  </div>
                </div>

                <div class="hs-sub-footer">
                  <div class="hs-price text-start">
                    <small>Starting from</small>
                    <strong>₹<?= esc($priceLabel) ?></strong>
                  </div>

                  <a href="<?= site_url('subscriptions/' . esc($slug)) ?>#hs-config"
                     class="hs-btn-subscribe">
                    Subscribe <i class="bi bi-arrow-right ms-1"></i>
                  </a>
                </div>
              </div>
            </div>

          <?php endforeach; ?>
        <?php endif; ?>
      </div>

    </div>
  </section>
</div>
<!-- ==========================
END TOP UI WRAPPER
=========================== -->


<!--==============================
Process / How We Work
(KEEP THIS SECTION UI UNCHANGED)
==============================-->
<section class="space hs-section" id="process-sec">
  <div class="shape-mockup" data-top="0" data-left="0">
    <img src="<?= base_url('assets/img/shape/vector_shape_7.png') ?>" alt="shape">
  </div>
  <div class="shape-mockup" data-bottom="0" data-right="0">
    <img src="<?= base_url('assets/img/shape/vector_shape_6.png') ?>" alt="shape">
  </div>
  <div class="container">
    <div class="title-area text-center">
      <span class="sub-title">
        <img src="<?= base_url('assets/img/theme-img/title_icon.svg') ?>" alt="Icon">
        How Make Quality Foods
      </span>
      <h2 class="sec-title">How We Work Project?</h2>
      <div class="hs-divider"></div>
    </div>
    <div class="row gy-4 justify-content-center">

      <div class="col-xl-3 col-md-6 process-box-wrap">
        <div class="process-box">
          <div class="box-icon bg-white">
            <img src="<?= base_url('assets/img/icon/process_box_1.svg') ?>" alt="Step 1 Icon">
          </div>
          <div class="box-img" style="margin:0 auto 30px auto;max-width:200px;overflow:hidden;">
            <img src="<?= base_url('assets/img/healthysafar/harvest.jpg') ?>" alt="Sourcing Fresh Ingredients" style="width:100%;height:auto;display:block;">
          </div>
          <p class="box-number">Step - 01</p>
          <h3 class="box-title">Sourcing Fresh Ingredients</h3>
          <p class="box-text">
            We partner with trusted organic farmers to source the freshest and highest-quality
            fruits and vegetables, ensuring every ingredient is free from harmful chemicals and pesticides.
          </p>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 process-box-wrap">
        <div class="process-box">
          <div class="box-icon bg-white">
            <img src="<?= base_url('assets/img/icon/process_box_2.svg') ?>" alt="Step 2 Icon">
          </div>
          <div class="box-img" style="margin:0 auto 30px auto;max-width:200px;overflow:hidden;">
            <img src="<?= base_url('assets/img/healthysafar/vegebag.jpg') ?>" alt="Preparing with Care" style="width:100%;height:auto;display:block;">
          </div>
          <p class="box-number">Step - 02</p>
          <h3 class="box-title">Preparing with Care</h3>
          <p class="box-text">
            Every ingredient is thoroughly washed and handled hygienically. We use cold-press techniques
            for juices, slow-cooked methods for soups, and hand-prepped fresh cuts for salads to retain
            maximum nutrients and flavour.
          </p>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 process-box-wrap">
        <div class="process-box">
          <div class="box-icon bg-white">
            <img src="<?= base_url('assets/img/icon/process_box_3.svg') ?>" alt="Step 3 Icon">
          </div>
          <div class="box-img" style="margin:0 auto 30px auto;max-width:200px;overflow:hidden;">
            <img src="<?= base_url('assets/img/healthysafar/wash.jpg') ?>" alt="Hygienic and Sustainable Practices" style="width:100%;height:auto;display:block;">
          </div>
          <p class="box-number">Step - 03</p>
          <h3 class="box-title">Hygienic and Sustainable Practices</h3>
          <p class="box-text">
            We prioritise cleanliness and sustainability by preparing food in sanitised environments and
            using eco-friendly packaging, ensuring a safe and environmentally conscious product.
          </p>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 process-box-wrap">
        <div class="process-box">
          <div class="box-icon bg-white">
            <img src="<?= base_url('assets/img/icon/process_box_4.svg') ?>" alt="Step 4 Icon">
          </div>
          <div class="box-img" style="margin:0 auto 30px auto;max-width:200px;overflow:hidden;">
            <img src="<?= base_url('assets/img/healthysafar/delivery.jpg') ?>" alt="Delivering Wellness" style="width:100%;height:auto;display:block;">
          </div>
          <p class="box-number">Step - 04</p>
          <h3 class="box-title">Delivering Wellness</h3>
          <p class="box-text">
            Each product is crafted to provide maximum health benefits and delivered with a focus on
            freshness and customer satisfaction, ensuring a wholesome and delightful experience.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- =========================
  KEEP remaining sections as-is
========================== -->

<!-- =========================================================
Sticky Filter + Smooth Scroll to Shop
========================================================= -->
<script>
(function () {
  const shopSec   = document.querySelector('#shop-sec');
  const filterBar = document.querySelector('#hsFilterBar');
  const sentinel  = document.querySelector('#shop-filter-wrap .hs-filter-sentinel');

  // Filter buttons + cards
  const filterButtons = document.querySelectorAll('#hsFilterBar .tab-btn');
  const cards         = document.querySelectorAll('.hs-plan-col');

  function getHeaderEl() {
    return (
      document.querySelector('.th-header') ||
      document.querySelector('header') ||
      document.querySelector('.sticky-wrapper') ||
      document.querySelector('.header') ||
      null
    );
  }

  function getHeaderHeight() {
    const header = getHeaderEl();
    if (!header) return 86;
    const rect = header.getBoundingClientRect();
    return Math.max(70, Math.round(rect.height || 86));
  }

  function applyStickyTop() {
    const h = getHeaderHeight();
    document.documentElement.style.setProperty('--hs-sticky-top', (h + 10) + 'px');
  }

  // Sticky observer
  let io = null;
  function setupObserver() {
    if (!filterBar || !sentinel) return;
    if (io) { try { io.disconnect(); } catch(e) {} }

    const headerH = getHeaderHeight();
    io = new IntersectionObserver(
      (entries) => {
        const entry = entries[0];
        const stuck = !entry.isIntersecting;
        filterBar.classList.toggle('is-stuck', stuck);
      },
      { root:null, threshold:0, rootMargin:`-${headerH + 12}px 0px 0px 0px` }
    );
    io.observe(sentinel);
  }

  // Smooth scroll
  function smoothScrollTo(el) {
    if (!el) return;
    const headerH = getHeaderHeight();
    const barH = filterBar ? (filterBar.getBoundingClientRect().height || 0) : 0;
    const y = window.scrollY + el.getBoundingClientRect().top - (headerH + barH + 18);

    window.scrollTo({ top: Math.max(0, Math.round(y)), behavior: 'smooth' });
  }

  // Init sticky
  applyStickyTop();
  setupObserver();

  let resizeT = null;
  window.addEventListener('resize', function () {
    clearTimeout(resizeT);
    resizeT = setTimeout(() => {
      applyStickyTop();
      setupObserver();
    }, 120);
  }, { passive: true });

  // Clicks that should scroll to #shop-sec
  document.addEventListener('click', function (e) {
    const trigger =
      e.target.closest('[data-scroll="shop"]') ||
      e.target.closest('a[href="#shop-sec"]');

    if (!trigger) return;
    e.preventDefault();
    smoothScrollTo(shopSec);
  }, { passive: false });

  // Front-end filter
  if (filterButtons.length && cards.length) {
    filterButtons.forEach((btn) => {
      btn.addEventListener('click', () => {
        const filter = btn.getAttribute('data-filter') || 'all';

        filterButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        cards.forEach((card) => {
          const cat = card.getAttribute('data-category') || 'balanced';
          const show = (filter === 'all') || (cat === filter);
          card.classList.toggle('hs-hidden', !show);
        });
      });
    });
  }
})();
</script>

<?= $this->endSection() ?>