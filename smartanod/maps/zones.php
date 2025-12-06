<?php
// map/zones.php - Zone Management CRUD
require_once '../config.php';
$pageTitle = 'Zone Management';
require_once '../includes/header.php';

$success = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $success = 'Zone created successfully!';
                break;
            case 'update':
                $success = 'Zone updated successfully!';
                break;
            case 'delete':
                $success = 'Zone deleted successfully!';
                break;
        }
    }
}

// Get zone statistics from database
$zone_stats = [];
$zones_query = "SELECT 
                    'Zone A' as name,
                    '#f59e0b' as color,
                    COUNT(DISTINCT u.id) as tanod_count,
                    COUNT(DISTINCT i.id) as incident_count,
                    '2.5 km²' as coverage_area,
                    'Northern residential area including Purok 1-3' as description
                FROM users u
                LEFT JOIN incidents i ON i.zone = 'Zone A'
                WHERE u.role = 'tanod' AND u.zone = 'Zone A' AND u.status = 'active'
                
                UNION ALL
                
                SELECT 
                    'Zone B' as name,
                    '#10b981' as color,
                    COUNT(DISTINCT u.id) as tanod_count,
                    COUNT(DISTINCT i.id) as incident_count,
                    '2.8 km²' as coverage_area,
                    'Eastern commercial district including market area' as description
                FROM users u
                LEFT JOIN incidents i ON i.zone = 'Zone B'
                WHERE u.role = 'tanod' AND u.zone = 'Zone B' AND u.status = 'active'
                
                UNION ALL
                
                SELECT 
                    'Zone C' as name,
                    '#3b82f6' as color,
                    COUNT(DISTINCT u.id) as tanod_count,
                    COUNT(DISTINCT i.id) as incident_count,
                    '2.2 km²' as coverage_area,
                    'Southern residential area including Purok 7-9' as description
                FROM users u
                LEFT JOIN incidents i ON i.zone = 'Zone C'
                WHERE u.role = 'tanod' AND u.zone = 'Zone C' AND u.status = 'active'
                
                UNION ALL
                
                SELECT 
                    'Zone D' as name,
                    '#a855f7' as color,
                    COUNT(DISTINCT u.id) as tanod_count,
                    COUNT(DISTINCT i.id) as incident_count,
                    '2.6 km²' as coverage_area,
                    'Western mixed-use area including school zone' as description
                FROM users u
                LEFT JOIN incidents i ON i.zone = 'Zone D'
                WHERE u.role = 'tanod' AND u.zone = 'Zone D' AND u.status = 'active'";

$zones_result = mysqli_query($conn, $zones_query);
?>

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Zone Management</h1>
            <p class="text-gray-400">Manage zone boundaries and assignments</p>
        </div>
        <div class="flex space-x-3">
            <a href="index.php" class="px-4 py-2 glass hover:bg-white/10 text-white rounded-lg transition">
                🗺️ View Map
            </a>
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

<!-- Zones List -->
<div class="glass rounded-2xl overflow-hidden">
    <div class="p-6 border-b border-white/10">
        <h2 class="text-xl font-bold text-white">All Zones</h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-white/5">
                <tr>
                    <th class="text-left text-gray-400 py-4 px-6">Zone</th>
                    <th class="text-left text-gray-400 py-4 px-6">Tanods</th>
                    <th class="text-left text-gray-400 py-4 px-6">Incidents</th>
                    <th class="text-left text-gray-400 py-4 px-6">Coverage</th>
                    <th class="text-left text-gray-400 py-4 px-6">Description</th>
                    <th class="text-left text-gray-400 py-4 px-6">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($zone = mysqli_fetch_assoc($zones_result)): ?>
                <tr class="border-b border-white/5 hover:bg-white/5">
                    <td class="py-4 px-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 rounded" style="background-color: <?php echo $zone['color']; ?>"></div>
                            <span class="text-white font-medium"><?php echo htmlspecialchars($zone['name']); ?></span>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-white"><?php echo $zone['tanod_count']; ?> assigned</td>
                    <td class="py-4 px-6 text-white"><?php echo $zone['incident_count']; ?> total</td>
                    <td class="py-4 px-6 text-white"><?php echo $zone['coverage_area']; ?></td>
                    <td class="py-4 px-6 text-gray-300 max-w-xs truncate"><?php echo htmlspecialchars($zone['description']); ?></td>
                    <td class="py-4 px-6">
                        <div class="flex space-x-2">
                            <a href="index.php?zone=<?php echo urlencode($zone['name']); ?>" 
                                    class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded transition">
                                🗺️ View Map
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Instructions Card -->
<div class="mt-6 glass rounded-2xl p-6">
    <h3 class="text-xl font-bold text-white mb-4">📍 How to Update Your Google Maps</h3>
    <div class="space-y-4 text-gray-300">
        <div class="flex items-start space-x-3">
            <span class="text-orange-500 font-bold">1.</span>
            <div>
                <p class="font-semibold text-white">Go to Google Maps</p>
                <p class="text-sm">Visit <a href="https://www.google.com/maps" target="_blank" class="text-orange-400 hover:underline">https://www.google.com/maps</a></p>
            </div>
        </div>
        
        <div class="flex items-start space-x-3">
            <span class="text-orange-500 font-bold">2.</span>
            <div>
                <p class="font-semibold text-white">Search for Your Location</p>
                <p class="text-sm">Search for "Malabanban Sur, Candelaria, Quezon" or your exact barangay location</p>
            </div>
        </div>
        
        <div class="flex items-start space-x-3">
            <span class="text-orange-500 font-bold">3.</span>
            <div>
                <p class="font-semibold text-white">Get Embed Code</p>
                <p class="text-sm">Click the "Share" button → Click "Embed a map" tab → Copy the entire <code class="bg-black/50 px-2 py-1 rounded">&lt;iframe&gt;</code> code</p>
            </div>
        </div>
        
        <div class="flex items-start space-x-3">
            <span class="text-orange-500 font-bold">4.</span>
            <div>
                <p class="font-semibold text-white">Update the Code</p>
                <p class="text-sm">Open <code class="bg-black/50 px-2 py-1 rounded">map/index.php</code> file → Find the section marked "GOOGLE MAPS IFRAME" → Replace the iframe code with your copied code</p>
            </div>
        </div>
        
        <div class="flex items-start space-x-3">
            <span class="text-orange-500 font-bold">5.</span>
            <div>
                <p class="font-semibold text-white">Save and Refresh</p>
                <p class="text-sm">Save the file and refresh your browser to see your actual location on the map</p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>