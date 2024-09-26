INSERT INTO platform (name) VALUES
('PC'), 
('PS4'), 
('PS5'), 
('Xbox One'), 
('Xbox Series X'), 
('Nintendo Switch');


INSERT INTO pegi (name) VALUES
('pegi3'), 
('pegi7'), 
('pegi12'), 
('pegi16'), 
('pegi18');

INSERT INTO genre (name) VALUES
('Action'), 
('Aventure'), 
('Arcade'), 
('Plateau'), 
('FPS'), 
('Hasard'), 
('Pédagogique'), 
('Famille'), 
('Combat'), 
('Roguelike'), 
('Massivement multijoueur'), 
('Plateforme'), 
('Puzzle'), 
('Course'), 
('RPG'), 
('Shooter'), 
('Simulation'), 
('Sports'), 
('Stratégie'), 
('Horreur'), 
('Survival');

INSERT INTO store (location) VALUES
('Nantes'),
('Lille'),
('Bordeaux'),
('Paris'),
('Toulouse');

INSERT INTO game (name, description, fk_pegi_id) VALUES
('Half-Life : Alyx', "Half-Life: Alyx marque le retour, en VR, de Valve dans l'univers de Half-Life. Situé entre les événements de Half-Life et de Half-Life 2, le jeu retrace l'histoire d'un combat impossible contre un groupe d'extraterrestres cruels connu sous le nom du Cartel.", 4),
('Cyberpunk 2077', "Cyberpunk 2077 est un jeu de rôle futuriste et dystopique inspiré du jeu de rôle papier du même nom. Dans un monde devenu indissociable de la cybernétique, l'indépendance des robots humanoïdes pose quelques problèmes à la population.", 5),
('The Last of Us Part II', "The Last of Us Part II est un jeu d'action-aventure en vue à la troisième personne. Le joueur y incarne deux personnages différents évoluant dans un monde post-apocalyptique envahi par des créatures infectées.", 5),
('Animal Crossing : New Horizons', "Animal Crossing : New Horizons est un jeu de simulation de vie sur Switch. Dans cette nouvelle version, votre personnage s'embarque sur une île déserte après avoir acheté un forfait Nook Inc. Votre but est de la rendre la plus accueillante possible.", 1),
('Doom Eternal', "Doom Eternal est un FPS développé par id Software et édité par Bethesda. Suite directe de Doom sorti en 2016, cet épisode nous plonge dans une aventure toujours plus frénétique et brutale, en nous envoyant en enfer pour combattre des hordes de démons.", 5),
('Final Fantasy VII Remake', "Final Fantasy VII Remake est un remake du RPG sorti sur PS1. Revivez l'histoire du jeu original dans cette nouvelle version qui vous permet de revivre les aventures de Cloud, Tifa et Aerith.", 4),
('Ghost of Tsushima', "Ghost of Tsushima est un jeu d'action-aventure en monde ouvert. Le joueur y incarne Jin Sakai, l'un des derniers samouraïs survivants, qui doit lutter pour repousser l'invasion mongole sur l'île de Tsushima.", 5),
('Hades', "Hades est un rogue-like dans lequel vous incarnez le fils de Hadès. Avec l'aide de vos oncles, vous devez tenter de vous échapper des Enfers, en affrontant des hordes de monstres à chaque tentative.", 3),
('Ori and the Will of the Wisps', "Ori and the Will of the Wisps est un jeu de plateforme et d'aventure. Suite du premier opus, ce jeu vous permet de retrouver Ori et Sein dans une nouvelle aventure à la recherche de Ku, un hibou perdu.", 2),
('Persona 5 Royal', "Persona 5 Royal est un jeu de rôle développé et édité par Atlus. Il s'agit d'une version améliorée de Persona 5, incluant de nouveaux éléments de gameplay et une nouvelle histoire.", 4),
('Sekiro : Shadows Die Twice', "Sekiro : Shadows Die Twice est un jeu d'action-aventure en vue à la troisième personne. Le joueur y incarne le loup à un bras, un shinobi qui doit se battre pour sauver son maître et venger sa défaite face au clan Ashina.", 5),
('Streets of Rage 4', "Streets of Rage 4 est un beat'em all développé par Dotemu. Suite de la série des années 90, ce nouvel opus vous permet de retrouver Axel, Blaze et Adam dans une nouvelle aventure pour sauver la ville de Wood Oak City.", 3),
('The Legend of Zelda : Breath of the Wild', "The Legend of Zelda : Breath of the Wild est un jeu d'action-aventure en monde ouvert. Le joueur y incarne Link, qui doit sauver la princesse Zelda et le royaume d'Hyrule de l'emprise de Ganon.", 3),
('The Witcher 3 : Wild Hunt', "The Witcher 3 : Wild Hunt est un jeu de rôle en monde ouvert. Le joueur y incarne Geralt de Riv, un sorceleur qui doit retrouver Ciri, sa protégée, pour la protéger de la Chasse Sauvage.", 5),
("Uncharted 4 : A Thief's End", "Uncharted 4 : A Thief's End est un jeu d'action-aventure en vue à la troisième personne. Le joueur y incarne Nathan Drake, un aventurier à la recherche du trésor perdu du pirate Henry Avery.", 4);

INSERT INTO game_genre (fk_game_id, fk_genre_id) VALUES
(1, 16), (1, 2),
(2, 15), (2, 16), (2, 9), (2, 2),
(3, 1), (3, 2), (3, 21), (3, 20), (3, 16),
(4, 2), (4, 17),
(5, 5), (5, 12), (5, 2),
(6, 1), (6, 15),
(7, 1), (7, 2), (7, 15), (7, 9),
(8, 10), (8, 1), (8, 15), (8, 9), (8, 16),
(9, 16), (9, 9),
(10, 2), (10, 15),
(11, 2), (11, 1), (11, 15),
(12, 1), (12, 2),
(13, 16),
(14, 1), (14, 2), (14, 15), (14, 9),
(15, 1), (15, 2), (15, 16);

INSERT INTO game_platform (fk_game_id, fk_platform_id, fk_store_id, price, is_reduced, discount_rate, quantity) VALUES
(1, 1, 1, 58.99, 0, 0, 0, 5), (1, 1, 2, 58.99, 0, 0, 0, 4), (1, 1, 3, 58.99, 0, 0, 0, 1), (1, 1, 4, 58.99, 0, 0, 0, 0), (1, 1, 5, 58.99, 0, 0, 0, 3), 
(2, 3, 1, 70.00, 0, 0, 0, 5), (2, 3, 2, 70.00, 0, 0, 0, 2), (2, 3, 3, 70.00, 0, 0, 0, 5), (2, 3, 4, 70.00, 0, 0, 0, 4), (2, 3, 5, 70.00, 0, 0, 0, 9),
(2, 5, 1, 70.00, 0, 1, 0.6, 10), (2, 5, 2, 70.00, 0, 1, 0.6, 2), (2, 5, 3, 70.00, 0, 1, 0.6, 3), (2, 5, 4, 70.00, 0, 1, 0.6, 5), (2, 5, 5, 70.00, 0, 1, 0.6, 8),
(2, 1, 1, 60.00, 0, 1, 0.6, 2), (2, 1, 2, 60.00, 0, 1, 0.6, 8), (2, 1, 3, 60.00, 0, 1, 0.6, 5), (2, 1, 4, 60.00, 0, 1, 0.6, 4), (2, 1, 5, 60.00, 0, 1, 0.6, 9),
(3, 1, 1, 29.99, 0, 0, 0, 4), (3, 1, 2, 29.99, 0, 0, 0, 1), (3, 1, 3, 29.99, 0, 0, 0, 1), (3, 1, 4, 29.99, 0, 1, 0.2, 8), (3, 1, 5, 29.99, 0, 0, 0, 0), 
(4, 6, 1, 54.99, 0, 1, 0.1, 4), (4, 6, 2, 54.99, 0, 1, 0.1, 5), (4, 6, 3, 54.99, 0, 1, 0.1, 5), (4, 6, 4, 54.99, 0, 1, 0.1, 7), (4, 6, 5, 54.99, 0, 1, 0.1, 1), 
(5, 1, 1, 40.00, 0, 0, 0, 8), (5, 1, 2, 40.00, 0, 0, 0, 8), (5, 1, 3, 40.00, 0, 0, 0, 8), (5, 1, 4, 40.00, 0, 0, 0, 8), (5, 1, 5, 40.00, 0, 0, 0, 8), 
(5, 6, 1, 60.00, 0, 0, 0, 9), (5, 6, 2, 60.00, 0, 0, 0, 8), (5, 6, 3, 60.00, 0, 0, 0, 5), (5, 6, 4, 60.00, 0, 0, 0, 4), (5, 6, 5, 60.00, 0, 0, 0, 3), 
(5, 5, 1, 40.00, 0, 1, 0.7, 26), (5, 5, 2, 40.00, 0, 1, 0.7, 9), (5, 5, 3, 40.00, 0, 1, 0.7, 8), (5, 5, 4, 40.00, 0, 1, 0.7, 1), (5, 5, 5, 40.00, 0, 1, 0.7, 2),
(6, 2, 1, 70.00, 0, 0, 0, 1), (6, 2, 2, 70.00, 0, 0, 0, 0), (6, 2, 3, 70.00, 0, 0, 0, 5), (6, 2, 4, 70.00, 0, 0, 0, 7), (6, 2, 5, 70.00, 0, 0, 0, 8), 
(6, 1, 1, 80.00, 0, 0, 0, 8), (6, 1, 2, 80.00, 0, 0, 0, 8), (6, 1, 3, 80.00, 0, 0, 0, 8), (6, 1, 4, 80.00, 0, 0, 0, 0), (6, 1, 5, 80.00, 0, 0, 0, 7), 
(7, 1, 1, 39.99, 0, 0, 0, 2), (7, 1, 2, 39.99, 0, 0, 0, 1), (7, 1, 3, 39.99, 0, 0, 0, 8), (7, 1, 4, 39.99, 0, 0, 0, 45), (7, 1, 5, 39.99, 0, 0, 0, 8), 
(8, 1, 1, 25.99, 0, 0, 0, 3), (8, 1, 2, 25.99, 0, 0, 0, 0), (8, 1, 3, 25.99, 0, 0, 0, 9), (8, 1, 4, 25.99, 0, 0, 0, 2), (8, 1, 5, 25.99, 0, 0, 0, 9), 
(9, 1, 1, 35.00, 1, 0, 0, 5), (9, 1, 2, 35.00, 1, 0, 0, 9), (9, 1, 3, 35.00, 1, 0, 0, 8), (9, 1, 4, 35.00, 1, 0, 0, 3), (9, 1, 5, 35.00, 1, 1, 0.2, 2), 
(9, 4, 1, 30.00, 1, 0, 0, 4), (9, 4, 2, 30.00, 1, 0, 0, 5), (9, 4, 3, 30.00, 1, 1, 0.1, 6), (9, 4, 4, 30.00, 1, 0, 0, 2), (9, 4, 5, 30.00, 1, 0, 0, 8), 
(10, 1, 1, 60.00, 1, 1, 0.6, 10), (10, 1, 2, 60.00, 1, 1, 0.6, 6), (10, 1, 3, 60.00, 1, 1, 0.6, 5), (10, 1, 4, 60.00, 1, 1, 0.6, 4), (10, 1, 5, 60.00, 1, 1, 0.6, 0), 
(10, 6, 1, 47.99, 1, 0, 0, 4), (10, 6, 2, 47.99, 1, 0, 0, 7), (10, 6, 3, 47.99, 1, 0, 0, 5), (10, 6, 4, 47.99, 1, 0, 0, 0), (10, 6, 5, 47.99, 1, 0, 0, 8), 
(11, 4, 1, 27.99, 1, 0, 0, 0), (11, 4, 2, 27.99, 1, 0, 0, 8), (11, 4, 3, 27.99, 1, 0, 0, 9), (11, 4, 4, 27.99, 1, 0, 0, 20), (11, 4, 5, 27.99, 1, 0, 0, 10), 
(12, 1, 1, 10.00, 1, 0, 0, 8), (12, 1, 2, 10.00, 1, 0, 0, 2), (12, 1, 3, 10.00, 1, 0, 0, 5), (12, 1, 4, 10.00, 1, 0, 0, 5), (12, 1, 5, 10.00, 1, 0, 0, 6), 
(12, 6, 1, 19.99, 1, 0, 0, 5), (12, 6, 2, 19.99, 1, 0, 0, 7), (12, 6, 3, 19.99, 1, 0, 0, 8), (12, 6, 4, 19.99, 1, 0, 0, 0), (12, 6, 5, 19.99, 1, 0, 0, 8), 
(13, 6, 1, 69.99, 1, 1, 0.3, 4), (13, 6, 2, 69.99, 1, 1, 0.3, 1), (13, 6, 3, 69.99, 1, 1, 0.3, 0), (13, 6, 4, 69.99, 1, 1, 0.3, 5), (13, 6, 5, 69.99, 1, 1, 0.3, 1), 
(14, 1, 1, 49.99, 1, 0, 0, 5), (14, 1, 2, 49.99, 1, 0, 0, 4), (14, 1, 3, 49.99, 1, 0, 0, 2), (14, 1, 4, 49.99, 1, 0, 0, 8), (14, 1, 5, 49.99, 1, 0, 0, 8), 
(14, 6, 1, 25.99, 1, 0, 0, 5), (14, 6, 2, 25.99, 1, 0, 0, 5), (14, 6, 3, 25.99, 1, 0, 0, 5), (14, 6, 4, 25.99, 1, 0, 0, 4), (14, 6, 5, 25.99, 1, 0, 0, 8),
(14, 2, 1, 62.99, 1, 1, 0.2, 2), (14, 2, 2, 69.99, 1, 1, 0.3, 5), (14, 2, 3, 69.99, 1, 1, 0.4, 0), (14, 2, 4, 69.99, 1, 1, 0.2, 2), (14, 2, 5, 69.99, 1, 1, 0.1, 20), 
(14, 5, 1, 29.99, 1, 0, 0, 5), (14, 5, 2, 29.99, 1, 0, 0, 8), (14, 5, 3, 29.99, 1, 0, 0, 7), (14, 5, 4, 29.99, 1, 0, 0, 2), (14, 5, 5, 29.99, 1, 0, 0, 6), 
(15, 2, 1, 45.99, 1, 0, 0, 10), (15, 2, 2, 45.99, 1, 0, 0, 9), (15, 2, 3, 45.99, 1, 0, 0, 1), (15, 2, 4, 45.99, 1, 0, 0, 5), (15, 2, 5, 45.99, 1, 0, 0, 15);

INSERT INTO image (name, fk_game_id) VALUES
('spotlight-alyx.jpg', 1), ('presentation-alyx.jpg', 1), ('carousel-1-alyx.jpg', 1), ('carousel-2-alyx.jpg', 1), ('carousel-3-alyx.jpg', 1), ('carousel-4-alyx.jpg', 1), ('carousel-5-alyx.jpg', 1),
('spotlight-cyberpunk.jpg', 2), ('presentation-cyberpunk.png', 2), ('carousel-1-cyberpunk.jpg', 2), ('carousel-2-cyberpunk.jpg', 2), ('carousel-3-cyberpunk.jpg', 2), 
('spotlight-lastofus.jpg', 3), ('presentation-lastofus.png', 3), ('carousel-1-lastofus.jpg', 3),
('spotlight-animalcrossing.jpg', 4), ('presentation-animalcrossing.jpg', 4), ('carousel-1-animalcrossing.jpg', 4), ('carousel-2-animalcrossing.webp', 4),
('spotlight-doom.jpg', 5), ('presentation-doom.jpg', 5), ('carousel-1-doom.jpg', 5), ('carousel-2-doom.jpg', 5), ('carousel-3-doom.jpg', 5),
('spotlight-finalfantasy.jpg', 6), ('presentation-finalfantasy.jpg', 6), ('carousel-1-finalfantaisy.jpg', 6), 
('spotlight-ghost.jpg', 7), ('presentation-ghost.jpg', 7), ('carousel-1-ghost.jpg', 7),
('spotlight-hades.jpg', 8), ('presentation-hades.jpg', 8), ('carousel-1-hades.jpg', 8),
('spotlight-ori.jpg', 9), ('presentation-ori.jpg', 9), ('carousel-1-ori.jpg', 9),
('spotlight-persona.jpg', 10), ('presentation-persona.jpg', 10), ('carousel-1-persona.jpg', 10),
('spotlight-sekiro.jpg', 11), ('presentation-sekiro.jpg', 11), ('carousel-1-sekiro.jpg', 11), ('carousel-2-sekiro.jpg', 11),
('spotlight-streets.jpg', 12), ('presentation-streets.jpg', 12), ('carousel-1-streets.jpg', 12),
('spotlight-zelda.jpg', 13), ('presentation-zelda.jpg', 13), ('carousel-1-zelda.jpg', 13),
('spotlight-witcher.jpg', 14), ('presentation-witcher.jpg', 14), ('carousel-1-witcher.jpg', 14),
('spotlight-uncharted4.jpg', 15), ('presentation-uncharted.jpg', 15), ('carousel-1-uncharted4.jpg', 15), ('carousel-2-uncharted4.jpg', 15);