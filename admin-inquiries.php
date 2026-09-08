<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

requireAdmin();

$pdo = getDB();

// Handle delete inquiry
if(isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $stmt  = $pdo->prepare("
        DELETE FROM inquiries WHERE inquiry_id = ?
    ");
    $stmt->execute([$delId]);
    setFlash('success', 'Inquiry deleted successfully.');
    redirect('admin-inquiries.php');
}

// Fetch all inquiries
$inquiries = $pdo->query("
    SELECT i.*,
           p.title         AS property_title,
           p.location      AS property_location,
           p.property_type AS property_type,
           p.listing_type  AS listing_type,
           p.price         AS property_price,
           u.name          AS buyer_name,
           u.role          AS buyer_role
    FROM inquiries i
    LEFT JOIN properties p ON i.property_id = p.property_id
    LEFT JOIN users u      ON i.user_id = u.user_id
    ORDER BY i.created_at DESC
")->fetchAll();

$totalInquiries = count($inquiries);

// This month count
$thisMonth      = date('Y-m');
$thisMonthCount = 0;
foreach($inquiries as $inq) {
    if(strpos($inq['created_at'], $thisMonth) === 0) {
        $thisMonthCount++;
    }
}

// Guest inquiries count
$guestCount = 0;
foreach($inquiries as $inq) {
    if(empty($inq['user_id'])) $guestCount++;
}
?>
<?php require_once 'includes/header.php'; ?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span class="breadcrumb-sep">›</span>
            <a href="dashboard.php">Dashboard</a>
            <span class="breadcrumb-sep">›</span>
            <span>All Inquiries</span>
        </div>
        <h1>All Inquiries</h1>
        <p>View and manage all property inquiries submitted on the platform</p>
    </div>
</section>

<div class="container page-padding">

    <!-- Stats -->
    <div class="stats-grid" style="margin-bottom:32px;">
        <div class="stat-card">
            <div class="stat-icon">📩</div>
            <div class="stat-number"><?php echo $totalInquiries; ?></div>
            <div class="stat-label">Total Inquiries</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-number"><?php echo $thisMonthCount; ?></div>
            <div class="stat-label">This Month</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👤</div>
            <div class="stat-number">
                <?php echo $totalInquiries - $guestCount; ?>
            </div>
            <div class="stat-label">From Members</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🌐</div>
            <div class="stat-number"><?php echo $guestCount; ?></div>
            <div class="stat-label">From Guests</div>
        </div>
    </div>

    <!-- Inquiries Table -->
    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">
                📩 All Inquiries
            </h3>
        </div>

        <?php if(empty($inquiries)): ?>
            <div class="empty-state">
                <div class="empty-icon">📩</div>
                <h3>No Inquiries Yet</h3>
                <p>No inquiries have been submitted on the platform.</p>
            </div>
        <?php else: ?>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>From</th>
                            <th>Property</th>
                            <th>Message</th>
                            <th>User Type</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($inquiries as $i => $inq): ?>
                        <tr>
                            <!-- # -->
                            <td style="color:#718096;font-size:.85rem;">
                                <?php echo $i + 1; ?>
                            </td>

                            <!-- From -->
                            <td>
                                <strong style="color:#2d3748;">
                                    <?php echo e($inq['name']); ?>
                                </strong>
                                <div style="font-size:.8rem;
                                            color:#718096;margin-top:2px;">
                                    ✉️ <?php echo e($inq['email']); ?>
                                </div>
                                <?php if(!empty($inq['phone'])): ?>
                                <div style="font-size:.8rem;color:#1a6fc4;">
                                    📞 <?php echo e($inq['phone']); ?>
                                </div>
                                <?php endif; ?>
                            </td>

                            <!-- Property -->
                            <td>
                                <strong style="color:#2d3748;">
                                    <?php echo e($inq['property_title'] ?? 'Deleted Property'); ?>
                                </strong>
                                <div style="font-size:.8rem;color:#718096;margin-top:2px;">
                                    📍 <?php echo e($inq['property_location'] ?? ''); ?>
                                </div>
                                <?php if(!empty($inq['property_price'])): ?>
                                <div style="font-size:.82rem;font-weight:700;color:#1a6fc4;">
                                    <?php echo formatPrice(
                                        (float)$inq['property_price'],
                                        $inq['listing_type'] ?? 'Sale'
                                    ); ?>
                                </div>
                                <?php endif; ?>
                            </td>

                            <!-- Message -->
                            <td style="max-width:250px;">
                                <div style="font-size:.85rem;
                                            color:#4a5568;line-height:1.5;">
                                    <?php
                                    $msg = e($inq['message']);
                                    echo strlen($msg) > 80
                                        ? substr($msg, 0, 80) . '...'
                                        : $msg;
                                    ?>
                                </div>
                            </td>

                            <!-- User Type -->
                            <td>
                                <?php if(!empty($inq['user_id'])): ?>
                                <span class="role-badge role-<?php echo $inq['buyer_role'] ?? 'buyer'; ?>">
                                    👤 <?php echo ucfirst($inq['buyer_role'] ?? 'Member'); ?>
                                </span>
                                <?php else: ?>
                                <span class="badge badge-warning">
                                    🌐 Guest
                                </span>
                                <?php endif; ?>
                            </td>

                            <!-- Date -->
                            <td style="font-size:.82rem;
                                       color:#718096;white-space:nowrap;">
                                <?php echo date('d M Y', strtotime($inq['created_at'])); ?>
                                <div style="font-size:.78rem;">
                                    <?php echo date('h:i A', strtotime($inq['created_at'])); ?>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td>
                                <div style="display:flex;gap:6px;">
                                    <?php if(!empty($inq['property_id'])): ?>
                                    <a href="property-details.php?id=<?php echo (int)$inq['property_id']; ?>"
                                       class="btn btn-sm btn-outline">
                                        View
                                    </a>
                                    <?php endif; ?>
                                    <a href="admin-inquiries.php?delete=<?php echo (int)$inq['inquiry_id']; ?>"
                                       class="btn btn-sm btn-danger"
                                       data-confirm="Delete this inquiry from <?php echo e($inq['name']); ?>?">
                                        🗑️
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- Full Message -->
                        <tr style="background:#f7fafc;">
                            <td colspan="7"
                                style="padding:10px 16px;
                                       font-size:.82rem;color:#4a5568;
                                       border-bottom:2px solid #e2e8f0;">
                                <strong>Full Message:</strong>
                                <?php echo e($inq['message']); ?>
                            </td>
                        </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>