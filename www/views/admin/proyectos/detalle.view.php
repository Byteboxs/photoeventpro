<?php

use app\helpers\DateHelper;
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">

    <div class="d-flex flex-column justify-content-center">
    </div>
    <div class="d-flex align-content-center flex-wrap gap-4">
        <?= $linkPagoEfectivo ?>
        <?= $linkGestionServicios ?>
        <?= $linkVendedores ?>
    </div>
</div>
<div class="card shadow-lg border border-primary overflow-hidden my-3">
    <h5 class="card-header">
        Información
    </h5>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <dl class="row">
                    <dt class="col-sm-3 fw-bolder">Evento:</dt>
                    <dd class="col-sm-9"><?= $projectData->nombre ?></dd>
                    <dt class="col-sm-3 fw-bolder">Institución:</dt>
                    <dd class="col-sm-9"><?= $projectData->institucion ?></dd>
                    <dt class="col-sm-3 fw-bolder">Ubicación:</dt>
                    <dd class="col-sm-9"><?= $projectData->ubicacion ?></dd>
                    <dt class="col-sm-3 fw-bolder">Descripción:</dt>
                    <dd class="col-sm-9"><?= $projectData->descripcion ?></dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="row">
                    <dt class="col-sm-3 fw-bolder">Inicio:</dt>
                    <dd class="col-sm-9"><?= DateHelper::toDate($projectData->inicio) ?></dd>
                    <dt class="col-sm-3 fw-bolder">Hora:</dt>
                    <dd class="col-sm-9"><?= DateHelper::toAmPmTime($projectData->hora_ceremonia) ?></dd>
                    <dt class="col-sm-3 fw-bolder">Fin:</dt>
                    <dd class="col-sm-9"><?= DateHelper::toDate($projectData->fin) ?></dd>
                </dl>
            </div>
        </div>

    </div>
</div>
<div class="card overflow-hidden">
    <h5 class="card-header">
        <i class="fas fa-database"></i>
    </h5>
    <div class="table-responsive" id="contenedorTabla" style="max-height: 42vh; overflow: auto;">
        <?= $table->render(); ?>
    </div>
    <div class="card-footer text-muted">
        <?= $paginator->render(); ?>
    </div>
</div>