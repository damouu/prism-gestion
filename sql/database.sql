-- DROPS
DROP TABLE IF EXISTS `reservation_exemplaire`;
DROP TABLE IF EXISTS `groupe`;
DROP TABLE IF EXISTS `fiche_resa`;
DROP TABLE IF EXISTS `reservation`;
DROP TABLE IF EXISTS `departement`;
DROP TABLE IF EXISTS `professeur`;
DROP TABLE IF EXISTS `etudiant`;
DROP TABLE IF EXISTS `exemplaire`;
DROP TABLE IF EXISTS `fournisseur`;
DROP TABLE IF EXISTS `materiel`;
DROP TABLE IF EXISTS `type`;



CREATE TABLE IF NOT EXISTS `type` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nom` varchar(128) NOT NULL,
    `couleur` varchar(128) NOT NULL,
    `date_suppression` DATE DEFAULT NULL,
    PRIMARY KEY (`id`)
)Engine=InnoDB DEFAULT CHARSET=`utf8`;

CREATE TABLE IF NOT EXISTS `materiel` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `constructeur` varchar(128) NOT NULL,
    `modele` varchar(128) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `nb_ex` INT NULL,
    `type` INT NOT NULL,
    `date_creation` date NOT NULL,
    `date_modification` date DEFAULT NULL,
    `date_suppression` date DEFAULT NULL,
    FOREIGN KEY (`type`) REFERENCES `type`(`id`),
    PRIMARY KEY (`id`)
)Engine=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `fournisseur` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nom` varchar(255) NOT NULL,
    `adresse` varchar(255) NOT NULL,
    `ville` varchar(128) NOT NULL,
    `code_postal` varchar(10) NOT NULL,
    `site_web` varchar(255) DEFAULT NULL,
    `mail` varchar(128) NOT NULL,
    `tel` varchar(13) NOT NULL,
    `commercial_nom` varchar(128) DEFAULT NULL,
    `commercial_prenom` varchar(128) DEFAULT NULL,
    `commercial_mail` varchar(255) DEFAULT NULL,
    `commercial_tel` varchar(13) DEFAULT NULL,
    `date_creation` date NOT NULL,
    `date_modification` date DEFAULT NULL,
    `date_suppression` date DEFAULT NULL,
    PRIMARY KEY(`id`)
)Engine=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `exemplaire` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `materiel` INT NOT NULL,
    `reference` varchar(20) DEFAULT NULL,
    `fournisseur` INT NOT NULL,
    `prix_ht` DECIMAL(10,2) NOT NULL,
    `prix_ttc` DECIMAL(10,2) NOT NULL,
    `num_serie` varchar(128) NOT NULL,
    `url` TEXT DEFAULT NULL,
    `stockage` varchar(128) NOT NULL,
    `etat` ENUM('disponible','réparation','emprunté','non empruntable'),
    `num_ex` INT NOT NULL,
    `financement` varchar(128) DEFAULT NULL,
    `bon_commande` varchar(128) DEFAULT NULL,
    `immobilisation` varchar(128) DEFAULT NULL,
    `date_achat` date NOT NULL,
    `date_modif` date DEFAULT NULL,
    `date_sortie` date DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`materiel`) REFERENCES `materiel`(`id`),
    FOREIGN KEY (`fournisseur`) REFERENCES `fournisseur`(`id`)
)Engine=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `etudiant` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nom` varchar(128) NOT NULL,
    `prenom` varchar(128) NOT NULL,
    `mail` varchar(255) DEFAULT NULL,
    `telephone` varchar(20) NOT NULL,
    PRIMARY KEY (`id`)
)Engine=InnoDB DEFAULT Charset=utf8;

CREATE TABLE IF NOT EXISTS `professeur` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nom` varchar(128) NOT NULL,
    `prenom` varchar(128) NOT NULL,
    `mail` varchar(255) NOT NULL,
    `telephone` varchar(20) NOT NULL,
    PRIMARY KEY (`id`)
)Engine=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `departement` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nom` varchar(128) NOT NULL,
    `created_at` date NOT NULL,
    `deleted_at` date DEFAULT NULL,
    `updated_at` date DEFAULT NULL,
    PRIMARY KEY (`id`)
)Engine=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `reservation` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `responsable_projet` INT NOT NULL,
    `departement` INT NOT NULL,
    `matiere` varchar(255) NOT NULL,
    `annee` varchar(128) NOT NULL,
    `dep_groupe` varchar(128) NOT NULL,
    `observation` TEXT DEFAULT NULL,
    `created_at` date NOT NULL,
    `updated_at` date DEFAULT NULL,
    `deleted_at` date DEFAULT NULL,
    FOREIGN KEY (`departement`) REFERENCES `departement`(`id`),
    FOREIGN KEY (`responsable_projet`) REFERENCES `professeur`(`id`),
    PRIMARY KEY (`id`)
)Engine=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `fiche_resa` (
    `id` varchar(128) NOT NULL,
    `reservation` INT NOT NULL,
    `date_depart` timestamp NOT NULL,
    `date_retour` timestamp NOT NULL,
    `rendu` BOOL DEFAULT 0,
    `observation` TEXT DEFAULT NULL,
    `created_at` date NOT NULL,
    `updated_at` date DEFAULT NULL,
    `deleted_at` date DEFAULT NULL,
    FOREIGN KEY (`reservation`) REFERENCES `reservation`(`id`),
    PRIMARY KEY (`id`)
)Engine=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `groupe` (
    `reservation` INT NOT NULL,
    `etudiant` INT NOT NULL,
    `referent` BOOL DEFAULT 0,
    FOREIGN KEY (`reservation`) REFERENCES `reservation`(`id`),
    FOREIGN KEY (`etudiant`) REFERENCES `etudiant`(`id`),
    PRIMARY KEY (`reservation`, `etudiant`)
)Engine=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `reservation_exemplaire` (
    `fiche_reservation` varchar(128) NOT NULL,
    `id_exemplaire` INT NOT NULL,
    `emprunt` BOOL DEFAULT 0,
    `rendu` BOOL DEFAULT 0,
    `incident` TEXT DEFAULT NULL,
    FOREIGN KEY (`fiche_reservation`) REFERENCES `fiche_resa`(`id`),
    FOREIGN KEY (`id_exemplaire`) REFERENCES `exemplaire`(`id`),
    PRIMARY KEY (`fiche_reservation`,`id_exemplaire`)
)Engine=InnoDB DEFAULT CHARSET=utf8;

-- verifier emprunt depart et retour des tables reservation et resa_materiel