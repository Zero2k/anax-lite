<div id="section-one">
    <div class="container">
        <div class="row">
            <h4 class="text-center"><?= $message ?></h4>
            <div class="col-md-12">
                <h4>Change password</h4>
                <form action="<?= $change_password ?>" method="POST">
                <div class="form-group">
                    <input type="hidden" name="username" value="<?= $username ?>" class="form-control" id="exampleInputUsername">
                </div>
                <div class="form-group">
                    <label for="exampleInputNewPassword">New Password</label>
                    <input type="password" name="newPassword" class="form-control" id="exampleInputNewPassword" placeholder="New password">
                </div>
                <div class="form-group">
                    <label for="exampleInputNewPassword2">Confirm New Password</label>
                    <input type="password" name="confirmPassword" class="form-control" id="exampleInputNewPassword" placeholder="Confirm New Password">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
