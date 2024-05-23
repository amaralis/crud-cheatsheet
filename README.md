# Initial notes:

- Register a user. default `user_type` is 1. Change to 0 in DB to gain admin privileges.

# For seeding:

Run `php artisan migrate --seed --seeder=BandSeeder` for your first seed, or `php artisan migrate:fresh --seed --seeder=BandSeeder` to re-seed

# Don't forget

- Run `composer install` for dependencies
- Include port in your APP_URL constant in .env
- Run `php artisan storage:link` to create the symlink for image storage
- User with `user_type` 0 is admin. `user_type` 1 is standard. Must be changed directly in DB