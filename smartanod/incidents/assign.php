<?php
// incidents/assign.php
require_once '../config.php';
$pageTitle = 'Assign Incident';
require_once '../includes/header.php';

$id = $_GET['id'] ?? 1;
?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Assign Incident to Tanod</h1>
    <p class="text-gray-400 font-mono">INC-2025-<?php echo str_pad($id, 3, '0', STR_PAD_LEFT); ?></p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Assignment Form -->
    <div class="glass rounded-2xl p-6">
        <h2 class="text-xl font-bold text-white mb-4">Select Tanod</h2>
        
        <form method="POST">
            <div class="mb-4">
                <label class="block text-white mb-2">Tanod Personnel *</label>
                <select name="tanod_id" required 
                        class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Select Tanod</option>
                    <option value="1">Tanod Juan (Zone A) - Available</option>
                    <option value="2">Tanod Pedro (Zone A) - Available</option>
                    <option value="3">Tanod Maria (Zone B) - On Patrol</option>
                    <option value="4">Tanod Jose (Zone C) - Available</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-white mb-2">Notes (Optional)</label>
                <textarea name="notes" rows="4" placeholder="Add any special instructions..."
                          class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
            </div>
            
            <div class="mb-6">
                <label class="flex items-center text-white">
                    <input type="checkbox" name="send_notification" checked class="mr-2">
                    Send notification to assigned Tanod
                </label>
            </div>
            
            <div class="flex space-x-4">
                <a href="view.php?id=<?php echo $id; ?>" class="flex-1 px-6 py-3 glass rounded-lg text-white hover:bg-white/10 transition text-center">
                    Cancel
                </a>
                <button type="submit" class="flex-1 px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition">
                    Assign Incident
                </button>
            </div>
        </form>
    </div>
    
    <!-- Incident Summary -->
    <div class="glass rounded-2xl p-6">
        <h2 class="text-xl font-bold text-white mb-4">Incident Summary</h2>
        
        <div class="space-y-3">
            <div>
                <div class="text-gray-400 text-sm">Category</div>
                <div class="text-white">Noise Complaint</div>
            </div>
            <div>
                <div class="text-gray-400 text-sm">Location</div>
                <div class="text-white">Zone A, Street 5</div>
            </div>
            <div>
                <div class="text-gray-400 text-sm">Priority</div>
                <div><span class="px-3 py-1 bg-orange-500/20 text-orange-400 rounded-full text-sm">High</span></div>
            </div>
            <div>
                <div class="text-gray-400 text-sm">Date Reported</div>
                <div class="text-white">Nov 28, 2025 14:30</div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>