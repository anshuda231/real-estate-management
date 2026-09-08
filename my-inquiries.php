<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

requireLogin();

$pdo    = getDB();
$userId = (int)$_SESSION['user_id'];
$role   = $_SESSION['user_role'];

// Fetch inquiries based on role
if($role === 'seller' || $role === 'admin') {

    // Seller — show inquiries received on their properties
    if($role === 'admin') {
        // Admin sees all inquiries
        $stmt = $pdo->prepare("
            SELECT i.*,
                   p.title     AS property_title,
                   p.location  AS property_location,
                   p.property_type,
                   p.listing_type,
                   p.price
            FROM inquiries i
            LEFT JOIN properties p ON i.property_id = p.property_id
            ORDER BY i.created_at DESC
        ");
        $stmt->execute();
    } else {
        // Seller sees only their property inquiries
        $stmt = $pdo->prepare("
            SELECT i.*,
                   p.title     AS property_title,
                   p.location  AS property_location,
                   p.property_type,
                   p.listing_type,
                   p.price
            FROM inquiries i
            JOIN properties p ON i.property_id = p.property_id
            WHERE p.user_id = ?
            ORDER BY i.created_at DESC
        ");
        $stmt->execute([$userId]);
    }

    $inquiries    = $stmt->fetchAll();
    $pageTitle    = $role === 'admin' ? 'All Inquiries' : 'Received Inquiries';
    $pageSubtitle = $role === 'admin'
        ? 'All inquiries submitted on the platform'
        : 'Inquiries received on your property listings';

} else {

    // Buyer — show their own submitted inquiries
    $stmt = $pdo->prepare("
        SELECT i.*,
               p.title     AS property_title,
               p.location  AS property_location,
               p.property_type,
               p.listing_type,
               p.price,
               p.property_id AS prop_id
        FROM inquiries i
        LEFT JOIN properties p ON i.property_id = p.property_id
        WHERE i.user_id = ?
        ORDER BY i.created_at DESC
    ");
    $stmt->execute([$userId]);
    $inquiries    = $stmt->fetchAll();
    $pageTitle    = 'My Inquiries';
    $pageSubtitle = 'Inquiries you have submitted for properties';
}

$totalInquiries = count($inquiries);
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
            <span><?php echo $pageTitle; ?></span>
        </div>
        <h1><?php echo $pageTitle; ?></h1>
        <p><?php echo $pageSubtitle; ?></p>
    </div>
</section>

<div class="container page-padding">

    <!-- Stats -->
    <div class="stats-grid" style="margin-bottom:32px;">
        <div class="stat-card">
            <div class="stat-icon">📩</div>
            <div class="stat-number"><?php echo $totalInquiries; ?></div>
            <div class="stat-label">
                Total <?php echo $role === 'buyer' ? 'Submitted' : 'Received'; ?>
            </div>
        </div>
        <?php if($role !== 'buyer'): ?>
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-number">
                <?php
                // Count this month's inquiries
                $thisMonth = 0;
                $currentMonth = date('Y-m');
                foreach($inquiries as $inq) {
                    if(strpos($inq['created_at'], $currentMonth) === 0) {
                        $thisMonth++;
                    }
                }
                echo $thisMonth;
                ?>
            </div>
            <div class="stat-label">This Month</div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Inquiries Table -->
    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">
                📩 <?php echo $pageTitle; ?>
            </h3>
            <?php if($role === 'buyer'): ?>
            <a href="properties.php"
               class="btn btn-primary btn-sm">
                🔍 Browse Properties
            </a>
            <?php endif; ?>
        </div>

        <?php if(empty($inquiries)): ?>
            <div class="empty-state">
                <div class="empty-icon">📩</div>
                <h3>No Inquiries Found</h3>
                <?php if($role === 'buyer'): ?>
                <p>
                    You have not submitted any inquiries yet.
                    Browse properties and send your first inquiry.
                </p>
                <a href="properties.php"
                   class="btn btn-primary">
                    Browse Properties
                </a>
                <?php else: ?>
                <p>
                    You have not received any inquiries yet.
                    Make sure your listings are active and visible.
                </p>
                <a href="my-properties.php"
                   class="btn btn-primary">
                    View My Properties
                </a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <?php if($role !== 'buyer'): ?>
                            <th>From</th>
                            <th>Contact</th>
                            <?php endif; ?>
                            <th>Property</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($inquiries as $i => $inq): ?>
                        <tr>
                            <!-- # -->
                            <td style="color:#718096;font-size:.85rem;">
                                <?php echo $i + 1; ?>
                            </td>

                            <!-- From (for seller/admin) -->
                            <?php if($role !== 'buyer'): ?>
                            <td>
                                <strong style="color:#2d3748;">
                                    <?php echo e($inq['name']); ?>
                                </strong>
                                <div style="font-size:.8rem;color:#718096;">
                                    ✉️ <?php echo e($inq['email']); ?>
                                </div>
                            </td>
                            <td>
                                <?php if(!empty($inq['phone'])): ?>
                                <a href="tel:<?php echo e($inq['phone']); ?>"
                                   style="font-size:.88rem;color:#1a6fc4;font-weight:600;">
                                    📞 <?php echo e($inq['phone']); ?>
                                </a>
                                <?php else: ?>
                                <span style="color:#718096;font-size:.85rem;">—</span>
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>

                            <!-- Property -->
                            <td>
                                <strong style="color:#2d3748;">
                                    <?php echo e($inq['property_title'] ?? 'Property'); ?>
                                </strong>
                                <div style="font-size:.8rem;color:#718096;margin-top:2px;">
                                    📍 <?php echo e($inq['property_location'] ?? ''); ?>
                                    &nbsp;|&nbsp;
                                    <span class="card-badge <?php echo ($inq['listing_type'] ?? '') === 'Sale' ? 'badge-sale' : 'badge-rent'; ?>"
                                          style="position:static;font-size:.7rem;padding:2px 8px;">
                                        <?php echo e($inq['listing_type'] ?? ''); ?>
                                    </span>
                                </div>
                                <div style="font-size:.85rem;font-weight:700;
                                            color:#1a6fc4;margin-top:4px;">
                                    <?php
                                    if(!empty($inq['price'])) {
                                        echo formatPrice(
                                            (float)$inq['price'],
                                            $inq['listing_type'] ?? 'Sale'
                                        );
                                    }
                                    ?>
                                </div>
                            </td>

                            <!-- Message -->
                            <td style="max-width:280px;">
                                <div style="font-size:.88rem;color:#4a5568;
                                            line-height:1.5;">
                                    <?php
                                    $msg = e($inq['message']);
                                    echo strlen($msg) > 100
                                        ? substr($msg, 0, 100) . '...'
                                        : $msg;
                                    ?>
                                </div>
                            </td>

                            <!-- Date -->
                            <td style="font-size:.85rem;
                                       color:#718096;white-space:nowrap;">
                                <?php echo date('d M Y', strtotime($inq['created_at'])); ?>
                                <div style="font-size:.78rem;">
                                    <?php echo date('h:i A', strtotime($inq['created_at'])); ?>
                                </div>
                            </td>

                            <!-- Action -->
                            <td>
                                <a href="property-details.php?id=<?php echo (int)$inq['property_id']; ?>"
                                   class="btn btn-sm btn-outline">
                                    View Property
                                </a>
                            </td>
                        </tr>

                        <!-- Full Message Row (expandable) -->
                        <tr style="background:#f7fafc;">
                            <td colspan="<?php echo $role !== 'buyer' ? '7' : '5'; ?>"
                                style="padding:12px 16px;
                                       font-size:.85rem;color:#4a5568;
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