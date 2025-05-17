<?php
$title = "Register";
include 'header.php';
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $wa = trim($_POST['wa'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if ($name && $email && $wa && $password) {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email sudah terdaftar.";
        } else {
            // Hash the password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            // Insert new user
            $stmt = $pdo->prepare("INSERT INTO users (name, email, wa, password, role) VALUES (?, ?, ?, ?, 'user')");
            if ($stmt->execute([$name, $email, $wa, $passwordHash])) {
                header('Location: log.php');
                exit;
            } else {
                $error = "Gagal mendaftar. Silakan coba lagi.";
            }
        }
    } else {
        $error = "Semua field harus diisi.";
    }
}
?>
  <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md mx-auto mt-16">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Create Your Account</h2>
    <?php if (!empty($error)): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>
    <form class="space-y-6" action="" method="POST" aria-label="Register form">
      <div>
        <label for="name" class="block text-gray-700 mb-2">Full Name</label>
        <input
          type="text"
          id="name"
          name="name"
          required
          value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
          placeholder="Your full name"
          aria-required="true"
        />
      </div>
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
        />
      </div>
      <div>
        <label for="wa" class="block text-gray-700 mb-2">WhatsApp Number</label>
        <input
          type="tel"
          id="wa"
          name="wa"
          required
          value="<?= htmlspecialchars($_POST['wa'] ?? '') ?>"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
          placeholder="Nomor WhatsApp"
          aria-required="true"
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
          placeholder="Create a password"
          aria-required="true"
        />
      </div>
      <button
        type="submit"
        class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
      >
        Register
      </button>
    </form>
    <p class="mt-6 text-center text-gray-600">
      Already have an account?
      <a href="log.php" class="text-indigo-600 hover:text-indigo-500 font-medium">Sign in</a>
    </p>
  </div>
<?php include 'footer.php'; ?>
