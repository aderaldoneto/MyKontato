# MyKontato

Agenda de contatos feita em PHP, com Apache, MySQL e Doctrine.

## Para rodar

Você vai precisar do Docker Engine e do Docker Compose v2. A porta 8000 precisa estar livre.

Na pasta do projeto, copie o arquivo de configuração e suba os containers:

```sh
cp .env.example .env
docker compose up -d --build
```

Instale as dependências e crie as tabelas do banco:

```sh
docker compose exec app composer install
docker compose exec app vendor/bin/doctrine-migrations migrate --configuration=migrations.php --db-configuration=migrations-db.php
```

Abra [http://localhost:8000](http://localhost:8000) para acessar a agenda.

As migrações criam o usuário `admin@admin.com`, com a senha `123456789`, e adicionam 30 contatos de exemplo. Essa conta é para testar o projeto localmente; troque a senha antes de usar a aplicação em outro ambiente.

## Testes

Com os containers ligados, rode:

```sh
docker compose exec app composer test
```

Os testes usam SQLite em memória, então não precisam do banco MySQL.

## Outros comandos

Ver os logs:

```sh
docker compose logs -f app db
```

Parar os containers:

```sh
docker compose down
```

Apagar os containers e os dados do banco:

```sh
docker compose down --volumes
```
