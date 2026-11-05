-- HKHK Tugi – laboriandmebaas (kõik andmed on väljamõeldud)
SET NAMES utf8mb4;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password CHAR(32) NOT NULL,          -- MD5 räsi (tahtlikult nõrk)
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user'
) DEFAULT CHARSET=utf8mb4;

CREATE TABLE tickets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(200) NOT NULL,
  body TEXT NOT NULL,
  created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
) DEFAULT CHARSET=utf8mb4;

INSERT INTO users (username, password, full_name, email, role) VALUES
 ('admin', MD5('Adm1n!Labor#2026'), 'Süsteemi Administraator', 'admin@hkhk.labor', 'admin'),
 ('mari',  MD5('mari123'),          'Mari Maasikas',           'mari@hkhk.labor',  'user'),
 ('juri',  MD5('juri123'),          'Jüri Jõgi',               'juri@hkhk.labor',  'user');

INSERT INTO tickets (user_id, title, body) VALUES
 (3, 'Printer ei tööta', 'Klassi A-204 printer annab paberiummistuse vea.'),
 (3, 'Uue sülearvuti soov', 'Vajan arvutit, mille mälu on vähemalt 16 GB.'),
 (2, 'Parool aegus', 'Moodle''i parool aegus, palun lähtestage.'),
 (1, 'KONFIDENTSIAALNE: VPN', 'Ajutine VPN-i võti serveriruumi jaoks: LABOR-VPN-7731 (väljamõeldud).'),
 (2, 'WiFi katkeb', 'Raamatukogus katkeb WiFi iga 10 minuti järel.');
