# MyKontato

Stack: PHP nativo, Apache, MySQL e Doctrine ORM.

## Pré-requisitos

- Docker Engine e Docker Compose v2 (`docker compose`).
- Porta 8000 livre para a aplicação (PHP). A porta do MySQL pode ser alterada.

## Configuração e inicialização

Na raiz do projeto, copie o exemplo para `.env`. `DB_HOST=127.0.0.1` é usado por ferramentas executadas diretamente no host; o Compose substitui por `db` no container PHP para que a aplicação alcance o banco pela rede Docker. `MYSQL_PORT` define a porta do MySQL publicada no host.

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

O serviço `db` cria `DB_DATABASE` na primeira inicialização e usa `DB_PASSWORD` como senha do usuário root do MySQL. Se já houver um volume MySQL inicializado com outro banco ou credenciais, a imagem não reaplica essas variáveis aos dados existentes.

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

