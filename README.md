# セットアップ

```shell script
cp .env.example .env
```

DBなどのセットアップ(実行前にコンテナ内でcomposer installは必要なので注意)

```shell script
# composer install
docker-compose run --rm app sh -c 'composer install'
docker-compose run --rm app sh -c 'composer server:roadrunner:setup'

# migration
docker-compose run --rm app sh -c 'php artisan migrate'

# seeder
docker-compose run --rm app sh -c 'php artisan db:seed'

# keys
docker-compose run --rm app sh -c 'php artisan key:generate && php artisan jwt:secret'
```
