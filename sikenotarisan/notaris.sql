-- Users table for registration and login
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  wa VARCHAR(20) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('user', 'admin') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insert admin user with properly hashed password
INSERT INTO users (name, email, wa, password, role) VALUES
('Admin Notaris', 'admin@admin.com', '081234567890', '$2y$10$HY.vKNrEJCF6bBQQXgEBZOBGTXt0lAVAjk.8kBhEk.9bbR1qHgEcy', 'admin');
-- Password: admin123

-- Table for available time slots
CREATE TABLE IF NOT EXISTS available_slots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  day ENUM('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat') NOT NULL,
  time_slot VARCHAR(20) NOT NULL,
  is_available BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY day_time (day, time_slot)
) ENGINE=InnoDB;

-- Insert default time slots
INSERT INTO available_slots (day, time_slot) VALUES
('Senin', '09:00 - 10:00'), ('Senin', '10:00 - 11:00'), ('Senin', '11:00 - 12:00'),
('Senin', '13:00 - 14:00'), ('Senin', '14:00 - 15:00'), ('Senin', '15:00 - 16:00'),
('Selasa', '09:00 - 10:00'), ('Selasa', '10:00 - 11:00'), ('Selasa', '11:00 - 12:00'),
('Selasa', '13:00 - 14:00'), ('Selasa', '14:00 - 15:00'), ('Selasa', '15:00 - 16:00'),
('Rabu', '09:00 - 10:00'), ('Rabu', '10:00 - 11:00'), ('Rabu', '11:00 - 12:00'),
('Rabu', '13:00 - 14:00'), ('Rabu', '14:00 - 15:00'), ('Rabu', '15:00 - 16:00'),
('Kamis', '09:00 - 10:00'), ('Kamis', '10:00 - 11:00'), ('Kamis', '11:00 - 12:00'),
('Kamis', '13:00 - 14:00'), ('Kamis', '14:00 - 15:00'), ('Kamis', '15:00 - 16:00'),
('Jumat', '09:00 - 10:00'), ('Jumat', '10:00 - 11:00'), ('Jumat', '11:00 - 12:00'),
('Jumat', '13:00 - 14:00'), ('Jumat', '14:00 - 15:00'), ('Jumat', '15:00 - 16:00');

-- Services table: list of notary services
CREATE TABLE IF NOT EXISTS services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL UNIQUE,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Bookings table: records a reservation
CREATE TABLE IF NOT EXISTS bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  day ENUM('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat') NOT NULL,
  time_slot VARCHAR(20) NOT NULL,
  notes TEXT,
  status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_bookings_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Pivot table for many-to-many relationship between bookings and services
CREATE TABLE IF NOT EXISTS booking_services (
  booking_id INT NOT NULL,
  service_id INT NOT NULL,
  PRIMARY KEY (booking_id, service_id),
  CONSTRAINT fk_bs_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
  CONSTRAINT fk_bs_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Insert default services
INSERT INTO services (name, description) VALUES
('Pembuatan Akta', 'Membantu pembuatan akta otentik seperti akta pendirian perusahaan, perjanjian, dan lainnya.'),
('Pengesahan Dokumen', 'Mengesahkan dokumen penting agar memiliki kekuatan hukum yang sah.'),
('Konsultasi Hukum', 'Memberikan konsultasi hukum terkait berbagai masalah notaris dan hukum perdata.'),
('Pembuatan Surat Kuasa', 'Membantu pembuatan surat kuasa untuk berbagai keperluan hukum dan bisnis.'),
('Pengurusan Sertifikat', 'Membantu pengurusan sertifikat tanah dan dokumen properti lainnya.'),
('Layanan Lainnya', 'Layanan notaris lainnya sesuai kebutuhan klien.')
ON DUPLICATE KEY UPDATE name=name;

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
