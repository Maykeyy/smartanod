<?php
// settings/index.php
require_once '../config.php';
$pageTitle = 'System Settings';
require_once '../includes/header.php';

if (getUserRole() !== 'admin') {
    redirect(BASE_URL . '/index.php');
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    
    if (isset($_POST['action'])) {
        try {
            switch ($_POST['action']) {
                case 'security':
                    // Delete existing security settings
                    $pdo->exec("DELETE FROM system_settings WHERE category = 'security'");
                    
                    // Insert new security settings
                    $securitySettings = [
                        'min_password_length' => intval($_POST['min_password_length']),
                        'require_uppercase' => isset($_POST['require_uppercase']) ? 1 : 0,
                        'require_numbers' => isset($_POST['require_numbers']) ? 1 : 0,
                        'require_special_chars' => isset($_POST['require_special_chars']) ? 1 : 0,
                        'max_login_attempts' => intval($_POST['max_login_attempts']),
                        'lockout_duration' => intval($_POST['lockout_duration']),
                        'enable_two_factor' => isset($_POST['enable_two_factor']) ? 1 : 0
                    ];
                    
                    $stmt = $pdo->prepare("INSERT INTO system_settings (category, setting_key, setting_value) VALUES (?, ?, ?)");
                    foreach ($securitySettings as $key => $value) {
                        $stmt->execute(['security', $key, $value]);
                    }
                    
                    $response = ['success' => true, 'message' => 'Security settings updated successfully'];
                    break;
                    
                case 'file_upload':
                    // Delete existing file upload settings
                    $pdo->exec("DELETE FROM system_settings WHERE category = 'file_upload'");
                    
                    // Insert new file upload settings
                    $fileSettings = [
                        'max_file_size' => intval($_POST['max_file_size']),
                        'allowed_image_types' => json_encode($_POST['allowed_image_types'] ?? []),
                        'allowed_doc_types' => json_encode($_POST['allowed_doc_types'] ?? []),
                        'enable_virus_scan' => isset($_POST['enable_virus_scan']) ? 1 : 0,
                        'auto_delete_after' => intval($_POST['auto_delete_after'])
                    ];
                    
                    $stmt = $pdo->prepare("INSERT INTO system_settings (category, setting_key, setting_value) VALUES (?, ?, ?)");
                    foreach ($fileSettings as $key => $value) {
                        $stmt->execute(['file_upload', $key, $value]);
                    }
                    
                    $response = ['success' => true, 'message' => 'File upload settings updated successfully'];
                    break;
                    
                case 'notification':
                    // Delete existing notification settings
                    $pdo->exec("DELETE FROM system_settings WHERE category = 'notification'");
                    
                    // Insert new notification settings
                    $notificationSettings = [
                        'email_notifications' => isset($_POST['email_notifications']) ? 1 : 0,
                        'sms_notifications' => isset($_POST['sms_notifications']) ? 1 : 0,
                        'notify_new_incident' => isset($_POST['notify_new_incident']) ? 1 : 0,
                        'notify_status_change' => isset($_POST['notify_status_change']) ? 1 : 0,
                        'notify_assignment' => isset($_POST['notify_assignment']) ? 1 : 0,
                        'daily_report_email' => $_POST['daily_report_email'] ?? '',
                        'notification_template' => $_POST['notification_template'] ?? ''
                    ];
                    
                    $stmt = $pdo->prepare("INSERT INTO system_settings (category, setting_key, setting_value) VALUES (?, ?, ?)");
                    foreach ($notificationSettings as $key => $value) {
                        $stmt->execute(['notification', $key, $value]);
                    }
                    
                    $response = ['success' => true, 'message' => 'Notification settings updated successfully'];
                    break;
            }
        } catch (PDOException $e) {
            $response = ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Function to get settings from database
function getSettings($pdo, $category) {
    $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM system_settings WHERE category = ?");
    $stmt->execute([$category]);
    $settings = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}

// Load existing settings
$securitySettings = getSettings($pdo, 'security');
$fileSettings = getSettings($pdo, 'file_upload');
$notificationSettings = getSettings($pdo, 'notification');
?>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">System Settings</h1>
    <p class="text-gray-400">Configure system preferences</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Settings Menu -->
    <div class="space-y-2">
        <button onclick="showTab('security')" id="tab-security" class="w-full text-left px-4 py-3 bg-orange-500/20 text-orange-400 rounded-lg font-bold">
            Security Settings
        </button>
        <button onclick="showTab('file-upload')" id="tab-file-upload" class="w-full text-left px-4 py-3 glass text-white rounded-lg hover:bg-white/10">
            File Upload Settings
        </button>
        <button onclick="showTab('notification')" id="tab-notification" class="w-full text-left px-4 py-3 glass text-white rounded-lg hover:bg-white/10">
            Notification Settings
        </button>
    </div>
    
    <!-- Settings Forms -->
    <div class="lg:col-span-3">
        <!-- Alert Message -->
        <div id="alert-message" class="hidden mb-4 p-4 rounded-lg">
            <p id="alert-text"></p>
        </div>
        
        <!-- Security Settings -->
        <div id="content-security" class="settings-content">
            <form id="form-security" class="glass rounded-2xl p-6">
                <h2 class="text-2xl font-bold text-white mb-6">Security Settings</h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-white mb-2">Minimum Password Length *</label>
                        <input type="number" name="min_password_length" 
                               value="<?php echo $securitySettings['min_password_length'] ?? 8; ?>" 
                               min="6" max="20" required 
                               class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <p class="text-gray-400 text-sm mt-1">Minimum number of characters required for passwords</p>
                    </div>
                    
                    <div class="space-y-3">
                        <label class="block text-white mb-2">Password Requirements</label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="require_uppercase" 
                                   <?php echo (($securitySettings['require_uppercase'] ?? 1) == 1) ? 'checked' : ''; ?> 
                                   class="mr-3 w-5 h-5">
                            <span class="text-white">Require uppercase letters</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="require_numbers" 
                                   <?php echo (($securitySettings['require_numbers'] ?? 1) == 1) ? 'checked' : ''; ?> 
                                   class="mr-3 w-5 h-5">
                            <span class="text-white">Require numbers</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="require_special_chars" 
                                   <?php echo (($securitySettings['require_special_chars'] ?? 0) == 1) ? 'checked' : ''; ?> 
                                   class="mr-3 w-5 h-5">
                            <span class="text-white">Require special characters (!@#$%^&*)</span>
                        </label>
                    </div>
                    
                    <div>
                        <label class="block text-white mb-2">Maximum Login Attempts *</label>
                        <input type="number" name="max_login_attempts" 
                               value="<?php echo $securitySettings['max_login_attempts'] ?? 5; ?>" 
                               min="3" max="10" required 
                               class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <p class="text-gray-400 text-sm mt-1">Number of failed login attempts before account lockout</p>
                    </div>
                    
                    <div>
                        <label class="block text-white mb-2">Account Lockout Duration (minutes) *</label>
                        <input type="number" name="lockout_duration" 
                               value="<?php echo $securitySettings['lockout_duration'] ?? 30; ?>" 
                               min="5" max="120" required 
                               class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <p class="text-gray-400 text-sm mt-1">How long accounts remain locked after max login attempts</p>
                    </div>
                    
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="enable_two_factor" 
                                   <?php echo (($securitySettings['enable_two_factor'] ?? 0) == 1) ? 'checked' : ''; ?> 
                                   class="mr-3 w-5 h-5">
                            <span class="text-white">Enable Two-Factor Authentication</span>
                        </label>
                        <p class="text-gray-400 text-sm mt-1 ml-8">Require additional verification code for login</p>
                    </div>
                </div>
                
                <input type="hidden" name="action" value="security">
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
        
        <!-- File Upload Settings -->
        <div id="content-file-upload" class="settings-content hidden">
            <form id="form-file-upload" class="glass rounded-2xl p-6">
                <h2 class="text-2xl font-bold text-white mb-6">File Upload Settings</h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-white mb-2">Maximum File Upload Size (MB) *</label>
                        <input type="number" name="max_file_size" 
                               value="<?php echo $fileSettings['max_file_size'] ?? 10; ?>" 
                               min="1" max="50" required 
                               class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <p class="text-gray-400 text-sm mt-1">Maximum size for uploaded files</p>
                    </div>
                    
                    <div>
                        <label class="block text-white mb-2">Allowed Image Types</label>
                        <?php 
                        $allowedImages = json_decode($fileSettings['allowed_image_types'] ?? '["jpg","png"]', true);
                        ?>
                        <div class="space-y-2">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="allowed_image_types[]" value="jpg" 
                                       <?php echo in_array('jpg', $allowedImages) ? 'checked' : ''; ?> 
                                       class="mr-3 w-5 h-5">
                                <span class="text-white">JPG/JPEG</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="allowed_image_types[]" value="png" 
                                       <?php echo in_array('png', $allowedImages) ? 'checked' : ''; ?> 
                                       class="mr-3 w-5 h-5">
                                <span class="text-white">PNG</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="allowed_image_types[]" value="gif" 
                                       <?php echo in_array('gif', $allowedImages) ? 'checked' : ''; ?> 
                                       class="mr-3 w-5 h-5">
                                <span class="text-white">GIF</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="allowed_image_types[]" value="webp" 
                                       <?php echo in_array('webp', $allowedImages) ? 'checked' : ''; ?> 
                                       class="mr-3 w-5 h-5">
                                <span class="text-white">WebP</span>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-white mb-2">Allowed Document Types</label>
                        <?php 
                        $allowedDocs = json_decode($fileSettings['allowed_doc_types'] ?? '["pdf","doc"]', true);
                        ?>
                        <div class="space-y-2">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="allowed_doc_types[]" value="pdf" 
                                       <?php echo in_array('pdf', $allowedDocs) ? 'checked' : ''; ?> 
                                       class="mr-3 w-5 h-5">
                                <span class="text-white">PDF</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="allowed_doc_types[]" value="doc" 
                                       <?php echo in_array('doc', $allowedDocs) ? 'checked' : ''; ?> 
                                       class="mr-3 w-5 h-5">
                                <span class="text-white">DOC/DOCX</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="allowed_doc_types[]" value="xls" 
                                       <?php echo in_array('xls', $allowedDocs) ? 'checked' : ''; ?> 
                                       class="mr-3 w-5 h-5">
                                <span class="text-white">XLS/XLSX</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="allowed_doc_types[]" value="txt" 
                                       <?php echo in_array('txt', $allowedDocs) ? 'checked' : ''; ?> 
                                       class="mr-3 w-5 h-5">
                                <span class="text-white">TXT</span>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="enable_virus_scan" 
                                   <?php echo (($fileSettings['enable_virus_scan'] ?? 0) == 1) ? 'checked' : ''; ?> 
                                   class="mr-3 w-5 h-5">
                            <span class="text-white">Enable Virus Scanning</span>
                        </label>
                        <p class="text-gray-400 text-sm mt-1 ml-8">Scan uploaded files for malware (requires additional setup)</p>
                    </div>
                    
                    <div>
                        <label class="block text-white mb-2">Auto-Delete Evidence After (years) *</label>
                        <input type="number" name="auto_delete_after" 
                               value="<?php echo $fileSettings['auto_delete_after'] ?? 5; ?>" 
                               min="1" max="20" required 
                               class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <p class="text-gray-400 text-sm mt-1">Automatically delete old evidence files after specified period</p>
                    </div>
                </div>
                
                <input type="hidden" name="action" value="file_upload">
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Notification Settings -->
        <div id="content-notification" class="settings-content hidden">
            <form id="form-notification" class="glass rounded-2xl p-6">
                <h2 class="text-2xl font-bold text-white mb-6">Notification Settings</h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-white mb-3">Notification Channels</label>
                        <div class="space-y-3">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="email_notifications" 
                                       <?php echo (($notificationSettings['email_notifications'] ?? 1) == 1) ? 'checked' : ''; ?> 
                                       class="mr-3 w-5 h-5">
                                <span class="text-white">Email Notifications</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="sms_notifications" 
                                       <?php echo (($notificationSettings['sms_notifications'] ?? 0) == 1) ? 'checked' : ''; ?> 
                                       class="mr-3 w-5 h-5">
                                <span class="text-white">SMS Notifications (requires SMS gateway)</span>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-white mb-3">Notify Users When:</label>
                        <div class="space-y-3">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="notify_new_incident" 
                                       <?php echo (($notificationSettings['notify_new_incident'] ?? 1) == 1) ? 'checked' : ''; ?> 
                                       class="mr-3 w-5 h-5">
                                <span class="text-white">New incident is reported</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="notify_status_change" 
                                       <?php echo (($notificationSettings['notify_status_change'] ?? 1) == 1) ? 'checked' : ''; ?> 
                                       class="mr-3 w-5 h-5">
                                <span class="text-white">Incident status changes</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="notify_assignment" 
                                       <?php echo (($notificationSettings['notify_assignment'] ?? 1) == 1) ? 'checked' : ''; ?> 
                                       class="mr-3 w-5 h-5">
                                <span class="text-white">Tanod is assigned to incident</span>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-white mb-2">Daily Report Email</label>
                        <input type="email" name="daily_report_email" 
                               value="<?php echo htmlspecialchars($notificationSettings['daily_report_email'] ?? ''); ?>" 
                               placeholder="admin@barangay.gov.ph" 
                               class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <p class="text-gray-400 text-sm mt-1">Send daily incident summary to this email address</p>
                    </div>
                    
                    <div>
                        <label class="block text-white mb-2">Notification Template</label>
                        <textarea name="notification_template" rows="4" 
                                  class="w-full px-4 py-3 glass rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500" 
                                  placeholder="New incident reported: {incident_type} at {location}. Status: {status}"><?php echo htmlspecialchars($notificationSettings['notification_template'] ?? 'New incident reported: {incident_type} at {location}. Status: {status}'); ?></textarea>
                        <p class="text-gray-400 text-sm mt-1">Use {incident_type}, {location}, {status} as placeholders</p>
                    </div>
                </div>
                
                <input type="hidden" name="action" value="notification">
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all content
    document.querySelectorAll('.settings-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('[id^="tab-"]').forEach(tab => {
        tab.classList.remove('bg-orange-500/20', 'text-orange-400', 'font-bold');
        tab.classList.add('glass', 'text-white');
    });
    
    // Show selected content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.remove('glass', 'text-white');
    activeTab.classList.add('bg-orange-500/20', 'text-orange-400', 'font-bold');
}

function showAlert(message, isSuccess) {
    const alertDiv = document.getElementById('alert-message');
    const alertText = document.getElementById('alert-text');
    
    alertDiv.classList.remove('hidden', 'bg-green-500/20', 'text-green-400', 'bg-red-500/20', 'text-red-400');
    
    if (isSuccess) {
        alertDiv.classList.add('bg-green-500/20', 'text-green-400');
    } else {
        alertDiv.classList.add('bg-red-500/20', 'text-red-400');
    }
    
    alertText.textContent = message;
    
    setTimeout(() => {
        alertDiv.classList.add('hidden');
    }, 5000);
}

// Handle form submissions
document.querySelectorAll('form[id^="form-"]').forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        try {
            const response = await fetch('', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            showAlert(result.message, result.success);
            
            if (result.success) {
                // Settings saved successfully - they will take effect immediately
            }
        } catch (error) {
            showAlert('An error occurred while saving settings', false);
            console.error('Error:', error);
        }
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>