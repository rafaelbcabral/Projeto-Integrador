<?php
require_once 'vendor/autoload.php';

use \phputil\router\Router;
use function \phputil\cors\cors;

$app = new Router();
$app->use( cors( [ 'origin' => 'http://127.0.0.1:5501']) );

$app->get( '/', function( $req, $res ) {
  $res->json( [ ] );
} );

$app->listen();