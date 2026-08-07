<?php if (!empty($templateParams["errori"])): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($templateParams["errori"] as $errore): ?>
                <li><?php echo $errore; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
