<?php
/**
 * Routes for content.
 */
$app->router->add("test", function () use ($app) {
    $text = "[b]DEN HÄR TEXTEN SKA HA FETSTIL[/b]";
    $filter = "bbcode";
    $bbcode = "Den här texten ska ha fetstil:  '[b]DEN HÄR TEXTEN SKA HA FETSTIL[/b]' = " . $app->filter->doFilter($text, $filter);

    $text = "TESTAR FILTER FÖR \nNEWLINE\n NY RAD HÄR";
    $filter = "nl2br";
    $nl2br = "Den här texten ska ha två nya rader: 'TESTAR FILTER FÖR \nNEWLINE\n NY RAD HÄR' = " . $app->filter->doFilter($text, $filter);

    $text = "Skapa en länk till http://avanza.se";
    $filter = "clickable";
    $clickable = "Den här texten ska ha en länk till Avanza.se: 'Skapa en länk till http://avanza.se' = " . $app->filter->doFilter($text, $filter);

    $text = "[DEN HÄR TEXTEN ÄR BASERAD PÅ MARKDOWN](https://en.wikipedia.org/wiki/Markdown)";
    $filter = "markdown";
    $markdown = "Den här texten ska använda markdown: '[DEN HÄR TEXTEN ÄR BASERAD PÅ MARKDOWN](https://en.wikipedia.org/wiki/Markdown)' = " . $app->filter->doFilter($text, $filter);

    $app->view->add("take1/header", ["title" => "Test Filter Functions"]);
    $app->view->add("navbar2/navbar");
    $app->view->add("take1/intro", ["title" => "Test Filter Functions", "intro" => "Change this later..."]);
    $app->view->add("content/test", [
        "bbcode" => $bbcode,
        "nl2br" => $nl2br,
        "clickable" => $clickable,
        "markdown" => $markdown
    ]);
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});
