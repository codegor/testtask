<?php
//Log
ini_set('log_errors', '1');
ini_set('error_log', 'errors.log');
ini_set('display_errors', '0');

function exception_handler(Throwable $exception) { // todo add logging of errors (to log file)
  header('Content-Type: application/json; charset=utf-8');
  $mess = 'Server error 003256';
  if (is_a($exception, 'PDOException')) {
    http_response_code(500);
    $mess = 'Server error 22033';
  } elseif (is_a($exception, 'UnknownUrlException')) {
    http_response_code(404);
    $mess = 'Not found';
  }
  echo json_encode(['error' => $mess]);
  exit();
}

set_exception_handler('exception_handler');

require 'Autoloader.php';
Autoloader::register();

$routes = require 'router.php';

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$url = explode('?', $_SERVER['REQUEST_URI']);

if (!empty($_SERVER['HTTP_CLIENT_IP']))
  $ip = $_SERVER['HTTP_CLIENT_IP'];
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
else
  $ip = $_SERVER['REMOTE_ADDR'];

$agent = $_SERVER['HTTP_USER_AGENT'] ?? null; // or maybe try $_SERVER['HTTP_SEC_CH_UA']

if (isset($url[1]) && $url[1])
  $url[1] = rawurldecode($url[1]);

function dispatch(array $routes, array $url, string $httpMethod): array {
  foreach ($routes['cfg'] as $route => $config) {
    $prefix = str_replace('/', '\/', $routes['prefix']);
    $methods = [
      'index' => ['pattern' => "/^{$prefix}\/{$route}s$/", 'method' => 'GET'], // todo create ability to add 's' or 'es'
      'create' => ['pattern' => "/^{$prefix}\/{$route}$/", 'method' => 'POST'],
      'show' => ['pattern' => "/^{$prefix}\/{$route}\/(?<id>[0-9]+)$/", 'method' => 'GET'], // todo add more configurable check input params
      'update' => ['pattern' => "/^{$prefix}\/{$route}\/(?<id>[0-9]+)$/", 'method' => 'PUT'],  // todo add more configurable check input params
      'destroy' => ['pattern' => "/^{$prefix}\/{$route}\/(?<id>[0-9]+)$/", 'method' => 'DELETE'],  // todo add more configurable check input params
    ];
    $methodsCfg = empty($config) ? ['index', 'create', 'show', 'update', 'destroy'] : $config;
    foreach ($methodsCfg as $method) {
      $cfg = $methods[$method];
      if ($httpMethod === $cfg['method'] && preg_match($cfg['pattern'], $url[0], $matches)) {
        $name = ucfirst($route);
        return [
          'controller' => "\Controllers\\{$name}",
          'method' => $method,
          'params' => isset($matches['id']) ? ['id' => $matches['id']] : []
        ];
      }
    }
  }
  throw new UnknownUrlException('Can not understand what to show, wrong url'); // todo add default route
};

$toShow = dispatch($routes, $url, $httpMethod);
if (is_subclass_of($toShow['controller'], '\Controllers\ControllerWithLogsEntity')) {
  $db_config = require 'configs/db.php';
  $db = new DB($db_config);
  $log = new \Entities\Log($db);
  $cntrlr = new $toShow['controller']($log);
} else {
  $cntrlr = new $toShow['controller']();
}

$response = $cntrlr->{$toShow['method']}($toShow['params'] + compact('ip', 'agent'));

if ('integer' === gettype($response))
  http_response_code($response);
else if ('array' === gettype($response)) {
  header('Content-Type: application/json; charset=utf-8');
  http_response_code(200);
  echo json_encode($response);
} else
  echo $response;

exit();
