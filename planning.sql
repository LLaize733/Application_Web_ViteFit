####### On créer a base de données 'planning' ####### 
CREATE DATABASE planning;

####### Dans cette base, on créer les tables 'cours' et 'utilisateurs' ####### 
USE planning;

CREATE TABLE cours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sport VARCHAR(20) NOT NULL,
    date_cours DATE NOT NULL,
    places_disponibles INT NOT NULL,
    complet BOOLEAN DEFAULT FALSE
);

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    cours_id INT,
    FOREIGN KEY (cours_id) REFERENCES cours(id)
);

####### On créer des systèmes de mise à jours des tables après un ajout, une modification ou une supression d'inscription #######
DELIMITER //
CREATE TRIGGER after_insert_utilisateurs
AFTER INSERT ON utilisateurs
FOR EACH ROW
BEGIN
    UPDATE cours
    SET places_disponibles = places_disponibles - 1
    WHERE id = NEW.cours_id;
END;
//
DELIMITER ;


DELIMITER //
CREATE TRIGGER after_update_utilisateurs
AFTER UPDATE ON utilisateurs
FOR EACH ROW
BEGIN
    IF OLD.cours_id IS NOT NULL THEN
        UPDATE cours
        SET places_disponibles = places_disponibles + 1
        WHERE id = OLD.cours_id;
    END IF;
    
    IF NEW.cours_id IS NOT NULL THEN
        UPDATE cours
        SET places_disponibles = places_disponibles - 1
        WHERE id = NEW.cours_id;
    END IF;
END;
//
DELIMITER ;


DELIMITER //
CREATE TRIGGER after_delete_utilisateurs
AFTER DELETE ON utilisateurs
FOR EACH ROW
BEGIN
    IF OLD.cours_id IS NOT NULL THEN
        UPDATE cours
        SET places_disponibles = places_disponibles + 1
        WHERE id = OLD.cours_id;
    END IF;
END;
//
DELIMITER ;

####### On code en dure les dates de chaque cours #######
#######  Cours 1 (Lundi 9h - 10h) ####### 
INSERT INTO cours (sport, date_cours, places_disponibles) VALUES
('Cycling', '2024-01-01', 15), ('Cycling', '2024-01-08', 15), ('Cycling', '2024-01-15', 15), ('Cycling', '2024-01-22', 15), ('Cycling', '2024-01-29', 15),
('Cycling', '2024-02-05', 15), ('Cycling', '2024-02-12', 15), ('Cycling', '2024-02-19', 15), ('Cycling', '2024-02-26', 15), ('Cycling', '2024-03-04', 15),
('Cycling', '2024-03-11', 15), ('Cycling', '2024-03-18', 15), ('Cycling', '2024-03-25', 15), ('Cycling', '2024-04-01', 15), ('Cycling', '2024-04-08', 15),
('Cycling', '2024-04-15', 15), ('Cycling', '2024-04-22', 15), ('Cycling', '2024-04-29', 15);

#######  Cours 2 (Mercredi 18h30 - 19h30) ####### 
INSERT INTO cours (sport, date_cours, places_disponibles) VALUES
('Bodytraining', '2024-01-03', 15), ('Bodytraining', '2024-01-10', 15), ('Bodytraining', '2024-01-17', 15), ('Bodytraining', '2024-01-24', 15), ('Bodytraining', '2024-01-31', 15),
('Bodytraining', '2024-02-07', 15), ('Bodytraining', '2024-02-14', 15), ('Bodytraining', '2024-02-21', 15), ('Bodytraining', '2024-02-28', 15), ('Bodytraining', '2024-03-06', 15),
('Bodytraining', '2024-03-13', 15), ('Bodytraining', '2024-03-20', 15), ('Bodytraining', '2024-03-27', 15), ('Bodytraining', '2024-04-03', 15), ('Bodytraining', '2024-04-10', 15),
('Bodytraining', '2024-04-17', 15), ('Bodytraining', '2024-04-24', 15);

####### Cours 3 (Vendredi 12h30 - 13h30) ####### 
INSERT INTO cours (sport, date_cours, places_disponibles) VALUES
('Zumba', '2024-01-05', 15), ('Zumba', '2024-01-12', 15), ('Zumba', '2024-01-19', 15), ('Zumba', '2024-01-26', 15), ('Zumba', '2024-02-02', 15),
('Zumba', '2024-02-09', 15), ('Zumba', '2024-02-16', 15), ('Zumba', '2024-02-23', 15), ('Zumba', '2024-03-01', 15), ('Zumba', '2024-03-08', 15),
('Zumba', '2024-03-15', 15), ('Zumba', '2024-03-22', 15), ('Zumba', '2024-03-29', 15), ('Zumba', '2024-04-05', 15), ('Zumba', '2024-04-12', 15),
('Zumba', '2024-04-19', 15), ('Zumba', '2024-04-26', 15);
