<?php

require_once 'vendor/autoload.php';

R::setup('mysql:host=localhost;dbname=benchmark', 'root', '', true);

$config = Doctrine\ORM\Tools\Setup::createYAMLMetadataConfiguration([__DIR__ . '/app/doctrine/config/yaml'], true);

$dbParams = [
  'driver'   => 'pdo_mysql',
  'user'     => 'root',
  'password' => '',
  'dbname'   => 'benchmark',
  'charset'  => 'UTF8'
];

Flight::register('db', ['Doctrine\ORM\EntityManager', 'create'], [$dbParams, $config]);

$loader = new Twig_Loader_Filesystem(__DIR__ . '/app/views');
Flight::register('view', 'Twig_Environment', array($loader, []), function($twig) {
  $twig->addExtension(new Twig_Extension_Debug());
});

Flight::path(__DIR__ . '/app/controllers');

// Doctrine routes

Flight::route('/plaintext', ['BenchmarkDoctrineController', 'plaintext']);
Flight::route('/json', ['BenchmarkDoctrineController', 'json']);
Flight::route('/db', ['BenchmarkDoctrineController', 'db']);
Flight::route('/queries', ['BenchmarkDoctrineController', 'queries']);
Flight::route('/fortunes', ['BenchmarkDoctrineController', 'fortunes']);
Flight::route('/updates', ['BenchmarkDoctrineController', 'updates']);
Flight::route('/', ['BenchmarkDoctrineController', 'index']);


// Redbean routes
/*
Flight::route('/plaintext', ['BenchmarkRedbeanController', 'plaintext']);
Flight::route('/json', ['BenchmarkRedbeanController', 'json']);
Flight::route('/db', ['BenchmarkRedbeanController', 'db']);
Flight::route('/queries', ['BenchmarkRedbeanController', 'queries']);
Flight::route('/fortunes', ['BenchmarkRedbeanController', 'fortunes']);
Flight::route('/updates', ['BenchmarkRedbeanController', 'updates']);
Flight::route('/', ['BenchmarkRedbeanController', 'index']);
*/

Flight::start();
