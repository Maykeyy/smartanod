<?php
// incidents/edit.php
require_once '../config.php';
$pageTitle = 'Edit Incident';
require_once '../includes/header.php';

$id = $_GET['id'] ?? 1;
?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Edit Incident</h1>
    <p class="text-gray-400 font-mono">INC-2025-<?php echo str_pad($id, 3, '0', STR_PAD_LEFT); ?></p>
</div>

<form method="POST" class="glass rounded-2xl p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Pre-filled form fields (same structure as create.php) -->
        <div>
            <label class="block text-white mb-2">Status *</label>
            <select name="status" required 
                    class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option selected>Pending</option>
                <option>In Progress</option>
                <option>Resolved</option>
                <option>Closed</option>
            </select>
        </div>
        
        <div>
            <label class="block text-white mb-2">Priority *</label>
            <select name="priority" required 
                    class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option>Low</option>
                <option>Medium</option>
                <option selected>High</option>
                <option>Urgent</option>
            </select>
        </div>
        
        <div class="md:col-span-2">
            <label class="block text-white mb-2">Incident Narrative *</label>
            <textarea name="narrative" rows="6" required
                      class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">Multiple residents reported excessive noise coming from a residential property.</textarea>
        </div>
    </div>
    
    <div class="mt-8 flex justify-end space-x-4">
        <a href="view.php?id=<?php echo $id; ?>" class="px-6 py-3 glass rounded-lg text-white hover:bg-white/10 transition">
            Cancel
        </a>
        <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
            Save Changes
        </button>
    </div>
</form>

<?php require_once '../includes/footer.php'; ?>