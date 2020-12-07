/*
SQLyog Professional v13.1.1 (64 bit)
MySQL - 10.4.14-MariaDB : Database - chat_room
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`chat_room` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `chat_room`;

/*Table structure for table `blocked_users` */

DROP TABLE IF EXISTS `blocked_users`;

CREATE TABLE `blocked_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(20) CHARACTER SET utf8mb4 DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 DEFAULT NULL,
  `blocked_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `blocked_users` */

/*Table structure for table `roommate` */

DROP TABLE IF EXISTS `roommate`;

CREATE TABLE `roommate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `you` varchar(32) NOT NULL,
  `roommate` varchar(32) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `created_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `you` (`you`),
  FULLTEXT KEY `roommate` (`roommate`)
) ENGINE=InnoDB AUTO_INCREMENT=419 DEFAULT CHARSET=utf8;

/*Table structure for table `rooms` */

DROP TABLE IF EXISTS `rooms`;

CREATE TABLE `rooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `room_name` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `rooms` */

insert  into `rooms`(`id`,`room_name`,`created_by`,`created_date`) values 
(1,'Welcome',0,'2020-11-28 06:18:33');

/*Table structure for table `user_role` */

DROP TABLE IF EXISTS `user_role`;

CREATE TABLE `user_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(20) CHARACTER SET utf8mb4 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `user_role` */

insert  into `user_role`(`id`,`role_name`) values 
(1,'admin'),
(2,'user');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `user_id` varchar(32) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(10) CHARACTER SET utf8mb4 DEFAULT 'other',
  `ip_address` varchar(20) CHARACTER SET utf8mb4 DEFAULT NULL,
  `created_date` int(10) NOT NULL,
  `user_role` varchar(5) DEFAULT 'user',
  `check_timeout` int(10) DEFAULT NULL,
  `chat_room` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
