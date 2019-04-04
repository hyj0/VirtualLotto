<?php
/**
 * Created by PhpStorm.
 * User: dell-pc
 * Date: 2019/3/24
 * Time: 16:32
 */

require_once "Common/Config.php";
require_once "Common/User.php";
require_once "Common/Utils.php";
//PHP会话(Session)实现用户登陆功能
// https://www.cnblogs.com/happyforev1/articles/1645916.html

//$_POST['password'] = '12345';
//$_POST['username'] = 'admin';

$posts = $_POST;
//  清除一些空白符号
foreach ($posts as $key => $value) {
    $posts[$key] = trim($value);
}


$password = md5($posts["password"]);
$username = $posts["username"];


//todo：测试 这里要验证
$userInfo = false;
if ($username == null) {
    $userInfo = false;
} else {
    $mysql = \VirtulLotto\DBConfig::newConn();
    $user = new \VirtulLotto\User($mysql);
    $result = $user->getUserByName($username);
    if ($result == null) {

    } else {
        $dbPwd = $result["passwd"];
        if ($dbPwd == $password) {
            $userInfo = $result["user_id"];
        }
    }
}

if (!empty($userInfo)) {
    //  当验证通过后，启动 Session
    session_start();
    //  注册登陆成功的 admin 变量，并赋值 true
    $_SESSION["admin"] = $userInfo;
    header("Location: ./userProfile.php");
    exit(0);
} else {
//    die("用户名密码错误");
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>登陆</title>
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<?php echo \VirtulLotto\Utils::getCommHead(); ?>


<form method="post" action="">
    姓名：<input type="text" name="username" />
    密码：<input type="password" name="password"/>
    <input type="submit" name="submit" value="登入"/>
</form>
<div><a href="register.php">注册</a></div>
</body>
</html>


