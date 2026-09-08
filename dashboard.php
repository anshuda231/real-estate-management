<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

requireLogin();

$pdo         = getDB();
$currentUser = currentUser();
$role        = $currentUser['role'];
$userId      = (int)$currentUser['user_id'];

// ── STATS based on role ───────────────────────────────────
if($role === 'admin') {

    $totalProperties = $pdo->query("
        SELECT COUNT(*) FROM properties
    ")->fetchColumn();

    $totalUsers = $pdo->query("
        SELECT COUNT(*) FROM users
    ")->fetchColumn();

    $totalInquiries = $pdo->query("
        SELECT COUNT(*) FROM inquiries
    ")->fetchColumn();

    $totalActive = $pdo->query("
        SELECT COUNT(*) FROM properties
        WHERE status = 'active'
    ")->fetchColumn();

    // Recent properties
    $recentProps = $pdo->query("
        SELECT p.*, u.name AS owner_name
        FROM properties p
        LEFT JOIN users u ON p.user_id = u.user_id
        ORDER BY p.created_at DESC
        LIMIT 5
    ")->fetchAll();

    // Recent inquiries
    $recentInquiries = $pdo->query("
        SELECT i.*, p.title AS property_title
        FROM inquiries i
        LEFT JOIN properties p ON i.property_id = p.property_id
        ORDER BY i.created_at DESC
        LIMIT 5
    ")->fetchAll();

} elseif($role === 'seller') {

    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM properties
        WHERE user_id = ?
    ");
    $stmt->execute([$userId]);
    $myProperties = $stmt->fetchColumn();

    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM properties
        WHERE user_id = ? AND status = 'active'
    ");
    $stmt->execute([$userId]);
    $activeProperties = $stmt->fetchColumn();

    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM inquiries i
        JOIN properties p ON i.property_id = p.property_id
        WHERE p.user_id = ?
    ");
    $stmt->execute([$userId]);
    $myInquiries = $stmt->fetchColumn();

    // Recent properties
    $stmt = $pdo->prepare("
        SELECT * FROM properties
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT 5
    ");
    $stmt->execute([$userId]);
    $recentProps = $stmt->fetchAll();

    // Recent inquiries on my properties
    $stmt = $pdo->prepare("
        SELECT i.*, p.title AS property_title
        FROM inquiries i
        JOIN properties p ON i.property_id = p.property_id
        WHERE p.user_id = ?
        ORDER BY i.created_at DESC
        LIMIT 5
    ");
    $stmt->execute([$userId]);
    $recentInquiries = $stmt->fetchAll();

} else {
    // Buyer

    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM inquiries
        WHERE user_id = ?
    ");
    $stmt->execute([$userId]);
    $myInquiries = $stmt->fetchColumn();

    $totalProperties = $pdo->query("
        SELECT COUNT(*) FROM properties
        WHERE status = 'active'
    ")->fetchColumn();

    // Recent inquiries by buyer
    $stmt = $pdo->prepare("
        SELECT i.*, p.title AS property_title
        FROM inquiries i
        LEFT JOIN properties p ON i.property_id = p.property_id
        WHERE i.user_id = ?
        ORDER BY i.created_at DESC
        LIMIT 5
    ");
    $stmt->execute([$userId]);
    $recentInquiries = $stmt->fetchAll();
}
?>
<?php require_once 'includes/header.php'; ?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <h1>
            Welcome, <?php echo e($currentUser['name']); ?>! 👋
        </h1>
        <p>
            <?php if($role === 'admin'): ?>
                Admin Dashboard — Full system overview
            <?php elseif($role === 'seller'): ?>
                Seller Dashboard — Manage your property listings
            <?php else: ?>
                Buyer Dashboard — Find your perfect property
            <?php endif; ?>
        </p>
    </div>
</section>

<div class="container">
    <div class="dashboard-layout">

        <!-- ── SIDEBAR ─────────────────────────────────── -->
        <aside class="dashboard-sidebar">

            <div class="dash-user-header">
                <div class="dash-avatar">
                    <?php echo strtoupper(substr($currentUser['name'], 0, 1)); ?>
                </div>
                <div class="dash-username">
                    <?php echo e($currentUser['name']); ?>
                </div>
                <div class="dash-role">
                    <?php echo ucfirst($role); ?>
                </div>
            </div>

            <nav class="dash-nav">

                <a href="dashboard.php"
                   class="dash-nav-item active">
                    <span class="nav-icon">📊</span>
                    Dashboard
                </a>

                <a href="properties.php"
                   class="dash-nav-item">
                    <span class="nav-icon">🔍</span>
                    Browse Properties
                </a>

                <?php if($role === 'seller' || $role === 'admin'): ?>
                <a href="my-properties.php"
                   class="dash-nav-item">
                    <span class="nav-icon">🏠</span>
                    My Properties
                </a>
                <a href="add-property.php"
                   class="dash-nav-item">
                    <span class="nav-icon">➕</span>
                    Add Property
                </a>
                <?php endif; ?>

                <a href="my-inquiries.php"
                   class="dash-nav-item">
                    <span class="nav-icon">📩</span>
                    <?php echo $role === 'buyer' ? 'My Inquiries' : 'Received Inquiries'; ?>
                </a>

                <?php if($role === 'admin'): ?>
                <a href="admin-users.php"
                   class="dash-nav-item">
                    <span class="nav-icon">👥</span>
                    Manage Users
                </a>
                <a href="admin-properties.php"
                   class="dash-nav-item">
                    <span class="nav-icon">🏘️</span>
                    All Properties
                </a>
                <a href="admin-inquiries.php"
                   class="dash-nav-item">
                    <span class="nav-icon">📋</span>
                    All Inquiries
                </a>
                <?php endif; ?>

                <a href="logout.php"
                   class="dash-nav-item"
                   style="color:#e74c3c;"
                   data-confirm="Are you sure you want to logout?">
                    <span class="nav-icon">🚪</span>
                    Logout
                </a>

            </nav>
        </aside>

        <!-- ── MAIN CONTENT ───────────────────────────── -->
        <div class="dashboard-main">

            <!-- ════ ADMIN DASHBOARD ════ -->
            <?php if($role === 'admin'): ?>

                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">🏘️</div>
                        <div class="stat-number">
                            <?php echo number_format($totalProperties); ?>
                        </div>
                        <div class="stat-label">Total Properties</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">✅</div>
                        <div class="stat-number">
                            <?php echo number_format($totalActive); ?>
                        </div>
                        <div class="stat-label">Active Listings</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-number">
                            <?php echo number_format($totalUsers); ?>
                        </div>
                        <div class="stat-label">Total Users</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📩</div>
                        <div class="stat-number">
                            <?php echo number_format($totalInquiries); ?>
                        </div>
                        <div class="stat-label">Total Inquiries</div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="content-card-title">⚡ Quick Actions</h3>
                    </div>
                    <div style="display:flex;gap:12px;flex-wrap:wrap;">
                        <a href="admin-users.php"
                           class="btn btn-primary">
                            👥 Manage Users
                        </a>
                        <a href="admin-properties.php"
                           class="btn btn-primary">
                            🏘️ All Properties
                        </a>
                        <a href="admin-inquiries.php"
                           class="btn btn-primary">
                            📋 All Inquiries
                        </a>
                        <a href="add-property.php"
                           class="btn btn-success">
                            ➕ Add Property
                        </a>
                    </div>
                </div>

                <!-- Recent Properties -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="content-card-title">
                            🏠 Recent Properties
                        </h3>
                        <a href="admin-properties.php"
                           class="btn btn-sm btn-outline">
                            View All
                        </a>
                    </div>
                    <?php if(empty($recentProps)): ?>
                        <p style="color:#718096;">
                            No properties found.
                        </p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Price</th>
                                    <th>Owner</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recentProps as $p): ?>
                                <tr>
                                    <td>
                                        <img src="assets/images/properties/<?php echo e($p['image']); ?>"
                                             class="table-img"
                                             onerror="this.src='https://placehold.co/50x40/1a6fc4/fff?text=P'"
                                             alt="">
                                    </td>
                                    <td>
                                        <strong>
                                            <?php echo e($p['title']); ?>
                                        </strong>
                                        <div style="font-size:.8rem;color:#718096;">
                                            📍 <?php echo e($p['location']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo e($p['property_type']); ?></td>
                                    <td>
                                        <?php echo formatPrice(
                                            (float)$p['price'],
                                            $p['listing_type']
                                        ); ?>
                                    </td>
                                    <td>
                                        <?php echo e($p['owner_name'] ?? '—'); ?>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo $p['status']; ?>">
                                            <?php echo ucfirst($p['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:6px;">
                                            <a href="property-details.php?id=<?php echo $p['property_id']; ?>"
                                               class="btn btn-sm btn-outline">
                                                View
                                            </a>
                                            <a href="edit-property.php?id=<?php echo $p['property_id']; ?>"
                                               class="btn btn-sm btn-warning">
                                                Edit
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

                <!-- Recent Inquiries -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="content-card-title">
                            📩 Recent Inquiries
                        </h3>
                        <a href="admin-inquiries.php"
                           class="btn btn-sm btn-outline">
                            View All
                        </a>
                    </div>
                    <?php if(empty($recentInquiries)): ?>
                        <p style="color:#718096;">No inquiries yet.</p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>Property</th>
                                    <th>Email</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recentInquiries as $inq): ?>
                                <tr>
                                    <td><strong><?php echo e($inq['name']); ?></strong></td>
                                    <td><?php echo e($inq['property_title'] ?? '—'); ?></td>
                                    <td><?php echo e($inq['email']); ?></td>
                                    <td>
                                        <?php echo date('d M Y', strtotime($inq['created_at'])); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>

            <!-- ════ SELLER DASHBOARD ════ -->
            <?php elseif($role === 'seller'): ?>

                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">🏠</div>
                        <div class="stat-number">
                            <?php echo $myProperties; ?>
                        </div>
                        <div class="stat-label">My Properties</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">✅</div>
                        <div class="stat-number">
                            <?php echo $activeProperties; ?>
                        </div>
                        <div class="stat-label">Active Listings</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📩</div>
                        <div class="stat-number">
                            <?php echo $myInquiries; ?>
                        </div>
                        <div class="stat-label">Inquiries Received</div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="content-card-title">⚡ Quick Actions</h3>
                    </div>
                    <div style="display:flex;gap:12px;flex-wrap:wrap;">
                        <a href="add-property.php"
                           class="btn btn-primary">
                            ➕ Add New Property
                        </a>
                        <a href="my-properties.php"
                           class="btn btn-outline">
                            🏠 My Properties
                        </a>
                        <a href="my-inquiries.php"
                           class="btn btn-outline">
                            📩 View Inquiries
                        </a>
                    </div>
                </div>

                <!-- My Recent Properties -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="content-card-title">
                            🏠 My Recent Listings
                        </h3>
                        <a href="my-properties.php"
                           class="btn btn-sm btn-outline">
                            View All
                        </a>
                    </div>
                    <?php if(empty($recentProps)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">🏠</div>
                            <h3>No Properties Yet</h3>
                            <p>You have not listed any properties yet.</p>
                            <a href="add-property.php"
                               class="btn btn-primary">
                                Add Your First Property
                            </a>
                        </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recentProps as $p): ?>
                                <tr>
                                    <td>
                                        <img src="assets/images/properties/<?php echo e($p['image']); ?>"
                                             class="table-img"
                                             onerror="this.src='https://placehold.co/50x40/1a6fc4/fff?text=P'"
                                             alt="">
                                    </td>
                                    <td>
                                        <strong>
                                            <?php echo e($p['title']); ?>
                                        </strong>
                                        <div style="font-size:.8rem;color:#718096;">
                                            📍 <?php echo e($p['location']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo e($p['property_type']); ?></td>
                                    <td>
                                        <?php echo formatPrice(
                                            (float)$p['price'],
                                            $p['listing_type']
                                        ); ?>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo $p['status']; ?>">
                                            <?php echo ucfirst($p['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:6px;">
                                            <a href="property-details.php?id=<?php echo $p['property_id']; ?>"
                                               class="btn btn-sm btn-outline">
                                                View
                                            </a>
                                            <a href="edit-property.php?id=<?php echo $p['property_id']; ?>"
                                               class="btn btn-sm btn-warning">
                                                Edit
                                            </a>
                                            <a href="delete-property.php?id=<?php echo $p['property_id']; ?>"
                                               class="btn btn-sm btn-danger"
                                               data-confirm="Delete this property permanently?">
                                                Del
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

                <!-- Recent Inquiries -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="content-card-title">
                            📩 Recent Inquiries
                        </h3>
                        <a href="my-inquiries.php"
                           class="btn btn-sm btn-outline">
                            View All
                        </a>
                    </div>
                    <?php if(empty($recentInquiries)): ?>
                        <p style="color:#718096;">
                            No inquiries received yet.
                        </p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>Property</th>
                                    <th>Phone</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recentInquiries as $inq): ?>
                                <tr>
                                    <td>
                                        <strong>
                                            <?php echo e($inq['name']); ?>
                                        </strong>
                                        <div style="font-size:.8rem;color:#718096;">
                                            <?php echo e($inq['email']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo e($inq['property_title'] ?? '—'); ?>
                                    </td>
                                    <td>
                                        <?php echo e($inq['phone'] ?? '—'); ?>
                                    </td>
                                    <td>
                                        <?php echo date('d M Y', strtotime($inq['created_at'])); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>

            <!-- ════ BUYER DASHBOARD ════ -->
            <?php else: ?>

                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">📩</div>
                        <div class="stat-number">
                            <?php echo $myInquiries; ?>
                        </div>
                        <div class="stat-label">My Inquiries</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">🏘️</div>
                        <div class="stat-number">
                            <?php echo number_format($totalProperties); ?>
                        </div>
                        <div class="stat-label">Available Properties</div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="content-card-title">⚡ Quick Actions</h3>
                    </div>
                    <div style="display:flex;gap:12px;flex-wrap:wrap;">
                        <a href="properties.php"
                           class="btn btn-primary">
                            🔍 Browse Properties
                        </a>
                        <a href="properties.php?listing_type=Sale"
                           class="btn btn-outline">
                            💰 Properties For Sale
                        </a>
                        <a href="properties.php?listing_type=Rent"
                           class="btn btn-outline">
                            🔑 Properties For Rent
                        </a>
                        <a href="my-inquiries.php"
                           class="btn btn-outline">
                            📩 My Inquiries
                        </a>
                    </div>
                </div>

                <!-- Recent Inquiries -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="content-card-title">
                            📩 My Recent Inquiries
                        </h3>
                        <a href="my-inquiries.php"
                           class="btn btn-sm btn-outline">
                            View All
                        </a>
                    </div>
                    <?php if(empty($recentInquiries)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">📩</div>
                            <h3>No Inquiries Yet</h3>
                            <p>
                                You have not sent any inquiries.
                                Browse properties and send your first inquiry!
                            </p>
                            <a href="properties.php"
                               class="btn btn-primary">
                                Browse Properties
                            </a>
                        </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Property</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recentInquiries as $inq): ?>
                                <tr>
                                    <td>
                                        <strong>
                                            <?php echo e($inq['property_title'] ?? 'Property'); ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <?php echo e(substr($inq['message'], 0, 60)); ?>...
                                    </td>
                                    <td>
                                        <?php echo date('d M Y', strtotime($inq['created_at'])); ?>
                                    </td>
                                    <td>
                                        <a href="property-details.php?id=<?php echo (int)$inq['property_id']; ?>"
                                           class="btn btn-sm btn-outline">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Property Suggestions -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="content-card-title">
                            🏠 Latest Properties
                        </h3>
                        <a href="properties.php"
                           class="btn btn-sm btn-outline">
                            View All
                        </a>
                    </div>
                    <?php
                    $latestStmt = $pdo->prepare("
                        SELECT * FROM properties
                        WHERE status = 'active'
                        ORDER BY created_at DESC
                        LIMIT 3
                    ");
                    $latestStmt->execute();
                    $latestProps = $latestStmt->fetchAll();
                    ?>
                    <div class="property-grid"
                         style="grid-template-columns:repeat(auto-fill,minmax(220px,1fr));">
                        <?php foreach($latestProps as $lp): ?>
                        <div class="property-card">
                            <div class="card-image" style="height:160px;">
                                <img src="assets/images/properties/<?php echo e($lp['image']); ?>"
                                     alt="<?php echo e($lp['title']); ?>"
                                     onerror="this.src='https://placehold.co/300x160/1a6fc4/ffffff?text=<?php echo urlencode($lp['property_type']); ?>'">
                                <span class="card-badge <?php echo $lp['listing_type'] === 'Sale' ? 'badge-sale' : 'badge-rent'; ?>">
                                    <?php echo e($lp['listing_type']); ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="card-title">
                                    <?php echo e($lp['title']); ?>
                                </div>
                                <div class="card-location">
                                    📍 <?php echo e($lp['location']); ?>
                                </div>
                                <div class="card-price">
                                    <?php echo formatPrice(
                                        (float)$lp['price'],
                                        $lp['listing_type']
                                    ); ?>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="property-details.php?id=<?php echo (int)$lp['property_id']; ?>"
                                   class="btn btn-primary btn-sm btn-full">
                                    View Details
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            <?php endif; ?>

        </div>
        <!-- end main -->

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
