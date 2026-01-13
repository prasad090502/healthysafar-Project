<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!--==============================
 Breadcumb
==============================-->
<div class="breadcumb-wrapper" data-bg-src="<?= base_url('assets/img/bg/breadcumb-bg.jpg') ?>">
    <div class="container">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">Contact Us</h1>
            <ul class="breadcumb-menu">
                <li><a href="<?= site_url('/') ?>">Home</a></li>
                <li>Contact Us</li>
            </ul>
        </div>
    </div>
</div>

<!--==============================
 Contact Info Area
==============================-->
<div class="space">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-md-7">
                <div class="title-area text-center">
                    <h2 class="sec-title">Contact Information</h2>
                    <p class="sec-text">
                        We’d love to hear from you. Reach out for any queries about our healthy and organic products.
                    </p>
                </div>
            </div>
        </div>

        <div class="row gy-4 justify-content-center">
            <!-- Address -->
            

            <!-- Phone -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="contact-feature">
                    <div class="box-icon bg-theme2">
                        <i class="fa-light fa-phone"></i>
                    </div>
                    <div class="media-body">
                        <h3 class="box-title">Phone Number</h3>
                        <p class="box-text">
                            <a href="tel:+919876543210">+91 98765 43210</a>
                            <a href="tel:+919123456789">+91 91234 56789</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Email -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="contact-feature">
                    <div class="box-icon bg-title">
                        <i class="fa-light fa-envelope"></i>
                    </div>
                    <div class="media-body">
                        <h3 class="box-title">Email Address</h3>
                        <p class="box-text">
                            <a href="mailto:support@healthysafar.in">support@healthysafar.in</a>
                            <a href="mailto:info@healthysafar.in">info@healthysafar.in</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="contact-feature">
                    <div class="media-body">
                        <h3 class="box-title">Follow Social Media</h3>
                        <div class="th-social">
                            <a target="_blank" href="https://facebook.com/"><i class="fab fa-facebook-f"></i></a>
                            <a target="_blank" href="https://twitter.com/"><i class="fab fa-twitter"></i></a>
                            <a target="_blank" href="https://instagram.com/"><i class="fab fa-instagram"></i></a>
                            
                            
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- /row -->
    </div>
</div>

<!--==============================
 Contact Area
==============================-->
<div class="space-bottom">
    <div class="container">
        <form action="<?= site_url('contact') ?>" method="post" class="contact-form input-smoke ajax-contact">
            <?= csrf_field() ?>
            <h2 class="sec-title">Get In Touch</h2>
            <div class="row">
                <!-- Name -->
                <div class="form-group col-md-6">
                    <input
                        type="text"
                        class="form-control"
                        name="name"
                        id="name"
                        placeholder="Your Name"
                        required
                    >
                    <i class="fal fa-user"></i>
                </div>

                <!-- Email -->
                <div class="form-group col-md-6">
                    <input
                        type="email"
                        class="form-control"
                        name="email"
                        id="email"
                        placeholder="Email Address"
                        required
                    >
                    <i class="fal fa-envelope"></i>
                </div>

                <!-- Phone -->
                <div class="form-group col-md-6">
                    <input
                        type="tel"
                        class="form-control"
                        name="number"
                        id="number"
                        placeholder="Phone Number"
                    >
                    <i class="fal fa-phone"></i>
                </div>

                <!-- Subject -->
                <div class="form-group col-md-6">
                    <select name="subject" id="subject" class="form-select">
                        <option value="" disabled selected hidden>Select Subject</option>
                        <option value="Organic Food Enquiry">Organic Food Enquiry</option>
                        <option value="Fresh Fruits & Vegetables">Fresh Fruits & Vegetables</option>
                        <option value="Bulk / Wholesale Order">Bulk / Wholesale Order</option>
                        <option value="Other">Other</option>
                    </select>
                    <i class="fal fa-chevron-down"></i>
                </div>

                <!-- Message -->
                <div class="form-group col-12">
                    <textarea
                        name="message"
                        id="message"
                        cols="30"
                        rows="3"
                        class="form-control"
                        placeholder="Your Message"
                        required
                    ></textarea>
                    <i class="fal fa-pencil"></i>
                </div>

                <!-- Submit -->
                <div class="form-btn col-12">
                    <button class="th-btn btn-fw" type="submit">
                        Send Message<i class="fas fa-chevrons-right ms-2"></i>
                    </button>
                </div>
            </div>

            <?php if (session()->getFlashdata('contact_message')): ?>
                <p class="form-messages mb-0 mt-3">
                    <?= esc(session()->getFlashdata('contact_message')) ?>
                </p>
            <?php endif; ?>
        </form>
    </div>
</div>

<!--==============================
 Map Area
==============================-->
<div class="space-bottom">
    <div class="contact-map">
        <iframe
            src="https://www.google.com/maps?q=Chhatrapati+Sambhajinagar,+Maharashtra,+India&output=embed"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
</div>

<?= $this->endSection() ?>