<?php
/**
 * Routes for webshop.
 */
$app->router->add("webshop", function () use ($app) {
    // Check if user is logged in otherwise redirect to login
    if ($app->session->has("username") && $app->session->get("username") == "Admin") {
        // Routes
        $overview = $app->url->create("webshop");
        $category = $app->url->create("category");
        $inventory = $app->url->create("inventory");
        $addProduct = $app->url->create("add-product");
        $editProduct = $app->url->create("edit-product");
        $inventory_log = $app->url->create("inventory-log");
        // Default View
        $sql = "SELECT * FROM ViewInventory";
        $resultset = $app->db->executeFetchAll($sql);
        $html = $app->webshop->productsList($resultset);

        // Paths
        if (isset($_GET["preview"])) {
            $id = $_GET["preview"];
            $sql = "SELECT * FROM ViewProduct WHERE id=?";
            $resultset = $app->db->executeFetchAll($sql, [$id]);
            $html = $app->webshop->productPreview($resultset);
        }

        if (isset($_GET["add"])) {
            $sql = "SELECT * FROM kmom05_category";
            $categories = $app->db->executeFetchAll($sql);
            $html = $app->webshop->addProduct($addProduct, $categories);
        }

        if (isset($_GET["edit"])) {
            $id = $_GET["edit"];
            // Get products
            $sql_1 = "SELECT * FROM ViewProduct WHERE id=?";
            $resultset = $app->db->executeFetchAll($sql_1, [$id]);
            // Get categories
            $sql_2 = "SELECT * FROM kmom05_category ORDER BY id";
            $categories = $app->db->executeFetchAll($sql_2);
            $html = $app->webshop->editProduct($editProduct, $resultset, $categories);
        }

        if (isset($_GET["delete"])) {
            $id = htmlentities($_GET["delete"]);
            $sql = "CALL deleteProduct($id);";
            $app->db->execute($sql);
            $app->session->set("flash", "Product was deleted.");
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }

        $app->view->add("take1/header", ["title" => "Webshop"]);
        $app->view->add("navbar2/navbar");
        $app->view->add("take1/intro", ["title" => "Manage Webshop", "intro" => "Add, Edit or Remove Products from the Webshop"]);
        $app->view->add("webshop/webshop", [
            "html" => $html,
            "overview" => $overview,
            "category" => $category,
            "inventory" => $inventory,
            "inventory_log" => $inventory_log,
            "message" => $app->session->getOnce("flash")
        ]);
        $app->view->add("take1/footer");
        $app->response->setBody([$app->view, "render"])
                  ->send();
    } else {
        $app->response->redirect($app->url->create('login'));
    }
});

$app->router->add("category", function () use ($app) {
    // Check if user is logged in otherwise redirect to login
    if ($app->session->has("username") && $app->session->get("username") == "Admin") {
        // Routes
        $overview = $app->url->create("webshop");
        $category = $app->url->create("category");
        $inventory = $app->url->create("inventory");
        $addCategory = $app->url->create("add-category");
        $editCategory = $app->url->create("edit-category");
        $inventory_log = $app->url->create("inventory-log");
        // Default View
        $sql = "SELECT * FROM kmom05_category ORDER BY id";
        $resultset = $app->db->executeFetchAll($sql);
        $html = $app->webshop->categoryList($resultset);

        // Paths
        if (isset($_GET["add"])) {
            $html = $app->webshop->addCategory($addCategory);
        }

        if (isset($_GET["edit"])) {
            $id = $_GET["edit"];
            $sql = "SELECT * FROM kmom05_category WHERE id=?";
            $resultset = $app->db->executeFetchAll($sql, [$id]);
            $html = $app->webshop->editCategory($editCategory, $resultset);
        }

        if (isset($_GET["delete"])) {
            $id = htmlentities($_GET["delete"]);
            $sql = "CALL deleteCategory($id);";
            $app->db->execute($sql);
            $app->session->set("flash", "Category was deleted.");
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }

        $app->view->add("take1/header", ["title" => "Categories"]);
        $app->view->add("navbar2/navbar");
        $app->view->add("take1/intro", ["title" => "Manage Categories", "intro" => "Add, Edit or Remove Categories from the Webshop"]);
        $app->view->add("webshop/category", [
            "html" => $html,
            "overview" => $overview,
            "category" => $category,
            "inventory" => $inventory,
            "inventory_log" => $inventory_log,
            "message" => $app->session->getOnce("flash")
        ]);
        $app->view->add("take1/footer");
        $app->response->setBody([$app->view, "render"])
                  ->send();
    } else {
        $app->response->redirect($app->url->create('login'));
    }
});

$app->router->add("inventory", function () use ($app) {
    // Check if user is logged in otherwise redirect to login
    if ($app->session->has("username") && $app->session->get("username") == "Admin") {
        // Routes
        $overview = $app->url->create("webshop");
        $category = $app->url->create("category");
        $inventory = $app->url->create("inventory");
        $editInventory = $app->url->create("edit-inventory");
        $inventory_log = $app->url->create("inventory-log");
        // Default View
        $sql = "SELECT * FROM ViewInventory";
        $resultset = $app->db->executeFetchAll($sql);
        $html = $app->webshop->inventoryList($resultset);

        if (isset($_GET["edit"])) {
            $id = $_GET["edit"];
            $sql = "SELECT * FROM kmom05_inventory WHERE product_id=?";
            $resultset = $app->db->executeFetchAll($sql, [$id]);
            $html = $app->webshop->editInventory($editInventory, $resultset);
        }

        $app->view->add("take1/header", ["title" => "Inventory"]);
        $app->view->add("navbar2/navbar");
        $app->view->add("take1/intro", ["title" => "Manage Inventory", "intro" => "Add, Edit or Remove Inventory from the Webshop"]);
        $app->view->add("webshop/inventory", [
            "html" => $html,
            "overview" => $overview,
            "category" => $category,
            "inventory" => $inventory,
            "inventory_log" => $inventory_log,
            "message" => $app->session->getOnce("flash")
        ]);
        $app->view->add("take1/footer");
        $app->response->setBody([$app->view, "render"])
                  ->send();
    } else {
        $app->response->redirect($app->url->create('login'));
    }
});

$app->router->add("inventory-log", function () use ($app) {
    // Check if user is logged in otherwise redirect to login
    if ($app->session->has("username") && $app->session->get("username") == "Admin") {
        // Routes
        $overview = $app->url->create("webshop");
        $category = $app->url->create("category");
        $inventory = $app->url->create("inventory");
        $inventory_log = $app->url->create("inventory-log");
        // Default View
        $sql = "SELECT * FROM kmom05_inventoryLog";
        $resultset = $app->db->executeFetchAll($sql);
        $html = $app->webshop->inventoryLog($resultset);

        $app->view->add("take1/header", ["title" => "Inventory Log"]);
        $app->view->add("navbar2/navbar");
        $app->view->add("take1/intro", ["title" => "Inventory Log", "intro" => "See all changes made to the inventory"]);
        $app->view->add("webshop/inventory-log", [
            "html" => $html,
            "overview" => $overview,
            "category" => $category,
            "inventory" => $inventory,
            "inventory_log" => $inventory_log,
            "message" => $app->session->getOnce("flash")
        ]);
        $app->view->add("take1/footer");
        $app->response->setBody([$app->view, "render"])
                  ->send();
    } else {
        $app->response->redirect($app->url->create('login'));
    }
});

/* PROCESSING ROUTES */

$app->router->add("add-product", function () use ($app) {
    // Check if user is logged in otherwise redirect to login
    if (!$app->session->has("username")) {
        $app->response->redirect($app->url->create('login'));
    }

    $url = isset($_POST["url"]) ? htmlentities($_POST["url"]) : null;
    $title = isset($_POST["title"]) ? htmlentities($_POST["title"]) : null;
    $description = isset($_POST["description"]) ? htmlentities($_POST["description"]) : null;
    $price = isset($_POST["price"]) ? htmlentities($_POST["price"]) : null;
    $category_id = isset($_POST["categories"]) ? htmlentities($_POST["categories"]) : null;

    if (!$title) {
        $app->session->set("flash", "Title is required");
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        $sql_1 = "INSERT INTO kmom05_product(image, title, description, price) VALUES (?, ?, ?, ?)";  
        $params_1 = [$url, $title, $description, $price];
        $app->db->execute($sql_1, $params_1);

        $getLastId = "SELECT MAX(id) AS lastId FROM kmom05_product";
        $lastId = $app->db->fetchAll($getLastId);

        $sql_2 = "INSERT INTO kmom05_product_to_category(product_id, category_id) VALUES (?, ?)";  
        $params_2 = [$lastId[0]->lastId, $category_id];
        $app->db->execute($sql_2, $params_2);

        $sql_3 = "INSERT INTO kmom05_inventory(product_id, amount) VALUES (?, ?)";
        $params_3 = [$lastId[0]->lastId, 0];
        $app->db->execute($sql_3, $params_3);

        $app->session->set("flash", "Product added to database.");
        $app->response->redirect($app->url->create('webshop'));
    }
});

$app->router->add("edit-product", function () use ($app) {
    // Check if user is logged in otherwise redirect to login
    if (!$app->session->has("username")) {
        $app->response->redirect($app->url->create('login'));
    }

    $product_id = isset($_POST["id"]) ? htmlentities($_POST["id"]) : null;
    $url = isset($_POST["url"]) ? htmlentities($_POST["url"]) : null;
    $title = isset($_POST["title"]) ? htmlentities($_POST["title"]) : null;
    $description = isset($_POST["description"]) ? htmlentities($_POST["description"]) : null;
    $price = isset($_POST["price"]) ? htmlentities($_POST["price"]) : null;
    $category_id = isset($_POST["categories"]) ? htmlentities($_POST["categories"]) : null;

    if (!$title) {
        $app->session->set("flash", "Title is required");
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        $sql_1 = "UPDATE kmom05_product SET image=?, title=?, description=?, price=? WHERE id=?";
        $params_1 = [$url, $title, $description, $price, $product_id];
        $app->db->execute($sql_1, $params_1);

        $sql_2 = "UPDATE kmom05_product_to_category SET category_id=? WHERE product_id=?"; 
        $params_2 = [$category_id, $product_id];
        $app->db->execute($sql_2, $params_2);

        $app->session->set("flash", "Product was updated.");
        $app->response->redirect($app->url->create('webshop'));
    }
});

$app->router->add("add-category", function () use ($app) {
    $category = isset($_POST["category"]) ? htmlentities($_POST["category"]) : null;

    if (!$category) {
        $app->session->set("flash", "Error - Category is required.");
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    $sql = "INSERT INTO kmom05_category(category) VALUES (?)";
    $params = [$category];

    try {
        $app->db->execute($sql, $params);
    } catch (PDOException $e) {
        if ($e->errorInfo[0] == '23000' && $e->errorInfo[1] == '1062') {
            $app->session->set("flash", "Error - Category must be unique.");
        }
    }

    $app->response->redirect($app->url->create('category'));
});

$app->router->add("edit-category", function () use ($app) {
    // Check if user is logged in otherwise redirect to login
    if (!$app->session->has("username")) {
        $app->response->redirect($app->url->create('login'));
    }

    $category = isset($_POST["category"]) ? htmlentities($_POST["category"]) : null;
    $category_id = isset($_POST["id"]) ? htmlentities($_POST["id"]) : null;

    if (!$category) {
        $app->session->set("flash", "Category is required");
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        $sql = "UPDATE kmom05_category SET category=? WHERE id=?";
        $params = [$category, $category_id];
        $app->db->execute($sql, $params);

        $app->session->set("flash", "Category was updated.");
        $app->response->redirect($app->url->create('category'));
    }
});

$app->router->add("edit-inventory", function () use ($app) {
    // Check if user is logged in otherwise redirect to login
    if (!$app->session->has("username")) {
        $app->response->redirect($app->url->create('login'));
    }

    $amount = isset($_POST["amount"]) ? htmlentities($_POST["amount"]) : null;
    $product_id = isset($_POST["id"]) ? htmlentities($_POST["id"]) : null;

    if ($amount < 0) {
        $app->session->set("flash", "Amount can't be negative.");
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        $sql = "UPDATE kmom05_inventory SET amount=? WHERE product_id=?";
        $params = [$amount, $product_id];
        $app->db->execute($sql, $params);

        $app->session->set("flash", "Inventory was updated.");
        $app->response->redirect($app->url->create('inventory'));
    }
});
