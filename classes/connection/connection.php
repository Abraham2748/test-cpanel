<?php

class Connection
{
    private $server;
    private $user;
    private $password;
    private $database;
    private $port;

    private $connection;

    function __construct()
    {
        $configList = $this->configList();
        $connectionData = $configList["connection"];
        $this->server = $connectionData['server'];
        $this->user = $connectionData['user'];
        $this->password = $connectionData['password'];
        $this->database = $connectionData['database'];
        $this->port = $connectionData['port'];
        $this->connection = new mysqli($this->server, $this->user, $this->password, $this->database, $this->port);
        if ($this->connection->connect_errno) {
            echo "Error connecting";
            die();
        }
    }

    private function configList()
    {
        $current = dirname(__FILE__);
        $data = file_get_contents($current . "/" . "config");
        return json_decode($data, true);
    }

    private function convertUTF8($array)
    {
        array_walk_recursive($array, function (&$item, $key) {
            if (!mb_detect_encoding($item, 'utf-8', true)) {
                $item = utf8_encode($item);
            }
        });
        return $array;
    }

    public function getData($sql_query)
    {
        $results = $this->connection->query($sql_query);
        $resultArray = array();
        foreach ($results as $key => $value) {
            $resultArray[] = $value;
        }
        return $this->convertUTF8($resultArray);
    }

    public function nonQuery($sql_query)
    {
        $this->connection->query($sql_query);
        return $this->connection->affected_rows;
    }

    public function nonQueryId($sql_query)
    {
        $this->connection->query($sql_query);
        $rows = $this->connection->affected_rows;
        if ($rows >= 1) {
            return $this->connection->insert_id;
        } else {
            return 0;
        }
    }

    public function callProcedure($procedureName, $parameters)
    {
        $sql = "CALL " . $procedureName . "(";
        foreach ($parameters as $key => $value) {
            $sql .= "'" . $value . "',";
        }
        $sql = substr($sql, 0, -1);
        $sql .= ")";
        echo $sql;
        $results = $this->connection->query($sql);
        $resultArray = array();
        foreach ($results as $key => $value) {
            $resultArray[] = $value;
        }
        return $this->convertUTF8($resultArray);
    }
}
