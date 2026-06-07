<?php
// about.php
require_once 'includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero container">
    <div class="row justify-content-center">
        <div class="col-lg-8 animate-fade-in">
            <span class="badge-premium mb-4 d-inline-flex"><i class="bi bi-grid-3x3-gap-fill me-2"></i>Our Platform</span>
            <h1 class="mb-3">About Alain-e-Shopping</h1>
            <p class="lead" style="font-size: 1.15rem; color: var(--text-secondary); max-width: 650px; margin: 0 auto;">
                A next-generation marketplace connecting verified sellers with smart buyers across the globe — delivering premium physical goods and instant digital resources.
            </p>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="container py-5">
    <div class="row gy-5 align-items-center">
        <div class="col-lg-6 animate-fade-in-left">
            <span class="text-gradient-primary fw-bold text-uppercase small" style="letter-spacing: 2px;">Our Mission</span>
            <h2 class="text-white mt-2 mb-4">Built for the Future of Commerce</h2>
            <p class="text-secondary" style="font-size: 1.05rem; line-height: 1.8;">
                Alain-e-Shopping was founded with one vision: to create a transparent, fair, and premium marketplace where independent sellers can thrive and customers can discover quality products with confidence.
            </p>
            <p class="text-secondary" style="font-size: 1.05rem; line-height: 1.8;">
                We bridge the gap between digital and physical commerce — from downloadable icon kits and eBooks to smart home devices and fashion — all under one intelligent, verified roof.
            </p>
            <div class="d-flex gap-3 mt-4">
                <a href="shop.php" class="btn btn-premium"><i class="bi bi-bag-heart me-2"></i>Browse Shop</a>
                <a href="register.php" class="btn btn-premium-secondary"><i class="bi bi-person-plus me-2"></i>Join Us</a>
            </div>
        </div>
        <div class="col-lg-6 animate-fade-in-right">
            <div class="card-glass p-5 text-center" style="background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(124,58,237,0.1) 100%);">
                <div class="row g-4">
                    <div class="col-6">
                        <div class="stats-card">
                            <h3 style="font-size: 2.2rem; font-weight: 900; background: var(--gradient-tertiary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">10K+</h3>
                            <p class="text-secondary small mb-0">Orders Fulfilled</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-card">
                            <h3 style="font-size: 2.2rem; font-weight: 900; background: var(--gradient-tertiary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">500+</h3>
                            <p class="text-secondary small mb-0">Verified Sellers</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-card">
                            <h3 style="font-size: 2.2rem; font-weight: 900; background: var(--gradient-tertiary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">99.8%</h3>
                            <p class="text-secondary small mb-0">Customer Satisfaction</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-card">
                            <h3 style="font-size: 2.2rem; font-weight: 900; background: var(--gradient-tertiary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">50+</h3>
                            <p class="text-secondary small mb-0">Countries Served</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="py-5" style="background: rgba(0,0,0,0.2);">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-gradient-primary fw-bold text-uppercase small" style="letter-spacing: 2px;">Core Values</span>
            <h2 class="text-white mt-2">What Drives Us</h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card-glass p-4 text-center h-100">
                    <div class="feature-icon-circle">
                        <i class="bi bi-shield-check text-success"></i>
                    </div>
                    <h4 class="text-white font-heading mb-3">Verified Trust</h4>
                    <p class="text-secondary mb-0">Every seller undergoes document verification and compliance review before publishing products. Buyers shop with confidence knowing each store is authenticated.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-glass p-4 text-center h-100">
                    <div class="feature-icon-circle">
                        <i class="bi bi-lightning-charge-fill text-warning"></i>
                    </div>
                    <h4 class="text-white font-heading mb-3">Instant Access</h4>
                    <p class="text-secondary mb-0">Digital products are delivered instantly upon purchase. No waiting, no delays — your files are available in your customer dashboard the moment payment is confirmed.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-glass p-4 text-center h-100">
                    <div class="feature-icon-circle">
                        <i class="bi bi-globe2" style="color: var(--accent-primary);"></i>
                    </div>
                    <h4 class="text-white font-heading mb-3">Global Reach</h4>
                    <p class="text-secondary mb-0">Our platform supports sellers and buyers from across the globe. From Kigali to São Paulo, commerce has no borders on Alain-e-Shopping.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-glass p-4 text-center h-100">
                    <div class="feature-icon-circle">
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                    <h4 class="text-white font-heading mb-3">Premium Quality</h4>
                    <p class="text-secondary mb-0">We curate only premium listings. Our community-driven rating and review system ensures products meet rigorous quality standards before reaching wide audiences.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-glass p-4 text-center h-100">
                    <div class="feature-icon-circle">
                        <i class="bi bi-credit-card-2-front-fill" style="color: var(--accent-tertiary);"></i>
                    </div>
                    <h4 class="text-white font-heading mb-3">Secure Payments</h4>
                    <p class="text-secondary mb-0">Multiple secure payment gateways including Credit/Debit Card, Mobile Money (MTN/Airtel), and PayPal. All transactions are encrypted and protected.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-glass p-4 text-center h-100">
                    <div class="feature-icon-circle">
                        <i class="bi bi-headset text-info"></i>
                    </div>
                    <h4 class="text-white font-heading mb-3">Dedicated Support</h4>
                    <p class="text-secondary mb-0">Our support team is available to assist with product enquiries, seller compliance, dispute resolution, and account management — every step of the journey.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team / CTA Section -->
<section class="container py-5">
    <div class="card-glass p-5 text-center" style="background: linear-gradient(135deg, rgba(99,102,241,0.1) 0%, rgba(236,72,153,0.1) 100%);">
        <i class="bi bi-rocket-takeoff-fill fs-1 text-gradient-primary d-block mb-4"></i>
        <h2 class="text-white mb-3">Ready to Join Alain-e-Shopping?</h2>
        <p class="text-secondary mb-5 mx-auto" style="max-width: 550px;">Whether you're a buyer looking for premium products or a seller ready to reach a global audience — your journey starts here.</p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="register.php" class="btn btn-premium btn-lg"><i class="bi bi-person-plus-fill me-2"></i>Create Free Account</a>
            <a href="shop.php" class="btn btn-premium-secondary btn-lg"><i class="bi bi-bag-heart me-2"></i>Explore Catalogue</a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
