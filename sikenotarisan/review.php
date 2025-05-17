<?php
$title = "Review & Konfirmasi";
include 'header.php';
include 'navbar.php';

$selectedServices = $_POST['layanan'] ?? $_GET['layanan'] ?? [];
if (is_string($selectedServices)) {
    $selectedServices = explode(',', $selectedServices);
}
$selectedDay = $_POST['day'] ?? '';
$selectedTime = $_POST['time'] ?? '';
?>
<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl mx-auto space-y-8">
  <h1 class="text-3xl font-semibold text-gray-800 mb-8 text-center">Review & Konfirmasi Reservasi</h1>

  <div class="border border-gray-300 rounded p-6">
    <h2 class="text-xl font-semibold mb-4">Layanan Terpilih</h2>
    <?php if (count($selectedServices) > 0): ?>
      <ul class="list-disc list-inside text-gray-700">
        <?php foreach ($selectedServices as $service): ?>
          <li><?= htmlspecialchars($service) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p class="text-gray-600">Tidak ada layanan yang dipilih.</p>
    <?php endif; ?>
  </div>

  <div class="border border-gray-300 rounded p-6">
    <h2 class="text-xl font-semibold mb-4">Jadwal Pilihan</h2>
    <?php if ($selectedDay && $selectedTime): ?>
      <p class="text-gray-700">Hari: <span class="font-medium"><?= htmlspecialchars($selectedDay) ?></span></p>
      <?php
        // Parse start and end time from selectedTime
        $times = explode('-', $selectedTime);
        $startTime = $times[0] ?? '';
        $endTime = $times[1] ?? '';
      ?>
      <p class="text-gray-700">Jam Mulai: <span class="font-medium"><?= htmlspecialchars($startTime) ?></span></p>
      <p class="text-gray-700">Jam Selesai: <span class="font-medium"><?= htmlspecialchars($endTime) ?></span></p>
    <?php else: ?>
      <p class="text-gray-600">Jadwal belum dipilih.</p>
    <?php endif; ?>
  </div>

  <div class="border border-gray-300 rounded p-6 bg-gray-50">
    <h2 class="text-xl font-semibold mb-4">Syarat dan Ketentuan Reservasi</h2>
    <ul class="list-disc list-inside text-gray-700 space-y-2">
      <li>Reservasi harus dilakukan minimal 1 hari sebelum jadwal yang dipilih.</li>
      <li>Harap datang tepat waktu sesuai jadwal yang telah dipilih.</li>
      <li>Jika ingin membatalkan atau mengubah jadwal, harap hubungi kami minimal 24 jam sebelumnya.</li>
      <li>Reservasi dianggap batal jika tidak hadir tanpa pemberitahuan.</li>
      <li>Notaris berhak menolak reservasi jika syarat dan ketentuan tidak dipenuhi.</li>
    </ul>
  </div>

  <form action="booking_success.php" method="POST" class="text-center space-y-4">
    <?php foreach ($selectedServices as $service): ?>
      <input type="hidden" name="layanan[]" value="<?= htmlspecialchars($service) ?>">
    <?php endforeach; ?>
    <input type="hidden" name="day" value="<?= htmlspecialchars($selectedDay) ?>">
    <input type="hidden" name="time" value="<?= htmlspecialchars($selectedTime) ?>">
    <div>
      <label for="notes" class="block text-left font-semibold mb-2">Catatan untuk Admin</label>
      <textarea id="notes" name="notes" rows="4" class="w-full max-w-3xl mx-auto px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Tambahkan catatan atau permintaan khusus..."></textarea>
    </div>
    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
      Book Now
    </button>
  </form>
</div>
<?php include 'footer.php'; ?>
