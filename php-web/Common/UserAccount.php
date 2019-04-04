<?php
/**
 * Created by PhpStorm.
 * User: dell-pc
 * Date: 2019/3/24
 * Time: 23:59
 */

namespace VirtulLotto;


class UserAccount
{
    function __construct(\PDO $conn)
    {
        $this->conn = $conn;
    }

    function getAccount($userId) {
        try {
            $sth = $this->conn->prepare("SELECT user_id, fee, frozen_fee, account_status
                FROM t_user_account tua
                WHERE tua.user_id = :user_id;");
            $sth->execute(array(
                "user_id" => $userId
            ));
            $result = $sth->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $err) {
            return null;
        }
        return 0;
    }

    function createAccount($userId) {
        try {
            $sth = $this->conn->prepare("INSERT INTO t_user_account (user_id, fee, frozen_fee, account_status, create_time, update_time)
                 VALUES (:user_id, 0, 0, 1, sysdate(), sysdate());");
            $sth->execute(array(
                "user_id" => $userId
            ));
        } catch (\PDOException $err) {
            return null;
        }
        return 0;
    }

    //收入
    function addMoney($transNo, $userId, $fee, $note, $type) {
        $accout = $this->getAccount($userId);
        if ($accout == null) {
            return -2;
        }
        try {
            $sth = $this->conn->prepare("UPDATE t_user_account
                SET fee = fee+:in_fee, update_time=sysdate()
                WHERE user_id=:user_id;");
            $sth->execute(array(
                "in_fee" =>$fee,
                "user_id" => $userId
            ));
            return $this->__addLog($userId, $type, 1, $transNo, $fee, $accout['fee'],$accout['fee']+$fee, $note);
        } catch (\PDOException $err) {
            return -3;
        }
        return 0;
    }

    //支出
    function delMoney($transNo, $userId, $fee, $note, $type) {
        $accout = $this->getAccount($userId);
        if ($accout['fee'] < $fee) {
            return -1;
        }
        try {
            $sth = $this->conn->prepare("UPDATE t_user_account
                SET fee = fee-:in_fee, update_time=sysdate()
                WHERE user_id=:user_id;");
            $sth->execute(array(
                "in_fee" =>$fee,
                "user_id" => $userId
            ));
            return $this->__addLog($userId, $type, 2, $transNo, $fee, $accout['fee'],$accout['fee']-$fee, $note);
        } catch (\PDOException $err) {
            return -3;
        }
        return 0;
    }

    function __addLog($userId, $type, $subType, $transNo, $fee, $org_fee, $end_fee, $note) {
        try {
            $sth = $this->conn->prepare("INSERT INTO t_user_account_log (uid, type, sub_type, trans_no, fee, org_fee, end_fee, update_time, create_time, note)
                VALUES (:uid, :type, :sub_type, :trans_no, :fee, :org_fee, :end_fee, sysdate(), sysdate(), :note);");
            $sth->execute(array(
                "uid" => $userId,
                "type" =>$type,
                "sub_type" =>$subType,
                "trans_no" => $transNo,
                "fee" => $fee,
                "org_fee" => $org_fee,
                "end_fee" => $end_fee,
                "note" => $note
            ));
        } catch (\PDOException $err) {
            return -4;
        }
        return 0;
    }


    function getAccountLogList($userId) {
        try {
            $sth = $this->conn->prepare("SELECT *
                FROM t_user_account_log tual
                  WHERE uid = :uid
                  ORDER BY id DESC ;");
            $sth->execute(array(
                "uid" => $userId,
            ));
            return $sth;
        } catch (\PDOException $err) {
            return null;
        }
        return null;
    }
}