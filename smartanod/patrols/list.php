<?php
// patrols/list.php
require_once '../config.php';
$pageTitle = 'Patrol List';
require_once '../includes/header.php';
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-white">Patrol Schedules</h1>
        <p class="text-gray-400">View all patrol schedules</p>
    </div>
    <div class="space-x-2">
        <a href="calendar.php" class="px-4 py-2 glass text-white rounded-lg hover:bg-white/10">Calendar View</a>
        <a href="create.php" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
            + Schedule Patrol
        </a>
    </div>
</div>

<!-- Filters -->
<div class="glass rounded-2xl p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <select class="px-4 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
            <option>All Zones</option>
            <option>Zone A</option>
            <option>Zone B</option>
            <option>Zone C</option>
        </select>
        
        <select class="px-4 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
            <option>All Status</option>
            <option>Scheduled</option>
            <option>Ongoing</option>
            <option>Completed</option>
            <option>Missed</option>
        </select>
        
        <input type="date" class="px-4 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        
        <button class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
            Apply Filters
        </button>
    </div>
</div>

<!-- Patrol Table -->
<div class="glass rounded-2xl p-6">
    <table class="w-full">
        <thead>
            <tr class="border-b border-white/10">
                <th class="text-left text-gray-400 py-3 px-4">Patrol ID</th>
                <th class="text-left text-gray-400 py-3 px-4">Tanod</th>
                <th class="text-left text-gray-400 py-3 px-4">Zone</th>
                <th class="text-left text-gray-400 py-3 px-4">Start Time</th>
                <th class="text-left text-gray-400 py-3 px-4">End Time</th>
                <th class="text-left text-gray-400 py-3 px-4">Status</th>
                <th class="text-left text-gray-400 py-3 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-b border-white/5 hover:bg-white/5">
                <td class="py-3 px-4 text-white font-mono">PTL-2025-001</td>
                <td class="py-3 px-4 text-white">Tanod Juan</td>
                <td class="py-3 px-4 text-white">Zone A</td>
                <td class="py-3 px-4 text-white">Nov 28, 08:00</td>
                <td class="py-3 px-4 text-white">Nov 28, 16:00</td>
                <td class="py-3 px-4"><span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-sm">Ongoing</span></td>
                <td class="py-3 px-4">
                    <button class="text-green-400 hover:text-green-300">Complete</button>
                </td>
            </tr>
            <tr class="border-b border-white/5 hover:bg-white/5">
                <td class="py-3 px-4 text-white font-mono">PTL-2025-002</td>
                <td class="py-3 px-4 text-white">Tanod Pedro</td>
                <td class="py-3 px-4 text-white">Zone B</td>
                <td class="py-3 px-4 text-white">Nov 28, 20:00</td>
                <td class="py-3 px-4 text-white">Nov 29, 04:00</td>
                <td class="py-3 px-4"><span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-sm">Scheduled</span></td>
                <td class="py-3 px-4">
                    <a href="edit.php?id=2" class="text-orange-400 hover:text-orange-300">Edit</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>