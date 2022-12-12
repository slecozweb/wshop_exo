<?php

namespace FwTest\Core;

class Database
{
    private $host;
    private $user;
    private $password;
    private $port;
    private $database;

    private $object;
    private $prepare;

    public function __construct()
    {
        $this->loadConfig();
        $this->connect();
    }

    private function loadConfig()
    {
        $path = '../config/database.ini';

        if (!file_exists($path)) {
            throw new \Exception('Missing database config file');
        }

        $arrayConfig = parse_ini_file($path, true);

        if (
            !array_key_exists('database', $arrayConfig) ||
            !array_key_exists('host', $arrayConfig['database']) ||
            !array_key_exists('user', $arrayConfig['database']) ||
            !array_key_exists('password', $arrayConfig['database']) ||
            !array_key_exists('port', $arrayConfig['database']) ||
            !array_key_exists('database', $arrayConfig['database'])
        ) {
            throw new \Exception('Missing config informations');
        }

        $this->host = $arrayConfig['database']['host'];
        $this->user = $arrayConfig['database']['user'];
        $this->password = $arrayConfig['database']['password'];
        $this->port = $arrayConfig['database']['port'];
        $this->database = $arrayConfig['database']['database'];
    }

    private function connect()
    {
        $this->object = new \PDO('mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->database, $this->user, $this->password);
        $this->object->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->object->query('SET NAMES \'utf8\' COLLATE \'utf8_unicode_ci\'');
		$this->object->query('SET CHARACTER SET utf8');

    }

    public function query($sql, $arrayAttributes = [])
    {
        $prepare = $this->object->prepare($sql);

        if (!$prepare) {
            return false;
        }

        $isExecuted = $prepare->execute($arrayAttributes);

        $this->prepare = $prepare;
        return $isExecuted;
    }

    public function fetchAll($sql, $arrayAttributes = [])
    {
        $prepare = $this->object->prepare($sql);

        if (!$prepare) {
            return false;
        }

        // Correction de l'appel de la fonction executeSql en execute
        if (!$prepare->execute($arrayAttributes)) {
            $this->prepare = $prepare;
            // Correction du ; manquant en fin d'instruction
            return false;
        }

        $data = $prepare->fetchAll(\PDO::FETCH_ASSOC);
        $this->prepare = $prepare;

        return $data;
    }

    public function fetchOne($sql, $arrayAttributes = [])
    {
        $prepare = $this->object->prepare($sql);

        if (!$prepare) {
            return false;
        }

        if (!$prepare->execute($arrayAttributes)) {
            $this->prepare = $prepare;
            return false;
        }

        $data = $prepare->fetchColumn();
        $this->prepare = $prepare;

        return $data;
    }

    public function fetchRow($sql, $arrayAttributes = [])
    {
        $prepare = $this->object->prepare($sql);

        if (!$prepare) {
            return false;
        }

        //file_put_contents("debug.txt", "-- SQL : ".$sql."\n", FILE_APPEND);
        //file_put_contents("debug.txt", "Params : ".serialize($arrayAttributes)."\n", FILE_APPEND);

        if (!$prepare->execute($arrayAttributes)) {
            $this->prepare = $prepare;
            return false;
        }

        $data = $prepare->fetch(\PDO::FETCH_ASSOC);
        $this->prepare = $prepare;

        return $data;
    }

    public function add ($sql, $arrayAttributes = []) {
        $prepare = $this->object->prepare($sql);

        if (!$prepare) {
            return false;
        }

        if (!$prepare->execute($arrayAttributes)) {
            $this->prepare = $prepare;
            return false;
        }

    }

    public function del ($sql, $arrayAttributes = []) {
        $prepare = $this->object->prepare($sql);

        if (!$prepare) {
            return false;
        }

        if (!$prepare->execute($arrayAttributes)) {
            $this->prepare = $prepare;
            return false;
        }

    }
}