<?php
// includes/auth.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if the user is logged in.
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current logged in user details.
 */
function get_current_user_details() {
    if (is_logged_in()) {
        return [
            'id'               => $_SESSION['user_id'],
            'name'             => $_SESSION['user_name'],
            'email'            => $_SESSION['user_email'],
            'role'             => $_SESSION['user_role'],
            'status'           => $_SESSION['user_status'] ?? 'approved',
            'location'         => $_SESSION['seller_location'] ?? null,
            'seller_documents' => $_SESSION['seller_documents'] ?? null,
            'created_at'       => $_SESSION['user_created_at'] ?? null,
        ];
    }
    return null;
}

/**
 * Restrict page access to specific roles.
 * Uses BASE_URL for cross-directory safe redirects.
 *
 * @param array  $allowed_roles
 * @param string $redirect_to  relative path from project root (e.g. 'login.php')
 */
function restrict_to_roles($allowed_roles, $redirect_to = 'login.php') {
    // Build an absolute redirect URL using BASE_URL
    $base = defined('BASE_URL') ? BASE_URL : '';
    // Strip leading slashes/dots from redirect_to
    $redirect_to = ltrim($redirect_to, './\\');

    if (!is_logged_in()) {
        $_SESSION['flash_message'] = "Please login to access this page.";
        $_SESSION['flash_type'] = "warning";
        header("Location: $base/$redirect_to");
        exit;
    }

    $user = get_current_user_details();
    if (!in_array($user['role'], $allowed_roles)) {
        $_SESSION['flash_message'] = "Unauthorized access level.";
        $_SESSION['flash_type'] = "danger";

        // Redirect to their respective dashboards
        if ($user['role'] === 'admin') {
            header("Location: $base/admin/dashboard.php");
        } elseif ($user['role'] === 'seller') {
            header("Location: $base/seller/dashboard.php");
        } else {
            header("Location: $base/customer/dashboard.php");
        }
        exit;
    }

    // If seller is pending approval, add restriction flag
    if ($user['role'] === 'seller' && $user['status'] !== 'approved') {
        $_SESSION['seller_pending_warning'] = true;
    }
}

/**
 * Add an item to the shopping cart.
 */
function cart_add($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

/**
 * Remove an item from the cart.
 */
function cart_remove($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Get total number of items in the cart.
 */
function cart_count() {
    if (!isset($_SESSION['cart'])) {
        return 0;
    }
    return array_sum($_SESSION['cart']);
}

/**
 * Format currency.
 */
function format_price($price) {
    return '$' . number_format($price, 2);
}

/**
 * Get average rating for a product.
 */
function get_avg_rating($conn, $product_id) {
    $stmt = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM comments WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return [
        'avg'   => round($res['avg_rating'] ?? 0, 1),
        'total' => (int)($res['total'] ?? 0)
    ];
}
?>
