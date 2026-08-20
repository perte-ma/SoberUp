<?php require 'template/messaggio.php'; ?>
<?php require 'template/errori.php'; ?>

<ul class="list-group mb-4">
    <?php foreach ($templateParams["drinks"] as $d): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong><?php echo $d["nomedrink"]; ?></strong>
                <span class="text-muted small">(<?php echo $d["nomecategoria"]; ?>, <?php echo $d["gradazione"]; ?>%, <?php echo $d["volume_standard"]; ?> ml)</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="admin-drink.php?modifica=<?php echo $d["iddrink"]; ?>" class="btn btn-sm btn-outline-primary" title="Modifica"><i class="bi bi-pencil"></i></a>
                <form method="POST" action="admin-drink.php" onsubmit="return confirm('Eliminare questo drink?');">
                    <input type="hidden" name="iddrink" value="<?php echo $d["iddrink"]; ?>">
                    <button type="submit" name="elimina" value="1" class="btn btn-sm btn-outline-danger" title="Elimina"><i class="bi bi-trash"></i></button>
                </form>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

<?php $drink = $templateParams["drinkInModifica"]; ?>

<h2 class="h5"><?php echo $drink ? "Modifica drink" : "Aggiungi drink"; ?></h2>
<form method="POST" action="admin-drink.php" enctype="multipart/form-data">
    <?php if ($drink): ?>
        <input type="hidden" name="iddrink" value="<?php echo $drink["iddrink"]; ?>">
        <input type="hidden" name="immagine_attuale" value="<?php echo $drink["immagine"]; ?>">
    <?php endif; ?>

    <div class="mb-3">
        <label for="nomedrink" class="form-label">Nome</label>
        <input id="nomedrink" name="nomedrink" type="text" class="form-control" required value="<?php echo $drink ? $drink["nomedrink"] : ""; ?>">
    </div>
    <div class="mb-3">
        <label for="categoria" class="form-label">Categoria</label>
        <select id="categoria" name="categoria" class="form-select" required>
            <option value="" disabled <?php echo !$drink ? "selected" : ""; ?>>Seleziona una categoria</option>
            <?php foreach ($templateParams["categorie"] as $c): ?>
                <option value="<?php echo $c["idcategoria"]; ?>" <?php echo ($drink && $drink["categoria"] == $c["idcategoria"]) ? "selected" : ""; ?>><?php echo $c["nomecategoria"]; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="gradazione" class="form-label">Gradazione (%)</label>
        <input id="gradazione" name="gradazione" type="number" step="0.1" min="0" class="form-control" required value="<?php echo $drink ? $drink["gradazione"] : ""; ?>">
    </div>
    <div class="mb-3">
        <label for="volume_standard" class="form-label">Volume standard (ml)</label>
        <input id="volume_standard" name="volume_standard" type="number" min="1" class="form-control" required value="<?php echo $drink ? $drink["volume_standard"] : ""; ?>">
    </div>
    <div class="mb-3">
        <label for="descrizione" class="form-label">Descrizione</label>
        <textarea id="descrizione" name="descrizione" class="form-control"><?php echo $drink ? ($drink["descrizione"] ?? '') : ""; ?></textarea>
    </div>
    <div class="mb-3">
        <label for="immagine" class="form-label">Immagine<?php echo $drink ? " (lascia vuoto per non cambiarla)" : ""; ?></label>
        <input id="immagine" name="immagine" type="file" class="form-control">
    </div>

    <?php if ($drink): ?>
        <button type="submit" name="modifica" value="1" class="btn btn-primary">Salva modifiche</button>
    <?php else: ?>
        <button type="submit" class="btn btn-primary">Aggiungi</button>
    <?php endif; ?>
</form>
