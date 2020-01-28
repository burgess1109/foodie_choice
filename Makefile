init:
	docker-compose exec nginx-php composer install
	docker-compose exec nginx-php php artisan key:generate
	docker-compose exec nginx-php php artisan migrate:fresh --seed
