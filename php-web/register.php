<?php
/**
 * Created by PhpStorm.
 * User: dell-pc
 * Date: 2019/3/24
 * Time: 16:32
 */

require_once "Common/Config.php";
require_once "Common/User.php";
require_once "Common/UserAccount.php";
require_once "Common/Utils.php";

//$_POST['username'] = 'hyjj';
//$_POST['password'] = '123456';


session_start();
//  这种方法是将原来注册的某个变量销毁
unset($_SESSION['admin']);
//  这种方法是销毁整个 Session 文件
session_destroy();


//重新开session
session_start();

$posts = $_POST;
//  清除一些空白符号
foreach ($posts as $key => $value) {
    $posts[$key] = trim($value);
}

$password = md5($posts["password"]);
$username = $posts["username"];

if ($username == null) {

} else {

    $conn = (new  \VirtulLotto\DBConfig())->newConn();
    $user = new \VirtulLotto\User($conn);
    $userId = $user->addUser($username, $password);
    if ($userId != null) {

        $userAccount = new \VirtulLotto\UserAccount($conn);
        $ret = $userAccount->createAccount($userId);
        if ($ret == 0) {
            //新用户奖励
            $transNo = \VirtulLotto\Utils::newTransNo();

            $ret = $userAccount->addMoney($transNo, $userId, 10000, '新用户奖励', 3);
            if ($ret == 0) {
                $conn->commit();
                header("Location: ./index.php");
                exit(0);
            }
        }
    }

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
    <input type="submit" name="submit" value="注册"/>
</form>
</body>
</html>




