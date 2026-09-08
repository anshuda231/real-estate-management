<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

requireLogin();

$pdo = getDB();
$id  = (int)($_GET['id'] ?? 0);

if($id <= 0) {
    redirect('my-properties.php');
}

// Fetch property
$stmt = $pdo->prepare("
    SELECT * FROM properties
    WHERE property_id = ?
    LIMIT 1
");
$stmt->execute([$id]);
$property = $stmt->fetch();

if(!$property) {
    setFlash('error', 'Property not found.');
    redirect('my-properties.php');
}

// Check permission
if(!canEditProperty($property)) {
    setFlash('error', 'You do not have permission to edit this property.');
    redirect('my-properties.php');
}

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
    $status        = trim($_POST['status']        ?? 'active');

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

    if(!in_array($status, ['active','inactive']))
        $status = 'active';

    // Handle image upload
    $imageName = $property['image']; // Keep existing image by default

    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file    = $_FILES['image'];
        $allowed = ['image/jpeg','image/png','image/webp'];
        $maxSize = 5 * 1024 * 1024;

        if(!in_array($file['type'], $allowed)) {
            $errors[] = 'Image must be JPG, PNG or WebP.';
        } elseif($file['size'] > $maxSize) {
            $errors[] = 'Image must be less than 5MB.';
        } else {
            $ext       = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newImage  = 'prop_' . time() . '_' . rand(100,999) . '.' . $ext;
            $uploadDir = __DIR__ . '/assets/images/properties/';

            if(!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if(move_uploaded_file($file['tmp_name'], $uploadDir . $newImage)) {
                // Delete old image if not default
                if(
                    !empty($property['image']) &&
                    $property['image'] !== 'default.jpg' &&
                    file_exists($uploadDir . $property['image'])
                ) {
                    unlink($uploadDir . $property['image']);
                }
                $imageName = $newImage;
            } else {
                $errors[] = 'Failed to upload image. Please try again.';
            }
        }
    }

    // Update database
    if(empty($errors)) {
        $stmt = $pdo->prepare("
            UPDATE properties SET
                title         = ?,
                property_type = ?,
                listing_type  = ?,
                price         = ?,
                location      = ?,
                area          = ?,
                bedrooms      = ?,
                bathrooms     = ?,
                description   = ?,
                features      = ?,
                image         = ?,
                status        = ?
            WHERE property_id = ?
        ");
        $stmt->execute([
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
            $imageName,
            $status,
            $id
        ]);

        setFlash('success', 'Property updated successfully!');
        redirect('property-details.php?id=' . $id);
    }

    // If errors — update property array with POST values for form
    $property = array_merge($property, [
        'title'         => $title,
        'property_type' => $property_type,
        'listing_type'  => $listing_type,
        'price'         => $price,
        'location'      => $location,
        'area'          => $area,
        'bedrooms'      => $bedrooms,
        'bathrooms'     => $bathrooms,
        'description'   => $description,
        'features'      => $features,
        'status'        => $status,
    ]);
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
            <a href="my-properties.php">My Properties</a>
            <span class="breadcrumb-sep">›</span>
            <span>Edit Property</span>
        </div>
        <h1>Edit Property</h1>
        <p>Update your property listing details</p>
    </div>
</section>

<div class="container page-padding">
    <div class="form-card form-card-wide">

        <h2 class="form-title">Update Property Details</h2>
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
              action="edit-property.php?id=<?php echo $id; ?>"
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
                           value="<?php echo e($property['title']); ?>"
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
                            <?php echo $property['property_type'] === $t ? 'selected' : ''; ?>>
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
                        <option value="Sale"
                            <?php echo $property['listing_type'] === 'Sale' ? 'selected' : ''; ?>>
                            For Sale
                        </option>
                        <option value="Rent"
                            <?php echo $property['listing_type'] === 'Rent' ? 'selected' : ''; ?>>
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
                           value="<?php echo e($property['price']); ?>"
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
                           value="<?php echo e($property['location']); ?>"
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
                           value="<?php echo e($property['area']); ?>"
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
                            <?php echo (int)$property['bedrooms'] === $i ? 'selected' : ''; ?>>
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
                            <?php echo (int)$property['bathrooms'] === $i ? 'selected' : ''; ?>>
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
                          required><?php echo e($property['description']); ?></textarea>
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
                       placeholder="e.g. Lift, Parking, Swimming Pool, Gym"
                       value="<?php echo e($property['features'] ?? ''); ?>">
                <div class="form-hint">
                    Separate features with commas
                </div>
            </div>

            <!-- Status (Admin only) -->
            <?php if(hasRole('admin')): ?>
            <div class="form-group">
                <label for="status">
                    Listing Status
                </label>
                <select name="status"
                        id="status"
                        class="form-control">
                    <option value="active"
                        <?php echo $property['status'] === 'active' ? 'selected' : ''; ?>>
                        Active — Visible to buyers
                    </option>
                    <option value="inactive"
                        <?php echo $property['status'] === 'inactive' ? 'selected' : ''; ?>>
                        Inactive — Hidden from buyers
                    </option>
                </select>
            </div>
            <?php endif; ?>

            <!-- Current Image -->
            <?php if(!empty($property['image']) && $property['image'] !== 'default.jpg'): ?>
            <div class="form-group">
                <label>Current Image</label>
                <div style="width:200px;height:140px;border-radius:10px;
                            overflow:hidden;border:1px solid #e2e8f0;">
                    <img src="assets/images/properties/<?php echo e($property['image']); ?>"
                         alt="Current"
                         style="width:100%;height:100%;object-fit:cover;"
                         onerror="this.src='https://placehold.co/200x140/1a6fc4/ffffff?text=Property'">
                </div>
            </div>
            <?php endif; ?>

            <!-- New Image Upload -->
            <div class="form-group">
                <label for="propertyImage">
                    Update Image
                    <span style="color:#718096;font-weight:400;">
                        (Leave empty to keep current image)
                    </span>
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
                        Select new image to preview
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div style="display:flex;gap:16px;flex-wrap:wrap;">
                <button type="submit"
                        class="btn btn-primary btn-lg">
                    💾 Update Property
                </button>
                <a href="property-details.php?id=<?php echo $id; ?>"
                   class="btn btn-outline btn-lg">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>