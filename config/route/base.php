<?php
/**
 * Routes.
 */
$app->router->add("", function () use ($app) {
    $app->view->add("take1/header", ["title" => "Home"]);
    $app->view->add("navbar2/navbar");
    $app->view->add("take1/home");
    $app->view->add("take1/byline");
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

$app->router->add("about", function () use ($app) {
    $app->view->add("take1/header", ["title" => "About"]);
    $app->view->add("navbar1/navbar");
    $app->view->add("take1/intro", ["title" => "About", "intro" => "About this site and and its underlying technology."]);
    $app->view->add("take1/about");
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

$app->router->add("report", function () use ($app) {
    $app->view->add("take1/header", ["title" => "Report"]);
    $app->view->add("navbar1/navbar");
    $app->view->add("take1/intro", ["title" => "Report", "intro" => "Reports for this course and its mandatory assignments."]);
    $app->view->add("take1/report");
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

$app->router->add("components", function () use ($app) {
    $app->view->add("take1/header", ["title" => "Components"]);
    $app->view->add("navbar1/navbar");
    $app->view->add("take1/intro", ["title" => "Components", "intro" => "This page is used to try new functionality that's being added to the framework."]);
    $app->view->add("take1/components");
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

$app->router->add("status", function () use ($app) {
    $data = [
        "Server" => php_uname(),
        "PHP version" => phpversion(),
        "Included files" => count(get_included_files()),
        "Memory used" => memory_get_peak_usage(true),
        "Execution time" => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
    ];

    $app->response->sendJson($data);
});
