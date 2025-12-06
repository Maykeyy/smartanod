<!-- ============================================== -->
<!-- FILE 1: map/index.php - Interactive Zone Map -->
<!-- ============================================== -->

<?php
// map/index.php - Interactive Zone Map with Google Maps
require_once '../config.php';
$pageTitle = 'Interactive Zone Map';
require_once '../includes/header.php';

// Fetch tanods from database
$tanods_query = "SELECT u.*, 
                 (SELECT COUNT(*) FROM incidents WHERE assigned_to = u.id AND status IN ('Pending', 'In Progress')) as active_incidents
                 FROM users u 
                 WHERE u.role = 'tanod' AND u.status = 'active'
                 ORDER BY u.zone, u.full_name";
$tanods_result = mysqli_query($conn, $tanods_query);

// Count statistics
$total_tanods = mysqli_num_rows($tanods_result);
$on_patrol = 0;
$available = 0;

// Reset pointer to count
mysqli_data_seek($tanods_result, 0);
while($tanod = mysqli_fetch_assoc($tanods_result)) {
    if($tanod['active_incidents'] > 0) {
        $on_patrol++;
    } else {
        $available++;
    }
}

// Reset pointer for later use
mysqli_data_seek($tanods_result, 0);
?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Interactive Zone Map</h1>
    <p class="text-gray-400">Real-time view of Malabanban Sur zones and tanod locations</p>
</div>

<!-- Map Controls -->
<div class="glass rounded-2xl p-4 mb-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <button onclick="refreshMap()" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                🔄 Refresh Map
            </button>
            <button onclick="toggleFullscreen()" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
                📺 Fullscreen
            </button>
            <a href="zones.php" class="px-4 py-2 glass hover:bg-white/10 text-white rounded-lg transition">
                ⚙️ Manage Zones
            </a>
        </div>
        
        <!-- Zone Statistics -->
        <div class="flex items-center space-x-6 text-sm">
            <div class="text-center">
                <div class="text-orange-400 font-bold"><?php echo $total_tanods; ?></div>
                <div class="text-gray-400">Total Tanods</div>
            </div>
            <div class="text-center">
                <div class="text-green-400 font-bold"><?php echo $on_patrol; ?></div>
                <div class="text-gray-400">On Patrol</div>
            </div>
            <div class="text-center">
                <div class="text-blue-400 font-bold"><?php echo $available; ?></div>
                <div class="text-gray-400">Available</div>
            </div>
        </div>
    </div>
</div>

<!-- Map Container -->
<div class="glass rounded-2xl overflow-hidden" id="mapContainer">
    <div id="map" class="w-full h-[600px] relative">
        <!-- ============================================ -->
        <!-- GOOGLE MAPS IFRAME - REPLACE WITH YOUR URL -->
        <!-- ============================================ -->
        <!-- 
        INSTRUCTIONS:
        1. Go to https://www.google.com/maps
        2. Search for "Malabanban Sur, Candelaria, Quezon"
        3. Click "Share" button
        4. Click "Embed a map" tab
        5. Copy the entire <iframe> code
        6. Replace the iframe below with your copied code
        -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d87629.66580753597!2d121.
            39277446702783!3d13.91563658875148!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd47d0f53342fd
            %3A0xb71c784eb5464b05!2sMalabanban%20Sur%2C%20Candelaria%2C%20Quezon!5e0!3m2!1sen!2sph
            !4v1764670259911!5m2!1sen!2sph" 
            width="400" 
            height="300" 
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
    
    <!-- Map Legend -->
    <div class="p-4 border-t border-white/10">
        <h3 class="text-white font-semibold mb-3">Zone Legend</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-orange-500 rounded border-2 border-orange-500"></div>
                <span class="text-white text-sm">Zone A</span>
                <span class="text-gray-400 text-xs">(<?php 
                    $zone_count = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE role='tanod' AND zone='Zone A' AND status='active'");
                    echo mysqli_fetch_assoc($zone_count)['cnt'];
                ?> tanods)</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-green-500 rounded border-2 border-green-500"></div>
                <span class="text-white text-sm">Zone B</span>
                <span class="text-gray-400 text-xs">(<?php 
                    $zone_count = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE role='tanod' AND zone='Zone B' AND status='active'");
                    echo mysqli_fetch_assoc($zone_count)['cnt'];
                ?> tanods)</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-blue-500 rounded border-2 border-blue-500"></div>
                <span class="text-white text-sm">Zone C</span>
                <span class="text-gray-400 text-xs">(<?php 
                    $zone_count = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE role='tanod' AND zone='Zone C' AND status='active'");
                    echo mysqli_fetch_assoc($zone_count)['cnt'];
                ?> tanods)</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-purple-500 rounded border-2 border-purple-500"></div>
                <span class="text-white text-sm">Zone D</span>
                <span class="text-gray-400 text-xs">(<?php 
                    $zone_count = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE role='tanod' AND zone='Zone D' AND status='active'");
                    echo mysqli_fetch_assoc($zone_count)['cnt'];
                ?> tanods)</span>
            </div>
        </div>
    </div>
</div>

<!-- Tanod List Cards -->
<div class="mt-6">
    <h2 class="text-2xl font-bold text-white mb-4">Active Tanods by Zone</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php 
        mysqli_data_seek($tanods_result, 0);
        while($tanod = mysqli_fetch_assoc($tanods_result)): 
            $status = $tanod['active_incidents'] > 0 ? 'On Patrol' : 'Available';
            $statusColor = $status == 'On Patrol' ? 'green' : 'orange';
        ?>
        <div class="glass rounded-xl p-4 hover:bg-white/10 transition cursor-pointer" onclick='showTanodDetails(<?php echo json_encode($tanod); ?>)'>
            <div class="flex items-start space-x-3">
                <div class="w-12 h-12 bg-gray-600 rounded-full flex items-center justify-center text-xl flex-shrink-0">
                    👤
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-white font-semibold truncate"><?php echo htmlspecialchars($tanod['full_name']); ?></h3>
                    <p class="text-gray-400 text-sm"><?php echo htmlspecialchars($tanod['zone']); ?></p>
                    <p class="text-gray-400 text-xs"><?php echo htmlspecialchars($tanod['phone']); ?></p>
                    <div class="mt-2">
                        <span class="px-2 py-1 rounded-full text-xs bg-<?php echo $statusColor; ?>-500/20 text-<?php echo $statusColor; ?>-400">
                            <?php echo $status; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Tanod Details Modal -->
<div id="tanodModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="glass rounded-2xl p-6 max-w-md w-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-white">Tanod Details</h3>
                <button onclick="closeTanodModal()" class="text-gray-400 hover:text-white">✕</button>
            </div>
            
            <div id="tanodDetails" class="space-y-4">
                <!-- Details will be loaded here -->
            </div>
            
            <div class="mt-6 flex space-x-3">
                <button onclick="contactTanod()" class="flex-1 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
                    📞 Contact
                </button>
                <button onclick="assignPatrol()" class="flex-1 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                    🚶 Assign Patrol
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentTanodPhone = '';

// Show tanod details modal
function showTanodDetails(tanod) {
    const status = tanod.active_incidents > 0 ? 'On Patrol' : 'Available';
    const statusColor = status === 'On Patrol' ? '#10b981' : '#f59e0b';
    currentTanodPhone = tanod.phone;
    
    document.getElementById('tanodDetails').innerHTML = `
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-gray-600 rounded-full flex items-center justify-center text-2xl">
                👤
            </div>
            <div>
                <h4 class="text-lg font-bold text-white">${tanod.full_name}</h4>
                <p class="text-gray-400">${tanod.zone}</p>
            </div>
        </div>
        
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-400">Status:</span>
                <span class="px-3 py-1 rounded-full text-sm" style="background: ${statusColor}20; color: ${statusColor};">
                    ${status}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Contact:</span>
                <span class="text-white">${tanod.phone || 'N/A'}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Email:</span>
                <span class="text-white text-right">${tanod.email || 'N/A'}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Address:</span>
                <span class="text-white text-right text-sm">${tanod.address || 'N/A'}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Active Incidents:</span>
                <span class="text-white">${tanod.active_incidents || 0}</span>
            </div>
        </div>
    `;
    
    document.getElementById('tanodModal').classList.remove('hidden');
}

// Close tanod modal
function closeTanodModal() {
    document.getElementById('tanodModal').classList.add('hidden');
}

// Refresh map
function refreshMap() {
    const iframe = document.querySelector('#map iframe');
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '🔄 Refreshing...';
    button.disabled = true;
    
    // Reload the page to get fresh data
    setTimeout(() => {
        location.reload();
    }, 500);
}

// Toggle fullscreen
function toggleFullscreen() {
    const mapContainer = document.getElementById('mapContainer');
    if (!document.fullscreenElement) {
        mapContainer.requestFullscreen().then(() => {
            document.getElementById('map').style.height = '100vh';
        });
    } else {
        document.exitFullscreen().then(() => {
            document.getElementById('map').style.height = '600px';
        });
    }
}

// Contact tanod
function contactTanod() {
    if (currentTanodPhone) {
        window.location.href = `tel:${currentTanodPhone}`;
    } else {
        alert('No contact number available');
    }
}

// Assign patrol
function assignPatrol() {
    window.location.href = '../patrols/create.php';
}
</script>

<style>
/* Fullscreen map adjustments */
#mapContainer:fullscreen {
    background: #1a1a1a;
}

#mapContainer:fullscreen #map {
    height: 100vh !important;
}

/* Smooth transitions */
#map {
    transition: height 0.3s ease;
}
</style>

<?php require_once '../includes/footer.php'; ?>
