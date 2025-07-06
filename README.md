## Expense Tracker API

Symfony 6 · PHP 8.1+ · PostgreSQL · Doctrine · REST API · Auth · Tests

## Instalacja

composer install

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load

symfony server:start

## Użytkownicy testowi

| Email              | Hasło     | Rola        |
|--------------------|-----------|-------------|
| admin@test.com     | password  | ROLE_ADMIN  |
| user1@test.com     | password  | ROLE_USER   |
| user2@test.com     | password  | ROLE_USER   |

## API endpoints

| Metoda | Endpoint                      | Opis                            |
|--------|-------------------------------|---------------------------------|
| POST   | `/api/login`                  | Logowanie (JWT lub session)     |
| GET    | `/api/expenses`               | Lista wydatków                  |
| GET    | `/api/expenses/{id}`          | Szczegóły wydatku               |
| POST   | `/api/expenses`               | Dodanie nowego wydatku          |
| PUT    | `/api/expenses/{id}`          | Edycja wydatku                  |
| DELETE | `/api/expenses/{id}`          | Usunięcie wydatku               |
| GET    | `/api/categories`             | Lista kategorii                 |
| POST   | `/api/categories`             | Dodanie kategorii               |
| PUT    | `/api/categories/{id}`        | Edycja kategorii                |
| DELETE | `/api/categories/{id}`        | Usunięcie kategorii             |
| GET    | `/api/users`                  | Lista użytkowników (admin only) |
| GET    | `/api/users/{id}`             | Dane użytkownika (admin only)   |
| GET    | `/api/reports/balance`        | Saldo użytkownika               |
| GET    | `/api/reports/monthly`        | Podsumowanie miesięczne         |
| GET    | `/api/reports/categories`     | Podsumowanie wg kategorii       |
| GET    | `/api/reports/recent`         | Ostatnie wydatki                |
| GET    | `/api/reports/max`            | Największy wydatek              |


## Postman

Importuj plik `postman/expense-tracker.postman_collection.json` w aplikacji Postman.

- Użyj danych testowych (email + hasło)

## Komendy CLI

php bin/console app:add-user user@test.com ROLE_USER
php bin/console app:user-balance user@test.com

## Testy

## PHPUnit:
php bin/phpunit

## Behat:
vendor/bin/behat