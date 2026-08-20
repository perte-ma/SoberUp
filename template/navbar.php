<nav class="navbar navbar-dark bg-dark">
        <div class="container d-flex justify-content-between align-items-center flex-wrap">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="logo.png" alt="SoberUp! logo" width="32" height="32" class="rounded-circle me-2" style="object-fit: cover;">
                SoberUp!
            </a>
            <?php if (isAdmin()): ?>
            <a class="nav-link text-light d-none d-md-inline-flex align-items-center gap-1 <?php isActive('admin-drink.php'); ?>" href="admin-drink.php"><i class="bi bi-cup-straw"></i> Drink</a>
            <a class="nav-link text-light d-none d-md-inline-flex align-items-center gap-1 <?php isActive('admin-categorie.php'); ?>" href="admin-categorie.php"><i class="bi bi-tags-fill"></i> Categorie</a>
            <a class="nav-link text-light d-none d-md-inline-flex align-items-center gap-1 <?php isActive('admin-articoli.php'); ?>" href="admin-articoli.php"><i class="bi bi-newspaper"></i> Contenuti</a>
            <a class="nav-link text-light d-none d-md-inline-flex align-items-center gap-1 <?php isActive('admin-utenti.php'); ?>" href="admin-utenti.php"><i class="bi bi-people-fill"></i> Utenti</a>
            <a class="nav-link text-light d-none d-md-inline-flex align-items-center gap-1" href="logout.php"><i class="bi bi-box-arrow-right"></i> Esci</a>
            <?php elseif (isUserLoggedIn()): ?>
            <a class="nav-link text-light d-none d-md-inline-flex align-items-center gap-1 <?php isActive('index.php'); ?>" href="index.php"><i class="bi bi-house-door-fill"></i> Dashboard</a>
            <a class="nav-link text-light d-none d-md-inline-flex align-items-center gap-1 <?php isActive('catalogo-drink.php'); ?>" href="catalogo-drink.php"><i class="bi bi-cup-straw"></i> Catalogo Drink</a>
            <a class="nav-link text-light d-none d-md-inline-flex align-items-center gap-1 <?php isActive('serata.php'); ?>" href="serata.php"><i class="bi bi-activity"></i> La tua Serata</a>
            <a class="nav-link text-light d-none d-md-inline-flex align-items-center gap-1 <?php isActive('profilo.php'); ?>" href="profilo.php"><i class="bi bi-person-circle"></i> Profilo</a>
            <?php endif; ?>
            <div class="form-check form-switch text-light mb-0">
                <input class="form-check-input" type="checkbox" id="darkModeSwitch">
                <label class="form-check-label" for="darkModeSwitch"><i class="bi bi-moon-stars-fill" id="temaIcona"></i><span class="visually-hidden">Attiva/disattiva tema scuro</span></label>
            </div>
        </div>
    </nav>
    <?php if (isUserLoggedIn()): ?>
    <nav class="fixed-bottom bg-dark d-flex d-md-none justify-content-around py-2">
        <?php if (isAdmin()): ?>
        <a class="nav-link text-light <?php isActive('admin-drink.php'); ?>" href="admin-drink.php" aria-label="Drink"><i class="bi bi-cup-straw fs-4"></i></a>
        <a class="nav-link text-light <?php isActive('admin-categorie.php'); ?>" href="admin-categorie.php" aria-label="Categorie"><i class="bi bi-tags-fill fs-4"></i></a>
        <a class="nav-link text-light <?php isActive('admin-articoli.php'); ?>" href="admin-articoli.php" aria-label="Contenuti"><i class="bi bi-newspaper fs-4"></i></a>
        <a class="nav-link text-light <?php isActive('admin-utenti.php'); ?>" href="admin-utenti.php" aria-label="Utenti"><i class="bi bi-people-fill fs-4"></i></a>
        <a class="nav-link text-light" href="logout.php" aria-label="Esci"><i class="bi bi-box-arrow-right fs-4"></i></a>
        <?php else: ?>
        <a class="nav-link text-light <?php isActive('index.php'); ?>" href="index.php" aria-label="Dashboard"><i class="bi bi-house-door-fill fs-4"></i></a>
        <a class="nav-link text-light <?php isActive('catalogo-drink.php'); ?>" href="catalogo-drink.php" aria-label="Catalogo Drink"><i class="bi bi-cup-straw fs-4"></i></a>
        <a class="nav-link text-light <?php isActive('serata.php'); ?>" href="serata.php" aria-label="La tua Serata"><i class="bi bi-activity fs-4"></i></a>
        <a class="nav-link text-light <?php isActive('profilo.php'); ?>" href="profilo.php" aria-label="Profilo"><i class="bi bi-person-circle fs-4"></i></a>
        <?php endif; ?>
    </nav>
    <?php endif; ?>

    <script>
        function getPreferredTheme() {
            const salvato = localStorage.getItem("theme");
            if (salvato) return salvato;
            const ora = new Date().getHours();
            return (ora >= 20 || ora < 7) ? "dark" : "light";
        }

        function applyTheme(theme) {
            document.documentElement.setAttribute("data-theme", theme);
            document.documentElement.setAttribute("data-bs-theme", theme);
            document.getElementById("darkModeSwitch").checked = (theme == "dark");
            document.getElementById("temaIcona").className = theme == "dark" ? "bi bi-moon-stars-fill" : "bi bi-sun-fill";
        }

        applyTheme(getPreferredTheme());

        document.getElementById("darkModeSwitch").addEventListener("change", function() {
            const theme = this.checked ? "dark" : "light";
            localStorage.setItem("theme", theme);
            applyTheme(theme);
            if (typeof currentSerataId !== 'undefined') {
                renderSerataChart(currentSerataId);
            }
        });
    </script>
