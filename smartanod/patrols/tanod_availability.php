<?php
// patrols/tanod_availability.php - Tanod Availability Management
require_once '../config.php';
$pageTitle = 'Tanod Availability';
require_once '../includes/header.php';

$success = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update_availability') {
        $success = 'Availability updated successfully!';
    }
}

// Sample tanod data - in real implementation, fetch from database
$tanods = [
    [
        'id' => 1,
        'name' => 'Juan Dela Cruz',
        'zone' => 'Zone A',
        'max_hours' => 40,
        'availability_summary' => 'Mon-Fri: Morning/Afternoon, Sat-Sun: Any',
        'unavailable_dates' => '2025-12-15, 2025-12-25'
    ],
    [
        'id' => 2,
        'name' => 'Maria Santos',
        'zone' => 'Zone A',
        'max_hours' => 35,
        'availability_summary' => 'Mon-Wed-Fri: Any, Tue-Thu: Afternoon only',
        'unavailable_dates' => '2025-12-20 to 2025-12-22'
    ],
    [
        'id' => 3,
        'name' => 'Pedro Garcia',
        'zone' => 'Zone B',
        'max_hours' => 45,
        'availability_summary' => 'Daily: Morning/Night shifts preferred',
        'unavailable_dates' => 'None scheduled'
    ],
    [
        'id' => 4,
        'name' => 'Ana Rodriguez',
        'zone' => 'Zone B',
        'max_hours' => 30,
        'availability_summary' => 'Weekends only: Any shift',
        'unavailable_dates' => 'Dec 24-26, Jan 1'
    ]
];
?>

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Tanod Availability Management</h1>
            <p class="text-gray-400">Configure tanod availability for auto-scheduling</p>
        </div>
        <div class="flex space-x-3">
            <a href="calendar.php" class="px-4 py-2 glass hover:bg-white/10 text-white rounded-lg transition">
                📅 View Calendar
            </a>
            <button onclick="bulkUpdateAvailability()" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
                🔄 Bulk Update
            </button>
        </div>
    </div>
</div>

<?php if ($success): ?>
<div class="bg-green-500/20 border border-green-500 text-green-200 px-4 py-3 rounded-lg mb-6">
    <?php echo htmlspecialchars($success); ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-500/20 border border-red-500 text-red-200 px-4 py-3 rounded-lg mb-6">
    <?php echo htmlspecialchars($error); ?>
</div>
<?php endif; ?>

<!-- Availability Overview -->
<div class="glass rounded-2xl overflow-hidden mb-6">
    <div class="p-6 border-b border-white/10">
        <h2 class="text-xl font-bold text-white">Availability Overview</h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-white/5">
                <tr>
                    <th class="text-left text-gray-400 py-4 px-6">Tanod</th>
                    <th class="text-left text-gray-400 py-4 px-6">Zone</th>
                    <th class="text-left text-gray-400 py-4 px-6">Max Hours/Week</th>
                    <th class="text-left text-gray-400 py-4 px-6">Availability Summary</th>
                    <th class="text-left text-gray-400 py-4 px-6">Unavailable Dates</th>
                    <th class="text-left text-gray-400 py-4 px-6">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tanods as $tanod): ?>
                <tr class="border-b border-white/5 hover:bg-white/5">
                    <td class="py-4 px-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center text-white font-bold">
                                <?php echo substr($tanod['name'], 0, 1); ?>
                            </div>
                            <span class="text-white font-medium"><?php echo htmlspecialchars($tanod['name']); ?></span>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1 bg-orange-500/20 text-orange-400 rounded-full text-sm">
                            <?php echo htmlspecialchars($tanod['zone']); ?>
                        </span>
                    </td>
                    <td class="py-4 px-6 text-white"><?php echo $tanod['max_hours']; ?> hours</td>
                    <td class="py-4 px-6 text-gray-300 max-w-xs"><?php echo htmlspecialchars($tanod['availability_summary']); ?></td>
                    <td class="py-4 px-6 text-gray-300"><?php echo htmlspecialchars($tanod['unavailable_dates']); ?></td>
                    <td class="py-4 px-6">
                        <button onclick="editAvailability(<?php echo $tanod['id']; ?>)" 
                                class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm rounded-lg transition">
                            ✏️ Edit Availability
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Availability Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="glass rounded-2xl p-6">
        <div class="text-gray-400 text-sm mb-2">Total Tanods</div>
        <div class="text-3xl font-bold text-white mb-1">12</div>
        <div class="text-green-400 text-sm">All configured</div>
    </div>
    
    <div class="glass rounded-2xl p-6">
        <div class="text-gray-400 text-sm mb-2">Avg Hours/Week</div>
        <div class="text-3xl font-bold text-white mb-1">37.5</div>
        <div class="text-orange-400 text-sm">Per tanod</div>
    </div>
    
    <div class="glass rounded-2xl p-6">
        <div class="text-gray-400 text-sm mb-2">Weekend Available</div>
        <div class="text-3xl font-bold text-white mb-1">8</div>
        <div class="text-green-400 text-sm">Out of 12</div>
    </div>
    
    <div class="glass rounded-2xl p-6">
        <div class="text-gray-400 text-sm mb-2">Night Shift Available</div>
        <div class="text-3xl font-bold text-white mb-1">6</div>
        <div class="text-blue-400 text-sm">Out of 12</div>
    </div>
</div>

<!-- Edit Availability Modal -->
<div id="availabilityModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="glass rounded-2xl p-6 max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 id="modalTitle" class="text-xl font-bold text-white">Edit Tanod Availability</h3>
                <button onclick="closeAvailabilityModal()" class="text-gray-400 hover:text-white">✕</button>
            </div>
            
            <form id="availabilityForm" method="POST">
                <input type="hidden" name="action" value="update_availability">
                <input type="hidden" name="tanod_id" id="tanodId">
                
                <div class="space-y-6">
                    <!-- Tanod Info -->
                    <div class="glass rounded-lg p-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gray-600 rounded-full flex items-center justify-center text-2xl text-white font-bold">
                                J
                            </div>
                            <div>
                                <h4 id="tanodName" class="text-lg font-bold text-white">Juan Dela Cruz</h4>
                                <p id="tanodZone" class="text-gray-400">Zone A</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Max Hours Per Week -->
                    <div>
                        <label class="block text-white mb-2">Maximum Hours Per Week *</label>
                        <input type="number" name="max_hours" id="maxHours" min="1" max="60" value="40" required 
                               class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <p class="text-gray-400 text-sm mt-1">Recommended: 35-45 hours per week</p>
                    </div>
                    
                    <!-- Weekly Availability Grid -->
                    <div>
                        <label class="block text-white mb-4">Weekly Availability *</label>
                        <div class="glass rounded-lg p-4">
                            <div class="grid grid-cols-1 gap-4">
                                <?php 
                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                $shifts = [
                                    'morning' => 'Morning (6 AM - 2 PM)',
                                    'afternoon' => 'Afternoon (2 PM - 10 PM)',
                                    'night' => 'Night (10 PM - 6 AM)'
                                ];
                                
                                foreach ($days as $day): 
                                ?>
                                <div class="border border-white/10 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h5 class="text-white font-medium"><?php echo $day; ?></h5>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="available_<?php echo strtolower($day); ?>" 
                                                   class="w-4 h-4 text-orange-500 bg-transparent border-gray-300 rounded focus:ring-orange-500"
                                                   onchange="toggleDayAvailability('<?php echo strtolower($day); ?>')">
                                            <span class="text-gray-400 text-sm">Available</span>
                                        </label>
                                    </div>
                                    
                                    <div id="shifts_<?php echo strtolower($day); ?>" class="space-y-2 opacity-50">
                                        <?php foreach ($shifts as $shift_key => $shift_label): ?>
                                        <label class="flex items-center space-x-3">
                                            <input type="checkbox" name="<?php echo strtolower($day); ?>_<?php echo $shift_key; ?>" 
                                                   class="w-4 h-4 text-orange-500 bg-transparent border-gray-300 rounded focus:ring-orange-500">
                                            <span class="text-gray-300 text-sm"><?php echo $shift_label; ?></span>
                                        </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Unavailable Dates -->
                    <div>
                        <label class="block text-white mb-2">Unavailable Dates</label>
                        <div class="space-y-3">
                            <div id="unavailableDates" class="space-y-2">
                                <!-- Unavailable dates will be added here -->
                            </div>
                            <button type="button" onclick="addUnavailableDate()" 
                                    class="px-4 py-2 glass hover:bg-white/10 text-white rounded-lg transition">
                                ➕ Add Unavailable Date Range
                            </button>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    <div>
                        <label class="block text-white mb-2">Additional Notes</label>
                        <textarea name="notes" id="availabilityNotes" rows="3" 
                                  class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500"
                                  placeholder="Any special availability notes or preferences..."></textarea>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end space-x-4">
                    <button type="button" onclick="closeAvailabilityModal()" 
                            class="px-6 py-3 glass rounded-lg text-white hover:bg-white/10 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                        Save Availability
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Update Modal -->
<div id="bulkUpdateModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="glass rounded-2xl p-6 max-w-2xl w-full">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white">Bulk Update Availability</h3>
                <button onclick="closeBulkUpdateModal()" class="text-gray-400 hover:text-white">✕</button>
            </div>
            
            <form method="POST">
                <input type="hidden" name="action" value="bulk_update">
                
                <div class="space-y-6">
                    <!-- Select Tanods -->
                    <div>
                        <label class="block text-white mb-3">Select Tanods to Update</label>
                        <div class="space-y-2 max-h-40 overflow-y-auto glass rounded-lg p-4">
                            <?php foreach ($tanods as $tanod): ?>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="selected_tanods[]" value="<?php echo $tanod['id']; ?>" 
                                       class="w-4 h-4 text-orange-500 bg-transparent border-gray-300 rounded focus:ring-orange-500">
                                <span class="text-white"><?php echo htmlspecialchars($tanod['name']); ?></span>
                                <span class="text-gray-400 text-sm">(<?php echo htmlspecialchars($tanod['zone']); ?>)</span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Bulk Actions -->
                    <div>
                        <label class="block text-white mb-3">Bulk Action</label>
                        <select name="bulk_action" required 
                                class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="">Select Action</option>
                            <option value="set_max_hours">Set Maximum Hours Per Week</option>
                            <option value="add_unavailable_date">Add Unavailable Date Range</option>
                            <option value="reset_availability">Reset to Default Availability</option>
                            <option value="copy_availability">Copy from Another Tanod</option>
                        </select>
                    </div>
                    
                    <!-- Action Parameters -->
                    <div id="actionParameters" class="hidden">
                        <!-- Parameters will be shown based on selected action -->
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end space-x-4">
                    <button type="button" onclick="closeBulkUpdateModal()" 
                            class="px-6 py-3 glass rounded-lg text-white hover:bg-white/10 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
                        Apply Bulk Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let unavailableDateCount = 0;

// Edit availability
function editAvailability(tanodId) {
    // In real implementation, fetch tanod data from server
    const tanodData = {
        1: { name: 'Juan Dela Cruz', zone: 'Zone A', max_hours: 40 },
        2: { name: 'Maria Santos', zone: 'Zone A', max_hours: 35 },
        3: { name: 'Pedro Garcia', zone: 'Zone B', max_hours: 45 },
        4: { name: 'Ana Rodriguez', zone: 'Zone B', max_hours: 30 }
    };
    
    const tanod = tanodData[tanodId];
    if (tanod) {
        document.getElementById('tanodId').value = tanodId;
        document.getElementById('tanodName').textContent = tanod.name;
        document.getElementById('tanodZone').textContent = tanod.zone;
        document.getElementById('maxHours').value = tanod.max_hours;
        
        // Reset form
        document.getElementById('availabilityForm').reset();
        document.getElementById('unavailableDates').innerHTML = '';
        unavailableDateCount = 0;
        
        document.getElementById('availabilityModal').classList.remove('hidden');
    }
}

// Toggle day availability
function toggleDayAvailability(day) {
    const checkbox = document.querySelector(`input[name="available_${day}"]`);
    const shiftsDiv = document.getElementById(`shifts_${day}`);
    
    if (checkbox.checked) {
        shiftsDiv.classList.remove('opacity-50');
        shiftsDiv.querySelectorAll('input[type="checkbox"]').forEach(input => {
            input.disabled = false;
        });
    } else {
        shiftsDiv.classList.add('opacity-50');
        shiftsDiv.querySelectorAll('input[type="checkbox"]').forEach(input => {
            input.disabled = true;
            input.checked = false;
        });
    }
}

// Add unavailable date range
function addUnavailableDate() {
    unavailableDateCount++;
    const dateRangeHtml = `
        <div id="dateRange_${unavailableDateCount}" class="flex items-center space-x-3 glass rounded-lg p-3">
            <input type="date" name="unavailable_start_${unavailableDateCount}" 
                   class="px-3 py-2 glass rounded text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
            <span class="text-gray-400">to</span>
            <input type="date" name="unavailable_end_${unavailableDateCount}" 
                   class="px-3 py-2 glass rounded text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
            <input type="text" name="unavailable_reason_${unavailableDateCount}" placeholder="Reason (optional)" 
                   class="flex-1 px-3 py-2 glass rounded text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
            <button type="button" onclick="removeUnavailableDate(${unavailableDateCount})" 
                    class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded transition">
                🗑️
            </button>
        </div>
    `;
    document.getElementById('unavailableDates').insertAdjacentHTML('beforeend', dateRangeHtml);
}

// Remove unavailable date range
function removeUnavailableDate(id) {
    document.getElementById(`dateRange_${id}`).remove();
}

// Bulk update availability
function bulkUpdateAvailability() {
    document.getElementById('bulkUpdateModal').classList.remove('hidden');
}

// Close modals
function closeAvailabilityModal() {
    document.getElementById('availabilityModal').classList.add('hidden');
}

function closeBulkUpdateModal() {
    document.getElementById('bulkUpdateModal').classList.add('hidden');
}

// Initialize day availability toggles
document.addEventListener('DOMContentLoaded', function() {
    const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    days.forEach(day => {
        const checkbox = document.querySelector(`input[name="available_${day}"]`);
        if (checkbox) {
            toggleDayAvailability(day);
        }
    });
});
</script>

<style>
/* Custom scrollbar */
.overflow-y-auto {
    scrollbar-width: thin;
    scrollbar-color: rgba(249, 115, 22, 0.5) rgba(255, 255, 255, 0.1);
}

.overflow-y-auto::-webkit-scrollbar {
    width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: rgba(249, 115, 22, 0.5);
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: rgba(249, 115, 22, 0.7);
}
</style>

<?php require_once '../includes/footer.php'; ?>