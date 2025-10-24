<?php
session_start();
include 'config.php';
include 'headers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, username, email, password, user_type FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id'        => $user['id'],
                'username'  => $user['username'],
                'email'     => $user['email'],
                'user_type' => $user['user_type'],
            ];

            $redirect = match($user['user_type']) {
                'owner' => 'owner_dashboard.php',
                'admin' => 'admin_dashboard.php',
                'seeker' => 'index.php',
            };
            
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = 'Invalid email or password';
        }
    } 
    else {
        $error = 'No account found with this email.';
       
    }

    $stmt->close();
}
?>
<!-- Now begin your HTML output here (after PHP logic above) 
<!DOCTYPE html>
<html lang="en">
<head>
     Your <style> block and other headers 
</head>
<body>-->
    <?php if (!empty($error)): ?>
    <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
    <div class="form-container">
    <h2>Welcome Back</h2>
    <p>Sign in to your Boarding Gigs account</p>
   <form method="post" class="auth-form" autocomplete="off">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                required 
                autocomplete="username"
                placeholder="Enter your email">
        </div>

        <div class="form-group password-group">
            <label for="password">Password</label>
            <div class="password-field">
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    placeholder="Enter your password">
                    
                <button type="button" class="toggle-password" aria-label="Show password">👁️</button>
            </div>
            <br>
        </div>

        <button type="submit" class="btn-full">Sign In</button>

        <div class="auth-links">
            <p>Don't have an account? <a href="register.php">Create one here</a></p>
        </div>
    </form>
</div>
<style>
/* === FULL PAGE BACKGROUND === */
body {
    margin: 0;
    padding: 0;
    background: url('') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Poppins', sans-serif;
    color: #fff;
}

/* === TRANSPARENT LOGIN BOX === */
.form-container {
    width: 80%;
    max-width: 520px;
    margin: auto;
    margin-top: -40px;
    padding: 35px 30px;
    background: rgba(0, 0, 0, 0.55); /* more transparent */
    border: 1px solid rgba(255,255,255,0.2);
    backdrop-filter: blur(8px); /* glass effect */
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.5);
    text-align: center;
    transition: all 0.3s ease-in-out;
}

.form-container:hover {
    background: rgba(0, 0, 0, 0.65);
    box-shadow: 0 12px 30px rgba(0,0,0,0.6);
}

.form-container h2 {
    color: #ffd700;
    margin-bottom: 10px;
    font-size: 32px;
    letter-spacing: 1px;
}

.form-container p {
    color: #eee;
    margin-bottom: 0px;
    font-size: 14px;
}

/* === INPUTS === */
.form-group {
    text-align: left;
    margin-bottom: 0px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    color: #ffd700;
    font-weight: 500;
}

.form-group input {
    width: 100%;
    padding: 12px 15px;
    border: none;
    border-radius: 8px;
    background: rgba(255,255,255,0.85); /* Changed to more opaque white */
    color: #000; /* Changed to black for better visibility */
    font-size: 15px;
    outline: none;
    transition: all 0.3s ease;
}

.form-group input:focus {
    background: rgba(255,255,255,0.95);
    box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.5);
}

.form-group input::placeholder {
    color: rgba(0,0,0,0.6); /* Changed to dark placeholder */
}

/* === PASSWORD FIELD WITH ICON === */
.password-field {
    position: relative;
    display: flex;
    align-items: center;
}

.toggle-password {
    position: absolute;
    right: 12px;
    background: transparent;
    border: none;
    cursor: pointer;
    color: #555; /* Darker color for better visibility */
    transition: 0.3s;
    font-size: 18px;
    z-index: 10;
}

.toggle-password:hover {
    color: #ffd700;
}

/* === BUTTON === */
.btn-full {
    width: 100%;
    padding: 12px;
    background: #ffd700;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    color: #000;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

.btn-full:hover {
    background: #ffea00;
}

/* === LINKS === */
.auth-links {
    margin-top: 15px;
    font-size: 14px;
    color: #ccc;
}

.auth-links a {
    color: #ffd700;
    text-decoration: none;
}

.auth-links a:hover {
    text-decoration: underline;
}

/* === ALERTS === */
.alert.error {
    background: rgba(255, 0, 0, 0.2);
    color: #ff8080;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 20px;
}
</style>


<script>
const togglePassword = document.querySelector('.toggle-password');
const passwordInput = document.getElementById('password');

togglePassword.addEventListener('click', () => {
    const isPassword = passwordInput.type === 'password';
    passwordInput.type = isPassword ? 'text' : 'password';
    togglePassword.textContent = isPassword ? '🙈' : '👁️';
});
</script>

<?php include 'footer.php'; ?>     