<?php
// patrols/create.php
require_once '../config.php';
$pageTitle = 'Schedule Patrol';
require_once '../includes/header.php';
?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Schedule New Patrol</h1>
    <p class="text-gray-400">Create a new patrol assignment</p>
</div>

<form method="POST" class="glass rounded-2xl p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-white mb-2">Tanod Personnel *</label>
            <select name="tanod_id" required 
                    class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="">Select Tanod</option>
                <option>Tanod Juan</option>
                <option>Tanod Pedro</option>
                <option>Tanod Maria</option>
                <option>Tanod Jose</option>
            </select>
        </div>
        
        <div>
            <label class="block text-white mb-2">Zone *</label>
            <select name="zone" required 
                    class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option>Zone A</option>
                <option>Zone B</option>
                <option>Zone C</option>
                <option>Zone D</option>
            </select>
        </div>
        
        <div>
            <label class="block text-white mb-2">Start Date & Time *</label>
            <input type="datetime-local" name="start_time" required 
                   class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        
        <div>
            <label class="block text-white mb-2">End Date & Time *</label>
            <input type="datetime-local" name="end_time" required 
                   class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        
        <div class="md:col-span-2">
            <label class="block text-white mb-2">Notes (Optional)</label>
            <textarea name="notes" rows="4" placeholder="Add patrol instructions or special notes..."
                      class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
        </div>
    </div>
    
    <div class="mt-8 flex justify-end space-x-4">
        <a href="calendar.php" class="px-6 py-3 glass rounded-lg text-white hover:bg-white/10 transition">
            Cancel
        </a>
        <button type="submit" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
            Schedule Patrol
        </button>
    </div>
</form>

<?php require_once '../includes/footer.php'; ?>