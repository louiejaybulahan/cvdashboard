/*
Navicat MySQL Data Transfer

Source Server         : localConnection
Source Server Version : 50717
Source Host           : localhost:3306
Source Database       : cvdashboard

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2018-03-23 17:26:01
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `audit_trail`
-- ----------------------------
DROP TABLE IF EXISTS `audit_trail`;
CREATE TABLE `audit_trail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `table` varchar(25) NOT NULL,
  `actions` varchar(25) NOT NULL,
  `date_modified` datetime NOT NULL,
  `hh_id` varchar(30) DEFAULT NULL,
  `change_value` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of audit_trail
-- ----------------------------
INSERT INTO audit_trail VALUES ('1', '2', '2.ez.4.mics', 'cash_grant_2017', 'modified', '2017-09-26 14:29:18', '160201028-8812-00003', 'information', null);
INSERT INTO audit_trail VALUES ('2', '3', 'louiejay', 'cash_grant_2016', 'deleted', '2017-09-26 14:32:44', '160201028-8933-00012', null, null);

-- ----------------------------
-- Table structure for `config`
-- ----------------------------
DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `handler` varchar(50) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `handler_unique` (`handler`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of config
-- ----------------------------
INSERT INTO config VALUES ('1', 'PERIOD_START', '2010');
INSERT INTO config VALUES ('2', 'PERIOD_CURRENT', '2017');
INSERT INTO config VALUES ('3', 'AUDIT_TRAIL', '0');

-- ----------------------------
-- Table structure for `tbl_turnout_2017`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_turnout_2017`;
CREATE TABLE `tbl_turnout_2017` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region` varchar(45) NOT NULL,
  `province` varchar(45) NOT NULL,
  `city` varchar(50) NOT NULL,
  `brgy` varchar(60) NOT NULL,
  `category` varchar(60) NOT NULL,
  `set` varchar(2) NOT NULL,
  `setgroup` varchar(2) NOT NULL,
  `eligibility` tinyint(4) NOT NULL DEFAULT '0',
  `not_attend_dominant` tinyint(4) NOT NULL DEFAULT '0',
  `attend_dominant` tinyint(4) NOT NULL DEFAULT '0',
  `attend_del_dominant` tinyint(4) NOT NULL DEFAULT '0',
  `outside` tinyint(4) NOT NULL DEFAULT '0',
  `monitored_dominant` tinyint(4) NOT NULL DEFAULT '0',
  `endcoded_approved` tinyint(4) NOT NULL DEFAULT '0',
  `submitted_deworming` tinyint(4) NOT NULL DEFAULT '0',
  `not_encoded_approved` tinyint(4) NOT NULL DEFAULT '0',
  `encoded_under_forcem` tinyint(4) NOT NULL DEFAULT '0',
  `non_compliant` tinyint(4) NOT NULL DEFAULT '0',
  `compliant` tinyint(4) NOT NULL DEFAULT '0',
  `remarks_1` tinyint(4) NOT NULL DEFAULT '0',
  `remarks_2` tinyint(4) NOT NULL DEFAULT '0',
  `remarks_3` tinyint(4) NOT NULL DEFAULT '0',
  `remarks_4` tinyint(4) NOT NULL DEFAULT '0',
  `year` year(4) NOT NULL DEFAULT '0000',
  `period` tinyint(4) NOT NULL DEFAULT '0',
  `month` tinyint(4) NOT NULL DEFAULT '0',
  `client_status` tinyint(4) NOT NULL DEFAULT '0',
  `sex` tinyint(4) NOT NULL DEFAULT '0',
  `grade_group` tinyint(4) NOT NULL DEFAULT '0',
  `ip` varchar(2) NOT NULL DEFAULT '''''',
  `brgy_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_turnout_2017
-- ----------------------------

-- ----------------------------
-- Table structure for `user_infos`
-- ----------------------------
DROP TABLE IF EXISTS `user_infos`;
CREATE TABLE `user_infos` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `level_id` int(11) DEFAULT '0',
  `username` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `middlename` varchar(45) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `contact` varchar(11) NOT NULL DEFAULT '',
  `REGION_ID` int(9) NOT NULL,
  `avatar` varchar(45) DEFAULT NULL,
  `access` text NOT NULL,
  `access_status` tinyint(1) NOT NULL DEFAULT '0',
  `is_status` tinyint(4) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_infos
-- ----------------------------
INSERT INTO user_infos VALUES ('1', '38', 'admin', '$2y$10$rVmVLVeXpcIAA/Y/KItKc.px2BQWc2hkCfonbAQcchadiZDh2Is8G', 'Juan', 'Dela Cruz', 'Jose', 'admin@gmail.com', '09484334354', '18', null, ' ', '0', '1', 'PJFLYxWNiSHclJiBmW8UMiCHI3DNir5pymz2DqD8VcQOFQMlVHPV8tAsk73s', '2018-01-24 10:14:48', '2018-01-24 10:14:50');
INSERT INTO user_infos VALUES ('2', '17', 'cvfocal', '$2y$10$rVmVLVeXpcIAA/Y/KItKc.px2BQWc2hkCfonbAQcchadiZDh2Is8G', 'Regional CVS Focal', 'Regional CVS Focal', 'Regional CVS Focal', 'cvfocal@gmail.com', '09484334354', '16', null, ' ', '0', '1', null, '2018-01-29 10:09:50', '2018-01-29 10:09:52');

-- ----------------------------
-- Table structure for `user_level`
-- ----------------------------
DROP TABLE IF EXISTS `user_level`;
CREATE TABLE `user_level` (
  `level_id` int(11) NOT NULL AUTO_INCREMENT,
  `level_name` varchar(50) NOT NULL,
  `is_status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_level
-- ----------------------------
INSERT INTO user_level VALUES ('1', 'Central Encoder', '1');
INSERT INTO user_level VALUES ('2', 'BDMD Central Validator (Level 1)', '1');
INSERT INTO user_level VALUES ('3', 'Viewer', '1');
INSERT INTO user_level VALUES ('4', 'Regional GRS Focal', '1');
INSERT INTO user_level VALUES ('5', 'Finance People', '1');
INSERT INTO user_level VALUES ('6', 'BDMD (Level 3)', '1');
INSERT INTO user_level VALUES ('7', 'Regional Encoder', '1');
INSERT INTO user_level VALUES ('8', 'CVS Focal', '1');
INSERT INTO user_level VALUES ('9', 'Regional Director', '1');
INSERT INTO user_level VALUES ('10', 'NPMO Project Manager', '1');
INSERT INTO user_level VALUES ('11', 'Regional CMT', '1');
INSERT INTO user_level VALUES ('12', 'Regional ITO', '1');
INSERT INTO user_level VALUES ('13', 'GRS Focal Assistant', '1');
INSERT INTO user_level VALUES ('14', 'GRS System Focal', '1');
INSERT INTO user_level VALUES ('15', 'BDMD Central Validator (Level 2)', '1');
INSERT INTO user_level VALUES ('16', 'Regional BUS Focal', '1');
INSERT INTO user_level VALUES ('17', 'Regional CVS Focal', '1');
INSERT INTO user_level VALUES ('18', 'BDMD (Level 2)', '1');
INSERT INTO user_level VALUES ('19', 'BDMD (Level 1)', '1');
INSERT INTO user_level VALUES ('20', 'NPMO Deputy Director', '1');
INSERT INTO user_level VALUES ('21', 'Cash People', '1');
INSERT INTO user_level VALUES ('22', 'Regional DPM', '1');
INSERT INTO user_level VALUES ('23', 'MRB / FA I', '1');
INSERT INTO user_level VALUES ('24', 'FA II', '1');
INSERT INTO user_level VALUES ('25', 'Regional Accountant', '1');
INSERT INTO user_level VALUES ('26', 'SWI Encode', '1');
INSERT INTO user_level VALUES ('27', 'SWI Supervisor', '1');
INSERT INTO user_level VALUES ('28', 'SWI Activator', '1');
INSERT INTO user_level VALUES ('29', 'SWI Focal', '1');
INSERT INTO user_level VALUES ('30', 'SWI Admin', '1');
INSERT INTO user_level VALUES ('31', 'IP Focal - NPMO', '1');
INSERT INTO user_level VALUES ('32', 'IP Focal - RPMO', '1');
INSERT INTO user_level VALUES ('33', 'ML/CL', '1');
INSERT INTO user_level VALUES ('34', 'FA III', '1');
INSERT INTO user_level VALUES ('35', 'Approving Officer for Finance', '1');
INSERT INTO user_level VALUES ('38', 'Super Administrator', '1');
INSERT INTO user_level VALUES ('39', 'Anonymous', '1');

-- ----------------------------
-- Table structure for `user_permission`
-- ----------------------------
DROP TABLE IF EXISTS `user_permission`;
CREATE TABLE `user_permission` (
  `user_levelpermission_id` int(11) NOT NULL AUTO_INCREMENT,
  `level_id` int(11) NOT NULL,
  `module` varchar(50) NOT NULL,
  PRIMARY KEY (`user_levelpermission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_permission
-- ----------------------------
INSERT INTO user_permission VALUES ('1', '17', 'users');
INSERT INTO user_permission VALUES ('2', '17', 'settings');
