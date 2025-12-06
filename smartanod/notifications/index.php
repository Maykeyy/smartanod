<?php
// notifications/index.php
require_once '../config.php';
$pageTitle = 'Notifications';
require_once '../includes/header.php';
?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Notifications</h1>
    <p class="text-gray-400">View all your notifications</p>
</div>

<!-- Notification List -->
<div class="space-y-4">
    <!-- Unread Notification -->
    <div class="glass rounded-2xl p-6 border-l-4 border-l-orange-500 hover:bg-white/10">
        <div class="flex justify-between items-start mb-2">
            <div class="flex items-start gap-4">
                <div class="text-3xl">📢</div>
                <div>
                    <h3 class="text-white font-bold">New Incident Assigned</h3>
                    <p class="text-gray-300 mt-1">You have been assigned to incident <a href="<?php echo BASE_URL; ?>/incidents/view.php?id=1" class="text-orange-400 hover:underline">INC-2025-001</a></p>
                    <p class="text-gray-500 text-sm mt-2">5 minutes ago</p>
                </div>
            </div>
            <button class="text-gray-400 hover:text-white">✓</button>
        </div>
    </div>
    
    <!-- Unread Notification -->
    <div class="glass rounded-2xl p-6 border-l-4 border-l-orange-500 hover:bg-white/10">
        <div class="flex justify-between items-start mb-2">
            <div class="flex items-start gap-4">
                <div class="text-3xl">🚶</div>
                <div>
                    <h3 class="text-white font-bold">Patrol Schedule Updated</h3>
                    <p class="text-gray-300 mt-1">Your patrol schedule for tomorrow has been updated. <a href="<?php echo BASE_URL; ?>/patrols/calendar.php" class="text-orange-400 hover:underline">View schedule</a></p>
                    <p class="text-gray-500 text-sm mt-2">2 hours ago</p>
                </div>
            </div>
            <button class="text-gray-400 hover:text-white">✓</button>
        </div>
    </div>
    
    <!-- Read Notification -->
    <div class="glass rounded-2xl p-6 opacity-60 hover:bg-white/10">
        <div class="flex justify-between items-start mb-2">
            <div class="flex items-start gap-4">
                <div class="text-3xl">✅</div>
                <div>
                    <h3 class="text-white font-bold">Incident Resolved</h3>
                    <p class="text-gray-300 mt-1">Incident <a href="<?php echo BASE_URL; ?>/incidents/view.php?id=2" class="text-orange-400 hover:underline">INC-2025-002</a> has been marked as resolved</p>
                    <p class="text-gray-500 text-sm mt-2">1 day ago</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Read Notification -->
    <div class="glass rounded-2xl p-6 opacity-60 hover:bg-white/10">
        <div class="flex justify-between items-start mb-2">
            <div class="flex items-start gap-4">
                <div class="text-3xl">📎</div>
                <div>
                    <h3 class="text-white font-bold">New Evidence Added</h3>
                    <p class="text-gray-300 mt-1">New evidence has been added to incident <a href="<?php echo BASE_URL; ?>/incidents/view.php?id=1" class="text-orange-400 hover:underline">INC-2025-001</a></p>
                    <p class="text-gray-500 text-sm mt-2">2 days ago</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mark All as Read -->
<div class="mt-6 text-center">
    <button class="px-6 py-3 glass text-white rounded-lg hover:bg-white/10">
        Mark All as Read
    </button>
</div>

<?php require_once '../includes/footer.php'; ?>