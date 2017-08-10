<?php

$app->router->add("dice", function () use ($app) {
    $app->view->add("take1/header", ["title" => "Dice"]);
    $app->view->add("navbar2/navbar");
    $app->view->add("take1/intro", ["title" => "Play Dice", "intro" => "Play dice, the first player to get 100 points will win. But look out for number 1 since it will reset your points to 0 and you will have to start over."]);
    $app->view->add("take1/dice");
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});
