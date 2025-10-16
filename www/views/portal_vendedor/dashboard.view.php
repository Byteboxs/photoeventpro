<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
    <div class="d-flex flex-column justify-content-center">
        <h4 class="mb-1"><?= $title ?></h4>
        <p class="mb-0"><?= $subtitle ?></p>
    </div>
    <div class="d-flex align-content-center flex-wrap gap-4">
    </div>
</div>

<div class="card mb-5">
    <div class="d-flex align-items-start row">
        <div class="col-sm-8">
            <div class="card-body">
                <h5 class="card-title text-primary mb-3">Bienvenido de nuevo 🎉</h5>
                <p class="mb-6">¡Bienvenido al panel de administración de ventas!</p>

                <a href="<?= $appPath ?>/portal/vendedor/eventos" class="btn btn-label-success">
                    <i class="fas fa-project-diagram mx-2"></i> Mis eventos
                </a>
            </div>
        </div>
        <div class="col-sm-4 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-6">
                <img src="<?= $imgPath ?>/man-with-laptop.png" height="175" class="scaleX-n1-rtl" alt="View Badge User">
            </div>
        </div>
    </div>
</div>