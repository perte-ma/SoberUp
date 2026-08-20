<?php require 'template/messaggio.php'; ?>
<?php require 'template/errori.php'; ?>

<ul class="list-group mb-4">
    <?php foreach ($templateParams["articoli"] as $a): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong><?php echo $a["titoloarticolo"]; ?></strong>
                <span class="text-muted small">(<?php echo (new DateTime($a["dataarticolo"]))->format('d/m/Y'); ?>, di <?php echo $a["nome"]; ?> <?php echo $a["cognome"]; ?>)</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="admin-articoli.php?modifica=<?php echo $a["idarticolo"]; ?>" class="btn btn-sm btn-outline-primary" title="Modifica"><i class="bi bi-pencil"></i></a>
                <form method="POST" action="admin-articoli.php" onsubmit="return confirm('Eliminare questo articolo?');">
                    <input type="hidden" name="idarticolo" value="<?php echo $a["idarticolo"]; ?>">
                    <button type="submit" name="elimina" value="1" class="btn btn-sm btn-outline-danger" title="Elimina"><i class="bi bi-trash"></i></button>
                </form>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

<?php $articolo = $templateParams["articoloInModifica"]; ?>

<h2 class="h5"><?php echo $articolo ? "Modifica articolo" : "Aggiungi articolo"; ?></h2>
<form method="POST" action="admin-articoli.php">
    <?php if ($articolo): ?>
        <input type="hidden" name="idarticolo" value="<?php echo $articolo["idarticolo"]; ?>">
    <?php endif; ?>

    <div class="mb-3">
        <label for="titoloarticolo" class="form-label">Titolo</label>
        <input id="titoloarticolo" name="titoloarticolo" type="text" class="form-control" required value="<?php echo $articolo ? $articolo["titoloarticolo"] : ""; ?>">
    </div>
    <div class="mb-3">
        <label for="testoarticolo" class="form-label">Testo</label>
        <textarea id="testoarticolo" name="testoarticolo" class="form-control" rows="5" required><?php echo $articolo ? $articolo["testoarticolo"] : ""; ?></textarea>
    </div>

    <?php if ($articolo): ?>
        <button type="submit" name="modifica" value="1" class="btn btn-primary">Salva modifiche</button>
    <?php else: ?>
        <button type="submit" class="btn btn-primary">Aggiungi</button>
    <?php endif; ?>
</form>
