<form class="row needs-validation" id="myForm" novalidate>
    <?= $renderer->render($form) ?>
    <div id="formResponses"></div>
    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-outline-primary mb-5" id="login">Ingresar</button>
    </div>
</form>
<a href="#!" class="forgot-password-link">¿Ha olvidado su contraseña?</a>
<p class="login-card-footer-text">¿No tiene cuenta? <a href="#!" class="text-reset">Regístrese aquí</a></p>
<nav class="login-card-footer-nav">
    <a href="#!">Condiciones de uso.</a>
    <a href="#!">Política de privacidad</a>
</nav>