<div id="section-one">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1>Session</h1>
                        <h4>Session: <?= $value ?></h4>
                    </div>
                </div>
                <div class="btn-group" role="group" aria-label="...">
                    <a href="<?= $increment ?>" type="button" class="btn btn-success">Increase</<a>
                    <a href="<?= $decrement ?>" type="button" class="btn btn-warning">Decrease</a>
                    <a href="<?= $status ?>" type="button" class="btn btn-info">Status</a>
                    <a href="<?= $dump ?>" type="button" class="btn btn-default">Dump</a>
                    <a href="<?= $destroy ?>" type="button" class="btn btn-danger">Destroy</a>
                </div>
            </div>
        </div>
    </div>
</div>
