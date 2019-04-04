<?php
/**
 * Created by PhpStorm.
 * User: dell-pc
 * Date: 2019/3/26
 * Time: 1:03
 */

namespace VirtulLotto;


class UserInfo
{
    function __construct(\PDO $conn)
    {
        $this->conn = $conn;
    }

    function getRecentWagerList($userId) {
        try {
            $sth = $this->conn->prepare("SELECT draw_issue, pre_issue, wager_num, trans_no, create_time, win_fee, win_level, status, wager_fee
                                    FROM t_user_wager tuw
                                    WHERE tuw.user_id = :userId
                                    ORDER BY tuw.id DESC ;");
            $sth->execute(array(
                "userId" => $userId
            ));
            if ($sth->rowCount() <= 0) {
                return null;
            }
//            $result = $sth->fetch(\PDO::FETCH_ASSOC);
//            return $result;
            return $sth;
        } catch (\PDOException $err) {
            return null;
        }
        return 0;
    }
}