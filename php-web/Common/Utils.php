<?php
/**
 * Created by PhpStorm.
 * User: dell-pc
 * Date: 2019/3/26
 * Time: 12:20
 */

namespace VirtulLotto;


class Utils
{
    static $staticC6 = array(
        0, 0, 0, 0, 0, 0, 1,//0-6
        7,
        28,
        84,
        210,
        462,
        924,
        1716,
        3003,
        5005,
        8008,
        12376,
        18564,
        27132,
        38760,
        54264,
        74613,
        100947,
        134596,
        177100,
        230230,
        296010,
        376740,
        475020,
        593775,
        736281,
        906192
    );
    function __construct()
    {

    }

    static function sql_to_html_table( $sqlresult, $delim="\n") {
        if ($sqlresult == null) {
            return "";
        }
        // starting table
        $htmltable =  "<table border=\"1\" >" . $delim ;
        $counter   = 0 ;
        // putting in lines
        while( $row = $sqlresult->fetch(\PDO::FETCH_ASSOC)  ){
            if ( $counter===0 ) {
                // table header
                $htmltable .=   "<tr>"  . $delim;
                foreach ($row as $key => $value ) {
                    $htmltable .=   "<th>" . $key . "</th>"  . $delim ;
                }
                $htmltable .=   "</tr>"  . $delim ;
                $counter = 22;
            }
            // table body
            $htmltable .=   "<tr>"  . $delim ;
            foreach ($row as $key => $value ) {
                $htmltable .=   "<td>" . $value . "</td>"  . $delim ;
            }
            $htmltable .=   "</tr>"   . $delim ;
        }
        // closing table
        $htmltable .=   "</table>"   . $delim ;
        // return
        return( $htmltable ) ;
    }


    //阶乘
    static function __N($n) {
        if ($n <= 0) {
            return 0;
        }
        $ret = 1;
        while ($n > 0) {
            $ret = $ret*$n;
            $n -= 1;
        }
        return $ret;
    }
    //组合
    static function __C($n, $m) {
        if ($n < $m) {
            return 0;
        } else if ($n == $m) {
            return 1;
        }

        if ($n <= 32) {
            return self::$staticC6[$n];
        }

        $a = self::__N($n);
        $x = self::__N($m);
        $y = self::__N($n - $m);
        $ret = $a / ($x * $y);
        return $ret;
    }

    static function __split($sp, $str) {
        $ret = explode($sp, $str);
        return $ret;
    }

    static function getMsectime() {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectime;
    }

    static function newTransNo() {
        return "Trans" . self::getMsectime();
    }

    //通用头 todo：暂时放这里
    static function getCommHead() {
        header("Content-Type: text/html;charset=utf-8");
        return "<div ><a  ><font size='6'>假装很有钱，为所欲为</font></a></div>";
    }
}

if (false) {
    //test
    $sp = Utils::__split(",", "a, b, c");
    print_r($sp);

    for ($i = 7; $i<=32; $i++) {
        echo  "" . Utils::__C($i, 6). ",\n";
    }
}