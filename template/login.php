<?php require 'template/messaggio.php'; ?>
<?php require 'template/errori.php'; ?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">

        <form action="" method="POST" class="card shadow-sm p-4 mb-4">
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

        <div class="d-grid gap-2">
            <a href="registrazione.php" class="btn btn-outline-primary">Non hai un account? Registrati</a>
            <a href="calcolo-rapido.php" class="btn btn-outline-secondary">Prova il calcolo rapido senza registrarti</a>
        </div>

    </div>
</div>
