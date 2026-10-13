<?php /** Iskolai fejléc */ ?>
<header class="site-head">
    <div class="site-head-inner">
        <a class="brand" href="<?= $gyoker ?>index.php">
            <?php require __DIR__ . '/logo.php'; ?>
            <span class="brand-name"><?= e(ISKOLA_NEV) ?></span>
        </a>
        <?php if (!empty($fejlecJobb)) echo $fejlecJobb; ?>
    </div>
</header>
