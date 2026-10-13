# Vasi SZC Nádasdy Tamás Technikum és Kollégium – Nyílt napi jelentkezés

Regisztrációs űrlap és admin panel a nyílt napokra, sima PHP-ban (mysqli + JSON API).

## Indítás Laragonnal

1. A projekt mappáját tedd ide: `C:\laragon\www\NadasdyJelentkezes`
2. Laragonban: **Start All**
3. Az adatbázis: `iskola`, tábla: `jelentkezok` (lásd lent)
4. Böngészőben:
   - Űrlap: http://localhost/NadasdyJelentkezes/frontend/
   - Admin: http://localhost/NadasdyJelentkezes/frontend/admin/

A kapcsolódási adatok a `backend/db/conn.php`-ban vannak (alapból `root`, jelszó nélkül).

Az admin felület jelszóval védett: **nadasdyadmin** (módosítható a `backend/config.php`-ban: `ADMIN_JELSZO`).

## Adatbázis

```sql
CREATE TABLE jelentkezok (
  Id            INT AUTO_INCREMENT PRIMARY KEY,
  Full_name     VARCHAR(50),
  CurrentSchool VARCHAR(50),
  Major_Name    VARCHAR(500),
  Dorm          TINYINT DEFAULT 0,
  isSenior      TINYINT DEFAULT 0
);
```

- `Major_Name`: a kiválasztott szakmák vesszővel elválasztva (pl. `CNC-programozó,Szakács`)
- `Dorm`: 0 = bejáró, 1 = kollégista, 2 = még nem tudja

Ha a tábla még a régi, `VARCHAR(50)`-es, futtasd le egyszer:

```sql
ALTER TABLE jelentkezok MODIFY Major_Name VARCHAR(500);
```

## Felépítés

```
backend/
├── db/conn.php        mysqli kapcsolat
├── config.php         Admin jelszó, iskola neve, szakmák listája
├── applicant.php      Applicant osztály (egy jelentkező)
├── applicantlist.php  Applicantlist osztály (lekérdezések)
└── api/
    ├── create.php     POST   – új jelentkezés (JSON)
    ├── getall.php     GET    – összes jelentkező (admin)
    ├── stats.php      GET    – statisztika (admin)
    ├── delete.php     DELETE – ?id= törlés (admin)
    ├── login.php      POST   – admin belépés (JSON: password)
    └── logout.php     POST   – kijelentkezés

frontend/
├── index.php                  Regisztrációs űrlap
├── sikeres.php                Köszönő oldal (15 mp után visszaáll)
├── admin/belepes.php          Admin belépő oldal
├── admin/index.php            Áttekintés, szakmánkénti statisztika, lista + törlés
├── partials/                  Közös fejléc, logó, segédfüggvények
└── assets/                    CSS, JS, logó
```

A választható szakmákat a `backend/config.php` `SZAKOK` listájában lehet módosítani.
