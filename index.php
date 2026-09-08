<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

$pdo = getDB();

// Featured Properties — Latest 6
$stmt = $pdo->prepare("
    SELECT * FROM properties
    WHERE status = 'active'
    ORDER BY created_at DESC
    LIMIT 6
");
$stmt->execute();
$featuredProperties = $stmt->fetchAll();

// Property Count by Type
$stmt2 = $pdo->prepare("
    SELECT property_type, COUNT(*) as total
    FROM properties
    WHERE status = 'active'
    GROUP BY property_type
");
$stmt2->execute();
$typeCounts = [];
foreach($stmt2->fetchAll() as $row) {
    $typeCounts[$row['property_type']] = $row['total'];
}

// Popular Locations
$stmt3 = $pdo->prepare("
    SELECT location, COUNT(*) as total
    FROM properties
    WHERE status = 'active'
    GROUP BY location
    ORDER BY total DESC
    LIMIT 6
");
$stmt3->execute();
$locations = $stmt3->fetchAll();

// Stats for Hero
$totalProps = $pdo->query("
    SELECT COUNT(*) FROM properties WHERE status = 'active'
")->fetchColumn();

$totalUsers = $pdo->query("
    SELECT COUNT(*) FROM users
")->fetchColumn();

$totalCities = $pdo->query("
    SELECT COUNT(DISTINCT location) FROM properties WHERE status = 'active'
")->fetchColumn();
?>
<?php require_once 'includes/header.php'; ?>

<!-- ═══════════════════════════════════════════════════
     HERO SECTION
════════════════════════════════════════════════════ -->
<section class="hero">
    <div class="container">
        <div class="hero-content">

            <div class="hero-badge">
                🏆 India's #1 Real Estate Portal
            </div>

            <h1>Find Your Dream Home<br>Right Here</h1>

            <p>
                Thousands of verified properties — Apartments, Villas,
                Houses, Plots &amp; Offices.<br>
                Buy or Rent — everything in one place.
            </p>

            <!-- SEARCH FORM -->
            <div class="hero-search">

                <div class="hero-search-tabs">
                    <button class="search-tab active" data-type="Sale">
                        🏠 Buy
                    </button>
                    <button class="search-tab" data-type="Rent">
                        🔑 Rent
                    </button>
                </div>

                <form action="properties.php" method="GET">
                    <input type="hidden"
                           name="listing_type"
                           id="listingTypeInput"
                           value="Sale">

                    <div class="hero-search-grid">

                        <div class="search-field">
                            <label for="hero_location">Location</label>
                            <input type="text"
                                   name="location"
                                   id="hero_location"
                                   placeholder="City or area...">
                        </div>

                        <div class="search-field">
                            <label for="hero_type">Property Type</label>
                            <select name="property_type" id="hero_type">
                                <option value="">All Types</option>
                                <option value="Apartment">Apartment</option>
                                <option value="Villa">Villa</option>
                                <option value="House">House</option>
                                <option value="Plot">Plot</option>
                                <option value="Office">Office</option>
                            </select>
                        </div>

                        <div class="search-field">
                            <label for="hero_min">Min Price (₹)</label>
                            <input type="number"
                                   name="min_price"
                                   id="hero_min"
                                   placeholder="Min ₹"
                                   min="0">
                        </div>

                        <div class="search-field">
                            <label for="hero_max">Max Price (₹)</label>
                            <input type="number"
                                   name="max_price"
                                   id="hero_max"
                                   placeholder="Max ₹"
                                   min="0">
                        </div>

                        <div class="search-btn-wrap">
                            <button type="submit" class="btn btn-primary">
                                🔍 Search
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            <!-- HERO STATS -->
            <div class="hero-stats">
                <div class="hero-stat">
                    <strong><?php echo number_format($totalProps); ?>+</strong>
                    <span>Properties</span>
                </div>
                <div class="hero-stat">
                    <strong><?php echo number_format($totalUsers); ?>+</strong>
                    <span>Happy Users</span>
                </div>
                <div class="hero-stat">
                    <strong><?php echo number_format($totalCities); ?>+</strong>
                    <span>Cities</span>
                </div>
                <div class="hero-stat">
                    <strong>100%</strong>
                    <span>Trusted</span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════
     FEATURED PROPERTIES
════════════════════════════════════════════════════ -->
<section class="featured-section">
    <div class="container">

        <h2 class="section-title fade-in">Featured Properties</h2>
        <div class="section-divider"></div>
        <p class="section-subtitle fade-in">
            Our most popular and latest property listings
        </p>

        <?php if(empty($featuredProperties)): ?>
            <div class="empty-state">
                <div class="empty-icon">🏠</div>
                <h3>No Properties Found</h3>
                <p>No properties are available at the moment.</p>
                <?php if(isAdminOrSeller()): ?>
                    <a href="add-property.php" class="btn btn-primary">
                        Add First Property
                    </a>
                <?php endif; ?>
            </div>

        <?php else: ?>

            <div class="property-grid">
                <?php foreach($featuredProperties as $p): ?>
                <div class="property-card fade-in">

                    <!-- Card Image -->
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

                    <!-- Card Body -->
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

                    <!-- Card Footer -->
                    <div class="card-footer">
                        <span class="card-area">
                            📐 <?php echo e($p['area']); ?> sq.ft
                        </span>
                        <a href="property-details.php?id=<?php echo (int)$p['property_id']; ?>"
                           class="btn btn-primary btn-sm">
                            View Details
                        </a>
                    </div>

                </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-4">
                <a href="properties.php" class="btn btn-outline btn-lg">
                    View All Properties →
                </a>
            </div>

        <?php endif; ?>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════
     BROWSE BY CATEGORY
════════════════════════════════════════════════════ -->
<section class="categories-section">
    <div class="container">

        <h2 class="section-title fade-in">Browse by Category</h2>
        <div class="section-divider"></div>
        <p class="section-subtitle fade-in">
            Choose a property type that suits your needs
        </p>

        <div class="categories-grid">
            <?php
            $categories = [
                [
                    'type'  => 'Apartment',
                    'icon'  => '🏢',
                    'label' => 'Apartments'
                ],
                [
                    'type'  => 'Villa',
                    'icon'  => '🏡',
                    'label' => 'Villas'
                ],
                [
                    'type'  => 'House',
                    'icon'  => '🏠',
                    'label' => 'Houses'
                ],
                [
                    'type'  => 'Plot',
                    'icon'  => '🌿',
                    'label' => 'Plots'
                ],
                [
                    'type'  => 'Office',
                    'icon'  => '🏬',
                    'label' => 'Offices'
                ],
            ];

            foreach($categories as $cat):
                $count = $typeCounts[$cat['type']] ?? 0;
            ?>
            <a href="properties.php?property_type=<?php echo urlencode($cat['type']); ?>"
               class="category-card fade-in">
                <span class="category-icon"><?php echo $cat['icon']; ?></span>
                <span class="category-name"><?php echo $cat['label']; ?></span>
                <span class="category-count">
                    <?php echo $count; ?> Properties
                </span>
            </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- ═══════════════════════════════════════════════════
     POPULAR LOCATIONS
════════════════════════════════════════════════════ -->
<section class="locations-section">
    <div class="container">

        <h2 class="section-title fade-in">Popular Locations</h2>
        <div class="section-divider"></div>
        <p class="section-subtitle fade-in">
            Most properties available in these cities
        </p>

        <div class="locations-grid">
            <?php foreach($locations as $loc): ?>
            <a href="properties.php?location=<?php echo urlencode($loc['location']); ?>"
               class="location-card fade-in">
                <img src="https://placehold.co/400x160/1a6fc4/ffffff?text=<?php echo urlencode($loc['location']); ?>"
                     alt="<?php echo e($loc['location']); ?>">
                <div class="location-overlay">
                    <div class="location-name">
                        📍 <?php echo e($loc['location']); ?>
                    </div>
                    <div class="location-count">
                        <?php echo $loc['total']; ?> Properties
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- ═══════════════════════════════════════════════════
     WHY CHOOSE NESTFINDER
════════════════════════════════════════════════════ -->
<section class="why-section">
    <div class="container">

        <h2 class="section-title fade-in">Why Choose NestFinder?</h2>
        <div class="section-divider"></div>
        <p class="section-subtitle fade-in">
            We provide the best real estate experience in India
        </p>

        <div class="why-grid">

            <div class="why-card fade-in">
                <div class="why-icon">🔍</div>
                <h3>Smart Search</h3>
                <p>
                    Filter by location, price, type and more
                    to find your perfect property instantly.
                </p>
            </div>

            <div class="why-card fade-in">
                <div class="why-icon">✅</div>
                <h3>Verified Listings</h3>
                <p>
                    All properties are verified —
                    no fraud, no hidden charges, no stress.
                </p>
            </div>

            <div class="why-card fade-in">
                <div class="why-icon">💬</div>
                <h3>Direct Contact</h3>
                <p>
                    Contact sellers directly —
                    no brokers, no commissions, no middlemen.
                </p>
            </div>

            <div class="why-card fade-in">
                <div class="why-icon">📱</div>
                <h3>Mobile Friendly</h3>
                <p>
                    Works perfectly on all devices —
                    browse properties from anywhere, anytime.
                </p>
            </div>

            <div class="why-card fade-in">
                <div class="why-icon">🔒</div>
                <h3>Secure Platform</h3>
                <p>
                    Your data is safe with us.
                    Secure login, verified users, trusted transactions.
                </p>
            </div>

            <div class="why-card fade-in">
                <div class="why-icon">⚡</div>
                <h3>Fast & Easy</h3>
                <p>
                    List your property in minutes.
                    Simple process, instant visibility to buyers.
                </p>
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════
     CALL TO ACTION
════════════════════════════════════════════════════ -->
<section class="cta-section">
    <div class="container">

        <h2 class="fade-in">List Your Property — It's FREE!</h2>
        <p class="fade-in">
            Reach millions of buyers and renters across India.
            Register now and list your property today.
        </p>

        <div class="cta-buttons">
            <?php if(!isLoggedIn()): ?>
                <a href="register.php" class="btn btn-secondary btn-lg">
                    🚀 Register Now — It's Free
                </a>
                <a href="properties.php" class="btn btn-outline-white btn-lg">
                    🔍 Browse Properties
                </a>
            <?php else: ?>
                <?php if(isAdminOrSeller()): ?>
                <a href="add-property.php" class="btn btn-secondary btn-lg">
                    ➕ Add Your Property
                </a>
                <?php endif; ?>
                <a href="properties.php" class="btn btn-outline-white btn-lg">
                    🔍 Browse Properties
                </a>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>