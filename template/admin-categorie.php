<?php require 'template/messaggio.php'; ?>
<?php require 'template/errori.php'; ?>

<ul class="list-group mb-4">
    <?php foreach ($templateParams["categorie"] as $c): ?>
        <li class="list-group-item">
            <form method="POST" action="admin-categorie.php" class="d-flex align-items-center gap-2">
                <input type="hidden" name="idcategoria" value="<?php echo $c["idcategoria"]; ?>">
                <input type="text" name="nomecategoria" class="form-control form-control-sm" value="<?php echo $c["nomecategoria"]; ?>" aria-label="Nome categoria" required>
                <button type="submit" name="modifica" value="1" class="btn btn-sm btn-outline-primary" title="Salva"><i class="bi bi-check-lg"></i></button>
                <button type="submit" name="elimina" value="1" class="btn btn-sm btn-outline-danger" title="Elimina" onclick="return confirm('Eliminare questa categoria?');"><i class="bi bi-trash"></i></button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>

<h2 class="h5">Aggiungi categoria</h2>
<form method="POST" action="admin-categorie.php" class="d-flex gap-2">
    <input type="text" name="nomecategoria" class="form-control" placeholder="Nome nuova categoria" aria-label="Nome nuova categoria" required>
    <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Aggiungi</button>
</form>
