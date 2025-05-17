<nav class="bg-indigo-700 text-white shadow">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16 items-center">
      <div class="flex items-center space-x-2">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
        </svg>
        <div class="flex-shrink-0 text-2xl font-notaris tracking-wide">
          Notaris R. Dewi Agung
        </div>
      </div>
      <div class="hidden sm:flex sm:space-x-8 relative items-center">
        <button id="menu-button" type="button" aria-haspopup="true" aria-expanded="false" class="inline-flex items-center px-3 py-2 text-sm font-medium hover:text-indigo-300 focus:outline-none">
          Menu
          <svg class="ml-1 h-4 w-4 fill-current" viewBox="0 0 20 20">
            <path d="M5.25 7.5L10 12.25L14.75 7.5H5.25Z" />
          </svg>
        </button>
        <div id="dropdown-menu" class="absolute left-0 top-full mt-1 w-48 bg-white text-gray-800 border border-gray-300 rounded-md shadow-lg opacity-0 invisible transition-opacity duration-200 z-50">
          <a href="index.php" class="block px-4 py-2 hover:bg-indigo-100 hover:text-indigo-700">Home</a>
          <a href="layanan.php" class="block px-4 py-2 hover:bg-indigo-100 hover:text-indigo-700">Reservasi</a>
          <a href="layanandeks.php" class="block px-4 py-2 hover:bg-indigo-100 hover:text-indigo-700">Layanan</a>
          <a href="about.php" class="block px-4 py-2 hover:bg-indigo-100 hover:text-indigo-700">About</a>
          <a href="kontak.php" class="block px-4 py-2 hover:bg-indigo-100 hover:text-indigo-700">Kontak Kami</a>
        </div>
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
          <div class="ml-6 relative">
            <button id="profile-button" type="button" aria-haspopup="true" aria-expanded="false" class="flex items-center space-x-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              <span class="text-white font-semibold"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
              <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 9l6 6 6-6" />
              </svg>
            </button>
            <div id="profile-dropdown" class="absolute right-0 top-full mt-1 w-56 bg-white text-gray-800 border border-gray-300 rounded-md shadow-lg opacity-0 invisible transition-opacity duration-200 z-50">
              <div class="px-4 py-3 border-b border-gray-200">
                <p class="text-sm font-semibold"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></p>
                <p class="text-xs text-gray-500 truncate"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></p>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                  <p class="text-xs font-medium text-indigo-600">Administrator</p>
                <?php endif; ?>
              </div>
              <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="/pakwawan/admin/index.php" class="block px-4 py-2 hover:bg-indigo-100 hover:text-indigo-700">
                  <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Admin Dashboard</span>
                  </div>
                </a>
              <?php else: ?>
                <a href="reservasi_saya.php" class="block px-4 py-2 hover:bg-indigo-100 hover:text-indigo-700">
                  <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span>Reservasi Anda</span>
                  </div>
                </a>
              <?php endif; ?>
              <a href="/pakwawan/logout.php" class="block px-4 py-2 hover:bg-indigo-100 hover:text-indigo-700">Logout</a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <script>
    const menuButton = document.getElementById('menu-button');
    const dropdownMenu = document.getElementById('dropdown-menu');

    menuButton.addEventListener('click', () => {
      const expanded = menuButton.getAttribute('aria-expanded') === 'true' || false;
      menuButton.setAttribute('aria-expanded', !expanded);
      if (!expanded) {
        dropdownMenu.classList.remove('opacity-0', 'invisible');
      } else {
        dropdownMenu.classList.add('opacity-0', 'invisible');
      }
    });

    // Profile dropdown toggle
    const profileButton = document.getElementById('profile-button');
    const profileDropdown = document.getElementById('profile-dropdown');

    if (profileButton && profileDropdown) {
      profileButton.addEventListener('click', (e) => {
        e.stopPropagation();
        const expanded = profileButton.getAttribute('aria-expanded') === 'true' || false;
        profileButton.setAttribute('aria-expanded', !expanded);
        if (!expanded) {
          profileDropdown.classList.remove('opacity-0', 'invisible');
        } else {
          profileDropdown.classList.add('opacity-0', 'invisible');
        }
      });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', (event) => {
      if (!menuButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
        menuButton.setAttribute('aria-expanded', 'false');
        dropdownMenu.classList.add('opacity-0', 'invisible');
      }
      if (profileButton && profileDropdown && !profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
        profileButton.setAttribute('aria-expanded', 'false');
        profileDropdown.classList.add('opacity-0', 'invisible');
      }
    });
  </script>
</nav>
