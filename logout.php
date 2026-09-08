<?php
require_once 'includes/auth.php';

// Destroy session completely
session_unset();
session_destroy();

// Redirect to login
header('Location: login.php?msg=logged_out');
exit;