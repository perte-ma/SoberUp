<?php $categorie = $templateParams["categorie"]; ?>

    <div class="accordion" id="accordionCatalogo">
        <?php foreach ($categorie as $cat):
            $collapseId = "collapseCategoria" . $cat['idcategoria'];
        ?>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapseId; ?>" aria-expanded="false" aria-controls="<?php echo $collapseId; ?>">
                        <?php echo $cat['nomecategoria']; ?>
                        <span class="text-muted small ms-2">(<?php echo count($cat['drinks']); ?> drink)</span>

                    </button>
                </h2>
                <div id="<?php echo $collapseId; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionCatalogo">
                    <div class="accordion-body">
                        <?php if (empty($cat['drinks'])): ?>
                            <p class="text-muted mb-0">Nessun drink in questa categoria.</p>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($cat["drinks"] as $d): ?>
                                    <li class="list-group-item">
                                        <a href="dettaglio-drink.php?iddrink=<?php echo $d['iddrink']; ?>" class="d-flex align-items-center gap-2 text-decoration-none">
                                            <img src="upload/<?php echo $d['immagine'] ?? 'placeholder.png'; ?>" alt="" width="40" height="40" style="object-fit: cover;" class="rounded">
                                            <span><?php echo $d['nomedrink']; ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>
    </div>