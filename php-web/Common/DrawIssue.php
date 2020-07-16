<?php
/**
 * Created by PhpStorm.
 * User: dell-pc
 * Date: 2019/3/28
 * Time: 23:42
 */


namespace VirtulLotto;
require_once  dirname(__FILE__) . "/../third_party/html-parser/src/ParserDom.php";

require_once "UserAccount.php";


class BullNumZc
{
    function __construct()
    {
        $this->url = "http://www.cwl.gov.cn/cwl_admin/kjxx/findDrawNotice?name=ssq&issueCount=30";
        $this->html = "";
    }

    function getPage() {
        $this->html = file_get_contents($this->url);
    }
    function getBullNumList() {
        $retArr = array();
        $arr = json_decode($this->html,true);

        foreach ($arr['result'] as $item) {
            $date = substr($item["date"], 0, 10);
            $one = array(
                "issue" =>   $item["code"],
                "redNum" => $item["red"],
                "blueNum" => $item["blue"],
                "drawTime" => $date
            );
            array_push($retArr, $one);
        }

        return $retArr;
    }
}


class DrawIssue
{
    function __construct(\PDO $conn)
    {
        $this->conn = $conn;
        $this->url = "http://www.bwlc.net/bulletin/prevslto.html";
        $this->userAccount = new UserAccount($this->conn);
    }

    function __getBullPage() {
        $html = file_get_contents($this->url);
        return $html;
    }

    function __parseBullPage($html) {
        $retArr = array();
        $doc =new \HtmlParser\ParserDom($html);
//        $arr = $doc->find(".odd");
        $arr = $doc->find("tr");
        foreach ($arr as $item) {
            $str = $item->node->nodeValue;
            $str = str_replace("\t", "", $str);
            $sp = explode("\n", $str);
            $issue = (int)$sp[0];
            if ($issue <= 0) {
                continue;
            }
            $one = array(
              "issue" =>   $sp[0],
                "redNum" => $sp[1],
                "blueNum" => $sp[2],
                "drawTime" => $sp[6]
            );
            array_push($retArr, $one);
        }
        return $retArr;
    }

    private function __addBull2Db($id, $bull, $preIssue)
    {
        try {
            $sth = $this->conn->prepare("INSERT INTO t_bullnum (id, draw_issue, draw_num, draw_time, create_time, update_time, status, pre_issue)
            VALUES (:id, :draw_issue, :draw_num, :draw_time, sysdate(), sysdate(), 1, :pre_issue);");
            $sth->execute(array(
                "id" => $id,
                "draw_issue" => intval($bull['issue']),
                "draw_num" => $bull['redNum'].":".$bull['blueNum'],
                "draw_time" => $bull['drawTime'],
                "pre_issue" => $preIssue
            ));
            return 0;
        } catch (\PDOException $err) {
            return -1;
        }
    }

    //更新开奖号码
    function updateBullNum() {
        if (false) {
            /*北京福彩*/
            $html = $this->__getBullPage();
            $arrBullNum = $this->__parseBullPage($html);
        } else {
            /*中福彩*/
            $bullNum = new BullNumZc();
            $bullNum->getPage();
            $arrBullNum = $bullNum->getBullNumList();
        }

        try {
            //最大期
            $sth = $this->conn->prepare("SELECT id, draw_issue
                FROM t_bullnum tb
                ORDER BY draw_issue DESC
                LIMIT 1;");
            $sth->execute(array(
            ));
            $result = $sth->fetch(\PDO::FETCH_ASSOC);
            $maxId = $result['id'];
            $maxIssue = $result['draw_issue'];

            $addCount = 0;
            $preIssue = $maxIssue;
            for ($idx = count($arrBullNum)-1; $idx >= 0; $idx-=1) {
                $item = $arrBullNum[$idx];
                $issue = (int)($item['issue']);
                if ($issue > $maxIssue) {
                    $addCount += 1;
                    $id = $maxId + $addCount;
                    $ret = $this->__addBull2Db($id, $item, $preIssue);
                    if ($ret < 0) {
                        return -2;
                    }
                    $preIssue = $issue;
                }
            }
        } catch (\PDOException $err) {
            return -1;
        }
        return 0;
    }

    //对未开奖期开奖
    function drawIssue() {

        try {
            //最大期
            $sth = $this->conn->prepare("SELECT pre_issue, id, draw_issue, draw_num
                    FROM t_bullnum tb
                    WHERE tb.status = 1
                    ORDER BY draw_issue ;");
            $sth->execute(array(
            ));
            while (true) {
                $result = $sth->fetch(\PDO::FETCH_ASSOC);
                if ($result == null) {
                    break;
                }

                $preIssue = $result['pre_issue'];
                $id = $result['id'];
                $draw_issue = $result['draw_issue'];
                $draw_num = $result['draw_num'];

                $ret = $this->__drawOneIssue($preIssue, $draw_issue, $draw_num);
                if ($ret == 0) {
                    $ret = $this->__updateBullStatus($id, 1, 2);
                }
                if ($ret != 0) {
                    return -2;
                }
            }
            return 0;
        } catch (\PDOException $err) {
            return -1;
        }
        return 0;
    }

    private function __updateBullStatus($id, $fromStatus, $toStatus)
    {
        try {
            //最大期
            $sth = $this->conn->prepare("UPDATE t_bullnum
                SET status = :toStatus, update_time=sysdate()
                WHERE id = :id AND status =:fromStatus;");
            $sth->execute(array(
                "fromStatus" => $fromStatus,
                "id" => $id,
                "toStatus" => $toStatus
            ));
        } catch (\PDOException $err) {
            return -1;
        }
        return 0;
    }

    private function __drawOneIssue($preIssue, $draw_issue, $draw_num)
    {
        try {
            //最大期
            $sth = $this->conn->prepare("SELECT id, user_id, wager_num, trans_no,  win_level, win_fee
                FROM t_user_wager tuw
                WHERE tuw.status = 2 AND pre_issue = :pre_issue;");
            $sth->execute(array(
                "pre_issue" =>$preIssue
            ));
            while (true) {
                $result = $sth->fetch(\PDO::FETCH_ASSOC);
                if ($result == null) {
                    break;
                }
                $id = $result['id'];
                $userId = $result['user_id'];
                $userWagerNum = $result['wager_num'];
                $transNo = $result['trans_no'];
                //计算号码中奖情况
                $winInfo = $this->__getWagerWinInfo($userWagerNum, $draw_num);

                //todo:目前只支持一个奖等，后续支持多奖等
                $draw_win_level = $winInfo[0]['winLevel'];
                $draw_win_fee = $winInfo[0]['winFee'];

                //
                $status = 3;//已开奖
                $ret = $this->__updateUserWager($id, $userId, $transNo, $draw_win_level, $draw_win_fee, $status, $draw_issue);
                if ($ret != 0) {
                    return -2;
                }

                if ($draw_win_fee > 0) {
                    //todo:派彩,测试， 可以优化成轮询，队列等
                    $ret = $this->userAccount->addMoney($transNo, $userId, $draw_win_fee,  '奖金', 2);
                    if ($ret != 0) {
                        return $ret;
                    }

                    $status = 4;//已支付奖金
                    $ret = $this->__updateUserWager($id, $userId, $transNo, $draw_win_level, $draw_win_fee, $status, $draw_issue);
                    if ($ret != 0) {
                        return -2;
                    }
                } else {
                    $status = 5;//完成
                    $ret = $this->__updateUserWager($id, $userId, $transNo, $draw_win_level, $draw_win_fee, $status, $draw_issue);
                    if ($ret != 0) {
                        return -2;
                    }
                }
            }
            return 0;
        } catch (\PDOException $err) {
            return -1;
        }
        return 0;
    }

    //计算中奖情况
    /*
     * 目前只返回一个最高的中奖奖等
     * arrary
     */
    private function __getWagerWinInfo($userWagerNum, $draw_num)
    {
//        $userWagerNum; //  	1 2 3 4 5 7 8:5 7
//        $draw_num;//04,08,09,13,28,33:04

        $retArr = array();


        $userWagerNumArr = $this->__parseUserWagerNum($userWagerNum);
        $draw_num_arr = $this->__parseBullDrawNum($draw_num);

        //计算中奖
        //todo:复式也只算出一个最高奖
        $redWinCount = 0;
        $blueWinCount = 0;

        foreach ($userWagerNumArr['redNum'] as $item) {
            $num = (int)$item;
            foreach ($draw_num_arr['redNum'] as $dItem) {
                if ($num == (int)$dItem) {
                    $redWinCount += 1;
                    break;
                }
            }
        }

        foreach ($userWagerNumArr['blueNum'] as $item) {
            $num = (int)$item;
            foreach ($draw_num_arr['blueNum'] as $dItem) {
                if ($num == (int)$dItem) {
                    $blueWinCount += 1;
                    break;
                }
            }
        }

        //计算奖等
        $winLevel = -1;
        $winFee = 0;
        if ($redWinCount == 6 ) {
            if ($blueWinCount == 1) {
                $winLevel = 1;
                $winFee = 1000*10000;//todo:浮动奖金
            } else {
                $winLevel = 2;
                $winFee = 20*10000;//todo:浮动奖金
            }
        } else if ($redWinCount == 5) {
           if ($blueWinCount == 1) {
               $winLevel = 3;
               $winFee = 3000;
           } else {
               $winLevel = 4;
               $winFee = 200;
           }
        } else if ($redWinCount == 4) {
            if ($blueWinCount == 1) {
                $winLevel = 4;
                $winFee = 200;
            } else {
                $winLevel = 5;
                $winFee = 10;
            }
        } else if ($redWinCount == 3) {
            if ($blueWinCount == 1) {
                $winLevel = 5;
                $winFee = 10;
            }
        } else if ($redWinCount == 2 or $redWinCount == 1 or $redWinCount == 0) {
            if ($blueWinCount == 1) {
                $winLevel = 6;
                $winFee = 5;
            }
        }

        $winArr = array(
            "winLevel" => $winLevel,
            "winFee" => $winFee
        );
        array_push($retArr, $winArr);
        return $retArr;
    }

    function __parseUserWagerNum($userWagerNum) {
        $sp = explode(":", $userWagerNum);
        $redNumStr = $sp[0];
        $blueNumStr = $sp[1];

        $sp = explode(" ", $redNumStr);
        $redNum = array();
        foreach ($sp as $item) {
            $num = (int)$item;
            array_push($redNum, $num);
        }

        $sp = explode(" ", $blueNumStr);
        $blueNum = array();
        foreach ($sp as $item) {
            $num = (int)$item;
            array_push($blueNum, $num);
        }

        return array(
            "redNum" => $redNum,
            "blueNum" => $blueNum
        );
    }
    function __parseBullDrawNum($draw_num) {
        $sp = explode(":", $draw_num);
        $redNumStr = $sp[0];
        $blueNumStr = $sp[1];

        $sp = explode(",", $redNumStr);
        $redNum = array();
        foreach ($sp as $item) {
            $num = (int)$item;
            array_push($redNum, $num);
        }

        $sp = explode(",", $blueNumStr);
        $blueNum = array();
        foreach ($sp as $item) {
            $num = (int)$item;
            array_push($blueNum, $num);
        }

        return array(
            "redNum" => $redNum,
            "blueNum" => $blueNum
        );
    }

    private function __updateUserWager($id, $userId, $transNo, $draw_win_level, $draw_win_fee, $status, $draw_issue)
    {
        try {
            //最大期
            $sth = $this->conn->prepare("UPDATE t_user_wager SET win_level=:win_level, win_fee=:win_fee,
              status = :status, update_time = sysdate(), draw_issue = :draw_issue
              WHERE id=:id AND trans_no = :trans_no AND user_id = :user_id");
            $sth->execute(array(
                "win_level" => $draw_win_level,
                "win_fee" => $draw_win_fee,
                "status" => $status,
                "draw_issue" => $draw_issue,

                "id" =>$id,
                "trans_no" =>$transNo,
                "user_id" =>$userId
            ));
        } catch (\PDOException $err) {
            return -1;
        }
        return 0;
    }

    public function getBullList()
    {
        try {
            $sth = $this->conn->prepare("SELECT *
            FROM t_bullnum
            ORDER BY draw_issue DESC
            LIMIT  10;");
            $sth->execute(array(
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
    }
}

if (false) {
    $str = substr("2020-07-14(二)", 0, 10);
    $bullNum = new BullNumZc();
    $bullNum->getPage();
    $ret = $bullNum->getBullNumList();
    print_r($ret);

    "一等奖 	10 	6689534
二等奖 	105 	201135
三等奖 	1359 	3000
四等奖 	72605 	200
五等奖 	1386153 	10
六等奖 	10855102 	5";

    "一等奖 	21 	5000000
二等奖 	85 	6000
三等奖 	2956 	3000
四等奖 	117550 	200
五等奖 	1895239 	10
六等奖 	28680477 	5";

    $sale = 383547756;
    $win = $sale * 0.49;
    $pool = 0;

    $low =  2956*3000 + 117550*200 + 1895239*10 +  28680477*5;

    $high  = $win-$low;

    $win2 = $high*0.25;
    $win2perBet = $win2 / 85;
    echo $win2perBet;

    $high = 6000*85*4;

    echo $high;

//    $conn = (new \VirtulLotto\DBConfig())->newConn();
    $drawIssue = new \VirtulLotto\DrawIssue(null);

    $userWagerNum = "1 5 8 13 18 20 22 25 28:1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16";
    $draw_num = "03,06,10,11,27,33:03";
//    $ret = $drawIssue->__getWagerWinInfo($userWagerNum, $draw_num);

}