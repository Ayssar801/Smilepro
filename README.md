# SmilePro

Laravel-app voor SmilePro. Iedereen gebruikt dezelfde inlogpagina. De rol bepaalt welke pagina daarna opent.

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

Open daarna `http://127.0.0.1:8000/inloggen`.

Testdata, wachtwoord `Welkom123!`:

- `assistent@smilepro.nl` komt bij patiënt zoeken, toevoegen en bekijken
- `beheerder@smilepro.nl` komt bij de medewerkers
- `tandarts@smilepro.nl` komt op het tandartsdashboard
- `mondhygienist@smilepro.nl` komt op het mondhygiënistendashboard
- `inactief.tandarts@smilepro.nl` wordt geweigerd
