<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

// Only sellers and admin can add properties
requireLogin();
requireRole('seller');

$pdo    = getDB();
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get inputs
    $title         = trim($_POST['title']         ?? '');
    $property_type = trim($_POST['property_type'] ?? '');
    $listing_type  = trim($_POST['listing_type']  ?? '');
    $price         = trim($_POST['price']         ?? '');
    $location      = trim($_POST['location']      ?? '');
    $area          = trim($_POST['area']          ?? '');
    $bedrooms      = (int)($_POST['bedrooms']     ?? 0);
    $bathrooms     = (int)($_POST['bathrooms']    ?? 0);
    $description   = trim($_POST['description']   ?? '');
    $features      = trim($_POST['features']      ?? '');
    $user_id       = (int)$_SESSION['user_id'];

    // Validation
    if(empty($title) || strlen($title) < 5)
        $errors[] = 'Title must be at least 5 characters.';

    if(!in_array($property_type, ['Apartment','Villa','House','Plot','Office']))
        $errors[] = 'Please select a valid property type.';

    if(!in_array($listing_type, ['Sale','Rent']))
        $errors[] = 'Please select Sale or Rent.';

    if(empty($price) || !is_numeric($price) || (float)$price <= 0)
        $errors[] = 'Please enter a valid price.';

    if(empty($location) || strlen($location) < 2)
        $errors[] = 'Please enter a valid location.';

    if(empty($area) || !is_numeric($area) || (float)$area <= 0)
        $errors[] = 'Please enter a valid area.';

    if(empty($description) || strlen($description) < 20)
        $errors[] = 'Description must be at least 20 characters.';

    // Handle image upload
    $imageName = 'default.jpg';
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file     = $_FILES['image'];
        $allowed  = ['image/jpeg','image/png','image/webp'];
        $maxSize  = 5 * 1024 * 1024; // 5MB

        if(!in_array($file['type'], $allowed)) {
            $errors[] = 'Image must be JPG, PNG or WebP.';
        } elseif($file['size'] > $maxSize) {
            $errors[] = 'Image must be less than 5MB.';
        } else {
            $ext       = pathinfo($file['name'], PATHINFO_EXTENSION);
            $imageName = 'prop_' . time() . '_' . rand(100,999) . '.' . $ext;
            $uploadDir = __DIR__ . '/assets/images/properties/';

            if(!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if(!move_uploaded_file($file['tmp_name'], $uploadDir . $imageName)) {
                $errors[] = 'Failed to upload image. Please try again.';
                $imageName = 'default.jpg';
            }
        }
    }

    // Insert into database
    if(empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO properties
                (user_id, title, property_type, listing_type,
                 price, location, area, bedrooms, bathrooms,
                 description, features, image, status)
            VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')
        ");
        $stmt->execute([
            $user_id,
            $title,
            $property_type,
            $listing_type,
            (float)$price,
            $location,
            (float)$area,
            $bedrooms,
            $bathrooms,
            $description,
            $features,
            $imageName
        ]);

        $newId = $pdo->lastInsertId();
        setFlash('success', 'Property listed successfully!');
        redirect('property-details.php?id=' . $newId);
    }
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
            <span>Add Property</span>
        </div>
        <h1>Add New Property</h1>
        <p>Fill in the details below to list your property</p>
    </div>
</section>

<div class="container page-padding">
    <div class="form-card form-card-wide">

        <h2 class="form-title">Property Details</h2>
        <p class="form-subtitle">
            All fields marked with * are required
        </p>

        <!-- Errors -->
        <?php if(!empty($errors)): ?>
        <div class="alert alert-danger">
            <span class="alert-icon">❌</span>
            <ul style="margin:0;padding-left:16px;">
                <?php foreach($errors as $err): ?>
                    <li><?php echo e($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST"
              action="add-property.php"
              enctype="multipart/form-data"
              id="propertyForm"
              novalidate>

            <!-- Row 1: Title + Type -->
            <div class="form-row">
                <div class="form-group">
                    <label for="title" class="required">
                        Property Title
                    </label>
                    <input type="text"
                           name="title"
                           id="title"
                           class="form-control"
                           placeholder="e.g. Green Valley Apartment"
                           value="<?php echo isset($_POST['title']) ? e($_POST['title']) : ''; ?>"
                           required>
                </div>

                <div class="form-group">
                    <label for="property_type" class="required">
                        Property Type
                    </label>
                    <select name="property_type"
                            id="property_type"
                            class="form-control"
                            required>
                        <option value="">Select Type</option>
                        <?php
                        $types = ['Apartment','Villa','House','Plot','Office'];
                        foreach($types as $t): ?>
                        <option value="<?php echo $t; ?>"
                            <?php echo (($_POST['property_type'] ?? '') === $t) ? 'selected' : ''; ?>>
                            <?php echo $t; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Row 2: Listing Type + Price -->
            <div class="form-row">
                <div class="form-group">
                    <label for="listing_type" class="required">
                        Listing Type
                    </label>
                    <select name="listing_type"
                            id="listing_type"
                            class="form-control"
                            required>
                        <option value="">Select</option>
                        <option value="Sale"
                            <?php echo (($_POST['listing_type'] ?? '') === 'Sale') ? 'selected' : ''; ?>>
                            For Sale
                        </option>
                        <option value="Rent"
                            <?php echo (($_POST['listing_type'] ?? '') === 'Rent') ? 'selected' : ''; ?>>
                            For Rent
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="price" class="required">
                        Price (₹)
                    </label>
                    <input type="number"
                           name="price"
                           id="price"
                           class="form-control"
                           placeholder="e.g. 4500000"
                           min="0"
                           value="<?php echo isset($_POST['price']) ? e($_POST['price']) : ''; ?>"
                           required>
                    <div id="priceDisplay"></div>
                </div>
            </div>

            <!-- Row 3: Location + Area -->
            <div class="form-row">
                <div class="form-group">
                    <label for="location" class="required">
                        Location / City
                    </label>
                    <input type="text"
                           name="location"
                           id="location"
                           class="form-control"
                           placeholder="e.g. Nagpur, Pune, Mumbai"
                           value="<?php echo isset($_POST['location']) ? e($_POST['location']) : ''; ?>"
                           required>
                </div>

                <div class="form-group">
                    <label for="area" class="required">
                        Area (sq.ft)
                    </label>
                    <input type="number"
                           name="area"
                           id="area"
                           class="form-control"
                           placeholder="e.g. 1200"
                           min="0"
                           value="<?php echo isset($_POST['area']) ? e($_POST['area']) : ''; ?>"
                           required>
                </div>
            </div>

            <!-- Row 4: Bedrooms + Bathrooms -->
            <div class="form-row">
                <div class="form-group" id="bedroomGroup">
                    <label for="bedrooms">
                        Bedrooms
                    </label>
                    <select name="bedrooms"
                            id="bedrooms"
                            class="form-control">
                        <?php for($i = 0; $i <= 10; $i++): ?>
                        <option value="<?php echo $i; ?>"
                            <?php echo (($_POST['bedrooms'] ?? 0) == $i) ? 'selected' : ''; ?>>
                            <?php echo $i === 0 ? 'Not Applicable' : $i; ?>
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group" id="bathroomGroup">
                    <label for="bathrooms">
                        Bathrooms
                    </label>
                    <select name="bathrooms"
                            id="bathrooms"
                            class="form-control">
                        <?php for($i = 0; $i <= 10; $i++): ?>
                        <option value="<?php echo $i; ?>"
                            <?php echo (($_POST['bathrooms'] ?? 0) == $i) ? 'selected' : ''; ?>>
                            <?php echo $i === 0 ? 'Not Applicable' : $i; ?>
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description" class="required">
                    Description
                </label>
                <textarea name="description"
                          id="description"
                          class="form-control"
                          rows="5"
                          placeholder="Describe the property in detail — location highlights, nearby facilities, construction quality, etc."
                          required><?php echo isset($_POST['description']) ? e($_POST['description']) : ''; ?></textarea>
            </div>

            <!-- Features -->
            <div class="form-group">
                <label for="features">
                    Features &amp; Amenities
                </label>
                <input type="text"
                       name="features"
                       id="features"
                       class="form-control"
                       placeholder="e.g. Lift, Parking, Swimming Pool, Gym, CCTV"
                       value="<?php echo isset($_POST['features']) ? e($_POST['features']) : ''; ?>">
                <div class="form-hint">
                    Separate features with commas
                </div>
            </div>

            <!-- Image Upload -->
            <div class="form-group">
                <label for="propertyImage">
                    Property Image
                </label>
                <input type="file"
                       name="image"
                       id="propertyImage"
                       class="form-control"
                       accept="image/jpeg,image/png,image/webp">
                <div class="form-hint">
                    JPG, PNG or WebP. Max size: 5MB
                </div>
                <div class="image-preview" id="imagePreview">
                    <div class="image-preview-placeholder">
                        <span>🖼️</span>
                        Click to preview image
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div style="display:flex;gap:16px;flex-wrap:wrap;">
                <button type="submit"
                        class="btn btn-primary btn-lg">
                    ✅ List Property
                </button>
                <a href="dashboard.php"
                   class="btn btn-outline btn-lg">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>