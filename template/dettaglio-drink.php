<?php require 'template/messaggio.php'; ?>
<?php $drink = $templateParams["drink"]; ?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm mb-4">
            <img src="upload/<?php echo $drink['immagine'] ?? 'placeholder.png'; ?>" class="card-img-top" style="height: 240px; object-fit: cover;" alt="<?php echo $drink['nomedrink']; ?>">
            <div class="card-body">
                <p class="text-muted mb-2"><?php echo $drink['nomecategoria']; ?></p>
                <p>
                    <span class="badge badge-orario rounded-pill"><?php echo $drink['gradazione']; ?>%</span>
                    <span class="badge badge-orario rounded-pill"><?php echo $drink['volume_standard']; ?> ml</span>
                </p>
                <p class="card-text"><?php echo $drink['descrizione']; ?></p>
            </div>
        </div>

        <form action="" method="POST" class="card shadow-sm p-4">
            <div class="mb-3">
                <label for="volume" class="form-label">Volume bevuto (ml)</label>
                <input id="volume" name="volume" type="number" class="form-control" min="1" required value="<?php echo $drink['volume_standard']; ?>">
            </div>
            <div class="mb-3">
                <label for="orario" class="form-label">Orario</label>
                <input id="orario" name="orario" type="time" class="form-control" required value="<?php echo date('H:i'); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi alla tua serata</button>
        </form>

    </div>
</div>
