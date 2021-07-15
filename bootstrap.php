<?php
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

require_once 'vendor/autoload.php';

$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/src'], $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

$conn = [
    'driver' => 'pdo_mysql',
    'host' => '172.20.0.2',
    'dbname' => 'project0707',
    'user' => 'root',
    'password' => 'root',
];
$entityManager = EntityManager::create($conn, $config);
