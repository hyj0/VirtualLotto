<?php
/**
 * Created by PhpStorm.
 * User: dell-pc
 * Date: 2019/3/30
 * Time: 20:34
 */

require_once "Common/Config.php";
require_once "Common/Touzhu.php";

session_start();
//debug
if (false) {
    $_SESSION["admin"] = 1;
    $_POST['redNumInput'] = '11+12+13+15+16+17+18+';
    $_POST['blueNumInput'] = '4+5+8+11+12+';
}
//  判断是否登陆
if (isset($_SESSION["admin"]) && $_SESSION["admin"] > 0) {
//    echo "您已经成功登陆";
} else {
    //  验证失败，将 $_SESSION["admin"] 置为 false
    $_SESSION["admin"] = false;
    die("您无权访问 请登陆");
}

$userId = $_SESSION["admin"];
$redNumInput = $_POST['redNumInput'];
$blueNumInput = $_POST['blueNumInput'];

//账户
$conn = (new \VirtulLotto\DBConfig())->newConn();
$account = new \VirtulLotto\UserAccount($conn);
$userAcountInfo = $account->getAccount($userId);

//计算投注金额
$fee = 0;
if ($redNumInput == null or $blueNumInput == null) {
    $fee = 0;
} else {
    if (count($redNumInput) <= 0 or count($blueNumInput) <= 0) {
        $fee = 0;
    } else {
        $redNumInput = substr($redNumInput, 0, -1);
        $redNumInput = str_replace( '+', ' ', $redNumInput);
        $blueNumInput = str_replace('+', ' ', substr($blueNumInput, 0, -1));

        $touzhu = new \VirtulLotto\Touzhu($conn);
        $fee = $touzhu->calcMoney($redNumInput, $blueNumInput);
    }
}
echo  json_encode(array(
    "accountFee" => $userAcountInfo['fee'],
    "touzhFee" => $fee,
    "red" => $redNumInput,
    "blue" => $blueNumInput
));

?>