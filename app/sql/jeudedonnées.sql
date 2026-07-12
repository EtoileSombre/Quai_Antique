SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- -------------------------------------------------------------
-- Comptes clients
-- -------------------------------------------------------------
INSERT INTO users (email, password, firstname, lastname, phone, default_guests, allergies, role) VALUES
('marie.dupont@email.fr',
 '$2y$10$TKh8H1.PyfSza4Exv6ZFbu/1DsHbfSGmANmGPIGgfZ5OaEaOc6Sey',
 'Marie', 'Dupont', '06 11 22 33 44', 2,
 NULL, 'client'),

('jean.martin@email.fr',
 '$2y$10$TKh8H1.PyfSza4Exv6ZFbu/1DsHbfSGmANmGPIGgfZ5OaEaOc6Sey',
 'Jean', 'Martin', '06 55 66 77 88', 4,
 'Intolérance au gluten', 'client'),

('sophie.bernard@email.fr',
 '$2y$10$TKh8H1.PyfSza4Exv6ZFbu/1DsHbfSGmANmGPIGgfZ5OaEaOc6Sey',
 'Sophie', 'Bernard', '07 12 34 56 78', 1,
 'Allergie aux fruits à coque', 'client'),

('pierre.leroy@email.fr',
 '$2y$10$TKh8H1.PyfSza4Exv6ZFbu/1DsHbfSGmANmGPIGgfZ5OaEaOc6Sey',
 'Pierre', 'Leroy', '06 98 76 54 32', 6,
 NULL, 'client'),

('camille.moreau@email.fr',
 '$2y$10$TKh8H1.PyfSza4Exv6ZFbu/1DsHbfSGmANmGPIGgfZ5OaEaOc6Sey',
 'Camille', 'Moreau', '07 45 67 89 01', 2,
 'Végétarienne', 'client'),

('thomas.petit@email.fr',
 '$2y$10$TKh8H1.PyfSza4Exv6ZFbu/1DsHbfSGmANmGPIGgfZ5OaEaOc6Sey',
 'Thomas', 'Petit', NULL, 3,
 'Allergie aux crustacés', 'client');

-- -------------------------------------------------------------
-- Galerie photos
-- -------------------------------------------------------------
INSERT INTO gallery (title, image_path, sort_order) VALUES
('La salle principale',      'uploads/galerie/salle-principale.jpg',   1),
('Vue sur le lac',            'uploads/galerie/vue-lac.jpg',            2),
('Filet de féra du Lac',      'uploads/galerie/fera-lac.jpg',           3),
('Carré d''agneau en croûte', 'uploads/galerie/agneau-herbes.jpg',      4),
('Tarte aux myrtilles',       'uploads/galerie/tarte-myrtilles.jpg',    5),
('Crème brûlée chartreuse',   'uploads/galerie/creme-brulee.jpg',       6),
('Bar en terrasse',           'uploads/galerie/terrasse.jpg',           7),
('Fondue savoyarde',          'uploads/galerie/fondue-savoyarde.jpg',   8);

-- -------------------------------------------------------------
-- Réservations de test
-- user_id : 2 = Marie Dupont, 3 = Jean Martin, 4 = Sophie Bernard,
--           5 = Pierre Leroy, 6 = Camille Moreau, 7 = Thomas Petit
-- -------------------------------------------------------------
INSERT INTO reservations (user_id, reservation_date, reservation_time, guests, allergies, status) VALUES
-- Réservations à venir (confirmées)
(2, '2026-07-18', '12:30', 2, NULL,                             'confirmed'),
(3, '2026-07-18', '19:30', 4, 'Intolérance au gluten',         'confirmed'),
(4, '2026-07-19', '20:00', 1, 'Allergie aux fruits à coque',   'confirmed'),
(5, '2026-07-19', '12:00', 6, NULL,                             'confirmed'),
(6, '2026-07-25', '19:00', 2, 'Végétarienne',                  'confirmed'),
(7, '2026-07-26', '20:30', 3, 'Allergie aux crustacés',        'confirmed'),
(2, '2026-08-02', '12:30', 2, NULL,                             'confirmed'),

-- Réservations passées (confirmées — historique)
(3, '2026-06-14', '19:30', 4, 'Intolérance au gluten',         'confirmed'),
(5, '2026-06-20', '12:00', 5, NULL,                             'confirmed'),
(6, '2026-07-04', '19:00', 2, 'Végétarienne',                  'confirmed'),

-- Réservations annulées
(4, '2026-07-10', '20:00', 1, NULL,                             'cancelled'),
(7, '2026-07-05', '19:30', 3, 'Allergie aux crustacés',        'cancelled');
