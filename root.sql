CREATE DATABASE LBLOG DEFAULT CHARACTER SET UTF8;


USE LBLOG;


CREATE TABLE t_article (
  id        INT PRIMARY KEY AUTO_INCREMENT COMMENT '主键, 自增',
  title     VARCHAR(100) NOT NULL COMMENT '标题',
  summary VARCHAR(5000) COMMENT '摘要',
  content   LONGTEXT     NOT NULL COMMENT '正文',
  tags      VARCHAR(255) COMMENT '标签, 多标签之间用 | 分隔',
  editTime DATETIME COMMENT '创建或修改的时间',
  readNum  INT COMMENT '阅读数'
) ENGINE = InnoDB DEFAULT CHARSET = utf8;


CREATE TABLE t_tag (
  id   INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8;


CREATE TABLE t_admin (
  id       VARCHAR(32) PRIMARY KEY,
  name     VARCHAR(32) NOT NULL,
  password VARCHAR(32) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8;


CREATE TABLE t_letter (
  id int PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255),
  email VARCHAR(255),
  message VARCHAR(5000),
  `time` DATETIME,
  isRead tinyint COMMENT '0: unread, 1: already'
) ENGINE = InnoDB DEFAULT CHARSET = utf8;


CREATE TABLE `t_comment` (
  `id` int primary key auto_increment COMMENT '主键',
  `ip` varchar(15) not null comment '评论人ip',
  `artId` int not null COMMENT '所属文章id',
  `content` varchar(2000) not null COMMENT '评论内容',
  `floor` int(11) not NULL COMMENT '所在楼层',
  `name` varchar(8) default '匿名' not null comment '评论人名字',
  `time` datetime not null COMMENT '评论时间',
  FOREIGN KEY(`artId`) REFERENCES `t_article`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论表';

insert into t_comment values(null, '111.222.333.444', 60, '评论1', 1, '匿名', now());
insert into t_comment values(null, '111.222.333.444', 60, '评论2', 2, '匿名', now());
insert into t_comment values(null, '111.222.333.444', 60, '评论3', 3, '匿名', now());
insert into t_comment values(null, '111.222.333.444', 60, '评论4', 4, '匿名', now());
insert into t_comment values(null, '111.222.333.444', 60, '评论5', 5, '匿名', now());
insert into t_comment values(null, '111.222.333.444', 60, '评论6', 6, '匿名', now());
insert into t_comment values(null, '111.222.333.444', 60, '评论7', 7, '匿名', now());
insert into t_comment values(null, '111.222.333.444', 60, '评论8', 8, '匿名', now());
insert into t_comment values(null, '111.222.333.444', 60, '评论9', 9, '匿名', now());
insert into t_comment values(null, '111.222.333.444', 60, '评论10', 10, '匿名', now());
insert into t_comment values(null, '111.222.333.444', 60, '评论11', 11, '匿名', now());
insert into t_comment values(null, '111.222.333.444', 60, '评论12', 12, '匿名', now());
insert into t_comment values(null, '111.222.333.444', 60, '评论13', 13, '匿名', now());
insert into t_comment values(null, '111.222.333.444', 60, '评论14', 14, '匿名', now());
insert into t_comment values(null, '111.222.333.444', 60, '评论15', 15, '匿名', now());

CREATE TABLE t_read_log (
  id int PRIMARY KEY AUTO_INCREMENT,
  artId INT COMMENT '所属文章id',
  ip VARCHAR(15) NOT NULL COMMENT '访问者ip',
  `time` DATETIME COMMENT '访问时间',
  FOREIGN KEY (`artId`) REFERENCES t_article(`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO t_read_log VALUES (null, 3, '1.0.1.0', now());
INSERT INTO t_read_log VALUES (null, 4, '0.0.45.0', now());
INSERT INTO t_read_log VALUES (null, 5, '0.0.0.0', now());
INSERT INTO t_read_log VALUES (null, 29, '0.25.0.0', now());
INSERT INTO t_read_log VALUES (null, 29, '0.0.18.0', now());
INSERT INTO t_read_log VALUES (null, 30, '0.0.0.0', now());
INSERT INTO t_read_log VALUES (null, 31, '0.0.0.25', now());
INSERT INTO t_read_log VALUES (null, 31, '0.12.0.0', now());

CREATE TABLE t_sign_in_log (
  id int PRIMARY KEY AUTO_INCREMENT,
  ip VARCHAR(15) NOT NULL,
  `time` DATETIME
) ENGINE = InnoDB DEFAULT CHARSET = utf8;


# sumuz158z = c853a0aebdf80dd98067adf1c84b0a7c

INSERT into t_admin VALUES ('1', 'sumuzhe', 'c853a0aebdf80dd98067adf1c84b0a7c');

insert into t_tag VALUES (1, 'PHP');
insert into t_tag VALUES (2, 'LARAVEL');
insert into t_tag VALUES (3, 'BLOG');
insert into t_tag VALUES (4, 'ARRAY');
insert into t_tag VALUES (5, 'DATA-STRUCTURE');

insert into t_article VALUES (1, '深入探讨依赖注入', '测试摘要','使用Interface目前的写法，执行上没有什么问题，若以TDD开发，我们将得到第一个绿灯。我们将继续重构成更好的程式。目前我们是实际去 new Blackcat() 、new Hsinchu() 与 new PostOffice()，也就是说 ShippingService 将直接相依于 BlackCat、Hshinchu 与PostOffice 3个class。物件导向就是希望达到高内聚，低耦合的设计。所谓的低耦合，就是希望能减少相依于外部的class的数量。', '1|2|3', now(), 0);
insert into t_article VALUES (2, '一般缓存更新策略',  '测试摘要','长相思，在长安。络纬秋啼金井阑，微霜凄凄簟色寒。孤灯不明思欲绝，卷帷望月空长叹。美人如花隔云端！上有青冥之长天，下有渌水之波澜。天长路远魂飞苦，梦魂不到关山难。长相思，摧心肝！', '2|3', now(), 0);
insert into t_article VALUES (3, 'CodePush 私有化部署', '测试摘要','参考文档如下： CodePush 官方文档 code-push-server https://github.com/lisong/code-push-server https://github.com/lisong/code-push-web 安装 NodeJs 和 Npm 下载安装 NodeJs # 安装 nodejs wget https://nodejs.org/dist/v6.9.4/node-v6.9.4.tar.gz ./configure make make install # 安装 npm wget http://npmjs.org/install.sh sh install.sh # 设置淘宝镜像 vi ~/.npmrc registry = ', '1|5', now(), 0);
insert into t_article VALUES (4, 'webstrom 调试 Vue.js 单页面程序',  '测试摘要','云想衣裳花想容，春风拂槛露华浓。若非群玉山头见，会向瑶台月下逢。', '1|3', now(), 0);
insert into t_article VALUES (5, '测试摘要',  '测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要测试摘要','云想衣裳花想容，春风拂槛露华浓。若非群玉山头见，会向瑶台月下逢。', '1|3', now(), 0);


insert into t_letter VALUES (1, 'n1', '1111@qq.com', 'message......', now(), 0);
insert into t_letter VALUES (2, 'n1', '2222@qq.com', 'message......', now(), 0);
insert into t_letter VALUES (3, 'n1', '3333@qq.com', 'message......', now(), 0);
insert into t_letter VALUES (4, 'n1', '4444@qq.com', 'message......', now(), 0);
insert into t_letter VALUES (5, 'n1', '5555@qq.com', 'message......', now(), 0);
insert into t_letter VALUES (6, 'n1', '6666@qq.com', 'message......', now(), 0);
insert into t_letter VALUES (7, 'n1', '7777@qq.com', 'message......', now(), 0);





