<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $templateParams["titolo"]; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css" />
</head>
<body class="d-flex flex-column min-vh-100">
    
    <?php require 'template/navbar.php'; ?>

    <main class="container my-4 flex-grow-1">

    <h1 class="text-center"><?php echo $templateParams["intestazione"]; ?></h1>

    <?php
    if(isset($templateParams["nome"])){
        require($templateParams["nome"]);
    }
    ?>
    </main>

    <footer class="footer text-center small py-4 mb-5 mb-md-0">
        <p>SoberUp — Progetto Tecnologie Web</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="utils/drawChart.js"></script>
</body>
</html>