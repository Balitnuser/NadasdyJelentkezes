(function () {
    const form = document.getElementById('regForm');
    if (!form) return;

    function jelol(mezo, hibas) {
        mezo.classList.toggle('invalid', hibas);
    }

    function ellenoriz() {
        let ok = true;

        ['nev', 'iskola'].forEach(function (id) {
            const input = document.getElementById(id);
            const hibas = input.value.trim().length < 3;
            jelol(input.closest('.field'), hibas);
            if (hibas) ok = false;
        });

        const vanSzak = form.querySelector('input[name="szak[]"]:checked') !== null;
        jelol(document.getElementById('szakField'), !vanSzak);
        if (!vanSzak) ok = false;

        ['lakhatas', 'vegzos'].forEach(function (nev) {
            const van = form.querySelector('input[name="' + nev + '"]:checked') !== null;
            jelol(document.getElementById(nev + 'Field'), !van);
            if (!van) ok = false;
        });

        return ok;
    }

    // Javítás közben eltűnik a hibajelzés
    form.addEventListener('change', function (e) {
        const mezo = e.target.closest('.field');
        if (mezo && mezo.classList.contains('invalid')) ellenoriz();
    });
    form.addEventListener('input', function (e) {
        const mezo = e.target.closest('.field');
        if (mezo && mezo.classList.contains('invalid')) ellenoriz();
    });

    form.addEventListener('submit', function (e) {
        if (!ellenoriz()) {
            e.preventDefault();
            const elso = form.querySelector('.invalid');
            if (elso) elso.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        e.preventDefault();
        const gomb = form.querySelector('button[type="submit"]');
        const hiba = document.getElementById('hiba');
        const nev = document.getElementById('nev').value.trim();
        const adatok = {
            name: nev,
            school: document.getElementById('iskola').value.trim(),
            majors: Array.from(form.querySelectorAll('input[name="szak[]"]:checked')).map(function (cb) { return cb.value; }),
            dorm: parseInt(form.querySelector('input[name="lakhatas"]:checked').value, 10),
            senior: form.querySelector('input[name="vegzos"]:checked').value === '1'
        };

        gomb.classList.add('loading');
        hiba.hidden = true;

        fetch('../backend/api/create.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(adatok)
        }).then(function (valasz) {
            if (valasz.ok) {
                window.location.href = 'sikeres.php?nev=' + encodeURIComponent(nev);
            } else {
                throw new Error(valasz.status);
            }
        }).catch(function () {
            gomb.classList.remove('loading');
            hiba.hidden = false;
            hiba.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });
})();
