/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : mydb

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2023-05-05 16:47:41
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '用户名',
  `password` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '密码',
  `create_time` datetime NOT NULL COMMENT '创建日期',
  `create_id` int(6) DEFAULT 893 COMMENT '创建人',
  `modify_time` datetime NOT NULL COMMENT '修改时间',
  `modify_id` int(6) DEFAULT 893 COMMENT '修改人',
  `is_active` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT 'Y' COMMENT '是否可用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=924 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('893', 'root', 'mb123', '2023-04-22 13:55:15', '893', '2023-04-22 13:55:23', '893', 'Y');
INSERT INTO `user` VALUES ('923', 'admin', '123456', '2023-04-22 17:11:33', '893', '2023-04-22 17:11:33', '893', 'Y');
