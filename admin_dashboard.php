<?php include 'config.php'; include 'headers.php'; ?>
<?php
$user = current_user();
if(!$user || $user['user_type']!=='admin'){ 
    echo '<div class="alert error">Admins only.</div>'; 
    include 'footer.php'; 
    exit; 
}

// Get statistics
$users_count = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$gigs_count = $conn->query("SELECT COUNT(*) as count FROM gigs")->fetch_assoc()['count'];
$active_gigs_count = $conn->query("SELECT COUNT(*) as count FROM gigs WHERE status='active'")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(amount) as total FROM payments")->fetch_assoc()['total'] ?? 0;
?>

<h2>Admin Dashboard</h2>

<div class="dashboard-stats">
    <div class="stat-card">
        <span class="stat-number"><?php echo $users_count; ?></span>
        <span class="stat-label">Total Users</span>
    </div>
    <div class="stat-card">
        <span class="stat-number"><?php echo $gigs_count; ?></span>
        <span class="stat-label">Total Gigs</span>
    </div>
    <div class="stat-card">
        <span class="stat-number"><?php echo $active_gigs_count; ?></span>
        <span class="stat-label">Active Gigs</span>
    </div>
    <div class="stat-card">
        <span class="stat-number">Rs.<?php echo number_format($total_revenue, 2); ?></span>
        <span class="stat-label">Total Revenue</span>
    </div>
</div>

<div class="dashboard-section">
    <h3>Users</h3>
    <?php
    $users_result = $conn->query("SELECT id, username, email, user_type, created_at FROM users ORDER BY created_at DESC");
    if($users_result && $users_result->num_rows > 0): 
    ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $users_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo e($row['username']); ?></td>
                        <td><?php echo e($row['email']); ?></td>
                        <td><?php echo e($row['user_type']); ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert info">No users found.</div>
    <?php endif; ?>
</div>

<div class="dashboard-section">
    <h3>Gigs</h3>
    <?php
    $gigs_result = $conn->query("SELECT g.id, g.title, g.status, g.created_at, u.username FROM gigs g JOIN users u ON g.user_id=u.id ORDER BY g.created_at DESC");
    if($gigs_result && $gigs_result->num_rows > 0): 
    ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Owner</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $gigs_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo e($row['title']); ?></td>
                        <td><?php echo e($row['username']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo e($row['status']); ?>">
                                <?php echo e($row['status']); ?>
                            </span>
                        </td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert info">No gigs found.</div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>