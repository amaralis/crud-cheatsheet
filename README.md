# Initial notes:

1. Include port in your APP_URL constant in .env
2. Run `composer install` for dependencies
3. Run `php artisan storage:link` to create the symlink for image storage if for some reason it's not already created (may very depending on system)
4. Run `php artisan migrate --seed --seeder=BandSeeder` for your first seed, or `php artisan migrate:fresh --seed --seeder=BandSeeder` to re-seed
5. Register a user. default `user_type` is 1. Change to 0 in DB to gain admin privileges.