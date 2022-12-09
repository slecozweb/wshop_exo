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

        if (!$prepare->executeSql($arrayAttributes)) {
            $this->prepare = $prepare;
            return false
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

        if (!$prepare->execute($arrayAttributes)) {
            $this->prepare = $prepare;
            return false;
        }

        $data = $prepare->fetch(\PDO::FETCH_ASSOC);
        $this->prepare = $prepare;

        return $data;
    }
}