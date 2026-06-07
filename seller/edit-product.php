<?php
// seller/edit-product.php
require_once __DIR__ . '/../includes/header.php';

restrict_to_roles(['seller'], 'login.php');

$current_user = get_current_user_details();
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch product and verify ownership
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->bind_param("ii", $product_id, $current_user['id']);
$stmt->execute();
$product_res = $stmt->get_result();

if ($product_res->num_rows === 0) {
    $_SESSION['flash_message'] = "Product not found or access denied.";
    $_SESSION['flash_type'] = "danger";
    header("Location: dashboard.php");
    exit;
}

$product = $product_res->fetch_assoc();
$stmt->close();

// Handle Update
$error_msg = '';
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_product') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category_id'];
    $stock = (int)$_POST['stock'];
    
    if (empty($name) || empty($description) || $price <= 0) {
        $error_msg = "Please fill in all required fields.";
    } else {
        $image_path = $product['image_url']; // Keep existing by default
        
        // Handle new image upload if provided
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $img_tmp = $_FILES['product_image']['tmp_name'];
            $img_name = $_FILES['product_image']['name'];
            $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
            
            $upload_dir = __DIR__ . '/../assets/uploads/';
            $new_img_name = 'prod_' . uniqid() . '.' . $img_ext;
            $dest_path = $upload_dir . $new_img_name;
            
            if (move_uploaded_file($img_tmp, $dest_path)) {
                // Delete old image if it exists
                if (file_exists(__DIR__ . '/../' . $product['image_url'])) {
                    unlink(__DIR__ . '/../' . $product['image_url']);
                }
                $image_path = 'assets/uploads/' . $new_img_name;
            }
        }
        
        // Update product
        $stmt_update = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ?, image_url = ?, stock = ? WHERE id = ? AND seller_id = ?");
        $stmt_update->bind_param("ssdiisii", $name, $description, $price, $category_id, $image_path, $stock, $product_id, $current_user['id']);
        
        if ($stmt_update->execute()) {
            $_SESSION['flash_message'] = "Product updated successfully!";
            $_SESSION['flash_type'] = "success";
            header("Location: dashboard.php");
            exit;
        } else {
            $error_msg = "Failed to update product.";
        }
        $stmt_update->close();
    }
}

// Fetch categories
$cats_res = $conn->query("SELECT * FROM categories ORDER BY name ASC");

// Note: header.php already included above - do NOT include it again
?>

<div class="container py-5 mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="<?php echo BASE_URL; ?>/seller/dashboard.php" class="btn btn-premium-secondary"><i class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="text-white mb-1"><i class="bi bi-pencil-square text-gradient-primary me-2"></i>Edit Product</h2>
                    <p class="text-secondary small mb-0">Modify product listing details and pricing</p>
                </div>
            </div>

            <div class="card-glass p-5">
                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-danger bg-danger-subtle border-danger text-danger-emphasis rounded-3 px-3 py-2 small mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error_msg); ?>
                    </div>
                <?php endif; ?>

                <form action="edit-product.php?id=<?php echo $product_id; ?>" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-4">
                    <input type="hidden" name="action" value="update_product">
                    
                    <!-- Current Image Preview -->
                    <div class="text-center mb-3">
                        <label class="form-glass-label d-block mb-2">Current Product Image</label>
                        <img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product" class="img-fluid rounded border border-secondary" style="max-height: 200px; object-fit: contain;">
                    </div>

                    <div>
                        <label class="form-glass-label" for="name">Product Name</label>
                        <input type="text" name="name" id="name" class="form-control form-glass-input" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    </div>

                    <div>
                        <label class="form-glass-label" for="category_id">Category</label>
                        <select name="category_id" id="category_id" class="form-select form-glass-input" required>
                            <?php while ($cat = $cats_res->fetch_assoc()): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-glass-label" for="price">Price (USD)</label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control form-glass-input" value="<?php echo $product['price']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-glass-label" for="stock">Stock Quantity</label>
                            <input type="number" name="stock" id="stock" class="form-control form-glass-input" value="<?php echo $product['stock']; ?>" <?php echo $product['is_digital'] ? 'readonly' : ''; ?>>
                        </div>
                    </div>

                    <div>
                        <label class="form-glass-label" for="description">Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control form-glass-input" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>

                    <div>
                        <label class="form-glass-label" for="product_image">Replace Product Image (Optional)</label>
                        <input type="file" name="product_image" id="product_image" class="form-control form-glass-input">
                        <span class="text-secondary small d-block mt-1">Leave empty to keep current image</span>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-premium flex-grow-1 py-3"><i class="bi bi-check-circle-fill me-2"></i>Save Changes</button>
                        <a href="<?php echo BASE_URL; ?>/seller/dashboard.php" class="btn btn-premium-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
