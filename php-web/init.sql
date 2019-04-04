-- MySQL dump 10.13  Distrib 5.6.28, for Win32 (AMD64)
--
-- Host: localhost    Database: lotty
-- ------------------------------------------------------
-- Server version	5.6.28

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `t_bullnum`
--

DROP TABLE IF EXISTS `t_bullnum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_bullnum` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `draw_issue` int(11) DEFAULT '1' COMMENT '开奖期号 比如 2019032',
    `draw_num` varchar(64) NOT NULL COMMENT '开奖号码 比如 04,08,09,13,28,33:04',
    `draw_time` datetime NOT NULL COMMENT '开奖日期',
    `create_time` datetime NOT NULL,
    `update_time` datetime NOT NULL,
    `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态 1--未开奖 2--已开奖',
    `pre_issue` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `t_bullnum_draw_issue_uindex` (`draw_issue`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='开奖公告表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_user`
--

DROP TABLE IF EXISTS `t_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_user` (
    `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
    `user_name` varchar(64) NOT NULL COMMENT '用户名',
    `regster_time` datetime NOT NULL COMMENT '注册时间',
    `icon_url` varchar(64) DEFAULT NULL COMMENT '头像',
    `create_time` datetime DEFAULT NULL,
    `update_time` datetime DEFAULT NULL,
    `passwd` varchar(64) DEFAULT NULL,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `t_user_user_name_uindex` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=10014 DEFAULT CHARSET=utf8 COMMENT='用户表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_user_account`
--

DROP TABLE IF EXISTS `t_user_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_user_account` (
    `user_id` int(11) NOT NULL,
    `fee` int(11) NOT NULL DEFAULT '0' COMMENT '余额单位分',
    `frozen_fee` int(11) NOT NULL DEFAULT '0',
    `account_status` int(11) NOT NULL COMMENT '状态 1--正常',
    `create_time` datetime DEFAULT NULL,
    `update_time` datetime DEFAULT NULL,
    PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户账户表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_user_account_log`
--

DROP TABLE IF EXISTS `t_user_account_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_user_account_log` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `uid` int(11) NOT NULL,
    `type` int(11) NOT NULL COMMENT '类型 1--投注 2--中奖入账 3--注册赠送',
    `sub_type` int(11) NOT NULL COMMENT '收支类型 1--收入 2--支出',
    `trans_no` varchar(100) NOT NULL COMMENT '交易号',
    `fee` int(11) NOT NULL COMMENT '金额，单位分',
    `org_fee` int(11) NOT NULL COMMENT '交易前的余额',
    `end_fee` int(11) NOT NULL COMMENT '交易后的余额',
    `update_time` datetime NOT NULL,
    `create_time` datetime NOT NULL,
    `note` varchar(64) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `t_account_log_trans_no_uid_uindex` (`trans_no`,`uid`,`type`,`sub_type`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_user_wager`
--

DROP TABLE IF EXISTS `t_user_wager`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_user_wager` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL COMMENT '用户ID',
    `pre_issue` int(11) NOT NULL COMMENT '前一期',
    `draw_issue` int(11) DEFAULT NULL COMMENT '开奖期',
    `wager_num` varchar(128) NOT NULL COMMENT '投注号码 比如 01,02,03,04,05,06,07 11,12',
    `trans_no` varchar(64) NOT NULL COMMENT '交易号',
    `status` int(11) NOT NULL COMMENT '业务状态 1--未支付 2--已支付 3--已开奖  4--已支付奖金 5--已完成',
    `create_time` datetime DEFAULT NULL,
    `update_time` datetime DEFAULT NULL,
    `win_fee` int(11) NOT NULL DEFAULT '0',
    `win_level` int(11) NOT NULL DEFAULT '-1',
    `wager_fee` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='用户投注表';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-31 23:56:39
