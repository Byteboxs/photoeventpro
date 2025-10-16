<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">

    <div class="d-flex flex-column justify-content-center">
        <h4 class="mb-1">Gestionar eventos</h4>
        <p class="mb-0">Lorem ipsum</p>
    </div>
    <div class="d-flex align-content-center flex-wrap gap-4">
        <!-- <div class="d-flex gap-4"><button class="btn btn-label-secondary">Discard</button>
            <button class="btn btn-label-primary">Save draft</button>
        </div> -->
        <!-- <button type="submit" class="btn btn-warning rounded-1">
            <i class="far fa-plus-square mx-1"></i> Crear
        </button> -->
        <a href="<?= $appPath ?>/crear-proyecto" class="btn btn-label-warning">
            <i class="fas fa-project-diagram mx-2"></i> Cree un evento
        </a>
    </div>

</div>

<div class="card overflow-hidden">
    <h5 class="card-header"><i class="fas fa-database"></i></h5>
    <div class="table-responsive">
        <?= $table->render(); ?>
    </div>
    <div class="card-footer text-muted">
        <?= $paginator->render(); ?>
    </div>
</div>