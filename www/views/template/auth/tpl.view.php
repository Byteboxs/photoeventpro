<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?></title>
    <meta name="description" content="<?= $description ?>">

    <link rel="apple-touch-icon" sizes="180x180" href="<?= $publicPath ?>/static/assets/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $publicPath ?>/static/assets/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $publicPath ?>/static/assets/favicon-16x16.png">
    <link rel="manifest" href="<?= $publicPath ?>/static/assets/site.webmanifest">

    <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
    <link disabled rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $tplPath ?>/assets/css/login.css">
</head>

<body>
    <div id="trash" data-valor-base64="<?= base64_encode(APP_DIRECTORY == '/' ? '' : APP_DIRECTORY)  ?>"></div>
    <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
        <div class="container py-4">
            <!-- <div class="card rounded-5 shadow"> -->
            <div class="card login-card">
                <div class="row">
                    <div class="col-md-5">
                        <img src="<?= $tplPath ?>/assets/images/login.jpg" alt="login" class="img-fluid d-none d-md-block login-card-img">
                    </div>
                    <div class="col-md-7">
                        <div class="card-body">
                            <div class="brand-wrapper">
                                <img src="<?= $tplPath ?>/assets/images/logo.svg" alt="logo" class="logo">
                            </div>
                            <h1 class="login-card-description"><?= $loginDescription ?></h1>
                            {{content}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="card login-card">
        <img src="assets/images/login.jpg" alt="login" class="login-card-img">
        <div class="card-body">
          <h2 class="login-card-title">Login</h2>
          <p class="login-card-description">Sign in to your account to continue.</p>
          <form action="#!">
            <div class="form-group">
              <label for="email" class="sr-only">Email</label>
              <input type="email" name="email" id="email" class="form-control" placeholder="Email">
            </div>
            <div class="form-group">
              <label for="password" class="sr-only">Password</label>
              <input type="password" name="password" id="password" class="form-control" placeholder="Password">
            </div>
            <div class="form-prompt-wrapper">
              <div class="custom-control custom-checkbox login-card-check-box">
                <input type="checkbox" class="custom-control-input" id="customCheck1">
                <label class="custom-control-label" for="customCheck1">Remember me</label>
              </div>              
              <a href="#!" class="text-reset">Forgot password?</a>
            </div>
            <input name="login" id="login" class="btn btn-block login-btn mb-4" type="button" value="Login">
          </form>
          <p class="login-card-footer-text">Don't have an account? <a href="#!" class="text-reset">Register here</a></p>
        </div>
      </div> -->
            <script>
                window.onload = function() {
                    const form = document.getElementById("myForm");
                    const viewElementResponse = document.getElementById("formResponses");
                    const formHandler = new FormHandler('myForm', '/autenticar', (response) => {
                        let alertType = "success";
                        let icon = "far fa-check-circle";
                        let msg = '';
                        if (response.status === "success") {
                            if (response.html) {
                                msg = response.html;
                            } else {
                                msg = response.message;
                            }

                            setTimeout(function() {
                                window.location.href = response.url;
                            }, 1000);
                        } else if (response.status === "fail") {
                            alertType = "warning";
                            icon = "fas fa-exclamation-triangle";
                            msg = response.message;
                            const botonEnviar = document.querySelector('button[type="submit"]');
                            botonEnviar.disabled = false;
                        } else {
                            alertType = "danger";
                            icon = "far fa-times-circle";
                            msg = response.message;
                        }
                        let responseDiv = document.getElementById("formResponses");
                        responseDiv.innerHTML = "";
                        const alert = BootstrapAlertFactory.createAlert({
                            message: msg,
                            type: alertType,
                            dismissible: true,
                            icon: icon,
                        });
                        responseDiv.appendChild(alert.generateAlert());
                    });
                    formHandler.setViewElementResponse(viewElementResponse);
                    formHandler.init();
                };
            </script>
        </div>
    </main>
    <script async src="<?= app\core\Application::$BASE_URL . APP_DIRECTORY ?>/public/static/js/toolbox.js"></script>
    <script async src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script async src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script async src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>