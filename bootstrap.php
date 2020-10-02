<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once 'vendor/autoload.php';

$is_dev_mode = true;

$config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/entities'], $is_dev_mode, null, null, false);

$conn = require 'config/config.php';

$entity_manager = EntityManager::create($conn, $config);
