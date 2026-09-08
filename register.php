<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

if(isLoggedIn()) { redirect('dashboard.php'); }

$errors  = [];
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['name']             ?? '');
    $email    = trim($_POST['email']            ?? '');
    $phone    = trim($_POST['phone']            ?? '');
    $password = trim($_POST['password']         ?? '');
    $confirm  = trim($_POST['confirm_password'] ?? '');
    $role     = trim($_POST['role']             ?? 'buyer');

    // Validation
    if(empty($name) || strlen($name) < 2)
        $errors[] = 'Name must be at least 2 characters.';

    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = 'Please enter a valid email address.';

    if(empty($phone) || !preg_match('/^\d{10}$/', $phone))
        $errors[] = 'Please enter a valid 10-digit phone number.';

    if(empty($password) || strlen($password) < 6)
        $errors[] = 'Password must be at least 6 characters.';

    if($password !== $confirm)
        $errors[] = 'Passwords do not match.';

    if(!in_array($role, ['buyer','seller'])) $role = 'buyer';

    // Check email already exists
    if(empty($errors)) {
        $pdo  = getDB();
        $stmt = $pdo->prepare("
            SELECT user_id FROM users WHERE email = ?
        ");
        $stmt->execute([$email]);
        if($stmt->fetch())
            $errors[] = 'This email is already registered. Please login.';
    }

    // Insert new user
    if(empty($errors)) {
        $pdo  = getDB();
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, phone, password, role)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$name, $email, $phone, $hash, $role]);
        $success = 'Registration successful! You can now login.';
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

        <h1 class="auth-title">Create Account</h1>
        <p class="auth-subtitle">
            Register for free and start exploring
        </p>

        <!-- Success -->
        <?php if($success): ?>
        <div class="alert alert-success" data-autodismiss="5000">
            <span class="alert-icon">✅</span>
            <div>
                <?php echo e($success); ?>
                <br>
                <a href="login.php" style="font-weight:700;">
                    Click here to Login →
                </a>
            </div>
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

        <!-- Form -->
        <form method="POST"
              action="register.php"
              id="registerForm"
              novalidate>

            <!-- Full Name -->
            <div class="form-group">
                <label for="name" class="required">
                    Full Name
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       class="form-control <?php echo !empty($errors) && empty($_POST['name']) ? 'is-invalid' : ''; ?>"
                       placeholder="e.g. Rahul Sharma"
                       value="<?php echo isset($_POST['name']) ? e($_POST['name']) : ''; ?>"
                       required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email" class="required">
                    Email Address
                </label>
                <input type="email"
                       name="email"
                       id="email"
                       class="form-control"
                       placeholder="e.g. rahul@email.com"
                       value="<?php echo isset($_POST['email']) ? e($_POST['email']) : ''; ?>"
                       required>
            </div>

            <!-- Phone -->
            <div class="form-group">
                <label for="phone" class="required">
                    Phone Number
                </label>
                <input type="tel"
                       name="phone"
                       id="phone"
                       class="form-control"
                       placeholder="10-digit mobile number"
                       value="<?php echo isset($_POST['phone']) ? e($_POST['phone']) : ''; ?>"
                       maxlength="10"
                       required>
            </div>

            <!-- Role -->
            <div class="form-group">
                <label for="role" class="required">
                    I am a
                </label>
                <select name="role"
                        id="role"
                        class="form-control">
                    <option value="buyer"
                        <?php echo (($_POST['role'] ?? 'buyer') === 'buyer') ? 'selected' : ''; ?>>
                        🔍 Buyer — Looking for a property
                    </option>
                    <option value="seller"
                        <?php echo (($_POST['role'] ?? '') === 'seller') ? 'selected' : ''; ?>>
                        🏠 Seller — Want to list a property
                    </option>
                </select>
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
                           placeholder="Minimum 6 characters"
                           required>
                    <button type="button"
                            class="pass-toggle"
                            data-target="password">👁️</button>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="confirm_password" class="required">
                    Confirm Password
                </label>
                <div class="pass-wrap">
                    <input type="password"
                           name="confirm_password"
                           id="confirm_password"
                           class="form-control"
                           placeholder="Re-enter your password"
                           required>
                    <button type="button"
                            class="pass-toggle"
                            data-target="confirm_password">👁️</button>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="btn btn-primary btn-full btn-lg">
                🚀 Create Account
            </button>

        </form>

        <div class="auth-footer">
            Already have an account?
            <a href="login.php">Login Here</a>
        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>