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
        "pages" => $app->url->create("pages"),
        "posts" => $app->url->create("posts"),
        "blocks" => $app->url->create("blocks"),
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

/* CONTENT */

$app->router->add("pages", function () use ($app) {
    // Check if user is logged in otherwise redirect to login
    if (!$app->session->has("username")) {
        $app->response->redirect($app->url->create('login'));
    }
    $username = $app->session->get("username");
    $createContent = $app->url->create("create-content");
    $editContent = $app->url->create("edit-content");

    $sql = "SELECT * FROM content WHERE type=?";
    $resultset = $app->db->executeFetchAll($sql, ["page"]);
    $html = $app->content->showContent($resultset);

    $currentDate = date('Y-m-d H:i:s');

    if (isset($_GET["preview"])) {
        $id = $_GET["preview"];

        $sql = "SELECT * FROM content WHERE id=?";
        $resultset = $app->db->executeFetchAll($sql, [$id]);

        foreach ($resultset as $res) {
            $title = $res->title;
            $filter = $res->filter;
            $data = $app->filter->doFilter($res->data, $filter);
            $createdAt = $res->created;
            $type = $res->type;
        }

        $html = $app->content->previewContent($title, $data, $createdAt, $type, $filter);
    }

    if (isset($_GET["add"])) {
        $type = "page";
        $html = $app->content->addContent($createContent, $currentDate, $type);
    }

    if (isset($_GET["edit"])) {
        $id = $_GET["edit"];
        $sql = "SELECT * FROM content WHERE id=?";
        $resultset = $app->db->executeFetchAll($sql, [$id]);

        $html = $app->content->editContent($resultset, $editContent, $currentDate);
    }

    if (isset($_GET["delete"])) {
        $id = $_GET["delete"];
        $sql = "UPDATE content SET deleted='1' WHERE id='$id'";
        $app->db->execute($sql);

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    $app->view->add("take1/header", ["title" => "Add or Edit Pages"]);
    $app->view->add("navbar2/navbar");
    $app->view->add("take1/intro", ["title" => "Add or Edit Pages", "intro" => "Add new page."]);
    $app->view->add("profile/pages", [
            "html" => $html,
            "message" => $app->session->getOnce("flash")
        ]);
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

$app->router->add("posts", function () use ($app) {
    // Check if user is logged in otherwise redirect to login
    if (!$app->session->has("username")) {
        $app->response->redirect($app->url->create('login'));
    }
    $username = $app->session->get("username");
    $createContent = $app->url->create("create-content");
    $editContent = $app->url->create("edit-content");

    $sql = "SELECT * FROM content WHERE type=?";
    $resultset = $app->db->executeFetchAll($sql, ["post"]);
    $html = $app->content->showContent($resultset);

    $currentDate = date('Y-m-d H:i:s');

    if (isset($_GET["preview"])) {
        $id = $_GET["preview"];

        $sql = "SELECT * FROM content WHERE id=?";
        $resultset = $app->db->executeFetchAll($sql, [$id]);

        foreach ($resultset as $res) {
            $title = $res->title;
            $filter = $res->filter;
            $data = $app->filter->doFilter($res->data, $filter);
            $createdAt = $res->created;
            $type = $res->type;
        }

        $html = $app->content->previewContent($title, $data, $createdAt, $type, $filter);
    }

    if (isset($_GET["add"])) {
        $type = "post";
        $html = $app->content->addContent($createContent, $currentDate, $type);
    }

    if (isset($_GET["edit"])) {
        $id = $_GET["edit"];
        $sql = "SELECT * FROM content WHERE id=?";
        $resultset = $app->db->executeFetchAll($sql, [$id]);

        $html = $app->content->editContent($resultset, $editContent, $currentDate);
    }

    if (isset($_GET["delete"])) {
        $id = $_GET["delete"];
        $sql = "UPDATE content SET deleted='1' WHERE id='$id'";
        $app->db->execute($sql);

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    $app->view->add("take1/header", ["title" => "Add or Edit Posts"]);
    $app->view->add("navbar2/navbar");
    $app->view->add("take1/intro", ["title" => "Add or Edit Posts", "intro" => "Add new post."]);
    $app->view->add("profile/posts", [
            "html" => $html,
            "message" => $app->session->getOnce("flash")
        ]);
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

$app->router->add("blocks", function () use ($app) {
    // Check if user is logged in otherwise redirect to login
    if (!$app->session->has("username")) {
        $app->response->redirect($app->url->create('login'));
    }
    $username = $app->session->get("username");
    $createContent = $app->url->create("create-content");
    $editContent = $app->url->create("edit-content");

    $sql = "SELECT * FROM content WHERE type=?";
    $resultset = $app->db->executeFetchAll($sql, ["block"]);
    $html = $app->content->showContent($resultset);

    $currentDate = date('Y-m-d H:i:s');

    if (isset($_GET["preview"])) {
        $id = $_GET["preview"];

        $sql = "SELECT * FROM content WHERE id=?";
        $resultset = $app->db->executeFetchAll($sql, [$id]);

        foreach ($resultset as $res) {
            $title = $res->title;
            $filter = $res->filter;
            $data = $app->filter->doFilter($res->data, $filter);
            $createdAt = $res->created;
            $type = $res->type;
        }

        $html = $app->content->previewContent($title, $data, $createdAt, $type, $filter);
    }

    if (isset($_GET["add"])) {
        $type = "block";
        $html = $app->content->addContent($createContent, $currentDate, $type);
    }

    if (isset($_GET["edit"])) {
        $id = $_GET["edit"];
        $sql = "SELECT * FROM content WHERE id=?";
        $resultset = $app->db->executeFetchAll($sql, [$id]);

        $html = $app->content->editContent($resultset, $editContent, $currentDate);
    }

    if (isset($_GET["delete"])) {
        $id = $_GET["delete"];
        $sql = "UPDATE content SET deleted='1' WHERE id='$id'";
        $app->db->execute($sql);

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    $app->view->add("take1/header", ["title" => "Add or Edit Blocks"]);
    $app->view->add("navbar2/navbar");
    $app->view->add("take1/intro", ["title" => "Add or Edit Blocks", "intro" => "Add new block."]);
    $app->view->add("profile/blocks", [
            "html" => $html, 
            "message" => $app->session->getOnce("flash")
        ]);
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

/* CONTENT */

$app->router->add("create-content", function () use ($app) {
    $title = isset($_POST["title"]) ? htmlentities($_POST["title"]) : null;
    $path = isset($_POST["path"]) ? htmlentities($_POST["path"]) : null;
    $slug = $app->content->slugify($title);
    $data = isset($_POST["data"]) ? htmlentities($_POST["data"]) : null;
    $published = isset($_POST["published"]) ? htmlentities($_POST["published"]) : null;
    $type = isset($_POST["type"]) ? htmlentities($_POST["type"]) : null;
    $filter = isset($_POST["filter"]) ? implode(',', $_POST["filter"]) : null;

    if (!$path) {
        $path = null;
    }

    if (!$data) {
        $app->session->set("flash", "Error, you need to enter some text!");
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        $sql = "INSERT INTO content (title, path, slug, data, published, type, filter) VALUES (?, ?, ?, ?, ?, ?, ?)";  
        $params = [$title, $path, $slug, $data, $published, $type, $filter];

        try {
            $app->db->execute($sql, $params);
        } catch (PDOException $e) {
            // 1062
            if ($e->errorInfo[0] == '23000' && $e->errorInfo[1] == '1062') {
                $app->session->set("flash", "Error - Title must be unique.");
            }
        }

        $app->response->redirect($app->url->create('profile'));
    }
});

$app->router->add("edit-content", function () use ($app) {
    $id = isset($_POST["id"]) ? htmlentities($_POST["id"]) : null;
    $title = isset($_POST["title"]) ? htmlentities($_POST["title"]) : null;
    $slug = $app->content->slugify($title);
    $path = isset($_POST["path"]) ? htmlentities($_POST["path"]) : null;
    $data = isset($_POST["data"]) ? htmlentities($_POST["data"]) : null;
    $published = isset($_POST["published"]) ? htmlentities($_POST["published"]) : null;
    $updated = isset($_POST["updated"]) ? htmlentities($_POST["updated"]) : null;
    $filter = isset($_POST["filter"]) ? htmlentities($_POST["filter"]) : null;
    $deleted = isset($_POST["deleted"]) ? htmlentities($_POST["deleted"]) : null;

    if (!$path) {
        $path = null;
    }

    $sql = "UPDATE content SET title=?, slug=?, path=?, data=?, published=?, updated=NOW(), filter=?, deleted=? WHERE id=?";
    $params = [$title, $slug, $path, $data, $published, $filter, $deleted, $id];

    try {
        $app->db->execute($sql, $params);
    } catch (PDOException $e) {
        if ($e->errorInfo[0] == '23000' && $e->errorInfo[1] == '1062') {
            $app->session->set("flash", "Error - Title and Path can't be same as other pages.");
        }
    }

    $app->response->redirect($app->url->create('profile'));
});

/* USER & PROFILE */

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
