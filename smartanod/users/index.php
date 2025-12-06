<?php
// users/index.php
require_once '../config.php';
$pageTitle = 'User Management';
require_once '../includes/header.php';

// Restrict to admin only
if (getUserRole() !== 'admin') {
    redirect(BASE_URL . '/index.php');
}
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-white">User Management</h1>
        <p class="text-gray-400">Manage system users and roles</p>
    </div>
    <a href="create.php" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
        + Add User
    </a>
</div>

<!-- Filters -->
<div class="glass rounded-2xl p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" placeholder="Search users..." class="px-4 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        
        <select class="px-4 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
            <option>All Roles</option>
            <option>Admin</option>
            <option>Captain</option>
            <option>Clerk</option>
            <option>Tanod</option>
            <option>Viewer</option>
        </select>
        
        <select class="px-4 py-2 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
            <option>All Status</option>
            <option>Active</option>
            <option>Inactive</option>
        </select>
        
        <button class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
            Apply Filters
        </button>
    </div>
</div>

<!-- Users Table -->
<div class="glass rounded-2xl p-6">
    <table class="w-full">
        <thead>
            <tr class="border-b border-white/10">
                <th class="text-left text-gray-400 py-3 px-4">User ID</th>
                <th class="text-left text-gray-400 py-3 px-4">Full Name</th>
                <th class="text-left text-gray-400 py-3 px-4">Username</th>
                <th class="text-left text-gray-400 py-3 px-4">Email</th>
                <th class="text-left text-gray-400 py-3 px-4">Role</th>
                <th class="text-left text-gray-400 py-3 px-4">Zone</th>
                <th class="text-left text-gray-400 py-3 px-4">Status</th>
                <th class="text-left text-gray-400 py-3 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-b border-white/5 hover:bg-white/5">
                <td class="py-3 px-4 text-white">1</td>
                <td class="py-3 px-4 text-white">Admin User</td>
                <td class="py-3 px-4 text-white">admin</td>
                <td class="py-3 px-4 text-white">admin@example.com</td>
                <td class="py-3 px-4"><span class="px-3 py-1 bg-purple-500/20 text-purple-400 rounded-full text-sm">Admin</span></td>
                <td class="py-3 px-4 text-white">-</td>
                <td class="py-3 px-4"><span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm">Active</span></td>
                <td class="py-3 px-4 space-x-2">
                    <a href="edit.php?id=1" class="text-orange-400 hover:text-orange-300">Edit</a>
                </td>
            </tr>
            <tr class="border-b border-white/5 hover:bg-white/5">
                <td class="py-3 px-4 text-white">2</td>
                <td class="py-3 px-4 text-white">Clerk User</td>
                <td class="py-3 px-4 text-white">clerk</td>
                <td class="py-3 px-4 text-white">clerk@example.com</td>
                <td class="py-3 px-4"><span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-sm">Clerk</span></td>
                <td class="py-3 px-4 text-white">-</td>
                <td class="py-3 px-4"><span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm">Active</span></td>
                <td class="py-3 px-4 space-x-2">
                    <a href="edit.php?id=2" class="text-orange-400 hover:text-orange-300">Edit</a>
                    <button class="text-red-400 hover:text-red-300">Deactivate</button>
                </td>
            </tr>
            <tr class="border-b border-white/5 hover:bg-white/5">
                <td class="py-3 px-4 text-white">3</td>
                <td class="py-3 px-4 text-white">Juan Dela Cruz</td>
                <td class="py-3 px-4 text-white">tanod_juan</td>
                <td class="py-3 px-4 text-white">juan@example.com</td>
                <td class="py-3 px-4"><span class="px-3 py-1 bg-orange-500/20 text-orange-400 rounded-full text-sm">Tanod</span></td>
                <td class="py-3 px-4 text-white">Zone A</td>
                <td class="py-3 px-4"><span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm">Active</span></td>
                <td class="py-3 px-4 space-x-2">
                    <a href="edit.php?id=3" class="text-orange-400 hover:text-orange-300">Edit</a>
                    <button class="text-red-400 hover:text-red-300">Deactivate</button>
                </td>
            </tr>
        </tbody>
    </table>
    
    <!-- Pagination -->
    <div class="mt-6 flex justify-between items-center">
        <div class="text-gray-400">Showing 1-10 of 25 users</div>
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