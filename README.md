# Symfony

## Description
Here goes you project description

## Requirements
- docker
- docker-composer

## Installation
Next, put your symfony application into `docroot` folder and do not forget to add `Symfony.dev` in your `hosts` file.

Make sure you adjust `database_host` in your configuration file to the database container alias "db"

Then, run:

```bash
$ docker-compose up
```

You are done, you can visit your symfony application on the following URL: `http://Symfony.dev`

_Note :_ you can rebuild all Docker images by running:

```bash
$ docker-compose build
```
