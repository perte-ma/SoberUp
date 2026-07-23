    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">SoberUp</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" <?php isActive("index.php"); ?> href="index.php">Home</a></li>
                    <!-- TODO: aggiungere i link a Catalogo, Serata, Storico, Amici, Profilo, Login/Logout -->
                </ul>
            </div>
        </div>
    </nav>
