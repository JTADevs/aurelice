# Aurelice

Laravel 13 + Vue 3 + MySQL 8.4 (Docker dev przez Laravel Sail).

## Uruchomienie

```sh
cp .env.example .env            # tylko przy pierwszym klonowaniu
composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate   # tylko przy pierwszym klonowaniu
./vendor/bin/sail artisan migrate
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

Aplikacja: http://localhost:8000 · Vite/HMR: 5174 · MySQL: localhost:3307 (sail/password) · Mailpit: http://localhost:8026

Wszystkie komendy PHP/Node uruchamiaj przez `sail` (`sail artisan …`, `sail npm …`, `sail composer …`, `sail test`).
`node_modules` są zainstalowane dla Linuksa (wewnątrz kontenera), więc `npm` z hosta nie zadziała bez reinstalacji.

## Frontend

Wejście: `resources/js/app.js` montuje `resources/js/App.vue` w `#app` (`resources/views/welcome.blade.php`). Alias `@` → `resources/js`. Tailwind 4.
