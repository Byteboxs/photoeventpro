<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
    <div class="d-flex flex-column justify-content-center">
        <h4 class="mb-1"><?= $title ?></h4>
        <p class="mb-0"><?= $subtitle ?></p>
    </div>
    <div class="d-flex align-content-center flex-wrap gap-4">
        <a href="<?= $actionLink ?>" class="btn <?= $actionClass ?>">
            <i class="<?= $icon ?> mx-2"></i> <?= $actionText ?>
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