<?php 
include 'config.php'; 
include 'headers.php'; 
?>

<style>
/* === DARK THEME BACKGROUND === */
body {
    margin: 0;
    padding: 0;
    background: url('WhatsApp Image 2025-10-19 at 16.34.21_9a7a2344.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Poppins', sans-serif;
    color: #fff;
}

/* === PAGE HEADER === */
.page-header {
    text-align: center;
    margin: 100px auto 40px;
    padding: 20px;
    background: rgba(0, 0, 0, 0.65);
    border-radius: 15px;
    width: 90%;
    max-width: 1000px;
}
.page-header h2 {
    font-size: 38px;
    color: #ffd700;
    margin-bottom: 10px;
}
.page-header p {
    font-size: 16px;
    color: #ddd;
}

/* === GIGS GRID === */
.gigs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    width: 90%;
    margin: 0 auto 60px;
}

/* === GIG CARD === */
.gig-card {
    background: rgba(255, 255, 255, 0.08);
    border-radius: 15px;
    overflow: hidden;
    backdrop-filter: blur(8px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.gig-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 25px rgba(255,215,0,0.4);
}

/* === IMAGE === */
.gig-image {
    position: relative;
}
.gig-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}
/* Price text only — no yellow box */
.gig-price {
    position: absolute;
    bottom: 10px;
    right: 10px;
    color: #fff;
    font-weight: 600;
    font-size: 15px;
    text-shadow: 0 0 6px rgba(0,0,0,0.8);
    background: none;  /* just transparent, no override for navbar */
    padding: 0;
    border: none;
}

/* === CONTENT === */
.gig-content {
    padding: 15px 20px;
}
.gig-content h4 a {
    color: #fff;
    text-decoration: none;
}
.gig-content h4 a:hover {
    color: #ffd700;
}
.gig-description {
    font-size: 14px;
    color: #ccc;
    margin: 10px 0;
    line-height: 1.5;
}

/* === META INFO === */
.gig-meta {
    font-size: 13px;
    color: #aaa;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    margin-bottom: 10px;
}
.meta-item span { margin-right: 5px; }

/* === BUTTONS === */
.btn {
    display: inline-block;
    background: #ffd700;
    color: #000;
    padding: 8px 14px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: background 0.3s ease;
}
.btn:hover { background: #fff000; }
.btn.secondary {
    background: transparent;
    color: #ffd700;
    border: 1px solid #ffd700;
}
.btn.secondary:hover { background: #ffd700; color: #000; }
.btn-small { padding: 6px 10px; font-size: 13px; }

/* === ALERT === */
.alert {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 25px;
    margin: 60px auto;
    width: 80%;
    text-align: center;
    color: #fff;
}
.alert h4 { color: #ffd700; margin-bottom: 10px; }
.alert a { color: #ffd700; text-decoration: underline; }
</style>

<div class="page-header">
    <h2>Available Boarding Gigs</h2>
    <p>Discover perfect living spaces from verified owners</p>
</div>

<?php
// Fetch all active gigs
$query = "
    SELECT g.id, g.title, g.description, g.location, g.price, g.photo, g.created_at, u.username
    FROM gigs g 
    JOIN users u ON g.user_id = u.id 
    WHERE g.status = 'active'
    ORDER BY g.created_at DESC
";
$result = $conn->query($query);
?>

<!-- Results Count -->
<div class="results-info" style="text-align:center; margin-bottom:25px;">
<?php 
$total_gigs = $result ? $result->num_rows : 0;
echo "<p style='color:blue;'>Found <strong style='color:blue;'>$total_gigs</strong> boarding gig" . ($total_gigs !== 1 ? 's' : '') . "</p>";
?>
</div>

<!-- Gigs Grid -->
<?php if($result && $result->num_rows > 0): ?>
    <div class="gigs-grid">
        <?php while($gig = $result->fetch_assoc()): ?>
            <div class="gig-card">
                <div class="gig-image">
                    <?php if(!empty($gig['photo'])): ?>
                        <img src="<?php echo htmlspecialchars($gig['photo']); ?>" 
                             alt="<?php echo htmlspecialchars($gig['title']); ?>" 
                             onerror="this.src='https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=500&q=80'">
                    <?php else: ?>
                        <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=500&q=80" 
                             alt="Default boarding image">
                    <?php endif; ?>
                    <div class="gig-price">Rs <?php echo number_format((float)$gig['price'], 2); ?>/month</div>
                </div>

                <div class="gig-content">
                    <h4>
                        <a href="gig.php?id=<?php echo (int)$gig['id']; ?>">
                            <?php echo htmlspecialchars($gig['title']); ?>
                        </a>
                    </h4>

                    <p class="gig-description">
                        <?php 
                        $desc = (string)$gig['description'];
                        echo htmlspecialchars(mb_substr($desc, 0, 150));
                        if (mb_strlen($desc) > 150) echo '...';
                        ?>
                    </p>

                    <div class="gig-meta">
                        <div class="meta-item">📍 <?php echo htmlspecialchars($gig['location']); ?></div>
                        <div class="meta-item">👤 <?php echo htmlspecialchars($gig['username']); ?></div>
                        <div class="meta-item">📅 <?php echo date('M j, Y', strtotime($gig['created_at'])); ?></div>
                    </div>

                    <div class="gig-actions">
                        <a href="gig.php?id=<?php echo (int)$gig['id']; ?>" class="btn btn-small">View Details</a>
                        <?php if(function_exists('current_user') && current_user() && current_user()['user_type'] === 'seeker'): ?>
                            <button class="btn btn-small secondary">Save</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="alert">
        <h4>No gigs found</h4>
        <p>There are no active boarding listings at the moment. Please check back later!</p>
        <?php if(function_exists('current_user') && current_user() && current_user()['user_type'] === 'owner'): ?>
            <a href="post_gig.php" class="btn mt-1">Post the First Gig</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>
