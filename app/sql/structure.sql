-- Base de données Quai Antique
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    default_guests INT DEFAULT 1,
    allergies TEXT DEFAULT NULL,
    role ENUM('client', 'admin') NOT NULL DEFAULT 'client',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS opening_hours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    day_of_week TINYINT NOT NULL COMMENT '1=Lundi, 7=Dimanche',
    lunch_start TIME DEFAULT NULL,
    lunch_end TIME DEFAULT NULL,
    dinner_start TIME DEFAULT NULL,
    dinner_end TIME DEFAULT NULL,
    is_closed TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS restaurant_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    max_capacity INT NOT NULL DEFAULT 50,
    setting_key VARCHAR(100) DEFAULT NULL,
    setting_value TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS dishes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    price DECIMAL(6,2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    price DECIMAL(6,2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) DEFAULT NULL,
    image_path VARCHAR(500) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    guests INT NOT NULL,
    allergies TEXT DEFAULT NULL,
    status ENUM('confirmed', 'cancelled') NOT NULL DEFAULT 'confirmed',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Données initiales : horaires par défaut (CDC: mardi-dimanche, lundi fermé)
INSERT INTO opening_hours (day_of_week, lunch_start, lunch_end, dinner_start, dinner_end, is_closed) VALUES
(1, NULL, NULL, NULL, NULL, 1),
(2, '12:00', '14:00', '19:00', '21:00', 0),
(3, '12:00', '14:00', '19:00', '21:00', 0),
(4, '12:00', '14:00', '19:00', '21:00', 0),
(5, '12:00', '14:00', '19:00', '21:00', 0),
(6, '12:00', '14:00', '19:00', '21:00', 0),
(7, '12:00', '14:00', '19:00', '21:00', 0);

-- Capacité par défaut
INSERT INTO restaurant_settings (max_capacity) VALUES (50);

-- Catégories par défaut
INSERT INTO categories (name, sort_order) VALUES
('Entrées', 1),
('Plats', 2),
('Desserts', 3);

-- Compte admin par défaut (password: Admin123!)
INSERT INTO users (email, password, firstname, lastname, role) VALUES
('admin@quai-antique.fr', '$2y$10$A0RsdHXrvWr577TSaWWpYeTt403JbGjmFWpJQr0l7ZH7vwA.lrrLm', 'Admin', 'Restaurant', 'admin');

-- Plats de démonstration
INSERT INTO dishes (category_id, title, description, price) VALUES
(1, 'Terrine de foie gras maison', 'Servie avec son chutney de figues et toasts briochés', 18.00),
(1, 'Soupe à l''oignon gratinée', 'Recette traditionnelle savoyarde au Beaufort', 12.00),
(1, 'Salade de chèvre chaud', 'Mesclun, noix, miel et crottin de Chavignol', 14.00),
(2, 'Filet de féra du Lac Léman', 'Beurre blanc aux herbes, légumes de saison', 26.00),
(2, 'Carré d''agneau en croûte d''herbes', 'Jus corsé, gratin dauphinois', 32.00),
(2, 'Fondue savoyarde', 'Beaufort, Comté, Emmental — pour 2 personnes', 28.00),
(2, 'Diots de Savoie au vin blanc', 'Accompagnés de crozets au Beaufort', 22.00),
(3, 'Tarte aux myrtilles', 'Myrtilles fraîches de montagne, crème fouettée', 10.00),
(3, 'Crème brûlée à la chartreuse', 'Infusion de Chartreuse verte', 11.00),
(3, 'Gâteau de Savoie', 'Recette originale, coulis de fruits rouges', 9.00);

-- Menus de démonstration
INSERT INTO menus (title, description, price) VALUES
('Menu Découverte', 'Entrée + Plat\nou\nPlat + Dessert', 29.00),
('Menu Savoyard', 'Entrée + Plat + Dessert', 42.00),
('Menu Dégustation', 'Amuse-bouche + Entrée + Poisson + Viande + Fromage + Dessert', 65.00);
