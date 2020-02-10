/*
Navicat MySQL Data Transfer

Source Server         : 本地连接
Source Server Version : 80012
Source Host           : localhost:3306
Source Database       : think_rbac

Target Server Type    : MYSQL
Target Server Version : 80012
File Encoding         : 65001

Date: 2020-02-09 00:42:59
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for think_admin
-- ----------------------------
DROP TABLE IF EXISTS `think_admin`;
CREATE TABLE `think_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `username` varchar(50) NOT NULL COMMENT '管理员用户名',
  `password` varchar(128) NOT NULL COMMENT '管理员密码',
  `role_id` int(4) unsigned NOT NULL COMMENT '角色id',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用 0禁用',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `last_login_time` int(10) unsigned DEFAULT NULL COMMENT '最后登录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of think_admin
-- ----------------------------
INSERT INTO `think_admin` VALUES ('1', 'admin', '90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad', '1', '1', '1579881069', '1581135178', '1581180089');
INSERT INTO `think_admin` VALUES ('2', 'zhangsan', '10470c3b4b1fed12c3baac014be15fac67c6e815', '2', '1', '1580614960', '1580738851', '1581179655');
INSERT INTO `think_admin` VALUES ('3', 'lisi', '10470c3b4b1fed12c3baac014be15fac67c6e815', '3', '0', '1580625206', '1580740152', '1581139548');
INSERT INTO `think_admin` VALUES ('4', 'wangwu', '10470c3b4b1fed12c3baac014be15fac67c6e815', '3', '1', '1580740168', '1580740168', null);

-- ----------------------------
-- Table structure for think_login_log
-- ----------------------------
DROP TABLE IF EXISTS `think_login_log`;
CREATE TABLE `think_login_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志id',
  `login_username` varchar(30) NOT NULL COMMENT '登录管理员用户名',
  `login_status` tinyint(1) unsigned NOT NULL COMMENT '登录状态：1 登录成功 0 登录失败',
  `login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `login_ip` varchar(20) NOT NULL COMMENT '登录ip',
  `login_area` varchar(255) NOT NULL,
  `login_client_os` varchar(255) DEFAULT NULL COMMENT '登录客户端操作系统',
  `login_client_browser` varchar(255) DEFAULT NULL COMMENT '登录客户端浏览器',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='管理员登录日志表';

-- ----------------------------
-- Records of think_login_log
-- ----------------------------
INSERT INTO `think_login_log` VALUES ('1', 'admin', '1', '1581179622', '127.0.0.1', '本机地址', 'Windows10.0', 'Chrome79.0.3945.88');
INSERT INTO `think_login_log` VALUES ('2', 'zhangsan', '1', '1581179655', '127.0.0.1', '本机地址', 'Windows10.0', 'Chrome79.0.3945.88');
INSERT INTO `think_login_log` VALUES ('3', 'admin', '1', '1581180089', '127.0.0.1', '本机地址', 'Windows10.0', 'Chrome79.0.3945.88');

-- ----------------------------
-- Table structure for think_node
-- ----------------------------
DROP TABLE IF EXISTS `think_node`;
CREATE TABLE `think_node` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '节点id',
  `name` varchar(50) NOT NULL COMMENT '节点名称',
  `path` varchar(50) NOT NULL COMMENT '节点路径',
  `pid` int(11) unsigned NOT NULL COMMENT '所属节点id',
  `sort` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `icon` varchar(50) DEFAULT NULL COMMENT '图标',
  `is_menu` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否是菜单项 1 不是 2 是',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态：1 启用 0 禁用',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='权限节点表';

-- ----------------------------
-- Records of think_node
-- ----------------------------
INSERT INTO `think_node` VALUES ('1', '后台主页', '#', '0', '0', 'iconfont zspicon-shouye_shouye', '2', '0', '1580472462', '1580614379');
INSERT INTO `think_node` VALUES ('2', '后台首页', 'index/home', '1', '0', 'iconfont zspicon-shouye_shouye', '1', '1', '1580472462', '1581130960');
INSERT INTO `think_node` VALUES ('3', '修改密码', 'index/editPassword', '1', '0', 'iconfont zspicon-xiugaimima', '1', '1', '1580472462', '1581130888');
INSERT INTO `think_node` VALUES ('4', '权限管理', '#', '0', '0', 'iconfont zspicon-quanxian-copy-copy', '2', '1', '1580472462', '1580550491');
INSERT INTO `think_node` VALUES ('5', '角色管理', 'role/index', '4', '0', 'iconfont zspicon-jiaoseguanli1', '2', '1', '1580486519', '1580550514');
INSERT INTO `think_node` VALUES ('6', '添加角色', 'role/add', '5', '0', '', '1', '1', '1580486586', '1580486586');
INSERT INTO `think_node` VALUES ('7', '编辑角色', 'role/edit', '5', '0', '', '1', '1', '1580486613', '1580784774');
INSERT INTO `think_node` VALUES ('8', '删除角色', 'role/del', '5', '0', '', '1', '1', '1580486637', '1580784782');
INSERT INTO `think_node` VALUES ('9', '角色授权', 'role/auth', '5', '0', '', '1', '1', '1580486773', '1580486773');
INSERT INTO `think_node` VALUES ('10', '节点管理', 'node/index', '4', '0', 'iconfont zspicon-quanxian', '2', '1', '1580487561', '1580552841');
INSERT INTO `think_node` VALUES ('11', '添加节点', 'node/add', '10', '0', '', '1', '1', '1580487581', '1580487581');
INSERT INTO `think_node` VALUES ('12', '编辑节点', 'node/edit', '10', '0', '', '1', '1', '1580487606', '1580487606');
INSERT INTO `think_node` VALUES ('13', '删除节点', 'node/del', '10', '0', '', '1', '1', '1580487622', '1580487622');
INSERT INTO `think_node` VALUES ('14', '管理员管理', 'admin/index', '4', '0', 'iconfont zspicon-guanliyuan', '2', '1', '1580524112', '1580552858');
INSERT INTO `think_node` VALUES ('15', '添加管理员', 'admin/add', '14', '0', '', '1', '1', '1580524130', '1580524130');
INSERT INTO `think_node` VALUES ('16', '编辑管理员', 'admin/edit', '14', '0', '', '1', '1', '1580524144', '1580524144');
INSERT INTO `think_node` VALUES ('17', '删除管理员', 'admin/del', '14', '0', '', '1', '1', '1580524188', '1580524188');
INSERT INTO `think_node` VALUES ('18', '日志管理', '#', '0', '0', 'iconfont zspicon-wenzhang2', '2', '1', '1580536346', '1580553039');
INSERT INTO `think_node` VALUES ('19', '登录日志', 'loginlog/index', '18', '0', 'iconfont zspicon-rizhi', '2', '1', '1580536578', '1580826890');
INSERT INTO `think_node` VALUES ('20', '文章管理', '#', '0', '0', 'iconfont zspicon-wenzhang2', '2', '1', '1581165688', '1581166029');
INSERT INTO `think_node` VALUES ('21', '文章分类管理', 'test/index', '20', '0', 'iconfont zspicon-lanmu', '2', '1', '1581165832', '1581166893');
INSERT INTO `think_node` VALUES ('22', '文章列表', 'test/index', '20', '0', 'iconfont zspicon-wenzhang2', '2', '1', '1581165871', '1581166901');

-- ----------------------------
-- Table structure for think_role
-- ----------------------------
DROP TABLE IF EXISTS `think_role`;
CREATE TABLE `think_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `name` varchar(30) NOT NULL COMMENT '角色名称',
  `rules` varchar(255) NOT NULL COMMENT '角色拥有的权限节点',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态：1 启用 0 禁用',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of think_role
-- ----------------------------
INSERT INTO `think_role` VALUES ('1', '超级管理员', '#', '1', '1579881069', '1578035821');
INSERT INTO `think_role` VALUES ('2', '管理员', '1,2,3,4,14,15,16,17,18,19', '1', '1578034663', '1578035801');
INSERT INTO `think_role` VALUES ('3', '会员', '1,2,18,19', '1', '1578037602', '1578037602');
