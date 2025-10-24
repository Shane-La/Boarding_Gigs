<?php
include 'config.php';
include 'headers.php';

// Validate gig ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: owner_dashboard.php');
    exit;
}

$id = intval($_GET['id']);

// Fetch gig info
$stmt = $conn->prepare("SELECT * FROM gigs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Gig not found.";
    exit;
}

$gig = $result->fetch_assoc();
$stmt->close();

// Default activation price
$activation_fee = 1000.00;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Pay & Activate - <?= htmlspecialchars($gig['title']) ?></title>
</head>
<body>
<div class="container">
  <h2>Activate: <?= htmlspecialchars($gig['title']) ?></h2>
  <div class="card">
    <form action="process_payment.php" method="POST" onsubmit="return confirmPayment()">
      <input type="hidden" name="gig_id" value="<?= htmlspecialchars($gig['id']) ?>">
      <input type="hidden" name="amount" value="<?= $activation_fee ?>">

      <label>Name on Card *</label>
      <input type="text" name="name" required placeholder="John Doe">

      <label>Card Number *</label>
      <input type="text" name="card_number" id="card_number" required pattern="[0-9\s]{13,17}" placeholder="1234 5678 9012 3456">

      <label>Expiry Date *</label>
      <input type="text" name="expiry_date" id="expiry_date" required pattern="(0[1-9]|1[0-2])\/[0-9]{2}" placeholder="MM/YY">

      <label>CVV *</label>
      <input type="password" name="cvv" required placeholder="123">

      <div style="margin-top:16px;">
        <button class="btn" type="submit">
          Pay Rs.<?= number_format($activation_fee, 2) ?> & Activate Gig
        </button>
        <a href="owner_dashboard.php" style="margin-left:10px;" class="btn secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
function confirmPayment() {
    return confirm('Proceed with mock payment of රු<?= number_format($activation_fee, 2) ?>? This is a demonstration only – no real payment will be processed.');
}

// Format card number
document.getElementById('card_number').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    let formatted = value.match(/.{1,4}/g);
    e.target.value = formatted ? formatted.join(' ') : value;
});

// Format expiry date
document.getElementById('expiry_date').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 2) {
        e.target.value = value.substring(0, 2) + '/' + value.substring(2, 4);
    } else {
        e.target.value = value;
    }
});
</script>

<?php include 'footer.php'; ?>
</body>
</html>
