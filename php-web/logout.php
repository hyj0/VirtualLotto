<?php
/**
 * Created by PhpStorm.
 * User: dell-pc
 * Date: 2019/3/24
 * Time: 16:38
 */

session_start();
//  这种方法是将原来注册的某个变量销毁
unset($_SESSION['admin']);
//  这种方法是销毁整个 Session 文件
session_destroy();

header("Location: ./index.php");
exit(0);
?>