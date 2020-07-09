#------------------------------------------------------------
#                        Script MySQL.
#------------------------------------------------------------
#-- creation de la base de donnees si elle n existe pas
CREATE DATABASE IF NOT EXISTS db_ajax_panier_dev;
#-- on precise que l on va utiliser cette datbase pour creer les tables
USE db_ajax_panier_dev;

#------------------------------------------------------------
# Table: PRODUITS
#------------------------------------------------------------

CREATE TABLE product (
    id                  int                 not null Auto_increment,
    name                varchar(255)        not null,
    image               varchar(255)        not null,
    quantity            int                 not null,
    price               decimal(5, 2)       not null,
    created_at          datetime            not null,
    availability        boolean             not null DEFAULT 1,
    CONSTRAINT product_PK PRIMARY KEY (id)
) ENGINE=InnoDB;

#------------------------------------------------------------
# Table: PANIERS
#------------------------------------------------------------

CREATE TABLE paniers (
    paniersId                   int             not null Auto_increment,
    panierUserTemp              varchar(100)    not null,
    created_at                  datetime        not null,
    valided                     boolean         not null DEFAULT 0,
    CONSTRAINT paniers_PK PRIMARY KEY (paniersId)
) ENGINE=InnoDB;

#------------------------------------------------------------
# Table: LIGNE_COMMANDE
#------------------------------------------------------------
CREATE TABLE lignes_commande (
    ligneCmdId                  int             not null Auto_increment,
    paniersId                   int             not null,
    produitsId                  int             not null,
    quantite                    int             not null,
    CONSTRAINT lignes_commande_PK PRIMARY KEY (ligneCmdId),
    FOREIGN KEY (paniersId) REFERENCES paniers(paniersId),
    FOREIGN KEY (produitsId) REFERENCES product(id)
) ENGINE=InnoDB;
