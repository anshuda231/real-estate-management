<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

$pdo = getDB();

// Get property ID
$id = (int)($_GET['id'] ?? 0);

if($id <= 0) {
    redirect('properties.php');
}

// Fetch property with owner info
$stmt = $pdo->prepare("
    SELECT p.*, u.name AS owner_name,
           u.email AS owner_email,
           u.phone AS owner_phone
    FROM properties p
    LEFT JOIN users u ON p.user_id = u.user_id
    WHERE p.property_id = ?
    AND p.status = 'active'
    LIMIT 1
");
$stmt->execute([$id]);
$property = $stmt->fetch();

if(!$property) {
    redirect('properties.php');
}

// Related Properties
$relStmt = $pdo->prepare("
    SELECT * FROM properties
    WHERE property_type = ?
    AND property_id != ?
    AND status = 'active'
    ORDER BY created_at DESC
    LIMIT 3
");
$relStmt->execute([
    $property['property_type'],
    $id
]);
$relatedProperties = $relStmt->fetchAll();

// Features array
$features = [];
if(!empty($property['features'])) {
    $features = array_map('trim', explode(',', $property['features']));
}

// Inquiry success message
$inquirySuccess = getFlash('inquiry_success');
$inquiryError   = getFlash('inquiry_error');

// Pre-fill inquiry form if logged in
$currentUser = currentUser();
?>
<?php require_once 'includes/header.php'; ?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span class="breadcrumb-sep">›</span>
            <a href="properties.php">Properties</a>
            <span class="breadcrumb-sep">›</span>
            <a href="properties.php?property_type=<?php echo urlencode($property['property_type']); ?>">
                <?php echo e($property['property_type']); ?>s
            </a>
            <span class="breadcrumb-sep">›</span>
            <span><?php echo e($property['title']); ?></span>
        </div>
        <h1><?php echo e($property['title']); ?></h1>
        <p>
            📍 <?php echo e($property['location']); ?> &nbsp;|&nbsp;
            <?php echo e($property['property_type']); ?> &nbsp;|&nbsp;
            For <?php echo e($property['listing_type']); ?>
        </p>
    </div>
</section>

<!-- DETAILS LAYOUT -->
<div class="container">
    <div class="details-layout">

        <!-- ── MAIN CONTENT ───────────────────────────── -->
        <div class="details-main">

            <!-- Back Link -->
            <a href="properties.php" class="back-link">
                ← Back to Properties
            </a>

            <!-- Property Image -->
            <div class="details-gallery">
                <img src="assets/images/properties/<?php echo e($property['image']); ?>"
                     alt="<?php echo e($property['title']); ?>"
                     onerror="this.src='https://placehold.co/900x420/1a6fc4/ffffff?text=<?php echo urlencode($property['property_type']); ?>'">
            </div>

            <!-- Property Header Card -->
            <div class="details-card">

                <!-- Badges -->
                <div class="details-badges">
                    <span class="badge <?php echo $property['listing_type'] === 'Sale' ? 'badge-success' : 'badge-secondary'; ?>">
                        For <?php echo e($property['listing_type']); ?>
                    </span>
                    <span class="badge badge-primary">
                        <?php echo e($property['property_type']); ?>
                    </span>
                    <span class="badge badge-primary">
                        📍 <?php echo e($property['location']); ?>
                    </span>
                </div>

                <!-- Title & Price -->
                <div class="details-header">
                    <div>
                        <h1 class="details-title">
                            <?php echo e($property['title']); ?>
                        </h1>
                        <div class="details-price">
                            <?php echo formatPrice(
                                (float)$property['price'],
                                $property['listing_type']
                            ); ?>
                        </div>
                    </div>

                    <!-- Edit/Delete buttons if owner or admin -->
                    <?php if(canEditProperty($property)): ?>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <a href="edit-property.php?id=<?php echo (int)$property['property_id']; ?>"
                           class="btn btn-warning btn-sm">
                            ✏️ Edit
                        </a>
                        <a href="delete-property.php?id=<?php echo (int)$property['property_id']; ?>"
                           class="btn btn-danger btn-sm"
                           data-confirm="Are you sure you want to delete this property? This cannot be undone.">
                            🗑️ Delete
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Info Grid -->
                <div class="details-info-grid">

                    <div class="info-item">
                        <span class="info-icon">📐</span>
                        <span class="info-label">Area</span>
                        <span class="info-value">
                            <?php echo e($property['area']); ?> sq.ft
                        </span>
                    </div>

                    <?php if($property['bedrooms'] > 0): ?>
                    <div class="info-item">
                        <span class="info-icon">🛏️</span>
                        <span class="info-label">Bedrooms</span>
                        <span class="info-value">
                            <?php echo e($property['bedrooms']); ?>
                        </span>
                    </div>
                    <?php endif; ?>

                    <?php if($property['bathrooms'] > 0): ?>
                    <div class="info-item">
                        <span class="info-icon">🚿</span>
                        <span class="info-label">Bathrooms</span>
                        <span class="info-value">
                            <?php echo e($property['bathrooms']); ?>
                        </span>
                    </div>
                    <?php endif; ?>

                    <div class="info-item">
                        <span class="info-icon">🏠</span>
                        <span class="info-label">Type</span>
                        <span class="info-value">
                            <?php echo e($property['property_type']); ?>
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-icon">💰</span>
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            For <?php echo e($property['listing_type']); ?>
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-icon">📅</span>
                        <span class="info-label">Listed On</span>
                        <span class="info-value">
                            <?php echo date('d M Y', strtotime($property['created_at'])); ?>
                        </span>
                    </div>

                </div>

            </div>

            <!-- Description Card -->
            <div class="details-card">
                <h3 style="margin-bottom:16px;">About This Property</h3>
                <p style="line-height:1.8;color:#4a5568;margin:0;">
                    <?php echo nl2br(e($property['description'])); ?>
                </p>
            </div>

            <!-- Features Card -->
            <?php if(!empty($features)): ?>
            <div class="details-card">
                <h3 style="margin-bottom:16px;">
                    ✨ Property Features & Amenities
                </h3>
                <div class="features-list">
                    <?php foreach($features as $feature): ?>
                    <?php if(!empty($feature)): ?>
                    <span class="feature-tag">
                        ✅ <?php echo e($feature); ?>
                    </span>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Related Properties -->
            <?php if(!empty($relatedProperties)): ?>
            <div class="details-card">
                <h3 style="margin-bottom:20px;">
                    Similar <?php echo e($property['property_type']); ?>s
                </h3>
                <div class="property-grid"
                     style="grid-template-columns:repeat(auto-fill,minmax(220px,1fr));">
                    <?php foreach($relatedProperties as $rp): ?>
                    <div class="property-card">
                        <div class="card-image" style="height:160px;">
                            <img src="assets/images/properties/<?php echo e($rp['image']); ?>"
                                 alt="<?php echo e($rp['title']); ?>"
                                 onerror="this.src='https://placehold.co/300x160/1a6fc4/ffffff?text=<?php echo urlencode($rp['property_type']); ?>'">
                            <span class="card-badge <?php echo $rp['listing_type'] === 'Sale' ? 'badge-sale' : 'badge-rent'; ?>">
                                <?php echo e($rp['listing_type']); ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="card-title">
                                <?php echo e($rp['title']); ?>
                            </div>
                            <div class="card-location">
                                📍 <?php echo e($rp['location']); ?>
                            </div>
                            <div class="card-price">
                                <?php echo formatPrice(
                                    (float)$rp['price'],
                                    $rp['listing_type']
                                ); ?>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="property-details.php?id=<?php echo (int)$rp['property_id']; ?>"
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

        <!-- ── SIDEBAR ─────────────────────────────────── -->
        <div class="details-sidebar">

            <!-- Price Card -->
            <div class="sidebar-card"
                 style="background:linear-gradient(135deg,#1a6fc4,#145fa8);color:#fff;">
                <div style="font-size:.85rem;opacity:.8;margin-bottom:6px;">
                    <?php echo e($property['listing_type']); ?> Price
                </div>
                <div style="font-size:2rem;font-weight:800;margin-bottom:16px;">
                    <?php echo formatPrice(
                        (float)$property['price'],
                        $property['listing_type']
                    ); ?>
                </div>
                <div style="display:flex;gap:10px;">
                    <span style="background:rgba(255,255,255,.15);
                                 padding:6px 14px;border-radius:50px;
                                 font-size:.82rem;font-weight:600;">
                        <?php echo e($property['property_type']); ?>
                    </span>
                    <span style="background:rgba(255,255,255,.15);
                                 padding:6px 14px;border-radius:50px;
                                 font-size:.82rem;font-weight:600;">
                        <?php echo e($property['area']); ?> sq.ft
                    </span>
                </div>
            </div>

            <!-- Agent/Owner Card -->
            <div class="sidebar-card">
                <h3>Contact Owner / Agent</h3>

                <div class="agent-info">
                    <div class="agent-avatar">
                        <?php echo strtoupper(substr($property['owner_name'] ?? 'O', 0, 1)); ?>
                    </div>
                    <div>
                        <div class="agent-name">
                            <?php echo e($property['owner_name'] ?? 'Property Owner'); ?>
                        </div>
                        <div class="agent-role">Property Owner / Seller</div>
                    </div>
                </div>

                <div class="agent-contact">
                    <?php if(!empty($property['owner_phone'])): ?>
                    <div class="agent-contact-item">
                        <span>📞</span>
                        <span><?php echo e($property['owner_phone']); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($property['owner_email'])): ?>
                    <div class="agent-contact-item">
                        <span>✉️</span>
                        <span><?php echo e($property['owner_email']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if(!empty($property['owner_phone'])): ?>
                <a href="tel:<?php echo e($property['owner_phone']); ?>"
                   class="btn btn-success btn-full"
                   style="margin-bottom:10px;">
                    📞 Call Owner
                </a>
                <?php endif; ?>

                <?php if(!empty($property['owner_email'])): ?>
                <a href="mailto:<?php echo e($property['owner_email']); ?>"
                   class="btn btn-outline btn-full">
                    ✉️ Send Email
                </a>
                <?php endif; ?>

            </div>

            <!-- Inquiry Form Card -->
            <div class="sidebar-card">
                <h3>Send Inquiry</h3>

                <!-- Inquiry Success/Error -->
                <?php if($inquirySuccess): ?>
                <div class="alert alert-success" data-autodismiss="5000">
                    <span class="alert-icon">✅</span>
                    <?php echo e($inquirySuccess); ?>
                </div>
                <?php endif; ?>

                <?php if($inquiryError): ?>
                <div class="alert alert-danger">
                    <span class="alert-icon">❌</span>
                    <?php echo e($inquiryError); ?>
                </div>
                <?php endif; ?>

                <form method="POST"
                      action="inquiry.php"
                      id="inquiryForm">

                    <input type="hidden"
                           name="property_id"
                           value="<?php echo (int)$property['property_id']; ?>">

                    <!-- Name -->
                    <div class="form-group">
                        <label for="inq_name" class="required">
                            Your Name
                        </label>
                        <input type="text"
                               name="inq_name"
                               id="inq_name"
                               class="form-control"
                               placeholder="Full name"
                               value="<?php echo e($currentUser['name'] ?? ''); ?>"
                               required>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="inq_email" class="required">
                            Email Address
                        </label>
                        <input type="email"
                               name="inq_email"
                               id="inq_email"
                               class="form-control"
                               placeholder="your@email.com"
                               value="<?php echo e($currentUser['email'] ?? ''); ?>"
                               required>
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label for="inq_phone">
                            Phone Number
                        </label>
                        <input type="tel"
                               name="inq_phone"
                               id="inq_phone"
                               class="form-control"
                               placeholder="10-digit number">
                    </div>

                    <!-- Message -->
                    <div class="form-group">
                        <label for="inq_message" class="required">
                            Message
                        </label>
                        <textarea name="inq_message"
                                  id="inq_message"
                                  class="form-control"
                                  rows="4"
                                  placeholder="I am interested in this property. Please contact me..."
                                  required></textarea>
                    </div>

                    <button type="submit"
                            class="btn btn-primary btn-full">
                        📩 Send Inquiry
                    </button>

                </form>
            </div>

            <!-- Property Summary -->
            <div class="sidebar-card">
                <h3>Property Summary</h3>
                <table style="width:100%;font-size:.9rem;">
                    <tr style="border-bottom:1px solid #e2e8f0;">
                        <td style="padding:10px 0;color:#718096;">Type</td>
                        <td style="padding:10px 0;font-weight:600;text-align:right;">
                            <?php echo e($property['property_type']); ?>
                        </td>
                    </tr>
                    <tr style="border-bottom:1px solid #e2e8f0;">
                        <td style="padding:10px 0;color:#718096;">Status</td>
                        <td style="padding:10px 0;font-weight:600;text-align:right;">
                            For <?php echo e($property['listing_type']); ?>
                        </td>
                    </tr>
                    <tr style="border-bottom:1px solid #e2e8f0;">
                        <td style="padding:10px 0;color:#718096;">Location</td>
                        <td style="padding:10px 0;font-weight:600;text-align:right;">
                            <?php echo e($property['location']); ?>
                        </td>
                    </tr>
                    <tr style="border-bottom:1px solid #e2e8f0;">
                        <td style="padding:10px 0;color:#718096;">Area</td>
                        <td style="padding:10px 0;font-weight:600;text-align:right;">
                            <?php echo e($property['area']); ?> sq.ft
                        </td>
                    </tr>
                    <?php if($property['bedrooms'] > 0): ?>
                    <tr style="border-bottom:1px solid #e2e8f0;">
                        <td style="padding:10px 0;color:#718096;">Bedrooms</td>
                        <td style="padding:10px 0;font-weight:600;text-align:right;">
                            <?php echo e($property['bedrooms']); ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if($property['bathrooms'] > 0): ?>
                    <tr style="border-bottom:1px solid #e2e8f0;">
                        <td style="padding:10px 0;color:#718096;">Bathrooms</td>
                        <td style="padding:10px 0;font-weight:600;text-align:right;">
                            <?php echo e($property['bathrooms']); ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td style="padding:10px 0;color:#718096;">Listed On</td>
                        <td style="padding:10px 0;font-weight:600;text-align:right;">
                            <?php echo date('d M Y', strtotime($property['created_at'])); ?>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
        <!-- end sidebar -->

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>