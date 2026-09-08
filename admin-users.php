<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

requireAdmin();

$pdo    = getDB();
$errors = [];

// Handle role change
if(isset($_POST['change_role'])) {
    $targetId   = (int)($_POST['user_id'] ?? 0);
    $newRole    = trim($_POST['role'] ?? '');
    $adminCount = (int)$pdo->query("
        SELECT COUNT(*) FROM users WHERE role = 'admin'
    ")->fetchColumn();

    if(!in_array($newRole, ['buyer','seller','admin'])) {
        $errors[] = 'Invalid role selected.';
    } elseif($targetId === (int)$_SESSION['user_id'] && $newRole !== 'admin') {
        $errors[] = 'You cannot change your own admin role.';
    } elseif($adminCount <= 1 && $newRole !== 'admin') {
        $errors[] = 'At least one admin must exist.';
    } else {
        $stmt = $pdo->prepare("
            UPDATE users SET role = ? WHERE user_id = ?
        ");
        $stmt->execute([$newRole, $targetId]);
        setFlash('success', 'User role updated successfully.');
        redirect('admin-users.php');
    }
}

// Handle delete user
if(isset($_GET['delete'])) {
    $targetId   = (int)$_GET['delete'];
    $adminCount = (int)$pdo->query("
        SELECT COUNT(*) FROM users WHERE role = 'admin'
    ")->fetchColumn();

    if($targetId === (int)$_SESSION['user_id']) {
        setFlash('error', 'You cannot delete your own account.');
    } elseif($adminCount <= 1) {
        $stmt = $pdo->prepare("
            SELECT role FROM users WHERE user_id = ?
        ");
        $stmt->execute([$targetId]);
        $targetUser = $stmt->fetch();
        if($targetUser && $targetUser['role'] === 'admin') {
            setFlash('error', 'Cannot delete the last admin account.');
        }
    } else {
        $stmt = $pdo->prepare("
            DELETE FROM users WHERE user_id = ?
        ");
        $stmt->execute([$targetId]);
        setFlash('success', 'User deleted successfully.');
    }
    redirect('admin-users.php');
}

// Fetch all users
$users = $pdo->query("
    SELECT u.*,
        (SELECT COUNT(*) FROM properties WHERE user_id = u.user_id) AS property_count,
        (SELECT COUNT(*) FROM inquiries  WHERE user_id = u.user_id) AS inquiry_count
    FROM users u
    ORDER BY u.created_at DESC
")->fetchAll();

$totalUsers   = count($users);
$totalAdmins  = 0;
$totalSellers = 0;
$totalBuyers  = 0;

foreach($users as $u) {
    if($u['role'] === 'admin')  $totalAdmins++;
    if($u['role'] === 'seller') $totalSellers++;
    if($u['role'] === 'buyer')  $totalBuyers++;
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
            <span>Manage Users</span>
        </div>
        <h1>Manage Users</h1>
        <p>View, edit roles and manage all registered users</p>
    </div>
</section>

<div class="container page-padding">

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

    <!-- Stats -->
    <div class="stats-grid" style="margin-bottom:32px;">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-number"><?php echo $totalUsers; ?></div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👑</div>
            <div class="stat-number"><?php echo $totalAdmins; ?></div>
            <div class="stat-label">Admins</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏠</div>
            <div class="stat-number"><?php echo $totalSellers; ?></div>
            <div class="stat-label">Sellers</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🔍</div>
            <div class="stat-number"><?php echo $totalBuyers; ?></div>
            <div class="stat-label">Buyers</div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">👥 All Users</h3>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Properties</th>
                        <th>Inquiries</th>
                        <th>Joined</th>
                        <th>Change Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $i => $u): ?>
                    <tr>
                        <!-- # -->
                        <td style="color:#718096;font-size:.85rem;">
                            <?php echo $i + 1; ?>
                        </td>

                        <!-- User Info -->
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:38px;height:38px;
                                            border-radius:50%;
                                            background:#1a6fc4;color:#fff;
                                            display:flex;align-items:center;
                                            justify-content:center;
                                            font-weight:700;font-size:1rem;
                                            flex-shrink:0;">
                                    <?php echo strtoupper(substr($u['name'], 0, 1)); ?>
                                </div>
                                <div>
                                    <strong style="color:#2d3748;">
                                        <?php echo e($u['name']); ?>
                                        <?php if($u['user_id'] == $_SESSION['user_id']): ?>
                                        <span style="font-size:.75rem;
                                                     color:#1a6fc4;
                                                     font-weight:600;">
                                            (You)
                                        </span>
                                        <?php endif; ?>
                                    </strong>
                                    <div style="font-size:.8rem;color:#718096;">
                                        ✉️ <?php echo e($u['email']); ?>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Phone -->
                        <td style="font-size:.88rem;">
                            📞 <?php echo e($u['phone']); ?>
                        </td>

                        <!-- Role Badge -->
                        <td>
                            <span class="role-badge role-<?php echo $u['role']; ?>">
                                <?php
                                $roleIcons = [
                                    'admin'  => '👑',
                                    'seller' => '🏠',
                                    'buyer'  => '🔍'
                                ];
                                echo ($roleIcons[$u['role']] ?? '') . ' ' . ucfirst($u['role']);
                                ?>
                            </span>
                        </td>

                        <!-- Properties Count -->
                        <td style="text-align:center;">
                            <?php if($u['property_count'] > 0): ?>
                            <a href="admin-properties.php?user_id=<?php echo $u['user_id']; ?>"
                               style="font-weight:700;color:#1a6fc4;">
                                <?php echo $u['property_count']; ?>
                            </a>
                            <?php else: ?>
                            <span style="color:#718096;">0</span>
                            <?php endif; ?>
                        </td>

                        <!-- Inquiries Count -->
                        <td style="text-align:center;">
                            <span style="color:#718096;">
                                <?php echo $u['inquiry_count']; ?>
                            </span>
                        </td>

                        <!-- Joined Date -->
                        <td style="font-size:.85rem;color:#718096;">
                            <?php echo date('d M Y', strtotime($u['created_at'])); ?>
                        </td>

                        <!-- Change Role Form -->
                        <td>
                            <form method="POST"
                                  action="admin-users.php"
                                  style="display:flex;gap:6px;align-items:center;">
                                <input type="hidden"
                                       name="user_id"
                                       value="<?php echo $u['user_id']; ?>">
                                <select name="role"
                                        class="sort-select"
                                        style="height:34px;font-size:.82rem;">
                                    <option value="buyer"
                                        <?php echo $u['role'] === 'buyer' ? 'selected' : ''; ?>>
                                        Buyer
                                    </option>
                                    <option value="seller"
                                        <?php echo $u['role'] === 'seller' ? 'selected' : ''; ?>>
                                        Seller
                                    </option>
                                    <option value="admin"
                                        <?php echo $u['role'] === 'admin' ? 'selected' : ''; ?>>
                                        Admin
                                    </option>
                                </select>
                                <button type="submit"
                                        name="change_role"
                                        class="btn btn-sm btn-success"
                                        title="Update Role">
                                    ✓
                                </button>
                            </form>
                        </td>

                        <!-- Delete Action -->
                        <td>
                            <?php if($u['user_id'] != $_SESSION['user_id']): ?>
                            <a href="admin-users.php?delete=<?php echo $u['user_id']; ?>"
                               class="btn btn-sm btn-danger"
                               data-confirm="Delete user '<?php echo e($u['name']); ?>'? All their properties will also be deleted!">
                                🗑️ Delete
                            </a>
                            <?php else: ?>
                            <span style="font-size:.82rem;color:#718096;">
                                Current User
                            </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>