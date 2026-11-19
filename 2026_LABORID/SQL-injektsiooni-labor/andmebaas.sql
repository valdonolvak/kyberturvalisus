-- =====================================================================
--  SQL-süstimise labor — andmebaasi loomise skript
--  Kursus: Küberturvalisus (täiskasvanud IT-õppijad)
--  Andmebaasimootor: MySQL / MariaDB
--
--  HOIATUS: see andmebaas ja sellega seotud veebileht on TAHTLIKULT
--  haavatavad ja mõeldud AINULT isoleeritud laborikeskkonnas
--  (Proxmox SDN VNet, ilma väljastpoolt ligipääsuta) kasutamiseks.
--  Ära kunagi paigalda seda avalikku võrku ega tootmisserverisse.
-- =====================================================================

-- Loome õppeandmebaasi
DROP DATABASE IF EXISTS pood;
CREATE DATABASE pood CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pood;

-- ---------------------------------------------------------------------
-- Tabel: kasutajad  (siit "varastab" ründaja UNION-süstimisega andmed)
-- Paroolid on tahtlikult nõrgalt hoitud: osa avatekstina, osa
-- MD5-räsina, et näidata, miks nõrk räsistamine ei aita.
-- ---------------------------------------------------------------------
CREATE TABLE kasutajad (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    kasutaja  VARCHAR(50)  NOT NULL,
    parool    VARCHAR(255) NOT NULL,
    roll      VARCHAR(20)  NOT NULL DEFAULT 'kasutaja',
    email     VARCHAR(100)
);

INSERT INTO kasutajad (kasutaja, parool, roll, email) VALUES
('admin',  'SuperSalajane2019!',                'admin',    'admin@pood.local'),
('mart',   '827ccb0eea8a706c4c34a16891f84e7b',  'kasutaja', 'mart@pood.local'),   -- MD5('12345')
('kati',   'kati2024',                          'kasutaja', 'kati@pood.local'),
('juhan',  'e10adc3949ba59abbe56e057f20f883e',  'haldur',   'juhan@pood.local');  -- MD5('123456')

-- ---------------------------------------------------------------------
-- Tabel: tooted  (avalik toodete nimekiri, mida leht näitab)
-- ---------------------------------------------------------------------
CREATE TABLE tooted (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nimi      VARCHAR(100) NOT NULL,
    kirjeldus TEXT,
    hind      DECIMAL(8,2) NOT NULL,
    laos      INT NOT NULL DEFAULT 0
);

INSERT INTO tooted (nimi, kirjeldus, hind, laos) VALUES
('Klaviatuur',    'Mehaaniline klaviatuur, eesti paigutus',  49.90, 25),
('Hiir',          'Juhtmeta optiline hiir',                   19.90, 60),
('Monitor 24"',   'Full HD IPS-monitor',                     129.00, 12),
('Veebikaamera',  '1080p USB-veebikaamera',                   39.00,  8),
('USB-mälupulk',  '64 GB USB 3.0 mälupulk',                   12.50, 40);

-- ---------------------------------------------------------------------
-- Tabel: logid  (näitamaks andmete MUUTMIST / kustutamist rünnakul)
-- ---------------------------------------------------------------------
CREATE TABLE logid (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    aeg   DATETIME DEFAULT CURRENT_TIMESTAMP,
    tekst VARCHAR(255)
);

INSERT INTO logid (tekst) VALUES
('Süsteem käivitatud'),
('Kasutaja admin logis sisse');

-- ---------------------------------------------------------------------
-- Eraldi, piiratud õigustega andmebaasikasutaja veebilehe jaoks.
-- Laboris ANNAME tahtlikult liiga laiad õigused (sh andmete muutmine),
-- et demonstreerida manipuleerimist. Turvalises süsteemis annaks
-- veebirakendusele AINULT SELECT/INSERT/UPDATE vajalikele tabelitele.
-- ---------------------------------------------------------------------
CREATE USER IF NOT EXISTS 'veebipood'@'localhost' IDENTIFIED BY 'veebipood';
GRANT ALL PRIVILEGES ON pood.* TO 'veebipood'@'localhost';
FLUSH PRIVILEGES;
