<?php
// kiosk/index.php - Simplified kiosk interface for citizen intake
require_once '../config.php';

// Check if clerk is logged in
if (!isLoggedIn() || getUserRole() !== 'clerk') {
    redirect(BASE_URL . '/auth/login.php');
}

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = 'INC-2025-003';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosk Mode - SmartTanod</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        body {
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 h-screen">
    
<?php if ($success): ?>
    <!-- Success Screen -->
    <div class="h-screen flex items-center justify-center p-8">
        <div class="glass rounded-2xl p-12 max-w-2xl text-center">
            <div class="text-8xl mb-6">✅</div>
            <h1 class="text-4xl font-bold text-white mb-4">Incident Reported Successfully!</h1>
            <div class="bg-orange-500/20 border-2 border-orange-500 rounded-xl p-8 mb-6">
                <div class="text-gray-400 text-xl mb-2">Your Incident Number:</div>
                <div class="text-6xl font-bold text-orange-400 font-mono"><?php echo $success; ?></div>
            </div>
            <p class="text-white text-xl mb-8">Please save this number for your reference.</p>
            <div class="space-y-4">
                <button onclick="window.print()" class="w-full px-8 py-6 bg-green-500 hover:bg-green-600 text-white text-2xl rounded-xl transition">
                    🖨️ Print Receipt
                </button>
                <a href="index.php" class="block w-full px-8 py-6 bg-orange-500 hover:bg-orange-600 text-white text-2xl rounded-xl transition">
                    📝 Report Another Incident
                </a>
                <a href="<?php echo BASE_URL; ?>/index.php" class="block text-gray-400 hover:text-white text-lg">
                    Exit Kiosk Mode
                </a>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- Report Form -->
    <div class="h-screen overflow-y-auto p-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-5xl font-bold text-white mb-2">🛡️ SmartTanod</h1>
                <p class="text-2xl text-gray-400">Incident Report Kiosk</p>
            </div>
            
            <form method="POST" enctype="multipart/form-data" class="glass rounded-2xl p-8">
                <div class="space-y-6">
                    <!-- Category (Large Touch-Friendly) -->
                    <div>
                        <label class="block text-white text-2xl font-bold mb-4">What type of incident? *</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="glass rounded-xl p-6 cursor-pointer hover:bg-white/10 transition">
                                <input type="radio" name="category" value="Noise Complaint" required class="hidden peer">
                                <div class="text-center peer-checked:text-orange-400">
                                    <div class="text-6xl mb-2">🔊</div>
                                    <div class="text-xl font-bold">Noise Complaint</div>
                                </div>
                            </label>
                            <label class="glass rounded-xl p-6 cursor-pointer hover:bg-white/10 transition">
                                <input type="radio" name="category" value="Theft" required class="hidden peer">
                                <div class="text-center peer-checked:text-orange-400">
                                    <div class="text-6xl mb-2">🏃</div>
                                    <div class="text-xl font-bold">Theft</div>
                                </div>
                            </label>
                            <label class="glass rounded-xl p-6 cursor-pointer hover:bg-white/10 transition">
                                <input type="radio" name="category" value="Dispute" required class="hidden peer">
                                <div class="text-center peer-checked:text-orange-400">
                                    <div class="text-6xl mb-2">⚖️</div>
                                    <div class="text-xl font-bold">Dispute</div>
                                </div>
                            </label>
                            <label class="glass rounded-xl p-6 cursor-pointer hover:bg-white/10 transition">
                                <input type="radio" name="category" value="Other" required class="hidden peer">
                                <div class="text-center peer-checked:text-orange-400">
                                    <div class="text-6xl mb-2">📋</div>
                                    <div class="text-xl font-bold">Other</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Location -->
                    <div>
                        <label class="block text-white text-2xl font-bold mb-4">Where did it happen? *</label>
                        <input type="text" name="location" required placeholder="Enter street address or landmark" 
                               class="w-full px-6 py-6 text-2xl glass rounded-xl text-white focus:outline-none focus:ring-4 focus:ring-orange-500">
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label class="block text-white text-2xl font-bold mb-4">What happened? *</label>
                        <textarea name="narrative" rows="6" required placeholder="Please describe the incident in detail..."
                                  class="w-full px-6 py-6 text-2xl glass rounded-xl text-white focus:outline-none focus:ring-4 focus:ring-orange-500"></textarea>
                    </div>
                    
                    <!-- Your Name (Optional) -->
                    <div>
                        <label class="block text-white text-2xl font-bold mb-4">Your Name (Optional)</label>
                        <input type="text" name="reporter_name" placeholder="You may report anonymously" 
                               class="w-full px-6 py-6 text-2xl glass rounded-xl text-white focus:outline-none focus:ring-4 focus:ring-orange-500">
                    </div>
                    
                    <!-- Your Contact (Optional) -->
                    <div>
                        <label class="block text-white text-2xl font-bold mb-4">Your Phone Number (Optional)</label>
                        <input type="tel" name="reporter_contact" placeholder="For follow-up only" 
                               class="w-full px-6 py-6 text-2xl glass rounded-xl text-white focus:outline-none focus:ring-4 focus:ring-orange-500">
                    </div>
                    
                    <!-- File Upload -->
                    <div>
                        <label class="block text-white text-2xl font-bold mb-4">Upload Photos/Videos (Optional)</label>
                        <input type="file" name="evidence[]" multiple accept="image/*,video/*"
                               class="w-full px-6 py-6 text-xl glass rounded-xl text-white focus:outline-none focus:ring-4 focus:ring-orange-500">
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="mt-10 space-y-4">
                    <button type="submit" class="w-full px-8 py-8 bg-orange-500 hover:bg-orange-600 text-white text-3xl font-bold rounded-xl transition shadow-lg">
                        📝 Submit Report
                    </button>
                    <a href="<?php echo BASE_URL; ?>/index.php" class="block text-center text-gray-400 hover:text-white text-xl">
                        Cancel & Exit
                    </a>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

</body>
</html>