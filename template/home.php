<div class="text-center">
    <p class="lead">Stima il tuo tasso alcolemico e scopri se sei in grado di guidare.</p>

    <?php if ($templateParams["bac"] !== null): ?> //Disuguaglianza Stretta perche se il bac è 0.0 != null darebbe false
        <p class="display-6"><?php echo number_format($templateParams["bac"], 2); ?> g/L</p>

        <?php if ($templateParams["bac"] <= LIMITE_LEGALE_BAC): ?>
            <p class="stato-puoi-guidare">🟢 <strong>Puoi guidare</strong></p>
        <?php else: ?>
            <p class="stato-non-puoi-guidare">🔴 <strong>Non puoi guidare</strong></p>
        <?php endif; ?>

        <a href="la-tua-serata.php?idserata=<?php echo $templateParams["idserata"]; ?>">Vedi il grafico</a>
    <?php else: ?>
        <p class="text-muted">Nessuna serata attiva al momento.</p>
        <a href="catalogo-drink.php" class="btn btn-primary">Inizia una serata</a>
    <?php endif; ?>

    <div class="disclaimer mx-auto mt-4" style="max-width: 640px;">
        Il valore calcolato è una stima statistica basata su formule scientifiche approssimate
        (formula di Widmark). Non sostituisce un etilometro. In caso di dubbio, non guidare.
    </div>
</div>
