<?php $serate = $templateParams["serate"]; ?>

<?php if (empty($serate)): ?>

    <div class="text-center">
        <p class="text-muted">Non hai ancora serate concluse nel tuo storico.</p>
    </div>

<?php else: ?>

    <div class="accordion" id="accordionStorico">
        <?php foreach ($serate as $i => $s): ?>
            <?php
                $inizio = new DateTime($s['datainizio']);
                $fine = new DateTime($s['datafine']);
                $durata = $inizio->diff($fine);
                $numDrink = count($s['drinks']);
                $collapseId = "collapseSerata" . $s['idserata'];
            ?>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button<?php echo $i == 0 ? '' : ' collapsed'; ?>" type="button"
                        data-bs-toggle="collapse" data-bs-target="#<?php echo $collapseId; ?>"
                        aria-expanded="<?php echo $i == 0 ? 'true' : 'false'; ?>"
                        aria-controls="<?php echo $collapseId; ?>">
                        <div class="d-flex justify-content-between w-100 me-3 flex-wrap">
                            <span><?php echo $inizio->format('d/m/Y H:i'); ?></span>
                            <span class="text-muted small">
                                Durata <?php echo $durata->format('%hh %imin'); ?>
                                -
                                <?php echo $numDrink; ?> drink
                            </span>
                        </div>
                    </button>
                </h2>
                <div id="<?php echo $collapseId; ?>"
                    class="accordion-collapse collapse<?php echo $i == 0 ? ' show' : ''; ?>"
                    data-bs-parent="#accordionStorico">
                    <div class="accordion-body">

                        <?php if ($numDrink == 0): ?>
                            <p class="text-muted mb-0">Nessun drink registrato per questa serata.</p>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($s['drinks'] as $d): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <?php echo htmlspecialchars($d['nomedrink']); ?>
                                            <span class="text-muted small">
                                                (<?php echo $d['volume']; ?> ml, <?php echo $d['gradazione']; ?>%)
                                            </span>
                                        </span>
                                        <span class="badge badge-orario rounded-pill">
                                            <?php echo (new DateTime($d['orario']))->format('H:i'); ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <p class="small text-muted mt-3 mb-0">
                            Conclusa il <?php echo $fine->format('d/m/Y H:i'); ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>