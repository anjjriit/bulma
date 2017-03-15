<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Bulma Template</title>

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
</head>
<body>

<section id="welcome" class="hero is-fullheight">

    @if (Route::has('login'))
        <div class="hero-head">
            <header class="nav">
                <div class="container">
                    <div class="nav-right nav-menu">
                        <a href="/login" class="nav-item">
                            Login
                        </a>
                        <a href="/register" class="nav-item">
                            Register
                        </a>
                    </div>
                </div>
            </header>
        </div>
    @endif

    <div class="hero-body">
        <div class="container">
            <div class="columns is-vcentered">
                <div class="column has-text-centered">
                    <h1 class="title is-1">
                        Bulma Template
                    </h1>
                    <div id="links" class="title is-6">
                        <a href="#">Documentation</a>
                        <a href="https://github.com/rustymulvaney/bulma" target="_blank">Github</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

</body>
</html>