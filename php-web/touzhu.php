<?php
/**
 * Created by PhpStorm.
 * User: dell-pc
 * Date: 2019/3/24
 * Time: 17:25
 */

require_once "Common/Config.php";
require_once "Common/Touzhu.php";
require_once "Common/UserInfo.php";


//redNumInput=11+12+13+15+16+&blueNumInput=4+5+8+11+12+&%E7%A1%AE%E5%AE%9A%E6%8A%95%E6%B3%A8=%E6%8F%90%E4%BA%A4%E6%9F%A5%E8%AF%A2

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

$redNumInput = $_POST['redNumInput'];
$blueNumInput = $_POST['blueNumInput'];
do {
    if ($redNumInput == null || $blueNumInput == null ) {
        break;
    }
    $redNumInput = substr($redNumInput, 0, -1);
    $redNumInput = str_replace( '+', ' ', $redNumInput);
    $blueNumInput = str_replace('+', ' ', substr($blueNumInput, 0, -1));

    $conn = (new \VirtulLotto\DBConfig())->newConn();
    $touzhu = new \VirtulLotto\Touzhu($conn);
    $ret = $touzhu->touzhu($redNumInput, $blueNumInput, $_SESSION["admin"]);
    if ($ret < 0) {
        $GLOBALS['err'] = $ret;
        break;
    }
    $conn->commit();
    header("Location: ./userProfile.php");
    exit(0);
} while(0);

//开奖公告
$conn = (new \VirtulLotto\DBConfig())->newConn();
$drawIssue = new \VirtulLotto\DrawIssue($conn);
$sth = $drawIssue->getBullList();
$tableStr = \VirtulLotto\Utils::sql_to_html_table($sth);
$GLOBALS['drawIssueTable'] = $tableStr;

//投注列表
$userId = $_SESSION["admin"];
$userInfo = new \VirtulLotto\UserInfo($conn);
$sth = $userInfo->getRecentWagerList($userId);
$tableStr = \VirtulLotto\Utils::sql_to_html_table($sth);
$GLOBALS['wagerTable'] = $tableStr;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>投注-双色球</title>
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript">

        function getBlueNum() {
            var allButton = $(":button");
            var len = allButton.length;

            var retStr = "";

            var index = 0;
            while (index < len) {
                if (allButton[index].name == "blueNum" && allButton[index].className == "btn btn-primary") {
                    var button = allButton[index];
                    var num =button.innerText;
                    retStr +=  num + " ";
                }
                index += 1;
            }
            return retStr;
        }

        function getRedNum() {
            var allButton = $(":button");
            var len = allButton.length;

            var retStr = "";

            var index = 0;
            while (index < len) {
                if (allButton[index].name == "redNum" && allButton[index].className == "btn btn-danger") {
                    var button = allButton[index];
                    var num =button.innerText;
                    retStr +=  num + " ";
                }
                index += 1;
            }
            return retStr;
        }

        // 刷新纪录
        function reflushNum() {
            var redNum = getRedNum();
            var blueNum = getBlueNum();
            $("#redNumStr")[0].innerText = redNum;
            $("#blueNumStr")[0].innerText = blueNum;

            $("#redNumInputID")[0].value = redNum;
            $("#blueNumInputID")[0].value = blueNum;

            getAccountAndCalcFee(redNum, blueNum);
        }

        function getAccountAndCalcFee(redNumInput, blueNumInput) {
            $.post("touzhu_check.php",
                {
                    redNumInput:redNumInput,
                    blueNumInput:blueNumInput
                },
                function(data,status){
//                    alert("数据: \n" + data.accountFee + "\n" + data.touzhFee + "\n状态: " + status);
                    $("#userFee")[0].innerText = data.accountFee;
                    $("#touzhFee")[0].innerText = data.touzhFee;
                },
                "json"
            );
        }

        $(function() {
            //初始化
            getAccountAndCalcFee(null, null);

            //记录号码
            $(".btn").click(function(){
                if (this.name == "redNum") {
                    if (this.className == "btn btn-default") {
                        this.className = "btn btn-danger";
                    } else  {
                        this.className = "btn btn-default";
                    }
                }

                if (this.name == "blueNum") {
                    if (this.className == "btn btn-default") {
                        this.className = "btn btn-primary";
                    } else  {
                        this.className = "btn btn-default";
                    }
                }
                reflushNum();
            });

            //清除
            $("#cleanButton").click(function() {
                var allButton = $(":button");
                var len = allButton.length;
                var index = 0;
                while (index < len) {
                    if (allButton[index].name == "redNum" || allButton[index].name == "blueNum") {
                        allButton[index].className = "btn btn-default";
                    }
                    index += 1;
                }
                reflushNum();
            });

        });
    </script>
</head>
<body>
<?php echo \VirtulLotto\Utils::getCommHead(); ?>
<div><a><?php echo $GLOBALS['err']; ?></a></div>
<a href="userProfile.php">返回</a>
<br>

<a>红色号码</a>
<?php
echo "<div >";
for ($i = 1; $i <= 10; $i++) {
    echo "<button type=\"button\" name='redNum' class=\"btn btn-default\">$i</button>";
}
echo "</div>";

echo "<div >";
for ($i = 11; $i <= 20; $i++) {
    echo "<button type=\"button\"  name='redNum'  class=\"btn btn-default\">$i</button>";
}
echo "</div>";

echo "<div >";
for ($i = 21; $i <= 30; $i++) {
    echo "<button type=\"button\" name='redNum'  class=\"btn btn-default\">$i</button>";
}
echo "</div>";

echo "<div >";
for ($i = 31; $i <= 33; $i++) {
    echo "<button type=\"button\" name='redNum'  class=\"btn btn-default\">$i</button>";
}
echo "</div>";

echo "<a>蓝色号码</a>";

echo "<div >";
for ($i = 1; $i <= 16; $i++) {
    echo "<button type=\"button\" name='blueNum'  class=\"btn btn-default\">$i</button>";
}
echo "</div>";

?>

<div ><a>当前余额:<a id="userFee">0</a></a> <a>  投注花费:<a id="touzhFee">0</a></a></div>
<div >
    <a >已选择号码：<br>
        红色球：<a id="redNumStr"></a><br>
        蓝色球：<a id="blueNumStr"></a><br>
        <form method="post" action="">
            <input type="hidden" id="redNumInputID" name="redNumInput" value="">
            <input type="hidden" id="blueNumInputID" name="blueNumInput" value="">
            <input type="submit" name="确定投注">
        </form>
        <button id="cleanButton">清除</button>
</div>

<div ><a>开奖公告</a></div>
<?php echo $GLOBALS['drawIssueTable']; ?>

<div ><a>个人投注情况</a></div>
<?php echo $GLOBALS['wagerTable']; ?>

</body>
</html>