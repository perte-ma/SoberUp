<?php require 'template/messaggio.php'; ?>
<?php require 'template/errori.php'; ?>
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
