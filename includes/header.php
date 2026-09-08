<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
$currentUser = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NestFinder — India's trusted online real estate portal. Buy, sell or rent properties.">
    <title>NestFinder — Online Real Estate Portal</title>
    <link rel="stylesheet" href="/real-estate-portal/assets/css/style.css">
</head>
<body>

<!-- ═══════════════════════════════════════
     NAVIGATION BAR
════════════════════════════════════════ -->
<nav class="navbar">
    <div class="container nav-inner">

        <!-- LOGO -->
        <a href="/real-estate-portal/index.php" class="nav-logo">
            <div class="nav-logo-icon">🏠</div>
            Nest<span>Finder</span>
        </a>

        <!-- NAV LINKS -->
        <ul class="nav-links" id="navLinks">
            <li>
                <a href="/real-estate-portal/index.php">
                    Home
                </a>
            </li>
            <li>
                <a href="/real-estate-portal/properties.php">
                    Properties
                </a>
            </li>
            <li>
                <a href="/real-estate-portal/properties.php?listing_type=Sale">
                    Buy
                </a>
            </li>
            <li>
                <a href="/real-estate-portal/properties.php?listing_type=Rent">
                    Rent
                </a>
            </li>
            <?php if(isLoggedIn() && isAdminOrSeller()): ?>
            <li>
                <a href="/real-estate-portal/add-property.php">
                    Add Property
                </a>
            </li>
            <?php endif; ?>
        </ul>

        <!-- NAV ACTIONS -->
        <div class="nav-actions">

            <?php if(isLoggedIn()): ?>

                <!-- Logged In User Menu -->
                <div class="nav-user-menu">
                    <button class="nav-user-btn">
                        👤 <?php echo e($currentUser['name']); ?> ▾
                    </button>
                    <div class="nav-dropdown">
                        <a href="/real-estate-portal/dashboard.php">
                            📊 Dashboard
                        </a>

                        <?php if(hasRole('seller') || hasRole('admin')): ?>
                        <a href="/real-estate-portal/my-properties.php">
                            🏠 My Properties
                        </a>
                        <a href="/real-estate-portal/add-property.php">
                            ➕ Add Property
                        </a>
                        <?php endif; ?>

                        <?php if(hasRole('buyer') || hasRole('admin')): ?>
                        <a href="/real-estate-portal/my-inquiries.php">
                            📩 My Inquiries
                        </a>
                        <?php endif; ?>

                        <?php if(hasRole('admin')): ?>
                        <a href="/real-estate-portal/admin-users.php">
                            👥 Manage Users
                        </a>
                        <a href="/real-estate-portal/admin-properties.php">
                            🏘️ All Properties
                        </a>
                        <a href="/real-estate-portal/admin-inquiries.php">
                            📋 All Inquiries
                        </a>
                        <?php endif; ?>

                        <a href="/real-estate-portal/logout.php">
                            🚪 Logout
                        </a>
                    </div>
                </div>

            <?php else: ?>

                <!-- Guest Buttons -->
                <a href="/real-estate-portal/login.php"
                   class="btn btn-outline btn-sm">
                    Login
                </a>
                <a href="/real-estate-portal/register.php"
                   class="btn btn-primary btn-sm">
                    Register
                </a>

            <?php endif; ?>

            <!-- HAMBURGER BUTTON -->
            <button class="hamburger"
                    id="hamburger"
                    aria-label="Toggle Navigation"
                    aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>

        </div>
    </div>
</nav>

<!-- Flash Messages -->
<?php
$flashSuccess = getFlash('success');
$flashError   = getFlash('error');
?>
<?php if($flashSuccess): ?>
<div class="container mt-2">
    <div class="alert alert-success" data-autodismiss="4000">
        <span class="alert-icon">✅</span>
        <?php echo e($flashSuccess); ?>
    </div>
</div>
<?php endif; ?>

<?php if($flashError): ?>
<div class="container mt-2">
    <div class="alert alert-danger" data-autodismiss="4000">
        <span class="alert-icon">❌</span>
        <?php echo e($flashError); ?>
    </div>
</div>
<?php endif; ?>