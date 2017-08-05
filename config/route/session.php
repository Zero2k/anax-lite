<?php

$app->router->add("session", function () use ($app) {
    // If no value in Session exist, set it to 0
    if ($app->session->has("value") == false) {
        $app->session->set("value", 0);
    }

    $app->view->add("take1/header", ["title" => "Session"]);
    $app->view->add("navbar1/navbar");
        $app->view->add("take1/intro", ["title" => "Session", "intro" => "Implementation of session in Anax-lite. Increase and decrease the value by clicking the buttons below."]);
    $app->view->add("session/session", [
        "value" => $app->session->get("value"),
        "increment" => $app->url->create("session/increment"),
        "decrement" => $app->url->create("session/decrement"),
        "status" => $app->url->create("session/status"),
        "dump" => $app->url->create("session/dump"),
        "destroy" => $app->url->create("session/destroy")
    ]);
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

$app->router->add("session/increment", function () use ($app) {
    $app->session->set("value", $app->session->get("value") + 1);
    header("Location: " . $_SERVER['HTTP_REFERER']);
});

$app->router->add("session/decrement", function () use ($app) {
    $app->session->set("value", $app->session->get("value") - 1);
    header("Location: " . $_SERVER['HTTP_REFERER']);
});

$app->router->add("session/status", function () use ($app) {
    $app->response->sendJson([
        "Session name:" => session_name(),
        "Session status:" => session_status(),
        "Session cache expire:" => session_cache_expire(),
        "Session cache limiter:" => session_cache_limiter(),
        "Session save path:" => session_save_path()
    ]);
});

$app->router->add("session/dump", function () use ($app) {
    $app->view->add("take1/header", ["title" => "Session Dump"]);
    $app->view->add("navbar1/navbar");
        $app->view->add("take1/intro", ["title" => "Session", "intro" => "Implementation of session in Anax-lite. Increase and decrease the value by clicking the buttons below."]);
    $app->view->add("session/dump");
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

$app->router->add("session/destroy", function () use ($app) {
    $app->session->destroy();
    header("Location: " . $_SERVER['HTTP_REFERER']);
});
