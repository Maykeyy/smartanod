<?php
// reports/index.php
require_once '../config.php';
$pageTitle = 'Reports';
require_once '../includes/header.php';
?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Reports</h1>
    <p class="text-gray-400">Generate and view system reports</p>
</div>

<!-- Report Filters -->
<div class="glass rounded-2xl p-6 mb-6">
    <h3 class="text-white font-bold mb-4">Generate Report</h3>
    
    <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-white mb-2">Report Type</label>
            <select class="w-full px-4 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option>Monthly Summary</option>
                <option>Tanod Performance</option>
                <option>Incident Trends</option>
                <option>Zone Analysis</option>
            </select>
        </div>
        
        <div>
            <label class="block text-white mb-2">Start Date</label>
            <input type="date" class="w-full px-4 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        
        <div>
            <label class="block text-white mb-2">End Date</label>
            <input type="date" class="w-full px-4 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="w-full px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                Generate Report
            </button>
        </div>
    </form>
</div>

<!-- Quick Report Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="glass rounded-2xl p-6 hover:bg-white/10 cursor-pointer">
        <div class="text-4xl mb-3">📊</div>
        <h3 class="text-xl font-bold text-white mb-2">Monthly Summary</h3>
        <p class="text-gray-400 text-sm mb-4">Comprehensive monthly incident report</p>
        <button class="text-orange-400 hover:text-orange-300">Generate →</button>
    </div>
    
    <div class="glass rounded-2xl p-6 hover:bg-white/10 cursor-pointer">
        <div class="text-4xl mb-3">👮</div>
        <h3 class="text-xl font-bold text-white mb-2">Tanod Performance</h3>
        <p class="text-gray-400 text-sm mb-4">Individual tanod activity and metrics</p>
        <button class="text-orange-400 hover:text-orange-300">Generate →</button>
    </div>
    
    <div class="glass rounded-2xl p-6 hover:bg-white/10 cursor-pointer">
        <div class="text-4xl mb-3">📈</div>
        <h3 class="text-xl font-bold text-white mb-2">Incident Trends</h3>
        <p class="text-gray-400 text-sm mb-4">Historical trends and patterns</p>
        <button class="text-orange-400 hover:text-orange-300">Generate →</button>
    </div>
</div>

<!-- Sample Report Display -->
<div class="glass rounded-2xl p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Monthly Summary - November 2025</h2>
        <div class="space-x-2">
            <button class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg">📄 Export PDF</button>
            <button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg">📊 Export CSV</button>
        </div>
    </div>
    
    <!-- Summary Stats -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="glass rounded-lg p-4">
            <div class="text-gray-400 text-sm">Total Incidents</div>
            <div class="text-3xl font-bold text-white">145</div>
        </div>
        <div class="glass rounded-lg p-4">
            <div class="text-gray-400 text-sm">Resolved</div>
            <div class="text-3xl font-bold text-green-400">126</div>
        </div>
        <div class="glass rounded-lg p-4">
            <div class="text-gray-400 text-sm">Pending</div>
            <div class="text-3xl font-bold text-orange-400">19</div>
        </div>
        <div class="glass rounded-lg p-4">
            <div class="text-gray-400 text-sm">Response Rate</div>
            <div class="text-3xl font-bold text-white">87%</div>
        </div>
    </div>
    
    <!-- Chart Placeholder -->
    <div class="bg-white/5 rounded-lg p-6 h-64 flex items-center justify-center">
        <div class="text-gray-400 text-center">
            <div class="text-4xl mb-2">📊</div>
            <div>Chart visualization would appear here</div>
            <div class="text-sm">(Chart.js integration)</div>
        </div>
    </div>
    
    <!-- Category Breakdown -->
    <div class="mt-6">
        <h3 class="text-xl font-bold text-white mb-4">Incident Categories</h3>
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="text-left text-gray-400 py-2">Category</th>
                    <th class="text-right text-gray-400 py-2">Count</th>
                    <th class="text-right text-gray-400 py-2">Percentage</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-white/5">
                    <td class="py-2 text-white">Noise Complaint</td>
                    <td class="text-right text-white">45</td>
                    <td class="text-right text-white">31%</td>
                </tr>
                <tr class="border-b border-white/5">
                    <td class="py-2 text-white">Theft</td>
                    <td class="text-right text-white">38</td>
                    <td class="text-right text-white">26%</td>
                </tr>
                <tr class="border-b border-white/5">
                    <td class="py-2 text-white">Dispute</td>
                    <td class="text-right text-white">32</td>
                    <td class="text-right text-white">22%</td>
                </tr>
                <tr class="border-b border-white/5">
                    <td class="py-2 text-white">Vandalism</td>
                    <td class="text-right text-white">20</td>
                    <td class="text-right text-white">14%</td>
                </tr>
                <tr class="border-b border-white/5">
                    <td class="py-2 text-white">Other</td>
                    <td class="text-right text-white">10</td>
                    <td class="text-right text-white">7%</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>