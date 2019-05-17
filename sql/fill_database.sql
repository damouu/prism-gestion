INSERT INTO `type` (`nom`) VALUES ('photo');
INSERT INTO `type` (`nom`) VALUES ('Son');
INSERT INTO `type` (`nom`) VALUES ('Eclairage');
INSERT INTO `type` (`nom`) VALUES ('Video');


INSERT INTO `materiel` (`constructeur`,`modele`,`description`,`nb_ex`,`type`,`date_creation`) VALUES ('Canon','EOS X5', 'appareil photo classique', 3, 1,'2019-04-16');
INSERT INTO `materiel` (`constructeur`,`modele`,`description`,`nb_ex`,`type`,`date_creation`) VALUES ('Canon','EOS X5', 'appareil photo classique', 2, 1,'2019-04-16');
INSERT INTO `materiel` (`constructeur`,`modele`,`description`,`nb_ex`,`type`,`date_creation`) VALUES ('Canon','EOS X5', 'appareil photo classique', 1, 1,'2019-04-16');
INSERT INTO `materiel` (`constructeur`,`modele`,`description`,`nb_ex`,`type`,`date_creation`) VALUES ('Canon','EOS X5', 'appareil photo classique', 0, 1,'2019-04-16');
INSERT INTO `materiel` (`constructeur`,`modele`,`description`,`nb_ex`,`type`,`date_creation`) VALUES ('Canon','EOS X5', 'appareil photo classique', 0, 1,'2019-04-16');
INSERT INTO `materiel` (`constructeur`,`modele`,`description`,`nb_ex`,`type`,`date_creation`) VALUES ('Canon','EOS X5', 'appareil photo classique', 0, 1,'2019-04-16');
INSERT INTO `materiel` (`constructeur`,`modele`,`description`,`nb_ex`,`type`,`date_creation`) VALUES ('Canon','EOS X5', 'appareil photo classique', 0, 1,'2019-04-16');
INSERT INTO `materiel` (`constructeur`,`modele`,`description`,`nb_ex`,`type`,`date_creation`) VALUES ('Canon','EOS X5', 'appareil photo classique', 0, 1,'2019-04-16');


INSERT INTO `fournisseur` (`nom`,`adresse`,`ville`,`code_postal`,`site_web`,`mail`,`tel`,`commercial_nom`,`commercial_prenom`,`commercial_mail`,`commercial_tel`,`date_creation`) VALUES ('KingJouet','1 passage du schtroumpf','Nancy','54000','http://www.google.fr','test@gmail.com','0476649999','commercialNom','commercialPrenom','commercial@gmail.com','0476777777','2019-04-29');
INSERT INTO `fournisseur` (`nom`,`adresse`,`ville`,`code_postal`,`site_web`,`mail`,`tel`,`date_creation`) VALUES ('Tralalalouette','1 passage du Chibisuke','Nancy','54000','http://www.google.fr','test@gmail.com','0476649999','2019-04-29');

INSERT INTO `exemplaire` (`materiel`,`reference`,`prix_ttc`,`prix_ht`,`fournisseur`,`num_serie`,`stockage`,`url`,`etat`,`num_ex`,`financement`,`bon_commande`,`immobilisation`,`date_achat`) VALUES (1,'ESOSHAJSD',200,200,1,'AZEYI738-3739','IUT','http://www.google.fr','disponible',1,'test','345678AZ','rtyuioiuytr','2019-04-18');
INSERT INTO `exemplaire` (`materiel`,`reference`,`prix_ttc`,`prix_ht`,`fournisseur`,`num_serie`,`stockage`,`url`,`etat`,`num_ex`,`financement`,`bon_commande`,`immobilisation`,`date_achat`) VALUES (1,'ESOSHAJSD',200,200,2,'AZEYI738-3739','701','http://www.google.fr','réparation',2,'test','345678AZ','rtyuioiuytr','2019-04-18');
INSERT INTO `exemplaire` (`materiel`,`reference`,`prix_ttc`,`prix_ht`,`fournisseur`,`num_serie`,`stockage`,`url`,`etat`,`num_ex`,`financement`,`bon_commande`,`immobilisation`,`date_achat`) VALUES (1,'ESOSHAJSD',200,200,1,'AZEYI738-3739','chez moi','http://www.google.fr','emprunté',3,'test','345678AZ','rtyuioiuytr','2019-04-18');
INSERT INTO `exemplaire` (`materiel`,`reference`,`prix_ttc`,`prix_ht`,`fournisseur`,`num_serie`,`stockage`,`url`,`etat`,`num_ex`,`financement`,`bon_commande`,`immobilisation`,`date_achat`) VALUES (2,'ESOSHAJSD',200,200,2,'AZEYI738-3739','magasin','http://www.google.fr','disponible',1,'test','345678AZ','rtyuioiuytr','2019-04-18');
INSERT INTO `exemplaire` (`materiel`,`reference`,`prix_ttc`,`prix_ht`,`fournisseur`,`num_serie`,`stockage`,`url`,`etat`,`num_ex`,`financement`,`bon_commande`,`immobilisation`,`date_achat`) VALUES (2,'ESOSHAJSD',200,200,1,'AZEYI738-3739','nulle part','http://www.google.fr','disponible',2,'test','345678AZ','rtyuioiuytr','2019-04-18');
INSERT INTO `exemplaire` (`materiel`,`reference`,`prix_ttc`,`prix_ht`,`fournisseur`,`num_serie`,`stockage`,`url`,`etat`,`num_ex`,`financement`,`bon_commande`,`immobilisation`,`date_achat`) VALUES (3,'ESOSHAJSD',200,200,1,'AZEYI738-3739','fort fort lointain','http://www.google.fr','non empruntable',1,'test','345678AZ','rtyuioiuytr','2019-04-18');

