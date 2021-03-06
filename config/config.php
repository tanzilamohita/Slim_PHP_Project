<?php
/*
* (Slim) Application Config
*/

$app = new \Slim\Slim(array(
    'templates.path' => 'templates',
	'mode' => 'development',
	'theme' => 'default',

));

$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'log.enable' => true,
        'debug'		 => false

    ));
	R::freeze(TRUE);
});

// Only invoked if mode is "development"
$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'log.enable' => true,
        'debug' => true

    ));
	//R::debug(true);
	R::freeze(true);
});





// Create monolog logger and store logger in container as singleton
// (Singleton resources retrieve the same log resource definition each time)
/*$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('slim-skeleton');
    $log->pushHandler(new \Monolog\Handler\StreamHandler('logs/app.log', \Monolog\Logger::DEBUG));
    return $log;
});
*/

// Prepare view
$app->view(new \Slim\Views\Twig());
$app->view->parserOptions = array(
    'charset' => 'utf-8',
    'cache' => realpath('templates/cache'),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true
);
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());
/*[Default Time Zone]*/
date_default_timezone_set('Asia/Dhaka');

$trdate     = date("Y-m-d H:i:s",time());
$d          = date("d",strtotime($trdate));
$m          = date("m",strtotime($trdate));
$Y          = date("Y",strtotime($trdate));
$H          = date("H",strtotime($trdate));
$i          = date("i",strtotime($trdate));
$s          = date("s",strtotime($trdate));
$trdate     = date("Y-m-d H:i:s",mktime($H,$i,$s,$m,$d,$Y));
$curdate    = date("Y-m-d",mktime($m,$d,$Y));

$curyesterday   = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));
$curweek        = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 7, date("Y")));
$curmonth       = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 30, date("Y")));


define("CURRENT_DT"	        ,$curdate);
define("CURRENT_DTT"	        ,$trdate);
define("CURRENT_YDT"	        ,$curyesterday);
define("IN_APPS","YES");


$sitetime     = date("Y-m-d h:i A ",mktime($H,$i,$s,$m,$d,$Y));
define("SITETIME","$sitetime");


/*[Default Language]*/
define("DEFAULT_LANGUAGE","EN");
define("CURRENT_THTME","default");
$site_url="";
$config ="";


// Define helper variable
$headers = $app->request()->headers();
$seo_uri = $app->request()->getResourceUri();
$root_uri = $app->request()->getRootUri();
$protocol = isset($_SERVER['HTTPS']) === true ? 'https' : 'http';
$site_url = $protocol.'://'.$headers['HOST'].$root_uri;



$app->config('config',   $config);
$app->config('site_url', $site_url);
// Set helper variable for template
$app->view()->setData('config',   $config);
$app->view()->setData('site_url', $site_url);
$app->view()->setData('CURENTTHEM', CURRENT_THTME);

define("BASEURL",$site_url);
/*[Default Theme]*/




