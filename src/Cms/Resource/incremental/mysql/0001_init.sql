CREATE TABLE `DB_CHANGELOG` (
  `filename` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `md5` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_role` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_acl` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `cms_role_id` integer NOT NULL,
  `module` varchar(32) COLLATE utf8_polish_ci DEFAULT NULL,
  `controller` varchar(32) COLLATE utf8_polish_ci DEFAULT NULL,
  `action` varchar(32) COLLATE utf8_polish_ci DEFAULT NULL,
  `access` varchar(8) COLLATE utf8_polish_ci DEFAULT 'deny',
  PRIMARY KEY (`id`),
  KEY `access` (`access`),
  KEY `action` (`action`),
  KEY `controller` (`controller`),
  KEY `module` (`module`),
  CONSTRAINT `cms_acl_ibfk_1` FOREIGN KEY (`cms_role_id`) REFERENCES `cms_role` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_article` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `lang` varchar(2) COLLATE utf8_polish_ci DEFAULT NULL,
  `title` varchar(160) COLLATE utf8_polish_ci NOT NULL,
  `uri` varchar(160) COLLATE utf8_polish_ci NOT NULL,
  `dateAdd` datetime DEFAULT NULL,
  `dateModify` datetime DEFAULT NULL,
  `text` text COLLATE utf8_polish_ci,
  `noindex` TINYINT DEFAULT 0 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dateAdd` (`dateAdd`),
  KEY `dateModify` (`dateModify`),
  KEY `lang` (`lang`),
  KEY `title` (`title`),
  KEY `uri` (`uri`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_auth` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `lang` varchar(2) COLLATE utf8_polish_ci DEFAULT NULL,
  `name` varchar(128) COLLATE utf8_polish_ci DEFAULT NULL,
  `username` varchar(128) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(128) COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_polish_ci DEFAULT NULL,
  `lastIp` varchar(16) COLLATE utf8_polish_ci DEFAULT NULL,
  `lastLog` datetime DEFAULT NULL,
  `lastFailIp` varchar(16) COLLATE utf8_polish_ci DEFAULT NULL,
  `lastFailLog` datetime DEFAULT NULL,
  `failLogCount` integer DEFAULT '0',
  `logged` tinyint DEFAULT '0',
  `active` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `active` (`active`),
  KEY `email` (`email`),
  KEY `logged` (`logged`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_auth_role` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `cms_auth_id` integer NOT NULL,
  `cms_role_id` integer NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `cms_auth_role_ibfk_1` FOREIGN KEY (`cms_auth_id`) REFERENCES `cms_auth` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cms_auth_role_ibfk_2` FOREIGN KEY (`cms_role_id`) REFERENCES `cms_role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE cms_tag
(
  `id` integer NOT NULL AUTO_INCREMENT,
  tag varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tag` (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE cms_tag_link
(
  `id` integer NOT NULL AUTO_INCREMENT,
  cms_tag_id integer NOT NULL,
  `object` varchar(32) NOT NULL,
  `objectId` integer NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `cms_tag_link_ibfk_1` FOREIGN KEY (cms_tag_id) REFERENCES cms_tag(id) ON UPDATE CASCADE ON DELETE CASCADE,
  KEY `object_object_id` (`object`,	`objectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_comment` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `cms_auth_id` integer DEFAULT NULL,
  `parent_id` integer DEFAULT '0',
  `dateAdd` datetime NOT NULL,
  `title` varchar(128) COLLATE utf8_polish_ci DEFAULT NULL,
  `text` text COLLATE utf8_polish_ci NOT NULL,
  `signature` varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
  `ip` varchar(16) COLLATE utf8_polish_ci DEFAULT NULL,
  `stars` double DEFAULT '0',
  `object` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `objectId` integer NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dateAdd` (`dateAdd`),
  KEY `object` (`object`,`objectId`),
  KEY `parent_id` (`parent_id`),
  KEY `stars` (`stars`),
  CONSTRAINT `cms_comment_ibfk_1` FOREIGN KEY (`cms_auth_id`) REFERENCES `cms_auth` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_contact` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `cms_contact_option_id` integer NOT NULL,
  `dateAdd` datetime DEFAULT NULL,
  `text` text COLLATE utf8_polish_ci,
  `reply` text COLLATE utf8_polish_ci,
  `cms_auth_id_reply` integer DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `phone` varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
  `email` varchar(128) COLLATE utf8_polish_ci NOT NULL,
  `ip` varchar(16) COLLATE utf8_polish_ci DEFAULT NULL,
  `cms_auth_id` integer DEFAULT NULL,
  `active` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `active` (`active`),
  KEY `dateAdd` (`dateAdd`),
  KEY `email` (`email`),
  KEY `uri` (`uri`),
  KEY `cms_auth_id` (`cms_auth_id`),
  KEY `cms_auth_id_reply` (`cms_auth_id_reply`),
  KEY `cms_contact_option_id` (`cms_contact_option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_contact_option` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `sendTo` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_file` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `class` varchar(32) COLLATE utf8_polish_ci DEFAULT NULL,
  `mimeType` varchar(32) COLLATE utf8_polish_ci DEFAULT NULL,
  `name` varchar(45) COLLATE utf8_polish_ci DEFAULT NULL,
  `original` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `author` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `source` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `size` bigint(20) DEFAULT NULL,
  `dateAdd` datetime DEFAULT NULL,
  `dateModify` datetime DEFAULT NULL,
  `order` integer DEFAULT NULL,
  `sticky` tinyint DEFAULT NULL,
  `object` varchar(32) COLLATE utf8_polish_ci DEFAULT NULL,
  `objectId` integer DEFAULT NULL,
  `cms_auth_id` integer DEFAULT NULL,
  `active` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`active`),
  KEY `author` (`author`),
  KEY `class` (`class`),
  KEY `dateAdd` (`dateAdd`),
  KEY `dateModify` (`dateModify`),
  KEY `name` (`name`),
  KEY `object` (`object`,`objectId`),
  KEY `order` (`order`),
  KEY `sticky` (`sticky`),
  KEY `title` (`title`),
  KEY `cms_auth_id` (`cms_auth_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_log` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `ip` varchar(16) COLLATE utf8_polish_ci DEFAULT NULL,
  `browser` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `operation` varchar(32) COLLATE utf8_polish_ci DEFAULT NULL,
  `object` varchar(32) COLLATE utf8_polish_ci DEFAULT NULL,
  `objectId` integer DEFAULT NULL,
  `data` text COLLATE utf8_polish_ci,
  `success` tinyint NOT NULL DEFAULT '0',
  `cms_auth_id` integer DEFAULT NULL,
  `dateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dateTime` (`dateTime`),
  KEY `ip` (`ip`),
  KEY `objectId` (`objectId`),
  KEY `object` (`object`),
  KEY `operation` (`operation`),
  KEY `url` (`url`),
  KEY `cms_auth_id` (`cms_auth_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_navigation` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `lang` varchar(2) COLLATE utf8_polish_ci DEFAULT NULL,
  `parent_id` integer NOT NULL DEFAULT '0',
  `order` integer NOT NULL DEFAULT '0',
  `module` varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
  `controller` varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
  `action` varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
  `params` text COLLATE utf8_polish_ci,
  `label` varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
  `title` varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
  `keywords` text COLLATE utf8_polish_ci,
  `description` text COLLATE utf8_polish_ci,
  `uri` text COLLATE utf8_polish_ci,
  `visible` tinyint NOT NULL DEFAULT '0',
  `https` tinyint DEFAULT NULL,
  `absolute` tinyint NOT NULL DEFAULT 0,
  `independent` tinyint NOT NULL DEFAULT 0,
  `nofollow` tinyint NOT NULL DEFAULT 0,
  `blank` tinyint NOT NULL DEFAULT 0,
  `dateStart` DATETIME,
  `dateEnd` DATETIME,
  `active` tinyint NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `order` (`order`),
  KEY `parent_id` (`parent_id`),
  KEY `visible` (`visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_route` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `pattern` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `replace` text COLLATE utf8_polish_ci,
  `default` text COLLATE utf8_polish_ci,
  `order` integer NOT NULL DEFAULT '0',
  `active` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_text` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `lang` varchar(2) COLLATE utf8_polish_ci DEFAULT NULL,
  `key` varchar(32) COLLATE utf8_polish_ci DEFAULT NULL,
  `content` text COLLATE utf8_polish_ci,
  `dateModify` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cms_text_lang_key` (`lang`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_cron` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `active` tinyint NOT NULL DEFAULT '0',
  `minute` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `hour` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `dayOfMonth` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `month` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `dayOfWeek` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `description` text COLLATE utf8_polish_ci,
  `module` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `controller` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `action` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `dateAdd` datetime DEFAULT NULL,
  `dateModified` datetime DEFAULT NULL,
  `dateLastExecute` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_mail_server` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `address` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `port` smallint NOT NULL DEFAULT '25',
  `username` varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
  `password` varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
  `from` varchar(200) COLLATE utf8_polish_ci DEFAULT NULL,
  `dateAdd` datetime DEFAULT NULL,
  `dateModify` datetime DEFAULT NULL,
  `active` tinyint NOT NULL DEFAULT '1',
  `ssl` varchar(16) COLLATE utf8_polish_ci DEFAULT 'tls',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_mail_definition` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `lang` varchar(2) COLLATE utf8_polish_ci DEFAULT NULL,
  `cms_mail_server_id` integer NOT NULL,
  `name` varchar(32) COLLATE utf8_polish_ci DEFAULT NULL,
  `replyTo` varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
  `fromName` varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
  `subject` varchar(200) COLLATE utf8_polish_ci DEFAULT NULL,
  `message` text COLLATE utf8_polish_ci,
  `html` tinyint NOT NULL DEFAULT '0',
  `dateAdd` datetime DEFAULT NULL,
  `dateModify` datetime DEFAULT NULL,
  `active` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`,`name`),
  CONSTRAINT `cms_mail_definition_ibfk_1` FOREIGN KEY (`cms_mail_server_id`) REFERENCES `cms_mail_server` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_mail` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `cms_mail_definition_id` integer NOT NULL,
  `fromName` varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
  `to` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `replyTo` varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
  `subject` varchar(200) COLLATE utf8_polish_ci DEFAULT NULL,
  `message` text COLLATE utf8_polish_ci,
  `attachements` text COLLATE utf8_polish_ci,
  `type` tinyint NOT NULL DEFAULT '1',
  `dateAdd` datetime DEFAULT NULL,
  `dateSent` datetime DEFAULT NULL,
  `dateSendAfter` datetime DEFAULT NULL,
  `active` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `active` (`active`),
  KEY `type` (`type`),
  CONSTRAINT `cms_mail_ibfk_1` FOREIGN KEY (`cms_mail_definition_id`) REFERENCES `cms_mail_definition` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_news` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `lang` varchar(2) COLLATE utf8_polish_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `lead` text COLLATE utf8_polish_ci,
  `text` text COLLATE utf8_polish_ci,
  `dateAdd` datetime DEFAULT NULL,
  `dateModify` datetime DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `internal` tinyint NOT NULL DEFAULT '1',
  `visible` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_stat` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `object` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `objectId` integer DEFAULT NULL,
  `dateTime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_stat_date` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `hour` smallint DEFAULT NULL,
  `day` smallint DEFAULT NULL,
  `month` smallint DEFAULT NULL,
  `year` smallint DEFAULT NULL,
  `object` varchar(32) COLLATE utf8_polish_ci DEFAULT NULL,
  `objectId` integer DEFAULT NULL,
  `count` integer NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `hour` (`hour`,`day`,`month`,`year`),
  KEY `object` (`object`,`objectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_stat_label` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `lang` varchar(2) COLLATE utf8_polish_ci DEFAULT NULL,
  `object` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `label` varchar(48) COLLATE utf8_polish_ci NOT NULL,
  `description` text COLLATE utf8_polish_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE cms_page_widget
(
  `id` integer NOT NULL AUTO_INCREMENT,
  name varchar(128),
  module varchar(64),
  controller varchar(64),
  action varchar(64),
  params varchar(512),
  active boolean,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE cms_page
(
  `id` integer NOT NULL AUTO_INCREMENT,
  name varchar(128),
  cms_navigation_id integer NOT NULL,
  cms_route_id integer NOT NULL,
  text text,
  active boolean,
  cms_auth_id integer DEFAULT NULL,
  `dateAdd`  datetime DEFAULT NULL,
  `dateModify` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `cms_page_ibfk_1` FOREIGN KEY (`cms_navigation_id`) REFERENCES cms_navigation(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `cms_page_ibfk_2` FOREIGN KEY (`cms_route_id`) REFERENCES cms_route (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `cms_page_ibfk_3` FOREIGN KEY (`cms_auth_id`) REFERENCES cms_auth (`id`) ON UPDATE SET NULL ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE cms_widget_text
(
  `id` integer NOT NULL AUTO_INCREMENT,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE cms_widget_picture
(
  `id` integer NOT NULL AUTO_INCREMENT,
  `dateAdd` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

INSERT INTO `cms_role` (`id`, `name`) VALUES
(1,	'guest'),
(2,	'member'),
(3,	'admin');

INSERT INTO cms_acl (id, cms_role_id, module, controller, action, access) VALUES (1, 3, NULL, NULL, NULL, 'allow');
INSERT INTO cms_acl (id, cms_role_id, module, controller, action, access) VALUES (2, 1, 'mmi', NULL, NULL, 'allow');
INSERT INTO cms_acl (id, cms_role_id, module, controller, action, access) VALUES (3, 1, 'cmsAdmin', 'index', 'login', 'allow');
INSERT INTO cms_acl (id, cms_role_id, module, controller, action, access) VALUES (4, 1, 'cms', NULL, NULL, 'allow');

INSERT INTO `cms_auth` (`id`, `lang`, `username`, `email`, `password`, `lastIp`, `lastLog`, `lastFailIp`, `lastFailLog`, `failLogCount`, `logged`, `active`) VALUES
(1,	'pl',	'admin',	'admin@example.com',	'd033e22ae348aeb5660fc2140aec35850c4da997',	'127.0.0.1',	'2012-02-23 15:41:12',	'89.231.108.27',	'2011-12-20 19:42:01',	8,	0,	1);

INSERT INTO `cms_auth_role` (`id`, `cms_auth_id`, `cms_role_id`) VALUES
(1,	1,	3);

INSERT INTO `cms_contact_option` (`id`, `name`) VALUES
(1,	'Inne'),
(2,	'Propozycje zmian');

INSERT INTO `cms_article` (`id`, `lang`, `title`, `uri`, `dateAdd`, `dateModify`, `text`, `noindex`) VALUES (1,	NULL,	'Hello admin',	'hello-admin',	'2014-03-20 12:06:56',	'2014-03-20 12:33:47',	'<h4>Witaj!</h4>
<p>To jest panel administracyjny systemu DEMO, pozwalający na zarządzanie treścią stron. Podłączone moduły umożliwiają dodawanie aktualności, artykułów (typu regulamin), zarządzanie strukturą menu i wiele innych, które zostaną krótko omówione w tym artykule.</p>
<p><strong>Górna sekcja została podzielona na 3 obszary:</strong></p>
<ol>
<li>Czarny pasek operacji - zawiera stałą ilość opcji: link do strony głównej panelu administracyjnego, podgląd strony frontowej, zmianę hasła i zamknięcie sesji.</li>
<li>Pasek "okruszków" - ułatwiają nawigację (np. cofnięcie do poprzedniej sekcji), oraz informują o obecnej pozycji w nawigacji.</li>
<li>Menu CMS - zawiera kompletną nawigację po panelu administracyjnym.</li>
<li>Okno robocze - pozwala na operację na danym module (wybranym z menu nawigacyjnego), pojawią się w nim np.: formularze, tabele, raporty i listy artykułów. </li>
</ol>
<h4>Przegląd modułów CMS</h4>
<ol>
<li>Aktualności - ten moduł zawiera dwa widoki: listę i szczegóły, umożliwia tworzenie treści za pomocą edytora WYSIWYG</li>
<li>Artykuły - jeden widok: artykuł, umożliwia tworzenie treści typu regulamin, polityka prywatności itp. (za pomocą WYSIWYG)</li>
<li>CMS<ol style="list-style-type: lower-alpha;`>
<li>Cron - harmonogram zadań, np. wysyłka newslettera, obliczanie statystyk itp.</li>
<li>Komentarze - agreguje komentarze użytkowników ze wszystkich modułów (np. aktualności)</li>
<li>Kontakt - zapytania zadane przez użytkowników w formularzu kontaktowym</li>
<li>Logi (systemowe i błędów) - pozwalają monitorować aplikację</li>
<li>Menu serwisu - umożliwia zarządzanie menu (zarówno frontu jak i panelu administracyjnego)</li>
<li>Pliki - agreguje pliki dodane we wszystkich modułach (np. zdjęcia w aktualnościach, awatary użytkowników itp.)</li>
<li>Strony CMS - umożliwia utworzenie szablonów (layoutów), a następnie stron opartych o te szablony, złożonych z dowolnych komponentów CMS</li>
<li>Teksty stałe - zarządzanie tekstami stałymi frontu aplikacji (np. tekst w stopce)</li>
</ol></li>
<li>Statystyki - pozwala monitorować wybrane zachowania użytkowników</li>
<li>System mailowy - odpowiada za wysyłkę e-maili do użytkowników</li>
<li>Użytkownicy - zarządzanie bazą zarejestrowanych użytkowników (oraz administratorów)<ol style="list-style-type: lower-alpha;">
<li>Uprawnienia - umożliwia nadawanie i odbieranie uprawnień wybranym rolom (ACL)</li>
</ol></li>
</ol>',	'0');

INSERT INTO `cms_navigation` (`id`, `lang`, `parent_id`, `order`, `module`, `controller`, `action`, `params`, `label`, `title`, `keywords`, `description`, `uri`, `independent`, `nofollow`, `blank`, `visible`, `dateStart`, `dateEnd`, `active`) VALUES (1,	'pl',	'0',	'0',	NULL,	NULL,	NULL,	'',	'Górne menu',	'Demo',	'',	'',	NULL,	'0',	'0',	'0',	'0',	NULL,	NULL,	1);
INSERT INTO `cms_navigation` (`id`, `lang`, `parent_id`, `order`, `module`, `controller`, `action`, `params`, `label`, `title`, `keywords`, `description`, `uri`, `independent`, `nofollow`, `blank`, `visible`, `dateStart`, `dateEnd`, `active`) VALUES (2,	'pl',	1,	'0',	'mmi',	'index',	'index',	'',	'Strona główna',	'',	'',	'',	NULL,	'0',	'0',	'0',	1,	NULL,	NULL,	1);
INSERT INTO `cms_navigation` (`id`, `lang`, `parent_id`, `order`, `module`, `controller`, `action`, `params`, `label`, `title`, `keywords`, `description`, `uri`, `independent`, `nofollow`, `blank`, `visible`, `dateStart`, `dateEnd`, `active`) VALUES (3,	'pl',	1,	1,	'cms',	'news',	'index',	'',	'Aktualności',	NULL,	'',	'',	NULL,	'0',	'0',	'0',	1,	NULL,	NULL,	1);
INSERT INTO `cms_navigation` (`id`, `lang`, `parent_id`, `order`, `module`, `controller`, `action`, `params`, `label`, `title`, `keywords`, `description`, `uri`, `independent`, `nofollow`, `blank`, `visible`, `dateStart`, `dateEnd`, `active`) VALUES (4,	'pl',	3,	'0',	'cms',	'news',	'display',	'',	'Artykuł',	NULL,	'',	'',	NULL,	'0',	'0',	'0',	'0',	NULL,	NULL,	1);
INSERT INTO `cms_navigation` (`id`, `lang`, `parent_id`, `order`, `module`, `controller`, `action`, `params`, `label`, `title`, `keywords`, `description`, `uri`, `independent`, `nofollow`, `blank`, `visible`, `dateStart`, `dateEnd`, `active`) VALUES (5,	'pl',	1,	2,	'cms',	'user',	'register',	'',	'Rejestracja',	NULL,	'',	'',	NULL,	'0',	'0',	'0',	1,	NULL,	NULL,	1);
INSERT INTO `cms_navigation` (`id`, `lang`, `parent_id`, `order`, `module`, `controller`, `action`, `params`, `label`, `title`, `keywords`, `description`, `uri`, `independent`, `nofollow`, `blank`, `visible`, `dateStart`, `dateEnd`, `active`) VALUES (6,	'pl',	1,	3,	'cms',	'contact',	'index',	'',	'Kontakt',	'Strona kontaktu',	'',	'',	NULL,	'0',	'0',	'0',	1,	NULL,	NULL,	1);

INSERT INTO `cms_text` (`id`, `lang`, `key`, `content`, `dateModify`) VALUES (1,	NULL,	'footer-copyright',	'© 2011-2014 Powered by MMi CMS',	'2014-03-19 16:59:43');

INSERT INTO `cms_cron` (`id`, `active`, `minute`, `hour`, `dayOfMonth`, `month`, `dayOfWeek`, `name`, `description`, `module`, `controller`, `action`, `dateAdd`, `dateModified`, `dateLastExecute`) VALUES (1,	1,	'*',	'*',	'*',	'*',	'*',	'Wysyłka maili',	'Wysyła maile z kolejki',	'cms',	'cron',	'sendMail',	'2012-03-14 10:35:57',	'2014-03-21 21:31:02',	'2014-03-21 21:31:02');
INSERT INTO `cms_cron` (`id`, `active`, `minute`, `hour`, `dayOfMonth`, `month`, `dayOfWeek`, `name`, `description`, `module`, `controller`, `action`, `dateAdd`, `dateModified`, `dateLastExecute`) VALUES (2,	1,	'*',	'*',	'*',	'*',	'*',	'Agregator statystyk',	'Zlicza statystyki z serwisu',	'cms',	'cron',	'agregate',	'2014-03-20 09:48:29',	'2014-03-21 21:31:02',	'2014-03-21 21:31:02');
INSERT INTO `cms_cron` (`id`, `active`, `minute`, `hour`, `dayOfMonth`, `month`, `dayOfWeek`, `name`, `description`, `module`, `controller`, `action`, `dateAdd`, `dateModified`, `dateLastExecute`) VALUES (3,	1,	'30',	'4',	'1',	'*/2',	'*',	'Czyszczenie logów',	'Czyści archiwalne logi aplikacji',	'cms',	'cron',	'clean',	'2014-03-20 09:49:37',	'2014-03-20 09:49:37',	NULL);
