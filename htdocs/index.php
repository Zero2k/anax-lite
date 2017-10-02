<?php
/**
 * Bootstrap the framework.
 */
// Where are all the files? Booth are needed by Anax.
define("ANAX_INSTALL_PATH", realpath(__DIR__ . "/.."));
define("ANAX_APP_PATH", ANAX_INSTALL_PATH);

// Include essentials
require ANAX_INSTALL_PATH . "/config/error_reporting.php";

// Get the autoloader by using composers version.
require ANAX_INSTALL_PATH . "/vendor/autoload.php";

// Add all resources to $app
$app = new \Vibe\App\App();
$app->request = new \Anax\Request\Request();
$app->response = new \Anax\Response\Response();
$app->url     = new \Anax\Url\Url();
$app->router  = new \Anax\Route\RouterInjectable();
$app->view    = new \Anax\View\ViewContainer();
$app->session  = new \Vibe\Session\Session();
$app->cookie = new \Vibe\Cookie\Cookie();
$app->navbar = new \Vibe\Navbar\Navbar();
$app->admin = new \Vibe\Admin\Admin();
$app->filter = new \Mos\TextFilter\CTextFilter();
$app->db = new \Vibe\Database\Database(); 
$app->diceSession = new \Vibe\Session\Session("diceGame");
$app->diceSession->start();

$databaseConfig = [
    $dsn        = "mysql:host=blu-ray.student.bth.se;dbname=vibe16;",
    $login      = "vibe16",
    $password   = "HPKhKaqEBk2F",
    $options    = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
];

$app->db->connect($databaseConfig); 

$app->navbar->configure("navbar.php");
$app->navbar->setApp($app);

// Start Session
$app->session->start();

// Inject $app into the view container for use in view files.
$app->view->setApp($app);

// Update view configuration with values from config file.
$app->view->configure("view.php");

// Init the object of the request Class.
$app->request->init();

// Init the url-object with default values from the request object.
$app->url->setSiteUrl($app->request->getSiteUrl());
$app->url->setBaseUrl($app->request->getBaseUrl());
$app->url->setStaticSiteUrl($app->request->getSiteUrl());
$app->url->setStaticBaseUrl($app->request->getBaseUrl());
$app->url->setScriptName($app->request->getScriptName());

// Update url configuration with values from config file.
$app->url->configure("url.php");
$app->url->setDefaultsFromConfiguration();

$app->logo = $app->url->asset("img/anax-lite.svg");

// Load the routes
require ANAX_INSTALL_PATH . "/config/route.php";

// Leave to router to match incoming request to routes
$app->router->handle($app->request->getRoute());
