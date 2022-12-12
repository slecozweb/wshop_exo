<?php
// Augmentation du niveau d'affichage des erreurs
error_reporting(E_ALL);
require_once('../app/Loader.php');

FwTest\Core\Loader::register();
FwTest\Core\Router::init();
