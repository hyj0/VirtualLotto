<?php
/**
 * Created by PhpStorm.
 * User: dell-pc
 * Date: 2019/3/24
 * Time: 23:45
 */

namespace VirtulLotto;


class User
{
    private $user_id = null;

    function __construct(\PDO $conn)
    {
        $this->conn = $conn;
    }

    function getUserByName($userName) {
        try {
            $sth = $this->conn->prepare("SELECT * FROM t_user tu WHERE tu.user_name = :userName ;");
            $sth->execute(array(
                "userName" => $userName
            ));
            if ($sth->rowCount() <= 0) {
                return null;
            }
            $result = $sth->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $err) {
            return null;
        }
        return null;
    }
    function getUserByUid($userId) {
        try {
            $sth = $this->conn->prepare("SELECT * FROM t_user tu WHERE tu.user_id = :userId;");
            $sth->execute(array(
                "userId" => $userId
            ));
            if ($sth->rowCount() <= 0) {
                return null;
            }
            $result = $sth->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $err) {
            return null;
        }
        return null;
    }


    /**
     * @param $userName
     * @param $passwd
     */
    function addUser($userName, $passwd) {
        $userId = 0;
        try {
            $sth = $this->conn->prepare("INSERT INTO lotty.t_user (user_name, regster_time, icon_url, create_time, update_time, passwd)
                  VALUES (:userName, sysdate(), '', sysdate(), sysdate(), :passwd); ;");
            $sth->execute(array(
                "userName" => $userName,
                "passwd" => $passwd
            ));
            $userId = $this->conn->lastInsertId();
        } catch (\PDOException $err) {
            return null;
        }
        /** @var int $userId */
        return $userId;
    }
}