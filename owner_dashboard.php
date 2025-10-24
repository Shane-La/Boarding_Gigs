<?php 
include 'config.php'; 
require_owner(); 
include 'headers.php'; 

$user_id = $_SESSION['user']['id'];
?>

<div class="dashboard-header">
    <h2>Owner Dashboard</h2>
    <p>Manage your boarding gigs and track your listings</p>
</div>

<?php
// Get dashboard statistics
$stats = [];
$stats_stmt = $conn->prepare("
    SELECT 
        COUNT(*) as total_gigs,
        SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_gigs,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_gigs
    FROM gigs 
    WHERE user_id = ?
");
$stats_stmt->bind_param('i', $user_id);
$stats_stmt->execute();
$stats_result = $stats_stmt->get_result();
$stats = $stats_result->fetch_assoc();
$stats_stmt->close();
?>

<!-- Dashboard Stats -->
<div class="dashboard-stats">
    <div class="stat-card">
        <span class="stat-number"><?php echo $stats['total_gigs']; ?></span>
        <span class="stat-label">Total Gigs</span>
    </div>
    <div class="stat-card">
        <span class="stat-number"><?php echo $stats['active_gigs']; ?></span>
        <span class="stat-label">Active Gigs</span>
    </div>
    <div class="stat-card">
        <span class="stat-number"><?php echo $stats['pending_gigs']; ?></span>
        <span class="stat-label">pending Activation</span>
    </div>
</div>

<div class="dashboard-actions mb-3">
    <a href="post_gig.php" class="btn btn-large">+ Post New Gig</a>
</div>

<!-- Gigs Management -->
<div class="dashboard-section">
    <h3>Your Gigs</h3>
    
    <?php
    $gigs_stmt = $conn->prepare("
        SELECT id, title, description, location, price, status, created_at 
        FROM gigs 
        WHERE user_id = ? 
        ORDER BY 
            CASE 
                WHEN status = 'pending' THEN 1
                WHEN status = 'active' THEN 2
                ELSE 3
            END,
            created_at DESC
    ");
    $gigs_stmt->bind_param('i', $user_id);
    $gigs_stmt->execute();
    $gigs_result = $gigs_stmt->get_result();
    ?>

    <?php if($gigs_result && $gigs_result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($gig = $gigs_result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <strong><?php echo e($gig['title']); ?></strong>
                                <div class="small-text"><?php echo e(mb_substr($gig['description'], 0, 50)); ?>...</div>
                            </td>
                            <td><?php echo e($gig['location']); ?></td>
                            <td>Rs.<?php echo e($gig['price']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo e($gig['status']); ?>">
                                    <?php echo ucfirst($gig['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($gig['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                     <?php if($gig['status'] === 'pending'): ?>
                                    <a class="btn btn-small" href="payment.php?id=<?= $gig['id']; ?>"title="Activate Gig">
                                        💳Active
                                    </a>
                                    <?php else: ?>
                                        <a href="gig.php?id=<?php echo $gig['id']; ?>" 
                                           class="btn btn-small secondary" 
                                           title="View Gig">
                                            👁️ View
                                        </a>
                                    <?php endif; ?>
                                    <button class="btn btn-small warning" 
                                            onclick="editGig(<?php echo $gig['id']; ?>)" 
                                            title="Edit Gig">
                                        ✏️ Edit
                                    </button>
                                </div>
                                 <?php
    
$res = $conn->query("SELECT * FROM gigs ORDER BY created_at DESC");
$gigs = [];
while($r = $res->fetch_assoc()) $gigs[] = $r;
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Gigs Dashboard</title>
</head>
<body>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert info text-center">
            <h4>No Gigs Yet</h4>
            <p>You haven't posted any boarding gigs yet. Start by creating your first gig!</p>
            <a href="post_gig.php" class="btn mt-1">Post Your First Gig</a>
        </div>
    <?php endif; ?>
    
    <?php $gigs_stmt->close(); ?>
</div>

<!-- Quick Tips -->
<div class="dashboard-section">
    <h3>Owner Tips</h3>
    <div class="tips-grid">
        <div class="tip-card">
            <h4>💰 Pricing Strategy</h4>
            <p>Research similar properties in your area to set competitive prices. Consider amenities and location.</p>
        </div>
        <div class="tip-card">
            <h4>📸 Quality Photos</h4>
            <p>Clear, well-lit photos can increase engagement by up to 200%. Show all rooms and amenities.</p>
        </div>
        <div class="tip-card">
            <h4>📝 Detailed Descriptions</h4>
            <p>Be honest about conditions, rules, and amenities. Clear communication builds trust.</p>
        </div>
        <div class="tip-card">
            <h4>⚡ Quick Responses</h4>
            <p>Respond to inquiries within 24 hours. Fast responses increase booking chances by 30%.</p>
        </div>
    </div>
</div>

<script>
function editGig(gigId) {
    if(confirm('Edit functionality coming soon! For now, you can repost the gig with updated information.')) { 
        // Future edit functionality
    }
}
</script>

<?php include 'footer.php'; ?>
