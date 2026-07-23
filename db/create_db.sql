-- ============================================
-- SoberUp - Schema Database
-- Stile coerente con l'esempio del corso (mysqli)
-- ============================================

CREATE DATABASE IF NOT EXISTS soberup_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE soberup_db;

-- ============================================
-- Tabella UTENTE
-- ============================================
CREATE TABLE utente (
    idutente        INT AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR(50)     NOT NULL,
    cognome         VARCHAR(50)     NOT NULL,
    email           VARCHAR(100)    NOT NULL UNIQUE,
    username        VARCHAR(50)     NOT NULL UNIQUE,
    password        VARCHAR(255)    NOT NULL,          -- conterrà l'hash, mai la password in chiaro
    is_admin        TINYINT(1)      NOT NULL DEFAULT 0,
    sesso           ENUM('M','F')   NOT NULL,
    data_nascita    DATE            NOT NULL,
    peso            DECIMAL(5,2)    NOT NULL,           -- kg, es. 72.50
    altezza         DECIMAL(5,2)    NOT NULL,           -- cm, es. 178.00
    attivo          TINYINT(1)      NOT NULL DEFAULT 1, -- per disattivazione account da Admin
    data_creazione  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- Tabella CATEGORIA (categorie di drink)
-- ============================================
CREATE TABLE categoria (
    idcategoria     INT AUTO_INCREMENT PRIMARY KEY,
    nomecategoria   VARCHAR(50)     NOT NULL UNIQUE
);

-- ============================================
-- Tabella DRINK
-- ============================================
CREATE TABLE drink (
    iddrink         INT AUTO_INCREMENT PRIMARY KEY,
    nomedrink       VARCHAR(100)    NOT NULL,
    categoria       INT             NOT NULL,
    gradazione      DECIMAL(4,2)    NOT NULL,           -- %vol, es. 12.50
    volume          INT             NOT NULL,           -- ml, es. 330
    immagine        VARCHAR(255)    NULL,
    descrizione     TEXT            NULL,
    FOREIGN KEY (categoria) REFERENCES categoria(idcategoria)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ============================================
-- Tabella SERATA (serata di consumo)
-- ============================================
CREATE TABLE serata (
    idserata        INT AUTO_INCREMENT PRIMARY KEY,
    utente          INT             NOT NULL,
    datainizio      DATETIME        NOT NULL,
    datafine        DATETIME        NULL,               -- NULL finché la serata è "aperta"
    FOREIGN KEY (utente) REFERENCES utente(idutente)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- ============================================
-- Tabella SERATA_HA_DRINK (drink bevuti in una serata, con orario)
-- ============================================
CREATE TABLE serata_ha_drink (
    idseratadrink   INT AUTO_INCREMENT PRIMARY KEY,
    serata          INT             NOT NULL,
    drink           INT             NOT NULL,
    orario          DATETIME        NOT NULL,
    quantita        INT             NOT NULL DEFAULT 1,
    FOREIGN KEY (serata) REFERENCES serata(idserata)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (drink) REFERENCES drink(iddrink)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ============================================
-- Tabella ARTICOLO (contenuti informativi gestiti dall'Admin)
-- ============================================
CREATE TABLE articolo (
    idarticolo          INT AUTO_INCREMENT PRIMARY KEY,
    titoloarticolo       VARCHAR(150)    NOT NULL,
    testoarticolo         TEXT            NOT NULL,
    immaginearticolo      VARCHAR(255)    NULL,
    dataarticolo          DATE            NOT NULL,
    admin                 INT             NOT NULL,
    FOREIGN KEY (admin) REFERENCES utente(idutente)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ============================================
-- Tabella AMICIZIA (lista amici tra utenti)
-- Nota: lo stato "può guidare / non può guidare" dell'amico NON si salva qui,
-- si calcola al volo interrogando la serata attiva (datafine IS NULL)
-- e i relativi drink dell'amico.
-- ============================================
CREATE TABLE amicizia (
    idamicizia      INT AUTO_INCREMENT PRIMARY KEY,
    utente1         INT             NOT NULL,           -- chi ha inviato la richiesta
    utente2         INT             NOT NULL,           -- chi la riceve
    stato           ENUM('in_attesa','accettata') NOT NULL DEFAULT 'in_attesa',
    data_richiesta  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utente1) REFERENCES utente(idutente)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (utente2) REFERENCES utente(idutente)
        ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_amicizia (utente1, utente2)        -- evita richieste duplicate
);
