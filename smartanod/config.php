<?php
// config.php - Database configuration and common functions

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'smartanod_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection using PDO
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Helper function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Helper function to get current user role
function getUserRole() {
    return $_SESSION['role'] ?? null;
}

// Helper function to redirect
function redirect($url) {
    header("Location: $url");
    exit;
}

// Base URL
define('BASE_URL', '/smartanod');

// ============================================
// SYSTEM SETTINGS HELPER FUNCTIONS
// ============================================

/**
 * Get a specific setting value from the database
 * @param PDO $pdo Database connection
 * @param string $category Setting category (e.g., 'security', 'file_upload', 'notification')
 * @param string $key Setting key
 * @param mixed $default Default value if setting not found
 * @return mixed Setting value
 */
function getSetting($pdo, $category, $key, $default = null) {
    static $cache = [];
    
    $cacheKey = $category . '.' . $key;
    
    // Check if already cached
    if (isset($cache[$cacheKey])) {
        return $cache[$cacheKey];
    }
    
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM system_settings WHERE category = ? AND setting_key = ?");
        $stmt->execute([$category, $key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $value = $result ? $result['setting_value'] : $default;
        
        // Cache the value
        $cache[$cacheKey] = $value;
        
        return $value;
    } catch (PDOException $e) {
        return $default;
    }
}

/**
 * Get all settings for a specific category
 * @param PDO $pdo Database connection
 * @param string $category Setting category
 * @return array Associative array of settings
 */
function getSettingsByCategory($pdo, $category) {
    static $cache = [];
    
    // Check if already cached
    if (isset($cache[$category])) {
        return $cache[$category];
    }
    
    try {
        $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM system_settings WHERE category = ?");
        $stmt->execute([$category]);
        $settings = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        
        // Cache the settings
        $cache[$category] = $settings;
        
        return $settings;
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Validate password based on system settings
 * @param string $password Password to validate
 * @param PDO $pdo Database connection
 * @return array ['valid' => bool, 'message' => string]
 */
function validatePassword($password, $pdo) {
    $minLength = (int)getSetting($pdo, 'security', 'min_password_length', 8);
    $requireUppercase = (int)getSetting($pdo, 'security', 'require_uppercase', 1);
    $requireNumbers = (int)getSetting($pdo, 'security', 'require_numbers', 1);
    $requireSpecialChars = (int)getSetting($pdo, 'security', 'require_special_chars', 0);
    
    // Check length
    if (strlen($password) < $minLength) {
        return [
            'valid' => false,
            'message' => "Password must be at least {$minLength} characters long"
        ];
    }
    
    // Check uppercase
    if ($requireUppercase && !preg_match('/[A-Z]/', $password)) {
        return [
            'valid' => false,
            'message' => 'Password must contain at least one uppercase letter'
        ];
    }
    
    // Check numbers
    if ($requireNumbers && !preg_match('/[0-9]/', $password)) {
        return [
            'valid' => false,
            'message' => 'Password must contain at least one number'
        ];
    }
    
    // Check special characters
    if ($requireSpecialChars && !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        return [
            'valid' => false,
            'message' => 'Password must contain at least one special character'
        ];
    }
    
    return ['valid' => true, 'message' => ''];
}

/**
 * Check if a file upload is allowed based on system settings
 * @param string $filename File name
 * @param int $filesize File size in bytes
 * @param PDO $pdo Database connection
 * @return array ['allowed' => bool, 'message' => string]
 */
function validateFileUpload($filename, $filesize, $pdo) {
    // Get max file size in MB
    $maxFileSizeMB = (int)getSetting($pdo, 'file_upload', 'max_file_size', 10);
    $maxFileSizeBytes = $maxFileSizeMB * 1024 * 1024;
    
    // Check file size
    if ($filesize > $maxFileSizeBytes) {
        return [
            'allowed' => false,
            'message' => "File size exceeds maximum allowed size of {$maxFileSizeMB}MB"
        ];
    }
    
    // Get file extension
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    // Get allowed types
    $allowedImages = json_decode(getSetting($pdo, 'file_upload', 'allowed_image_types', '["jpg","png"]'), true);
    $allowedDocs = json_decode(getSetting($pdo, 'file_upload', 'allowed_doc_types', '["pdf","doc"]'), true);
    
    // Merge allowed types
    $allowedTypes = array_merge($allowedImages, $allowedDocs);
    
    // Check if extension is allowed
    if (!in_array($extension, $allowedTypes)) {
        return [
            'allowed' => false,
            'message' => 'File type not allowed. Allowed types: ' . implode(', ', $allowedTypes)
        ];
    }
    
    return ['allowed' => true, 'message' => ''];
}

/**
 * Check if email notifications are enabled
 * @param PDO $pdo Database connection
 * @return bool
 */
function isEmailNotificationEnabled($pdo) {
    return (int)getSetting($pdo, 'notification', 'email_notifications', 1) === 1;
}

/**
 * Check if SMS notifications are enabled
 * @param PDO $pdo Database connection
 * @return bool
 */
function isSmsNotificationEnabled($pdo) {
    return (int)getSetting($pdo, 'notification', 'sms_notifications', 0) === 1;
}

/**
 * Get notification template with replaced placeholders
 * @param PDO $pdo Database connection
 * @param array $data Data to replace placeholders
 * @return string Formatted notification message
 */
function getNotificationMessage($pdo, $data = []) {
    $template = getSetting($pdo, 'notification', 'notification_template', 
        'New incident reported: {incident_type} at {location}. Status: {status}');
    
    // Replace placeholders
    foreach ($data as $key => $value) {
        $template = str_replace('{' . $key . '}', $value, $template);
    }
    
    return $template;
}

/**
 * Record login attempt for rate limiting
 * @param string $username Username attempting to login
 * @param bool $success Whether login was successful
 * @param PDO $pdo Database connection
 */
function recordLoginAttempt($username, $success, $pdo) {
    try {
        // Get max attempts and lockout duration from settings
        $maxAttempts = (int)getSetting($pdo, 'security', 'max_login_attempts', 5);
        $lockoutMinutes = (int)getSetting($pdo, 'security', 'lockout_duration', 30);
        
        // Get current failed attempts count
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as attempts 
            FROM login_attempts 
            WHERE username = ? 
            AND success = 0 
            AND attempted_at > DATE_SUB(NOW(), INTERVAL ? MINUTE)
        ");
        $stmt->execute([$username, $lockoutMinutes]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $attempts = $result['attempts'];
        
        // Record this attempt
        $stmt = $pdo->prepare("
            INSERT INTO login_attempts (username, success, attempted_at, ip_address) 
            VALUES (?, ?, NOW(), ?)
        ");
        $stmt->execute([$username, $success ? 1 : 0, $_SERVER['REMOTE_ADDR'] ?? '']);
        
        // If this failed attempt exceeds max, lock the account
        if (!$success && $attempts >= $maxAttempts - 1) {
            // Lock account
            $stmt = $pdo->prepare("
                UPDATE users 
                SET locked_until = DATE_ADD(NOW(), INTERVAL ? MINUTE) 
                WHERE username = ?
            ");
            $stmt->execute([$lockoutMinutes, $username]);
            
            return [
                'locked' => true,
                'message' => "Account locked for {$lockoutMinutes} minutes due to too many failed login attempts"
            ];
        }
        
        return ['locked' => false, 'message' => ''];
    } catch (PDOException $e) {
        error_log("Error recording login attempt: " . $e->getMessage());
        return ['locked' => false, 'message' => ''];
    }
}

/**
 * Check if account is locked
 * @param string $username Username to check
 * @param PDO $pdo Database connection
 * @return bool
 */
function isAccountLocked($username, $pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT locked_until 
            FROM users 
            WHERE username = ? 
            AND locked_until > NOW()
        ");
        $stmt->execute([$username]);
        return $stmt->fetch() !== false;
    } catch (PDOException $e) {
        return false;
    }
}
?>