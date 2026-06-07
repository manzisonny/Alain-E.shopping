<?php
// admin/products.php
require_once __DIR__ . '/../includes/header.php';

restrict_to_roles(['admin'], 'login.php');

$current_user = get_current_user_details();

// ==========================================
// HANDLE POST ACTIONS
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    // Delete Product
    if ($_POST['action'] === 'delete_product') {
        $product_id = (int)$_POST['product_id'];
        // Also remove image file
        $res = $conn->query("SELECT image_url FROM products WHERE id = $product_id");
        if ($res && $row = $res->fetch_assoc()) {
            $img = __DIR__ . '/../' . $row['image_url'];
            if (file_exists($img)) @unlink($img);
        }
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        if ($stmt->execute()) {
            $_SESSION['flash_message'] = "Product #$product_id removed.";
            $_SESSION['flash_type'] = "info";
        }
        $stmt->close();
        header("Location: " . BASE_URL . "/admin/products.php");
        exit;
    }
}

// ==========================================
// SEARCH & FILTER
// ==========================================
$search  = trim($_GET['search'] ?? '');
$cat_id  = (int)($_GET['category'] ?? 0);
$type    = $_GET['type'] ?? '';    // digital | physical

$where_parts = [];
$params      = [];
$param_types = '';

if ($search !== '') {
    $where_parts[] = "(p.name LIKE ? OR p.description LIKE ?)";
    $like = "%$search%";
    $params[] = $like;
    $params[] = $like;
    $param_types .= 'ss';
}
if ($cat_id > 0) {
    $where_parts[] = "p.category_id = ?";
    $params[]      = $cat_id;
    $param_types  .= 'i';
}
if ($type === 'digital') {
    $where_parts[] = "p.is_digital = 1";
} elseif ($type === 'physical') {
    $where_parts[] = "p.is_digital = 0";
}

$where_sql = $where_parts ? 'WHERE ' . implode(' AND ', $where_parts) : '';

$sql = "SELECT p.*, c.name as category_name, s.name as seller_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN users s ON p.seller_id = s.id
        $where_sql
        ORDER BY p.id DESC";

if ($params) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($param_types, ...$params);
    $stmt->execute();
    $products_res = $stmt->get_result();
    $stmt->close();
} else {
    $products_res = $conn->query($sql);
}

// Totals for stats
$total_products  = $conn->query("SELECT COUNT(*) as c FROM products")->fetch_assoc()['c'];
$total_digital   = $conn->query("SELECT COUNT(*) as c FROM products WHERE is_digital = 1")->fetch_assoc()['c'];
$total_physical  = $total_products - $total_digital;
$avg_price       = $conn->query("SELECT ROUND(AVG(price),2) as a FROM products")->fetch_assoc()['a'];

// Categories for filter dropdown
$cats_res = $conn->query("SELECT * FROM categories ORDER BY name ASC");
?>

<div class="container-fluid py-5 mt-4">
    <div class="container">

        <!-- Page Title -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-5 gap-3">
            <div>
                <h2 class="text-white mb-1"><i class="bi bi-box-seam text-gradient-primary me-2"></i>Product Management</h2>
                <p class="text-secondary mb-0">View, search, and manage all marketplace products across all sellers.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="btn btn-premium-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Admin Panel</a>
                <a href="<?php echo BASE_URL; ?>/shop.php" class="btn btn-premium btn-sm" target="_blank"><i class="bi bi-shop me-1"></i>View Shop</a>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="row g-4 mb-5">
            <div class="col-md-3 col-6">
                <div class="stats-card text-center">
                    <i class="bi bi-box-seam text-warning fs-2 mb-3 d-block"></i>
                    <h3 class="text-white font-heading mb-1"><?php echo number_format($total_products); ?></h3>
                    <p class="text-secondary small mb-0">Total Products</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card text-center">
                    <i class="bi bi-cloud-download-fill fs-2 mb-3 d-block" style="color: #00d9ff;"></i>
                    <h3 class="text-white font-heading mb-1"><?php echo number_format($total_digital); ?></h3>
                    <p class="text-secondary small mb-0">Digital Products</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card text-center">
                    <i class="bi bi-truck fs-2 mb-3 d-block text-success"></i>
                    <h3 class="text-white font-heading mb-1"><?php echo number_format($total_physical); ?></h3>
                    <p class="text-secondary small mb-0">Physical Products</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card text-center">
                    <i class="bi bi-currency-dollar fs-2 mb-3 d-block text-danger"></i>
                    <h3 class="text-white font-heading mb-1">$<?php echo number_format($avg_price, 2); ?></h3>
                    <p class="text-secondary small mb-0">Avg. Price</p>
                </div>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="card-glass p-4 mb-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-glass-label">Search Products</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search text-secondary"></i></span>
                        <input type="text" name="search" class="form-control form-glass-input" placeholder="Product name or description..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-glass-label">Category</label>
                    <select name="category" class="form-select form-glass-input">
                        <option value="0">All Categories</option>
                        <?php while ($cat = $cats_res->fetch_assoc()): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $cat_id == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-glass-label">Type</label>
                    <select name="type" class="form-select form-glass-input">
                        <option value="">All Types</option>
                        <option value="digital" <?php echo $type === 'digital' ? 'selected' : ''; ?>>Digital</option>
                        <option value="physical" <?php echo $type === 'physical' ? 'selected' : ''; ?>>Physical</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-premium w-100"><i class="bi bi-funnel-fill me-1"></i>Filter</button>
                </div>
            </form>
            <?php if ($search || $cat_id || $type): ?>
                <div class="mt-3">
                    <a href="products.php" class="btn btn-sm btn-outline-secondary border-secondary text-secondary"><i class="bi bi-x-circle me-1"></i>Clear Filters</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Products Table -->
        <div class="card-glass p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-white font-heading mb-0"><i class="bi bi-list-ul me-2 text-warning"></i>All Marketplace Products</h4>
                <span class="badge bg-secondary px-3 py-2"><?php echo $products_res ? $products_res->num_rows : 0; ?> results</span>
            </div>

            <?php if ($products_res && $products_res->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-dark table-premium mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Seller</th>
                                <th>Category</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Type</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($prod = $products_res->fetch_assoc()): ?>
                                <tr>
                                    <td class="text-secondary small">#<?php echo $prod['id']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($prod['image_url']); ?>"
                                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);"
                                                 onerror="this.src='<?php echo BASE_URL; ?>/assets/img/placeholder.svg'"
                                                 alt="<?php echo htmlspecialchars($prod['name']); ?>">
                                            <div>
                                                <div class="text-white fw-bold" style="font-size: 0.9rem;"><?php echo htmlspecialchars($prod['name']); ?></div>
                                                <div class="text-secondary" style="font-size: 0.75rem;">Added: <?php echo date("M d, Y", strtotime($prod['created_at'])); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-info small"><i class="bi bi-shop me-1"></i><?php echo htmlspecialchars($prod['seller_name'] ?? 'Unknown'); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: rgba(100,116,234,0.2); color: #b4b4c8; border: 1px solid rgba(100,116,234,0.3); font-size: 0.75rem;">
                                            <?php echo htmlspecialchars($prod['category_name'] ?? 'Uncategorized'); ?>
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold text-white"><?php echo format_price($prod['price']); ?></td>
                                    <td class="text-center">
                                        <?php if ($prod['is_digital']): ?>
                                            <span class="text-secondary small">∞</span>
                                        <?php elseif ($prod['stock'] <= 0): ?>
                                            <span class="badge bg-danger">Out of Stock</span>
                                        <?php elseif ($prod['stock'] <= 5): ?>
                                            <span class="badge bg-warning text-dark"><?php echo $prod['stock']; ?> left</span>
                                        <?php else: ?>
                                            <span class="text-success small fw-bold"><?php echo $prod['stock']; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($prod['is_digital']): ?>
                                            <span class="badge" style="background: var(--gradient-tertiary);">Digital</span>
                                        <?php else: ?>
                                            <span class="badge" style="background: var(--gradient-success);">Physical</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="<?php echo BASE_URL; ?>/product-details.php?id=<?php echo $prod['id']; ?>"
                                               class="btn btn-sm btn-outline-info border-0 py-1 px-2" title="View Product" target="_blank">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <form action="products.php" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Delete this product? This cannot be undone.')">
                                                <input type="hidden" name="action" value="delete_product">
                                                <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 py-1 px-2" title="Delete Product">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-box-seam text-secondary" style="font-size: 3.5rem;"></i>
                    <h5 class="text-white mt-3">No Products Found</h5>
                    <p class="text-secondary mb-0">
                        <?php echo $search || $cat_id || $type ? 'Try adjusting your search/filter criteria.' : 'No products have been added to the marketplace yet.'; ?>
                    </p>
                    <?php if ($search || $cat_id || $type): ?>
                        <a href="products.php" class="btn btn-premium-secondary btn-sm mt-3">Clear Filters</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
