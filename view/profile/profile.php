<div id="section-one">
    <div class="container">
        <div class="row">
            <h4 class="text-center"><?= $message ?></h4>
            <div class="col-md-9">
                <h4>Information</h4>
                <dl>
                    <dt>Username</dt>
                    <dd><?= $user["username"] ?></dd>
                    <dt>Email</dt>
                    <dd><?= $user["email"] ?></dd>
                    <dt>Firstname</dt>
                    <dd><?= $user["firstname"] == null ? "Add to profile" : $user["firstname"] ?></dd>
                    <dt>Lastname</dt>
                    <dd><?= $user["lastname"] == null ? "Add to profile" : $user["lastname"] ?></dd>
                </dl>
                <h4>Database dump</h4>
                <p><?= var_dump($user) ?>
                <h4>Cookie dump</h4>
                <p><?= $app->cookie->dumpKey("username") ?></p>
            </div>
            <div class="col-md-3">
                <div class="list-group">
                    <a href="<?= $edit ?>" class="list-group-item">Edit User</a>
                    <a href="<?= $change ?>" class="list-group-item">Change Passowrd</a>
                    <a href="<?= $pages ?>" class="list-group-item">Pages</a>
                    <a href="<?= $posts ?>" class="list-group-item">Posts</a>
                    <a href="<?= $blocks ?>" class="list-group-item">Blocks</a>
                    <?php if ($user["username"] == "Admin") : ?>
                    <a href="<?= $admin ?>" class="list-group-item">Administration</a>
                    <?php endif; ?>
                    <a href="<?= $logout ?>" class="list-group-item">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>
