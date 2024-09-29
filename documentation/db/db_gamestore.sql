USE dbs13300266;

CREATE TABLE pegi (
  id INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL UNIQUE
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE store (
  id INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  location VARCHAR(100) NOT NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE genre (
  id INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL UNIQUE
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `platform` (
  id INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL UNIQUE
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE app_user (
  id INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  address VARCHAR(255) NOT NULL,
  postcode INT(11) NOT NULL,
  city VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL,
  is_verified TINYINT(1) NOT NULL DEFAULT 0,
  token VARCHAR(50) DEFAULT NULL,
  expires_at DATETIME DEFAULT NULL,
  fk_store_id int(11) UNSIGNED NOT NULL,
  FOREIGN KEY(fk_store_id) REFERENCES store(id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE game (
  id INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  fk_pegi_id INT(11) UNSIGNED NOT NULL,
  FOREIGN KEY(fk_pegi_id) REFERENCES pegi(id) ON DELETE CASCADE ON UPDATE CASCADE
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE image (
  id INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  fk_game_id INT(11) UNSIGNED NOT NULL,
  FOREIGN KEY(fk_game_id) REFERENCES game(id) ON DELETE CASCADE ON UPDATE CASCADE
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE user_order (
  id INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  order_date_time DATETIME,
  status VARCHAR(50) NOT NULL,
  fk_app_user_id INT(11) UNSIGNED NOT NULL,
  fk_store_id INT(11) UNSIGNED NOT NULL,
  FOREIGN KEY(fk_app_user_id) REFERENCES app_user(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY(fk_store_id) REFERENCES store(id) ON DELETE CASCADE ON UPDATE CASCADE
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `user_order` ADD INDEX(`fk_app_user_id`, `fk_store_id`); 

CREATE TABLE game_genre (
  fk_game_id INT(11) UNSIGNED NOT NULL,
  fk_genre_id INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY(fk_game_id, fk_genre_id),
  FOREIGN KEY(fk_game_id) REFERENCES game(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY(fk_genre_id) REFERENCES genre(id) ON DELETE CASCADE ON UPDATE CASCADE
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE game_platform (
  fk_game_id INT(11) UNSIGNED NOT NULL,
  fk_store_id INT(11) UNSIGNED NOT NULL,
  fk_platform_id INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY(fk_game_id, fk_store_id, fk_platform_id),  
  price DECIMAL(10,2) NOT NULL,
  is_new TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
  is_reduced TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  discount_rate DECIMAL(5,2) NOT NULL,
  quantity INT(11) NOT NULL,
  FOREIGN KEY(fk_game_id) REFERENCES game(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY(fk_store_id) REFERENCES store(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY(fk_platform_id) REFERENCES platform(id) ON DELETE CASCADE ON UPDATE CASCADE
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE game_user_order (
  fk_game_id INT(11) UNSIGNED NOT NULL,
  fk_platform_id INT(11) UNSIGNED NOT NULL,
  fk_user_order_id INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY(fk_game_id, fk_platform_id, fk_user_order_id),
  quantity INT(11) NOT NULL,
  price_at_order DECIMAL(10,2) NOT NULL,
  FOREIGN KEY(fk_game_id) REFERENCES game(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY(fk_platform_id) REFERENCES platform(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY(fk_user_order_id) REFERENCES user_order(id) ON DELETE CASCADE ON UPDATE CASCADE
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE email_verification (
  id INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  verification_code INT(6) UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL,
  fk_app_user_id INT(11) UNSIGNED NOT NULL,
  FOREIGN KEY(fk_app_user_id) REFERENCES app_user(id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
