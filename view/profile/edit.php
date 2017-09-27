<div id="section-one">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>Edit Profile</h4>
                <form action="<?= $edit_user ?>" method="POST">
                <div class="form-group">
                    <input type="hidden" name="username" class="form-control" value="<?= $user["username"] ?>" id="exampleInputUsername" placeholder="Username">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail">Email address</label>
                    <input type="email" name="email" class="form-control" value="<?= $user["email"] ?>" id="exampleInputEmail" placeholder="Email address">
                </div>
                <div class="form-group">
                    <label for="exampleInputFirstname">Firstname</label>
                    <input type="text" name="firstname" class="form-control" value="<?= $user["firstname"] ?>" id="exampleInputFirstname" placeholder="Firstname">
                </div>
                <div class="form-group">
                    <label for="exampleInputLastname">Lastname</label>
                    <input type="text" name="lastname" class="form-control" value="<?= $user["lastname"] ?>" id="exampleInputLastname" placeholder="Lastname">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
