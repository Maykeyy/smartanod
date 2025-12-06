<?php
// index.php - Enhanced Dashboard with Map Widgets
require_once 'config.php';
$pageTitle = 'Dashboard';
require_once 'includes/header.php';
?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Dashboard</h1>
    <p class="text-gray-400">Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</p>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
    <div class="glass rounded-2xl p-6">
        <div class="text-gray-400 text-sm mb-2">Total Incidents</div>
        <div class="text-3xl font-bold text-white mb-1">145</div>
        <div class="text-green-400 text-sm">↑ 12% from last month</div>
    </div>
    
    <div class="glass rounded-2xl p-6">
        <div class="text-gray-400 text-sm mb-2">Resolved Rate</div>
        <div class="text-3xl font-bold text-white mb-1">87%</div>
        <div class="text-green-400 text-sm">↑ 5% improvement</div>
    </div>
    
    <div class="glass rounded-2xl p-6">
        <div class="text-gray-400 text-sm mb-2">Avg Response Time</div>
        <div class="text-3xl font-bold text-white mb-1">23 min</div>
        <div class="text-orange-400 text-sm">→ Same as last month</div>
    </div>
    
    <div class="glass rounded-2xl p-6">
        <div class="text-gray-400 text-sm mb-2">Zone Coverage</div>
        <div class="text-3xl font-bold text-white mb-1">95%</div>
        <div class="text-green-400 text-sm">↑ 8% improvement</div>
    </div>
    
    <div class="glass rounded-2xl p-6">
        <div class="text-gray-400 text-sm mb-2">Active Patrols</div>
        <div class="text-3xl font-bold text-white mb-1">8</div>
        <div class="text-blue-400 text-sm">Currently on duty</div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Zone Map Widget -->
    <div class="lg:col-span-2 glass rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-white">Zone Overview</h2>
            <a href="<?php echo BASE_URL; ?>/map/index.php" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition text-sm">
                🗺️ View Full Map
            </a>
        </div>
        
        <div id="dashboardMap" class="w-full h-80 rounded-lg overflow-hidden border border-white/20 mb-4">
            <!-- Map will be loaded here -->
        </div>
        
        <!-- Real-time Stats -->
        <div class="grid grid-cols-4 gap-4 text-center">
            <div>
                <div class="text-orange-400 font-bold text-lg">3</div>
                <div class="text-gray-400 text-xs">Zone A Tanods</div>
            </div>
            <div>
                <div class="text-green-400 font-bold text-lg">3</div>
                <div class="text-gray-400 text-xs">Zone B Tanods</div>
            </div>
            <div>
                <div class="text-blue-400 font-bold text-lg">3</div>
                <div class="text-gray-400 text-xs">Zone C Tanods</div>
            </div>
            <div>
                <div class="text-purple-400 font-bold text-lg">3</div>
                <div class="text-gray-400 text-xs">Zone D Tanods</div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="space-y-4">
        <a href="<?php echo BASE_URL; ?>/incidents/create.php" class="block glass rounded-2xl p-6 hover:bg-white/10 transition">
            <div class="text-4xl mb-3">📝</div>
            <h3 class="text-xl font-bold text-white mb-2">New Incident</h3>
            <p class="text-gray-400 text-sm">Report a new incident with location mapping</p>
        </a>
        
        <a href="<?php echo BASE_URL; ?>/patrols/create.php" class="block glass rounded-2xl p-6 hover:bg-white/10 transition">
            <div class="text-4xl mb-3">🚶</div>
            <h3 class="text-xl font-bold text-white mb-2">Schedule Patrol</h3>
            <p class="text-gray-400 text-sm">Create patrol schedule or auto-generate</p>
        </a>
        
        <a href="<?php echo BASE_URL; ?>/kiosk/index.php" class="block glass rounded-2xl p-6 hover:bg-white/10 transition">
            <div class="text-4xl mb-3">🖥️</div>
            <h3 class="text-xl font-bold text-white mb-2">Kiosk Mode</h3>
            <p class="text-gray-400 text-sm">Citizen intake interface with map picker</p>
        </a>
    </div>
</div>

<!-- Active Patrols Section -->
<div class="glass rounded-2xl p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-white">Active Patrols</h2>
        <a href="<?php echo BASE_URL; ?>/patrols/calendar.php" class="text-orange-400 hover:text-orange-300 text-sm">
            View All Patrols →
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="text-left text-gray-400 py-3 px-4">Tanod</th>
                    <th class="text-left text-gray-400 py-3 px-4">Zone</th>
                    <th class="text-left text-gray-400 py-3 px-4">Start Time</th>
                    <th class="text-left text-gray-400 py-3 px-4">Status</th>
                    <th class="text-left text-gray-400 py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-white/5 hover:bg-white/5">
                    <td class="py-3 px-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white text-sm font-bold">J</div>
                            <span class="text-white">Juan Dela Cruz</span>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="px-3 py-1 bg-orange-500/20 text-orange-400 rounded-full text-sm">Zone A</span>
                    </td>
                    <td class="py-3 px-4 text-white">6:00 AM</td>
                    <td class="py-3 px-4">
                        <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm">On Patrol</span>
                    </td>
                    <td class="py-3 px-4">
                        <button onclick="viewPatrolOnMap('juan', 13.9315, 121.4235)" class="text-blue-400 hover:text-blue-300 text-sm">
                            🗺️ View on Map
                        </button>
                    </td>
                </tr>
                <tr class="border-b border-white/5 hover:bg-white/5">
                    <td class="py-3 px-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-bold">P</div>
                            <span class="text-white">Pedro Garcia</span>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm">Zone B</span>
                    </td>
                    <td class="py-3 px-4 text-white">2:00 PM</td>
                    <td class="py-3 px-4">
                        <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm">On Patrol</span>
                    </td>
                    <td class="py-3 px-4">
                        <button onclick="viewPatrolOnMap('pedro', 13.9345, 121.4255)" class="text-blue-400 hover:text-blue-300 text-sm">
                            🗺️ View on Map
                        </button>
                    </td>
                </tr>
                <tr class="border-b border-white/5 hover:bg-white/5">
                    <td class="py-3 px-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold">C</div>
                            <span class="text-white">Carlos Martinez</span>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-sm">Zone C</span>
                    </td>
                    <td class="py-3 px-4 text-white">10:00 PM</td>
                    <td class="py-3 px-4">
                        <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm">On Patrol</span>
                    </td>
                    <td class="py-3 px-4">
                        <button onclick="viewPatrolOnMap('carlos', 13.9275, 121.4265)" class="text-blue-400 hover:text-blue-300 text-sm">
                            🗺️ View on Map
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Incidents -->
<div class="glass rounded-2xl p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-white">Recent Incidents</h2>
        <a href="<?php echo BASE_URL; ?>/incidents/index.php" class="text-orange-400 hover:text-orange-300">
            View All Incidents →
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="text-left text-gray-400 py-3 px-4">ID</th>
                    <th class="text-left text-gray-400 py-3 px-4">Date</th>
                    <th class="text-left text-gray-400 py-3 px-4">Category</th>
                    <th class="text-left text-gray-400 py-3 px-4">Location</th>
                    <th class="text-left text-gray-400 py-3 px-4">Status</th>
                    <th class="text-left text-gray-400 py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-white/5 hover:bg-white/5">
                    <td class="py-3 px-4 text-white">INC-2025-001</td>
                    <td class="py-3 px-4 text-white">2025-11-28</td>
                    <td class="py-3 px-4 text-white">Noise Complaint</td>
                    <td class="py-3 px-4 text-white">Zone A</td>
                    <td class="py-3 px-4"><span class="px-3 py-1 bg-orange-500/20 text-orange-400 rounded-full text-sm">Pending</span></td>
                    <td class="py-3 px-4">
                        <div class="flex space-x-2">
                            <a href="<?php echo BASE_URL; ?>/incidents/view.php?id=1" class="text-orange-400 hover:text-orange-300 text-sm">View</a>
                            <button onclick="viewIncidentOnMap(13.9315, 121.4235)" class="text-blue-400 hover:text-blue-300 text-sm">Map</button>
                        </div>
                    </td>
                </tr>
                <tr class="border-b border-white/5 hover:bg-white/5">
                    <td class="py-3 px-4 text-white">INC-2025-002</td>
                    <td class="py-3 px-4 text-white">2025-11-27</td>
                    <td class="py-3 px-4 text-white">Theft</td>
                    <td class="py-3 px-4 text-white">Zone B</td>
                    <td class="py-3 px-4"><span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm">Resolved</span></td>
                    <td class="py-3 px-4">
                        <div class="flex space-x-2">
                            <a href="<?php echo BASE_URL; ?>/incidents/view.php?id=2" class="text-orange-400 hover:text-orange-300 text-sm">View</a>
                            <button onclick="viewIncidentOnMap(13.9345, 121.4255)" class="text-blue-400 hover:text-blue-300 text-sm">Map</button>
                        </div>
                    </td>
                </tr>
                <tr class="border-b border-white/5 hover:bg-white/5">
                    <td class="py-3 px-4 text-white">INC-2025-003</td>
                    <td class="py-3 px-4 text-white">2025-11-26</td>
                    <td class="py-3 px-4 text-white">Vandalism</td>
                    <td class="py-3 px-4 text-white">Zone C</td>
                    <td class="py-3 px-4"><span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-sm">In Progress</span></td>
                    <td class="py-3 px-4">
                        <div class="flex space-x-2">
                            <a href="<?php echo BASE_URL; ?>/incidents/view.php?id=3" class="text-orange-400 hover:text-orange-300 text-sm">View</a>
                            <button onclick="viewIncidentOnMap(13.9275, 121.4265)" class="text-blue-400 hover:text-blue-300 text-sm">Map</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Include Leaflet.js for dashboard map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let dashboardMap;

// Initialize dashboard map
function initDashboardMap() {
    // Center on Malabanban Sur, Candelaria, Quezon
    dashboardMap = L.map('dashboardMap', {
        zoomControl: false
    }).setView([13.9297, 121.4233], 14);
    
    // Dark theme tile layer
    L.tileLayer('/images/photo1764667248.jpg', {
        attribution: '© SmartTanod System',
        maxZoom: 20
    }).addTo(dashboardMap);
    
    // Add zone boundaries
    addDashboardZones();
    
    // Add active tanod markers
    addActiveTanods();
    
    // Add recent incidents
    addRecentIncidents();
}

// Add zone boundaries to dashboard map
function addDashboardZones() {
    const zones = [
        {
            name: 'Zone A',
            color: '#f59e0b',
            coordinates: [[13.9320, 121.4210], [13.9340, 121.4240], [13.9310, 121.4260], [13.9290, 121.4230]]
        },
        {
            name: 'Zone B',
            color: '#10b981',
            coordinates: [[13.9340, 121.4240], [13.9360, 121.4270], [13.9330, 121.4290], [13.9310, 121.4260]]
        },
        {
            name: 'Zone C',
            color: '#3b82f6',
            coordinates: [[13.9290, 121.4230], [13.9310, 121.4260], [13.9280, 121.4280], [13.9260, 121.4250]]
        },
        {
            name: 'Zone D',
            color: '#a855f7',
            coordinates: [[13.9310, 121.4260], [13.9330, 121.4290], [13.9300, 121.4310], [13.9280, 121.4280]]
        }
    ];
    
    zones.forEach(zone => {
        L.polygon(zone.coordinates, {
            color: zone.color,
            fillColor: zone.color,
            fillOpacity: 0.2,
            weight: 2
        }).addTo(dashboardMap);
        
        // Add zone label
        const center = L.polygon(zone.coordinates).getBounds().getCenter();
        L.marker(center, {
            icon: L.divIcon({
                className: 'zone-label',
                html: `<div style="background: rgba(0,0,0,0.7); color: white; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 10px;">${zone.name}</div>`,
                iconSize: [40, 16],
                iconAnchor: [20, 8]
            })
        }).addTo(dashboardMap);
    });
}

// Add active tanod markers
function addActiveTanods() {
    const activeTanods = [
        { name: 'Juan', lat: 13.9315, lng: 121.4235, zone: 'Zone A' },
        { name: 'Pedro', lat: 13.9345, lng: 121.4255, zone: 'Zone B' },
        { name: 'Carlos', lat: 13.9275, lng: 121.4265, zone: 'Zone C' }
    ];
    
    activeTanods.forEach(tanod => {
        L.marker([tanod.lat, tanod.lng], {
            icon: L.divIcon({
                className: 'tanod-marker',
                html: `
                    <div style="
                        background: #10b981; 
                        width: 16px; 
                        height: 16px; 
                        border-radius: 50%; 
                        border: 2px solid white;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
                    "></div>
                `,
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            })
        }).addTo(dashboardMap)
        .bindTooltip(`${tanod.name} - ${tanod.zone}`, {
            permanent: false,
            direction: 'top'
        });
    });
}

// Add recent incidents
function addRecentIncidents() {
    const recentIncidents = [
        { id: 'INC-2025-001', lat: 13.9315, lng: 121.4235, status: 'pending' },
        { id: 'INC-2025-002', lat: 13.9345, lng: 121.4255, status: 'resolved' },
        { id: 'INC-2025-003', lat: 13.9275, lng: 121.4265, status: 'in_progress' }
    ];
    
    const statusColors = {
        'pending': '#f59e0b',
        'resolved': '#10b981',
        'in_progress': '#3b82f6'
    };
    
    recentIncidents.forEach(incident => {
        L.marker([incident.lat, incident.lng], {
            icon: L.divIcon({
                className: 'incident-marker',
                html: `
                    <div style="
                        background: ${statusColors[incident.status]}; 
                        width: 12px; 
                        height: 12px; 
                        border-radius: 50%; 
                        border: 2px solid white;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
                    "></div>
                `,
                iconSize: [16, 16],
                iconAnchor: [8, 8]
            })
        }).addTo(dashboardMap)
        .bindTooltip(`${incident.id} - ${incident.status}`, {
            permanent: false,
            direction: 'top'
        });
    });
}

// View patrol on map
function viewPatrolOnMap(tanodName, lat, lng) {
    window.open(`<?php echo BASE_URL; ?>/map/index.php?lat=${lat}&lng=${lng}&tanod=${tanodName}`, '_blank');
}

// View incident on map
function viewIncidentOnMap(lat, lng) {
    window.open(`<?php echo BASE_URL; ?>/map/index.php?lat=${lat}&lng=${lng}&type=incident`, '_blank');
}

// Initialize dashboard map when page loads
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initDashboardMap, 100);
});
</script>

<style>
.zone-label, .tanod-marker, .incident-marker {
    background: none !important;
    border: none !important;
}

/* Custom scrollbar */
.overflow-x-auto {
    scrollbar-width: thin;
    scrollbar-color: rgba(249, 115, 22, 0.5) rgba(255, 255, 255, 0.1);
}

.overflow-x-auto::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: rgba(249, 115, 22, 0.5);
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: rgba(249, 115, 22, 0.7);
}
</style>

<?php require_once 'includes/footer.php'; ?>