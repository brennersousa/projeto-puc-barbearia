<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

// bootstrap.php
require_once "vendor/autoload.php";

// doctrine configurations 

$paths = array("source/Models");
$isDevMode = true;

// the connection configuration
$dbParams = array(
    'host'     => getenv('DB_HOST'),
    'driver'   => getenv('DB_DRIVER'),
    'user'     => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'dbname'   => getenv('DB_NAME'),
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);

new \App\Support\EntityManager($entityManager);