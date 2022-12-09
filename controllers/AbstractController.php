<?php

namespace FwTest\Controller;

class AbstractController
{
    private $db;

    public $array_constant;

    public function __construct()
    {
        $this->array_constant = $this->getConstant();
    }

    public function getDatabaseConnection()
    {
        if (!$this->db) {
            $this->db = new \FwTest\Core\Database();
        }

        return $this->db;
    }

    public function render($filePath, $arrayArgs)
    {
        $templatePath = '../templates/';
        $fullPath = $templatePath . $filePath . '.php';

        if (!file_exists($fullPath)) {
            throw new \Exception('Template doesn\'t exist');
        }
        $object = new class() {
            public function render($path)
            {
                ob_start();
                require_once($path);
                $file_content = ob_get_clean();

                return $file_content;
            }
        };

        if (!empty($arrayArgs)) {
            foreach ($arrayArgs as $key => $value) {
                $object->$key = $value;
            }
        }

        return $object->render($fullPath);
    }

    public function getConstant()
    {
        $path = 'config/constant.ini';
        
        $arrayConstant = parse_ini_file($path, true);

        return $arrayConstant;
    }

    /**
     * Redirects to the given URL
     *
     * @param String $url The URL we want to be redirected
     */
    public function _redirect($url, $code = null)
    {
        $session = get_session();
        $session->save();

        if ($code == '301') {
            header('HTTP/1.1 301 Moved Permanently');
        } elseif ($code == '307') {
            header('HTTP/1.1 307 Moved Temporarily');
        }

        // The original header redirection doesn't stop the page execution until the wanted link is found. that's why we "exit" right after the header.
        header('Location: ' . $url);
        exit;
    }
}