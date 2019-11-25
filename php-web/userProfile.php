<?php
/**
 * Created by PhpStorm.
 * User: dell-pc
 * Date: 2019/3/24
 * Time: 16:34
 */

require_once "Common/Config.php";
require_once "Common/User.php";
require_once "Common/UserInfo.php";
require_once "Common/Utils.php";
require_once "Common/DrawIssue.php";
require_once "Common/UserAccount.php";

//  防止全局变量造成安全隐患
$admin = false;
//  启动会话，这步必不可少
session_start();
//  判断是否登陆
//debug
//$_SESSION["admin"] = 10010;
if (isset($_SESSION["admin"]) && $_SESSION["admin"] > 0) {
//    echo "您已经成功登陆";
} else {
    //  验证失败，将 $_SESSION["admin"] 置为 false
    $_SESSION["admin"] = false;
    die("您无权访问 请登陆");
}

$userId = $_SESSION["admin"];

$conn = (new \VirtulLotto\DBConfig())->newConn();

//用户信息
$user = new \VirtulLotto\User($conn);
$userData = $user->getUserByUid($userId);
if ($userData != null) {

}
$GLOBALS['userData'] = $userData;

//用户账户信息
$account = new \VirtulLotto\UserAccount($conn);
$accountInfo = $account->getAccount($userId);
$GLOBALS['accountInfo'] = $accountInfo;

//投注列表
$userInfo = new \VirtulLotto\UserInfo($conn);
$sth = $userInfo->getRecentWagerList($userId);
$tableStr = \VirtulLotto\Utils::sql_to_html_table($sth);
$GLOBALS['wagerTable'] = $tableStr;

//开奖公告
$drawIssue = new \VirtulLotto\DrawIssue($conn);
$sth = $drawIssue->getBullList();
$tableStr = \VirtulLotto\Utils::sql_to_html_table($sth);
$GLOBALS['drawIssueTable'] = $tableStr;

//账户流水
$sth = $account->getAccountLogList($userId);
$tableStr = \VirtulLotto\Utils::sql_to_html_table($sth);
$GLOBALS['accountLog'] = $tableStr;

//开奖
if ($userId ) {
    $GLOBALS['DrawIssuePage'] = "<div  ><a href=\"DrawIssuePage.php\">开奖</a></div>";
} else {
    $GLOBALS['DrawIssuePage'] = "";
}

?>

<html>
<body>
<?php echo \VirtulLotto\Utils::getCommHead(); ?>
<div  ><a href="logout.php">退出登录</a></div>
<?php echo $GLOBALS['DrawIssuePage']; ?>
<div  ><a href="touzhu.php">投注</a></div>
<div  ><a href="addAcount.php">充值</a></div>
<div ><a><?php echo $GLOBALS['userData']['user_name'] . " 余额:". $GLOBALS['accountInfo']['fee']."元"; ?></a></div>

<div ><a>个人投注情况</a></div>
<?php echo $GLOBALS['wagerTable']; ?>

<div ><a>开奖公告</a></div>
<?php echo $GLOBALS['drawIssueTable']; ?>

<div ><a>账户流水</a></div>
<?php echo $GLOBALS['accountLog']; ?>


</body>
</html>


