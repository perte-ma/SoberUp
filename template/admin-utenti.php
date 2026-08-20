<?php require 'template/messaggio.php'; ?>
<?php require 'template/errori.php'; ?>

<ul class="list-group">
    <?php foreach ($templateParams["utenti"] as $u): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong><?php echo $u["nome"] . " " . $u["cognome"]; ?></strong>
                <span class="text-muted small">(<?php echo $u["username"]; ?>, <?php echo $u["email"]; ?>)</span>
                <?php if ($u["is_admin"] == 1): ?>
                    <span class="badge badge-orario rounded-pill">Admin</span>
                <?php endif; ?>
                <?php if ($u["attivo"] == 0): ?>
                    <span class="badge bg-secondary rounded-pill">Disattivato</span>
                <?php endif; ?>
            </div>
            <div class="d-flex align-items-center gap-2">
                <form method="POST" action="admin-utenti.php">
                    <input type="hidden" name="idutente" value="<?php echo $u["idutente"]; ?>">
                    <?php if ($u["attivo"] == 1): ?>
                        <button type="submit" name="disattiva" value="1" class="btn btn-sm btn-outline-warning" title="Disattiva"><i class="bi bi-slash-circle"></i></button>
                    <?php else: ?>
                        <button type="submit" name="attiva" value="1" class="btn btn-sm btn-outline-success" title="Riattiva"><i class="bi bi-check-circle"></i></button>
                    <?php endif; ?>
                </form>
                <form method="POST" action="admin-utenti.php" onsubmit="return confirm('Eliminare questo utente?');">
                    <input type="hidden" name="idutente" value="<?php echo $u["idutente"]; ?>">
                    <button type="submit" name="elimina" value="1" class="btn btn-sm btn-outline-danger" title="Elimina"><i class="bi bi-trash"></i></button>
                </form>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
