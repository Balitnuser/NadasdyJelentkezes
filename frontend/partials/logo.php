<?php
/** Az iskola logója: tedd be ide: assets/img/logo.png (vagy .svg). Amíg nincs, a név monogramja látszik. */
$logoFajl = null;
foreach (['logo.svg', 'logo.png', 'logo.jpg'] as $f) {
    if (is_file(__DIR__ . '/../assets/img/' . $f)) { $logoFajl = $f; break; }
}
?>
<?php if ($logoFajl): ?>
    <img class="logo" src="<?= $gyoker ?>assets/img/<?= $logoFajl ?>" alt="Iskola logó">
<?php else: ?>
    <span class="logo logo-placeholder" aria-hidden="true">NT</span>
<?php endif; ?>
