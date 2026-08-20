<?php require 'template/messaggio.php'; ?>
<?php require 'template/errori.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-12 col-md text-center text-md-start">
        <p class="mb-0"><strong><?php echo $templateParams["utente"]["nome"] . " " . $templateParams["utente"]["cognome"]; ?></strong></p>
        <span class="text-muted small">@<?php echo $templateParams["utente"]["username"]; ?></span>
    </div>
    <div class="col-12 col-md-auto text-center text-md-end mt-3 mt-md-0">
        <p class="mb-0"><strong>Il tuo codice amico</strong></p>
        <span class="badge badge-orario rounded-pill fs-6"><?php echo $templateParams["utente"]["codice_amico"]; ?></span>
    </div>
</div>

<?php if (!empty($templateParams["richieste"])): ?>
<hr class="mb-4">
<h2 class="h5 mb-3">Richieste ricevute</h2>
<ul class="list-group mb-4">
    <?php foreach ($templateParams["richieste"] as $r): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong><?php echo $r["nome"] . " " . $r["cognome"]; ?></strong>
                <span class="text-muted small">(@<?php echo $r["username"]; ?>)</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <form method="POST" action="amici.php">
                    <input type="hidden" name="idamicizia" value="<?php echo $r["idamicizia"]; ?>">
                    <button type="submit" name="accetta" value="1" class="btn btn-sm btn-outline-success" title="Accetta"><i class="bi bi-check-lg"></i></button>
                </form>
                <form method="POST" action="amici.php" onsubmit="return confirm('Rifiutare questa richiesta di amicizia?');">
                    <input type="hidden" name="idamicizia" value="<?php echo $r["idamicizia"]; ?>">
                    <button type="submit" name="rifiuta" value="1" class="btn btn-sm btn-outline-danger" title="Rifiuta"><i class="bi bi-x-lg"></i></button>
                </form>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<hr class="mb-4">
<h2 class="h5 mb-3">I tuoi amici</h2>
<?php if (empty($templateParams["amici"])): ?>
    <p class="text-muted">Non hai ancora amici. Aggiungine uno con il codice amico qui sotto.</p>
<?php else: ?>
<ul class="list-group mb-4">
    <?php foreach ($templateParams["amici"] as $a): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong><?php echo $a["nome"] . " " . $a["cognome"]; ?></strong>
                <span class="text-muted small">(@<?php echo $a["username"]; ?>)</span>
                <br>
                <?php if ($a["bac"] !== null): ?>
                    <?php
                        $bac = $a["bac"];
                        if ($bac > LIMITE_LEGALE_BAC) {
                            $statoSemaforo = "rosso";
                        } elseif ($bac > LIMITE_LEGALE_BAC - 0.1) {
                            $statoSemaforo = "giallo";
                        } else {
                            $statoSemaforo = "verde";
                        }
                        $statoClasse = $bac > LIMITE_LEGALE_BAC ? "stato-non-puoi-guidare" : ($statoSemaforo == "giallo" ? "stato-attenzione" : "stato-puoi-guidare");
                    ?>
                    <span class="<?php echo $statoClasse; ?>"><i class="bi bi-circle-fill"></i> <?php echo number_format($bac, 2); ?> g/L</span>
                <?php else: ?>
                    <span class="text-muted small">Nessuna serata attiva</span>
                <?php endif; ?>
            </div>
            <form method="POST" action="amici.php" onsubmit="return confirm('Rimuovere questo amico dalla tua lista?');">
                <input type="hidden" name="idamicizia" value="<?php echo $a["idamicizia"]; ?>">
                <button type="submit" name="rimuovi" value="1" class="btn btn-sm btn-outline-danger" title="Rimuovi"><i class="bi bi-x-lg"></i></button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<hr class="mb-4">
<h2 class="h5 mb-3">Aggiungi un amico</h2>
<form method="POST" action="amici.php" class="card shadow-sm p-4">
    <div class="mb-3">
        <label for="codice_amico" class="form-label">Codice amico</label>
        <input id="codice_amico" name="codice_amico" type="text" class="form-control text-uppercase" maxlength="10" placeholder="Es. AB12CD">
    </div>
    <button type="submit" name="invia" value="1" class="btn btn-primary">Invia richiesta</button>
</form>