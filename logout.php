<?php
// logout.php — clean and expressive logout page

// Start session FIRST
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Clear session data
$_SESSION = [];

// Clear the session cookie for safety
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

// Destroy the session
session_destroy();

// Target page to redirect
$target = 'index.php?logout=success';

// Try redirect with headers first
if (!headers_sent()) {
    header('Location: ' . $target);
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="refresh" content="2;url=<?php echo htmlspecialchars($target, ENT_QUOTES); ?>">
<title>Logging out...</title>
<style>
body {
    background: #0b0b0b;
    color: #ffd700;
    font-family: 'Poppins', sans-serif;
    text-align: center;
    padding-top: 120px;
}
.emoji {
    font-size: 70px;
    margin-bottom: 10px;
}
.message {
    font-size: 22px;
    margin-bottom: 10px;
}
.sub {
    color: #aaa;
    font-size: 15px;
}
</style>
</head>
<body>
    <div class="emoji">😔</div>
    <div class="message">You’ve been logged out safely.</div>
    <div class="sub">We’ll miss you here at <strong>Boarding Gigs</strong> 💛</div>
    <div class="sub">Redirecting you back to the home page...</div>

    <script>
        // Fallback JS redirect
        setTimeout(() => {
            window.location.href = "<?php echo addslashes($target); ?>";
        }, 2000);
    </script>
</body>
</html>
