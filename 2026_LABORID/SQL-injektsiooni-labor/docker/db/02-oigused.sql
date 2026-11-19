-- =====================================================================
--  02-oigused.sql — veebikasutaja õigused Dockeri jaoks
--  Dockeris ühendub veebikonteiner db-ga ÜLE VÕRGU (mitte localhost),
--  seega vajame kasutajat hostiga '%'. Lisaks anname FILE-õiguse
--  (globaalne), et Labor 7 (LOAD_FILE / INTO OUTFILE) töötaks.
--  AINULT laborile!
-- =====================================================================
CREATE USER IF NOT EXISTS 'veebipood'@'%' IDENTIFIED BY 'veebipood';
GRANT ALL PRIVILEGES ON pood.* TO 'veebipood'@'%';
GRANT FILE ON *.* TO 'veebipood'@'%';
FLUSH PRIVILEGES;
