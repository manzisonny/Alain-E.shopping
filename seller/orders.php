<?php
// seller/orders.php
require_once __DIR__ . '/../includes/header.php';

restrict_to_roles(['seller'], 'login.php');

$current_user = get_current_user_details();

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $order_id = (int)$_POST['order_id'];
    $new_status = $_POST['status'];
    
    if (in_array($new_status, ['pending', 'processing', 'completed', 'cancelled'])) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        if ($stmt->execute()) {
            $_SESSION['flash_message'] = "Order status updated successfully!";
            $_SESSION['flash_type'] = "success";
        }
        $stmt->close();
        header("Location: orders.php");
        exit;
    }
}

// Fetch orders containing this seller's products
$query = "SELECT DISTINCT o.*, u.name as customer_name, u.email as customer_email 
          FROM orders o 
          INNER JOIN order_items oi ON o.id = oi.order_id 
          INNER JOIN products p ON oi.product_id = p.id 
          INNER JOIN users u ON o.customer_id = u.id 
          WHERE p.seller_id = ? 
          ORDER BY o.id DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $current_user['id']);
$stmt->execute();
$orders_res = $stmt->get_result();
$stmt->close();

// Note: header.php already included above - do NOT include it again
?>

<div class="container py-5 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-white mb-1"><i class="bi bi-receipt-cutoff text-gradient-primary me-2"></i>Incoming Orders</h2>
            <p class="text-secondary small mb-0">Manage customer orders containing your products</p>
        </div>
        <a href="<?php echo BASE_URL; ?>/seller/dashboard.php" class="btn btn-premium-secondary"><i class="bi bi-arrow-left me-2"></i>Back to Dashboard</a>
    </div>

    <?php if ($orders_res && $orders_res->num_rows > 0): ?>
        <div class="d-flex flex-column gap-4">
            <?php while ($order = $orders_res->fetch_assoc()): ?>
                <div class="card-glass p-4">
                    <!-- Order Header -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-3 border-bottom border-secondary">
                        <div>
                            <h5 class="text-white font-heading mb-1">Order #<?php echo $order['id']; ?></h5>
                            <p class="text-secondary small mb-0">
                                <i class="bi bi-person me-1"></i><?php echo htmlspecialchars($order['customer_name']); ?> 
                                (<a href="mailto:<?php echo htmlspecialchars($order['customer_email']); ?>" class="text-info"><?php echo htmlspecialchars($order['customer_email']); ?></a>)
                            </p>
                            <p class="text-secondary small mb-0"><i class="bi bi-calendar me-1"></i><?php echo date("M d, Y H:i", strtotime($order['created_at'])); ?></p>
                        </div>
                        <div class="text-end">
                            <span class="badge <?php 
                                if ($order['status'] === 'pending') echo 'bg-warning text-dark';
                                elseif ($order['status'] === 'processing') echo 'bg-info';
                                elseif ($order['status'] === 'completed') echo 'bg-success';
                                else echo 'bg-danger';
                            ?> py-2 px-3 mb-2 d-inline-block">
                                <?php echo strtoupper($order['status']); ?>
                            </span>
                            <div class="text-gradient-primary fw-bold fs-5"><?php echo format_price($order['total_amount']); ?></div>
                        </div>
                    </div>

                    <!-- Order Items (Filter only this seller's products) -->
                    <?php
                    $items_stmt = $conn->prepare("SELECT oi.*, p.name as product_name, p.image_url 
                                                  FROM order_items oi 
                                                  INNER JOIN products p ON oi.product_id = p.id 
                                                  WHERE oi.order_id = ? AND p.seller_id = ?");
                    $items_stmt->bind_param("ii", $order['id'], $current_user['id']);
                    $items_stmt->execute();
                    $items_res = $items_stmt->get_result();
                    ?>

                    <div class="table-responsive mb-3">
                        <table class="table table-dark table-premium mb-0 small">
                            <thead>
                                <tr>
                                    <th>Your Products in this Order</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($item = $items_res->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($item['image_url']); ?>" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                                <span class="text-white"><?php echo htmlspecialchars($item['product_name']); ?></span>
                                            </div>
                                        </td>
                                        <td class="text-center"><?php echo format_price($item['price']); ?></td>
                                        <td class="text-center"><?php echo $item['quantity']; ?></td>
                                        <td class="text-end"><?php echo format_price($item['price'] * $item['quantity']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php $items_stmt->close(); ?>

                    <!-- Shipping Address & Status Update -->
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <h6 class="text-white small mb-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Shipping Address:</h6>
                            <p class="text-secondary small mb-0"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                        </div>
                        <div class="col-md-4">
                            <form action="orders.php" method="POST" class="d-flex gap-2">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <select name="status" class="form-select form-glass-input form-select-sm">
                                    <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <button type="submit" class="btn btn-premium btn-sm px-3">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="card-glass p-5 text-center">
            <i class="bi bi-inbox text-secondary" style="font-size: 3rem;"></i>
            <h5 class="text-white mt-3">No Orders Yet</h5>
            <p class="text-secondary mb-0">Orders containing your products will appear here once customers start purchasing.</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
