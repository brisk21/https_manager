
PRAGMA foreign_keys = false;

-- ----------------------------
-- Table structure for bs_https_conf
-- ----------------------------
DROP TABLE IF EXISTS "bs_https_conf";
CREATE TABLE "bs_https_conf" (
  "key" TEXT(128),
  "value" TEXT
);

-- ----------------------------
-- Table structure for bs_https_domain
-- ----------------------------
DROP TABLE IF EXISTS "bs_https_domain";
CREATE TABLE "bs_https_domain" (
    "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
    "domain" TEXT(255) DEFAULT '',
    "status" integer(1) DEFAULT 0,
    "remark" TEXT(255) DEFAULT '',
    "domain_dns" TEXT DEFAULT '',
    "start_time" integer(11) DEFAULT 0,
    "end_time" integer(11) DEFAULT 0,
    "last_check_time" integer(11) DEFAULT 0,
    "add_time" integer(11) DEFAULT 0,
    "up_time" integer(11) DEFAULT 0
);

-- ----------------------------
-- Table structure for bs_https_log_email
-- ----------------------------
DROP TABLE IF EXISTS "bs_https_log_email";
CREATE TABLE "bs_https_log_email" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "email" TEXT(128) DEFAULT '',
  "status" integer(1) DEFAULT 0,
  "content" TEXT DEFAULT '',
  "try_count" integer DEFAULT 0,
  "add_time" integer(11) DEFAULT 0,
  "up_time" integer(11) DEFAULT 0
);


PRAGMA foreign_keys = true;
