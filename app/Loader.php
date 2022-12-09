<?php
namespace FwTest\Core;

class Loader
{
    public static function register()
    {
        spl_autoload_register('FwTest\Core\Loader::autoload');
    }

    public static function autoload($name)
    {
        $arrayName = explode('\\', $name);

        if (
            $arrayName[0] != 'FwTest' ||
            !in_array($arrayName[1], ['Core', 'Classes', 'Controller', 'Model']) ||
            count($arrayName) != 3
        ) {
            throw new \Exception('Wrong namespace');
        }

        $type = $arrayName[1];
        $filename = ucfirst($arrayName[2]) . '.php';

        $path = '../';

        if ($type == 'Core') {
            $path .= 'app/';
        } elseif ($type == 'Classes') {
            $path .= 'classes/';
        } elseif ($type == 'Controller') {
            $path .= 'controllers/';
        } elseif ($type == 'Model') {
            $path .= 'models/';
        }

        $path .= $filename;

        if (!file_exists($path)) {
            throw new \Exception('Class "' . $name . '" doesn\'t exist');
        }

        require_once($path);

        if (!class_exists($name)) {
            throw new \Exception('Class ' . $name . ' doesn\'t exist');
        }
    }
}