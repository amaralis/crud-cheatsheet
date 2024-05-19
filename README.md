# For seeding:

Run `php artisan migrate --seed --seeder=BandSeeder` for your first seed, or `php artisan migrate:fresh --seed --seeder=BandSeeder` to re-seed

# Don't forget

- Run `composer install` for dependencies
- Include port in your APP_URL constant in .env
- User with `user_type` 0 is admin. `user_type` 1 is standard. Must be changed directly in DB