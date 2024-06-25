# Test Technique Symfony

J'ai utilisé WampServer pour faire ma base de donnée, le docker compose n'est pas encore au point.

Outils & languages utilisé :

- Symfony / Twig
- JavaScript natif
- TailwindCSS

# Données

INSERT INTO vehicles (brand, model)
VALUES ('Volkswagen', 'California T6 Ocean'),
('Mercedes', 'Marco Polo Horizon'),
('Ford', 'Transit Custom Active'),
('Citroën', 'Jumper Nomad'),
('Peugeot', 'Boxer Traveller');

INSERT INTO disponibilities (vehicle_id, departure_date, return_date, price, status)
VALUES (1, '2024-07-01', '2024-07-15', 120, 1),
(1, '2024-08-10', '2024-08-25', 130, 1),
(2, '2024-06-20', '2024-06-30', 150, 0),
(2, '2024-09-05', '2024-09-20', 140, 1),
(3, '2024-07-12', '2024-07-27', 100, 1),
(3, '2024-08-01', '2024-08-14', 110, 0),
(4, '2024-06-05', '2024-06-19', 90, 1),
(4, '2024-09-22', '2024-10-07', 120, 1),
(5, '2024-07-08', '2024-07-23', 80, 1),
(5, '2024-08-15', '2024-08-30', 95, 0);

INSERT INTO disponibilities (vehicle_id, departure_date, return_date, price, status)
VALUES
-- Véhicule 1
(1, '2024-07-01', '2024-07-15', 120, 1),
(1, '2024-08-10', '2024-08-25', 130, 1),
(1, '2024-09-05', '2024-09-20', 140, 0), -- Véhicule indisponible
(1, '2024-10-01', '2024-10-15', 150, 1),

-- Véhicule 2
(2, '2024-06-20', '2024-06-30', 150, 0), -- Véhicule indisponible
(2, '2024-07-05', '2024-07-20', 140, 1),
(2, '2024-08-01', '2024-08-14', 110, 0), -- Véhicule indisponible
(2, '2024-09-22', '2024-10-07', 120, 1),

-- Véhicule 3
(3, '2024-06-05', '2024-06-19', 90, 1),
(3, '2024-07-12', '2024-07-27', 100, 1),
(3, '2024-08-15', '2024-08-30', 95, 0), -- Véhicule indisponible
(3, '2024-10-10', '2024-10-25', 115, 1),

-- Véhicule 4
(4, '2024-07-08', '2024-07-23', 80, 1),
(4, '2024-08-01', '2024-08-14', 110, 1),
(4, '2024-09-05', '2024-09-20', 120, 0), -- Véhicule indisponible
(4, '2024-10-01', '2024-10-15', 130, 1),

-- Véhicule 5
(5, '2024-06-20', '2024-06-30', 150, 1),
(5, '2024-07-12', '2024-07-27', 100, 1),
(5, '2024-08-15', '2024-08-30', 95, 0), -- Véhicule indisponible
(5, '2024-09-22', '2024-10-07', 120, 1);

# TODO

- Docker
- Bonus # 1
- Bonus # 2
- Input de type date personnalisabl.
