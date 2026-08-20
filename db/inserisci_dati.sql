-- SoberUp - Popolamento dati di esempio

USE soberup_db;

-- UTENTI
-- Password per TUTTI gli utenti di test: Password123!
-- Hash generato con bcrypt (compatibile con password_hash()/password_verify() di PHP)
INSERT INTO utente (nome, cognome, email, username, password, codice_amico, is_admin, sesso, data_nascita, peso, altezza, attivo) VALUES
('Admin', 'SoberUp', 'admin@soberup.it', 'admin', '$2b$10$xj8YS0Qqoh1SfARJ6vPJk.76FjaD2C6IJhEQ6mDSnLZK6Jv3OjMSK', 'ADM001', 1, 'M', '1990-01-01', 75.00, 178.00, 1),
('Marco', 'Rossi', 'marco.rossi@studenti.it', 'marcorossi', '$2b$10$xj8YS0Qqoh1SfARJ6vPJk.76FjaD2C6IJhEQ6mDSnLZK6Jv3OjMSK', 'MR7K2A', 0, 'M', '2003-05-14', 78.00, 180.00, 1),
('Giulia', 'Bianchi', 'giulia.bianchi@studenti.it', 'giuliabianchi', '$2b$10$xj8YS0Qqoh1SfARJ6vPJk.76FjaD2C6IJhEQ6mDSnLZK6Jv3OjMSK', 'GB9X4C', 0, 'F', '2002-11-30', 58.00, 165.00, 1),
('Luca', 'Verdi', 'luca.verdi@studenti.it', 'lucaverdi', '$2b$10$xj8YS0Qqoh1SfARJ6vPJk.76FjaD2C6IJhEQ6mDSnLZK6Jv3OjMSK', 'LV3P8Z', 0, 'M', '2001-03-22', 82.00, 183.00, 1);

-- CATEGORIE
INSERT INTO categoria (nomecategoria) VALUES
('Birra'),
('Vino'),
('Superalcolici'),
('Cocktail'),
('Aperitivo');

-- DRINK
-- categoria: 1=Birra, 2=Vino, 3=Superalcolici, 4=Cocktail, 5=Aperitivo
INSERT INTO drink (nomedrink, categoria, gradazione, volume_standard, immagine, descrizione) VALUES
('Birra chiara', 1, 5.00, 330, 'birra_chiara.jpg', 'Birra lager classica, gusto leggero e beverino.'),
('Birra IPA', 1, 6.50, 330, 'birra_ipa.jpg', 'India Pale Ale, più amara e alcolica di una lager.'),
('Vino rosso', 2, 13.00, 150, 'vino_rosso.jpg', 'Calice di vino rosso da tavola.'),
('Prosecco', 2, 11.00, 125, 'prosecco.jpg', 'Vino bianco frizzante, tipico da aperitivo.'),
('Vodka liscia', 3, 40.00, 40, 'vodka.jpg', 'Shot di vodka, 40 ml, gradazione elevata.'),
('Rum', 3, 38.00, 40, 'rum.jpg', 'Shot di rum, 40 ml.'),
('Whisky', 3, 40.00, 40, 'whisky.jpg', 'Shot di whisky, 40 ml.'),
('Spritz', 5, 8.00, 200, 'spritz.jpg', 'Aperitivo a base di Aperol/Select, prosecco e soda.'),
('Gin Tonic', 4, 15.00, 250, 'gintonic.jpg', 'Cocktail a base di gin e acqua tonica.'),
('Mojito', 4, 12.00, 250, 'mojito.jpg', 'Cocktail a base di rum, lime, menta e soda.');

-- ARTICOLI (contenuti informativi)
INSERT INTO articolo (titoloarticolo, testoarticolo, dataarticolo, admin) VALUES
('Come funziona il calcolo del tasso alcolemico', 'SoberUp stima il tuo tasso alcolemico usando la formula di Widmark, uno standard scientifico riconosciuto. Ricorda: si tratta sempre di una stima, non di una misurazione certa come un etilometro.', '2026-01-10', 1),
('Numeri utili per tornare a casa in sicurezza', 'Se il semaforo è rosso, non guidare. Ecco alcuni numeri utili: taxi del campus, servizi NCC convenzionati, numero verde per emergenze.', '2026-01-15', 1);

-- AMICIZIE
-- stato: in_attesa / accettata
INSERT INTO amicizia (utente1, utente2, stato) VALUES
(2, 3, 'accettata'),
(2, 4, 'in_attesa');

-- SESSIONI DI CONSUMO (dati di esempio/test)

INSERT INTO serata (utente, datainizio, datafine) VALUES
(2, '2026-07-23 21:00:00', NULL);

INSERT INTO serata_ha_drink (serata, drink, orario, volume) VALUES
(1, 1, '2026-07-23 21:15:00', 330),
(1, 8, '2026-07-23 22:00:00', 200);

INSERT INTO serata (utente, datainizio, datafine) VALUES
(3, '2026-07-20 20:30:00', '2026-07-21 01:00:00');

INSERT INTO serata_ha_drink (serata, drink, orario, volume) VALUES
(2, 4, '2026-07-20 20:45:00', 125),
(2, 9, '2026-07-20 22:30:00', 250);