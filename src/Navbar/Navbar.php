<?php

namespace Vibe\Navbar;

/**
* Session class.
*/
class Navbar implements
    \Anax\Common\ConfigureInterface,
    \Anax\Common\AppInjectableInterface
{
    use \Anax\Common\ConfigureTrait,
        \Anax\Common\AppInjectableTrait;

    public function getHtml()
    {
        $navbarData = $this->config;

        foreach ($navbarData as $key => $value) {
            global $menu;
            foreach ($value as $key => $value) {
                if ($key == "text") {
                    $text = $value;
                } elseif ($key == "route") {
                    $url = $this->app->url->create($value);
                }
            }

            $menu .= "<li><a href=$url>$text</a></li>";
        } 

        return $menu;
    }
}
