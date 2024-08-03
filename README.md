<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Passo-a-passo - Instalação do projeto
0. Requisitos : Ter em sua máquina php >= 8.2, COMPOSER e NPM. 
1. Em sua pasta raiz, clone o arquivo do projeto usando **git clone** [https://github.com/Gustavo483/BackEndProjetoUnb-BD.git](https://github.com/Gustavo483/trabalho-redes-2024.git)
2. Abra a pasta do projeto em seu terminal e execute o comando:

```sh
composer install
```

4. Na pasta do projeto, crie um arquivo `.env`, no escopo do projeto existe um arquivo chamado `.env.example` onde basta renomea-lo para `.env`. Após isso gere a chave para este projeto usando o comando:

```sh
php artisan key:generate
```

5. Em seguida, compile os dados de CSS e JS usando os comando:

```sh
npm install && npm run dev
```

6. Nesse repositório existe um arquivo chamado EstruturaBancoTrabalhoBD.sql use-o para criar seu banco de dados no SGBD de sua escolha. No arquivo `.env` que foi criado, estabeleça a conexão com seu banco de dados:

```sh
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=NAMEDB
DB_USERNAME=DB_USERNAME
DB_PASSWORD=DB_PASSWORD
```

Por último, execute os comandos abaixo para servir a aplicação. OBS : Será necessário abrir um prompt de comando para cada comando.

```sh
php artisan serve
```
```sh
php artisan queue:work
```
```sh
php artisan reverb:start
```
