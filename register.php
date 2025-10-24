<?php include 'config.php'; include 'headers.php'; ?>

<div class="form-container">
    <h2>Join Boarding Gigs</h2>
    <p class="text-center mb-2">Create your account and start your boarding journey today</p>

    <?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $user_type = in_array($_POST['user_type'], ['owner','seeker']) ? $_POST['user_type'] : 'seeker';

        $errors = [];

        // Validation
        if(empty($username) || strlen($username) < 3) {
            $errors[] = "Username must be at least 3 characters long";
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address";
        }

        if(strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters long";
        }

        if($password !== $confirm_password) {
            $errors[] = "Passwords do not match";
        }

        if(empty($errors)) {
            // Check if email already exists
            $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $check_stmt->bind_param('s', $email);
            $check_stmt->execute();
            $check_stmt->store_result();

            if($check_stmt->num_rows > 0) {
                echo '<div class="alert error">Email address is already registered. <a href="login.php">Login here</a></div>';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, ?)");
                $stmt->bind_param('ssss', $username, $email, $hash, $user_type);
                
                if($stmt->execute()) {
                    echo '<div class="alert success">Account created successfully! <a href="login.php">Login to continue</a></div>';
                } else {
                    echo '<div class="alert error">Registration failed. Please try again.</div>';
                }
            }
            $check_stmt->close();
        } else {
            foreach($errors as $error) {
                echo '<div class="alert error">' . $error . '</div>';
            }
        }
    }
    ?>

    <form method="post" class="auth-form">
        <div class="form-group">
            <label for="username">Username *</label>
            <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? e($_POST['username']) : ''; ?>" required minlength="3">
        </div>

        <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? e($_POST['email']) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Password *</label>
            <input type="password" id="password" name="password" required minlength="6">
            <small>Minimum 6 characters</small>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password *</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <div class="form-group">
            <label for="user_type">I want to:</label>
            <select id="user_type" name="user_type" required>
                <option value="seeker" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] == 'seeker') ? 'selected' : ''; ?>>Find Boarding Places</option>
                <option value="owner" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] == 'owner') ? 'selected' : ''; ?>>Rent Out My Space</option>
            </select>
        </div>

        <button type="submit" class="btn btn-full">Create Account</button>
        
        <div class="auth-links text-center mt-2">
            <p>Already have an account? <a href="login.php">Sign in here</a></p>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>