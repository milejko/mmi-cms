CREATE TABLE "cms_auth" (
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "lang" varchar(2) DEFAULT NULL,
  "name" varchar(128) DEFAULT NULL,
  "username" varchar(128) NOT NULL,
  "email" varchar(128) NOT NULL,
  "password" varchar(128) DEFAULT NULL,
  "roles" text DEFAULT '',
  "lastIp" varchar(16) DEFAULT NULL,
  "lastLog" datetime DEFAULT NULL,
  "lastFailIp" varchar(16) DEFAULT NULL,
  "lastFailLog" datetime DEFAULT NULL,
  "failLogCount" INTEGER DEFAULT 0,
  "logged" tinyint(4) DEFAULT 0,
  "active" tinyint(4) NOT NULL DEFAULT 0
);

INSERT INTO "cms_auth" VALUES (1,'pl',NULL,'admin','admin@example.com','d033e22ae348aeb5660fc2140aec35850c4da997','admin','127.0.0.1','2012-02-23 15:41:12','89.231.108.27','2011-12-20 19:42:01',8,0,1);

CREATE TABLE "cms_category" (
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "cms_auth_id" INTEGER DEFAULT NULL,
  "template" varchar(255) DEFAULT '',
  "cms_category_original_id" INTEGER DEFAULT NULL,
  "status" tinyint(4) NOT NULL DEFAULT 0,
  "lang" varchar(2) DEFAULT NULL,
  "name" varchar(128) DEFAULT NULL,
  "title" varchar(128) DEFAULT NULL,
  "description" text DEFAULT NULL,
  "fullContent" text DEFAULT null,
  "uri" varchar(255) NOT NULL,
  "path" varchar(128) DEFAULT NULL,
  "customUri" varchar(255) DEFAULT NULL,
  "redirectUri" varchar(255) DEFAULT NULL,
  "blank" tinyint(4) NOT NULL DEFAULT 0,
  "configJson" text DEFAULT NULL,
  "parent_id" INTEGER DEFAULT NULL,
  "order" INTEGER DEFAULT 0,
  "dateAdd" datetime NOT NULL,
  "dateModify" datetime DEFAULT NULL,
  "active" tinyint(4) NOT NULL DEFAULT 1,
  "visible" tinyint(4) NOT NULL DEFAULT 1,
  FOREIGN KEY(cms_auth_id) REFERENCES cms_auth(id),
  FOREIGN KEY(cms_category_original_id) REFERENCES cms_category(id),
  FOREIGN KEY(parent_id) REFERENCES cms_category(id)
);
CREATE INDEX cms_category_name ON cms_category(name);
CREATE INDEX cms_category_lang ON cms_category(lang);
CREATE INDEX cms_category_dateAdd ON cms_category(dateAdd);
CREATE INDEX cms_category_dateModify ON cms_category(dateModify);
CREATE INDEX cms_category_template ON cms_category(template);
CREATE INDEX cms_category_uri ON cms_category(uri);
CREATE INDEX cms_category_customUri ON cms_category(customUri);
CREATE INDEX cms_category_path ON cms_category(path);
CREATE INDEX cms_category_status ON cms_category(status);
CREATE INDEX cms_category_active ON cms_category(active);

CREATE TABLE "cms_category_acl" (
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "role" varchar(255) DEFAULT 'guest',
  "cms_category_id" INTEGER,
  "access" varchar(8) DEFAULT 'deny',
  FOREIGN KEY(cms_category_id) REFERENCES cms_category(id)
);

CREATE TABLE "cms_category_widget_category" (
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "uuid" varchar(40) NOT NULL,
  "widget" varchar(255) DEFAULT '',
  "cms_category_id" INTEGER,
  "configJson" longtext DEFAULT NULL,
  "order" INTEGER,
  "active" tinyint(4) NOT NULL DEFAULT 0,
  FOREIGN KEY(cms_category_id) REFERENCES cms_category(id)
);
CREATE INDEX cms_category_widget_category_order ON cms_category_widget_category("order");
CREATE INDEX cms_category_widget_category_active ON cms_category_widget_category(active);
CREATE INDEX cms_category_widget_category_widget ON cms_category_widget_category(widget);
CREATE INDEX cms_category_widget_category_uuid ON cms_category_widget_category(uuid);

CREATE TABLE "cms_cron" (
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "active" tinyint(4) NOT NULL DEFAULT 0,
  "lock" INTEGER DEFAULT 0,
  "minute" varchar(50) NOT NULL,
  "hour" varchar(50) NOT NULL,
  "dayOfMonth" varchar(50) NOT NULL,
  "month" varchar(50) NOT NULL,
  "dayOfWeek" varchar(50) NOT NULL,
  "name" varchar(50) NOT NULL,
  "description" text DEFAULT NULL,
  "module" varchar(32) NOT NULL,
  "controller" varchar(32) NOT NULL,
  "action" varchar(32) NOT NULL,
  "message" text DEFAULT NULL,
  "dateAdd" datetime DEFAULT NULL,
  "dateModified" datetime DEFAULT NULL,
  "dateLastExecute" datetime DEFAULT NULL
);

INSERT INTO "cms_cron" VALUES (1,1,0,'*','*','*','*','*','Wysyłka maili','Wysyła maile z kolejki','cms','cron','sendMail',NULL,'2012-03-14 10:35:57','2014-03-21 21:31:02','2014-03-21 21:31:02');
INSERT INTO "cms_cron" VALUES (2,1,0,'*','*','*','*','*','Agregator statystyk','Zlicza statystyki z serwisu','cms','cron','agregate',NULL,'2014-03-20 09:48:29','2014-03-21 21:31:02','2014-03-21 21:31:02');
INSERT INTO "cms_cron" VALUES (3,1,0,'30','4','1','*/2','*','Czyszczenie logów','Czyści archiwalne logi aplikacji','cms','cron','clean',NULL,'2014-03-20 09:49:37','2014-03-20 09:49:37',NULL);

CREATE TABLE "cms_file" (
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "class" varchar(32) DEFAULT NULL,
  "mimeType" varchar(128) DEFAULT NULL,
  "name" varchar(45) DEFAULT NULL,
  "original" varchar(255) DEFAULT NULL,
  "data" longtext DEFAULT NULL,
  "size" bigint(20) DEFAULT NULL,
  "dateAdd" datetime DEFAULT NULL,
  "dateModify" datetime DEFAULT NULL,
  "order" INTEGER DEFAULT NULL,
  "sticky" tinyint(4) DEFAULT NULL,
  "object" varchar(64) DEFAULT NULL,
  "objectId" INTEGER DEFAULT NULL,
  "cms_auth_id" INTEGER DEFAULT NULL,
  "active" tinyint(4) DEFAULT NULL
);

CREATE TABLE "cms_mail" (
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "cms_mail_definition_id" INTEGER,
  "fromName" varchar(64) DEFAULT NULL,
  "to" varchar(255) DEFAULT NULL,
  "replyTo" varchar(64) DEFAULT NULL,
  "subject" varchar(200) DEFAULT NULL,
  "message" text DEFAULT NULL,
  "attachments" text DEFAULT NULL,
  "type" tinyint(4) NOT NULL DEFAULT 1,
  "dateAdd" datetime DEFAULT NULL,
  "dateSent" datetime DEFAULT NULL,
  "dateSendAfter" datetime DEFAULT NULL,
  "active" tinyint(4) NOT NULL DEFAULT 0,
  FOREIGN KEY(cms_mail_definition_id) REFERENCES cms_mail_definition(id)
);
CREATE INDEX cms_mail_active ON cms_mail(active);
CREATE INDEX cms_mail_type ON cms_mail(type);

CREATE TABLE "cms_mail_definition" (
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "lang" varchar(2) DEFAULT NULL,
  "cms_mail_server_id" INTEGER,
  "name" varchar(32) DEFAULT NULL,
  "replyTo" varchar(64) DEFAULT NULL,
  "fromName" varchar(64) DEFAULT NULL,
  "subject" varchar(200) DEFAULT NULL,
  "message" text DEFAULT NULL,
  "html" tinyint(4) NOT NULL DEFAULT 0,
  "dateAdd" datetime DEFAULT NULL,
  "dateModify" datetime DEFAULT NULL,
  "active" tinyint(4) NOT NULL DEFAULT 0,
  FOREIGN KEY(cms_mail_server_id) REFERENCES cms_mail_server(id)
);
CREATE INDEX cms_mail_definition_active ON cms_mail_definition(active);
CREATE INDEX cms_mail_definition_lang ON cms_mail_definition(lang);

CREATE TABLE "cms_mail_server" (
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "address" varchar(64) NOT NULL,
  "port" smallint(6) NOT NULL DEFAULT 25,
  "username" varchar(64) DEFAULT NULL,
  "password" varchar(64) DEFAULT NULL,
  "from" varchar(200) DEFAULT NULL,
  "dateAdd" datetime DEFAULT NULL,
  "dateModify" datetime DEFAULT NULL,
  "active" tinyint(4) NOT NULL DEFAULT 1,
  "ssl" varchar(16) DEFAULT 'tls'
);

CREATE TABLE "cms_role" (
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" varchar(32) NOT NULL
);

INSERT INTO "cms_role" VALUES (3,'admin');
INSERT INTO "cms_role" VALUES (1,'guest');
INSERT INTO "cms_role" VALUES (2,'member');

CREATE TABLE "cms_tag" (
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "scope" varchar(255) DEFAULT NULL,
  "lang" varchar(2) DEFAULT NULL,
  "tag" varchar(255) NOT NULL
);

CREATE TABLE "cms_tag_relation" (
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "cms_tag_id" INTEGER,
  "object" varchar(64) NOT NULL,
  "objectId" INTEGER,
  FOREIGN KEY(cms_tag_id) REFERENCES cms_tag(id)
);
CREATE INDEX cms_tag_relation_object ON cms_tag_relation(object);
CREATE INDEX cms_tag_relation_objectId ON cms_tag_relation(objectId);

CREATE TABLE "mmi_cache" (
  "id" varchar(64) NOT NULL PRIMARY KEY,
  "data" mediumtext DEFAULT NULL,
  "ttl" INTEGER DEFAULT NULL
);

CREATE TABLE "mmi_changelog" (
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "filename" varchar(64) NOT NULL,
  "md5" varchar(32) NOT NULL,
  "active" tinyint(4) NOT NULL DEFAULT 0
);
CREATE UNIQUE INDEX mmi_changelog_filename ON mmi_changelog(filename);

CREATE TABLE "mmi_session" (
  "id" varchar(64) NOT NULL PRIMARY KEY,
  "data" mediumtext DEFAULT NULL,
  "timestamp" INTEGER
);
CREATE INDEX mmi_session_timestamp ON mmi_session(timestamp);
