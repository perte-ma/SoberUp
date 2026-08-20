<?php
class DatabaseHelper{
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port){
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    // UTENTE

    // $passwordHash deve arrivare già calcolato con password_hash() dal chiamante
    public function insertUtente($nome, $cognome, $email, $username, $passwordHash, $codiceAmico, $sesso, $dataNascita, $peso, $altezza){
        $query = "INSERT INTO utente (nome, cognome, email, username, password, codice_amico, sesso, data_nascita, peso, altezza) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssssssssdd', $nome, $cognome, $email, $username, $passwordHash, $codiceAmico, $sesso, $dataNascita, $peso, $altezza);
        $stmt->execute();

        return $stmt->insert_id;
    }

    // Controlla se un codice amico esiste già (usato per generarne uno univoco in fase di registrazione)
    public function esisteCodiceAmico($codice){
        $query = "SELECT idutente FROM utente WHERE codice_amico = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $codice);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    // Usata nella pagina Amici: l'utente inserisce il codice di un altro utente per inviargli richiesta
    public function getUtenteByCodiceAmico($codice){
        $query = "SELECT idutente, nome, cognome, username FROM utente WHERE codice_amico = ? AND attivo = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $codice);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }    

    // Usata per il login: prende username O email, ritorna la riga completa (incluso l'hash)
    // La verifica della password va fatta FUORI da questa classe con password_verify()
    public function getUtenteByUsernameOrEmail($usernameOrEmail){
        $query = "SELECT * FROM utente WHERE (username = ? OR email = ?) AND attivo = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function getUtenteById($id){
        $query = "SELECT idutente, nome, cognome, email, username, codice_amico, is_admin, sesso, data_nascita, peso, altezza, attivo, data_creazione FROM utente WHERE idutente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function getAllUtenti(){
        $query = "SELECT idutente, nome, cognome, email, username, codice_amico, is_admin, sesso, data_nascita, peso, altezza, attivo, data_creazione FROM utente ORDER BY cognome, nome";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateUtente($id, $nome, $cognome, $sesso, $dataNascita, $peso, $altezza){
        $query = "UPDATE utente SET nome = ?, cognome = ?, sesso = ?, data_nascita = ?, peso = ?, altezza = ? WHERE idutente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssssddi', $nome, $cognome, $sesso, $dataNascita, $peso, $altezza, $id);

        return $stmt->execute();
    }

    // $newPasswordHash deve arrivare già calcolato con password_hash()
    public function updatePasswordUtente($id, $newPasswordHash){
        $query = "UPDATE utente SET password = ? WHERE idutente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('si', $newPasswordHash, $id);

        return $stmt->execute();
    }

    // Usata dall'Admin per disattivare/riattivare un account (soft delete)
    public function setUtenteAttivo($id, $attivo){
        $query = "UPDATE utente SET attivo = ? WHERE idutente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $attivo, $id);

        return $stmt->execute();
    }

    // Eliminazione definitiva dell'account (richiesta dall'utente stesso, GDPR-friendly)
    // Grazie a ON DELETE CASCADE su serata e amicizia, i dati collegati vengono rimossi automaticamente
    public function deleteUtente($id){
        $query = "DELETE FROM utente WHERE idutente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);

        return $stmt->execute();
    }

    // CATEGORIA

    public function getCategorie(){
        $stmt = $this->db->prepare("SELECT * FROM categoria ORDER BY nomecategoria");
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCategoriaById($id){
        $stmt = $this->db->prepare("SELECT * FROM categoria WHERE idcategoria = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function insertCategoria($nomecategoria){
        $stmt = $this->db->prepare("INSERT INTO categoria (nomecategoria) VALUES (?)");
        $stmt->bind_param('s', $nomecategoria);
        $stmt->execute();

        return $stmt->insert_id;
    }

    public function updateCategoria($id, $nomecategoria){
        $stmt = $this->db->prepare("UPDATE categoria SET nomecategoria = ? WHERE idcategoria = ?");
        $stmt->bind_param('si', $nomecategoria, $id);

        return $stmt->execute();
    }

    public function deleteCategoria($id){
        $stmt = $this->db->prepare("DELETE FROM categoria WHERE idcategoria = ?");
        $stmt->bind_param('i', $id);

        return $stmt->execute();
    }

    // DRINK

    public function getDrinks(){
        $query = "SELECT d.*, c.nomecategoria FROM drink d, categoria c WHERE d.categoria = c.idcategoria ORDER BY d.nomedrink";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDrinkById($id){
        $query = "SELECT d.*, c.nomecategoria FROM drink d, categoria c WHERE d.categoria = c.idcategoria AND d.iddrink = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function getDrinksByCategoria($idcategoria){
        $query = "SELECT d.*, c.nomecategoria FROM drink d, categoria c WHERE d.categoria = c.idcategoria AND d.categoria = ? ORDER BY d.nomedrink";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idcategoria);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insertDrink($nomedrink, $categoria, $gradazione, $volumeStandard, $immagine, $descrizione){
        $query = "INSERT INTO drink (nomedrink, categoria, gradazione, volume_standard, immagine, descrizione) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sidiss', $nomedrink, $categoria, $gradazione, $volumeStandard, $immagine, $descrizione);
        $stmt->execute();

        return $stmt->insert_id;
    }

    public function updateDrink($id, $nomedrink, $categoria, $gradazione, $volumeStandard, $immagine, $descrizione){
        $query = "UPDATE drink SET nomedrink = ?, categoria = ?, gradazione = ?, volume_standard = ?, immagine = ?, descrizione = ? WHERE iddrink = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sidissi', $nomedrink, $categoria, $gradazione, $volumeStandard, $immagine, $descrizione, $id);

        return $stmt->execute();
    }

    public function deleteDrink($id){
        $stmt = $this->db->prepare("DELETE FROM drink WHERE iddrink = ?");
        $stmt->bind_param('i', $id);

        return $stmt->execute();
    }

    // ARTICOLO (contenuti informativi)

    public function getArticoli(){
        $query = "SELECT a.*, u.nome, u.cognome FROM articolo a, utente u WHERE a.admin = u.idutente ORDER BY a.dataarticolo DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getArticoloById($id){
        $query = "SELECT a.*, u.nome, u.cognome FROM articolo a, utente u WHERE a.admin = u.idutente AND a.idarticolo = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function getArticoloByTitolo($titoloarticolo){
        $query = "SELECT a.*, u.nome, u.cognome FROM articolo a, utente u WHERE a.admin = u.idutente AND a.titoloarticolo = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $titoloarticolo);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function insertArticolo($titoloarticolo, $testoarticolo, $dataarticolo, $admin){
        $query = "INSERT INTO articolo (titoloarticolo, testoarticolo, dataarticolo, admin) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssi', $titoloarticolo, $testoarticolo, $dataarticolo, $admin);
        $stmt->execute();

        return $stmt->insert_id;
    }

    public function updateArticolo($id, $titoloarticolo, $testoarticolo){
        $query = "UPDATE articolo SET titoloarticolo = ?, testoarticolo = ? WHERE idarticolo = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssi', $titoloarticolo, $testoarticolo, $id);

        return $stmt->execute();
    }

    public function deleteArticolo($id){
        $stmt = $this->db->prepare("DELETE FROM articolo WHERE idarticolo = ?");
        $stmt->bind_param('i', $id);

        return $stmt->execute();
    }

    // SERATA
    // Apertura/chiusura gestite in automatico dalla logica applicativa (utils/),
    // qui ci sono solo le funzioni di lettura/scrittura pure sul DB.

    // Ritorna la serata aperta dell'utente (datafine IS NULL), o null se non ne ha
    public function getSerataAperta($idutente){
        $query = "SELECT * FROM serata WHERE utente = ? AND datafine IS NULL";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idutente);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function getSerataById($idserata){
        $query = "SELECT * FROM serata WHERE idserata = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idserata);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Storico: solo le serate già chiuse di un utente, dalla più recente
    public function getSerateChiuseByUtente($idutente){
        $query = "SELECT * FROM serata WHERE utente = ? AND datafine IS NOT NULL ORDER BY datainizio DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idutente);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insertSerata($idutente, $datainizio){
        $query = "INSERT INTO serata (utente, datainizio) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('is', $idutente, $datainizio);
        $stmt->execute();

        return $stmt->insert_id;
    }

    public function chiudiSerata($idserata, $datafine){
        $query = "UPDATE serata SET datafine = ? WHERE idserata = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('si', $datafine, $idserata);

        return $stmt->execute();
    }

    // Se l'orario passato e' antecedente all'inizio ufficiale della serata, aggiorna "datainizio"
    // cosi' resta sempre coerente col drink piu' vecchio davvero registrato.
    private function aggiornaInizioSeAntecedente($idserata, $orario){
        $serata = $this->getSerataById($idserata);

        if ($orario < $serata['datainizio']) {
            $stmt = $this->db->prepare("UPDATE serata SET datainizio = ? WHERE idserata = ?");
            $stmt->bind_param('si', $orario, $idserata);
            $stmt->execute();
        }
    }

    public function insertDrinkInSerata($idserata, $iddrink, $orario, $volume){
        $this->aggiornaInizioSeAntecedente($idserata, $orario);

        $query = "INSERT INTO serata_ha_drink (serata, drink, orario, volume) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iisi', $idserata, $iddrink, $orario, $volume);
        $stmt->execute();

        return $stmt->insert_id;
    }

    public function updateDrinkInSerata($idseratadrink, $idserata, $orario, $volume){
        $this->aggiornaInizioSeAntecedente($idserata, $orario);

        $query = "UPDATE serata_ha_drink SET orario = ?, volume = ? WHERE idseratadrink = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sii', $orario, $volume, $idseratadrink);

        return $stmt->execute();
    }

    public function deleteDrinkInSerata($idseratadrink){
        $stmt = $this->db->prepare("DELETE FROM serata_ha_drink WHERE idseratadrink = ?");
        $stmt->bind_param('i', $idseratadrink);

        return $stmt->execute();
    }

    // Tutti i drink di una serata, con i dati del drink (gradazione, volume) necessari al calcolo del BAC
    public function getDrinkDiSerata($idserata){
        $query = "SELECT sd.idseratadrink, sd.orario, sd.volume, d.iddrink, d.nomedrink, d.gradazione, d.volume_standard
                FROM serata_ha_drink sd, drink d
                WHERE sd.drink = d.iddrink AND sd.serata = ?
                ORDER BY sd.orario ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idserata);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Il drink più bevuto dall'utente in tutte le sue serate (per le statistiche dello storico)
    public function getDrinkPreferitoUtente($idutente){
        $query = "SELECT d.nomedrink, COUNT(*) AS conteggio
                FROM serata_ha_drink sd, serata s, drink d
                WHERE sd.serata = s.idserata AND sd.drink = d.iddrink AND s.utente = ?
                GROUP BY d.iddrink
                ORDER BY conteggio DESC
                LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idutente);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Millilitri totali bevuti dall'utente in tutte le sue serate
    public function getMillilitriTotaliUtente($idutente){
        $query = "SELECT SUM(sd.volume) AS millilitri
                FROM serata_ha_drink sd, serata s
                WHERE sd.serata = s.idserata AND s.utente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idutente);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc()['millilitri'];
    }

    public function getAmiciziaTra($idutente1, $idutente2){
        $query = "SELECT * FROM amicizia WHERE (utente1 = ? AND utente2 = ?) OR (utente1 = ? AND utente2 = ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iiii', $idutente1, $idutente2, $idutente2, $idutente1);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function inviaRichiestaAmicizia($idutente1, $idutente2){
        $query = "INSERT INTO amicizia (utente1, utente2, stato) VALUES (?, ?, 'in_attesa')";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $idutente1, $idutente2);

        return $stmt->execute();
    }
    
    public function accettaRichiestaAmicizia($idamicizia){
        $query = "UPDATE amicizia SET stato = 'accettata' WHERE idamicizia = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idamicizia);

        return $stmt->execute();
    }

    public function rimuoviAmicizia($idamicizia){
        $query = "DELETE FROM amicizia WHERE idamicizia = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idamicizia);

        return $stmt->execute();
    }
    public function getRichiesteInAttesa($idutente){
        $query = "SELECT am.idamicizia, u.idutente, u.nome, u.cognome, u.username
                   FROM amicizia am, utente u
                   WHERE am.utente1 = u.idutente AND am.utente2 = ? AND am.stato = 'in_attesa'";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idutente);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAmiciAccettati($idutente){
        $query = "SELECT am.idamicizia, u.idutente, u.nome, u.cognome, u.username, u.sesso, u.data_nascita, u.peso, u.altezza, s.idserata
                   FROM amicizia am
                   JOIN utente u ON (u.idutente = IF(am.utente1 = ?, am.utente2, am.utente1))
                   LEFT JOIN serata s ON (s.utente = u.idutente AND s.datafine IS NULL)
                   WHERE am.stato = 'accettata' AND (am.utente1 = ? OR am.utente2 = ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iii', $idutente, $idutente, $idutente);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>