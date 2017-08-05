<?php

$navbar = [
    "items" => [
        "home" => [
            "text" => "Home",
            "route" => "",
        ],
        "about" => [
            "text" => "About",
            "route" => "about",
        ],
        "report" => [
            "text" => "Report",
            "route" => "report",
        ],
        "components" => [
            "text" => "Components",
            "route" => "components",
        ],
        "session" => [
            "text" => "Session",
            "route" => "session",
        ]
    ]
];

foreach ($navbar["items"] as $key => $value) {
    global $menu;
    foreach ($value as $key => $value) {
        if ($key == "text") {
            $text = $value;
        } elseif ($key == "route") {
            $url = $app->url->create($value);
        }
    }
    $menu .= "<li><a href=$url>$text</a></li>";
}

?>

<nav style="margin-bottom: -20px" class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="">
              <img style="max-height: 30px;" src="<?= $app->logo ?>">
          </a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            <?php echo $menu; ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
</nav>
