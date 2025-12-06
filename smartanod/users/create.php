<?php
// users/create.php
require_once '../config.php';
$pageTitle = 'Create User';
require_once '../includes/header.php';

if (getUserRole() !== 'admin') {
    redirect(BASE_URL . '/index.php');
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $role = $_POST['role'] ?? '';
    $zone = $_POST['zone'] ?? null;
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($full_name) || empty($username) || empty($email) || empty($role) || empty($password)) {
        $error = 'Please fill in all required fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {
        try {
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = 'Username already exists.';
            } else {
                // Check if email already exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $error = 'Email already exists.';
                } else {
                    // Hash password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Insert new user
                    $stmt = $pdo->prepare("
                        INSERT INTO users (full_name, username, email, phone, address, role, zone, password, status, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW())
                    ");
                    
                    $stmt->execute([
                        $full_name,
                        $username,
                        $email,
                        $phone,
                        $address,
                        $role,
                        $zone,
                        $hashed_password
                    ]);
                    
                    $success = 'User created successfully!';
                    // Redirect after 2 seconds
                    header("refresh:2;url=index.php");
                }
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<?php if ($error): ?>
<div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-lg text-red-400">
    <?php echo htmlspecialchars($error); ?>
</div>
<?php endif; ?>

<?php if ($success): ?>
<div class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-400">
    <?php echo htmlspecialchars($success); ?>
    <p class="text-sm mt-2">Redirecting to user list...</p>
</div>
<?php endif; ?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Create New User</h1>
    <p class="text-gray-400">Add a new user to the system</p>
</div>

<form method="POST" class="glass rounded-2xl p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-white mb-2">Full Name *</label>
            <input type="text" name="full_name" required 
                   value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>"
                   class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        
        <div>
            <label class="block text-white mb-2">Username *</label>
            <input type="text" name="username" required 
                   value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                   class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        
        <div>
            <label class="block text-white mb-2">Email *</label>
            <input type="email" name="email" required 
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                   class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        
        <div>
            <label class="block text-white mb-2">Phone</label>
            <input type="tel" name="phone" placeholder="+63 912 345 6789"
                   value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                   class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        
        <div class="md:col-span-2">
            <label class="block text-white mb-2">Address</label>
            <input type="text" name="address" placeholder="House No., Street, Barangay, City"
                   value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>"
                   class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        
        <div>
            <label class="block text-white mb-2">Role *</label>
            <select name="role" id="role" required 
                    class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="">Select Role</option>
                <option value="admin" <?php echo (($_POST['role'] ?? '') === 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="captain" <?php echo (($_POST['role'] ?? '') === 'captain') ? 'selected' : ''; ?>>Captain</option>
                <option value="clerk" <?php echo (($_POST['role'] ?? '') === 'clerk') ? 'selected' : ''; ?>>Clerk</option>
                <option value="tanod" <?php echo (($_POST['role'] ?? '') === 'tanod') ? 'selected' : ''; ?>>Tanod</option>
                <option value="viewer" <?php echo (($_POST['role'] ?? '') === 'viewer') ? 'selected' : ''; ?>>Viewer</option>
            </select>
        </div>
        
        <div id="zoneField" style="display: <?php echo (($_POST['role'] ?? '') === 'tanod') ? 'block' : 'none'; ?>;">
            <label class="block text-white mb-2">Assigned Zone</label>
            <select name="zone" 
                    class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="">Select Zone</option>
                <option value="Zone A" <?php echo (($_POST['zone'] ?? '') === 'Zone A') ? 'selected' : ''; ?>>Zone A</option>
                <option value="Zone B" <?php echo (($_POST['zone'] ?? '') === 'Zone B') ? 'selected' : ''; ?>>Zone B</option>
                <option value="Zone C" <?php echo (($_POST['zone'] ?? '') === 'Zone C') ? 'selected' : ''; ?>>Zone C</option>
                <option value="Zone D" <?php echo (($_POST['zone'] ?? '') === 'Zone D') ? 'selected' : ''; ?>>Zone D</option>
            </select>
        </div>
        
        <div>
            <label class="block text-white mb-2">Password *</label>
            <input type="password" name="password" required 
                   class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
            <p class="text-gray-400 text-sm mt-1">Minimum 6 characters</p>
        </div>
        
        <div>
            <label class="block text-white mb-2">Confirm Password *</label>
            <input type="password" name="confirm_password" required 
                   class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
    </div>
    
    <div class="mt-8 flex justify-end space-x-4">
        <a href="index.php" class="px-6 py-3 glass rounded-lg text-white hover:bg-white/10 transition">
            Cancel
        </a>
        <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
            Create User
        </button>
    </div>
</form>

<script>
// Show zone field only for tanod role
document.getElementById('role').addEventListener('change', function() {
    const zoneField = document.getElementById('zoneField');
    if (this.value === 'tanod') {
        zoneField.style.display = 'block';
    } else {
        zoneField.style.display = 'none';
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>