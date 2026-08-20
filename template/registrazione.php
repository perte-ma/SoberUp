<?php require 'template/errori.php'; ?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">

        <form method="POST" class="card shadow-sm p-4 mb-4">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input id="nome" name="nome" type="text" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="cognome" class="form-label">Cognome</label>
                <input id="cognome" name="cognome" type="text" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input id="username" name="username" type="text" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" name="password" type="password" class="form-control" minlength="8" required>
            </div>
            <div class="mb-3">
                <label for="conferma-password" class="form-label">Conferma Password</label>
                <input id="conferma-password" name="conferma_password" type="password" class="form-control" minlength="8" required>
            </div>
            <fieldset class="mb-3">
                <legend class="form-label">Sesso</legend>
                <div class="form-check form-check-inline">
                    <input id="maschio" name="sesso" type="radio" class="form-check-input" required value="M">
                    <label for="maschio" class="form-check-label">M</label>
                </div>
                <div class="form-check form-check-inline">
                    <input id="femmina" name="sesso" type="radio" class="form-check-input" required value="F">
                    <label for="femmina" class="form-check-label">F</label>
                </div>
            </fieldset>
            <div class="mb-3">
                <label for="data-nascita" class="form-label">Data di nascita</label>
                <input id="data-nascita" name="data_nascita" type="date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="peso" class="form-label">Peso (kg)</label>
                <input id="peso" name="peso" type="number" class="form-control" step="0.1" min="1" required>
            </div>
            <div class="mb-3">
                <label for="altezza" class="form-label">Altezza (cm)</label>
                <input id="altezza" name="altezza" type="number" class="form-control" step="0.1" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrati</button>
        </form>

        <div class="d-grid gap-2">
            <a href="login.php" class="btn btn-outline-primary">Hai già un account? Accedi</a>
        </div>

    </div>
</div>

<script>

    const password = document.getElementById("password");
    const confermaPassword = document.getElementById("conferma-password");
     
    function controllaPassword() {
        if (password.value != confermaPassword.value) {
            confermaPassword.setCustomValidity("Le password non coincidono");
        } else {
            confermaPassword.setCustomValidity("");
        }
    }

    password.addEventListener("input", controllaPassword);
    confermaPassword.addEventListener("input", controllaPassword);
</script>