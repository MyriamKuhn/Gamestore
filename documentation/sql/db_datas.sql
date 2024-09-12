INSERT INTO plattform (name) VALUES
('PC'), 
('PS4'), 
('PS5'), 
('Xbox One'), 
('Xbox Series X'), 
('Nintendo Switch');


INSERT INTO pegi (name) VALUES
('pegi3.jpg'), 
('pegi7.jpg'), 
('pegi12.jpg'), 
('pegi16.jpg'), 
('pegi18.jpg'),
('pegi_discrimination.jpg'), 
('pegi_drugs.jpg'), 
('pegi_fear.jpg'), 
('pegi_gambling.jpg'), 
('pegi_language.jpg'), 
('pegi_online.jpg'), 
('pegi_paids.jpg'),
('pegi_parental.jpg'), 
('pegi_sexual.jpg'), 
('pegi_violence.jpg');

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

INSERT INTO game_plattform (fk_game_id, fk_plattform_id, fk_store_id, price, is_reduced, discount_rate) VALUES
(1, 8, 1, 58.99, 0, 0), (1, 8, 2, 58.99, 0, 0), (1, 8, 3, 58.99, 0, 0), (1, 8, 4, 58.99, 0, 0), (1, 8, 5, 58.99, 0, 0), 
(2, 10, 1, 70.00, 0, 0), (2, 10, 2, 70.00, 0, 0), (2, 10, 3, 70.00, 0, 0), (2, 10, 4, 70.00, 0, 0), (2, 10, 5, 70.00, 0, 0),
(2, 12, 1, 70.00, 1, 0.6), (2, 12, 2, 70.00, 1, 0.6), (2, 12, 3, 70.00, 1, 0.6), (2, 12, 4, 70.00, 1, 0.6), (2, 12, 5, 70.00, 1, 0.6),
(2, 8, 1, 60.00, 1, 0.6), (2, 8, 2, 60.00, 1, 0.6), (2, 8, 3, 60.00, 1, 0.6), (2, 8, 4, 60.00, 1, 0.6), (2, 8, 5, 60.00, 1, 0.6),
(3, 8, 1, 29.99, 0, 0), (3, 8, 2, 29.99, 0, 0), (3, 8, 3, 29.99, 0, 0), (3, 8, 4, 29.99, 1, 0.2), (3, 8, 5, 29.99, 0, 0), 
(4, 13, 1, 54.99, 1, 0.1), (4, 13, 2, 54.99, 1, 0.1), (4, 13, 3, 54.99, 1, 0.1), (4, 13, 4, 54.99, 1, 0.1), (4, 13, 5, 54.99, 1, 0.1), 
(5, 8, 1, 40.00, 0, 0), (5, 8, 2, 40.00, 0, 0), (5, 8, 3, 40.00, 0, 0), (5, 8, 4, 40.00, 0, 0), (5, 8, 5, 40.00, 0, 0), 
(5, 13, 1, 60.00, 0, 0), (5, 13, 2, 60.00, 0, 0), (5, 13, 3, 60.00, 0, 0), (5, 13, 4, 60.00, 0, 0), (5, 13, 5, 60.00, 0, 0), 
(5, 12, 1, 40.00, 1, 0.7), (5, 12, 2, 40.00, 1, 0.7), (5, 12, 3, 40.00, 1, 0.7), (5, 12, 4, 40.00, 1, 0.7), (5, 12, 5, 40.00, 1, 0.7),
(6, 9, 1, 70.00, 0, 0), (6, 9, 2, 70.00, 0, 0), (6, 9, 3, 70.00, 0, 0), (6, 9, 4, 70.00, 0, 0), (6, 9, 5, 70.00, 0, 0), 
(6, 8, 1, 80.00, 0, 0), (6, 8, 2, 80.00, 0, 0), (6, 8, 3, 80.00, 0, 0), (6, 8, 4, 80.00, 0, 0), (6, 8, 5, 80.00, 0, 0), 
(7, 8, 1, 39.99, 0, 0), (7, 8, 2, 39.99, 0, 0), (7, 8, 3, 39.99, 0, 0), (7, 8, 4, 39.99, 0, 0), (7, 8, 5, 39.99, 0, 0), 
(8, 8, 1, 25.99, 0, 0), (8, 8, 2, 25.99, 0, 0), (8, 8, 3, 25.99, 0, 0), (8, 8, 4, 25.99, 0, 0), (8, 8, 5, 25.99, 0, 0), 
(9, 8, 1, 35.00, 0, 0), (9, 8, 2, 35.00, 0, 0), (9, 8, 3, 35.00, 0, 0), (9, 8, 4, 35.00, 0, 0), (9, 8, 5, 35.00, 1, 0.2), 
(9, 11, 1, 30.00, 0, 0), (9, 11, 2, 30.00, 0, 0), (9, 11, 3, 30.00, 1, 0.1), (9, 11, 4, 30.00, 0, 0), (9, 11, 5, 30.00, 0, 0), 
(10, 8, 1, 60.00, 1, 0.6), (10, 8, 2, 60.00, 1, 0.6), (10, 8, 3, 60.00, 1, 0.6), (10, 8, 4, 60.00, 1, 0.6), (10, 8, 5, 60.00, 1, 0.6), 
(10, 13, 1, 47.99, 0, 0), (10, 13, 2, 47.99, 0, 0), (10, 13, 3, 47.99, 0, 0), (10, 13, 4, 47.99, 0, 0), (10, 13, 5, 47.99, 0, 0), 
(11, 11, 1, 27.99, 0, 0), (11, 11, 2, 27.99, 0, 0), (11, 11, 3, 27.99, 0, 0), (11, 11, 4, 27.99, 0, 0), (11, 11, 5, 27.99, 0, 0), 
(12, 8, 1, 10.00, 0, 0), (12, 8, 2, 10.00, 0, 0), (12, 8, 3, 10.00, 0, 0), (12, 8, 4, 10.00, 0, 0), (12, 8, 5, 10.00, 0, 0), 
(12, 13, 1, 19.99, 0, 0), (12, 13, 2, 19.99, 0, 0), (12, 13, 3, 19.99, 0, 0), (12, 13, 4, 19.99, 0, 0), (12, 13, 5, 19.99, 0, 0), 
(13, 13, 1, 69.99, 1, 0.3), (13, 13, 2, 69.99, 1, 0.3), (13, 13, 3, 69.99, 1, 0.3), (13, 13, 4, 69.99, 1, 0.3), (13, 13, 5, 69.99, 1, 0.3), 
(14, 8, 1, 49.99, 0, 0), (14, 8, 2, 49.99, 0, 0), (14, 8, 3, 49.99, 0, 0), (14, 8, 4, 49.99, 0, 0), (14, 8, 5, 49.99, 0, 0), 
(14, 13, 1, 25.99, 0, 0), (14, 13, 2, 25.99, 0, 0), (14, 13, 3, 25.99, 0, 0), (14, 13, 4, 25.99, 0, 0), (14, 13, 5, 25.99, 0, 0),
(14, 9, 1, 69.99, 1, 0.2), (14, 9, 2, 69.99, 1, 0.3), (14, 9, 3, 69.99, 1, 0.4), (14, 9, 4, 69.99, 1, 0.2), (14, 9, 5, 69.99, 1, 0.1), 
(14, 12, 1, 29.99, 0, 0), (14, 12, 2, 29.99, 0, 0), (14, 12, 3, 29.99, 0, 0), (14, 12, 4, 29.99, 0, 0), (14, 12, 5, 29.99, 0, 0), 
(15, 9, 1, 45.99, 0, 0), (15, 9, 2, 45.99, 0, 0), (15, 9, 3, 45.99, 0, 0), (15, 9, 4, 45.99, 0, 0), (15, 9, 5, 45.99, 0, 0);

INSERT INTO supply (fk_game_id, fk_plattform_id, fk_store_id, quantity) VALUES
(1, 8, 1, 10), (1, 8, 2, 10), (1, 8, 3, 10), (1, 8, 4, 10), (1, 8, 5, 10), 
(2, 10, 1, 5), (2, 10, 2, 15), (2, 10, 3, 0), (2, 10, 4, 5), (2, 10, 5, 0), 
(2, 12, 1, 3), (2, 12, 2, 3), (2, 12, 3, 3), (2, 12, 4, 3), (2, 12, 5, 3),
(2, 8, 1, 0), (2, 8, 2, 0), (2, 8, 3, 0), (2, 8, 4, 1), (2, 8, 5, 2), 
(3, 8, 1, 10), (3, 8, 2, 10), (3, 8, 3, 10), (3, 8, 4, 10), (3, 8, 5, 10), 
(4, 13, 1, 2), (4, 13, 2, 1), (4, 13, 3, 2), (4, 13, 4, 2), (4, 13, 5, 10),
(5, 8, 1, 10), (5, 8, 2, 10), (5, 8, 3, 8), (5, 8, 4, 9), (5, 8, 5, 0), 
(5, 13, 1, 8), (5, 13, 2, 8), (5, 13, 3, 8), (5, 13, 4, 8), (5, 13, 5, 8), 
(5, 12, 1, 9), (5, 12, 2, 5), (5, 12, 3, 5), (5, 12, 4, 7), (5, 12, 5, 4), 
(6, 9, 1, 7), (6, 9, 2, 7), (6, 9, 3, 7), (6, 9, 4, 7), (6, 9, 5, 7), 
(6, 8, 1, 5), (6, 8, 2, 0), (6, 8, 3, 5), (6, 8, 4, 5), (6, 8, 5, 5), 
(7, 8, 1, 10), (7, 8, 2, 10), (7, 8, 3, 10), (7, 8, 4, 10), (7, 8, 5, 10),
(8, 8, 1, 6), (8, 8, 2, 4), (8, 8, 3, 7), (8, 8, 4, 0), (8, 8, 5, 1), 
(9, 8, 1, 9), (9, 8, 2, 9), (9, 8, 3, 9), (9, 8, 4, 9), (9, 8, 5, 15), 
(9, 11, 1, 1), (9, 11, 2, 5), (9, 11, 3, 14), (9, 11, 4, 1), (9, 11, 5, 1), 
(10, 8, 1, 10), (10, 8, 2, 10), (10, 8, 3, 10), (10, 8, 4, 10), (10, 8, 5, 10),
(10, 13, 1, 8), (10, 13, 2, 8), (10, 13, 3, 5), (10, 13, 4, 7), (10, 13, 5, 8), 
(11, 11, 1, 5), (11, 11, 2, 5), (11, 11, 3, 5), (11, 11, 4, 5), (11, 11, 5, 5), 
(12, 8, 1, 10), (12, 8, 2, 10), (12, 8, 3, 10), (12, 8, 4, 10), (12, 8, 5, 10), 
(12, 13, 1, 10), (12, 13, 2, 10), (12, 13, 3, 10), (12, 13, 4, 10), (12, 13, 5, 10),
(13, 13, 1, 10), (13, 13, 2, 10), (13, 13, 3, 10), (13, 13, 4, 10), (13, 13, 5, 10),
(14, 8, 1, 10), (14, 8, 2, 10), (14, 8, 3, 10), (14, 8, 4, 10), (14, 8, 5, 10),
(14, 13, 1, 9), (14, 13, 2, 9), (14, 13, 3, 9), (14, 13, 4, 9), (14, 13, 5, 9),
(14, 9, 1, 5), (14, 9, 2, 0), (14, 9, 3, 5), (14, 9, 4, 5), (14, 9, 5, 5),
(14, 12, 1, 8), (14, 12, 2, 8), (14, 12, 3, 8), (14, 12, 4, 8), (14, 12, 5, 8),
(15, 9, 1, 10), (15, 9, 2, 10), (15, 9, 3, 10), (15, 9, 4, 10), (15, 9, 5, 10);

