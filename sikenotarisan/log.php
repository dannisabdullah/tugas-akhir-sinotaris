<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            // For debugging
            error_log("Login attempt - Email: " . $email);
            if ($user) {
                error_log("User found: " . print_r($user, true));
                error_log("Role: " . ($user['role'] ?? 'not set'));
            }

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: admin/index.php');
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                $error = "Email atau password salah.";
            }
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            $error = "Terjadi kesalahan sistem.";
        }
    } else {
        $error = "Email dan password harus diisi.";
    }
}
?>
<?php $title = "Login"; ?>
<?php include 'header.php'; ?>
<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md mx-auto mt-16">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Login to Your Account</h2>
    <?php if (!empty($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <form class="space-y-6" action="" method="POST" aria-label="Login form">
        <div>
            <label for="email" class="block text-gray-700 mb-2">Email address</label>
            <input
                type="email"
                id="email"
                name="email"
                required
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="you@example.com"
                aria-required="true"
                aria-describedby="email-desc"
            />
        </div>
        <div>
            <label for="password" class="block text-gray-700 mb-2">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="Enter your password"
                aria-required="true"
                aria-describedby="password-desc"
            />
        </div>
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                <label for="remember" class="ml-2 block text-gray-900">Remember me</label>
            </div>
            <div class="text-sm">
                <a href="reg.php" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot your password?</a>
            </div>
        </div>
        <button
            type="submit"
            class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
        >
            Sign In
        </button>
    </form>
    <div class="mt-6 text-center">
      <div class="mb-4 text-gray-600">
        Don't have an account?
        <a href="reg.php" class="text-indigo-600 hover:text-indigo-500 font-medium">Sign up</a>
      </div>
      <div class="relative">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
          <span class="px-2 bg-white text-gray-500">Or</span>
        </div>
      </div>
      <div class="mt-4">
        <a href="admin_login.php" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          Login as Admin
        </a>
      </div>
    </div>
</div>
<?php include 'footer.php'; ?>
