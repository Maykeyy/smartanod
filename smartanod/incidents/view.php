<?php
// incidents/view.php - Enhanced with Mini Map
require_once '../config.php';
$pageTitle = 'View Incident';
require_once '../includes/header.php';

// Sample incident data - in real implementation, fetch from database based on ID
$incident = [
    'id' => 'INC-2025-001',
    'date_time' => '2025-11-28 14:30:00',
    'category' => 'Noise Complaint',
    'priority' => 'Medium',
    'status' => 'Pending',
    'zone' => 'Zone A',
    'location' => 'Purok 2, Malabanban Sur',
    'latitude' => 13.9315,
    'longitude' => 121.4235,
    'reporter_type' => 'Walk-in',
    'reporter_name' => 'Maria Santos',
    'reporter_contact' => '09123456789',
    'involved_parties' => 'Neighbor playing loud music during rest hours',
    'narrative' => 'Resident complained about excessive noise from neighbor\'s karaoke session during afternoon rest period. Multiple residents affected.',
    'assigned_tanod' => 'Juan Dela Cruz',
    'evidence_files' => ['/images/NoiseComplaint.jpg', 'witness_statement.pdf']
];
?>

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Incident Details</h1>
            <p class="text-gray-400">ID: <?php echo htmlspecialchars($incident['id']); ?></p>
        </div>
        <div class="flex space-x-3">
            <a href="index.php" class="px-4 py-2 glass hover:bg-white/10 text-white rounded-lg transition">
                ← Back to List
            </a>
            <a href="edit.php?id=<?php echo urlencode($_GET['id'] ?? '1'); ?>" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                ✏️ Edit Incident
            </a>
            <button onclick="printIncident()" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
                🖨️ Print Report
            </button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="glass rounded-2xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-400 text-sm mb-1">Date & Time</label>
                    <div class="text-white font-medium"><?php echo date('M j, Y g:i A', strtotime($incident['date_time'])); ?></div>
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-1">Category</label>
                    <div class="text-white font-medium"><?php echo htmlspecialchars($incident['category']); ?></div>
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-1">Priority</label>
                    <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-sm">
                        <?php echo htmlspecialchars($incident['priority']); ?>
                    </span>
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-1">Status</label>
                    <span class="px-3 py-1 bg-orange-500/20 text-orange-400 rounded-full text-sm">
                        <?php echo htmlspecialchars($incident['status']); ?>
                    </span>
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-1">Zone</label>
                    <div class="text-white font-medium"><?php echo htmlspecialchars($incident['zone']); ?></div>
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-1">Assigned Tanod</label>
                    <div class="text-white font-medium"><?php echo htmlspecialchars($incident['assigned_tanod']); ?></div>
                </div>
            </div>
        </div>

        <!-- Location Details -->
        <div class="glass rounded-2xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Location Details</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-400 text-sm mb-1">Address</label>
                    <div class="text-white font-medium"><?php echo htmlspecialchars($incident['location']); ?></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-400 text-sm mb-1">Latitude</label>
                        <div class="text-white font-medium"><?php echo $incident['latitude']; ?></div>
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm mb-1">Longitude</label>
                        <div class="text-white font-medium"><?php echo $incident['longitude']; ?></div>
                    </div>
                </div>
                <button onclick="viewOnFullMap()" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition">
                    🗺️ View on Full Map
                </button>
            </div>
        </div>

        <!-- Reporter Information -->
        <div class="glass rounded-2xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Reporter Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-400 text-sm mb-1">Reporter Type</label>
                    <div class="text-white font-medium"><?php echo htmlspecialchars($incident['reporter_type']); ?></div>
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-1">Reporter Name</label>
                    <div class="text-white font-medium"><?php echo htmlspecialchars($incident['reporter_name']); ?></div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-400 text-sm mb-1">Contact Information</label>
                    <div class="text-white font-medium"><?php echo htmlspecialchars($incident['reporter_contact']); ?></div>
                </div>
            </div>
        </div>

        <!-- Incident Details -->
        <div class="glass rounded-2xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Incident Details</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-400 text-sm mb-2">Involved Parties</label>
                    <div class="text-white"><?php echo htmlspecialchars($incident['involved_parties']); ?></div>
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-2">Incident Narrative</label>
                    <div class="text-white leading-relaxed"><?php echo nl2br(htmlspecialchars($incident['narrative'])); ?></div>
                </div>
            </div>
        </div>

        <!-- Actions Log -->
        <div class="glass rounded-2xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Actions & Updates</h2>
            <div class="space-y-4">
                <div class="border-l-4 border-orange-500 pl-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-white font-medium">Incident Created</div>
                            <div class="text-gray-400 text-sm">Initial report filed by <?php echo htmlspecialchars($incident['reporter_name']); ?></div>
                        </div>
                        <div class="text-gray-400 text-sm">Nov 28, 2:30 PM</div>
                    </div>
                </div>
                <div class="border-l-4 border-blue-500 pl-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-white font-medium">Tanod Assigned</div>
                            <div class="text-gray-400 text-sm">Assigned to <?php echo htmlspecialchars($incident['assigned_tanod']); ?></div>
                        </div>
                        <div class="text-gray-400 text-sm">Nov 28, 2:45 PM</div>
                    </div>
                </div>
                <div class="border-l-4 border-green-500 pl-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-white font-medium">Investigation Started</div>
                            <div class="text-gray-400 text-sm">Tanod arrived at location and began investigation</div>
                        </div>
                        <div class="text-gray-400 text-sm">Nov 28, 3:15 PM</div>
                    </div>
                </div>
            </div>
            
            <!-- Add New Action -->
            <div class="mt-6 pt-4 border-t border-white/10">
                <button onclick="openAddActionModal()" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
                    ➕ Add Update
                </button>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Location Map -->
        <div class="glass rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-white">Location Map</h3>
                <button onclick="viewOnFullMap()" class="text-blue-400 hover:text-blue-300 text-sm">
                    🔍 Expand
                </button>
            </div>
            <div id="miniMap" class="w-full h-48 rounded-lg overflow-hidden border border-white/20 mb-3">
                <!-- Mini map will be loaded here -->
            </div>
            <div class="text-center">
                <button onclick="viewOnFullMap()" class="text-blue-400 hover:text-blue-300 text-sm underline">
                    View on Full Map →
                </button>
            </div>
        </div>

        <!-- Evidence Gallery -->
        <div class="glass rounded-2xl p-6">
            <h3 class="text-lg font-bold text-white mb-4">Evidence Gallery</h3>
            <div class="space-y-3">
                <?php foreach ($incident['evidence_files'] as $index => $file): ?>
                <div class="flex items-center space-x-3 p-3 glass rounded-lg hover:bg-white/10 cursor-pointer">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold">
                        <?php echo strtoupper(pathinfo($file, PATHINFO_EXTENSION)); ?>
                    </div>
                    <div class="flex-1">
                        <div class="text-white text-sm font-medium"><?php echo htmlspecialchars($file); ?></div>
                        <div class="text-gray-400 text-xs">Uploaded Nov 28, 2:30 PM</div>
                    </div>
                    <button class="text-blue-400 hover:text-blue-300">
                        📥
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-4 pt-4 border-t border-white/10">
                <button onclick="openUploadModal()" class="w-full px-4 py-2 glass hover:bg-white/10 text-white rounded-lg transition">
                    ➕ Upload Evidence
                </button>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="glass rounded-2xl p-6">
            <h3 class="text-lg font-bold text-white mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <button onclick="changeStatus()" class="w-full px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                    🔄 Change Status
                </button>
                <button onclick="assignTanod()" class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
                    👤 Assign Tanod
                </button>
                <button onclick="sendNotification()" class="w-full px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition">
                    📢 Send Notification
                </button>
                <button onclick="generateReport()" class="w-full px-4 py-2 glass hover:bg-white/10 text-white rounded-lg transition">
                    📄 Generate Report
                </button>
            </div>
        </div>

        <!-- Related Incidents -->
        <div class="glass rounded-2xl p-6">
            <h3 class="text-lg font-bold text-white mb-4">Related Incidents</h3>
            <div class="space-y-3">
                <div class="p-3 glass rounded-lg">
                    <div class="text-white text-sm font-medium">INC-2025-002</div>
                    <div class="text-gray-400 text-xs">Noise Complaint - Zone A</div>
                    <div class="text-gray-400 text-xs">Nov 25, 2025</div>
                </div>
                <div class="p-3 glass rounded-lg">
                    <div class="text-white text-sm font-medium">INC-2025-005</div>
                    <div class="text-gray-400 text-xs">Dispute - Zone A</div>
                    <div class="text-gray-400 text-xs">Nov 20, 2025</div>
                </div>
            </div>
            <div class="mt-3 text-center">
                <a href="index.php?zone=A" class="text-blue-400 hover:text-blue-300 text-sm underline">
                    View all Zone A incidents →
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Include Leaflet.js for mini map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let miniMap;

// Initialize mini map
function initMiniMap() {
    const lat = <?php echo $incident['latitude']; ?>;
    const lng = <?php echo $incident['longitude']; ?>;
    
    miniMap = L.map('miniMap', {
        zoomControl: false,
        dragging: false,
        touchZoom: false,
        doubleClickZoom: false,
        scrollWheelZoom: false,
        boxZoom: false,
        keyboard: false
    }).setView([lat, lng], 16);
    
    // Dark theme tile layer
    L.tileLayer('/images/MapTiles.jpg', {
        attribution: '© SmartTanod System'
    }).addTo(miniMap);
    
    // Add zone boundary
    const zoneBoundary = getZoneBoundary('<?php echo $incident['zone']; ?>');
    if (zoneBoundary) {
        L.polygon(zoneBoundary, {
            color: getZoneColor('<?php echo $incident['zone']; ?>'),
            fillColor: getZoneColor('<?php echo $incident['zone']; ?>'),
            fillOpacity: 0.3,
            weight: 2
        }).addTo(miniMap);
    }
    
    // Add incident marker
    L.marker([lat, lng], {
        icon: L.divIcon({
            className: 'incident-marker',
            html: `
                <div style="
                    background: #ef4444; 
                    width: 20px; 
                    height: 20px; 
                    border-radius: 50%; 
                    border: 3px solid white;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.5);
                "></div>
            `,
            iconSize: [26, 26],
            iconAnchor: [13, 13]
        })
    }).addTo(miniMap);
}

// Get zone boundary coordinates
function getZoneBoundary(zone) {
    const boundaries = {
        'Zone A': [[13.9320, 121.4210], [13.9340, 121.4240], [13.9310, 121.4260], [13.9290, 121.4230]],
        'Zone B': [[13.9340, 121.4240], [13.9360, 121.4270], [13.9330, 121.4290], [13.9310, 121.4260]],
        'Zone C': [[13.9290, 121.4230], [13.9310, 121.4260], [13.9280, 121.4280], [13.9260, 121.4250]],
        'Zone D': [[13.9310, 121.4260], [13.9330, 121.4290], [13.9300, 121.4310], [13.9280, 121.4280]]
    };
    return boundaries[zone];
}

// Get zone color
function getZoneColor(zone) {
    const colors = {
        'Zone A': '#f59e0b',
        'Zone B': '#10b981',
        'Zone C': '#3b82f6',
        'Zone D': '#a855f7'
    };
    return colors[zone] || '#6b7280';
}

// View on full map
function viewOnFullMap() {
    const lat = <?php echo $incident['latitude']; ?>;
    const lng = <?php echo $incident['longitude']; ?>;
    const incidentId = '<?php echo $incident['id']; ?>';
    
    window.open(`../map/index.php?lat=${lat}&lng=${lng}&incident=${incidentId}`, '_blank');
}

// Quick action functions
function changeStatus() {
    // In real implementation, open status change modal
    alert('Status change functionality would open a modal here');
}

function assignTanod() {
    // In real implementation, open tanod assignment modal
    alert('Tanod assignment functionality would open a modal here');
}

function sendNotification() {
    // In real implementation, open notification modal
    alert('Notification functionality would open a modal here');
}

function generateReport() {
    // In real implementation, generate PDF report
    alert('Report generation functionality would create a PDF here');
}

function printIncident() {
    window.print();
}

function openAddActionModal() {
    alert('Add action modal would open here');
}

function openUploadModal() {
    alert('Evidence upload modal would open here');
}

// Initialize mini map when page loads
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initMiniMap, 100);
});
</script>

<style>
.incident-marker {
    background: none !important;
    border: none !important;
}

/* Print styles */
@media print {
    .glass {
        background: white !important;
        color: black !important;
        border: 1px solid #ccc !important;
    }
    
    .text-white {
        color: black !important;
    }
    
    .text-gray-400 {
        color: #666 !important;
    }
    
    button, .bg-orange-500, .bg-green-500, .bg-blue-500 {
        display: none !important;
    }
}

/* Custom scrollbar */
.space-y-6 {
    scrollbar-width: thin;
    scrollbar-color: rgba(249, 115, 22, 0.5) rgba(255, 255, 255, 0.1);
}

.space-y-6::-webkit-scrollbar {
    width: 8px;
}

.space-y-6::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}

.space-y-6::-webkit-scrollbar-thumb {
    background: rgba(249, 115, 22, 0.5);
    border-radius: 4px;
}

.space-y-6::-webkit-scrollbar-thumb:hover {
    background: rgba(249, 115, 22, 0.7);
}
</style>

<?php require_once '../includes/footer.php'; ?>