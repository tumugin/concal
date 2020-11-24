# セットアップ

```shell script
cp .env.example .env
```

dockerのローカル開発環境は各自違うと思うのでdocker-compose.example.ymlから
docker-compose.ymlをコピーもしくはリンクし適宜書き換えて以下を実行。

```shell script
# いろいろ書き変えたいなら
cp docker-compose.example.yml docker-compose.yml
# 書き換え不要ならシンボリックリンクを張る
ln -s docker-compose.example.yml docker-compose.yml

docker-compose up -d
```

DBなどのセットアップ(実行前にコンテナ内でcomposer installは必要なので注意)

```shell script
# composer install
docker-compose run --rm app sh -c 'composer install'

# migration
docker-compose run --rm app sh -c 'php artisan migrate'

# seeder
docker-compose run --rm app sh -c 'php artisan db:seed'

# keys
docker-compose run --rm app sh -c 'php artisan key:generate'
```
