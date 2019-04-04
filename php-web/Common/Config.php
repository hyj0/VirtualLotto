<?php
/**
 * Created by PhpStorm.
 * User: dell-pc
 * Date: 2019/3/24
 * Time: 23:30
 */

namespace VirtulLotto;

class MysqlCfg {
    private $host = null;
    private $port = -1;
    private $user = null;
    private $password = null;
    private $database = null;

    /**
     * MysqlCfg constructor.
     * @param $host
     * @param $port
     * @param $user
     * @param $password
     * @param $database
     */
    public function __construct($host, $port, $user, $password, $database)
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return null
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return null
     */
    public function getDatabase()
    {
        return $this->database;
    }

}

class DBConfig {
    private $cfg = null;
    function __construct()
    {
        $this->cfg = new MysqlCfg("localhost", 3306, "root", "root", "lotty");
    }

    function getConnect() {
        $cfg = $this->cfg;
        return new \PDO("mysql:host=". $cfg->getHost().";dbname=".$cfg->getDatabase().";port=".$cfg->getPort().";",
            $cfg->getUser(), $cfg->getPassword());
    }

    static function newConn() {
        $conn = null;
        try {
            $conn = (new DBConfig())->getConnect();
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $conn->beginTransaction();
        } catch (\PDOException $e) {

            return null;
        }
        return $conn;
    }
}