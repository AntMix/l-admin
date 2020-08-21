/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : w_admin

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 21/08/2020 09:31:34
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_power
-- ----------------------------
DROP TABLE IF EXISTS `admin_power`;
CREATE TABLE `admin_power`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `pid` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `is_menu` tinyint(1) NOT NULL DEFAULT 0,
  `path` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `sign` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '页面标识 user:delete',
  `sort` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '升序',
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_power
-- ----------------------------
INSERT INTO `admin_power` VALUES (1, '首页', 0, 1, 'index', 'layui-icon-console', '', 1, 1, 0, 1594720562);
INSERT INTO `admin_power` VALUES (2, '权限管理', 0, 1, '', 'layui-icon-auz', '', 1, 1, 0, 1594720805);
INSERT INTO `admin_power` VALUES (3, '权限列表', 2, 1, 'power/index', 'layui-icon-tabs', '', 1, 1, 0, 1597284228);
INSERT INTO `admin_power` VALUES (4, '用户列表', 2, 1, 'admin_user/index', 'layui-icon-username', '', 1, 1, 0, 1594864898);
INSERT INTO `admin_power` VALUES (5, '角色列表', 2, 1, 'role/index', 'layui-icon-user', '', 1, 1, 0, 1594864882);
INSERT INTO `admin_power` VALUES (6, '编辑', 3, 0, '', '', 'power:edit', 1, 1, 0, 0);
INSERT INTO `admin_power` VALUES (7, '示例页面', 0, 1, 'sample', '', '', 1, 1, 0, 1595216277);
INSERT INTO `admin_power` VALUES (8, '添加', 3, 0, '', '', 'power:add', 1, 1, 0, 0);
INSERT INTO `admin_power` VALUES (9, '删除', 3, 0, '', '', 'power:del', 1, 1, 0, 0);
INSERT INTO `admin_power` VALUES (10, '新增子集', 3, 0, '', '', 'power:addChild', 1, 1, 0, 0);
INSERT INTO `admin_power` VALUES (11, '新增', 4, 0, '', '', 'adminUser:add', 1, 1, 0, 0);
INSERT INTO `admin_power` VALUES (12, '更改密码', 5, 0, '', '', 'adminUser:changePassword', 1, 1, 1594886306, 0);

-- ----------------------------
-- Table structure for admin_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_role`;
CREATE TABLE `admin_role`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_role
-- ----------------------------
INSERT INTO `admin_role` VALUES (1, '超管', 1, 0, 1594886398);
INSERT INTO `admin_role` VALUES (3, '客服', 1, 0, 0);

-- ----------------------------
-- Table structure for admin_role_power
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_power`;
CREATE TABLE `admin_role_power`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `power_id` int(11) UNSIGNED NOT NULL,
  `role_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 149 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_role_power
-- ----------------------------
INSERT INTO `admin_role_power` VALUES (9, 1, 3);
INSERT INTO `admin_role_power` VALUES (137, 1, 1);
INSERT INTO `admin_role_power` VALUES (138, 2, 1);
INSERT INTO `admin_role_power` VALUES (139, 3, 1);
INSERT INTO `admin_role_power` VALUES (140, 6, 1);
INSERT INTO `admin_role_power` VALUES (141, 8, 1);
INSERT INTO `admin_role_power` VALUES (142, 9, 1);
INSERT INTO `admin_role_power` VALUES (143, 10, 1);
INSERT INTO `admin_role_power` VALUES (144, 4, 1);
INSERT INTO `admin_role_power` VALUES (145, 11, 1);
INSERT INTO `admin_role_power` VALUES (146, 5, 1);
INSERT INTO `admin_role_power` VALUES (147, 12, 1);
INSERT INTO `admin_role_power` VALUES (148, 7, 1);

-- ----------------------------
-- Table structure for admin_role_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_user`;
CREATE TABLE `admin_role_user`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_role_user
-- ----------------------------
INSERT INTO `admin_role_user` VALUES (8, 3, 2, 1);
INSERT INTO `admin_role_user` VALUES (9, 1, 1, 1);
INSERT INTO `admin_role_user` VALUES (10, 3, 1, 1);

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user`  (
  `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `nick` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `delete_time` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`, `name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES (1, 'admin', '超级管理员', 'a7fb4e8862ba9b3319b6c447daa05726', '1234', '5678', 1, 0, 1594606738, 1597289815);
INSERT INTO `admin_user` VALUES (2, 'service', '客服', 'a7fb4e8862ba9b3319b6c447daa05726', '', '', 0, 0, 1594714346, 1594717486);

SET FOREIGN_KEY_CHECKS = 1;
