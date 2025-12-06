<?php
// patrols/calendar.php - Enhanced with Auto-Generate Feature
require_once '../config.php';
$pageTitle = 'Patrol Calendar';
require_once '../includes/header.php';

$success = '';
if (isset($_GET['generated']) && $_GET['generated'] === 'success') {
    $success = 'Patrol schedule generated successfully!';
}
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-white">Patrol Calendar</h1>
        <p class="text-gray-400">Schedule and manage patrol assignments</p>
    </div>
    <div class="flex space-x-2">
        <a href="list.php" class="px-4 py-2 glass text-white rounded-lg hover:bg-white/10 transition">📋 List View</a>
        <a href="tanod_availability.php" class="px-4 py-2 glass text-white rounded-lg hover:bg-white/10 transition">⏰ Availability</a>
        <button onclick="openAutoGenerateModal()" class="px-4 py-2 bg-gradient-to-r from-orange-500 to-green-500 hover:from-orange-600 hover:to-green-600 text-white rounded-lg transition">
            🤖 Auto-Generate Patrol Schedule
        </button>
        <a href="create.php" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
            ➕ Schedule Patrol
        </a>
    </div>
</div>

<?php if ($success): ?>
<div class="bg-green-500/20 border border-green-500 text-green-200 px-4 py-3 rounded-lg mb-6">
    <?php echo htmlspecialchars($success); ?>
</div>
<?php endif; ?>

<!-- Calendar Controls -->
<div class="glass rounded-2xl p-6 mb-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <h2 class="text-2xl font-bold text-white">December 2025</h2>
            <div class="flex space-x-2">
                <button class="px-4 py-2 glass text-white rounded-lg hover:bg-white/10 transition">← Previous</button>
                <button class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">Today</button>
                <button class="px-4 py-2 glass text-white rounded-lg hover:bg-white/10 transition">Next →</button>
            </div>
        </div>
        
        <!-- View Options -->
        <div class="flex items-center space-x-4">
            <div class="flex bg-white/10 rounded-lg p-1">
                <button class="px-3 py-2 bg-orange-500 text-white rounded text-sm">Month</button>
                <button class="px-3 py-2 text-gray-400 hover:text-white rounded text-sm">Week</button>
                <button class="px-3 py-2 text-gray-400 hover:text-white rounded text-sm">Day</button>
            </div>
            
            <!-- Filter -->
            <select class="px-3 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="">All Zones</option>
                <option value="A">Zone A</option>
                <option value="B">Zone B</option>
                <option value="C">Zone C</option>
                <option value="D">Zone D</option>
            </select>
        </div>
    </div>
</div>

<!-- Calendar Grid -->
<div class="glass rounded-2xl p-6">
    <div class="grid grid-cols-7 gap-2">
        <!-- Day Headers -->
        <div class="text-center text-gray-400 font-bold py-3 border-b border-white/10">Sun</div>
        <div class="text-center text-gray-400 font-bold py-3 border-b border-white/10">Mon</div>
        <div class="text-center text-gray-400 font-bold py-3 border-b border-white/10">Tue</div>
        <div class="text-center text-gray-400 font-bold py-3 border-b border-white/10">Wed</div>
        <div class="text-center text-gray-400 font-bold py-3 border-b border-white/10">Thu</div>
        <div class="text-center text-gray-400 font-bold py-3 border-b border-white/10">Fri</div>
        <div class="text-center text-gray-400 font-bold py-3 border-b border-white/10">Sat</div>
        
        <!-- Calendar Days -->
        <?php 
        $currentDate = 1;
        $daysInMonth = 31;
        
        for ($week = 0; $week < 5; $week++): 
            for ($day = 0; $day < 7; $day++): 
                if ($currentDate <= $daysInMonth):
        ?>
        <div class="glass rounded-lg p-3 min-h-32 hover:bg-white/10 cursor-pointer transition border border-white/5" 
             onclick="openDayDetails(<?php echo $currentDate; ?>)">
            <div class="flex justify-between items-start mb-2">
                <span class="text-white font-bold text-lg"><?php echo $currentDate; ?></span>
                <?php if ($currentDate == 2): ?>
                <span class="text-xs bg-green-500/20 text-green-400 px-2 py-1 rounded">Auto</span>
                <?php endif; ?>
            </div>
            
            <!-- Sample Patrol Assignments -->
            <?php if ($currentDate == 2): ?>
            <div class="space-y-1">
                <div class="text-xs bg-orange-500/20 text-orange-400 rounded px-2 py-1 flex items-center justify-between">
                    <span>Zone A - 06:00</span>
                    <span class="text-xs">Juan, Maria</span>
                </div>
                <div class="text-xs bg-green-500/20 text-green-400 rounded px-2 py-1 flex items-center justify-between">
                    <span>Zone B - 14:00</span>
                    <span class="text-xs">Pedro, Ana</span>
                </div>
                <div class="text-xs bg-blue-500/20 text-blue-400 rounded px-2 py-1 flex items-center justify-between">
                    <span>Zone C - 22:00</span>
                    <span class="text-xs">Carlos, Lisa</span>
                </div>
            </div>
            <?php elseif ($currentDate == 3): ?>
            <div class="space-y-1">
                <div class="text-xs bg-purple-500/20 text-purple-400 rounded px-2 py-1 flex items-center justify-between">
                    <span>Zone D - 06:00</span>
                    <span class="text-xs">Mike, Sara</span>
                </div>
                <div class="text-xs bg-orange-500/20 text-orange-400 rounded px-2 py-1 flex items-center justify-between">
                    <span>Zone A - 18:00</span>
                    <span class="text-xs">Juan, Pedro</span>
                </div>
            </div>
            <?php elseif ($currentDate == 15): ?>
            <div class="space-y-1">
                <div class="text-xs bg-red-500/20 text-red-400 rounded px-2 py-1 flex items-center justify-between">
                    <span>Zone B - 10:00</span>
                    <span class="text-xs">CONFLICT</span>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php 
                $currentDate++;
                endif;
            endfor;
        endfor; 
        ?>
    </div>
</div>

<!-- Legend -->
<div class="mt-6 glass rounded-2xl p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-white font-bold">Legend</h3>
        <div class="flex items-center space-x-4 text-sm">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-500 rounded"></div>
                <span class="text-gray-400">Auto-generated</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-red-500 rounded"></div>
                <span class="text-gray-400">Conflicts detected</span>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="flex items-center space-x-2">
            <div class="w-4 h-4 bg-orange-500 rounded"></div>
            <span class="text-white text-sm">Zone A</span>
            <span class="text-gray-400 text-xs">(Northern)</span>
        </div>
        <div class="flex items-center space-x-2">
            <div class="w-4 h-4 bg-green-500 rounded"></div>
            <span class="text-white text-sm">Zone B</span>
            <span class="text-gray-400 text-xs">(Eastern)</span>
        </div>
        <div class="flex items-center space-x-2">
            <div class="w-4 h-4 bg-blue-500 rounded"></div>
            <span class="text-white text-sm">Zone C</span>
            <span class="text-gray-400 text-xs">(Southern)</span>
        </div>
        <div class="flex items-center space-x-2">
            <div class="w-4 h-4 bg-purple-500 rounded"></div>
            <span class="text-white text-sm">Zone D</span>
            <span class="text-gray-400 text-xs">(Western)</span>
        </div>
    </div>
</div>

<!-- Auto-Generate Modal -->
<div id="autoGenerateModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="glass rounded-2xl p-6 max-w-2xl w-full">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white">🤖 Auto-Generate Patrol Schedule</h3>
                <button onclick="closeAutoGenerateModal()" class="text-gray-400 hover:text-white">✕</button>
            </div>
            
            <form id="autoGenerateForm" method="POST" action="auto_generate.php">
                <div class="space-y-6">
                    <!-- Date Range -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-white mb-2">Start Date *</label>
                            <input type="date" name="start_date" required 
                                   class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-white mb-2">End Date *</label>
                            <input type="date" name="end_date" required 
                                   class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                    </div>
                    
                    <!-- Shift Selection -->
                    <div>
                        <label class="block text-white mb-3">Shifts to Generate *</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="flex items-center space-x-3 glass rounded-lg p-4 cursor-pointer hover:bg-white/10">
                                <input type="checkbox" name="shifts[]" value="morning" 
                                       class="w-4 h-4 text-orange-500 bg-transparent border-gray-300 rounded focus:ring-orange-500">
                                <div>
                                    <div class="text-white font-medium">Morning Shift</div>
                                    <div class="text-gray-400 text-sm">6 AM - 2 PM</div>
                                </div>
                            </label>
                            <label class="flex items-center space-x-3 glass rounded-lg p-4 cursor-pointer hover:bg-white/10">
                                <input type="checkbox" name="shifts[]" value="afternoon" 
                                       class="w-4 h-4 text-orange-500 bg-transparent border-gray-300 rounded focus:ring-orange-500">
                                <div>
                                    <div class="text-white font-medium">Afternoon Shift</div>
                                    <div class="text-gray-400 text-sm">2 PM - 10 PM</div>
                                </div>
                            </label>
                            <label class="flex items-center space-x-3 glass rounded-lg p-4 cursor-pointer hover:bg-white/10">
                                <input type="checkbox" name="shifts[]" value="night" 
                                       class="w-4 h-4 text-orange-500 bg-transparent border-gray-300 rounded focus:ring-orange-500">
                                <div>
                                    <div class="text-white font-medium">Night Shift</div>
                                    <div class="text-gray-400 text-sm">10 PM - 6 AM</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Algorithm Options -->
                    <div>
                        <label class="block text-white mb-3">Algorithm Preferences</label>
                        <div class="space-y-3">
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="algorithm_options[]" value="prioritize_proximity" checked 
                                       class="w-4 h-4 text-orange-500 bg-transparent border-gray-300 rounded focus:ring-orange-500">
                                <span class="text-white">✓ Prioritize proximity (assign tanods near their zone)</span>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="algorithm_options[]" value="balance_workload" checked 
                                       class="w-4 h-4 text-orange-500 bg-transparent border-gray-300 rounded focus:ring-orange-500">
                                <span class="text-white">✓ Balance workload evenly</span>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="algorithm_options[]" value="respect_availability" checked 
                                       class="w-4 h-4 text-orange-500 bg-transparent border-gray-300 rounded focus:ring-orange-500">
                                <span class="text-white">✓ Respect availability preferences</span>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="algorithm_options[]" value="minimum_coverage" checked 
                                       class="w-4 h-4 text-orange-500 bg-transparent border-gray-300 rounded focus:ring-orange-500">
                                <span class="text-white">✓ Ensure minimum 2 tanods per zone per shift</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Advanced Options -->
                    <div class="glass rounded-lg p-4">
                        <h4 class="text-white font-medium mb-3">Advanced Options</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-400 mb-2">Max Hours per Tanod/Week</label>
                                <input type="number" name="max_hours_per_week" value="40" min="20" max="60" 
                                       class="w-full px-3 py-2 glass rounded text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                            </div>
                            <div>
                                <label class="block text-gray-400 mb-2">Min Tanods per Zone/Shift</label>
                                <input type="number" name="min_tanods_per_shift" value="2" min="1" max="5" 
                                       class="w-full px-3 py-2 glass rounded text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end space-x-4">
                    <button type="button" onclick="closeAutoGenerateModal()" 
                            class="px-6 py-3 glass rounded-lg text-white hover:bg-white/10 transition">
                        Cancel
                    </button>
                    <button type="button" onclick="generatePreview()" 
                            class="px-6 py-3 bg-gradient-to-r from-orange-500 to-green-500 hover:from-orange-600 hover:to-green-600 text-white rounded-lg transition">
                        Generate Preview
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="glass rounded-2xl p-6 max-w-6xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white">📋 Generated Schedule Preview</h3>
                <button onclick="closePreviewModal()" class="text-gray-400 hover:text-white">✕</button>
            </div>
            
            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="glass rounded-lg p-4">
                    <div class="text-gray-400 text-sm">Total Assignments</div>
                    <div class="text-2xl font-bold text-white">84</div>
                </div>
                <div class="glass rounded-lg p-4">
                    <div class="text-gray-400 text-sm">Avg Hours/Tanod</div>
                    <div class="text-2xl font-bold text-white">38.5</div>
                </div>
                <div class="glass rounded-lg p-4">
                    <div class="text-gray-400 text-sm">Zone Coverage</div>
                    <div class="text-2xl font-bold text-green-400">98%</div>
                </div>
                <div class="glass rounded-lg p-4">
                    <div class="text-gray-400 text-sm">Conflicts</div>
                    <div class="text-2xl font-bold text-red-400">2</div>
                </div>
            </div>
            
            <!-- Conflicts Alert -->
            <div id="conflictsAlert" class="bg-red-500/20 border border-red-500 text-red-200 px-4 py-3 rounded-lg mb-6">
                <h4 class="font-bold mb-2">⚠️ Conflicts Detected</h4>
                <ul class="text-sm space-y-1">
                    <li>• Dec 15: Juan Dela Cruz assigned to overlapping shifts in Zone A</li>
                    <li>• Dec 18: Zone C has only 1 tanod assigned for night shift (minimum 2 required)</li>
                </ul>
            </div>
            
            <!-- Preview Calendar -->
            <div id="previewCalendar" class="glass rounded-lg p-4 mb-6">
                <h4 class="text-white font-bold mb-4">Schedule Preview (Drag to adjust)</h4>
                <!-- Mini calendar grid would go here -->
                <div class="text-gray-400 text-center py-8">
                    Interactive calendar preview would be displayed here...
                </div>
            </div>
            
            <!-- Tanod Workload Summary -->
            <div class="glass rounded-lg p-4 mb-6">
                <h4 class="text-white font-bold mb-4">Tanod Workload Summary</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-white/10">
                                <th class="text-left text-gray-400 py-2">Tanod</th>
                                <th class="text-left text-gray-400 py-2">Total Hours</th>
                                <th class="text-left text-gray-400 py-2">Assignments</th>
                                <th class="text-left text-gray-400 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-white/5">
                                <td class="py-2 text-white">Juan Dela Cruz</td>
                                <td class="py-2 text-white">40 hrs</td>
                                <td class="py-2 text-white">5 shifts</td>
                                <td class="py-2"><span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs">Optimal</span></td>
                            </tr>
                            <tr class="border-b border-white/5">
                                <td class="py-2 text-white">Maria Santos</td>
                                <td class="py-2 text-white">35 hrs</td>
                                <td class="py-2 text-white">4 shifts</td>
                                <td class="py-2"><span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs">Optimal</span></td>
                            </tr>
                            <tr class="border-b border-white/5">
                                <td class="py-2 text-white">Pedro Garcia</td>
                                <td class="py-2 text-white">48 hrs</td>
                                <td class="py-2 text-white">6 shifts</td>
                                <td class="py-2"><span class="px-2 py-1 bg-orange-500/20 text-orange-400 rounded text-xs">Over limit</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4">
                <button onclick="regenerateSchedule()" 
                        class="px-6 py-3 glass rounded-lg text-white hover:bg-white/10 transition">
                    🔄 Regenerate
                </button>
                <button onclick="closePreviewModal()" 
                        class="px-6 py-3 glass rounded-lg text-white hover:bg-white/10 transition">
                    Cancel
                </button>
                <button onclick="confirmAndSave()" 
                        class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
                    ✓ Confirm & Save Schedule
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Day Details Modal -->
<div id="dayDetailsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="glass rounded-2xl p-6 max-w-2xl w-full">
            <div class="flex items-center justify-between mb-6">
                <h3 id="dayDetailsTitle" class="text-xl font-bold text-white">December 2, 2025</h3>
                <button onclick="closeDayDetailsModal()" class="text-gray-400 hover:text-white">✕</button>
            </div>
            
            <div id="dayDetailsContent" class="space-y-4">
                <!-- Day details will be loaded here -->
            </div>
            
            <div class="mt-6 flex justify-end space-x-4">
                <button onclick="closeDayDetailsModal()" 
                        class="px-6 py-3 glass rounded-lg text-white hover:bg-white/10 transition">
                    Close
                </button>
                <a href="create.php" 
                   class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                    Add New Patrol
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Open auto-generate modal
function openAutoGenerateModal() {
    // Set default dates (next week)
    const today = new Date();
    const nextWeek = new Date(today);
    nextWeek.setDate(today.getDate() + 7);
    const endDate = new Date(nextWeek);
    endDate.setDate(nextWeek.getDate() + 6);
    
    document.querySelector('input[name="start_date"]').value = nextWeek.toISOString().split('T')[0];
    document.querySelector('input[name="end_date"]').value = endDate.toISOString().split('T')[0];
    
    document.getElementById('autoGenerateModal').classList.remove('hidden');
}

// Close modals
function closeAutoGenerateModal() {
    document.getElementById('autoGenerateModal').classList.add('hidden');
}

function closePreviewModal() {
    document.getElementById('previewModal').classList.add('hidden');
}

function closeDayDetailsModal() {
    document.getElementById('dayDetailsModal').classList.add('hidden');
}

// Generate preview
function generatePreview() {
    // Validate form
    const startDate = document.querySelector('input[name="start_date"]').value;
    const endDate = document.querySelector('input[name="end_date"]').value;
    const selectedShifts = document.querySelectorAll('input[name="shifts[]"]:checked');
    
    if (!startDate || !endDate) {
        alert('Please select start and end dates');
        return;
    }
    
    if (selectedShifts.length === 0) {
        alert('Please select at least one shift');
        return;
    }
    
    // Show loading
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '🔄 Generating...';
    button.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        
        // Close auto-generate modal and show preview
        closeAutoGenerateModal();
        document.getElementById('previewModal').classList.remove('hidden');
    }, 2000);
}

// Regenerate schedule
function regenerateSchedule() {
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '🔄 Regenerating...';
    button.disabled = true;
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        
        // Update statistics
        document.querySelector('#previewModal .text-red-400').textContent = '0';
        document.getElementById('conflictsAlert').style.display = 'none';
    }, 1500);
}

// Confirm and save schedule
function confirmAndSave() {
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '💾 Saving...';
    button.disabled = true;
    
    setTimeout(() => {
        closePreviewModal();
        window.location.href = 'calendar.php?generated=success';
    }, 1000);
}

// Open day details
function openDayDetails(day) {
    document.getElementById('dayDetailsTitle').textContent = `December ${day}, 2025`;
    
    // Sample day details
    const dayDetails = {
        2: `
            <div class="space-y-4">
                <div class="glass rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-white font-bold">Morning Shift (6:00 AM - 2:00 PM)</h4>
                        <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs">Auto-generated</span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Zone A:</span>
                            <span class="text-white">Juan Dela Cruz, Maria Santos</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Zone B:</span>
                            <span class="text-white">Pedro Garcia, Ana Rodriguez</span>
                        </div>
                    </div>
                </div>
                
                <div class="glass rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-white font-bold">Afternoon Shift (2:00 PM - 10:00 PM)</h4>
                        <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs">Auto-generated</span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Zone C:</span>
                            <span class="text-white">Carlos Martinez, Lisa Wong</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Zone D:</span>
                            <span class="text-white">Mike Johnson, Sara Lee</span>
                        </div>
                    </div>
                </div>
            </div>
        `,
        15: `
            <div class="space-y-4">
                <div class="bg-red-500/20 border border-red-500 rounded-lg p-4">
                    <h4 class="text-red-400 font-bold mb-2">⚠️ Scheduling Conflict Detected</h4>
                    <p class="text-red-200 text-sm">Zone B morning shift has conflicting assignments. Please resolve manually.</p>
                </div>
                
                <div class="glass rounded-lg p-4">
                    <h4 class="text-white font-bold mb-3">Conflicting Assignments</h4>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Zone B (10:00 AM):</span>
                            <span class="text-red-400">Pedro Garcia (already assigned to Zone A)</span>
                        </div>
                    </div>
                </div>
            </div>
        `
    };
    
    document.getElementById('dayDetailsContent').innerHTML = dayDetails[day] || `
        <div class="text-center text-gray-400 py-8">
            <div class="text-4xl mb-4">📅</div>
            <p>No patrol assignments for this day.</p>
            <p class="text-sm mt-2">Click "Add New Patrol" to schedule patrols for this date.</p>
        </div>
    `;
    
    document.getElementById('dayDetailsModal').classList.remove('hidden');
}
</script>

<style>
/* Custom scrollbar for modals */
#previewModal .overflow-y-auto,
#autoGenerateModal .overflow-y-auto {
    scrollbar-width: thin;
    scrollbar-color: rgba(249, 115, 22, 0.5) rgba(255, 255, 255, 0.1);
}

#previewModal .overflow-y-auto::-webkit-scrollbar,
#autoGenerateModal .overflow-y-auto::-webkit-scrollbar {
    width: 8px;
}

#previewModal .overflow-y-auto::-webkit-scrollbar-track,
#autoGenerateModal .overflow-y-auto::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}

#previewModal .overflow-y-auto::-webkit-scrollbar-thumb,
#autoGenerateModal .overflow-y-auto::-webkit-scrollbar-thumb {
    background: rgba(249, 115, 22, 0.5);
    border-radius: 4px;
}

#previewModal .overflow-y-auto::-webkit-scrollbar-thumb:hover,
#autoGenerateModal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: rgba(249, 115, 22, 0.7);
}

/* Gradient button animation */
.bg-gradient-to-r {
    background-size: 200% 200%;
    animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
</style>

<?php require_once '../includes/footer.php'; ?>