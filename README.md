## Dishtansya API Endpoints

`git clone https://github.com/bry221996/dishtansya.git`

`docker run --rm \ -u "$(id -u):$(id -g)" \ -v $(pwd):/var/www/html \ -w /var/www/html \ laravelsail/php81-composer:latest \ composer install --ignore-platform-reqs`

-   https://laravel.com/docs/8.x/sail#installing-composer-dependencies-for-existing-projects

`cp .env.example .env`

`./vendor/bin/sail up`

`./vendor/bin/sail composer install`

`./vendor/bin sail php artisan jwt:secret`

`./vendor/bin sail php artisan test`
