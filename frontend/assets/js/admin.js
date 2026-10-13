(function () {
    const API = '../../backend/api/';

    /* ---------- Fülek ---------- */
    const fulek = document.querySelectorAll('.tab');
    fulek.forEach(function (ful) {
        ful.addEventListener('click', function () {
            fulek.forEach(function (f) { f.classList.remove('active'); });
            document.querySelectorAll('.panel').forEach(function (p) { p.classList.remove('active'); });
            ful.classList.add('active');
            document.getElementById(ful.dataset.tab).classList.add('active');
        });
    });

    function elem(tag, osztaly, szoveg) {
        const el = document.createElement(tag);
        if (osztaly) el.className = osztaly;
        if (szoveg !== undefined) el.textContent = szoveg;
        return el;
    }

    function kezelValasz(valasz) {
        if (valasz.status === 401) {
            window.location.href = 'belepes.php';
            throw new Error('401');
        }
        if (!valasz.ok) throw new Error(valasz.status);
        return valasz;
    }

    /* ---------- Statisztika ---------- */
    function betoltStatisztika() {
        return fetch(API + 'stats.php')
            .then(kezelValasz)
            .then(function (v) { return v.json(); })
            .then(function (adat) {
                const o = adat.summary;
                document.getElementById('statOsszes').textContent = o.total;
                document.getElementById('statVegzos').textContent = o.senior;
                document.getElementById('statVegzosSzazalek').textContent =
                    Math.round(o.senior / Math.max(o.total, 1) * 100) + '% az összesből';
                document.getElementById('statKollegium').textContent = o.dorm;
                document.getElementById('statBejaro').textContent = o.commuter + ' bejáró, ' + o.unsure + ' még nem tudja';
                document.getElementById('statKollVegzos').textContent = o.dorm_senior;

                const max = Math.max.apply(null, adat.majors.map(function (m) { return m.total; }).concat(1));
                const savok = document.getElementById('savok');
                const tabla = document.getElementById('szakTabla');
                savok.innerHTML = '';
                tabla.innerHTML = '';

                adat.majors.forEach(function (m) {
                    const li = elem('li');
                    li.appendChild(elem('span', 'bar-label', m.major));
                    const sav = elem('span', 'bar-track');
                    const osszes = elem('span', 'bar bar-all');
                    osszes.style.width = (m.total / max * 100) + '%';
                    const vegzos = elem('span', 'bar bar-sub');
                    vegzos.style.width = (m.senior / max * 100) + '%';
                    sav.appendChild(osszes);
                    sav.appendChild(vegzos);
                    li.appendChild(sav);
                    const szam = elem('span', 'bar-num', m.total);
                    szam.appendChild(elem('small', '', ' / ' + m.senior));
                    li.appendChild(szam);
                    savok.appendChild(li);

                    const tr = elem('tr');
                    tr.appendChild(elem('td', '', m.major));
                    [m.total, m.senior, m.dorm, m.dorm_senior].forEach(function (n) {
                        tr.appendChild(elem('td', 'num', n));
                    });
                    tabla.appendChild(tr);
                });
            });
    }

    /* ---------- Jelentkezők listája ---------- */
    const tbody = document.querySelector('#jelentkezoTabla tbody');
    let jelentkezok = [];

    function betoltJelentkezok() {
        return fetch(API + 'getall.php')
            .then(kezelValasz)
            .then(function (v) { return v.json(); })
            .then(function (lista) {
                jelentkezok = lista;
                document.getElementById('fulDb').textContent = lista.length;
                rajzol();
            });
    }

    const LAKHATAS = { 0: 'Bejáró', 1: 'Kollégista', 2: 'Még nem tudja' };

    function sor(j) {
        const tr = elem('tr');

        const nevTd = elem('td');
        const nev = elem('div', 'nev-cell', j.name);
        nev.appendChild(elem('small', '', '#' + j.id));
        nevTd.appendChild(nev);
        tr.appendChild(nevTd);

        tr.appendChild(elem('td', '', j.school));

        const szakTd = elem('td');
        const cimkek = elem('div', 'tags');
        j.majors.forEach(function (m) { cimkek.appendChild(elem('span', 'tag', m)); });
        szakTd.appendChild(cimkek);
        tr.appendChild(szakTd);

        tr.appendChild(elem('td', j.dorm === 2 ? 'muted' : '', LAKHATAS[j.dorm]));

        const vegzosTd = elem('td');
        if (j.senior) vegzosTd.appendChild(elem('span', 'badge-yes', 'Igen'));
        else vegzosTd.textContent = 'Nem';
        tr.appendChild(vegzosTd);

        const muveletTd = elem('td', 'act-cell');
        const gomb = elem('button', 'btn btn-danger-outline btn-sm', 'Törlés');
        gomb.type = 'button';
        gomb.addEventListener('click', function () { kerdez(j); });
        muveletTd.appendChild(gomb);
        tr.appendChild(muveletTd);

        return tr;
    }

    /* ---------- Keresés + szűrés ---------- */
    const kereso = document.getElementById('kereso');
    const szakSz = document.getElementById('szakSzuro');
    const ures = document.getElementById('ures');
    let aktivSzuro = 'mind';

    function rajzol() {
        const q = kereso.value.trim().toLowerCase();
        const szak = szakSz.value;

        const lathato = jelentkezok.filter(function (j) {
            if ((j.name + ' ' + j.school).toLowerCase().indexOf(q) === -1) return false;
            if (aktivSzuro === 'vegzos' && !j.senior) return false;
            if (aktivSzuro === 'kollegium' && j.dorm !== 1) return false;
            if (aktivSzuro === 'bejaro' && j.dorm !== 0) return false;
            if (aktivSzuro === 'bizonytalan' && j.dorm !== 2) return false;
            if (szak && j.majors.indexOf(szak) === -1) return false;
            return true;
        });

        tbody.innerHTML = '';
        lathato.forEach(function (j) { tbody.appendChild(sor(j)); });

        document.getElementById('talalatDb').textContent = lathato.length;
        document.getElementById('uresSzoveg').textContent = jelentkezok.length
            ? 'Nincs a szűrésnek megfelelő jelentkezés.'
            : 'Még nincs egy jelentkezés sem.';
        ures.hidden = lathato.length !== 0;
    }

    kereso.addEventListener('input', rajzol);
    szakSz.addEventListener('change', rajzol);
    document.querySelectorAll('.fchip').forEach(function (chip) {
        chip.addEventListener('click', function () {
            document.querySelectorAll('.fchip').forEach(function (c) { c.classList.remove('active'); });
            chip.classList.add('active');
            aktivSzuro = chip.dataset.szuro;
            rajzol();
        });
    });

    /* ---------- Törlés ---------- */
    const modal = document.getElementById('torlesModal');
    let torlendo = null;

    function kerdez(j) {
        torlendo = j;
        document.getElementById('modalNev').textContent = j.name;
        modal.hidden = false;
    }

    function bezar() { modal.hidden = true; torlendo = null; }
    document.getElementById('modalMegse').addEventListener('click', bezar);
    modal.addEventListener('click', function (e) { if (e.target === modal) bezar(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') bezar(); });

    document.getElementById('modalTorol').addEventListener('click', function () {
        if (!torlendo) return;
        const id = torlendo.id;
        bezar();
        fetch(API + 'delete.php?id=' + id, { method: 'DELETE' })
            .then(kezelValasz)
            .then(function () { return frissit(); })
            .then(function () { toast('Jelentkezés törölve'); })
            .catch(function () { toast('A törlés nem sikerült'); });
    });

    /* ---------- Kijelentkezés ---------- */
    document.getElementById('kilepes').addEventListener('click', function () {
        fetch(API + 'logout.php', { method: 'POST' }).then(function () {
            window.location.href = 'belepes.php';
        });
    });

    /* ---------- Toast ---------- */
    const toastEl = document.getElementById('toast');
    let toastIdo;
    function toast(uzenet) {
        toastEl.textContent = uzenet;
        toastEl.hidden = false;
        clearTimeout(toastIdo);
        toastIdo = setTimeout(function () { toastEl.hidden = true; }, 2500);
    }

    function frissit() {
        return Promise.all([betoltStatisztika(), betoltJelentkezok()]);
    }

    frissit().catch(function (e) {
        if (e.message !== '401') document.getElementById('betoltesHiba').hidden = false;
    });
})();
