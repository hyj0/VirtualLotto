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
-- Dumping data for table `t_bullnum`
--

LOCK TABLES `t_bullnum` WRITE;
/*!40000 ALTER TABLE `t_bullnum` DISABLE KEYS */;
INSERT INTO `t_bullnum` VALUES (1,2019032,'04,08,09,13,28,33:04','2019-03-21 00:00:00','2019-03-24 22:09:16','2019-03-30 18:15:07',2,2019031),(2,2019033,'09,15,19,21,23,29:15','2019-03-24 00:00:00','2019-03-24 22:09:16','2019-03-30 18:15:09',2,2019032),(3,2019034,'09,11,15,22,24,26:03','2019-03-26 00:00:00','2019-03-30 16:51:57','2019-03-30 19:12:19',2,2019033),(4,2019035,'01,05,07,09,10,20:16','2019-03-28 00:00:00','2019-03-30 16:52:03','2019-03-30 18:30:56',2,2019034),(5,2019036,'02,10,13,16,23,32:08','2019-03-31 00:00:00','2019-03-31 00:00:00','2019-03-31 22:19:39',2,2019035);
/*!40000 ALTER TABLE `t_bullnum` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `t_user`
--

LOCK TABLES `t_user` WRITE;
/*!40000 ALTER TABLE `t_user` DISABLE KEYS */;
INSERT INTO `t_user` VALUES (1,'hyj','2019-03-26 00:12:40','','2019-03-26 00:12:40','2019-03-26 00:12:40','e10adc3949ba59abbe56e057f20f883e'),(10002,'hyj0','2019-03-26 01:02:08','','2019-03-26 01:02:08','2019-03-26 01:02:08','e10adc3949ba59abbe56e057f20f883e'),(10010,'hyjj','2019-03-30 22:42:43','','2019-03-30 22:42:43','2019-03-30 22:42:43','e10adc3949ba59abbe56e057f20f883e'),(10013,'hyj01','2019-03-30 22:44:44','','2019-03-30 22:44:44','2019-03-30 22:44:44','e10adc3949ba59abbe56e057f20f883e');
/*!40000 ALTER TABLE `t_user` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `t_user_account`
--

LOCK TABLES `t_user_account` WRITE;
/*!40000 ALTER TABLE `t_user_account` DISABLE KEYS */;
INSERT INTO `t_user_account` VALUES (1,7552,0,1,'2019-03-30 19:42:16','2019-03-31 22:19:39'),(10002,7982,0,1,'2019-03-30 19:42:32','2019-03-30 23:15:48'),(10010,7962,0,1,'2019-03-30 22:42:43','2019-03-31 22:19:39'),(10013,10000,0,1,'2019-03-30 22:44:47','2019-03-30 22:44:49');
/*!40000 ALTER TABLE `t_user_account` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `t_user_account_log`
--

LOCK TABLES `t_user_account_log` WRITE;
/*!40000 ALTER TABLE `t_user_account_log` DISABLE KEYS */;
INSERT INTO `t_user_account_log` VALUES (2,1,1,2,'Trans1553951888333',70,10000,10070,'2019-03-30 21:18:10','2019-03-30 21:18:10','投注'),(3,1,1,2,'Trans1553952330196',70,10070,10140,'2019-03-30 21:25:30','2019-03-30 21:25:30','投注'),(4,1,1,2,'Trans1553952335105',70,10140,10210,'2019-03-30 21:25:35','2019-03-30 21:25:35','投注'),(5,1,1,2,'Trans1553952358296',70,10210,10280,'2019-03-30 21:25:58','2019-03-30 21:25:58','投注'),(6,1,1,2,'Trans1553952872873',42,10280,10322,'2019-03-30 21:34:32','2019-03-30 21:34:32','投注'),(8,1,1,2,'Trans1553955096384',70,10238,10308,'2019-03-30 22:12:01','2019-03-30 22:12:01','投注'),(9,1,1,2,'Trans1553955404537',98,10168,10266,'2019-03-30 22:16:54','2019-03-30 22:16:54','投注'),(10,1,1,2,'Trans1553955581814',2,10070,10072,'2019-03-30 22:20:01','2019-03-30 22:20:01','投注'),(11,1,1,2,'Trans1553955720884',2,10068,-10066,'2019-03-30 22:22:00','2019-03-30 22:22:00','投注'),(12,1,1,2,'Trans1553955860448',4,10066,10062,'2019-03-30 22:24:20','2019-03-30 22:24:20','投注'),(13,1,1,2,'Trans1553955980481',2520,10062,7542,'2019-03-30 22:26:20','2019-03-30 22:26:20','投注'),(15,10002,1,2,'TransNo1553956930352',2,10000,9998,'2019-03-30 22:42:10','2019-03-30 22:42:10','投注'),(16,10010,3,1,'Trans1553956963935',10000,0,10000,'2019-03-30 22:42:43','2019-03-30 22:42:43','新用户奖励'),(17,10013,3,1,'Trans1553957088890',10000,0,10000,'2019-03-30 22:44:49','2019-03-30 22:44:49','新用户奖励'),(18,10010,1,2,'Trans1553957487326',4,10000,9996,'2019-03-30 22:51:27','2019-03-30 22:51:27','投注'),(19,10010,1,2,'Trans1553957543592',84,9996,9912,'2019-03-30 22:52:23','2019-03-30 22:52:23','投注'),(20,10010,1,2,'Trans1553957737837',280,9912,9632,'2019-03-30 22:55:37','2019-03-30 22:55:37','投注'),(21,10010,1,2,'Trans1553958175193',1680,9632,7952,'2019-03-30 23:02:55','2019-03-30 23:02:55','投注'),(22,10002,1,2,'Trans1553958483596',672,9998,9326,'2019-03-30 23:08:03','2019-03-30 23:08:03','投注'),(23,10002,1,2,'Trans1553958894431',1176,9326,8150,'2019-03-30 23:14:54','2019-03-30 23:14:54','投注'),(24,10002,1,2,'Trans1553958948685',168,8150,7982,'2019-03-30 23:15:48','2019-03-30 23:15:48','投注'),(25,1,2,1,'Trans1553955096384',5,7542,7547,'2019-03-31 22:19:39','2019-03-31 22:19:39','奖金'),(26,1,2,1,'Trans1553955404537',5,7547,7552,'2019-03-31 22:19:39','2019-03-31 22:19:39','奖金'),(27,10010,2,1,'Trans1553957543592',5,7952,7957,'2019-03-31 22:19:39','2019-03-31 22:19:39','奖金'),(28,10010,2,1,'Trans1553957737837',5,7957,7962,'2019-03-31 22:19:39','2019-03-31 22:19:39','奖金');
/*!40000 ALTER TABLE `t_user_account_log` ENABLE KEYS */;
UNLOCK TABLES;

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

--
-- Dumping data for table `t_user_wager`
--

LOCK TABLES `t_user_wager` WRITE;
/*!40000 ALTER TABLE `t_user_wager` DISABLE KEYS */;
INSERT INTO `t_user_wager` VALUES (1,1,2019033,2019034,'1 2 3 4 5 6:1 2 3 4','Trans1553533014622',3,'2019-03-26 00:56:54','2019-03-30 18:09:30',0,0,0),(2,1,2019033,2019034,'5 6 17 18 22 24:5 8 10','Trans1553533089987',3,'2019-03-26 00:58:09','2019-03-30 18:09:37',0,0,0),(3,10002,2019033,2019034,'11 15 18 23 27 29:2 6 11','Trans1553533370667',3,'2019-03-26 01:02:50','2019-03-30 18:09:41',0,0,0),(4,10002,2019033,2019034,'09 11 15 22 24 26:03 5 6','Trans1553573721340',3,'2019-03-26 12:15:21','2019-03-30 18:29:53',10000000,1,0),(5,10002,2019033,2019034,'1 2 3 4 5 6:1 2 3 4 5 6 7 8','Trans1553575383068',3,'2019-03-26 12:43:03','2019-03-30 18:09:47',0,0,0),(6,10002,2019035,2019036,'1 2 3 4 5 7 8:5 7','Trans1553936370200',5,'2019-03-30 16:59:30','2019-03-31 22:19:39',0,-1,0),(7,10002,2019035,2019036,'3 7 13 14 16 18:5 7 10','Trans1553942413155',5,'2019-03-30 18:40:13','2019-03-31 22:19:39',0,-1,0),(8,10002,2019035,2019036,'4 6 10 11 16 19 24 27 30:2 5 9 11 13 15','Trans1553942462839',5,'2019-03-30 18:41:02','2019-03-31 22:19:39',0,-1,0),(9,1,2019035,2019036,'8 13 16 18 23 26 27:7 10 11','Trans1553944848382',5,'2019-03-30 19:20:48','2019-03-31 22:19:39',0,-1,0),(11,1,2019035,2019036,'11,12,13,15,16,17,18:4,5,8,11,12','Trans1553951888333',5,'2019-03-30 21:18:13','2019-03-31 22:19:39',0,-1,0),(12,1,2019035,2019036,'11,12,13,15,16,17,18:4,5,8,11,12','Trans1553952330196',5,'2019-03-30 21:25:30','2019-03-31 22:19:39',0,-1,0),(13,1,2019035,2019036,'11,12,13,15,16,17,18:4,5,8,11,12','Trans1553952335105',5,'2019-03-30 21:25:35','2019-03-31 22:19:39',0,-1,0),(14,1,2019035,2019036,'11,12,13,15,16,17,18:4,5,8,11,12','Trans1553952358296',5,'2019-03-30 21:25:58','2019-03-31 22:19:39',0,-1,0),(15,1,2019035,2019036,'4 8 11 14 15 18 27:4 5 6','Trans1553952872873',5,'2019-03-30 21:34:32','2019-03-31 22:19:39',0,-1,0),(17,1,2019035,2019036,'11 12 13 15 16 17 18:4 5 8 11 12','Trans1553955096384',4,'2019-03-30 22:12:01','2019-03-31 22:19:39',5,6,0),(18,1,2019035,2019036,'2 5 7 14 15 16 18:5 6 7 8 9 10 11','Trans1553955404537',4,'2019-03-30 22:16:57','2019-03-31 22:19:39',5,6,0),(19,1,2019035,2019036,'1 2 3 4 5 6:1','Trans1553955581814',5,'2019-03-30 22:20:03','2019-03-31 22:19:39',0,-1,0),(20,1,2019035,2019036,'1 2 13 15 24 25:3','Trans1553955720884',5,'2019-03-30 22:22:00','2019-03-31 22:19:39',0,-1,0),(21,1,2019035,2019036,'1 2 3 4 5 7:3 5','Trans1553955860448',5,'2019-03-30 22:24:20','2019-03-31 22:19:39',0,-1,0),(22,1,2019035,2019036,'1 5 8 14 16 17 18 22 25 28:4 5 7 10 12 13','Trans1553955980481',5,'2019-03-30 22:26:20','2019-03-31 22:19:39',0,-1,0),(23,10002,2019035,2019036,'1 2 3 4 5 6:4','TransNo1553956930352',5,'2019-03-30 22:42:10','2019-03-31 22:19:39',0,-1,0),(24,10010,2019035,2019036,'1 2 3 4 5 6:5 6','Trans1553957487326',5,'2019-03-30 22:51:27','2019-03-31 22:19:39',0,-1,0),(25,10010,2019035,2019036,'4 6 12 18 23 25 26:4 8 9 11 12 14','Trans1553957543592',4,'2019-03-30 22:52:23','2019-03-31 22:19:39',5,6,0),(26,10010,2019035,2019036,'4 8 11 17 18 23 25 27:1 3 5 7 8','Trans1553957737837',4,'2019-03-30 22:55:37','2019-03-31 22:19:39',5,6,0),(27,10010,2019035,2019036,'4 12 14 16 18 19 23 25 26 31:3 6 7 11','Trans1553958175193',5,'2019-03-30 23:02:55','2019-03-31 22:19:39',0,-1,0),(28,10002,2019035,2019036,'2 4 6 13 15 18 21 23 25:4 6 9 12','Trans1553958483596',5,'2019-03-30 23:08:03','2019-03-31 22:19:39',0,-1,0),(29,10002,2019035,2019036,'12 13 16 18 19 22 26 27 29:3 5 7 10 13 14 16','Trans1553958894431',5,'2019-03-30 23:14:54','2019-03-31 22:19:39',0,-1,1176),(30,10002,2019035,2019036,'6 12 14 17 23 25 28 30:4 6 9','Trans1553958948685',5,'2019-03-30 23:15:48','2019-03-31 22:19:39',0,-1,168);
/*!40000 ALTER TABLE `t_user_wager` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-31 23:54:42
