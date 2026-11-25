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

Testdata, wachtwoord `password`:

- `patient`: `jan.jansen@example.nl`, `eva.devries@example.nl`, `noah.bakker@example.nl`
- `tandarts`: `tandarts@smilepro.nl`
- `mondhygienist`: `mondhygienist@smilepro.nl`
- `assistent`: `assistent@smilepro.nl`
- `beheerder`: `beheerder@smilepro.nl`
- inactief, wordt geweigerd: `inactief.patient@example.nl`, `inactief.tandarts@smilepro.nl`, `inactief.mondhygienist@smilepro.nl`, `inactief.assistent@smilepro.nl`, `inactief.beheerder@smilepro.nl`
