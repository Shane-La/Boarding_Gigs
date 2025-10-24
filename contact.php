<?php
include 'config.php';
include 'headers.php';

// Safe output helper
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

$alerts = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '') {
        $alerts[] = ['type' => 'error', 'text' => 'Name is required'];
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alerts[] = ['type' => 'error', 'text' => 'Valid email is required'];
    }

    if ($message === '' || mb_strlen($message) < 10) {
        $alerts[] = ['type' => 'error', 'text' => 'Message must be at least 10 characters long'];
    }

    if (empty($alerts)) {
        $alerts[] = ['type' => 'success', 'text' => "✅ Thank you for your message, $name! We will get back to you soon."];
        $_POST = []; // clear after success
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us | Boarding Gigs</title>
<style>
/* ==== BACKGROUND ==== */
body {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
    background: url('WhatsApp Image 2025-10-19 at 16.34.21_9a7a2344.jpg') no-repeat center center fixed;
    background-size: cover;
    color: #fff;
}

/* ==== CONTAINER ==== */
.form-container {
    background: rgba(0, 0, 0, 0.7);
    max-width: 650px;
    margin: auto;
    margin-top: -40px;
    padding: 40px 35px;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.6);
    animation: fadeSlideUp 0.8s ease;
}

/* ==== HEADINGS ==== */
.form-container h2 {
    text-align: center;
    color: #ffd700;
    font-size: 34px;
    margin-bottom: 8px;
    letter-spacing: 1px;
    border-bottom: 2px solid #ffd700;
    display: inline-block;
    padding-bottom: 5px;
}
.form-container .sub {
    text-align: center;
    color: #ddd;
    font-size: 15px;
    margin-bottom: 25px;
}

/* ==== FORM INPUTS ==== */
.form-group { margin-bottom: 18px; }
label {
    display: block;
    margin-bottom: 6px;
    color: #ffd700;
    font-weight: 500;
    font-size: 14px;
}
input[type="text"],
input[type="email"],
textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ffd700;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
    font-size: 15px;
    font-weight: 400;
    outline: none;
    transition: all 0.3s ease;
}
textarea {
    resize: none;
}
input::placeholder,
textarea::placeholder {
    color: #bbb;
}
input:focus,
textarea:focus {
    border-color: #fff;
    background: rgba(255,255,255,0.1);
    box-shadow: 0 0 8px rgba(255,215,0,0.8);
}

/* ==== BUTTON ==== */
.btn-full {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    background: linear-gradient(90deg, #ffd700, #ffb300);
    color: #000;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}
.btn-full:hover {
    background: linear-gradient(90deg, #ffed4b, #ffc107);
    transform: scale(1.03);
}

/* ==== ALERT BOX ==== */
.alert {
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    text-align: center;
    font-weight: 500;
}
.alert.success {
    background: rgba(40, 167, 69, 0.95);
    color: #fff;
}
.alert.error {
    background: rgba(220, 53, 69, 0.95);
    color: #fff;
}

/* ==== CONTACT INFO ==== */
.contact-info {
    margin-top: 30px;
    background: rgba(0, 0, 0, 0.55);
    padding: 18px;
    border-radius: 12px;
    text-align: center;
}
.contact-info h4 {
    color: #ffd700;
    margin-bottom: 10px;
    letter-spacing: 0.5px;
}
.contact-info p {
    color: #ccc;
    margin: 5px 0;
}

/* ==== ANIMATION ==== */
@keyframes fadeSlideUp {
    0% { opacity: 0; transform: translateY(40px); }
    100% { opacity: 1; transform: translateY(0); }
}

/* === ONLY MESSAGE TEXTAREA IN BLACK === */
textarea {
    color: #000 !important;
    background: rgba(255, 255, 255, 0.9) !important;
}
</style>
</head>
<body>

<div class="form-container">
    <h2>Contact Us</h2>
    <p class="sub">Have questions about listings, payments, or finding a boarding space?<br>
    Send us a message and we’ll reply within 24 hours.</p>

    <?php if (!empty($alerts)): ?>
        <?php foreach ($alerts as $a): ?>
            <div class="alert <?php echo $a['type']; ?>">
                <?php echo h($a['text']); ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <form method="post" action="">
        <div class="form-group">
            <label for="name">Your Name *</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" value="<?php echo isset($_POST['name']) ? h($_POST['name']) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Your Email *</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo isset($_POST['email']) ? h($_POST['email']) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="message">Message *</label>
            <textarea id="message" name="message" rows="5" placeholder="Type your message here..." minlength="10" required><?php echo isset($_POST['message']) ? h($_POST['message']) : ''; ?></textarea>
        </div>

        <button type="submit" class="btn-full">Send Message ✉️</button>
    </form>

    <div class="contact-info">
        <h4>Other Ways to Reach Us</h4>
        <p>📧 <strong>Email:</strong> support@boardinggigs.com</p>
        <p>📞 <strong>Phone:</strong> +94 77 123 4567</p>
        <p>🏢 <strong>Office:</strong> 123 Boarding Street, Colombo, Sri Lanka</p>
        <p>🕒 <em>Mon–Fri, 9:00 AM – 6:00 PM</em></p>
    </div>
</div>

</body>
</html>

<?php include 'footer.php'; ?>
