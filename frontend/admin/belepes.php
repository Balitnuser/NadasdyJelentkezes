<?php
session_start();
require __DIR__ . '/../partials/seged.php';
if (!empty($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}
$oldalCim = 'Admin belépés';
$gyoker   = '../';
require __DIR__ . '/../partials/head.php';
?>
<body>

<?php
$fejlecJobb = '<a href="../index.php" class="btn btn-secondary">Vissza az űrlapra</a>';
require __DIR__ . '/../partials/fejlec.php';
?>

<main class="container login-wrap">
    <form class="panel-box login-box" id="belepesForm">
        <h1>Adminisztráció</h1>
        <p class="help">Az admin felület megtekintéséhez add meg a jelszót.</p>

        <div class="alert alert-error" id="hiba" hidden>Hibás jelszó.</div>

        <div class="field">
            <label for="jelszo">Jelszó</label>
            <input id="jelszo" name="jelszo" type="password" autocomplete="current-password" required autofocus>
        </div>

        <button type="submit" class="btn btn-primary btn-lg btn-block">Belépés</button>
    </form>
</main>

<script>
    document.getElementById('belepesForm').addEventListener('submit', function (e) {
        e.preventDefault();
        fetch('../../backend/api/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ password: document.getElementById('jelszo').value })
        }).then(function (valasz) {
            if (valasz.ok) {
                window.location.href = 'index.php';
            } else {
                document.getElementById('hiba').hidden = false;
                document.getElementById('jelszo').select();
            }
        });
    });
</script>
</body>
</html>
