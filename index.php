<?php

use Kanboard\Core\Controller\Runner;

/*@task added manually*/
ini_set('display_errors', 1);
ini_set("error_reporting", E_ALL & ~E_DEPRECATED);

try {
    require __DIR__.'/app/common.php';
    $container['router']->dispatch();
    $runner = new Runner($container);
    $runner->execute();
} catch (Exception $e) {
    echo 'Internal Error: '.$e->getMessage();
}
