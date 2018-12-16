## Environment requirement 

- Nginx 1.8+
- PHP 7.1+
- MySQL 5.7+
- Redis 3.0+

#### Composer

	composer install

#### Env

```
cp .env.example .env
```

#### Seeding

```shell
$ php artisan migrate --seed
```

#### Key generate

```shell
$ php artisan key:generate
```

#### Passport initial

```shell
$ php artisan passport:install