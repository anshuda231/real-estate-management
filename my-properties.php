<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

requireLogin();
requireRole('seller');

$pdo    = getDB();
$userId = (int)$_SESSION['user_id'];

// Fetch my properties
$stmt = $pdo->prepare("
    SELECT * FROM properties
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmt->execute([$userId]);
$properties = $stmt->fetchAll();

$totalProperties = count($properties);
$activeCount     = 0;
$rentCount       = 0;
$saleCount       = 0;

foreach($properties as $p) {
    if($p['status'] === 'active') $activeCount++;
    if($p['listing_type'] === 'Rent') $rentCount++;
    if($p['listing_type'] === 'Sale') $saleCount++;
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
            <span>My Properties</span>
        </div>
        <h1>My Properties</h1>
        <p>Manage all your property listings</p>
    </div>
</section>

<div class="container page-padding">

    <!-- Stats Row -->
    <div class="stats-grid" style="margin-bottom:32px;">
        <div class="stat-card">
            <div class="stat-icon">🏘️</div>
            <div class="stat-number"><?php echo $totalProperties; ?></div>
            <div class="stat-label">Total Listings</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-number"><?php echo $activeCount; ?></div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-number"><?php echo $saleCount; ?></div>
            <div class="stat-label">For Sale</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🔑</div>
            <div class="stat-number"><?php echo $rentCount; ?></div>
            <div class="stat-label">For Rent</div>
        </div>
    </div>

    <!-- Content Card -->
    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">
                🏠 All My Listings
            </h3>
            <a href="add-property.php"
               class="btn btn-primary btn-sm">
                ➕ Add New Property
            </a>
        </div>

        <?php if(empty($properties)): ?>
            <div class="empty-state">
                <div class="empty-icon">🏠</div>
                <h3>No Properties Listed Yet</h3>
                <p>
                    You have not listed any properties yet.
                    Add your first property to get started.
                </p>
                <a href="add-property.php"
                   class="btn btn-primary">
                    ➕ Add First Property
                </a>
            </div>
        <?php else: ?>

            <!-- Table View -->
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
                                     alt="<?php echo e($p['title']); ?>"
                                     onerror="this.src='https://placehold.co/50x40/1a6fc4/fff?text=P'">
                            </td>

                            <!-- Title -->
                            <td>
                                <strong style="color:#2d3748;">
                                    <?php echo e($p['title']); ?>
                                </strong>
                                <div style="font-size:.8rem;color:#718096;margin-top:2px;">
                                    <?php if($p['bedrooms'] > 0): ?>
                                        🛏️ <?php echo $p['bedrooms']; ?> Beds &nbsp;
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

                            <!-- Status -->
                            <td>
                                <span class="status-badge status-<?php echo $p['status']; ?>">
                                    <?php echo ucfirst($p['status']); ?>
                                </span>
                            </td>

                            <!-- Date -->
                            <td style="font-size:.85rem;color:#718096;">
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
                                       data-confirm="Are you sure you want to delete '<?php echo e($p['title']); ?>'? This cannot be undone.">
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

    <!-- Card Grid View -->
    <?php if(!empty($properties)): ?>
    <div class="content-card mt-4">
        <div class="content-card-header">
            <h3 class="content-card-title">
                🏘️ Grid View
            </h3>
        </div>
        <div class="property-grid">
            <?php foreach($properties as $p): ?>
            <div class="property-card">
                <div class="card-image">
                    <img src="assets/images/properties/<?php echo e($p['image']); ?>"
                         alt="<?php echo e($p['title']); ?>"
                         onerror="this.src='https://placehold.co/400x220/1a6fc4/ffffff?text=<?php echo urlencode($p['property_type']); ?>'">
                    <span class="card-badge <?php echo $p['listing_type'] === 'Sale' ? 'badge-sale' : 'badge-rent'; ?>">
                        <?php echo e($p['listing_type']); ?>
                    </span>
                    <span class="badge-type">
                        <?php echo e($p['property_type']); ?>
                    </span>
                </div>

                <div class="card-body">
                    <div class="card-title">
                        <?php echo e($p['title']); ?>
                    </div>
                    <div class="card-location">
                        📍 <?php echo e($p['location']); ?>
                    </div>
                    <div class="card-price">
                        <?php echo formatPrice(
                            (float)$p['price'],
                            $p['listing_type']
                        ); ?>
                    </div>
                    <div style="margin-top:8px;">
                        <span class="status-badge status-<?php echo $p['status']; ?>">
                            <?php echo ucfirst($p['status']); ?>
                        </span>
                    </div>
                </div>

                <div class="card-footer">
                    <div style="display:flex;gap:6px;">
                        <a href="edit-property.php?id=<?php echo (int)$p['property_id']; ?>"
                           class="btn btn-sm btn-warning">
                            ✏️ Edit
                        </a>
                        <a href="delete-property.php?id=<?php echo (int)$p['property_id']; ?>"
                           class="btn btn-sm btn-danger"
                           data-confirm="Delete this property permanently?">
                            🗑️
                        </a>
                    </div>
                    <a href="property-details.php?id=<?php echo (int)$p['property_id']; ?>"
                       class="btn btn-sm btn-outline">
                        View
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php require_once 'includes/footer.php'; ?>