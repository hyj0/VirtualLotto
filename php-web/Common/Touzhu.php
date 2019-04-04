<?php
/**
 * Created by PhpStorm.
 * User: dell-pc
 * Date: 2019/3/26
 * Time: 0:28
 */

namespace VirtulLotto;
require_once "DrawIssue.php";
require_once "Utils.php";
require_once "UserAccount.php";

class Touzhu
{
    function __construct(\PDO $conn)
    {
        $this->conn = $conn;
        $this->drawIssue_instance = new DrawIssue($this->conn);
    }

    //计算金额
    function calcMoney($redNum, $blueNum) {
        $wagerNum = $redNum . ":" . $blueNum;
        $arr = $this->drawIssue_instance->__parseUserWagerNum($wagerNum);
        if (count($arr['redNum']) < 6 or  count($arr['blueNum']) < 1) {
            return -1;
        }

        $fee = 2 * Utils::__C(count($arr['redNum']), 6) * count($arr['blueNum']);
        return $fee;
    }

    function touzhu($redNum, $blueNum, $userId) {
        try {
            //todo:流水号唯一性
            $transNo = Utils::newTransNo();

            $wagerNum = $redNum . ":" . $blueNum;

            $fee = $this->calcMoney($redNum, $blueNum);
            if ($fee <= 0) {
                return -4;
            }
            $account = new UserAccount($this->conn);

            $ret = $account->delMoney($transNo, $userId, $fee, "投注",1);
            if ($ret < 0) {
                return $ret;
            }

            $sth = $this->conn->prepare("INSERT INTO t_user_wager (user_id, pre_issue, wager_num, trans_no, status, create_time, update_time, wager_fee)
                VALUES (:userId, (SELECT max(draw_issue) FROM t_bullnum), :wagerNum, :transNo, 1, sysdate(), sysdate(), :wager_fee);");
            $sth->execute(array(
                "userId" => $userId,
                "wagerNum" => $wagerNum,
                "transNo" => $transNo,
                "wager_fee" => $fee
            ));

            //todo:支付
            $sth = $this->conn->prepare("UPDATE t_user_wager SET status = 2, update_time = sysdate()
              WHERE trans_no = :trans_no AND user_id = :user_id");
            $sth->execute(array(
                "user_id" => $userId,
                "trans_no" => $transNo
            ));
        } catch (\PDOException $err) {
            return -1;
        }
        return 0;
    }
}