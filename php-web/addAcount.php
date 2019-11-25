<?php
/**
 * Created by IntelliJ IDEA.
 * User: hyj
 * Date: 2019-11-25
 * Time: 23:08
 */

include_once "Common/Utils.php";
include_once "Common/Config.php";
include_once "Common/UserAccount.php";

//  防止全局变量造成安全隐患
$admin = false;
//  启动会话，这步必不可少
session_start();
//  判断是否登陆
if (isset($_SESSION["admin"]) && $_SESSION["admin"] > 0) {
//    echo "您已经成功登陆";
} else {
    //  验证失败，将 $_SESSION["admin"] 置为 false
    $_SESSION["admin"] = false;
    die("您无权访问 请登陆");
}
//header("Content-Type: text/html;charset=utf-8");
$moneyYuan = $_POST['moneyYuan'];
do {
    if ($moneyYuan == null) {
        break;
    }
    if ($moneyYuan > 100000 || $moneyYuan <= 0) {
        echo "参数错误";
        break;
    }
    $userId = $_SESSION["admin"];
    $conn = (new  \VirtulLotto\DBConfig())->newConn();
    $userAcc = new \VirtulLotto\UserAccount($conn);
    $transNo = \VirtulLotto\Utils::newTransNo();
    $userAcc->addMoney($transNo, $userId, $moneyYuan, "充值", 4);
    if ($ret == 0) {
        $conn->commit();
        header("Location: ./userProfile.php");
        exit(0);
    } else {
        echo "错误".$ret;
    }
} while(false);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>充值</title>
</head>
<body>
<?php echo \VirtulLotto\Utils::getCommHead(); ?>
<div  ><a href="logout.php">退出登录</a></div>
<div  ><a href="userProfile.php">主页</a></div>
<div>
    <form method="post" action="">
        <input type="number" name="moneyYuan" value="100000">
        <input type="submit" name="确定充值">
    </form>
</div>

</body>
</html>
