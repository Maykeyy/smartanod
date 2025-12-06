<?php
// auth/forgot_password.php
require_once '../config.php';
require_once '../lib/email.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect(BASE_URL . '/dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        $email = sanitize($_POST['email'] ?? '');

        if (empty($email)) {
            $error = 'Please enter your email address';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address';
        } else {
            try {
                // Check if user exists
                $stmt = $pdo->prepare("SELECT id, name FROM users WHERE email = ? AND is_active = 1");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user) {
                    // Generate reset token
                    $token = generateToken();
                    $expiresAt = date('Y-m-d H:i:s', time() + PASSWORD_RESET_EXPIRY);

                    // Delete any existing unused tokens for this user
                    $stmt = $pdo->prepare("DELETE FROM password_resets WHERE user_id = ? AND used = 0");
                    $stmt->execute([$user['id']]);

                    // Insert new token
                    $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
                    $stmt->execute([$user['id'], $token, $expiresAt]);

                    // Send reset email
                    $resetLink = BASE_URL . '/auth/reset_password.php?token=' . $token;
                    $emailSent = sendPasswordResetEmail($email, $user['name'], $resetLink);

                    if ($emailSent) {
                        $success = 'Password reset link has been sent to your email!';
                    } else {
                        $error = 'Failed to send email. Please try again later.';
                    }
                } else {
                    // Don't reveal if email exists or not (security best practice)
                    $success = 'If an account exists with this email, a password reset link has been sent.';
                }
            } catch (PDOException $e) {
                $error = 'An error occurred. Please try again.';
                error_log($e->getMessage());
            }
        }
    }
}

$csrf_token = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-orange': '#FF6A00',
                        'brand-orange-hover': '#FF8A3D',
                        'brand-green': '#16A34A'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4 py-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-8">
        <!-- Logo and Site Name -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-brand-orange to-orange-600 flex items-center justify-center shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <h1 class="mt-4 text-2xl md:text-3xl font-semibold text-gray-900"><?php echo SITE_NAME; ?></h1>
            <p class="text-sm text-gray-500 mt-1">Barangay Management System</p>
        </div>

        <!-- Success Message -->
        <?php if ($success): ?>
        <div class="mb-4 bg-green-50 border border-green-500 text-green-700 px-4 py-3 rounded-lg flex items-start">
            <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm"><?php echo htmlspecialchars($success); ?></span>
        </div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if ($error): ?>
        <div class="mb-4 bg-red-50 border border-red-500 text-red-700 px-4 py-3 rounded-lg flex items-start">
            <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm"><?php echo htmlspecialchars($error); ?></span>
        </div>
        <?php endif; ?>

        <!-- Forgot Password Form -->
        <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-2 text-center">Reset Password</h2>
            <p class="text-sm text-gray-600 mb-6 text-center">
                Enter your email and we'll send you a reset link
            </p>
            
            <form method="POST" action="" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required 
                        class="w-full rounded-lg border border-gray-300 shadow-sm py-3 px-4 focus:ring-2 focus:ring-brand-orange focus:border-transparent transition"
                        placeholder="your@email.com"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                    >
                </div>

                <!-- Send Reset Link Button -->
                <button 
                    type="submit"
                    class="w-full py-3 rounded-lg bg-brand-orange text-white font-semibold hover:bg-brand-orange-hover transition duration-150 ease-in-out shadow-md"
                >
                    Send Reset Link
                </button>

                <!-- Back to Login Link -->
                <div class="text-center text-sm text-gray-600 pt-2">
                    <a href="login.php" class="font-semibold text-brand-green hover:text-green-700 transition">
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>