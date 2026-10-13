<?php
require __DIR__ . '/partials/seged.php';
$oldalCim = 'Jelentkezés';
$gyoker   = '';
require __DIR__ . '/partials/head.php';
?>
<body>

<?php require __DIR__ . '/partials/fejlec.php'; ?>

<main class="container">

    <div class="page-title">
        <p class="eyebrow">Nyílt nap</p>
        <h1>Jelentkezés a nyílt napra</h1>
        <p class="lead">Töltsd ki az alábbi adatokat, hogy tudjuk, kik érkeznek és melyik szakma iránt érdeklődnek.</p>
    </div>

    <div class="alert alert-error" id="hiba" hidden>Nem sikerült elküldeni a jelentkezést. Ellenőrizd az adatokat, és próbáld újra.</div>

    <form id="regForm" class="panel-box reg-form" novalidate>

        <section class="form-section">
            <h2><span class="step">1</span> Személyes adatok</h2>

            <div class="field">
                <label for="nev">Teljes név</label>
                <input id="nev" name="teljes_nev" type="text" autocomplete="name" required minlength="3" maxlength="50">
                <p class="error-msg">Add meg a teljes neved.</p>
            </div>

            <div class="field">
                <label for="iskola">Jelenlegi iskolád neve</label>
                <input id="iskola" name="iskola" type="text" required minlength="3" maxlength="50">
                <p class="error-msg">Add meg az iskolád nevét.</p>
            </div>
        </section>

        <section class="form-section">
            <fieldset class="field" id="szakField">
                <legend><h2><span class="step">2</span> Érdeklődési terület</h2></legend>
                <p class="help">Jelöld be, melyik szakma érdekel. Többet is választhatsz.</p>
                <div class="check-list">
                    <?php foreach (SZAKOK as $szak): ?>
                        <label class="check-row">
                            <input type="checkbox" name="szak[]" value="<?= e($szak) ?>">
                            <span><?= e($szak) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <p class="error-msg">Válassz legalább egy szakmát.</p>
            </fieldset>
        </section>

        <section class="form-section">
            <h2><span class="step">3</span> Egyéb adatok</h2>
            <div class="two-col">
                <fieldset class="field" id="lakhatasField">
                    <legend>Kollégista vagy bejáró lennél?</legend>
                    <div class="radio-group">
                        <label class="radio-row"><input type="radio" name="lakhatas" value="1" required><span>Kollégista</span></label>
                        <label class="radio-row"><input type="radio" name="lakhatas" value="0"><span>Bejáró</span></label>
                        <label class="radio-row"><input type="radio" name="lakhatas" value="2"><span>Még nem tudom</span></label>
                    </div>
                    <p class="error-msg">Válassz egy lehetőséget.</p>
                </fieldset>

                <fieldset class="field" id="vegzosField">
                    <legend>Idén végzős vagy?</legend>
                    <div class="radio-group">
                        <label class="radio-row"><input type="radio" name="vegzos" value="1" required><span>Igen</span></label>
                        <label class="radio-row"><input type="radio" name="vegzos" value="0"><span>Nem</span></label>
                    </div>
                    <p class="error-msg">Válassz egy lehetőséget.</p>
                </fieldset>
            </div>
        </section>

        <div class="form-actions">
            <p class="help">Az adatokat csak a nyílt nap szervezéséhez használjuk fel.</p>
            <button type="submit" class="btn btn-primary btn-lg">Jelentkezés elküldése</button>
        </div>
    </form>
</main>

<footer class="site-foot">
    <div class="container">
        <span>© <?= date('Y') ?> <?= e(ISKOLA_NEV) ?></span>
        <a href="admin/">Adminisztráció</a>
    </div>
</footer>

<script src="assets/js/form.js"></script>
</body>
</html>
