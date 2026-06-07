<?php
// contact.php
require_once 'includes/header.php';

$contact_name    = '';
$contact_email   = '';
$contact_subject = '';
$contact_msg     = '';
$success_msg     = '';
$error_msg       = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contact_name    = trim($_POST['contact_name'] ?? '');
    $contact_email   = trim($_POST['contact_email'] ?? '');
    $contact_subject = trim($_POST['contact_subject'] ?? '');
    $contact_msg     = trim($_POST['contact_message'] ?? '');

    if (empty($contact_name) || empty($contact_email) || empty($contact_subject) || empty($contact_msg)) {
        $error_msg = "Please fill in all fields before submitting.";
    } elseif (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Please enter a valid email address.";
    } else {
        // In a real system you'd send an email here. For now we show a success message.
        $success_msg = "Thank you, {$contact_name}! Your message has been received. We'll respond to {$contact_email} within 24 hours.";
        $contact_name = $contact_email = $contact_subject = $contact_msg = '';
    }
}
?>

<!-- Page Hero -->
<section class="page-hero container">
    <div class="row justify-content-center">
        <div class="col-lg-7 animate-fade-in">
            <span class="badge-premium mb-4 d-inline-flex"><i class="bi bi-chat-dots-fill me-2"></i>Get In Touch</span>
            <h1 class="mb-3">Contact Us</h1>
            <p class="lead" style="font-size: 1.1rem; color: var(--text-secondary);">
                Have a question, need support, or want to partner with us? We're here to help. Reach out and our team will respond within 24 hours.
            </p>
        </div>
    </div>
</section>

<div class="container py-4 pb-5">
    <div class="row gy-5">
        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="card-glass p-5">
                <h3 class="text-white mb-4 font-heading"><i class="bi bi-envelope-fill text-gradient-primary me-2"></i>Send Us a Message</h3>

                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-danger mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error_msg); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success_msg)): ?>
                    <div class="alert alert-success mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i><?php echo htmlspecialchars($success_msg); ?>
                    </div>
                <?php endif; ?>

                <form action="contact.php" method="POST" class="d-flex flex-column gap-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-glass-label" for="contact_name">Full Name</label>
                            <input type="text" name="contact_name" id="contact_name" class="form-control form-glass-input"
                                   placeholder="e.g. John Doe" value="<?php echo htmlspecialchars($contact_name); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-glass-label" for="contact_email">Email Address</label>
                            <input type="email" name="contact_email" id="contact_email" class="form-control form-glass-input"
                                   placeholder="you@example.com" value="<?php echo htmlspecialchars($contact_email); ?>" required>
                        </div>
                    </div>

                    <div>
                        <label class="form-glass-label" for="contact_subject">Subject</label>
                        <select name="contact_subject" id="contact_subject" class="form-select form-glass-input" required>
                            <option value="">Select a topic...</option>
                            <option value="General Inquiry" <?php echo $contact_subject === 'General Inquiry' ? 'selected' : ''; ?>>General Inquiry</option>
                            <option value="Order Support" <?php echo $contact_subject === 'Order Support' ? 'selected' : ''; ?>>Order Support</option>
                            <option value="Seller Compliance" <?php echo $contact_subject === 'Seller Compliance' ? 'selected' : ''; ?>>Seller Compliance</option>
                            <option value="Technical Issue" <?php echo $contact_subject === 'Technical Issue' ? 'selected' : ''; ?>>Technical Issue</option>
                            <option value="Partnership" <?php echo $contact_subject === 'Partnership' ? 'selected' : ''; ?>>Partnership / Business</option>
                            <option value="Report a Problem" <?php echo $contact_subject === 'Report a Problem' ? 'selected' : ''; ?>>Report a Problem</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-glass-label" for="contact_message">Your Message</label>
                        <textarea name="contact_message" id="contact_message" rows="6" class="form-control form-glass-input"
                                  placeholder="Describe your issue or question in detail..."><?php echo htmlspecialchars($contact_msg); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-premium py-3">
                        <i class="bi bi-send-fill me-2"></i>Send Message
                    </button>
                </form>
            </div>
        </div>

        <!-- Contact Info Sidebar -->
        <div class="col-lg-5">
            <div class="d-flex flex-column gap-4">
                <!-- Contact Details -->
                <div class="card-glass p-4">
                    <h5 class="text-white mb-4 font-heading"><i class="bi bi-info-circle me-2 text-info"></i>Contact Details</h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-start gap-3">
                            <div class="feature-icon-circle" style="width: 48px; height: 48px; font-size: 1.2rem; flex-shrink: 0;">
                                <i class="bi bi-envelope-fill" style="color: var(--accent-primary);"></i>
                            </div>
                            <div>
                                <div class="text-secondary small">Email</div>
                                <a href="mailto:contact@alain-e-shopping.com" class="text-white fw-bold text-decoration-none">contact@alain-e-shopping.com</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <div class="feature-icon-circle" style="width: 48px; height: 48px; font-size: 1.2rem; flex-shrink: 0;">
                                <i class="bi bi-telephone-fill" style="color: var(--accent-success);"></i>
                            </div>
                            <div>
                                <div class="text-secondary small">Phone</div>
                                <div class="text-white fw-bold">+250 788 000 000</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <div class="feature-icon-circle" style="width: 48px; height: 48px; font-size: 1.2rem; flex-shrink: 0;">
                                <i class="bi bi-geo-alt-fill text-danger"></i>
                            </div>
                            <div>
                                <div class="text-secondary small">Office</div>
                                <div class="text-white fw-bold">Kigali, Rwanda</div>
                                <div class="text-secondary small">KN 12 Ave, Nyarugenge District</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <div class="feature-icon-circle" style="width: 48px; height: 48px; font-size: 1.2rem; flex-shrink: 0;">
                                <i class="bi bi-clock-fill text-warning"></i>
                            </div>
                            <div>
                                <div class="text-secondary small">Business Hours</div>
                                <div class="text-white fw-bold">Mon – Fri: 8:00AM – 6:00PM (CAT)</div>
                                <div class="text-secondary small">Sat: 9:00AM – 1:00PM</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="card-glass p-4">
                    <h5 class="text-white mb-4 font-heading"><i class="bi bi-share-fill me-2 text-gradient-primary"></i>Follow Us</h5>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#" class="btn btn-premium-secondary py-2 px-3">
                            <i class="bi bi-facebook me-1"></i> Facebook
                        </a>
                        <a href="#" class="btn btn-premium-secondary py-2 px-3">
                            <i class="bi bi-twitter me-1"></i> Twitter
                        </a>
                        <a href="#" class="btn btn-premium-secondary py-2 px-3">
                            <i class="bi bi-instagram me-1"></i> Instagram
                        </a>
                        <a href="#" class="btn btn-premium-secondary py-2 px-3">
                            <i class="bi bi-linkedin me-1"></i> LinkedIn
                        </a>
                    </div>
                </div>

                <!-- Map Placeholder -->
                <div class="contact-map-placeholder">
                    <i class="bi bi-map fs-1 mb-3" style="color: var(--accent-secondary); opacity: 0.7;"></i>
                    <div class="text-secondary small">Kigali, Rwanda</div>
                    <div class="text-muted" style="font-size: 0.8rem;">Interactive map coming soon</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
