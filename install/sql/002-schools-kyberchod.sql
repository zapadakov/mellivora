ALTER TABLE countries MODIFY COLUMN country_name VARCHAR(100);
ALTER TABLE countries MODIFY COLUMN country_code VARCHAR(20);

INSERT INTO countries (id, country_name, country_code) VALUES
(1, 'Czech Republic', 'cz'),
(2, 'Vyšší odborná škola, Obchodní akademie a Střední zdravotnická škola, Domažlice', 'oadomazlice'),
(3, 'Střední škola informatiky a finančních služeb, Plzeň', 'infis'),
(4, 'Střední odborné učiliště elektrotechnické, Plzeň', 'souepl'),
(5, 'Vyšší odborná škola a Střední průmyslová škola elektrotechnická, Plzeň', 'spseplzen'),
(6, 'Gymnázium a Střední odborná škola, Rokycany', 'gasos-ro'),
(7, 'Střední odborná škola, Stříbro', 'sosstribro'),
(8, 'Střední průmyslová škola, Tachov', 'sps-tachov'),
(9, 'Gymnázium J.Š.Baara, Domažlice', 'gymdom'),
(10, 'Gymnázium Jaroslava Vrchlického, Klatovy', 'gymkt'),
(11, 'Gymnázium, Sušice', 'gymsusice'),
(12, 'Gymnázium, Blovice', 'gblovice'),
(13, 'Masarykovo gymnázium, Plzeň', 'mgplzen'),
(14, 'Gymnázium, Plzeň', 'mikulasske'),
(15, 'Gymnázium Luďka Pika, Plzeň', 'gop'),
(16, 'Sportovní gymnázium Plzeň', 'sgpilsen'),
(17, 'Gymnázium a Střední odborná škola', 'gsplasy'),
(18, 'Gymnázium, Stříbro', 'goas'),
(19, 'Gymnázium, Tachov', 'gymtc'),
(20, 'Církevní gymnázium Plzeň', 'cg-plzen'),
(21, 'Soukromá Střední odborná škola a Gymnázium BEAN, s.r.o.', 'ssbean'),
(22, 'Gymnázium Františka Křižíka a základní škola, s.r.o.', 'krizik'),
(23, 'Střední škola zemědělská a potravinářská, Klatovy', 'sszpkt');