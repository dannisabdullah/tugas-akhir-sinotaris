<?php
$title = "Pemilihan Waktu";
include 'header.php';
include 'navbar.php';
include 'db.php';

$selectedServices = $_POST['layanan'] ?? $_GET['layanan'] ?? [];

// Fetch available time slots from database
$stmt = $pdo->prepare("SELECT * FROM available_slots ORDER BY day, time_slot");
$stmt->execute();
$availableSlots = $stmt->fetchAll();

// Fetch booked slots
$stmt = $pdo->prepare("
    SELECT day, time_slot 
    FROM bookings 
    WHERE status IN ('pending', 'confirmed')
");
$stmt->execute();
$bookedSlots = $stmt->fetchAll();

// Create arrays of disabled slots
$disabledSlots = [];
$bookedTimeSlots = [];

foreach ($availableSlots as $slot) {
    if (!$slot['is_available']) {
        $disabledSlots[$slot['day']][] = $slot['time_slot'];
    }
}

foreach ($bookedSlots as $slot) {
    $bookedTimeSlots[$slot['day']][] = $slot['time_slot'];
}
?>
<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl mx-auto">
  <h1 class="text-3xl font-semibold text-gray-800 mb-8 text-center">Pilih Jadwal</h1>
  <form action="review.php" method="POST" class="space-y-6">
    <div id="selected-services" class="mb-6 p-4 border border-indigo-300 rounded bg-indigo-50 text-indigo-700">
      <h3 class="font-semibold mb-2">Layanan Terpilih:</h3>
      <ul class="list-disc list-inside">
        <?php foreach ($selectedServices as $service): ?>
          <li><?= htmlspecialchars($service) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php foreach ($selectedServices as $service): ?>
      <input type="hidden" name="layanan[]" value="<?= htmlspecialchars($service) ?>">
    <?php endforeach; ?>
    <div>
      <label for="day" class="block text-gray-700 mb-2 font-semibold">Pilih Hari</label>
      <select id="day" name="day" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="" disabled selected>Pilih hari</option>
        <?php
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        foreach ($days as $day) {
            $isDisabled = isset($disabledSlots[$day]) && count($disabledSlots[$day]) === 6; // All slots disabled
            echo "<option value=\"$day\"" . ($isDisabled ? " disabled" : "") . ">$day" . 
                 ($isDisabled ? " (Tidak tersedia)" : "") . "</option>";
        }
        ?>
      </select>
    </div>
    <div>
      <label for="time" class="block text-gray-700 mb-2 font-semibold">Pilih Jam</label>
      <select id="time" name="time" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="" disabled selected>Pilih jam</option>
        <?php
        $timeSlots = [
            '09:00 - 10:00', '10:00 - 11:00', '11:00 - 12:00',
            '13:00 - 14:00', '14:00 - 15:00', '15:00 - 16:00'
        ];
        foreach ($timeSlots as $slot) {
            echo "<option value=\"$slot\">$slot</option>";
        }
        ?>
      </select>
    </div>
    <button type="submit" id="continue-btn" class="w-full bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
      Continue
    </button>
    <div id="error-message" class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded hidden" role="alert">
      Waktu yang dipilih tidak tersedia. Silakan pilih waktu lain.
    </div>
  </form>
</div>

<script>
// Convert PHP arrays to JavaScript
const disabledSlots = <?= json_encode($disabledSlots) ?>;
const bookedTimeSlots = <?= json_encode($bookedTimeSlots) ?>;

const daySelect = document.getElementById('day');
const timeSelect = document.getElementById('time');
const continueBtn = document.getElementById('continue-btn');
const errorMessage = document.getElementById('error-message');

function updateTimeOptions() {
    const selectedDay = daySelect.value;
    const disabled = disabledSlots[selectedDay] || [];
    const booked = bookedTimeSlots[selectedDay] || [];

    for (let i = 0; i < timeSelect.options.length; i++) {
        const option = timeSelect.options[i];
        if (option.value === "") continue; // skip placeholder

        const isDisabled = disabled.includes(option.value);
        const isBooked = booked.includes(option.value);
        
        option.disabled = isDisabled || isBooked;
        option.classList.toggle('text-gray-400', option.disabled);
        option.classList.toggle('cursor-not-allowed', option.disabled);
        
        if (isDisabled) {
            option.text = `${option.value} (Tidak tersedia)`;
        } else if (isBooked) {
            option.text = `${option.value} (Sudah dipesan)`;
        } else {
            option.text = option.value;
        }
    }

    if (timeSelect.selectedOptions.length > 0 && timeSelect.selectedOptions[0].disabled) {
        timeSelect.value = "";
    }
}

daySelect.addEventListener('change', updateTimeOptions);

continueBtn.addEventListener('click', (e) => {
    const selectedTime = timeSelect.value;
    if (!selectedTime || timeSelect.options[timeSelect.selectedIndex].disabled) {
        e.preventDefault();
        errorMessage.classList.remove('hidden');
        window.scrollTo({ top: errorMessage.offsetTop - 20, behavior: 'smooth' });
    } else {
        errorMessage.classList.add('hidden');
    }
});

// Initialize on page load
if (daySelect.value) {
    updateTimeOptions();
}
</script>
