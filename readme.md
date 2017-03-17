# Bulma Scaffolding for Laravel

[![Software License][ico-license]](LICENSE)
[![Build Status](https://travis-ci.org/rustymulvaney/bulma.svg?branch=master)](https://travis-ci.org/rustymulvaney/bulma)
[![StyleCI](https://styleci.io/repos/85115363/shield?branch=master)](https://styleci.io/repos/85115363)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rustymulvaney/bulma/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rustymulvaney/bulma/?branch=master)
[![Packagist](https://img.shields.io/packagist/v/rustymulvaney/bulma.svg?style=flat-square)](https://packagist.org/packages/rustymulvaney/bulma)

Generates scaffolding based on [Bulma](http://bulma.io/).  You also have the option to generate Auth scaffolding during the install. The Auth scaffolding uses [Vue](http://vuejs.org/) components.

| | |
|------------ | -------------|
<img src="https://rustymulvaney.github.io/assets/images/bulma/HomePage.png" width="300px"> | <img src="https://rustymulvaney.github.io/assets/images/bulma/Dashboard.png" width="300px">
<img src="https://rustymulvaney.github.io/assets/images/bulma/Login.png" width="200px"> | <img src="https://rustymulvaney.github.io/assets/images/bulma/Register.png" width="200px">

## Install

Via Composer

``` bash
$ composer require rustymulvaney/bulma
```

Add the service provider to `config/app.php`

``` php
rustymulvaney\bulma\BulmaServiceProvider::class,
```

## Usage

``` bash
$ php artisan bulma:install
```

This command will scaffold out your app using [Bulma](http://bulma.io/), and give you the option of generating Auth 
scaffolding that is similar to Laravel's default.  The following tasks will be completed:

- Install Node Modules
- Install Bulma (npm)
- Install Font Awesome (npm)
- Optionally install Auth scaffolding
- Compiles assets using Laravel Mix


## Security

If you discover any security related issues, please submit an issue.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

