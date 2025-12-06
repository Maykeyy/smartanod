<?php
// evidence/index.php
require_once '../config.php';
$pageTitle = 'Evidence Manager';
require_once '../includes/header.php';
?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Evidence Manager</h1>
    <p class="text-gray-400">View and manage all evidence files</p>
</div>

<!-- Filters -->
<div class="glass rounded-2xl p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <input type="text" placeholder="Incident ID..." class="px-4 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        
        <select class="px-4 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
            <option>All File Types</option>
            <option>Images</option>
            <option>Videos</option>
            <option>Documents</option>
        </select>
        
        <input type="date" class="px-4 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        
        <input type="text" placeholder="Uploaded by..." class="px-4 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        
        <button class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
            Apply Filters
        </button>
    </div>
</div>

<!-- Evidence Gallery -->
<div class="glass rounded-2xl p-6">
    <h2 class="text-2xl font-bold text-white mb-6">Evidence Files (24)</h2>
    
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <!-- Evidence Item 1 -->
        <div class="glass rounded-lg overflow-hidden hover:ring-2 hover:ring-orange-500 cursor-pointer">
            <div class="aspect-square bg-white/5">
                <img src="https://via.placeholder.com/200" alt="Evidence" class="w-full h-full object-cover">
            </div>
            <div class="p-2">
                <div class="text-white text-xs font-mono">INC-2025-001</div>
                <div class="text-gray-400 text-xs">Nov 28, 2025</div>
                <div class="flex gap-2 mt-2">
                    <button class="text-xs text-blue-400 hover:text-blue-300">View</button>
                    <button class="text-xs text-green-400 hover:text-green-300">Download</button>
                </div>
            </div>
        </div>
        
        <!-- Evidence Item 2 -->
        <div class="glass rounded-lg overflow-hidden hover:ring-2 hover:ring-orange-500 cursor-pointer">
            <div class="aspect-square bg-white/5">
                <img src="https://via.placeholder.com/200" alt="Evidence" class="w-full h-full object-cover">
            </div>
            <div class="p-2">
                <div class="text-white text-xs font-mono">INC-2025-001</div>
                <div class="text-gray-400 text-xs">Nov 28, 2025</div>
                <div class="flex gap-2 mt-2">
                    <button class="text-xs text-blue-400 hover:text-blue-300">View</button>
                    <button class="text-xs text-green-400 hover:text-green-300">Download</button>
                </div>
            </div>
        </div>
        
        <!-- Evidence Item 3 (Video) -->
        <div class="glass rounded-lg overflow-hidden hover:ring-2 hover:ring-orange-500 cursor-pointer">
            <div class="aspect-square bg-white/5 flex items-center justify-center">
                <div class="text-6xl">🎥</div>
            </div>
            <div class="p-2">
                <div class="text-white text-xs font-mono">INC-2025-002</div>
                <div class="text-gray-400 text-xs">Nov 27, 2025</div>
                <div class="flex gap-2 mt-2">
                    <button class="text-xs text-blue-400 hover:text-blue-300">View</button>
                    <button class="text-xs text-green-400 hover:text-green-300">Download</button>
                </div>
            </div>
        </div>
        
        <!-- Evidence Item 4 (PDF) -->
        <div class="glass rounded-lg overflow-hidden hover:ring-2 hover:ring-orange-500 cursor-pointer">
            <div class="aspect-square bg-white/5 flex items-center justify-center">
                <div class="text-6xl">📄</div>
            </div>
            <div class="p-2">
                <div class="text-white text-xs font-mono">INC-2025-002</div>
                <div class="text-gray-400 text-xs">Nov 27, 2025</div>
                <div class="flex gap-2 mt-2">
                    <button class="text-xs text-blue-400 hover:text-blue-300">View</button>
                    <button class="text-xs text-green-400 hover:text-green-300">Download</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
        <div class="flex space-x-2">
            <button class="px-4 py-2 glass rounded-lg text-white hover:bg-white/10">Previous</button>
            <button class="px-4 py-2 bg-orange-500 text-white rounded-lg">1</button>
            <button class="px-4 py-2 glass rounded-lg text-white hover:bg-white/10">2</button>
            <button class="px-4 py-2 glass rounded-lg text-white hover:bg-white/10">Next</button>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>