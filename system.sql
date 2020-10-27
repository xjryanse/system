
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for w_system_app
-- ----------------------------

CREATE TABLE `w_system_app`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '应用名称',
  `appid` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '应用id',
  `secret` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '应用密钥',
  `sort` int(11) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '微服务应用表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_async_operate
-- ----------------------------

CREATE TABLE `w_system_async_operate`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `company_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `table_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '表名',
  `last_table_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '末次执行取的记录id',
  `last_run_time` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '末次执行取值时间',
  `within_seconds` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '只取最近指定秒数内的记录，防误',
  `class_method` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '执行的类库/方法名',
  `sort` int(11) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统单线程异步实施类库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_cate
-- ----------------------------

CREATE TABLE `w_system_cate`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `company_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `group_key` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '分组key',
  `cate_key` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '分类key',
  `cate_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '分类名',
  `sort` int(11) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统分类表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_code
-- ----------------------------

CREATE TABLE `w_system_code`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` int(11) NULL DEFAULT 0,
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '返回码清单' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_column
-- ----------------------------

CREATE TABLE `w_system_column`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `company_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `controller` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '后台控制器，采用前台的模块名',
  `table_key` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '表单键名',
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '说明',
  `table_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  `source_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '资源服务地址',
  `form_style` int(2) NULL DEFAULT 1 COMMENT '表单样式：1竖排；2横排',
  `order_by` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  `color_con` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '行颜色条件（根据字段状态显示不同颜色）',
  `yearmonth_field` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '年月字段',
  `source` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '资源位置：\r\n\'\'当前\r\n\'user\'用户,\r\n\'access\'权限,\r\n\'system\'系统,\r\n\'busi\'业务',
  `block_width` int(11) NULL DEFAULT 12 COMMENT '块宽度',
  `sort` int(11) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '字段表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_column_block
-- ----------------------------

CREATE TABLE `w_system_column_block`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `company_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `column_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '字段id',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题',
  `main_field` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '主表字段',
  `table_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '关联表名',
  `table_field` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '关联字段',
  `union_type` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '关联类型：1v1 一对一;1vn 一对多',
  `show_type` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '展示方式：list 列表; info 信息',
  `block_width` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '12等分宽度：1,2,3,4,5',
  `fields` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '展示字段，逗号分隔',
  `sort` int(11) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '数据块表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_column_block_table_fields
-- ----------------------------

CREATE TABLE `w_system_column_block_table_fields`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `company_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `block_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '字段id',
  `main_field` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '主表字段',
  `table_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '关联表名',
  `table_field` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '关联字段',
  `fields` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '展示字段，逗号分隔',
  `sort` int(11) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '表字段' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_column_btn
-- ----------------------------

CREATE TABLE `w_system_column_btn`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `company_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `column_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '表id',
  `block_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'block_id，具体板块的操作按钮',
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '按钮名',
  `confirm` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '操作确认',
  `o_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'jump:本页跳链，\r\nmainpop本页弹窗，\r\nblank新窗口\r\nlistpop:列表弹窗\r\nlistoperate:列表操作（带确认）',
  `style` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '按钮样式：eg:layui-btn-normal',
  `icon` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '按钮图标：eg:fa-user',
  `url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '地址:内嵌参数拿主表数据',
  `param` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '参数json',
  `show_condition` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '显示条件',
  `sort` int(11) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '字段按钮表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_column_list
-- ----------------------------

CREATE TABLE `w_system_column_list`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `company_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `column_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '字段id',
  `label` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '字段名',
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '字段键名',
  `type` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '{\r\n\"hidden\":\"隐藏域\",\r\n\"text\":\"单行文字\",\r\n\"enum\":\"枚举\",\r\n\"image\":\"上传图片\",\r\n\"date\":\"日期\",\r\n\"datetime\":\"日期+时间\",\r\n\"textarea\":\"文本框\",\r\n\"editor\":\"编辑器\",\r\n\"number\":\"数值\",\r\n\"dynenum\":\"动态枚举\",\r\n\"10\":\"搜索选择框(文本)\",\r\n\"11\":\"搜索选择框(枚举)\",\r\n\"link\":\"跳转链接\",\r\n\"13\":\"链接图片\",\r\n\"14\":\"一级复选(中间表)\",\r\n\"15check\":\"二级复选(中间表)\",\r\n\"16switch\":\"开关\",\r\n\"17\":\"时间框\",\r\n\"18\":\"关联表\",\r\n\"19\":\"密码\",\r\n\"20\":\"联表数据\",\r\n\"21\":\"月日时分\",\r\n\"22\":\"百分比\",\r\n\"23\":\"上传附件\",\r\n\"24\":\"随机串(8位)\",\r\n\"25\":\"随机串(16位)\",\r\n\"26\":\"随机串(32位)\",\r\n\"27\":\"不可编辑框\",\r\n\"28\":\"后台换行设置(行颜色条件)\",\r\n\"29\":\"逗号分隔换行显示\",\r\n\"30\":\"链接二维码\",\r\n\"31-listedit\":\"列表编辑\",\r\n\"99\":\"混合字段\"}',
  `option` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '选项json',
  `search_type` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '-1' COMMENT '搜索类型？否、equal:精确查找;like:模糊查找;in:in查找;numberscope:数据范围查找;timescope:时间查找',
  `is_list` tinyint(1) NULL DEFAULT 1 COMMENT '列表字段？0否、1是',
  `is_list_edit` tinyint(1) NULL DEFAULT 0 COMMENT '列表编辑？0否、1是',
  `is_add` tinyint(1) NULL DEFAULT 1 COMMENT '添加字段？0否、1是',
  `is_edit` tinyint(1) NULL DEFAULT 1 COMMENT '编辑字段？0否、1是',
  `is_must` tinyint(1) NULL DEFAULT 0 COMMENT '必填(新增和编辑时)？0否、1是',
  `is_export` tinyint(1) NULL DEFAULT 1 COMMENT '是否导出？0否，1是',
  `is_import_must` tinyint(1) NULL DEFAULT 0 COMMENT '是否导入必填，0否，1是，导入时，需要表单预填的基础信息',
  `is_span_company` tinyint(1) NULL DEFAULT NULL COMMENT '是否跨公司？0否，1是。用于跨公司查询数据',
  `width` int(11) NULL DEFAULT 100 COMMENT '列表中的默认宽度',
  `form_col` int(2) NULL DEFAULT NULL COMMENT '2-12',
  `sort` int(11) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '字段明细表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_column_operate
-- ----------------------------

CREATE TABLE `w_system_column_operate`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `company_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `column_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `operate_key` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '操作key，添加，编辑，删除，导出',
  `operate_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '操作名称',
  `ajax_url` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '异步地址',
  `tpl_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '模板文件id',
  `page_notice` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '页面告知',
  `ajax_notice` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '请求确认告知',
  `sort` int(11) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '字段操作表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_company
-- ----------------------------

CREATE TABLE `w_system_company`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '公司名称',
  `company_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '公司编号',
  `short_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '公司简称',
  `company_cate` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '公司类型',
  `launch_time` datetime(0) NULL DEFAULT NULL COMMENT '成立时间',
  `management_content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '经营范围',
  `province` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '省',
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '市',
  `district` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '县',
  `address` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '公司地址',
  `licence` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '营业执照编号',
  `fr_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '法人姓名',
  `fr_mobile` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '法人手机',
  `key` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '接口访问key',
  `we_app_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '小程序appid',
  `we_pub_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '公众号acid',
  `create_company_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建公司',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '公司端口表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_configs
-- ----------------------------

CREATE TABLE `w_system_configs`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `company_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '配置描述',
  `module` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '模块',
  `group` int(11) NULL DEFAULT 0 COMMENT '分组：1杂项、2分享设置、3活动规则',
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '配置类型text显示文字 uplimage上传图片（wp_picture）其他，根据column_list表',
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '配置关键字:模块名+其他',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '【配置值】',
  `sort` int(11) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '配置表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_error_log
-- ----------------------------

CREATE TABLE `w_system_error_log`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '应用id',
  `url` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '请求url',
  `param` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '请求参数',
  `code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '错误码',
  `msg` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '错误日志内容',
  `file` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '错误日志文件地址',
  `function` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '方法名',
  `line` int(5) NULL DEFAULT NULL COMMENT '错误日志文件行数',
  `trace` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `o_company_id` int(11) NULL DEFAULT NULL COMMENT '操作公司id',
  `o_user_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '操作用户id',
  `o_ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '操作ip',
  `is_noticed` int(1) NULL DEFAULT 0 COMMENT '是否已通知：0否，1是',
  `sort` int(11) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT NULL COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '错误记录表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_export_log
-- ----------------------------

CREATE TABLE `w_system_export_log`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `company_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `info` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '导出信息说明',
  `module` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `file_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '导出文件名称',
  `file_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '文件路径',
  `filed_json` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '文件表头',
  `search_sql` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '查询sql语句',
  `exp_status` int(2) NULL DEFAULT 0 COMMENT '0待生成；1生成中；2已生成；3已下载',
  `finish_time` datetime(0) NULL DEFAULT NULL,
  `sort` int(11) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '数据导出记录' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_file
-- ----------------------------

CREATE TABLE `w_system_file`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `appid` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '应用id',
  `company_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '公司id',
  `file_type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'image图片；file文件',
  `sub_type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'idcard身份证',
  `is_encrypt` int(10) NULL DEFAULT 0 COMMENT '是否加密 0无加密，1加密',
  `file_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '图片链接',
  `md5` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `sort` int(11) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '微服务附件表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_log
-- ----------------------------

CREATE TABLE `w_system_log`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '应用id',
  `company_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '公司id',
  `ip` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '访问ip',
  `url` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `header` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '请求头部',
  `param` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '请求参数',
  `module` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '访问模块',
  `controller` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '访问控制器',
  `action` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '访问方法',
  `sort` int(11) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '访问日志' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_scan_log
-- ----------------------------

CREATE TABLE `w_system_scan_log`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `company_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `rec_user_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '推荐用户',
  `user_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '浏览用户',
  `scan_item` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '浏览项目：商标、网店',
  `scan_item_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '浏览项目id',
  `sort` int(11) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `app_id`(`app_id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `rec_user_id`(`rec_user_id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE,
  INDEX `scan_item`(`scan_item`) USING BTREE,
  INDEX `scan_item_id`(`scan_item_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '浏览记录表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_timing
-- ----------------------------

CREATE TABLE `w_system_timing`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `company_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `module` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '模块',
  `controller` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '控制器',
  `action` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '方法',
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '定时任务名称',
  `describe` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '定时任务说明',
  `spacing` int(11) NULL DEFAULT NULL COMMENT '最少间隔秒数(s)',
  `last_run_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '定时器末次调用时间',
  `last_execute_timestamp` int(11) NULL DEFAULT 0 COMMENT '程序末次执行时间（相应脚本中写入）',
  `sort` int(11) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '异步定时器类库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for w_system_timing_log
-- ----------------------------

CREATE TABLE `w_system_timing_log`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `company_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `timing_id` int(11) NULL DEFAULT 0 COMMENT 'id',
  `url` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '地址',
  `ip` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '请求ip',
  `sort` int(11) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '异步定时任务日志' ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
