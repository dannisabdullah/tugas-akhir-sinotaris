<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === 'admin@admin.com' && $password === 'admin123') {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = 1;
        $_SESSION['user_name'] = 'Admin Notaris';
        $_SESSION['user_email'] = 'admin@admin.com';
        $_SESSION['role'] = 'admin';

        header('Location: admin/index.php');
        exit;
    } else {
        $error = "Email atau password salah.";
    }
}
?>
<?php $title = "Admin Login"; ?>
<?php include 'header.php'; ?>
<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md mx-auto mt-16">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-semibold text-gray-800">Admin Login</h2>
        <p class="text-gray-600 mt-2">Login khusus untuk administrator</p>
        <div class="mt-2 text-sm text-gray-500">
            Email: admin@admin.com<br>
            Password: admin123
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form class="space-y-6" action="" method="POST" aria-label="Admin login form">
        <div>
            <label for="email" class="block text-gray-700 mb-2">Email Admin</label>
            <input
                type="email"
                id="email"
                name="email"
                required
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="admin@example.com"
                aria-required="true"
            />
        </div>
        <div>
            <label for="password" class="block text-gray-700 mb-2">Password Admin</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="Enter admin password"
                aria-required="true"
            />
        </div>
        <button
            type="submit"
            class="w-full bg-gray-800 text-white py-2 rounded-md hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500"
        >
            Login as Admin
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="log.php" class="text-sm text-gray-600 hover:text-gray-900">
            ← Kembali ke login user
        </a>
    </div>
</div>
<?php include 'footer.php'; ?>
