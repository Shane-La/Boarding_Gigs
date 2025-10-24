<?php  
include 'config.php';  
include 'headers.php';  
?> 

<style>
/* ===== BACKGROUND IMAGE =====
   Save your uploaded image as: assets/img/download.jpg
   or adjust the path below */
body {
    margin: 0;
    padding: 0;
    background:
        linear-gradient(0deg, rgba(0,0,0,.25), rgba(0,0,0,.25)),
        url('header-bg.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Poppins', sans-serif;
    color: #fff;
}

/* ===== NAVBAR ===== */
.navbar, header .navbar {
    background: rgba(0,0,0,0.35);
    backdrop-filter: blur(5px);
    box-shadow: 0 2px 10px rgba(0,0,0,0.25);
}
.navbar .nav-container { 
    max-width: 950px; 
    margin: 0 auto; 
    padding: 6px 10px; 
}
.navbar h2 { font-size: 18px; margin: 0; color: #ffd700; }
.navbar .nav-links a, .navbar .btn {
    padding: 6px 10px;
    border-radius: 16px;
    font-size: 13px;
    margin-left: 6px;
}

/* ===== UNIVERSAL BOX CONTAINERS ===== */
section, .hero-section, .description-box, .features-grid, .note, .gigs-grid {
    background: rgba(0,0,0,0.25);
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.1);
    padding: 10px 12px;
    margin: 12px auto;
    width: 68%;          /* narrow width for more background */
    max-width: 820px;
    backdrop-filter: blur(3px);
    box-shadow: 0 1px 8px rgba(0,0,0,0.22);
}

/* ===== Responsive layout ===== */
@media (max-width: 768px) {
    section, .hero-section, .description-box, .features-grid, .note, .gigs-grid {
        width: 90%;
        padding: 12px;
    }
}

/* ===== HERO SECTION ===== */
.hero-section {
    text-align: center;
    padding: 45px 20px;
}
.hero-section h1 {
    font-size: 34px;
    font-weight: 700;
    color: #ffd700;
    margin-bottom: 8px;
}
.hero-section p {
    font-size: 15.5px;
    color: #f5f5f5;
    margin-bottom: 18px;
}
.hero-section .cta-buttons a {
    text-decoration: none;
    padding: 7px 14px;
    border-radius: 20px;
    margin: 0 6px;
    font-weight: 600;
    font-size: 13.5px;
    transition: 0.25s;
}
.btn.btn-large { background: #ffd700; color: #000; }
.btn.btn-large:hover { background: #fff; color: #000; }
.btn.secondary {
    border: 1px solid #ffd700;
    background: transparent;
    color: #ffd700;
}
.btn.secondary:hover { background: #ffd700; color: #000; }

/* ===== DESCRIPTION ===== */
.description-box {
    text-align: center;
    line-height: 1.55;
    font-size: 15.5px;
    background: rgba(0,0,0,0.2);
    padding: 10px 12px;
    border-radius: 8px;
}
.description-box strong { color: #ffd700; }

/* ===== FEATURES ===== */
.features-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
    background: rgba(0,0,0,0.2);
}
.feature-card {
    background: rgba(0,0,0,0.45);
    padding: 12px;
    border-radius: 12px;
    flex: 1 1 240px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.22);
}
.feature-card h3 {
    color: #ffd700;
    margin-bottom: 6px;
    font-size: 17px;
}
.feature-card p {
    color: #f5f5f5;
    font-size: 13.5px;
}

/* ===== NOTE ===== */
.note {
    background: rgba(255,215,0,0.06);
    border-left: 3px solid #ffd700;
    color: #fff;
    border-radius: 8px;
    padding: 8px 10px;
    font-size: 13.5px;
}

/* ===== GIGS GRID ===== */
.gigs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 10px;
    background: rgba(0,0,0,0.2);
}
.gig-card {
    background: rgba(0,0,0,0.45);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.25);
}
.gig-card .gig-image img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}
.gig-card .gig-content {
    padding: 10px;
}
.gig-card h4 {
    margin: 0 0 6px;
    font-size: 15.5px;
}
.gig-card h4 a {
    color: #ffd700;
    text-decoration: none;
}
.gig-card p {
    color: #f5f5f5;
    font-size: 13.5px;
    margin: 0 0 8px;
}
.gig-meta {
    font-size: 12px;
    color: #ccc;
}
.gig-meta .price {
    color: #ffd700;
    font-weight: 700;
}
.btn.btn-small {
    font-size: 12px;
    padding: 6px 12px;
    border-radius: 18px;
}
</style>

<!-- === HERO SECTION === -->
<div class="hero-section text-center mb-3"> 
    <h1>Find Your Perfect Boarding Space</h1> 
    <p class="lead mb-2">
        Connect with verified boarding owners and discover affordable, comfortable living spaces tailored to your needs.
    </p> 
    <div class="cta-buttons"> 
        <a href="view_gigs.php" class="btn btn-large">Browse Available Gigs</a> 
        <?php if(current_user() && current_user()['user_type'] === 'owner'): ?> 
            <a href="post_gig.php" class="btn btn-large secondary">Post a New Gig</a> 
        <?php elseif(!current_user()): ?> 
            <a href="register.php" class="btn btn-large secondary">Join as Owner</a> 
        <?php endif; ?> 
    </div> 
</div> 

<!-- === DESCRIPTION === -->
<div class="description-box"> 
    <p >
        The <strong>Boarding Gig System</strong> helps <strong>boarding seekers</strong> find suitable boarding places
        quickly and easily. It lets users view <strong>available rooms, prices, and facilities</strong> online
        and helps <strong>boarding owners</strong> manage their listings efficiently.
    </p>
</div>

<!-- === FEATURES GRID === -->
<div class="features-grid mb-3"> 
    <div class="feature-card"> 
        <h3>🏠 For Seekers</h3> 
        <p>Find verified options with transparent pricing and real photos. Message owners directly and book with confidence.</p> 
    </div> 
    <div class="feature-card"> 
        <h3>💰 For Owners</h3> 
        <p>Reach thousands of potential tenants. Our one-time activation fee ensures your listing gets visibility.</p> 
    </div> 
    <div class="feature-card"> 
        <h3>🔒 Secure Platform</h3> 
        <p>Verified users, safe payments, and support to make your boarding experience smooth and worry-free.</p> 
    </div> 
</div> 

<!-- === NOTE BOX === -->
<div class="note alert info">
    <strong>Note for Owners:</strong> Pay a one-time posting fee of Rs. 1000 to activate each gig. Seekers can browse and contact owners for free.
</div>

<!-- === GIGS LIST === -->
<h2 class="text-center" style="color:#ffd700;">Latest Active Gigs</h2> 

<?php 
$res = $conn->query("
    SELECT g.id, g.title, g.description, g.location, g.price, g.created_at, g.photo, u.username 
    FROM gigs g 
    JOIN users u ON g.user_id = u.id 
    WHERE g.status = 'active' 
    ORDER BY g.created_at DESC 
    LIMIT 6
");
if ($res && $res->num_rows > 0): ?> 
    <div class="gigs-grid"> 
        <?php while($g = $res->fetch_assoc()): ?> 
            <div class="gig-card"> 
                <?php if(!empty($g['photo'])): ?> 
                    <div class="gig-image"> 
                        <img src="<?php echo e($g['photo']); ?>" alt="<?php echo e($g['title']); ?>" onerror="this.style.display='none'"> 
                    </div> 
                <?php endif; ?> 
                <div class="gig-content"> 
                    <h4><a href="gig.php?id=<?php echo $g['id']; ?>"><?php echo e($g['title']); ?></a></h4> 
                    <p><?php echo e(mb_substr($g['description'], 0, 120)); ?>...</p> 
                    <div class="gig-meta"> 
                        <span class="meta-item">👤 <?php echo e($g['username']); ?></span> | 
                        <span class="meta-item">📍 <?php echo e($g['location']); ?></span> | 
                        <span class="meta-item price">Rs. <?php echo e($g['price']); ?>/month</span> 
                    </div> 
                    <a href="gig.php?id=<?php echo $g['id']; ?>" class="btn btn-small" style="margin-top:8px; display:inline-block; background:#ffd700; color:#000;">View Details</a> 
                </div> 
            </div> 
        <?php endwhile; ?> 
    </div> 
    <div class="text-center" style="margin:16px 0 26px;">
        <a href="view_gigs.php" class="btn" style="background:#ffd700; color:#000; padding:8px 16px; border-radius:20px; font-size:14px;">View All Gigs</a>
    </div>
<?php else: ?> 
    <div class="alert warning text-center">
        <p>No active gigs available at the moment.</p>
        <?php if(current_user() && current_user()['user_type'] === 'owner'): ?> 
            <a href="post_gig.php" class="btn mt-1" style="background:#ffd700; color:#000; padding:7px 14px; border-radius:18px; font-size:13px;">Be the first to post a gig!</a> 
        <?php endif; ?> 
    </div> 
<?php endif; ?> 

<?php include 'footer.php'; ?> 
