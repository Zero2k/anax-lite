<div id="section-one">
    <div class="container">
        <div class="row">
            <h4 class="text-center"><?= $message ?></h4>
            <div class="col-md-6">
                <form action="<?= $login_user ?>" method="POST" class="form-signin">
                    <h2 class="form-signin-heading">Sign in</h2>
                    <label for="username" class="sr-only">Username</label>
                    <input type="username" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
                    <label for="password" class="sr-only">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                </form>
            </div>
            <div class="col-md-6">
                <form action="<?= $create_user ?>" method="POST" class="form-signin">
                    <h2 class="form-signin-heading">Sign up</h2>
                    <label for="username" class="sr-only">Username</label>
                    <input type="username" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
                    <label for="email" class="sr-only">Email adress</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email adress" required>
                    <label for="password" class="sr-only">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
                </form>
            </div>
        </div>
    </div>
</div>
