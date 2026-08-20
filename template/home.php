<div class="text-end mb-3">
    <a href="storicoSerate.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-clock-history"></i> Storico Serate</a>
</div>

<div class="text-center">
    <p class="lead">Stima il tuo tasso alcolemico e scopri se sei in grado di guidare.</p>

    <?php if ($templateParams["bac"] !== null): ?>
        <?php
            // Disuguaglianza stretta perché se il bac è 0.0, con != avremmo un falso negativo
            $bac = $templateParams["bac"];
            if ($bac > LIMITE_LEGALE_BAC) {
                $statoSemaforo = "rosso";
            } elseif ($bac > LIMITE_LEGALE_BAC - 0.1) {
                $statoSemaforo = "giallo";
            } else {
                $statoSemaforo = "verde";
            }
        ?>

        <div class="semaforo">
            <div class="semaforo-luce rosso<?php echo $statoSemaforo == "rosso" ? " attiva" : ""; ?>"></div>
            <div class="semaforo-luce giallo<?php echo $statoSemaforo == "giallo" ? " attiva" : ""; ?>"></div>
            <div class="semaforo-luce verde<?php echo $statoSemaforo == "verde" ? " attiva" : ""; ?>"></div>
        </div>

        <p class="display-6 mt-3 mb-1"><?php echo number_format($bac, 2); ?> g/L</p>

        <?php if ($bac <= LIMITE_LEGALE_BAC): ?>
            <p class="<?php echo $statoSemaforo == "giallo" ? "stato-attenzione" : "stato-puoi-guidare"; ?>">
                <i class="bi bi-circle-fill"></i> <strong>Puoi guidare</strong>
                <?php if ($statoSemaforo == "giallo"): ?>
                    <br><span class="small">ma sei vicino al limite, presta attenzione</span>
                <?php endif; ?>
            </p>
        <?php else: ?>
            <p class="stato-non-puoi-guidare"><i class="bi bi-circle-fill"></i> <strong>Non puoi guidare</strong></p>
        <?php endif; ?>

        <a href="serata.php?idserata=<?php echo $templateParams["idserata"]; ?>">Vedi il grafico</a>
    <?php else: ?>
        <p class="text-muted">Nessuna serata attiva al momento.</p>
        <a href="catalogo-drink.php" class="btn btn-primary">Inizia una serata</a>
    <?php endif; ?>

    <?php if ($templateParams["articolo"]): ?>
        <div class="box-articolo mx-auto mt-4" style="max-width: 640px;">
            <strong><?php echo $templateParams["articolo"]["titoloarticolo"]; ?></strong>
            <p class="mb-0"><?php echo $templateParams["articolo"]["testoarticolo"]; ?></p>
        </div>
    <?php endif; ?>
