<?php
if (session_status() == PHP_SESSION_NONE) session_start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
?>
<style>
    /* Base styles */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background-color: #f9f9fb;
    color: #333;
    line-height: 1.6;
}

/* Container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Header */
header {
    background-color: #fff;
    border-bottom: 1px solid #e0e0e0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.header-inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo a {
    font-size: 1.8rem;
    font-weight: bold;
    color: #2c3e50;
    text-decoration: none;
}

.nav {
    display: flex;
    align-items: center;
    gap: 15px;
}

nav a {
    color: #555;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 4px;
    transition: background-color 0.2s, color 0.2s;
}

.nav a:hover {
    background-color: #ecf0f1;
    color: #000;
}

.nav-btn {
    background-color: #ecf0f1;
    padding: 8px 14px;
    border-radius: 4px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.nav-btn:hover {
    background-color: #d0d7dd;
    color: #000;
}

.nav-btn.primary {
    background-color:none;
    color: white;
}

.nav-btn.primary:hover {
    background-color: #2980b9;
}

/* Logout Button */
.logout {
    background-color: #e74c3c;
    color: #fff;
}

.logout:hover {
    background-color: #c0392b;
}

/* Main */
main.container {
    margin-top: 40px;
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    opacity: 0.85;
}

/* Footer */
footer {
    background-color: #f1f1f1;
    color: #666;
    text-align: center;
    padding: 20px;
    margin-top: 40px;
    font-size: 0.9rem;
}
.main-header.scrolled {
    background: rgba(255, 255, 255, 0.95);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
}

</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boarding Gigs - Find Your Perfect Stay</title>
    <meta name="description" content="Discover the best boarding options and rental gigs. Connect with owners and find your ideal living space.">
    <link rel="stylesheet" href="style.css">
</head>
<body>
   <header class="main-header">
  <div class="container header-inner">
    <h1 class="logo">
      <a href="index.php">🏠 Boarding Gigs</a>
    </h1>
    <nav class="nav">
      <a href="index.php">Home</a>
      <a href="view_gigs.php">Browse Gigs</a>
      <a href="about.php">About</a>
      <a href="contact.php">Contact</a>
      <?php if(!$user): ?>
        <a href="login.php" class="nav-btn">Login</a>
        <a href="register.php" class="nav-btn">Register</a>
      <?php else: ?>
        <?php if($user['user_type'] === 'owner'): ?>
          <a href="owner_dashboard.php" class="nav-btn">Dashboard</a>
        <?php elseif($user['user_type'] === 'admin'): ?>
          <a href="admin_dashboard.php" class="nav-btn">Admin Panel</a>
        <?php endif; ?>
        <a href="logout.php" class="nav-btn logout">
          Logout (<?php echo htmlspecialchars($user['username']); ?>)
        </a>
      <?php endif; ?>
    </nav>
  </div>
</header>

    <main class="container">
        <script>
  window.addEventListener("scroll", () => {
    const header = document.querySelector(".main-header");
    if (window.scrollY > 10) {
      header.classList.add("scrolled");
    } else {
      header.classList.remove("scrolled");
    }
  });
</script>


