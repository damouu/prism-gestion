                             -- DROPS
DROP TABLE IF EXISTS `resa_materiel`;
DROP TABLE IF EXISTS `groupe`;
DROP TABLE IF EXISTS `resa`;
DROP TABLE IF EXISTS `reservation`;
DROP TABLE IF EXISTS `professeur`;
DROP TABLE IF EXISTS `etudiant`;
DROP TABLE IF EXISTS `materiel`;
DROP TABLE IF EXISTS `type`;


CREATE TABLE IF NOT EXISTS `type` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nom` varchar(128) NOT NULL,
    PRIMARY KEY (`id`)
)Engine=InnoDB DEFAULT CHARSET=`utf8`;

CREATE TABLE IF NOT EXISTS `materiel` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `constructeur` varchar(128) NOT NULL,
    `modele` varchar(128) NOT NULL,
`reference` varchar(20),
    `type` INT NOT NULL,
    `created_at` date NOT NULL,
    `deleted_at` date DEFAULT NULL,
    FOREIGN KEY (`type`) REFERENCES `type`(`id`),
    PRIMARY KEY (`id`)
)Engine=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `personnel` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nom` varchar(128) NOT NULL,
    `prenom` varchar(128) NOT NULL,
    `mail` varchar(255) NOT NULL,
    `telephone` varchar(20) NOT NULL,
    `status` enum('etudiant','professeur') NOT NULL,
    PRIMARY KEY (`id`)
)Engine=InnoDB DEFAULT Charset=utf8;

CREATE TABLE IF NOT EXISTS `departement` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nom` varchar(128) NOT NULL,
    PRIMARY KEY (`id`)
)Engine=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `reservation` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `responsable_projet` INT NOT NULL,
    `departement` INT NOT NULL,
    `matiere` varchar(255) NOT NULL,
    `projet` TEXT DEFAULT NULL,
    `referent` INT DEFAULT NULL,
    `created_at` date NOT NULL,
    `deleted_at` date DEFAULT NULL,
    FOREIGN KEY (`departement`) REFERENCES `departement`(`id`),
    FOREIGN KEY (`responsable_projet`) REFERENCES `personnel`(`id`),
    FOREIGN KEY (`referent`) REFERENCES `personnel`(`id`),
    PRIMARY KEY (`id`)
)Engine=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `resa` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `reservation` INT NOT NULL,
    `depart` DATETIME NOT NULL,
    `retour` DATETIME NOT NULL,
    `observation` TEXT DEFAULT NULL,
    `created_at` date NOT NULL,
    `deleted_at` date DEFAULT NULL,
    FOREIGN KEY (`reservation`) REFERENCES `reservation`(`id`),
    PRIMARY KEY (`id`)
)Engine=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `groupe` (
    `reservation` INT NOT NULL,
    `etudiant` INT NOT NULL,
    FOREIGN KEY (`reservation`) REFERENCES `reservation`(`id`),
    FOREIGN KEY (`etudiant`) REFERENCES `personnel`(`id`),
    PRIMARY KEY (`reservation`, `etudiant`)
)Engine=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `resa_materiel` (
    `id_resa` INT NOT NULL,
    `id_materiel` INT NOT NULL,
    `emprunt` BOOL DEFAULT 0,
    `rendu` BOOL DEFAULT 0,
    `incident` TEXT DEFAULT NULL,
    FOREIGN KEY (`id_resa`) REFERENCES `resa`(`id`),
    FOREIGN KEY (`id_materiel`) REFERENCES `materiel`(`id`),
    PRIMARY KEY (`id_resa`,`id_materiel`)
)Engine=InnoDB DEFAULT CHARSET=utf8;

-- verifier emprunt depart et retour des tables reservation et resa_materiel