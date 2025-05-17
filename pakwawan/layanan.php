<?php $title = "Layanan Notaris"; ?>
<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>
  <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl mx-auto">
    <h1 class="text-3xl font-semibold text-gray-800 mb-8 text-center">Layanan Notaris</h1>
    <form action="jadwal.php" method="POST" class="space-y-6">
      <div id="selected-services" class="mb-6 p-4 border border-indigo-300 rounded bg-indigo-50 text-indigo-700 hidden">
        <h3 class="font-semibold mb-2">Layanan Terpilih:</h3>
        <ul id="selected-list" class="list-disc list-inside"></ul>
      </div>
      <div class="flex items-start space-x-4">
        <input type="checkbox" id="pembuatan_akta" name="layanan[]" value="Pembuatan Akta" class="mt-1 h-5 w-5 text-indigo-600 border-gray-300 rounded" />
        <label for="pembuatan_akta" class="flex-1 cursor-pointer">
          <div class="flex items-center mb-1">
            <i class="fas fa-file-contract text-indigo-600 text-2xl mr-3"></i>
            <span class="text-xl font-semibold text-gray-700">Pembuatan Akta</span>
          </div>
          <p class="text-gray-600">Membantu pembuatan akta otentik seperti akta pendirian perusahaan, perjanjian, dan lainnya.</p>
        </label>
      </div>
      <div class="flex items-start space-x-4">
        <input type="checkbox" id="pengesahan_dokumen" name="layanan[]" value="Pengesahan Dokumen" class="mt-1 h-5 w-5 text-indigo-600 border-gray-300 rounded" />
        <label for="pengesahan_dokumen" class="flex-1 cursor-pointer">
          <div class="flex items-center mb-1">
            <i class="fas fa-handshake text-indigo-600 text-2xl mr-3"></i>
            <span class="text-xl font-semibold text-gray-700">Pengesahan Dokumen</span>
          </div>
          <p class="text-gray-600">Mengesahkan dokumen penting agar memiliki kekuatan hukum yang sah.</p>
        </label>
      </div>
      <div class="flex items-start space-x-4">
        <input type="checkbox" id="konsultasi_hukum" name="layanan[]" value="Konsultasi Hukum" class="mt-1 h-5 w-5 text-indigo-600 border-gray-300 rounded" />
        <label for="konsultasi_hukum" class="flex-1 cursor-pointer">
          <div class="flex items-center mb-1">
            <i class="fas fa-balance-scale text-indigo-600 text-2xl mr-3"></i>
            <span class="text-xl font-semibold text-gray-700">Konsultasi Hukum</span>
          </div>
          <p class="text-gray-600">Memberikan konsultasi hukum terkait berbagai masalah notaris dan hukum perdata.</p>
        </label>
      </div>
      <div class="flex items-start space-x-4">
        <input type="checkbox" id="pembuatan_surat_kuasa" name="layanan[]" value="Pembuatan Surat Kuasa" class="mt-1 h-5 w-5 text-indigo-600 border-gray-300 rounded" />
        <label for="pembuatan_surat_kuasa" class="flex-1 cursor-pointer">
          <div class="flex items-center mb-1">
            <i class="fas fa-file-invoice-dollar text-indigo-600 text-2xl mr-3"></i>
            <span class="text-xl font-semibold text-gray-700">Pembuatan Surat Kuasa</span>
          </div>
          <p class="text-gray-600">Membantu pembuatan surat kuasa untuk berbagai keperluan hukum dan bisnis.</p>
        </label>
      </div>
      <div class="flex items-start space-x-4">
        <input type="checkbox" id="pengurusan_sertifikat" name="layanan[]" value="Pengurusan Sertifikat" class="mt-1 h-5 w-5 text-indigo-600 border-gray-300 rounded" />
        <label for="pengurusan_sertifikat" class="flex-1 cursor-pointer">
          <div class="flex items-center mb-1">
            <i class="fas fa-landmark text-indigo-600 text-2xl mr-3"></i>
            <span class="text-xl font-semibold text-gray-700">Pengurusan Sertifikat</span>
          </div>
          <p class="text-gray-600">Membantu pengurusan sertifikat tanah dan dokumen properti lainnya.</p>
        </label>
      </div>
      <div class="flex items-start space-x-4">
        <input type="checkbox" id="layanan_lainnya" name="layanan[]" value="Layanan Lainnya" class="mt-1 h-5 w-5 text-indigo-600 border-gray-300 rounded" />
        <label for="layanan_lainnya" class="flex-1 cursor-pointer">
          <div class="flex items-center mb-1">
            <i class="fas fa-file-alt text-indigo-600 text-2xl mr-3"></i>
            <span class="text-xl font-semibold text-gray-700">Layanan Lainnya</span>
          </div>
          <p class="text-gray-600">Layanan notaris lainnya sesuai kebutuhan klien.</p>
        </label>
      </div>
      <div>
        <a href="jadwal.php" id="continue-link" class="mt-6 block text-center bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
          Continue
        </a>
      </div>
    </form>
  </div>
   <script>
     const checkboxes = document.querySelectorAll('input[name="layanan[]"]');
     const selectedServices = document.getElementById('selected-services');
     const selectedList = document.getElementById('selected-list');
     const continueLink = document.getElementById('continue-link');

     function updateSelectedServices() {
       const selected = [];
       checkboxes.forEach((checkbox) => {
         if (checkbox.checked) {
           selected.push(checkbox.value);
         }
       });

       if (selected.length > 0) {
         selectedServices.classList.remove('hidden');
         selectedList.innerHTML = '';
         selected.forEach((service) => {
           const li = document.createElement('li');
           li.textContent = service;
           selectedList.appendChild(li);
         });
         // Update continue link href with selected services as query params
         const params = new URLSearchParams();
         selected.forEach(service => params.append('layanan[]', service));
         continueLink.href = 'jadwal.php?' + params.toString();
         continueLink.classList.remove('pointer-events-none', 'opacity-50');
       } else {
         selectedServices.classList.add('hidden');
         selectedList.innerHTML = '';
         continueLink.href = '#';
         continueLink.classList.add('pointer-events-none', 'opacity-50');
       }
     }

     checkboxes.forEach((checkbox) => {
       checkbox.addEventListener('change', updateSelectedServices);
     });

     // Initialize on page load
     updateSelectedServices();
  </script>
<?php include 'footer.php'; ?>
