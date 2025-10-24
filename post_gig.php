<?php 
include 'config.php'; 
require_owner(); 
include 'headers.php'; 

$user_id = $_SESSION['user']['id'];
?>

<div class="form-container">
    <h2>Post a New Boarding Gig</h2>
    <p class="text-center mb-2">Fill in the details about your boarding space. You'll activate it after payment.</p>

    <?php
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Trim inputs
        $title       = trim($_POST['title']);
        $description = trim($_POST['description']);
        $location    = trim($_POST['location']);
        $conditions  = trim($_POST['conditions']);
        $phone       = trim($_POST['phone_number']);
        $photo       = trim($_POST['photo']);
        $price       = floatval($_POST['price']);
        
        $errors = [];
        
        // Validation
        if(empty($title) || strlen($title) < 10) {
            $errors[] = "Title should be at least 10 characters long.";
        }
        
        if(empty($description) || strlen($description) < 50) {
            $errors[] = "Description should be at least 50 characters long.";
        }
        
        if(empty($location)) {
            $errors[] = "Location is required.";
        }
        
        if(empty($conditions)) {
            $errors[] = "Please specify conditions and rules.";
        }

        // Sri Lanka phone validation: 0XXXXXXXXX or +94XXXXXXXXX (no spaces)
        if(empty($phone) || !preg_match('/^(?:0\d{9}|\+94\d{9})$/', str_replace([' ', '-', '(', ')'], '', $phone))) {
            $errors[] = "Please enter a valid Sri Lankan phone number (e.g., 0771234567 or +94771234567).";
        }
        
        // Keep same numeric guardrails, but in LKR
        if($price < 1000 || $price > 100000) {
            $errors[] = "Price should be between Rs 1,000 and Rs 100,000 per month.";
        }
        
        if(empty($errors)) {
            $stmt = $conn->prepare("
                INSERT INTO gigs (user_id, title, description, location, conditions, phone_number, photo, price, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')
            ");
            $stmt->bind_param('issssssd', $user_id, $title, $description, $location, $conditions, $phone, $photo, $price);
            
            if($stmt->execute()) {
                $gig_id = $conn->insert_id;
                header('Location: gig_success.php?gig_id=' . $gig_id);
                exit;
            } else {
                echo '<div class="alert error">Error while posting gig: ' . e($conn->error) . '</div>';
            }
            $stmt->close();
        } else {
            foreach($errors as $error) {
                echo '<div class="alert error">' . e($error) . '</div>';
            }
        }
    }
    ?>

    <form method="post" class="gig-form" id="gigForm" novalidate>
        <div class="form-section">
            <h3>Basic Information</h3>
            
            <div class="form-group">
                <label for="title">Gig Title *</label>
                <input type="text" id="title" name="title" 
                       value="<?php echo isset($_POST['title']) ? e($_POST['title']) : ''; ?>" 
                       required minlength="10" maxlength="200"
                       placeholder="e.g., Luxurious Room near ,Wijerama">
                <small>Make it descriptive and attractive (10–200 characters)</small>
            </div>

            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" rows="5" 
                          required minlength="50" maxlength="2000"
                          placeholder="Describe the room/annex, amenities (water, Wi-Fi, furniture), distance to bus/rail, nearby universities, and any highlights..."><?php echo isset($_POST['description']) ? e($_POST['description']) : ''; ?></textarea>
                <small class="char-count"><span id="descCount">0</span>/2000 characters</small>
            </div>

            <div class="form-group">
                <label for="location">Location *</label>
                <input type="text" id="location" name="location" 
                       value="<?php echo isset($_POST['location']) ? e($_POST['location']) : ''; ?>" 
                       required
                       placeholder="e.g., Maharagama, Colombo">
                <small>Tip: add the closest landmark or university (e.g., USJ, Kelaniya, Moratuwa)</small>
            </div>
        </div>

        <div class="form-section">
            <h3>Property Details</h3>
            
            <div class="form-group">
                <label for="price">Monthly Rent (LKR) *</label>
                <input type="number" id="price" name="price" 
                       value="<?php echo isset($_POST['price']) ? e($_POST['price']) : ''; ?>" 
                       required min="1000" max="100000" step="500"
                       placeholder="5000">
                <small>Rent per month in Sri Lankan Rupees (Rs)</small>
            </div>

            <div class="form-group">
                <label for="conditions">Conditions & Rules *</label>
                <textarea id="conditions" name="conditions" rows="4" 
                          required minlength="20"
                          placeholder="Guests, pets, smoking, advance/deposit, key money, notice period, electricity/water sharing, curfew, etc."><?php echo isset($_POST['conditions']) ? e($_POST['conditions']) : ''; ?></textarea>
            </div>
        </div>

        <div class="form-section">
            <h3>Contact & Media</h3>
            
            <div class="form-group">
                <label for="phone_number">Phone Number *</label>
                <input type="tel" id="phone_number" name="phone_number" 
                       value="<?php echo isset($_POST['phone_number']) ? e($_POST['phone_number']) : ''; ?>" 
                       required
                       pattern="(0\d{9}|\+94\d{9})"
                       placeholder="e.g., 0771234567 or +94771234567">
                <small>Use a Sri Lankan number. Format: 0XXXXXXXXX or +94XXXXXXXXX</small>
            </div>

            <div class="form-group">
                <label for="photo">Photo URL (Optional)</label>
                <input type="url" id="photo" name="photo" 
                       value="<?php echo isset($_POST['photo']) ? e($_POST['photo']) : ''; ?>" 
                       placeholder="https://example.com/room.jpg">
                <small>Paste a direct link to a clear property photo.</small>
            </div>

            <?php if(isset($_POST['photo']) && !empty($_POST['photo'])): ?>
                <div class="photo-preview">
                    <h4>Photo Preview:</h4>
                    <img src="<?php echo e($_POST['photo']); ?>" alt="Preview" style="max-width: 200px; border-radius: 8px;" onerror="this.style.display='none'">
                </div>
            <?php endif; ?>
        </div>

        <div class="form-notice alert info">
            <h4>💰 Activation Fee</h4>
            <p>After submitting this form, you'll need to pay a <strong>one-time activation fee of Rs 1,000 (LKR)</strong> to make your gig visible to seekers. This helps us maintain platform quality.</p>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-large btn-full">POST A GIG</button>
            <a href="owner_dashboard.php" class="btn btn-large secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
// Character count for description
const descEl = document.getElementById('description');
const descCountEl = document.getElementById('descCount');
if (descEl && descCountEl) {
    const updateCount = () => { descCountEl.textContent = descEl.value.length; };
    descEl.addEventListener('input', updateCount);
    updateCount();
}

// Price rounding to the nearest 500 LKR
const priceEl = document.getElementById('price');
if (priceEl) {
    priceEl.addEventListener('blur', function() {
        const value = parseFloat(this.value);
        if(!isNaN(value)) {
            this.value = Math.round(value / 500) * 500; // Round to nearest 500
        }
    });
}

// Photo preview
const photoEl = document.getElementById('photo');
if (photoEl) {
    photoEl.addEventListener('blur', function() {
        const url = this.value.trim();
        if(url) {
            const existing = document.querySelector('.photo-preview');
            const preview = existing || createPreview();
            const img = preview.querySelector('img');
            img.src = url;
            img.style.display = 'block';
        }
    });
}

function createPreview() {
    const preview = document.createElement('div');
    preview.className = 'photo-preview';
    preview.innerHTML = '<h4>Photo Preview:</h4><img src="" alt="Preview" style="max-width: 200px; border-radius: 8px;" onerror="this.style.display=\'none\'">';
    document.getElementById('photo').parentNode.appendChild(preview);
    return preview;
}
</script>

<?php include 'footer.php'; ?>
