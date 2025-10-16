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
        <a href="<?= $actionLink ?>" class="btn <?= $actionClass ?>">
            <i class="<?= $icon ?> mx-2"></i> <?= $actionText ?>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-sm-8">
        <div class="card border border-info overflow-hidden">
            <h5 class="card-header">
                Información
            </h5>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Categoria:</dt>
                    <dd class="col-sm-9"><?= $producto->categoria ?></dd>
                    <dt class="col-sm-3">Servicio:</dt>
                    <dd class="col-sm-9"><?= $producto->nombre ?></dd>
                    <dt class="col-sm-3">Descripcion:</dt>
                    <dd class="col-sm-9"><?= $producto->descripcion ?></dd>
                    <dt class="col-sm-3">Número maximo de imagenes:</dt>
                    <dd class="col-sm-9"><?= $producto->max_fotos ?></dd>
                    <dt class="col-sm-3">Estado</dt>
                    <dd class="col-sm-9"><?= $producto->estado ?></dd>
                </dl>
            </div>
            <div class="card-footer text-muted">
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border border-success overflow-hidden">
            <h5 class="card-header">
                Precio
            </h5>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">PVP:</dt>
                    <dd class="col-sm-9"><?= $precio ?></dd>

                </dl>
            </div>
            <div class="card-footer text-muted">
            </div>
        </div>
    </div>
</div>