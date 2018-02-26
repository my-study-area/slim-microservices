# Estudo Slim Microservices

via Docker
$ docker-compose run composer [update|require|…]

Reload composer.json: $ docker-compose run composer dump-autoload

$docker-compose run php php --version
$docker-compose build php
$docker-compose.exe run php vendor/bin/doctrine orm:schema-tool:update --force #cria tabela no banco
Obs: verificar o arquivo bootstrap.php e verificar usuário, senha e host
