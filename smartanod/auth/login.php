<?php
// auth/login.php
require_once '../config.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect(BASE_URL . '/index.php');
}

$error = '';

// logo config (server-side path + public URL)
$logoRelativePath = __DIR__ . '/../img/logo.jpg';
$logoUrl = '../img/logo.jpg';
$siteName = 'SmartTanod';
$logoExists = file_exists($logoRelativePath) && is_readable($logoRelativePath);

// build initials for placeholder (max 2 chars)
$words = preg_split('/\s+/', trim($siteName));
$initials = '';
foreach ($words as $w) {
    if ($w !== '') $initials .= strtoupper($w[0]);
}
$initials = substr($initials, 0, 2);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Simple demo authentication (replace with real DB check)
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = 'admin';
        $_SESSION['full_name'] = 'Admin User';
        $_SESSION['role'] = 'admin';
        redirect(BASE_URL . '/index.php');
    } elseif ($username === 'clerk' && $password === 'clerk123') {
        $_SESSION['user_id'] = 2;
        $_SESSION['username'] = 'clerk';
        $_SESSION['full_name'] = 'Clerk User';
        $_SESSION['role'] = 'clerk';
        redirect(BASE_URL . '/index.php');
    } else {
        $error = 'Invalid credentials. Try admin/admin123 or clerk/clerk123';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SmartTanod</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass select {
            background-color: #f97416ff;
            color: white;
            border: 1px solid #fb923c;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 min-h-screen flex items-center justify-center">
    <div class="glass rounded-2xl p-8 w-full max-w-md">
        <!-- Header: centered logo + centered text -->
        <div class="mb-6 w-full">
            <a href="<?php echo htmlspecialchars(BASE_URL); ?>/index.php" class="w-full block">
                <div class="flex flex-col items-center justify-center text-center gap-4">
                    <!-- Logo (centered) -->
                    <div class="flex-shrink-0">
                        <?php if ($logoExists): ?>
                            <img
                                src="<?php echo htmlspecialchars($logoUrl); ?>"
                                alt="<?php echo htmlspecialchars($siteName); ?> logo"
                                class="w-28 h-28 rounded-lg object-cover shadow-md border border-white/10 mx-auto"
                                onerror="this.style.display='none'; this.parentElement.querySelector('.logo-placeholder').classList.remove('hidden');"
                            >
                            <div class="logo-placeholder hidden w-28 h-28 rounded-lg flex items-center justify-center bg-orange-500 text-white font-bold shadow-md border border-white/10 mx-auto">
                                <?php echo htmlspecialchars($initials); ?>
                            </div>
                        <?php else: ?>
                            <div class="logo-placeholder w-28 h-28 rounded-lg flex items-center justify-center bg-orange-500 text-white font-bold shadow-md border border-white/10 mx-auto">
                                <?php echo htmlspecialchars($initials); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Title & Subtitle (centered) -->
                    <div class="text-center">
                        <h1 class="text-2xl sm:text-3xl font-bold text-white leading-tight">
                            <?php echo htmlspecialchars($siteName); ?>
                        </h1>
                        <p class="text-gray-400 text-sm sm:text-sm">
                            Barangay Malabanban Sur — Blotter & Patrol Management
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <?php if ($error): ?>
        <div class="bg-red-500/20 border border-red-500 text-red-200 px-4 py-3 rounded-lg mb-4">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="mb-4">
                <label class="block text-white mb-2">Username</label>
                <input type="text" name="username" required
                       class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500"
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>

            <div class="mb-6">
                <label class="block text-white mb-2">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>

            <button type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-lg transition">
                Login
            </button>

            <div class="mt-4 flex items-center justify-between text-sm">
                <a href="forgot.php" class="text-orange-400 hover:text-orange-300">Forgot Password?</a>
                <a href="register.php" class="text-green-400 hover:text-green-300">Register</a>
            </div>
        </form>

        <div class="mt-6 p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
            <p class="text-blue-200 text-sm"><strong>Demo Credentials:</strong></p>
            <p class="text-blue-200 text-xs">Admin: admin / admin123</p>
            <p class="text-blue-200 text-xs">Clerk: clerk / clerk123</p>
        </div>
    </div>
</body>
</html>
