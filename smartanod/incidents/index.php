<?php
// incidents/index.php
require_once '../config.php';
$pageTitle = 'Incidents';
require_once '../includes/header.php';
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-white">Incidents</h1>
        <p class="text-gray-400">Manage and track all incidents</p>
    </div>
    <a href="create.php" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
        + New Incident
    </a>
</div>

<!-- Filters -->
<div class="glass rounded-2xl p-6 mb-6">
    <h3 class="text-white font-bold mb-4">Filters</h3>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <input type="text" placeholder="Search..." class="px-4 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        
        <select class="px-4 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
            <option>All Categories</option>
            <option>Noise Complaint</option>
            <option>Theft</option>
            <option>Dispute</option>
        </select>
        
        <select class="px-4 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
            <option>All Zones</option>
            <option>Zone A</option>
            <option>Zone B</option>
            <option>Zone C</option>
        </select>
        
        <select class="px-4 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
            <option>All Status</option>
            <option>Pending</option>
            <option>In Progress</option>
            <option>Resolved</option>
        </select>
        
        <button class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
            Apply Filters
        </button>
    </div>
</div>

<!-- Incident Table -->
<div class="glass rounded-2xl p-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="text-left text-gray-400 py-3 px-4">Incident ID</th>
                    <th class="text-left text-gray-400 py-3 px-4">Date & Time</th>
                    <th class="text-left text-gray-400 py-3 px-4">Category</th>
                    <th class="text-left text-gray-400 py-3 px-4">Location</th>
                    <th class="text-left text-gray-400 py-3 px-4">Priority</th>
                    <th class="text-left text-gray-400 py-3 px-4">Status</th>
                    <th class="text-left text-gray-400 py-3 px-4">Assigned To</th>
                    <th class="text-left text-gray-400 py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-white/5 hover:bg-white/5 border-l-4 border-l-orange-500">
                    <td class="py-3 px-4 text-white font-mono">INC-2025-001</td>
                    <td class="py-3 px-4 text-white">2025-11-28 14:30</td>
                    <td class="py-3 px-4 text-white">Noise Complaint</td>
                    <td class="py-3 px-4 text-white">Zone A, Street 5</td>
                    <td class="py-3 px-4"><span class="px-3 py-1 bg-orange-500/20 text-orange-400 rounded-full text-sm">High</span></td>
                    <td class="py-3 px-4"><span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-sm">Pending</span></td>
                    <td class="py-3 px-4 text-white">-</td>
                    <td class="py-3 px-4 space-x-2">
                        <a href="view.php?id=1" class="text-orange-400 hover:text-orange-300">View</a>
                        <a href="edit.php?id=1" class="text-green-400 hover:text-green-300">Edit</a>
                        <a href="assign.php?id=1" class="text-blue-400 hover:text-blue-300">Assign</a>
                    </td>
                </tr>
                <tr class="border-b border-white/5 hover:bg-white/5">
                    <td class="py-3 px-4 text-white font-mono">INC-2025-002</td>
                    <td class="py-3 px-4 text-white">2025-11-27 10:15</td>
                    <td class="py-3 px-4 text-white">Theft</td>
                    <td class="py-3 px-4 text-white">Zone B, Market</td>
                    <td class="py-3 px-4"><span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-sm">Urgent</span></td>
                    <td class="py-3 px-4"><span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm">Resolved</span></td>
                    <td class="py-3 px-4 text-white">Tanod Juan</td>
                    <td class="py-3 px-4 space-x-2">
                        <a href="view.php?id=2" class="text-orange-400 hover:text-orange-300">View</a>
                        <a href="edit.php?id=2" class="text-green-400 hover:text-green-300">Edit</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="mt-6 flex justify-between items-center">
        <div class="text-gray-400">Showing 1-20 of 145 incidents</div>
        <div class="flex space-x-2">
            <button class="px-4 py-2 glass rounded-lg text-white hover:bg-white/10">Previous</button>
            <button class="px-4 py-2 bg-orange-500 text-white rounded-lg">1</button>
            <button class="px-4 py-2 glass rounded-lg text-white hover:bg-white/10">2</button>
            <button class="px-4 py-2 glass rounded-lg text-white hover:bg-white/10">3</button>
            <button class="px-4 py-2 glass rounded-lg text-white hover:bg-white/10">Next</button>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>