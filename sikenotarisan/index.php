<?php
session_start();
$title = "Beranda - Perusahaan Notaris";
include 'header.php';
include 'navbar.php';
?>
<div class="relative bg-white overflow-hidden">
  <div class="max-w-7xl mx-auto">
    <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:w-full lg:pb-28 xl:pb-32">
      <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
        <div class="sm:text-center lg:text-left">
          <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
            <span class="block xl:inline">Perusahaan Notaris</span>
            <span class="block text-indigo-600 xl:inline">Layanan Profesional dan Terpercaya</span>
          </h1>
          <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto lg:mx-0">
            Kami menyediakan layanan notaris lengkap dengan profesionalisme tinggi dan pelayanan terbaik untuk kebutuhan Anda.
          </p>
          <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start space-x-4">
            <div class="rounded-md shadow">
              <a href="layanandeks.php" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10 transition duration-300">
                Lihat Layanan Kami
              </a>
            </div>
            <?php if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true): ?>
            <div class="rounded-md shadow">
              <a href="log.php" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10 transition duration-300">
                Login Sekarang
              </a>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </main>
    </div>
  </div>
</div>

<section class="bg-gray-50 py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-extrabold text-gray-900 mb-12 text-center">Layanan Kami</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
        <h3 class="text-xl font-semibold mb-4">Pembuatan Akta</h3>
        <p class="text-gray-600">Membantu pembuatan akta notaris seperti akta pendirian perusahaan, perjanjian, dan dokumen hukum lainnya.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
        <h3 class="text-xl font-semibold mb-4">Konsultasi Hukum</h3>
        <p class="text-gray-600">Memberikan konsultasi hukum terkait berbagai masalah hukum dan peraturan yang berlaku.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
        <h3 class="text-xl font-semibold mb-4">Layanan Notaris Lainnya</h3>
        <p class="text-gray-600">Layanan notaris lainnya sesuai kebutuhan klien dengan pelayanan yang cepat dan terpercaya.</p>
      </div>
    </div>
  </div>
</section>

<section class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-extrabold text-gray-900 mb-12 text-center">Lokasi Kami</h2>
    <div class="flex justify-center">
      <iframe class="w-full max-w-4xl h-96 rounded-lg shadow-lg" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.1234567890123!2d110.123456789!3d-7.123456789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a123456789abc%3A0x123456789abcdef!2sPerusahaan%20Notaris!5e0!3m2!1sen!2sid!4v1234567890123" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</section>

<script>
  // Simple fade-in animation for sections
  document.addEventListener('DOMContentLoaded', () => {
    const sections = document.querySelectorAll('section, main');
    sections.forEach((section, index) => {
      section.style.opacity = 0;
      section.style.transform = 'translateY(20px)';
      setTimeout(() => {
        section.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        section.style.opacity = 1;
        section.style.transform = 'translateY(0)';
      }, index * 200);
    });
  });
</script>

<?php include 'footer.php'; ?>
