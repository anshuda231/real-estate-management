<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function currentUser(): ?array
{
    if (!isLoggedIn()) return null;
    return [
        'user_id' => $_SESSION['user_id'],
        'name'    => $_SESSION['user_name']  ?? '',
        'email'   => $_SESSION['user_email'] ?? '',
        'role'    => $_SESSION['user_role']  ?? 'buyer',
    ];
}

function hasRole(string $role): bool
{
    if (!isLoggedIn()) return false;
    return ($_SESSION['user_role'] ?? '') === $role;
}

function isAdminOrSeller(): bool
{
    return hasRole('admin') || hasRole('seller');
}

function requireLogin(string $redirect = 'login.php'): void
{
    if (!isLoggedIn()) {
        header('Location: ' . $redirect . '?msg=login_required');
        exit;
    }
}

function requireRole(string $role, string $redirect = 'dashboard.php'): void
{
    requireLogin();
    if (hasRole('admin')) return;
    if (!hasRole($role)) {
        header('Location: ' . $redirect . '?msg=unauthorized');
        exit;
    }
}

function requireAdmin(string $redirect = 'dashboard.php'): void
{
    requireLogin();
    if (!hasRole('admin')) {
        header('Location: ' . $redirect . '?msg=unauthorized');
        exit;
    }
}

function canEditProperty(array $property): bool
{
    if (!isLoggedIn()) return false;
    if (hasRole('admin')) return true;
    return (int)($_SESSION['user_id']) === (int)($property['user_id']);
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function setFlash(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function getFlash(string $key): ?string
{
    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function formatPrice(float $price, string $type = 'Sale'): string
{
    if ($type === 'Rent') {
        return '₹' . number_format($price, 0) . '/mo';
    }
    if ($price >= 10000000) {
        return '₹' . number_format($price / 10000000, 2) . ' Cr';
    }
    if ($price >= 100000) {
        return '₹' . number_format($price / 100000, 2) . ' Lakh';
    }
    return '₹' . number_format($price, 0);
}
