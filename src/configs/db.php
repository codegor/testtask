<?php
$conf = [
  'host' => getenv('MYSQL_HOST'),
  'dbname' => getenv('MYSQL_DATABASE'),
  'user' => getenv('MYSQL_USER'),
  'pass' => getenv('MYSQL_PASSWORD')
];

return $conf;