<?php
require __DIR__ . '/partials/seged.php';
$oldalCim = 'Sikeres jelentkezés';
$gyoker   = '';
// A backend sikeres mentés után ide irányít át
$nev = $_GET['nev'] ?? '';
require __DIR__ . '/partials/head.php';
?>
<body>

<?php require __DIR__ . '/partials/fejlec.php'; ?>

<main class="container narrow">
    <div class="panel-box success-box">
        <div class="success-mark" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
        </div>
        <h1>Köszönjük<?= $nev !== '' ? ', ' . e($nev) : '' ?>!</h1>
        <p>A jelentkezésedet rögzítettük. Várunk a nyílt napon!</p>
        <a href="index.php" class="btn btn-primary btn-lg">Új jelentkezés</a>
        <p class="help">Az oldal <span id="visszaszamlalo">15</span> másodperc múlva visszaáll az űrlapra.</p>
    </div>
</main>

<script>
    // Tableten kint hagyva magától visszaáll a következő diáknak
    (function () {
        let mp = 15;
        const el = document.getElementById('visszaszamlalo');
        setInterval(function () {
            mp--;
            if (mp <= 0) { window.location.href = 'index.php'; return; }
            el.textContent = mp;
        }, 1000);
    })();
</script>
</body>
</html>
