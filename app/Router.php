<?php

namespace FwTest\Core;

class Router
{
    public static function init()
    {
        $arrayGlob = glob('../controllers/*.php');

        if (empty($arrayGlob)) {
            throw new \Exception('No route to load');
        }

        $found = false;

        foreach ($arrayGlob as $filepath) {
            if (self::saveRoutesFromFile($filepath)) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new \Exception('No matching route for current URI');
        }
    }

    private static function saveRoutesFromFile($path)
    {
        $content = file_get_contents($path);

        if (!preg_match('/class [a-z]+/i', $content, $arrayMatches)) {
            return ;
        }

        $objectName = str_ireplace('class ', '', $arrayMatches[0]);
        $reflectionClass = new \ReflectionClass('\\FwTest\\Controller\\' . $objectName);

        if (!$reflectionClass) {
            return ;
        }

        $arrayReflectionMethods = $reflectionClass->getMethods();

        if (!$arrayReflectionMethods) {
            return ;
        }

        $scriptName = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        list($currentUri, ) = explode('?', str_replace($scriptName, '', $_SERVER['REQUEST_URI']));

        foreach ($arrayReflectionMethods as $reflectionMethod) {
            $docComment = $reflectionMethod->getDocComment();

            if (!$docComment || !preg_match('/@Route\\(\'(.*)\'\\)/', $docComment, $arrayMatches)) {
                continue;
            }

            $routeUri = $arrayMatches[1];

            if ($routeUri == $currentUri) {
                $objectName = '\FwTest\Controller\\' . $objectName;
                $methodName = $reflectionMethod->getName();
                $object = new $objectName();
                $object->$methodName();
                return true;
            }
        }
    }
}