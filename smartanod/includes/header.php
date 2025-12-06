<?php
// includes/header.php - Common header and navigation
if (!isLoggedIn()) {
    redirect(BASE_URL . '/auth/login.php');
}

// site logo config (change path/name if you put the logo elsewhere)
$logoRelativePath = __DIR__ . '/../img/logo.jpg'; // server-side filesystem path
$logoUrl = '../img/logo.jpg';                      // URL used in the <img src>
$siteName = 'SmartTanod';

// server-side check
$logoExists = file_exists($logoRelativePath) && is_readable($logoRelativePath);

// build initials for placeholder (max 2 chars)
$words = preg_split('/\s+/', trim($siteName));
$initials = '';
foreach ($words as $w) {
    if ($w !== '') $initials .= strtoupper($w[0]);
}
$initials = substr($initials, 0, 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo 'SmartTanod - ' . htmlspecialchars($pageTitle ?? 'Dashboard'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(15, 154, 0, 0.84);
        }

        /* proper selector for select elements inside .glass */
        .glass select {
            background-color: #f97416ff;
            color: white;
            border: 1px solid #fb923c;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-orange-900 via-gray-800 to-green-900 min-h-screen">
    <!-- Navigation -->
    <nav class="glass border-b border-white/10">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 text-white">
                    <!-- Logo image (server-side detection; client-side onerror as fallback) -->
                    <div class="flex items-center">
                        <?php if ($logoExists): ?>
                            <img
                                src="<?php echo htmlspecialchars($logoUrl); '../img/logo.jpg'?>"
                                alt="<?php echo htmlspecialchars($siteName); 'SmartTanod'?> logo"
                                class="w-20 h-20 rounded-md object-cover shadow-md border border-white/10"
                                onerror="this.style.display='none'; this.parentElement.querySelector('.logo-placeholder').classList.remove('hidden');"
                            >
                            <div class="logo-placeholder hidden w-20 h-20 rounded-md flex items-center justify-center bg-orange-500 text-white font-bold shadow-md border border-white/10">
                                <?php echo htmlspecialchars($initials); ?>
                            </div>
                        <?php else: ?>
                            <!-- server-side fallback shown immediately when file missing -->
                            <div class="logo-placeholder w-20 h-20 rounded-md flex items-center justify-center bg-orange-500 text-white font-bold shadow-md border border-white/10">
                                <?php echo htmlspecialchars($initials); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Site name -->
                    <div class="text-2xl font-bold">
                        <?php echo htmlspecialchars($siteName); ?>
                    </div>
                </div>

                <!-- Main Navigation -->
                <div class="hidden md:flex space-x-2">
                    <a href="<?php echo BASE_URL; ?>/index.php" class="px-4 py-2 text-white hover:bg-orange-500/20 rounded-lg transition">Dashboard</a>
                    <a href="<?php echo BASE_URL; ?>/incidents/index.php" class="px-4 py-2 text-white hover:bg-orange-500/20 rounded-lg transition">Incidents</a>
                    <a href="<?php echo BASE_URL; ?>/patrols/calendar.php" class="px-4 py-2 text-white hover:bg-orange-500/20 rounded-lg transition">Patrols</a>
                    <a href="<?php echo BASE_URL; ?>/evidence/index.php" class="px-4 py-2 text-white hover:bg-orange-500/20 rounded-lg transition">Evidence</a>
                    <a href="<?php echo BASE_URL; ?>/reports/index.php" class="px-4 py-2 text-white hover:bg-orange-500/20 rounded-lg transition">Reports</a>
                    
                    <?php if (getUserRole() === 'admin'): ?>
                    <a href="<?php echo BASE_URL; ?>/users/index.php" class="px-4 py-2 text-white hover:bg-green-500/20 rounded-lg transition">Users</a>
                    <a href="<?php echo BASE_URL; ?>/settings/index.php" class="px-4 py-2 text-white hover:bg-orange-500/20 rounded-lg transition">Settings</a>
                    <?php endif; ?>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <a href="<?php echo BASE_URL; ?>/notifications/index.php" class="text-white relative">
                        🔔
                        <span class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">3</span>
                    </a>
                    <div class="text-white">
                        <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?>
                        <span class="text-xs text-gray-400">(<?php echo htmlspecialchars(getUserRole()); ?>)</span>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/auth/logout.php" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    
    <main class="container mx-auto px-4 py-8">
