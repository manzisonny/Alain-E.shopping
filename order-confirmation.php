<?php
// order-confirmation.php
require_once 'includes/header.php';

restrict_to_roles(['customer'], 'login.php');

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$current_user = get_current_user_details();

// Fetch order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND customer_id = ?");
$stmt->bind_param("ii", $order_id, $current_user['id']);
$stmt->execute();
$order_res = $stmt->get_result();

if ($order_res->num_rows === 0) {
    $_SESSION['flash_message'] = "Order not found or access denied.";
    $_SESSION['flash_type'] = "danger";
    header("Location: index.php");
    exit;
}

$order = $order_res->fetch_assoc();
$stmt->close();

// Fetch order items
$items_stmt = $conn->prepare("SELECT oi.*, p.name as product_name, p.is_digital, p.file_url, p.image_url 
                              FROM order_items oi 
                              LEFT JOIN products p ON oi.product_id = p.id 
                              WHERE oi.order_id = ?");
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items_res = $items_stmt->get_result();
?>

<div class="container py-5 mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="text-center mb-5 animate-fade-in">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle border border-success mb-4" style="width: 120px; height: 120px; background: linear-gradient(135deg, rgba(11, 163, 96, 0.2) 0%, rgba(60, 186, 146, 0.2) 100%);">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                </div>
                <h1 class="text-white font-heading mb-3">Order Confirmed!</h1>
                <p class="text-secondary lead">Thank you for your purchase. Your order has been successfully placed.</p>
            </div>

            <!-- Order Details Card -->
            <div class="card-glass p-5 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-4 border-bottom border-secondary">
                    <div>
                        <h4 class="text-white font-heading mb-2">Order #<?php echo $order['id']; ?></h4>
                        <p class="text-secondary small mb-0">Placed on <?php echo date("F d, Y \a\\t H:i", strtotime($order['created_at'])); ?></p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-warning text-dark py-2 px-3 mb-2 d-inline-block">
                            <?php echo strtoupper($order['status']); ?>
                        </span>
                        <div class="text-gradient-primary fw-bold font-heading fs-3"><?php echo format_price($order['total_amount']); ?></div>
                    </div>
                </div>

                <!-- Order Items -->
                <h5 class="text-white font-heading mb-3"><i class="bi bi-box-seam me-2 text-info"></i>Order Items</h5>
                <div class="table-responsive">
                    <table class="table table-dark table-premium mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($item = $items_res->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="rounded" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.src='<?php echo BASE_URL; ?>/assets/img/placeholder.svg'">
                                            <div>
                                                <div class="text-white fw-bold"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                                <?php if ($item['is_digital']): ?>
                                                    <span class="badge bg-info" style="font-size: 0.7rem;">Digital Download</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center"><?php echo format_price($item['price']); ?></td>
                                    <td class="text-center"><?php echo $item['quantity']; ?></td>
                                    <td class="text-end"><?php echo format_price($item['price'] * $item['quantity']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold text-white">TOTAL:</td>
                                <td class="text-end fw-bold text-gradient-primary fs-5"><?php echo format_price($order['total_amount']); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php $items_stmt->close(); ?>
            </div>

            <!-- Payment & Shipping Info -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card-glass p-4 h-100">
                        <h6 class="text-white font-heading mb-3"><i class="bi bi-credit-card-fill text-info me-2"></i>Payment Method</h6>
                        <p class="text-secondary mb-0"><?php echo htmlspecialchars($order['payment_method']); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-glass p-4 h-100">
                        <h6 class="text-white font-heading mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Shipping Address</h6>
                        <p class="text-secondary mb-0"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card-glass p-4 text-center">
                <h5 class="text-white font-heading mb-3">What's Next?</h5>
                <p class="text-secondary mb-4">Track your order status, download digital products, or continue shopping.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="<?php echo BASE_URL; ?>/customer/dashboard.php" class="btn btn-premium"><i class="bi bi-speedometer2 me-2"></i>View Dashboard</a>
                    <a href="<?php echo BASE_URL; ?>/shop.php" class="btn btn-premium-secondary"><i class="bi bi-bag-heart me-2"></i>Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
