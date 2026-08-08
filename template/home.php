<div class="text-center">
    <p class="lead">Stima il tuo tasso alcolemico e scopri se sei in grado di guidare.</p>

    <?php if ($templateParams["idserata"]): ?>
        <canvas id="serataChart"></canvas>
    <?php else: ?>
        <p class="text-muted">Nessuna serata attiva al momento.</p>
    <?php endif; ?>

    <div class="disclaimer mx-auto" style="max-width: 640px;">
        Il valore calcolato è una stima statistica basata su formule scientifiche approssimate
        (formula di Widmark). Non sostituisce un etilometro. In caso di dubbio, non guidare.
    </div>
</div>

<?php if ($templateParams["idserata"]): ?>
<script>
    const currentSerataId = <?php echo $templateParams["idserata"]; ?>;
    // window "load" aspetta che TUTTO sia caricato, incluso utils/drawChart.js
    // (che nel documento sta dopo questo blocco, in fondo a template/base.php)
    window.addEventListener("load", function () {
        renderSerataChart(currentSerataId);
    });
</script>
<?php endif; ?>