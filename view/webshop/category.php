<div id="section-one">
    <div class="container">
        <div class="row">
            <h4 class="text-center"><?= $message ?></h4>
            <div class="col-md-9">
                <?= $html ?>
            </div>
            <div class="col-md-3">
                <div class="list-group">
                    <a href="<?= $overview ?>" class="list-group-item">Overview</a>
                    <a href="<?= $category ?>" class="list-group-item">Categories</a>
                    <a href="<?= $inventory ?>" class="list-group-item">Inventory</a>
                    <a href="<?= $inventory_log ?>" class="list-group-item">Inventory Log</a>
                </div>
            </div>
        </div>
    </div>
</div>
