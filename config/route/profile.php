<?php

$app->router->add("login", function () use ($app) {
    // Check if user is logged in
    if ($app->session->has("username")) {
        $app->response->redirect($app->url->create('profile'));
    }

    $app->view->add("take1/header", ["title" => "Login"]);
    $app->view->add("navbar2/navbar");
    $app->view->add("take1/intro", ["title" => "Login", "intro" => "Login to view profile"]);
    $app->view->add("profile/login", [
        "create_user" => $app->url->create("create-user"),
        "login_user" => $app->url->create("login-user"),
        "message" => $app->session->getOnce("flash")
    ]);
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

$app->router->add("profile", function () use ($app) {
    // Check if user is logged in otherwise redirect to login
    if (!$app->session->has("username")) {
        $app->response->redirect($app->url->create('login'));
    }
    $username = $app->session->get("username");

    $app->view->add("take1/header", ["title" => "Profile"]);
    $app->view->add("navbar2/navbar");
    $app->view->add("take1/intro", ["title" => "Profile", "intro" => "Welcome to your profie: {$username}"]);
    $app->view->add("profile/profile", [
        "user" => $app->db->getUser($username),
        "logout" => $app->url->create("logout"),
        "edit" => $app->url->create("edit"),
        "change" => $app->url->create("change"),
        "admin" => $app->url->create("admin"),
        "message" => $app->session->getOnce("flash")
    ]);
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

$app->router->add("edit", function () use ($app) {
    // Check if user is logged in otherwise redirect to login
    if (!$app->session->has("username")) {
        $app->response->redirect($app->url->create('login'));
    }
    $username = $app->session->get("username");

    $app->view->add("take1/header", ["title" => "Edit Profile"]);
    $app->view->add("navbar2/navbar");
    $app->view->add("take1/intro", ["title" => "Edit Profile", "intro" => "Change profie: {$username}"]);
    $app->view->add("profile/edit", [
        "user" => $app->db->getUser($username),
        "edit_user" => $app->url->create("edit-user")
    ]);
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

$app->router->add("change", function () use ($app) {
    // Check if user is logged in otherwise redirect to login
    if (!$app->session->has("username")) {
        $app->response->redirect($app->url->create('login'));
    }
    $username = $app->session->get("username");

    $app->view->add("take1/header", ["title" => "Change Password"]);
    $app->view->add("navbar2/navbar");
    $app->view->add("take1/intro", ["title" => "Change Password", "intro" => "Change password for profie: {$username}"]);
    $app->view->add("profile/change-password", [
        "username" => $app->session->get("username"),
        "change_password" => $app->url->create("change-password"),
        "message" => $app->session->getOnce("flash")
    ]);
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

$app->router->add("admin", function () use ($app) {
    // Check if user is logged in and admin
    if ($app->session->has("username") && $app->session->get("username") == "Admin") {
        // Routes
        $editUser = $app->url->create("edit-user");
        $addUser = $app->url->create("create-user");
        $changePassword = $app->url->create("change-password");
        // Default values
        $limit = 3;
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;
        $orderby = isset($_GET["orderby"]) ? $_GET["orderby"] : "username";
        $order = isset($_GET["order"]) ? $_GET["order"] : "ASC";
        $query = isset($_GET["search"]) ? $_GET["search"] : "";
        $total = ceil($app->db->getMax() / $limit); 
        // Default
        $html = $app->admin->showUsers($app->db->searchUsers($query, $limit, $page, $orderby, $order), $total, $limit, $query, $page);

        if (isset($_GET["limit"])) {
            $limit = $_GET["limit"];
            $total = ceil($app->db->getMax() / $limit); 
            $html = $app->admin->showUsers($app->db->searchUsers($query, $limit, $page, $orderby, $order), $total, $limit, $query, $page);
        }

        if (isset($_GET["add"])) {
            $html = $app->admin->addUser($addUser);
        }

        if (isset($_GET["delete"])) {
            $username = $_GET["delete"];
            $app->db->deleteUser($username);
            $app->session->set("flash", "Deleted: {$username}!");
            $app->response->redirect($app->url->create('admin'));
        }

        if (isset($_GET["edit"])) {
            $username = $_GET["edit"];
            $user = $app->db->getUser($username); 
            $html = $app->admin->editUser($user, $editUser);
        }

        if (isset($_GET["password"])) {
            $username = $_GET["password"];
            $user = $app->db->getUser($username);
            $html = $app->admin->changePassword($user, $changePassword);
        }

        if (isset($_GET["search"])) {
            $query = $_GET["search"];
            $html = $app->admin->showUsers($app->db->searchUsers($query, $limit, $page, $orderby, $order), $total, $limit, $query, $page);
        }

        $app->view->add("take1/header", ["title" => "Administration"]);
        $app->view->add("navbar2/navbar");
        $app->view->add("take1/intro", ["title" => "Administration", "intro" => "Manage users, etc"]);
        $app->view->add("admin/admin", [
            "html" => $html,
            "message" => $app->session->getOnce("flash")
        ]);
        $app->view->add("take1/footer");
        $app->response->setBody([$app->view, "render"])
                ->send();
    } else {
        // FALSE
        $app->response->redirect($app->url->create('profile'));
    }
});

$app->router->add("create-user", function () use ($app) {
    $username = isset($_POST["username"]) ? htmlentities($_POST["username"]) : null;
    $email = isset($_POST["email"]) ? htmlentities($_POST["email"]) : null;
    $password = isset($_POST["password"]) ? htmlentities($_POST["password"]) : null;

    if ($username != null && $email != null && $password != null) {
        // Check if username is unique
        if (!$app->db->exists($username)) {
            $app->db->createUser($username, $email, $password);
            if (!$app->session->get("username") == "Admin") {
                // Prevent set session if user is Admin
                $app->session->set("username", $username);
                $app->cookie->set("username", $username);
            }
            $app->response->redirect($app->url->create('profile'));
        } else {
            // Username alredy exists in the database
            $app->response->redirect($app->url->create('login'));
        }
    } else {
        // Username, email or password not provided
        $app->response->redirect($app->url->create("login"));
    }
});

$app->router->add("login-user", function () use ($app) {
    $username = isset($_POST["username"]) ? htmlentities($_POST["username"]) : null;
    $password = isset($_POST["password"]) ? htmlentities($_POST["password"]) : null;

    if ($username != null && $password != null) {
        // See if username exists in db
        if ($app->db->exists($username)) {
            $hashPassword = $app->db->getHash($username);

            // Check if password match against hashPassword
            if (password_verify($password, $hashPassword)) {
                // Add username to session
                $app->session->set("username", $username);
                // TEST ADD USERNAME TO COOKIE
                $app->cookie->set("username", $username);
                $app->session->set("flash", "Welcome {$username}!");
                $app->response->redirect($app->url->create('profile'));
            } else {
                // Wrong password!
                $app->session->set("flash", "Looks like you entered wrong password.");
                $app->response->redirect($app->url->create('login'));
            }
        } else {
            // No user with that username
            $app->session->set("flash", "No user with that username.");
            $app->response->redirect($app->url->create('login'));
        }
    } else {
        // Username or Password not provided
        $app->session->set("flash", "Please enter both username and password.");
        $app->response->redirect($app->url->create("login"));
    }
});

$app->router->add("edit-user", function () use ($app) {
    $username = isset($_POST["username"]) ? htmlentities($_POST["username"]) : null;
    $email = isset($_POST["email"]) ? htmlentities($_POST["email"]) : null;
    $firstname = isset($_POST["firstname"]) ? htmlentities($_POST["firstname"]) : null;
    $lastname = isset($_POST["lastname"]) ? htmlentities($_POST["lastname"]) : null;

    $app->db->changeUser($username, $email, $firstname, $lastname);
    $app->session->set("flash", "The change was saved to the database.");
    $app->response->redirect($app->url->create('profile'));

});

$app->router->add("change-password", function () use ($app) {
    $username = isset($_POST["username"]) ? htmlentities($_POST["username"]) : null;
    $newPassword = isset($_POST["newPassword"]) ? htmlentities($_POST["newPassword"]) : null;
    $confirmPassword = isset($_POST["confirmPassword"]) ? htmlentities($_POST["confirmPassword"]) : null;

    if ($newPassword != null && $confirmPassword != null) {
        if ($app->db->exists($username)) {
            if ($newPassword != $confirmPassword) {
                $app->session->set("flash", "Error, password didn't match.");
                $app->response->redirect($app->url->create('change'));
            } else {
                $app->session->set("flash", "The password has been changed.");
                $app->db->changePassword($username, $newPassword);
                $app->response->redirect($app->url->create('profile'));
            }
        }
    } else {
        $app->session->set("flash", "Fields can't be empty.");
        $app->response->redirect($app->url->create('change'));
    }
});

$app->router->add("logout", function () use ($app) {
    // Check if someone is logged in
    if ($app->session->has("username")) {
        $app->session->delete("username");
        if ($app->cookie->has("username")) {
            $app->cookie->delete("username");
            $app->response->redirect($app->url->create('login'));
        }
        $app->response->redirect($app->url->create('login'));
    } else {
        $app->response->redirect($app->url->create('login'));
        echo "<p>No active user.</p>";
        die();
    }
});
