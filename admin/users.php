<?php
// admin/users.php
require_once __DIR__ . '/../includes/header.php';

restrict_to_roles(['admin'], 'login.php');

$current_user = get_current_user_details();

// ==========================================
// HANDLE POST ACTIONS
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    // Update seller status
    if ($_POST['action'] === 'update_status') {
        $user_id    = (int)$_POST['user_id'];
        $new_status = $_POST['status'];
        if (in_array($new_status, ['pending', 'approved', 'rejected'])) {
            $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ? AND role != 'admin'");
            $stmt->bind_param("si", $new_status, $user_id);
            if ($stmt->execute()) {
                $_SESSION['flash_message'] = "User #$user_id status updated to '$new_status'.";
                $_SESSION['flash_type'] = $new_status === 'approved' ? 'success' : 'warning';
            }
            $stmt->close();
        }
        header("Location: " . BASE_URL . "/admin/users.php");
        exit;
    }

    // Delete user
    if ($_POST['action'] === 'delete_user') {
        $user_id = (int)$_POST['user_id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $_SESSION['flash_message'] = "User #$user_id has been removed from the system.";
            $_SESSION['flash_type'] = "info";
        }
        $stmt->close();
        header("Location: " . BASE_URL . "/admin/users.php");
        exit;
    }
}

// ==========================================
// FILTERS
// ==========================================
$role_filter   = $_GET['role'] ?? 'all';
$search_filter = trim($_GET['search'] ?? '');

$where_parts = [];
$params      = [];
$param_types = '';

if ($role_filter !== 'all' && in_array($role_filter, ['admin', 'seller', 'customer'])) {
    $where_parts[] = "role = ?";
    $params[]      = $role_filter;
    $param_types  .= 's';
} else {
    $where_parts[] = "role != 'admin'"; // by default show non-admins
}

if ($search_filter !== '') {
    $where_parts[] = "(name LIKE ? OR email LIKE ?)";
    $like = "%$search_filter%";
    $params[]     = $like;
    $params[]     = $like;
    $param_types .= 'ss';
}

$where_sql = 'WHERE ' . implode(' AND ', $where_parts);
$sql = "SELECT * FROM users $where_sql ORDER BY id DESC";

if ($params) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($param_types, ...$params);
    $stmt->execute();
    $users_res = $stmt->get_result();
    $stmt->close();
} else {
    $users_res = $conn->query($sql);
}

// Stats
$total_users     = $conn->query("SELECT COUNT(*) as c FROM users WHERE role != 'admin'")->fetch_assoc()['c'];
$total_sellers   = $conn->query("SELECT COUNT(*) as c FROM users WHERE role = 'seller'")->fetch_assoc()['c'];
$total_customers = $conn->query("SELECT COUNT(*) as c FROM users WHERE role = 'customer'")->fetch_assoc()['c'];
$pending_sellers = $conn->query("SELECT COUNT(*) as c FROM users WHERE role = 'seller' AND status = 'pending'")->fetch_assoc()['c'];
?>

<div class="container-fluid py-5 mt-4">
    <div class="container">

        <!-- Page Title -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-5 gap-3">
            <div>
                <h2 class="text-white mb-1"><i class="bi bi-people-fill text-gradient-primary me-2"></i>User Management</h2>
                <p class="text-secondary mb-0">Manage all registered sellers and customers on the platform.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="btn btn-premium-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Admin Panel</a>
                <?php if ($pending_sellers > 0): ?>
                    <a href="users.php?role=seller" class="btn btn-warning btn-sm">
                        <i class="bi bi-exclamation-circle-fill me-1"></i><?php echo $pending_sellers; ?> Pending
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="row g-4 mb-5">
            <div class="col-md-3 col-6">
                <div class="stats-card text-center">
                    <i class="bi bi-people-fill text-info fs-2 mb-3 d-block"></i>
                    <h3 class="text-white font-heading mb-1"><?php echo number_format($total_users); ?></h3>
                    <p class="text-secondary small mb-0">Total Users</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card text-center">
                    <i class="bi bi-shop fs-2 mb-3 d-block" style="color: #00d9ff;"></i>
                    <h3 class="text-white font-heading mb-1"><?php echo number_format($total_sellers); ?></h3>
                    <p class="text-secondary small mb-0">Sellers</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card text-center">
                    <i class="bi bi-person-fill text-success fs-2 mb-3 d-block"></i>
                    <h3 class="text-white font-heading mb-1"><?php echo number_format($total_customers); ?></h3>
                    <p class="text-secondary small mb-0">Customers</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card text-center">
                    <i class="bi bi-hourglass-split text-warning fs-2 mb-3 d-block"></i>
                    <h3 class="text-white font-heading mb-1"><?php echo number_format($pending_sellers); ?></h3>
                    <p class="text-secondary small mb-0">Pending Sellers</p>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="card-glass p-4 mb-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-glass-label">Search Users</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search text-secondary"></i></span>
                        <input type="text" name="search" class="form-control form-glass-input" placeholder="Search by name or email..." value="<?php echo htmlspecialchars($search_filter); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-glass-label">Filter by Role</label>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="users.php<?php echo $search_filter ? "?search=" . urlencode($search_filter) : ''; ?>" class="btn btn-sm <?php echo $role_filter === 'all' ? 'btn-premium' : 'btn-premium-secondary'; ?>">All</a>
                        <a href="users.php?role=seller<?php echo $search_filter ? "&search=" . urlencode($search_filter) : ''; ?>" class="btn btn-sm <?php echo $role_filter === 'seller' ? 'btn-premium' : 'btn-premium-secondary'; ?>">Sellers</a>
                        <a href="users.php?role=customer<?php echo $search_filter ? "&search=" . urlencode($search_filter) : ''; ?>" class="btn btn-sm <?php echo $role_filter === 'customer' ? 'btn-premium' : 'btn-premium-secondary'; ?>">Customers</a>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-premium w-100"><i class="bi bi-search me-1"></i>Search</button>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="card-glass p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-white font-heading mb-0">
                    <i class="bi bi-list-check me-2 text-info"></i>
                    <?php
                    if ($role_filter === 'seller') echo 'Registered Sellers';
                    elseif ($role_filter === 'customer') echo 'Registered Customers';
                    else echo 'All Users';
                    ?>
                </h4>
                <span class="badge bg-secondary px-3 py-2"><?php echo $users_res ? $users_res->num_rows : 0; ?> results</span>
            </div>

            <?php if ($users_res && $users_res->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-dark table-premium mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Status</th>
                                <th>Joined</th>
                                <?php if ($role_filter !== 'customer'): ?>
                                    <th>Location</th>
                                <?php endif; ?>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = $users_res->fetch_assoc()): ?>
                                <tr>
                                    <td class="text-secondary small">#<?php echo $user['id']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                                 style="width: 38px; height: 38px; background: var(--gradient-primary); font-size: 0.85rem; font-weight: 700; flex-shrink: 0;">
                                                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <div class="text-white fw-bold" style="font-size: 0.9rem;"><?php echo htmlspecialchars($user['name']); ?></div>
                                                <div class="text-secondary" style="font-size: 0.75rem;"><?php echo htmlspecialchars($user['email']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($user['role'] === 'seller'): ?>
                                            <span class="badge" style="background: rgba(0,217,255,0.2); color: #00d9ff; border: 1px solid rgba(0,217,255,0.4);">Seller</span>
                                        <?php elseif ($user['role'] === 'customer'): ?>
                                            <span class="badge" style="background: rgba(0,245,160,0.15); color: #00f5a0; border: 1px solid rgba(0,245,160,0.3);">Customer</span>
                                        <?php else: ?>
                                            <span class="badge" style="background: rgba(255,214,10,0.2); color: #ffd60a; border: 1px solid rgba(255,214,10,0.3);">Admin</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?php
                                            if ($user['status'] === 'approved') echo 'bg-success';
                                            elseif ($user['status'] === 'pending') echo 'bg-warning text-dark';
                                            else echo 'bg-danger';
                                        ?>">
                                            <?php echo strtoupper($user['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-secondary small"><?php echo date("M d, Y", strtotime($user['created_at'])); ?></td>
                                    <?php if ($role_filter !== 'customer'): ?>
                                        <td class="text-secondary small">
                                            <?php echo $user['seller_location'] ? htmlspecialchars($user['seller_location']) : '<span class="text-muted">—</span>'; ?>
                                        </td>
                                    <?php endif; ?>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end align-items-center flex-wrap">
                                            <!-- Status update dropdown -->
                                            <?php if ($user['role'] === 'seller'): ?>
                                                <form action="users.php" method="POST" class="d-flex gap-1">
                                                    <input type="hidden" name="action" value="update_status">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                    <select name="status" class="form-select form-select-sm" style="min-width: 110px; background: rgba(30,30,56,0.9); border: 1px solid rgba(255,255,255,0.15); color: #fff; border-radius: 8px;">
                                                        <option value="pending" <?php echo $user['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="approved" <?php echo $user['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                                                        <option value="rejected" <?php echo $user['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-premium px-2">Set</button>
                                                </form>
                                            <?php endif; ?>

                                            <!-- Delete button -->
                                            <form action="users.php" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete user \'<?php echo htmlspecialchars(addslashes($user['name'])); ?>\'? This will also delete all their products and orders.')">
                                                <input type="hidden" name="action" value="delete_user">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 py-1 px-2" title="Delete User">
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
                    <i class="bi bi-people text-secondary" style="font-size: 3.5rem;"></i>
                    <h5 class="text-white mt-3">No Users Found</h5>
                    <p class="text-secondary mb-3">
                        <?php echo $search_filter ? 'No users match your search.' : 'No users registered in this category yet.'; ?>
                    </p>
                    <?php if ($search_filter): ?>
                        <a href="users.php" class="btn btn-premium-secondary btn-sm">Clear Search</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
