<?php
session_start();
if (empty($_SESSION['admin'])) {
    header('Location: belepes.php');
    exit;
}
require __DIR__ . '/../partials/seged.php';
$oldalCim = 'Admin';
$gyoker   = '../';
require __DIR__ . '/../partials/head.php';
?>
<body class="page-admin">

<?php
$fejlecJobb = '<div class="head-actions">'
    . '<a href="../index.php" class="btn btn-secondary">Vissza az űrlapra</a>'
    . '<button type="button" class="btn btn-secondary" id="kilepes">Kijelentkezés</button>'
    . '</div>';
require __DIR__ . '/../partials/fejlec.php';
?>

<div class="container admin-title">
    <h1>Nyílt napi jelentkezések</h1>
    <nav class="tabs" role="tablist">
        <button class="tab active" data-tab="attekintes" role="tab">Áttekintés</button>
        <button class="tab" data-tab="szakok" role="tab">Szakmák szerint</button>
        <button class="tab" data-tab="jelentkezesek" role="tab">Jelentkezők <span class="count" id="fulDb">0</span></button>
    </nav>
</div>

<main class="container admin-main">

    <div class="alert alert-error" id="betoltesHiba" hidden>Nem sikerült betölteni az adatokat.</div>

    <!-- ===== ÁTTEKINTÉS ===== -->
    <section class="panel active" id="attekintes">
        <div class="stat-grid">
            <div class="stat">
                <p class="stat-label">Összes jelentkező</p>
                <p class="stat-value" id="statOsszes">–</p>
            </div>
            <div class="stat">
                <p class="stat-label">Végzős</p>
                <p class="stat-value" id="statVegzos">–</p>
                <p class="stat-sub" id="statVegzosSzazalek"></p>
            </div>
            <div class="stat">
                <p class="stat-label">Kollégista</p>
                <p class="stat-value" id="statKollegium">–</p>
                <p class="stat-sub" id="statBejaro"></p>
            </div>
            <div class="stat">
                <p class="stat-label">Végzős és kollégista</p>
                <p class="stat-value" id="statKollVegzos">–</p>
            </div>
        </div>

        <div class="panel-box">
            <div class="box-head row">
                <h2>Érdeklődők szakmánként</h2>
                <div class="legend">
                    <span><i class="dot dot-main"></i>Összes</span>
                    <span><i class="dot dot-accent"></i>Ebből végzős</span>
                </div>
            </div>
            <ul class="bars" id="savok"></ul>
        </div>
    </section>

    <!-- ===== SZAKOK ===== -->
    <section class="panel" id="szakok">
        <div class="panel-box">
            <div class="box-head">
                <h2>Jelentkezők szakmánként</h2>
                <p>Egy diák több szakmát is megjelölhet, ezért az oszlopok összege nagyobb lehet a jelentkezők számánál.</p>
            </div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Szak</th>
                            <th class="num">Összes</th>
                            <th class="num">Végzős</th>
                            <th class="num">Kollégista</th>
                            <th class="num">Kollégista és végzős</th>
                        </tr>
                    </thead>
                    <tbody id="szakTabla"></tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- ===== JELENTKEZÉSEK ===== -->
    <section class="panel" id="jelentkezesek">
        <div class="panel-box">
            <div class="toolbar">
                <input type="search" id="kereso" class="search" placeholder="Keresés név vagy iskola alapján">
                <div class="filter-chips" id="szurok">
                    <button class="fchip active" data-szuro="mind">Mind</button>
                    <button class="fchip" data-szuro="vegzos">Végzős</button>
                    <button class="fchip" data-szuro="kollegium">Kollégista</button>
                    <button class="fchip" data-szuro="bejaro">Bejáró</button>
                    <button class="fchip" data-szuro="bizonytalan">Még nem tudja</button>
                </div>
                <select id="szakSzuro" class="select">
                    <option value="">Minden szakma</option>
                    <?php foreach (SZAKOK as $szak): ?>
                        <option value="<?= e($szak) ?>"><?= e($szak) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="table-wrap">
                <table class="data-table" id="jelentkezoTabla">
                    <thead>
                        <tr>
                            <th>Név</th>
                            <th>Iskola</th>
                            <th>Szakmák</th>
                            <th>Lakhatás</th>
                            <th>Végzős</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div class="empty" id="ures" hidden>
                    <p id="uresSzoveg"></p>
                </div>
            </div>
            <p class="table-foot"><span id="talalatDb">0</span> jelentkezés látható</p>
        </div>
    </section>
</main>

<!-- Törlés megerősítése -->
<div class="modal" id="torlesModal" hidden>
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modalCim">
        <h3 id="modalCim">Jelentkezés törlése</h3>
        <p>Biztosan törlöd <strong id="modalNev"></strong> jelentkezését? Ez nem vonható vissza.</p>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" id="modalMegse">Mégse</button>
            <button type="button" class="btn btn-danger" id="modalTorol">Törlés</button>
        </div>
    </div>
</div>

<div class="toast" id="toast" hidden></div>

<script src="../assets/js/admin.js"></script>
</body>
</html>
