<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!--==============================
  Breadcumb
==============================-->
<div class="breadcumb-wrapper" data-bg-src="<?= base_url('assets/img/bg/breadcumb-bg.jpg') ?>">
    <div class="container">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">About Us</h1>
            <ul class="breadcumb-menu">
                <li><a href="<?= site_url('/') ?>">Home</a></li>
                <li>About Us</li>
            </ul>
        </div>
    </div>
</div>

<!--==============================
 About Area  
==============================-->
<div class="overflow-hidden space" id="about-sec">
    <div class="container">
        <div class="row align-items-center">
            <!-- Left: Images -->
            <div class="col-xl-6 mb-30 mb-xl-0">
                <div class="img-box1">
                    <div class="img1">
                        <img src="<?= base_url('assets/img/healthysafar/about_1_1.jpg') ?>" alt="About Healthy Safar">
                    </div>
                    <div class="img2">
                        <img src="<?= base_url('assets/img/healthysafar/about_1_2.jpg') ?>" alt="Healthy Food">
                    </div>
                    <div class="shape1 movingX">
                        <img src="<?= base_url('assets/img/healthysafar/about_1_3.png') ?>" alt="Healthy Safar">
                    </div>
                    <div class="year-counter">
                        <div class="year-counter_number">
                            <span class="counter-number">5</span>+
                        </div>
                        <p class="year-counter_text">Years of Serving Fresh Food</p>
                    </div>
                </div>
            </div>

            <!-- Right: Text -->
            <div class="col-xl-6">
                <div class="ps-xxl-5 ps-xl-2 ms-xl-1 text-center text-xl-start">
                    <div class="title-area mb-32">
                        <span class="sub-title">
                            <img src="<?= base_url('assets/img/theme-img/title_icon.svg') ?>" alt="shape">
                            About Healthy Safar
                        </span>
                        <h2 class="sec-title">Eating Right Starts With Fresh &amp; Honest Food</h2>
                        <p class="sec-text">
                            At <strong>Healthy Safar</strong>, we focus on clean, natural and thoughtfully prepared food.
                            From fresh salads and fruit boxes to cold-pressed juices and wholesome soups, our goal is to
                            make healthy choices simple and tasty for your everyday routine.
                        </p>
                        <p class="sec-text mb-0">
                            We carefully source ingredients, wash and prepare them hygienically, and follow mindful
                            cooking methods so that you get maximum nutrition and flavour in every bite.
                        </p>
                    </div>

                    <div class="about-feature-wrap">
                        <div class="about-feature">
                            <div class="box-icon">
                                <img src="<?= base_url('assets/img/icon/about_feature_1.svg') ?>" alt="Icon">
                            </div>
                            <h3 class="box-title">Trusted by Health-Conscious Families</h3>
                        </div>
                        <div class="about-feature">
                            <div class="box-icon">
                                <img src="<?= base_url('assets/img/icon/about_feature_2.svg') ?>" alt="Icon">
                            </div>
                            <h3 class="box-title">Smart &amp; Fresh Food Solutions</h3>
                        </div>
                    </div>

                    <div>
                        <a href="<?= site_url('contacts') ?>" class="th-btn">
                            Contact Us<i class="fas fa-chevrons-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--==============================
 Process Area – How We Work (from Home)
==============================-->
<section class="space bg-smoke2" id="process-sec">
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
                How We Make Quality Food
            </span>
            <h2 class="sec-title">How We Work – Step by Step</h2>
        </div>
        <div class="row gy-4 justify-content-center">

            <!-- Step 1 -->
            <div class="col-xl-3 col-md-6 process-box-wrap">
                <div class="process-box">
                    <div class="box-icon bg-white">
                        <img src="<?= base_url('assets/img/icon/process_box_1.svg') ?>" alt="Step 1 Icon">
                    </div>
                    <div class="box-img"
                         style="margin:0 auto 30px auto;max-width:200px;overflow:hidden;">
                        <img src="<?= base_url('assets/img/healthysafar/harvest.jpg') ?>"
                             alt="Sourcing Fresh Ingredients"
                             style="width:100%;height:auto;display:block;">
                    </div>
                    <p class="box-number">Step - 01</p>
                    <h3 class="box-title">Sourcing Fresh Ingredients</h3>
                    <p class="box-text">
                        We connect with reliable farmers and local suppliers to get fresh fruits and vegetables,
                        focusing on quality and minimal chemical exposure.
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="col-xl-3 col-md-6 process-box-wrap">
                <div class="process-box">
                    <div class="box-icon bg-white">
                        <img src="<?= base_url('assets/img/icon/process_box_2.svg') ?>" alt="Step 2 Icon">
                    </div>
                    <div class="box-img"
                         style="margin:0 auto 30px auto;max-width:200px;overflow:hidden;">
                        <img src="<?= base_url('assets/img/healthysafar/vegebag.jpg') ?>"
                             alt="Preparing with Care"
                             style="width:100%;height:auto;display:block;">
                    </div>
                    <p class="box-number">Step - 02</p>
                    <h3 class="box-title">Preparing with Care</h3>
                    <p class="box-text">
                        Every ingredient is carefully washed, cut and processed using methods that lock in nutrients
                        and maintain natural taste.
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="col-xl-3 col-md-6 process-box-wrap">
                <div class="process-box">
                    <div class="box-icon bg-white">
                        <img src="<?= base_url('assets/img/icon/process_box_3.svg') ?>" alt="Step 3 Icon">
                    </div>
                    <div class="box-img"
                         style="margin:0 auto 30px auto;max-width:200px;overflow:hidden;">
                        <img src="<?= base_url('assets/img/healthysafar/wash.jpg') ?>"
                             alt="Hygienic & Safe"
                             style="width:100%;height:auto;display:block;">
                    </div>
                    <p class="box-number">Step - 03</p>
                    <h3 class="box-title">Hygienic &amp; Safe Process</h3>
                    <p class="box-text">
                        We follow strict hygiene practices and use food-grade containers and packaging to keep
                        your food safe and fresh.
                    </p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="col-xl-3 col-md-6 process-box-wrap">
                <div class="process-box">
                    <div class="box-icon bg-white">
                        <img src="<?= base_url('assets/img/icon/process_box_4.svg') ?>" alt="Step 4 Icon">
                    </div>
                    <div class="box-img"
                         style="margin:0 auto 30px auto;max-width:200px;overflow:hidden;">
                        <img src="<?= base_url('assets/img/healthysafar/delivery.jpg') ?>"
                             alt="Delivering Wellness"
                             style="width:100%;height:auto;display:block;">
                    </div>
                    <p class="box-number">Step - 04</p>
                    <h3 class="box-title">Delivering Wellness</h3>
                    <p class="box-text">
                        We deliver your orders with care so that you receive them as fresh and flavourful as
                        they left our kitchen.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<!--==============================
 Why Choose Us (from Home)
==============================-->
<div class="overflow-hidden space">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 text-center text-xl-start">
                <div class="title-area">
                    <span class="sub-title">
                        <img src="<?= base_url('assets/img/theme-img/title_icon.svg') ?>" alt="shape">
                        Why Choose Us
                    </span>
                    <h2 class="sec-title">Nourish Your Body with Clean, Fresh Food</h2>
                    <p class="sec-text">
                        We believe healthy eating should be easy, practical and enjoyable. Healthy Safar works with
                        honest ingredients, careful preparation and mindful recipes so that your everyday food supports
                        your long-term health.
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center text-xl-start">
            <div class="choose-feature-area">
                <div class="row">
                    <div class="col-xl-7">
                        <div class="choose-feature-wrap">
                            <div class="choose-feature">
                                <div class="box-icon">
                                    <img src="<?= base_url('assets/img/icon/choose_feature_1.svg') ?>" alt="Icon">
                                </div>
                                <h3 class="box-title">Fresh &amp; Seasonal</h3>
                                <p class="box-text">
                                    We prioritise seasonal produce so that you get better taste and natural nutrition.
                                </p>
                            </div>

                            <div class="choose-feature">
                                <div class="box-icon">
                                    <img src="<?= base_url('assets/img/icon/choose_feature_2.svg') ?>" alt="Icon">
                                </div>
                                <h3 class="box-title">Mindful Preparation</h3>
                                <p class="box-text">
                                    Minimal processing, balanced recipes and smart combinations tailored for better health.
                                </p>
                            </div>

                            <div class="choose-feature">
                                <div class="box-icon">
                                    <img src="<?= base_url('assets/img/icon/choose_feature_3.svg') ?>" alt="Icon">
                                </div>
                                <h3 class="box-title">Balanced Food</h3>
                                <p class="box-text">
                                    From salads and soups to fruit mixes and juices, everything is planned to support
                                    your daily lifestyle.
                                </p>
                            </div>

                            <div class="choose-feature">
                                <div class="box-icon">
                                    <img src="<?= base_url('assets/img/icon/choose_feature_4.svg') ?>" alt="Icon">
                                </div>
                                <h3 class="box-title">Secure &amp; Easy Payments</h3>
                                <p class="box-text">
                                    Pay safely through trusted online payment methods and enjoy hassle-free ordering.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-5 d-none d-xl-block">
                        <div class="img-box2-wrap">
                            <div class="img-box2">
                                <div class="img1">
                                    <img src="<?= base_url('assets/img/healthysafar/tessvege.png') ?>" alt="Why Healthy Safar">
                                </div>
                                <div class="img2"><!-- reserved --></div>
                            </div>
                        </div>
                    </div>
                </div> <!--/.row-->
            </div>
        </div>
    </div>
</div>

<!--==============================
 Counter Area  
==============================-->
<div class="counter-sec11" data-bg-src="<?= base_url('assets/img/bg/counter_bg_1_1.jpg') ?>">
    <div class="container">
        <div class="counter-card-wrap">
            <div class="counter-card">
                <div class="box-icon">
                    <img src="<?= base_url('assets/img/icon/counter_card_1.svg') ?>" alt="Icon">
                </div>
                <div class="media-body">
                    <h2 class="box-number"><span class="counter-number">100</span>+</h2>
                    <p class="box-text">Fresh Products Offered</p>
                </div>
            </div>
            <div class="divider"></div>
            <div class="counter-card">
                <div class="box-icon">
                    <img src="<?= base_url('assets/img/icon/counter_card_2.svg') ?>" alt="Icon">
                </div>
                <div class="media-body">
                    <h2 class="box-number"><span class="counter-number">25</span>+</h2>
                    <p class="box-text">Team Members</p>
                </div>
            </div>
            <div class="divider"></div>
            <div class="counter-card">
                <div class="box-icon">
                    <img src="<?= base_url('assets/img/icon/counter_card_3.svg') ?>" alt="Icon">
                </div>
                <div class="media-body">
                    <h2 class="box-number"><span class="counter-number">1500</span>+</h2>
                    <p class="box-text">Happy Indian Customers</p>
                </div>
            </div>
            <div class="divider"></div>
            <div class="counter-card">
                <div class="box-icon">
                    <img src="<?= base_url('assets/img/icon/counter_card_4.svg') ?>" alt="Icon">
                </div>
                <div class="media-body">
                    <h2 class="box-number"><span class="counter-number">12</span>+</h2>
                    <p class="box-text">City Deliveries</p>
                </div>
            </div>
            <div class="divider"></div>
        </div>
    </div>
</div>

<!--==============================
 Testimonial Area – Indian Customers  
==============================-->
<!--<section class="overflow-hidden bg-smoke2" id="testi-sec">-->
<!--    <div class="shape-mockup testi-shape1" data-top="0" data-left="0">-->
<!--        <img src="<?= base_url('assets/img/normal/testi_shape.png') ?>" alt="shape">-->
<!--    </div>-->
<!--    <div class="shape-mockup" data-bottom="0" data-right="0">-->
<!--        <img src="<?= base_url('assets/img/shape/vector_shape_5.png') ?>" alt="shape">-->
<!--    </div>-->
<!--    <div class="container">-->
<!--        <div class="testi-card-area">-->
<!--            <div class="title-area">-->
<!--                <span class="sub-title">-->
<!--                    <img src="<?= base_url('assets/img/theme-img/title_icon.svg') ?>" alt="Icon">-->
<!--                    Testimonials-->
<!--                </span>-->
<!--                <h2 class="sec-title">What Our Customers Say</h2>-->
<!--            </div>-->
<!--            <div class="testi-card-slide">-->
<!--                <div class="swiper th-slider" id="testiSlide1" data-slider-options='{"effect":"slide"}'>-->
<!--                    <div class="swiper-wrapper">-->
                      
<!--                        <div class="swiper-slide">-->
<!--                            <div class="testi-card">-->
<!--                                <p class="testi-card_text">-->
<!--                                    “Healthy Safar salads and fruit boxes have become a part of my daily routine.-->
<!--                                    The ingredients are always fresh and the taste is light yet filling. Perfect-->
<!--                                    for busy working days.”-->
<!--                                </p>-->
<!--                                <div class="testi-card_profile">-->
<!--                                    <div class="testi-card_avater">-->
<!--                                        <img src="<?= base_url('assets/img/testimonial/testi_1_1.jpg') ?>" alt="Avater">-->
<!--                                    </div>-->
<!--                                    <div class="testi-card_content">-->
<!--                                        <h3 class="testi-card_name">Priya Sharma</h3>-->
<!--                                        <span class="testi-card_desig">Pune, Maharashtra</span>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                       
<!--                        <div class="swiper-slide">-->
<!--                            <div class="testi-card">-->
<!--                                <p class="testi-card_text">-->
<!--                                    “I order their cold-pressed juices and soups regularly. The flavours are very-->
<!--                                    natural and you can feel the difference compared to packaged options in the-->
<!--                                    market.”-->
<!--                                </p>-->
<!--                                <div class="testi-card_profile">-->
<!--                                    <div class="testi-card_avater">-->
<!--                                        <img src="<?= base_url('assets/img/testimonial/testi_1_2.jpg') ?>" alt="Avater">-->
<!--                                    </div>-->
<!--                                    <div class="testi-card_content">-->
<!--                                        <h3 class="testi-card_name">Amit Desai</h3>-->
<!--                                        <span class="testi-card_desig">Mumbai, Maharashtra</span>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                        
<!--                        <div class="swiper-slide">-->
<!--                            <div class="testi-card">-->
<!--                                <p class="testi-card_text">-->
<!--                                    “We started ordering Healthy Safar for our parents as well. They like the mild-->
<!--                                    seasoning and fresh ingredients, and we are happy about the hygiene and quality.”-->
<!--                                </p>-->
<!--                                <div class="testi-card_profile">-->
<!--                                    <div class="testi-card_avater">-->
<!--                                        <img src="<?= base_url('assets/img/testimonial/testi_1_3.jpg') ?>" alt="Avater">-->
<!--                                    </div>-->
<!--                                    <div class="testi-card_content">-->
<!--                                        <h3 class="testi-card_name">Sneha &amp; Rohan Kulkarni</h3>-->
<!--                                        <span class="testi-card_desig">Nashik, Maharashtra</span>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="icon-box">-->
<!--                    <button data-slider-prev="#testiSlide1" class="slider-arrow default">-->
<!--                        <i class="far fa-arrow-left"></i>-->
<!--                    </button>-->
<!--                    <button data-slider-next="#testiSlide1" class="slider-arrow default">-->
<!--                        <i class="far fa-arrow-right"></i>-->
<!--                    </button>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->

<?= $this->endSection() ?>