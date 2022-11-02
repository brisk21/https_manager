SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for bs_https_conf
-- ----------------------------
DROP TABLE IF EXISTS `bs_https_conf`;
CREATE TABLE `bs_https_conf`  (
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置唯一键名',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '配置内容',
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统配置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bs_https_domain
-- ----------------------------
DROP TABLE IF EXISTS `bs_https_domain`;
CREATE TABLE `bs_https_domain`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '域名url',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '0=禁用，1-启用',
  `domain_dns` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '支持的域名',
  `remark` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '备注信息',
  `start_time` int(11) NULL DEFAULT 0 COMMENT '开始时间',
  `end_time` int(11) NULL DEFAULT 0 COMMENT '到期时间',
  `last_check_time` int(11) NULL DEFAULT 0 COMMENT '上次检测时间',
  `add_time` int(11) NULL DEFAULT 0 COMMENT '添加时间',
  `up_time` int(11) NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10000 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bs_https_log_email
-- ----------------------------
DROP TABLE IF EXISTS `bs_https_log_email`;
CREATE TABLE `bs_https_log_email`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '邮箱',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '-1=发送失败，0=待发送，1-发送成功',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '发送内容',
  `try_count` int(4) NULL DEFAULT 0 COMMENT '尝试发送次数',
  `add_time` int(11) NULL DEFAULT 0 COMMENT '添加时间',
  `up_time` int(11) NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
