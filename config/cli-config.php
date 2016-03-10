<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 26.02.2016
 * Time: 1:08
 */

use Doctrine\ORM\Tools\Console\ConsoleRunner;

// replace with file to your own project bootstrap
require_once 'bootstrap.php';

// replace with mechanism to retrieve EntityManager in your app
$entityManager = GetEntityManager();

return ConsoleRunner::createHelperSet($entityManager);
