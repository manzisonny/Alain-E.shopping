<?php
// admin/dashboard.php
require_once __DIR__ . '/../includes/header.php';

restrict_to_roles(['admin'], 'login.php');

$current_user = get_current_user_details();

// ==========================================
// HANDLE POST ACTIONS
// ==========================================

// 1. Seller Approval/Rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'compliance_decide') {
        $seller_id = (int)$_POST['seller_id'];
        $decision  = $_POST['decision'];

        if (in_array($decision, ['approved', 'rejected'])) {
            $stmt_up = $conn->prepare("UPDATE users SET status = ? WHERE id = ? AND role = 'seller'");
            $stmt_up->bind_param("si", $decision, $seller_id);
            if ($stmt_up->execute()) {
                $_SESSION['flash_message'] = "Seller account #$seller_id has been $decision!";
                $_SESSION['flash_type'] = $decision === 'approved' ? 'success' : 'warning';
            }
            $stmt_up->close();
        }
        header("Location: " . BASE_URL . "/admin/dashboard.php");
        exit;
    }

    // 2. Delete User (seller or customer)
    if ($_POST['action'] === 'delete_user') {
        $user_id = (int)$_POST['user_id'];
        $stmt_del = $conn->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
        $stmt_del->bind_param("i", $user_id);
        if ($stmt_del->execute()) {
            $_SESSION['flash_message'] = "User #$user_id removed from the system.";
            $_SESSION['flash_type'] = "info";
        }
        $stmt_del->close();
        header("Location: " . BASE_URL . "/admin/dashboard.php");
        exit;
    }

    // 3. Update Order Status
    if ($_POST['action'] === 'update_order_status') {
        $order_id   = (int)$_POST['order_id'];
        $new_status = $_POST['status'];
        if (in_array($new_status, ['pending', 'processing', 'completed', 'cancelled'])) {
            $stmt_ord = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt_ord->bind_param("si", $new_status, $order_id);
            if ($stmt_ord->execute()) {
                $_SESSION['flash_message'] = "Order #$order_id status updated to $new_status.";
                $_SESSION['flash_type'] = "success";
            }
            $stmt_ord->close();
        }
        header("Location: " . BASE_URL . "/admin/dashboard.php#orders-section");
        exit;
    }
}

// ==========================================
// STATS
// ==========================================
$total_customers = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'customer'")->fetch_assoc()['count'];
$total_sellers   = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'seller'")->fetch_assoc()['count'];
$total_products  = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$total_orders    = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$total_revenue   = $conn->query("SELECT COALESCE(SUM(total_amount),0) as total FROM orders WHERE status != 'cancelled'")->fetch_assoc()['total'];

// ==========================================
// DATA FETCHING
// ==========================================
// Sellers list
$sellers_res = $conn->query("SELECT * FROM users WHERE role = 'seller' ORDER BY id DESC");

// Customers list
$customers_res = $conn->query("SELECT * FROM users WHERE role = 'customer' ORDER BY id DESC LIMIT 15");

// All Orders (with customer info)
$orders_res = $conn->query("
    SELECT o.*, u.name as customer_name, u.email as customer_email
    FROM orders o
    LEFT JOIN users u ON o.customer_id = u.id
    ORDER BY o.id DESC
    LIMIT 20
");

// Latest Products
$products_res = $conn->query("
    SELECT p.*, s.name as seller_name
    FROM products p
    LEFT JOIN users s ON p.seller_id = s.id
    ORDER BY p.id DESC LIMIT 8
");
?>

<div class="container-fluid py-5 mt-4">
    <div class="container">
        <!-- Page Title -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="text-white mb-1"><i class="bi bi-speedometer2 text-gradient-primary me-2"></i>Admin Control Center</h2>
                <p class="text-secondary mb-0">Manage sellers, customers, orders and platform health.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?php echo BASE_URL; ?>/admin/users.php" class="btn btn-premium-secondary btn-sm"><i class="bi bi-people-fill me-1"></i>Manage Users</a>
                <a href="<?php echo BASE_URL; ?>/admin/products.php" class="btn btn-premium-secondary btn-sm"><i class="bi bi-box-seam me-1"></i>Manage Products</a>
                <a href="<?php echo BASE_URL; ?>/shop.php" class="btn btn-premium-secondary btn-sm"><i class="bi bi-shop me-1"></i>View Shop</a>
                <a href="<?php echo BASE_URL; ?>/logout.php" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Sign Out</a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-xl col-md-4 col-6">
                <div class="stats-card text-center">
                    <i class="bi bi-person-lines-fill text-success fs-2 mb-3 d-block"></i>
                    <h3 class="text-white font-heading mb-1"><?php echo number_format($total_customers); ?></h3>
                    <p class="text-secondary small mb-0">Total Customers</p>
                </div>
            </div>
            <div class="col-xl col-md-4 col-6">
                <div class="stats-card text-center">
                    <i class="bi bi-shop fs-2 mb-3 d-block" style="color: #00d9ff;"></i>
                    <h3 class="text-white font-heading mb-1"><?php echo number_format($total_sellers); ?></h3>
                    <p class="text-secondary small mb-0">Registered Sellers</p>
                </div>
            </div>
            <div class="col-xl col-md-4 col-6">
                <div class="stats-card text-center">
                    <i class="bi bi-box-seam text-warning fs-2 mb-3 d-block"></i>
                    <h3 class="text-white font-heading mb-1"><?php echo number_format($total_products); ?></h3>
                    <p class="text-secondary small mb-0">Products Listed</p>
                </div>
            </div>
            <div class="col-xl col-md-6 col-6">
                <div class="stats-card text-center">
                    <i class="bi bi-cart-check-fill text-danger fs-2 mb-3 d-block"></i>
                    <h3 class="text-white font-heading mb-1"><?php echo number_format($total_orders); ?></h3>
                    <p class="text-secondary small mb-0">Total Orders</p>
                </div>
            </div>
            <div class="col-xl col-md-6 col-12">
                <div class="stats-card text-center">
                    <i class="bi bi-graph-up-arrow text-success fs-2 mb-3 d-block"></i>
                    <h3 class="text-white font-heading mb-1"><?php echo format_price($total_revenue); ?></h3>
                    <p class="text-secondary small mb-0">Total Revenue</p>
                </div>
            </div>
        </div>

        <!-- Main Panels -->
        <div class="row gy-5">

            <!-- ===== SELLERS COMPLIANCE DESK ===== -->
            <div class="col-12">
                <div class="card-glass p-4">
                    <h4 class="text-white mb-4 font-heading"><i class="bi bi-patch-check-fill text-info me-2"></i>Sellers Compliance Desk</h4>

                    <?php if ($sellers_res && $sellers_res->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-premium mb-0 small">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Seller Identity</th>
                                        <th>Location</th>
                                        <th>Documents</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($sel = $sellers_res->fetch_assoc()): ?>
                                        <tr>
                                            <td class="text-secondary">#<?php echo $sel['id']; ?></td>
                                            <td>
                                                <strong class="text-white"><?php echo htmlspecialchars($sel['name']); ?></strong><br>
                                                <span class="text-secondary"><?php echo htmlspecialchars($sel['email']); ?></span>
                                            </td>
                                            <td><i class="bi bi-geo-alt text-danger me-1"></i><?php echo htmlspecialchars($sel['seller_location'] ?? 'Not set'); ?></td>
                                            <td>
                                                <?php if ($sel['seller_documents']): ?>
                                                    <a href="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($sel['seller_documents']); ?>" class="btn btn-sm btn-outline-info border-0 py-1 px-2" target="_blank">
                                                        <i class="bi bi-file-earmark-pdf-fill text-danger me-1"></i>View Doc
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted small">No upload</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge <?php
                                                    if ($sel['status'] === 'pending') echo 'bg-warning text-dark';
                                                    elseif ($sel['status'] === 'approved') echo 'bg-success';
                                                    else echo 'bg-danger';
                                                ?>">
                                                    <?php echo strtoupper($sel['status']); ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <?php if ($sel['status'] !== 'approved'): ?>
                                                        <form action="dashboard.php" method="POST" class="d-inline">
                                                            <input type="hidden" name="action" value="compliance_decide">
                                                            <input type="hidden" name="seller_id" value="<?php echo $sel['id']; ?>">
                                                            <input type="hidden" name="decision" value="approved">
                                                            <button type="submit" class="btn btn-sm btn-success py-1 px-2"><i class="bi bi-check-circle-fill"></i> Approve</button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <?php if ($sel['status'] !== 'rejected'): ?>
                                                        <form action="dashboard.php" method="POST" class="d-inline">
                                                            <input type="hidden" name="action" value="compliance_decide">
                                                            <input type="hidden" name="seller_id" value="<?php echo $sel['id']; ?>">
                                                            <input type="hidden" name="decision" value="rejected">
                                                            <button type="submit" class="btn btn-sm btn-warning text-dark py-1 px-2"><i class="bi bi-x-circle-fill"></i> Reject</button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <form action="dashboard.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this seller and all their products?')">
                                                        <input type="hidden" name="action" value="delete_user">
                                                        <input type="hidden" name="user_id" value="<?php echo $sel['id']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 py-1 px-2"><i class="bi bi-trash3-fill"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-shop text-secondary" style="font-size: 2.5rem;"></i>
                            <p class="text-secondary mt-3 mb-0">No sellers registered yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ===== ORDERS MANAGEMENT ===== -->
            <div class="col-12" id="orders-section">
                <div class="card-glass p-4">
                    <h4 class="text-white mb-4 font-heading"><i class="bi bi-receipt-cutoff text-warning me-2"></i>Orders Management</h4>

                    <?php if ($orders_res && $orders_res->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-premium mb-0 small">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th class="text-center">Amount</th>
                                        <th>Payment</th>
                                        <th>Date</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-end">Update Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($ord = $orders_res->fetch_assoc()): ?>
                                        <tr>
                                            <td class="text-white fw-bold">#<?php echo $ord['id']; ?></td>
                                            <td>
                                                <div class="text-white"><?php echo htmlspecialchars($ord['customer_name'] ?? 'Unknown'); ?></div>
                                                <div class="text-secondary" style="font-size:0.78rem;"><?php echo htmlspecialchars($ord['customer_email'] ?? ''); ?></div>
                                            </td>
                                            <td class="text-center fw-bold text-white"><?php echo format_price($ord['total_amount']); ?></td>
                                            <td class="text-secondary"><?php echo htmlspecialchars($ord['payment_method']); ?></td>
                                            <td class="text-secondary"><?php echo date("M d, Y", strtotime($ord['created_at'])); ?></td>
                                            <td class="text-center">
                                                <span class="badge <?php
                                                    if ($ord['status'] === 'pending') echo 'bg-warning text-dark';
                                                    elseif ($ord['status'] === 'processing') echo 'bg-info';
                                                    elseif ($ord['status'] === 'completed') echo 'bg-success';
                                                    else echo 'bg-danger';
                                                ?>">
                                                    <?php echo strtoupper($ord['status']); ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <form action="dashboard.php" method="POST" class="d-flex gap-1 justify-content-end">
                                                    <input type="hidden" name="action" value="update_order_status">
                                                    <input type="hidden" name="order_id" value="<?php echo $ord['id']; ?>">
                                                    <select name="status" class="form-select form-glass-input form-select-sm" style="width: auto; min-width: 120px;">
                                                        <option value="pending" <?php echo $ord['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="processing" <?php echo $ord['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                                        <option value="completed" <?php echo $ord['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                        <option value="cancelled" <?php echo $ord['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-premium px-2">Update</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-inbox text-secondary" style="font-size: 2.5rem;"></i>
                            <p class="text-secondary mt-3 mb-0">No orders have been placed yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ===== CUSTOMERS LIST ===== -->
            <div class="col-xl-7">
                <div class="card-glass p-4 h-100">
                    <h4 class="text-white mb-4 font-heading"><i class="bi bi-people-fill text-success me-2"></i>Registered Customers</h4>

                    <?php if ($customers_res && $customers_res->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-premium mb-0 small">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer</th>
                                        <th>Joined</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($cust = $customers_res->fetch_assoc()): ?>
                                        <tr>
                                            <td class="text-secondary"><?php echo $cust['id']; ?></td>
                                            <td>
                                                <div class="text-white"><?php echo htmlspecialchars($cust['name']); ?></div>
                                                <div class="text-secondary" style="font-size:0.78rem;"><?php echo htmlspecialchars($cust['email']); ?></div>
                                            </td>
                                            <td class="text-secondary"><?php echo date("M d, Y", strtotime($cust['created_at'])); ?></td>
                                            <td class="text-end">
                                                <form action="dashboard.php" method="POST" class="d-inline" onsubmit="return confirm('Remove this customer?')">
                                                    <input type="hidden" name="action" value="delete_user">
                                                    <input type="hidden" name="user_id" value="<?php echo $cust['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0 py-1 px-2"><i class="bi bi-trash3-fill"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-secondary mb-0">No customers registered yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ===== LATEST PRODUCTS ===== -->
            <div class="col-xl-5">
                <div class="card-glass p-4 h-100">
                    <h4 class="text-white mb-4 font-heading"><i class="bi bi-box-seam text-warning me-2"></i>Latest Products</h4>

                    <?php if ($products_res && $products_res->num_rows > 0): ?>
                        <div class="d-flex flex-column gap-3">
                            <?php while ($prod = $products_res->fetch_assoc()): ?>
                                <div class="d-flex align-items-center gap-3 p-2 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                                    <img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($prod['image_url']); ?>" 
                                         class="rounded product-thumb-sm" 
                                         onerror="this.src='<?php echo BASE_URL; ?>/assets/img/placeholder.svg'"
                                         alt="Product">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="text-white fw-bold text-truncate" style="font-size: 0.875rem;"><?php echo htmlspecialchars($prod['name']); ?></div>
                                        <div class="text-secondary" style="font-size: 0.78rem;">by <?php echo htmlspecialchars($prod['seller_name']); ?></div>
                                    </div>
                                    <span class="badge bg-primary text-nowrap"><?php echo format_price($prod['price']); ?></span>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-secondary mb-0">No products uploaded yet.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
