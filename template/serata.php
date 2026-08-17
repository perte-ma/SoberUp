<?php require 'template/messaggio.php'; ?>
<?php require 'template/errori.php'; ?>

<?php if (!$templateParams["haSerataAperta"]): ?>

    <div class="text-center">
        <p class="text-muted mb-4">Non hai nessuna serata attiva al momento.</p>
        <a href="catalogo-drink.php" class="btn btn-primary">Vai al Catalogo Drink</a>
    </div>

<?php else: ?>
    <?php
        $serata = $templateParams["serata"];
        $drinks = $templateParams["drinks"];
        $bac = $templateParams["bac"];
        $puoGuidare = $templateParams["puoGuidare"];
        $oraFineStimata = $templateParams["oraFineStimata"];
        if ($bac > LIMITE_LEGALE_BAC) {
            $statoSemaforo = "rosso";
        } elseif ($bac > LIMITE_LEGALE_BAC - 0.1) {
            $statoSemaforo = "giallo";
        } else {
            $statoSemaforo = "verde";
        }
    ?>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="row">
                <div class="col-12 col-md-4 mb-4">
                    <div class="semaforo">
                        <div class="semaforo-luce rosso<?php echo $statoSemaforo == "rosso" ? " attiva" : ""; ?>"></div>
                        <div class="semaforo-luce giallo<?php echo $statoSemaforo == "giallo" ? " attiva" : ""; ?>"></div>
                        <div class="semaforo-luce verde<?php echo $statoSemaforo == "verde" ? " attiva" : ""; ?>"></div>
                    </div>
                    <div class="text-center mt-3">
                        <p class="display-6 mb-1"><?php echo number_format($bac, 2); ?> g/L</p>
                        <?php if ($puoGuidare): ?>
                            <p class="mb-1 stato-puoi-guidare">
                                🟢 <strong>Puoi guidare</strong>
                                <?php if ($statoSemaforo == "giallo"): ?>
                                    <br><span class="small">ma sei vicino al limite, presta attenzione</span>
                                <?php endif; ?>
                            </p>
                        <?php else: ?>
                            <p class="mb-1 stato-non-puoi-guidare">🔴 <strong>Non puoi guidare</strong></p>
                        <?php endif; ?>
                        <p class="small text-muted mb-0">
                            Torni sotto il limite legale intorno alle <?php echo date('H:i', $oraFineStimata); ?>
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-8 mb-4">
                    <div class="card shadow-sm p-3 h-100">
                        <h2 class="h5">Andamento della serata</h2>
                        <canvas id="serataChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <h2 class="h5 mb-0">Drink bevuti</h2>
                    <a href="catalogo-drink.php" class="btn btn-sm btn-primary">+ Aggiungi drink</a>
                </div>
                <?php if (empty($drinks)): ?>
                    <p class="text-muted mb-0">Nessun drink registrato per questa serata.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach ($drinks as $d): ?>
                            <li class="list-group-item">
                                <form method="POST" action="serata.php" class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <input type="hidden" name="idseratadrink" value="<?php echo $d["idseratadrink"]; ?>">

                                    <div class="me-2">
                                        <strong><?php echo htmlspecialchars($d["nomedrink"]); ?></strong>
                                        <span class="text-muted small">(<?php echo $d["gradazione"]; ?>%)</span>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <input type="number" class="form-control form-control-sm" style="width: 85px" name="volume" value="<?php echo $d["volume"]; ?>" min="1" step="1" required>
                                        <span class="small text-muted">ml</span>
                                        <input type="time" class="form-control form-control-sm" style="width: 105px" name="orario" value="<?php echo (new DateTime($d["orario"]))->format('H:i'); ?>" required>
                                        <button type="submit" name="modifica" value="1" class="btn btn-sm btn-outline-primary" title="Salva modifiche">💾</button>
                                        <button type="submit" name="elimina" value="1" class="btn btn-sm btn-outline-danger" title="Elimina" onclick="return confirm('Eliminare questo drink dalla serata?');">🗑</button>
                                    </div>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="disclaimer mx-auto" style="max-width: 640px;">
                Il valore calcolato è una stima statistica basata sulla formula di Watson. Non sostituisce un
                etilometro. In caso di dubbio, non guidare.
            </div>
        </div>
    </div>
    <script>
        const currentSerataId = <?php echo $serata["idserata"]; ?>;
        window.addEventListener("load", function () {
            renderSerataChart(currentSerataId);
        });
    </script>
<?php endif; ?>
