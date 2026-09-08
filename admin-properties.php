<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

requireAdmin();

$pdo = getDB();

// Filter by user_id if set
$filterUserId = (int)($_GET['user_id'] ?? 0);

// Build query
if($filterUserId > 0) {
    $stmt = $pdo->prepare("
        SELECT p.*, u.name AS owner_name
        FROM properties p
        LEFT JOIN users u ON p.user_id = u.user_id
        WHERE p.user_id = ?
        ORDER BY p.created_at DESC
    ");
    $stmt->execute([$filterUserId]);
} else {
    $stmt = $pdo->prepare("
        SELECT p.*, u.name AS owner_name
        FROM properties p
        LEFT JOIN users u ON p.user_id = u.user_id
        ORDER BY p.created_at DESC
    ");
    $stmt->execute();
}

$properties = $stmt->fetchAll();

// Stats
$totalProps    = count($properties);
$activeProps   = 0;
$inactiveProps = 0;
$saleProps     = 0;
$rentProps     = 0;

foreach($properties as $p) {
    if($p['status'] === 'active')   $activeProps++;
    if($p['status'] === 'inactive') $inactiveProps++;
    if($p['listing_type'] === 'Sale') $saleProps++;
    if($p['listing_type'] === 'Rent') $rentProps++;
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
            <span>All Properties</span>
        </div>
        <h1>All Properties</h1>
        <p>
            Manage all property listings on the platform
            <?php if($filterUserId > 0): ?>
                — Filtered by User ID: <?php echo $filterUserId; ?>
                <a href="admin-properties.php"
                   style="color:#fff;text-decoration:underline;margin-left:8px;">
                    Clear Filter
                </a>
            <?php endif; ?>
        </p>
    </div>
</section>

<div class="container page-padding">

    <!-- Stats -->
    <div class="stats-grid" style="margin-bottom:32px;">
        <div class="stat-card">
            <div class="stat-icon">🏘️</div>
            <div class="stat-number"><?php echo $totalProps; ?></div>
            <div class="stat-label">Total Properties</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-number"><?php echo $activeProps; ?></div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-number"><?php echo $saleProps; ?></div>
            <div class="stat-label">For Sale</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🔑</div>
            <div class="stat-number"><?php echo $rentProps; ?></div>
            <div class="stat-label">For Rent</div>
        </div>
    </div>

    <!-- Properties Table -->
    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">
                🏘️ All Property Listings
            </h3>
            <a href="add-property.php"
               class="btn btn-primary btn-sm">
                ➕ Add Property
            </a>
        </div>

        <?php if(empty($properties)): ?>
            <div class="empty-state">
                <div class="empty-icon">🏘️</div>
                <h3>No Properties Found</h3>
                <p>No properties have been listed yet.</p>
                <a href="add-property.php"
                   class="btn btn-primary">
                    Add First Property
                </a>
            </div>
        <?php else: ?>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Property</th>
                            <th>Type</th>
                            <th>Listing</th>
                            <th>Price</th>
                            <th>Location</th>
                            <th>Owner</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($properties as $p): ?>
                        <tr>
                            <!-- Image -->
                            <td>
                                <img src="assets/images/properties/<?php echo e($p['image']); ?>"
                                     class="table-img"
                                     alt=""
                                     onerror="this.src='https://placehold.co/50x40/1a6fc4/fff?text=P'">
                            </td>

                            <!-- Title -->
                            <td>
                                <strong style="color:#2d3748;">
                                    <?php echo e($p['title']); ?>
                                </strong>
                                <div style="font-size:.8rem;color:#718096;margin-top:2px;">
                                    <?php if($p['bedrooms'] > 0): ?>
                                        🛏️ <?php echo $p['bedrooms']; ?> &nbsp;
                                    <?php endif; ?>
                                    📐 <?php echo e($p['area']); ?> sq.ft
                                </div>
                            </td>

                            <!-- Type -->
                            <td>
                                <span class="badge badge-primary">
                                    <?php echo e($p['property_type']); ?>
                                </span>
                            </td>

                            <!-- Listing Type -->
                            <td>
                                <span class="card-badge <?php echo $p['listing_type'] === 'Sale' ? 'badge-sale' : 'badge-rent'; ?>"
                                      style="position:static;font-size:.75rem;
                                             padding:4px 10px;">
                                    <?php echo e($p['listing_type']); ?>
                                </span>
                            </td>

                            <!-- Price -->
                            <td style="font-weight:700;color:#1a6fc4;">
                                <?php echo formatPrice(
                                    (float)$p['price'],
                                    $p['listing_type']
                                ); ?>
                            </td>

                            <!-- Location -->
                            <td>📍 <?php echo e($p['location']); ?></td>

                            <!-- Owner -->
                            <td style="font-size:.88rem;">
                                <?php echo e($p['owner_name'] ?? '—'); ?>
                            </td>

                            <!-- Status -->
                            <td>
                                <span class="status-badge status-<?php echo $p['status']; ?>">
                                    <?php echo ucfirst($p['status']); ?>
                                </span>
                            </td>

                            <!-- Date -->
                            <td style="font-size:.82rem;color:#718096;">
                                <?php echo date('d M Y', strtotime($p['created_at'])); ?>
                            </td>

                            <!-- Actions -->
                            <td>
                                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                    <a href="property-details.php?id=<?php echo (int)$p['property_id']; ?>"
                                       class="btn btn-sm btn-outline"
                                       title="View">
                                        👁️
                                    </a>
                                    <a href="edit-property.php?id=<?php echo (int)$p['property_id']; ?>"
                                       class="btn btn-sm btn-warning"
                                       title="Edit">
                                        ✏️
                                    </a>
                                    <a href="delete-property.php?id=<?php echo (int)$p['property_id']; ?>"
                                       class="btn btn-sm btn-danger"
                                       title="Delete"
                                       data-confirm="Delete '<?php echo e($p['title']); ?>' permanently?">
                                        🗑️
                                    </a>
                                </div>
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