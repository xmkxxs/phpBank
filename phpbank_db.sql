/*
 Navicat Premium Data Transfer

 Source Server         : rootDB
 Source Server Type    : MySQL
 Source Server Version : 50515
 Source Host           : localhost:3306
 Source Schema         : phpbank_db

 Target Server Type    : MySQL
 Target Server Version : 50515
 File Encoding         : 65001

 Date: 25/02/2021 19:28:00
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tb_user
-- ----------------------------
DROP TABLE IF EXISTS `tb_user`;
CREATE TABLE `tb_user`  (
  `account` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mobile` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `balance` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `question` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `answer` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_user
-- ----------------------------
INSERT INTO `tb_user` VALUES ('111111111111111', '二马的朋友', '123', '123456789101', 'admin@admin.com', '100000000000', '我叫啥', 'admin');
INSERT INTO `tb_user` VALUES ('461833215018403', '士官长', '123', '11223344556', 'shiguanz@s.com', '0', '士官长是谁', '117');
INSERT INTO `tb_user` VALUES ('548307902167851', '士官长', '123', '12312312311', 'dshia@sa.com', '100000', 'dsa', 'dsa');
INSERT INTO `tb_user` VALUES ('842561023701248', '特朗普', '123', '52152152152', 'aiguochuan@ai.com', '-1000000000', '爱不爱国', '爱');

SET FOREIGN_KEY_CHECKS = 1;
