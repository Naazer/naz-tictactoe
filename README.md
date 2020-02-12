Naazer Tic Tac Toe Game
==========================

This is a Tic Tac Toe game built in PHP 7.2 with Symfony 5

# Installation 

### Requirements

- PHP 7.2
- Docker Compose (for docker usage)

Clone this repository

```bash
$ git clone git@github.com:Naazer/naz-tictactoe.git
```

Install all the dependencies using composer

```bash
$ composer install
```

# Run

### Using Symfony Server

If you are using Symfony server component: 

```bash
$ bin/console server:run
```

Now just go to `http://localhost:8000` and enjoy!

### Using Docker Compose

Docker Compose to build this application:

```bash
$ docker-compose up --build -d
```

# Test

To run the tests just access the path of project and run:

```bash
$ bin/phpunit
```
