<?php
// incidents/create.php - Enhanced with Map Location Picker
require_once '../config.php';
$pageTitle = 'Create Incident';
require_once '../includes/header.php';

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = 'Incident created successfully! ID: INC-2025-003';
}
?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Create New Incident</h1>
    <p class="text-gray-400">Record a new incident report with location mapping</p>
</div>

<?php if ($success): ?>
<div class="bg-green-500/20 border border-green-500 text-green-200 px-4 py-3 rounded-lg mb-6">
    <?php echo htmlspecialchars($success); ?>
    <a href="view.php?id=3" class="ml-4 underline">View Incident</a>
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="glass rounded-2xl p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Date & Time -->
        <div>
            <label class="block text-white mb-2">Date & Time *</label>
            <input type="datetime-local" name="date_time" required 
                   class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        
        <!-- Category -->
        <div>
            <label class="block text-white mb-2">Category *</label>
            <select name="category" required 
                    class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="">Select Category</option>
                <option>Noise Complaint</option>
                <option>Theft</option>
                <option>Vandalism</option>
                <option>Dispute</option>
                <option>Assault</option>
                <option>Other</option>
            </select>
        </div>
        
        <!-- Priority -->
        <div>
            <label class="block text-white mb-2">Priority *</label>
            <select name="priority" required 
                    class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="">Select Priority</option>
                <option>Low</option>
                <option>Medium</option>
                <option>High</option>
                <option>Urgent</option>
            </select>
        </div>
        
        <!-- Zone (Auto-detected) -->
        <div>
            <label class="block text-white mb-2">Zone *</label>
            <div class="flex space-x-2">
                <select name="zone" id="zoneSelect" required 
                        class="flex-1 px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Auto-detect from map</option>
                    <option>Zone A</option>
                    <option>Zone B</option>
                    <option>Zone C</option>
                    <option>Zone D</option>
                </select>
                <button type="button" onclick="openLocationPicker()" 
                        class="px-4 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition whitespace-nowrap">
                    📍 Pick on Map
                </button>
            </div>
        </div>
        
        <!-- Location Details -->
        <div class="md:col-span-2">
            <label class="block text-white mb-2">Location Details *</label>
            <div class="flex space-x-2">
                <input type="text" name="location" id="locationInput" required placeholder="Street address or landmark"
                       class="flex-1 px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                <div id="locationStatus" class="flex items-center px-4 py-3 glass rounded-lg">
                    <span class="text-gray-400 text-sm">📍 Click "Pick on Map" to set location</span>
                </div>
            </div>
            <input type="hidden" name="latitude" id="latitudeInput">
            <input type="hidden" name="longitude" id="longitudeInput">
        </div>
        
        <!-- Reporter Type -->
        <div>
            <label class="block text-white mb-2">Reporter Type *</label>
            <select name="reporter_type" required 
                    class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="">Select Type</option>
                <option>Walk-in</option>
                <option>Phone Call</option>
                <option>Online</option>
                <option>Patrol</option>
            </select>
        </div>
        
        <!-- Reporter Name -->
        <div>
            <label class="block text-white mb-2">Reporter Name</label>
            <input type="text" name="reporter_name" 
                   class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        
        <!-- Reporter Contact -->
        <div class="md:col-span-2">
            <label class="block text-white mb-2">Reporter Contact</label>
            <input type="text" name="reporter_contact" placeholder="Phone or email"
                   class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        
        <!-- Involved Parties -->
        <div class="md:col-span-2">
            <label class="block text-white mb-2">Involved Parties</label>
            <textarea name="involved_parties" rows="3" placeholder="Names and descriptions of involved individuals"
                      class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
        </div>
        
        <!-- Narrative -->
        <div class="md:col-span-2">
            <label class="block text-white mb-2">Incident Narrative *</label>
            <textarea name="narrative" rows="6" required placeholder="Detailed description of the incident"
                      class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
        </div>
        
        <!-- Evidence Upload -->
        <div class="md:col-span-2">
            <label class="block text-white mb-2">Upload Evidence (Photos, Videos, Documents)</label>
            <input type="file" name="evidence[]" multiple accept="image/*,video/*,.pdf"
                   class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
            <p class="text-gray-400 text-sm mt-2">Max 5MB per file. Accepts: JPG, PNG, MP4, PDF</p>
        </div>
    </div>
    
    <div class="mt-8 flex justify-end space-x-4">
        <a href="index.php" class="px-6 py-3 glass rounded-lg text-white hover:bg-white/10 transition">
            Cancel
        </a>
        <button type="submit" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
            Create Incident
        </button>
    </div>
</form>

<!-- Location Picker Modal -->
<div id="locationPickerModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="glass rounded-2xl p-6 max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white">📍 Select Incident Location</h3>
                <button onclick="closeLocationPicker()" class="text-gray-400 hover:text-white">✕</button>
            </div>
            
            <!-- Instructions -->
            <div class="bg-blue-500/20 border border-blue-500 text-blue-200 px-4 py-3 rounded-lg mb-4">
                <p class="text-sm">
                    <strong>Instructions:</strong> Click on the map to set the exact location of the incident. 
                    The zone will be automatically detected based on your selection.
                </p>
            </div>
            
            <!-- Map Container -->
            <div id="locationMap" class="w-full h-96 rounded-lg overflow-hidden border border-white/20 mb-4">
                <!-- Map will be loaded here -->
            </div>
            
            <!-- Selected Location Info -->
            <div id="selectedLocationInfo" class="glass rounded-lg p-4 mb-6 hidden">
                <h4 class="text-white font-bold mb-3">Selected Location</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-400">Address:</span>
                        <div id="selectedAddress" class="text-white font-medium">-</div>
                    </div>
                    <div>
                        <span class="text-gray-400">Detected Zone:</span>
                        <div id="detectedZone" class="text-white font-medium">-</div>
                    </div>
                    <div>
                        <span class="text-gray-400">Coordinates:</span>
                        <div id="selectedCoordinates" class="text-white font-medium">-</div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4">
                <button onclick="closeLocationPicker()" 
                        class="px-6 py-3 glass rounded-lg text-white hover:bg-white/10 transition">
                    Cancel
                </button>
                <button onclick="confirmLocation()" id="confirmLocationBtn" disabled
                        class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Confirm Location
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Include Leaflet.js for map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let locationMap;
let selectedMarker;
let selectedLocation = null;

// Zone boundaries for auto-detection
const zoneBoundaries = {
    'Zone A': [[13.9320, 121.4210], [13.9340, 121.4240], [13.9310, 121.4260], [13.9290, 121.4230]],
    'Zone B': [[13.9340, 121.4240], [13.9360, 121.4270], [13.9330, 121.4290], [13.9310, 121.4260]],
    'Zone C': [[13.9290, 121.4230], [13.9310, 121.4260], [13.9280, 121.4280], [13.9260, 121.4250]],
    'Zone D': [[13.9310, 121.4260], [13.9330, 121.4290], [13.9300, 121.4310], [13.9280, 121.4280]]
};

// Open location picker modal
function openLocationPicker() {
    document.getElementById('locationPickerModal').classList.remove('hidden');
    
    // Initialize map after modal is shown
    setTimeout(initLocationMap, 100);
}

// Close location picker modal
function closeLocationPicker() {
    document.getElementById('locationPickerModal').classList.add('hidden');
    if (locationMap) {
        locationMap.remove();
        locationMap = null;
    }
    selectedLocation = null;
}

// Initialize location picker map
function initLocationMap() {
    if (locationMap) {
        locationMap.remove();
    }
    
    // Center on Malabanban Sur, Candelaria, Quezon
    locationMap = L.map('locationMap').setView([13.9297, 121.4233], 15);
    
    // Dark theme tile layer
    L.tileLayer('/images/photo1764667149.jpg', {
        attribution: '© SmartTanod System',
        maxZoom: 20
    }).addTo(locationMap);
    
    // Add zone boundaries
    addZoneBoundariesToMap();
    
    // Add click handler
    locationMap.on('click', function(e) {
        selectLocation(e.latlng);
    });
}

// Add zone boundaries to map
function addZoneBoundariesToMap() {
    const zoneColors = {
        'Zone A': '#f59e0b',
        'Zone B': '#10b981',
        'Zone C': '#3b82f6',
        'Zone D': '#a855f7'
    };
    
    Object.keys(zoneBoundaries).forEach(zoneName => {
        const polygon = L.polygon(zoneBoundaries[zoneName], {
            color: zoneColors[zoneName],
            fillColor: zoneColors[zoneName],
            fillOpacity: 0.3,
            weight: 2
        }).addTo(locationMap);
        
        // Add zone label
        const center = polygon.getBounds().getCenter();
        L.marker(center, {
            icon: L.divIcon({
                className: 'zone-label',
                html: `<div style="background: rgba(0,0,0,0.7); color: white; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 11px;">${zoneName}</div>`,
                iconSize: [50, 16],
                iconAnchor: [25, 8]
            })
        }).addTo(locationMap);
    });
}

// Select location on map
function selectLocation(latlng) {
    // Remove previous marker
    if (selectedMarker) {
        locationMap.removeLayer(selectedMarker);
    }
    
    // Add new marker
    selectedMarker = L.marker([latlng.lat, latlng.lng], {
        icon: L.divIcon({
            className: 'incident-marker',
            html: `
                <div style="
                    background: #ef4444; 
                    width: 24px; 
                    height: 24px; 
                    border-radius: 50%; 
                    border: 3px solid white;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                    position: relative;
                ">
                    <div style="
                        position: absolute;
                        top: -8px;
                        left: 50%;
                        transform: translateX(-50%);
                        width: 0;
                        height: 0;
                        border-left: 6px solid transparent;
                        border-right: 6px solid transparent;
                        border-bottom: 8px solid #ef4444;
                    "></div>
                </div>
            `,
            iconSize: [30, 30],
            iconAnchor: [15, 24]
        })
    }).addTo(locationMap);
    
    // Store selected location
    selectedLocation = {
        lat: latlng.lat,
        lng: latlng.lng
    };
    
    // Detect zone
    const detectedZone = detectZone(latlng.lat, latlng.lng);
    
    // Reverse geocode to get address (mock implementation)
    const mockAddress = `Street near ${detectedZone}, Malabanban Sur, Candelaria, Quezon`;
    
    // Update UI
    document.getElementById('selectedAddress').textContent = mockAddress;
    document.getElementById('detectedZone').textContent = detectedZone;
    document.getElementById('selectedCoordinates').textContent = `${latlng.lat.toFixed(6)}, ${latlng.lng.toFixed(6)}`;
    document.getElementById('selectedLocationInfo').classList.remove('hidden');
    document.getElementById('confirmLocationBtn').disabled = false;
}

// Detect which zone contains the point
function detectZone(lat, lng) {
    for (const [zoneName, coordinates] of Object.entries(zoneBoundaries)) {
        if (isPointInPolygon([lat, lng], coordinates)) {
            return zoneName;
        }
    }
    return 'Unknown Zone';
}

// Point in polygon algorithm
function isPointInPolygon(point, polygon) {
    const [x, y] = point;
    let inside = false;
    
    for (let i = 0, j = polygon.length - 1; i < polygon.length; j = i++) {
        const [xi, yi] = polygon[i];
        const [xj, yj] = polygon[j];
        
        if (((yi > y) !== (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi)) {
            inside = !inside;
        }
    }
    
    return inside;
}

// Confirm selected location
function confirmLocation() {
    if (!selectedLocation) return;
    
    const address = document.getElementById('selectedAddress').textContent;
    const zone = document.getElementById('detectedZone').textContent;
    
    // Update form fields
    document.getElementById('locationInput').value = address;
    document.getElementById('latitudeInput').value = selectedLocation.lat;
    document.getElementById('longitudeInput').value = selectedLocation.lng;
    document.getElementById('zoneSelect').value = zone;
    
    // Update status
    document.getElementById('locationStatus').innerHTML = `
        <span class="text-green-400 text-sm">✓ Location set: ${zone}</span>
    `;
    
    // Close modal
    closeLocationPicker();
}
</script>

<style>
.zone-label {
    background: none !important;
    border: none !important;
}

.incident-marker {
    background: none !important;
    border: none !important;
}

/* Custom scrollbar */
#locationPickerModal .overflow-hidden {
    scrollbar-width: thin;
    scrollbar-color: rgba(249, 115, 22, 0.5) rgba(255, 255, 255, 0.1);
}

#locationPickerModal .overflow-hidden::-webkit-scrollbar {
    width: 8px;
}

#locationPickerModal .overflow-hidden::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}

#locationPickerModal .overflow-hidden::-webkit-scrollbar-thumb {
    background: rgba(249, 115, 22, 0.5);
    border-radius: 4px;
}

#locationPickerModal .overflow-hidden::-webkit-scrollbar-thumb:hover {
    background: rgba(249, 115, 22, 0.7);
}
</style>

<?php require_once '../includes/footer.php'; ?>