# セットアップ

```shell script
cp .env.example .env
```

dockerのローカル開発環境は各自違うと思うのでdocker-compose.example.ymlから
docker-compose.ymlをコピーし適宜書き換えて以下を実行

```shell script
cp docker-compose.example.yml docker-compose.yml
# 書き換え不要ならそのまま実行
docker-compose up -d
```

db周りのコマンドを実行

```shell script
# migration
docker-compose exec app sh -c 'php artisan migrate'

# seeder
docker-compose exec app sh -c 'php artisan db:seed'
```
