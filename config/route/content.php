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

/* BLOG / POST */

$app->router->add("blog", function () use ($app) {
    $sql = "SELECT * FROM content WHERE type=? AND deleted=? ORDER BY published DESC";
    $resultset = $app->db->executeFetchAll($sql, ["post", 0]);
    $html = $app->content->blogList($resultset);

    $app->view->add("take1/header", ["title" => "Blog"]);
    $app->view->add("navbar2/navbar");
    $app->view->add("take1/intro", ["title" => "Blog", "intro" => "Change this later..."]);
    $app->view->add("content/blog", [
        "html" => $html,
        "test" => $app->url->create('test'),
        "posts" => $app->url->create('blog'),
        "pages" => $app->url->create('page'),
        "blocks" => $app->url->create('block')
    ]);
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

$app->router->add("blog/{slug}", function ($slug) use ($app) {
    $sql = "SELECT * FROM content WHERE slug=? AND deleted=?";
    $resultset = $app->db->executeFetchAll($sql, [$slug, 0]);

    if (!$resultset) {
        $app->response->redirect($app->url->create("blog"));
    }
    
    foreach ($resultset as $res) {
        $title = $res->title;
        $data = $app->filter->doFilter($res->data, $res->filter);
        $published = $res->published;
    }
    $html = $app->content->blogContent($data, $published); 

    $app->view->add("take1/header", ["title" => "Blog"]);
    $app->view->add("navbar2/navbar");
    $app->view->add("take1/intro", ["title" => $title, "intro" => "This is a single blog post"]);
    $app->view->add("content/blog", [
        "html" => $html,
        "test" => $app->url->create('test'),
        "posts" => $app->url->create('blog'),
        "pages" => $app->url->create('page'),
        "blocks" => $app->url->create('block')
    ]);
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

/* PAGE */

$app->router->add("page", function () use ($app) {
    $sql = "SELECT * FROM content WHERE type=? AND deleted=? ORDER BY published DESC";
    $resultset = $app->db->executeFetchAll($sql, ["page", 0]);
    $html = $app->content->pageList($resultset);

    $app->view->add("take1/header", ["title" => "Page"]);
    $app->view->add("navbar2/navbar");
    $app->view->add("take1/intro", ["title" => "Page", "intro" => "Change this later..."]);
    $app->view->add("content/page", [
        "html" => $html,
        "test" => $app->url->create('test'),
        "posts" => $app->url->create('blog'),
        "pages" => $app->url->create('page'),
        "blocks" => $app->url->create('block')
    ]);
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

$app->router->add("page/{slug}", function ($slug) use ($app) {
    $sql = "SELECT * FROM content WHERE slug=? AND deleted=?";
    $resultset = $app->db->executeFetchAll($sql, [$slug, 0]);

    if (!$resultset) {
        $app->response->redirect($app->url->create("page"));
    }
    
    foreach ($resultset as $res) {
        $title = $res->title;
        $data = $app->filter->doFilter($res->data, $res->filter);
    }
    $html = $app->content->pageContent($data); 

    $app->view->add("take1/header", ["title" => "Page"]);
    $app->view->add("navbar2/navbar");
    $app->view->add("take1/intro", ["title" => $title, "intro" => "This is a single page"]);
    $app->view->add("content/page", [
        "html" => $html,
        "test" => $app->url->create('test'),
        "posts" => $app->url->create('blog'),
        "pages" => $app->url->create('page'),
        "blocks" => $app->url->create('block')
    ]);
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

$app->router->add("block", function () use ($app) {
    $sql = "SELECT * FROM content WHERE type=? AND deleted=? ORDER BY published DESC";
    $resultset = $app->db->executeFetchAll($sql, ["block", 0]);
    $html = $app->content->pageList($resultset);

    $app->view->add("take1/header", ["title" => "Block"]);
    $app->view->add("navbar2/navbar");
    $app->view->add("take1/intro", ["title" => "Block", "intro" => "Change this later..."]);
    $app->view->add("content/block", [
        "html" => $html,
        "test" => $app->url->create('test'),
        "posts" => $app->url->create('blog'),
        "pages" => $app->url->create('page'),
        "blocks" => $app->url->create('block')
    ]);
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});

$app->router->add("block/{slug}", function ($slug) use ($app) {
    $sql = "SELECT * FROM content WHERE slug=? AND deleted=?";
    $resultset = $app->db->executeFetchAll($sql, [$slug, 0]);

    if (!$resultset) {
        $app->response->redirect($app->url->create("block"));
    }
    
    foreach ($resultset as $res) {
        $title = $res->title;
        $data = $app->filter->doFilter($res->data, $res->filter);
    }
    $html = $app->content->pageContent($data); 

    $app->view->add("take1/header", ["title" => "Block"]);
    $app->view->add("navbar2/navbar");
    $app->view->add("take1/intro", ["title" => $title, "intro" => "This is a single block"]);
    $app->view->add("content/page", [
        "html" => $html,
        "test" => $app->url->create('test'),
        "posts" => $app->url->create('blog'),
        "pages" => $app->url->create('page'),
        "blocks" => $app->url->create('block')
    ]);
    $app->view->add("take1/footer");
    $app->response->setBody([$app->view, "render"])
              ->send();
});
