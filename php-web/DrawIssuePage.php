<?php
/**
 * Created by PhpStorm.
 * User: dell-pc
 * Date: 2019/3/30
 * Time: 15:11
 */

require_once "Common/Config.php";
require_once "Common/DrawIssue.php";

session_start();

$conn = (new \VirtulLotto\DBConfig())->newConn();
$drawIssue = new \VirtulLotto\DrawIssue($conn);
$ret = $drawIssue->updateBullNum();
if ($ret == 0) {
    $ret = $drawIssue->drawIssue();
}
if ($ret != 0) {
    echo  "";
} else {
    $conn->commit();
    header("Location: ./userProfile.php");
    exit(0);
}

?>
<html>
<body>
<div ><a>错误</a></div>
</body>
</html>
