# MyKontato

Stack: PHP nativo, Apache, MySQL e Doctrine ORM.

## Pré-requisitos

- Docker Engine e Docker Compose v2 (`docker compose`).
- Porta 8000 livre para a aplicação (PHP). A porta do MySQL pode ser alterada.

## Configuração e inicialização

Na raiz do projeto, defina as senhas para `MYSQL_PASSWORD` e `MYSQL_ROOT_PASSWORD`.

```sh
cp .env.example .env
docker compose config
docker compose up -d --build
docker compose ps
```

## Dependências

Instale as dependências Composer dentro do container:

```sh
docker compose exec app composer install
```

## Extras

Acesse a página em [http://localhost:8000](http://localhost:8000).

Logs dos serviços:

```sh
docker compose logs -f app db
```

## Parar e remover

```sh
docker compose down
```

Para apagar também os dados:

```sh
docker compose down --volumes
```

Para remover tudo: 
```sh
docker compose down --volumes --rmi all --remove-orphans
```

