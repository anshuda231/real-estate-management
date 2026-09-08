<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

$pdo = getDB();

// ── GET FILTER VALUES ─────────────────────────────────────
$location      = trim($_GET['location']      ?? '');
$property_type = trim($_GET['property_type'] ?? '');
$listing_type  = trim($_GET['listing_type']  ?? '');
$min_price     = trim($_GET['min_price']     ?? '');
$max_price     = trim($_GET['max_price']     ?? '');
$bedrooms      = trim($_GET['bedrooms']      ?? '');
$sort          = trim($_GET['sort']          ?? 'newest');

// ── PAGINATION ────────────────────────────────────────────
$perPage     = 9;
$currentPage = max(1, (int)($_GET['page'] ?? 1));
$offset      = ($currentPage - 1) * $perPage;

// ── BUILD QUERY ───────────────────────────────────────────
$where  = ["p.status = 'active'"];
$params = [];

if(!empty($location)) {
    $where[]  = "p.location LIKE ?";
    $params[] = '%' . $location . '%';
}
if(!empty($property_type)) {
    $where[]  = "p.property_type = ?";
    $params[] = $property_type;
}
if(!empty($listing_type)) {
    $where[]  = "p.listing_type = ?";
    $params[] = $listing_type;
}
if($min_price !== '') {
    $where[]  = "p.price >= ?";
    $params[] = (float)$min_price;
}
if($max_price !== '') {
    $where[]  = "p.price <= ?";
    $params[] = (float)$max_price;
}
if($bedrooms !== '') {
    $where[]  = "p.bedrooms >= ?";
    $params[] = (int)$bedrooms;
}

$whereSQL = implode(' AND ', $where);

// Sort
$sortSQL = match($sort) {
    'price_asc'  => 'p.price ASC',
    'price_desc' => 'p.price DESC',
    'oldest'     => 'p.created_at ASC',
    default      => 'p.created_at DESC',
};

// Total count
$countStmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM properties p
    WHERE {$whereSQL}
");
$countStmt->execute($params);
$totalResults = (int)$countStmt->fetchColumn();
$totalPages   = (int)ceil($totalResults / $perPage);

// Fetch properties
$stmt = $pdo->prepare("
    SELECT p.*, u.name AS owner_name
    FROM properties p
    LEFT JOIN users u ON p.user_id = u.user_id
    WHERE {$whereSQL}
    ORDER BY {$sortSQL}
    LIMIT {$perPage} OFFSET {$offset}
");
$stmt->execute($params);
$properties = $stmt->fetchAll();

// Build query string for pagination links
$queryParams = $_GET;
unset($queryParams['page']);
$queryString = http_build_query($queryParams);
?>
<?php require_once 'includes/header.php'; ?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span class="breadcrumb-sep">›</span>
            <span>Properties</span>
            <?php if(!empty($listing_type)): ?>
                <span class="breadcrumb-sep">›</span>
                <span>For <?php echo e($listing_type); ?></span>
            <?php endif; ?>
            <?php if(!empty($property_type)): ?>
                <span class="breadcrumb-sep">›</span>
                <span><?php echo e($property_type); ?></span>
            <?php endif; ?>
        </div>
        <h1>
            <?php
            if(!empty($property_type) && !empty($listing_type)) {
                echo e($property_type) . 's For ' . e($listing_type);
            } elseif(!empty($listing_type)) {
                echo 'Properties For ' . e($listing_type);
            } elseif(!empty($property_type)) {
                echo e($property_type) . 's';
            } else {
                echo 'All Properties';
            }
            ?>
        </h1>
        <p>
            <?php echo number_format($totalResults); ?>
            propert<?php echo $totalResults === 1 ? 'y' : 'ies'; ?> found
            <?php if(!empty($location)): ?>
                in <strong><?php echo e($location); ?></strong>
            <?php endif; ?>
        </p>
    </div>
</section>

<!-- LISTING LAYOUT -->
<div class="container">
    <div class="listing-layout">

        <!-- ── FILTER SIDEBAR ─────────────────────────── -->
        <aside class="filter-sidebar">
            <div class="filter-title">
                <span>🔍 Filters</span>
                <button id="clearFilters"
                        class="btn btn-sm btn-outline"
                        title="Clear all filters">
                    Clear
                </button>
            </div>

            <form method="GET"
                  action="properties.php"
                  id="filterForm">

                <!-- Location -->
                <div class="filter-group">
                    <label for="f_location">Location</label>
                    <input type="text"
                           name="location"
                           id="f_location"
                           placeholder="City or area..."
                           value="<?php echo e($location); ?>">
                </div>

                <!-- Property Type -->
                <div class="filter-group">
                    <label for="f_type">Property Type</label>
                    <select name="property_type" id="f_type">
                        <option value="">All Types</option>
                        <?php
                        $types = [
                            'Apartment',
                            'Villa',
                            'House',
                            'Plot',
                            'Office'
                        ];
                        foreach($types as $type): ?>
                        <option value="<?php echo $type; ?>"
                            <?php echo $property_type === $type ? 'selected' : ''; ?>>
                            <?php echo $type; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Listing Type -->
                <div class="filter-group">
                    <label for="f_listing">Buy / Rent</label>
                    <select name="listing_type" id="f_listing">
                        <option value="">Buy &amp; Rent</option>
                        <option value="Sale"
                            <?php echo $listing_type === 'Sale' ? 'selected' : ''; ?>>
                            For Sale
                        </option>
                        <option value="Rent"
                            <?php echo $listing_type === 'Rent' ? 'selected' : ''; ?>>
                            For Rent
                        </option>
                    </select>
                </div>

                <!-- Price Range -->
                <div class="filter-group">
                    <label>Price Range (₹)</label>
                    <div class="price-range">
                        <input type="number"
                               name="min_price"
                               placeholder="Min"
                               min="0"
                               value="<?php echo e($min_price); ?>">
                        <input type="number"
                               name="max_price"
                               placeholder="Max"
                               min="0"
                               value="<?php echo e($max_price); ?>">
                    </div>
                </div>

                <!-- Bedrooms -->
                <div class="filter-group">
                    <label for="f_beds">Min Bedrooms</label>
                    <select name="bedrooms" id="f_beds">
                        <option value="">Any</option>
                        <?php for($i = 1; $i <= 5; $i++): ?>
                        <option value="<?php echo $i; ?>"
                            <?php echo $bedrooms == $i ? 'selected' : ''; ?>>
                            <?php echo $i; ?>+ Beds
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Sort -->
                <div class="filter-group">
                    <label for="f_sort">Sort By</label>
                    <select name="sort" id="f_sort">
                        <option value="newest"
                            <?php echo $sort === 'newest' ? 'selected' : ''; ?>>
                            Newest First
                        </option>
                        <option value="oldest"
                            <?php echo $sort === 'oldest' ? 'selected' : ''; ?>>
                            Oldest First
                        </option>
                        <option value="price_asc"
                            <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>>
                            Price: Low to High
                        </option>
                        <option value="price_desc"
                            <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>
                            Price: High to Low
                        </option>
                    </select>
                </div>

                <button type="submit"
                        class="btn btn-primary btn-full">
                    🔍 Apply Filters
                </button>

            </form>
        </aside>

        <!-- ── RESULTS ─────────────────────────────────── -->
        <div class="listing-results">

            <!-- Results Header -->
            <div class="results-header">
                <div class="results-count">
                    Showing
                    <strong>
                        <?php
                        $from = $totalResults > 0 ? $offset + 1 : 0;
                        $to   = min($offset + $perPage, $totalResults);
                        echo $from . '–' . $to;
                        ?>
                    </strong>
                    of
                    <strong><?php echo number_format($totalResults); ?></strong>
                    properties
                </div>

                <!-- Active Filters -->
                <?php if(!empty($location) || !empty($property_type) || !empty($listing_type)): ?>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <?php if(!empty($listing_type)): ?>
                    <span class="badge badge-primary">
                        For <?php echo e($listing_type); ?>
                    </span>
                    <?php endif; ?>
                    <?php if(!empty($property_type)): ?>
                    <span class="badge badge-primary">
                        <?php echo e($property_type); ?>
                    </span>
                    <?php endif; ?>
                    <?php if(!empty($location)): ?>
                    <span class="badge badge-primary">
                        📍 <?php echo e($location); ?>
                    </span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Property Grid -->
            <?php if(empty($properties)): ?>

                <div class="empty-state">
                    <div class="empty-icon">🏠</div>
                    <h3>No Properties Found</h3>
                    <p>
                        Try adjusting your filters or search
                        in a different location.
                    </p>
                    <a href="properties.php"
                       class="btn btn-primary">
                        View All Properties
                    </a>
                </div>

            <?php else: ?>

                <div class="property-grid">
                    <?php foreach($properties as $p): ?>
                    <div class="property-card">

                        <!-- Image -->
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

                        <!-- Body -->
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
                            <div class="card-features">
                                <?php if($p['bedrooms'] > 0): ?>
                                <div class="card-feature">
                                    🛏️ <?php echo e($p['bedrooms']); ?> Beds
                                </div>
                                <?php endif; ?>
                                <?php if($p['bathrooms'] > 0): ?>
                                <div class="card-feature">
                                    🚿 <?php echo e($p['bathrooms']); ?> Baths
                                </div>
                                <?php endif; ?>
                                <div class="card-feature">
                                    📐 <?php echo e($p['area']); ?> sq.ft
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="card-footer">
                            <span class="card-area">
                                By <?php echo e($p['owner_name'] ?? 'Owner'); ?>
                            </span>
                            <a href="property-details.php?id=<?php echo (int)$p['property_id']; ?>"
                               class="btn btn-primary btn-sm">
                                View Details
                            </a>
                        </div>

                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if($totalPages > 1): ?>
                <div class="pagination">

                    <?php if($currentPage > 1): ?>
                    <a href="?<?php echo $queryString; ?>&page=<?php echo $currentPage - 1; ?>"
                       class="page-btn">
                        ‹
                    </a>
                    <?php endif; ?>

                    <?php for($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?<?php echo $queryString; ?>&page=<?php echo $i; ?>"
                       class="page-btn <?php echo $i === $currentPage ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                    <?php endfor; ?>

                    <?php if($currentPage < $totalPages): ?>
                    <a href="?<?php echo $queryString; ?>&page=<?php echo $currentPage + 1; ?>"
                       class="page-btn">
                        ›
                    </a>
                    <?php endif; ?>

                </div>
                <?php endif; ?>

            <?php endif; ?>

        </div>
        <!-- end results -->

    </div>
    <!-- end listing-layout -->
</div>

<?php require_once 'includes/footer.php'; ?>