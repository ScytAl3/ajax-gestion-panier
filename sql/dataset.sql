#-- utilisation de la database pour créer le jeu de donnees
USE db_ajax_panier_dev;

#-----------------------------------------------------------
#                     JEU DE DONNEES
#-----------------------------------------------------------

#-----------------------------------------------------------
# Table: Produits - Data
#-----------------------------------------------------------
INSERT INTO
    product(`name`, `image`, `quantity`, `price`, `created_at`)
VALUES
    ('Aerodynamic Knife', 'https://picsum.photos/400/400/?83654', 99, 59.90, now()),
    ('Mediocre Paper Coat', 'https://picsum.photos/400/400/?21604', 99,  55.25, now()),
    ('Ergonomic Wool Coat', 'https://picsum.photos/400/400/?11969', 99, 25.85, now()),
    ('Ergonomic Wool Clock', 'https://picsum.photos/400/400/?19112', 99, 75.65, now()),
    ('Awesome Wooden Lamp', 'https://picsum.photos/400/400/?74277', 99, 45.55, now()),
    ('Ergonomic Copper Gloves', 'https://picsum.photos/400/400/?92834', 99, 48.95, now()),
    ('Rustic Cotton Hat', 'https://picsum.photos/400/400/?62745', 99, 84.25, now()),
	('Durable Silk Computer', 'https://picsum.photos/400/400/?84685', 99, 54.25, now()),
	('Small Steel Computer', 'https://picsum.photos/400/400/?35408', 99, 47.75, now()),
	('Heavy Duty Steel Wallet', 'https://picsum.photos/400/400/?33246', 99, 39.90, now());
