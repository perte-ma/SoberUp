<?php require 'template/messaggio.php'; ?>
<?php require 'template/errori.php'; ?>
<div class="row mb-4 align-items-center">
    <div class="col-12 col-md-auto text-center text-md-start">
        <!-- Slot immagine profilo -->
        <div class="rounded-circle bg-secondary mx-auto mx-md-0" style="width: 96px; height: 96px;"></div>
    </div>
    <div class="col-12 col-md mt-3 mt-md-0">
        <div class="mb-4">
            <p><strong>Username:</strong> <?php echo $templateParams['utente']['username']; ?></p>
            <p><strong>Email:</strong> <?php echo $templateParams['utente']['email']; ?></p>
        </div>
    </div>
    <div class="col-12 col-md-auto text-md-end">
        <div class="mb-4">
            <p><strong>Codice Amico:</strong> <?php echo $templateParams['utente']['codice_amico']; ?></p>
            <a href="amici.php">I tuoi amici</a>
        </div>
    </div>
</div>
<hr class="mb-4">
<form action="" method="POST">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input id="nome" name="nome" type="text" class="form-control" required value="<?php echo $templateParams["utente"]["nome"];?>">
    </div>
    <div class="mb-3">
        <label for="cognome" class="form-label">Cognome</label>
        <input id="cognome" name="cognome" type="text" class="form-control" required value="<?php echo $templateParams["utente"]["cognome"];?>">
    </div>
    <fieldset class="mb-3">
        <legend class="form-label">Sesso</legend>
        <div class="form-check form-check-inline">
            <input id="maschio" name="sesso" type="radio" class="form-check-input" required value="M" <?php echo ($templateParams["utente"]["sesso"]=='M') ? "checked" : ""; ?>>
            <label for="maschio" class="form-check-label">M</label>
        </div>
        <div class="form-check form-check-inline">
            <input id="femmina" name="sesso" type="radio" class="form-check-input" required value="F" <?php echo ($templateParams["utente"]["sesso"]=='F') ? "checked" : ""; ?>>
            <label for="femmina" class="form-check-label">F</label>
        </div>
    </fieldset>
    <div class="mb-3">
        <label for="data-nascita" class="form-label">Data di nascita</label>
        <input id="data-nascita" name="data_nascita" type="date" class="form-control" required value="<?php echo $templateParams["utente"]["data_nascita"];?>">
    </div>
    <div class="mb-3">
        <label for="peso" class="form-label">Peso (kg)</label>
        <input id="peso" name="peso" type="number" class="form-control" step="0.1" min="1" required value="<?php echo $templateParams["utente"]["peso"];?>">
    </div>
    <div class="mb-3">
        <label for="altezza" class="form-label">Altezza (cm)</label>
        <input id="altezza" name="altezza" type="number" class="form-control" step="0.1" min="1" required value="<?php echo $templateParams["utente"]["altezza"];?>">
    </div>
    <button type="submit" class="btn btn-primary">Salva le modifiche</button>
</form>
<div class="text-center mt-4">
    <a href="logout.php" class="btn btn-outline-danger">Esci</a>
</div>