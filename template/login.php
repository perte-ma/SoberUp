<?php if (!empty($templateParams["messaggio"])): ?>
    <div class="alert alert-success"><?php echo $templateParams["messaggio"]; ?></div>
<?php endif; ?>
<?php if (!empty($templateParams["errori"])): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($templateParams["errori"] as $errore): ?>
                <li><?php echo $errore; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form action="" method="POST">
    <div class="mb-3">
        <label for="username" class="form-label">Username o email</label>
        <input id="username" name="username" type="text" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input id="password" name="password" type="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Entra</button>
</form>
<p class="mt-3">Non hai un account? <a href="registrazione.php">Registrati!</a></p>
<p><a href="calcolo-rapido.php">Prova il calcolo rapido senza registrarti</a></p>
