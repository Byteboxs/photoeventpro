<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">

    <div class="d-flex flex-column justify-content-center">
        <h4 class="mb-1"><?= $title ?></h4>
        <p class="mb-0"><?php // $subtitle 
                        ?></p>
    </div>
    <div class="d-flex align-content-center flex-wrap gap-4">
        <!-- <div class="d-flex gap-4"><button class="btn btn-label-secondary">Discard</button>
            <button class="btn btn-label-primary">Save draft</button>
        </div> -->
        <!-- <button type="submit" class="btn btn-warning rounded-1">
            <i class="far fa-plus-square mx-1"></i> Crear
        </button> -->
        <!-- <a href="<?= $actionLink ?>" class="btn <?= $actionClass ?>">
            <i class="<?= $icon ?> mx-2"></i> <?= $actionText ?>
        </a> -->
    </div>
</div>

<div class="card shadow-none border border-primary overflow-hidden">
    <h5 class="card-header">
        <i class="far fa-images"></i> Imagenes del cliente
    </h5>
    <div class="card-body">

        <?php if ($images) { ?>
            <?php foreach ($images as $image) { ?>
                <div class="row">
                    <div class="col-12">
                        <?= $image ?>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="alert alert-warning" role="alert">
                <i class="fas fa-exclamation-triangle"></i> Atencion, el cliente no tiene imagenes asociadas.
            </div>
        <?php } ?>
    </div>
</div>