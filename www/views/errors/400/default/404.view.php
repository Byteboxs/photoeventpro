<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />

    <!-- CSS file -->
    <link rel="stylesheet" href="<?= APP_DIRECTORY_PATH ?>/public/static/css/404.style.min.css" />
    <link rel="shortcut icon" href="<?= APP_DIRECTORY_PATH ?>/public/static/img/copyright_logo.ico" type="image/x-icon" />

    <title>{{title}}</title>
</head>

<body>
    <div class="noise"></div>
    <div class="overlay"></div>
    <div class="terminal">
        <h1>Error <span class="errorcode">404</span></h1>
        <p class="output">La pagina que intenta acceder no existe</p>
        <p class="output">Por favor intente <a href="<?= APP_DIRECTORY_PATH . '/dashboard' ?>">Ir al dashboard</a>.</p>
        <p class="output">O si lo prefiere <a href="<?= APP_DIRECTORY_PATH . '/signin' ?>">Inicie sesion</a>.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tsparticles@2/tsparticles.bundle.min.js"></script>
</body>

</html>