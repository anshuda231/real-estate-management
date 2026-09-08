<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

if(isLoggedIn()) { redirect('dashboard.php'); }

$errors = [];

$msgMap = [
    'login_required' => 'Please login to access that page.',
    'unauthorized'   => 'You do not have permission to view that page.',
    'logged_out'     => 'You have been successfully logged out.',
];
$infoMsg = $msgMap[$_GET['msg'] ?? ''] ?? '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validation
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = 'Please enter a valid email address.';

    if(empty($password))
        $errors[] = 'Please enter your password.';

    // Check credentials
    if(empty($errors)) {
        $pdo  = getDB();
        $stmt = $pdo->prepare("
            SELECT * FROM users
            WHERE email = ?
            LIMIT 1
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if($user && password_verify($password, $user['password'])) {

            // Set session
            $_SESSION['user_id']    = $user['user_id'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role']  = $user['role'];

            setFlash('success', 'Welcome back, ' . $user['name'] . '! 👋');
            redirect('dashboard.php');

        } else {
            $errors[] = 'Invalid email or password. Please try again.';
        }
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="auth-wrapper">
    <div class="auth-card">

        <!-- Logo -->
        <div class="auth-logo">
            <a href="index.php">🏠 Nest<span>Finder</span></a>
        </div>

        <h1 class="auth-title">Welcome Back</h1>
        <p class="auth-subtitle">
            Login to your NestFinder account
        </p>

        <!-- Info Message -->
        <?php if($infoMsg): ?>
        <div class="alert alert-info">
            <span class="alert-icon">ℹ️</span>
            <?php echo e($infoMsg); ?>
        </div>
        <?php endif; ?>

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

        <!-- Login Form -->
        <form method="POST" action="login.php">

            <!-- Email -->
            <div class="form-group">
                <label for="email" class="required">
                    Email Address
                </label>
                <input type="email"
                       name="email"
                       id="email"
                       class="form-control"
                       placeholder="Enter your email"
                       value="<?php echo isset($_POST['email']) ? e($_POST['email']) : ''; ?>"
                       required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="required">
                    Password
                </label>
                <div class="pass-wrap">
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="Enter your password"
                           required>
                    <button type="button"
                            class="pass-toggle"
                            data-target="password">👁️</button>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="btn btn-primary btn-full btn-lg">
                🔐 Login
            </button>

        </form>

        <!-- Demo Credentials Box -->
        <div style="margin-top:24px;padding:18px;
                    background:#f8f9fa;border-radius:10px;
                    font-size:.85rem;border:1px solid #e2e8f0;">
            <strong style="color:#1a202c;">
                🧪 Demo Accounts for Testing:
            </strong>
            <div style="display:grid;gap:8px;margin-top:12px;">
                <div style="display:flex;align-items:center;
                            gap:8px;padding:8px;background:#fff;
                            border-radius:8px;border:1px solid #e2e8f0;">
                    <span>👑</span>
                    <div>
                        <div style="font-weight:700;color:#c0392b;">
                            Admin
                        </div>
                        <div style="color:#718096;">
                            admin@nestfinder.com
                        </div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;
                            gap:8px;padding:8px;background:#fff;
                            border-radius:8px;border:1px solid #e2e8f0;">
                    <span>🏠</span>
                    <div>
                        <div style="font-weight:700;color:#1e8449;">
                            Seller
                        </div>
                        <div style="color:#718096;">
                            rajesh@email.com
                        </div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;
                            gap:8px;padding:8px;background:#fff;
                            border-radius:8px;border:1px solid #e2e8f0;">
                    <span>🔍</span>
                    <div>
                        <div style="font-weight:700;color:#1a6fc4;">
                            Buyer
                        </div>
                        <div style="color:#718096;">
                            rohit@email.com
                        </div>
                    </div>
                </div>
                <div style="padding:8px;background:#e8f1fb;
                            border-radius:8px;text-align:center;
                            font-weight:700;color:#1a6fc4;">
                    🔑 Password for all: Test@1234
                </div>
            </div>
        </div>

        <div class="auth-footer">
            Don't have an account?
            <a href="register.php">Register Now</a>
        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>