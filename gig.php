<?php include 'config.php'; include 'headers.php'; ?>

<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("
    SELECT g.*, u.username, u.email as owner_email
    FROM gigs g 
    JOIN users u ON g.user_id = u.id
    WHERE g.id = ? AND g.status = 'active' 
    LIMIT 1
");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$gig = $result ? $result->fetch_assoc() : null;
?>

<?php if($gig): ?>
    <div class="gig-detail">
        <!-- Gig Images -->
        <div class="gig-gallery mb-3">
            <?php if(!empty($gig['photo'])): ?>
                <div class="main-image">
                    <img src="<?php echo e($gig['photo']); ?>" alt="<?php echo e($gig['title']); ?>" 
                         onerror="this.src='https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'">
                </div>
            <?php else: ?>
                <div class="main-image">
                    <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Default boarding image">
                </div>
            <?php endif; ?>
        </div>

        <div class="gig-layout">
            <!-- Main Content -->
            <div class="gig-main">
                <div class="gig-header mb-2">
                    <h1><?php echo e($gig['title']); ?></h1>
                    <div class="gig-meta-large">
                        <div class="meta-item">
                            <span class="icon">📍</span>
                            <span class="text"><?php echo e($gig['location']); ?></span>
                        </div>
                        <div class="meta-item">
                            <span class="icon">👤</span>
                            <span class="text"><?php echo e($gig['username']); ?></span>
                        </div>
                        <div class="meta-item">
                            <span class="icon">📅</span>
                            <span class="text">Posted <?php echo date('F j, Y', strtotime($gig['created_at'])); ?></span>
                        </div>
                    </div>
                </div>

                <div class="price-tag large">
                    <span class="amount">Rs.<?php echo e($gig['price']); ?></span>
                    <span class="period">per month</span>
                </div>

                <div class="gig-section">
                    <h3>Description</h3>
                    <div class="gig-description">
                        <?php echo nl2br(e($gig['description'])); ?>
                    </div>
                </div>

                <div class="gig-section">
                    <h3>Conditions & Rules</h3>
                    <div class="gig-conditions">
                        <?php echo nl2br(e($gig['conditions'])); ?>
                    </div>
                </div>

                <?php if(!empty($gig['phone_number'])): ?>
                    <div class="gig-section">
                        <h3>Contact Information</h3>
                        <div class="contact-info">
                            <p><strong>Phone:</strong> <?php echo e($gig['phone_number']); ?></p>
                            <?php if(current_user()): ?>
                                <p><strong>Email:</strong> <?php echo e($gig['owner_email']); ?></p>
                            <?php else: ?>
                                <p><a href="login.php">Login to view contact email</a></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="gig-sidebar">
                <div class="sidebar-card">
                    <div class="price-display">
                        <div class="price-amount">Rs.<?php echo e($gig['price']); ?></div>
                        <div class="price-period">per month</div>
                    </div>

                    <div class="action-buttons">
                        <?php if(current_user()): ?>
                            <?php if(current_user()['user_type'] === 'seeker'): ?>
                                <button class="btn btn-full mb-1" onclick="contactOwner()">📞 Contact Owner</button>
                                <button class="btn btn-full secondary">❤️ Save Gig</button>
                            <?php elseif(current_user()['id'] == $gig['user_id']): ?>
                                <p class="text-center">This is your gig</p>
                                <a href="owner_dashboard.php" class="btn btn-full">Manage Gig</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-full">Login to Contact Owner</a>
                            <a href="register.php" class="btn btn-full secondary">Create Account</a>
                        <?php endif; ?>
                    </div>

                    <div class="owner-info">
                        <h4>Listed by</h4>
                        <div class="owner-details">
                            <div class="owner-name"><?php echo e($gig['username']); ?></div>
                            <div class="member-since">
                                Member since <?php echo date('Y', strtotime($gig['created_at'])); ?>
                            </div>
                        </div>
                    </div>

                    <div class="safety-tips">
                        <h4>💡 Safety Tips</h4>
                        <ul>
                            <li>Meet in public places first</li>
                            <li>Verify the property details</li>
                            <li>Never pay advance without contract</li>
                            <li>Trust your instincts</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function contactOwner() {
        alert('Contact the owner at: <?php echo e($gig['phone_number']); ?>');
    }
    </script>

<?php else: ?>
    <div class="alert error text-center">
        <h3>Gig Not Found</h3>
        <p>The gig you're looking for doesn't exist or is no longer active.</p>
        <a href="view_gigs.php" class="btn mt-1">Browse Available Gigs</a>
    </div>
<?php endif; ?>

<?php 
$stmt->close();
include 'footer.php'; 
?>