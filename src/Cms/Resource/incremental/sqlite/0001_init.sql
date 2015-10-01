CREATE TABLE "DB_CHANGELOG"
(
  filename TEXT PRIMARY KEY,
  md5 TEXT NOT NULL
);

CREATE TABLE cms_acl (
    id INTEGER PRIMARY KEY,
    cms_role_id integer NOT NULL,
    module character varying(32),
    controller character varying(32),
    action character varying(32),
    access TEXT 'deny',
	FOREIGN KEY(cms_role_id) REFERENCES cms_role(id)
);

CREATE INDEX cms_acl_access_idx ON cms_acl (access);
CREATE INDEX cms_acl_action_idx ON cms_acl(action);
CREATE INDEX cms_acl_controller_idx ON cms_acl (controller);
CREATE INDEX cms_acl_module_idx ON cms_acl (module);
CREATE INDEX fki_cms_acl_cms_role_id_fkey ON cms_acl (cms_role_id);

CREATE TABLE cms_article (
    id INTEGER PRIMARY KEY,
    lang character varying(2),
    title character varying(160) NOT NULL,
    uri character varying(160) NOT NULL,
    "dateAdd" DATETIME,
    "dateModify" DATETIME,
    "text" text,
	noindex smallint DEFAULT 0 NOT NULL
);

CREATE INDEX "cms_article_dateAdd_idx" ON cms_article ("dateAdd");
CREATE INDEX "cms_article_dateModify_idx" ON cms_article ("dateModify");
CREATE INDEX cms_article_lang_idx ON cms_article (lang);
CREATE INDEX cms_article_title_idx ON cms_article (title);
CREATE INDEX cms_article_uri_idx ON cms_article (uri);

CREATE TABLE cms_auth (
    id INTEGER PRIMARY KEY,
    lang character varying(2),
    name character varying(128),
    username character varying(128) NOT NULL,
    email character varying(128) NOT NULL,
    password character varying(128),
    "lastIp" character varying(16),
    "lastLog" DATETIME,
    "lastFailIp" character varying(16),
    "lastFailLog" DATETIME,
    "failLogCount" integer DEFAULT 0,
    logged smallint DEFAULT 0,
    active smallint DEFAULT 0 NOT NULL
);

CREATE INDEX cms_auth_active_idx ON cms_auth (active);
CREATE INDEX cms_auth_email_idx ON cms_auth (email);
CREATE INDEX cms_auth_logged_idx ON cms_auth (logged);
CREATE INDEX cms_auth_username_idx ON cms_auth (username);

CREATE TABLE cms_auth_role (
    id INTEGER PRIMARY KEY,
    cms_auth_id integer NOT NULL,
    cms_role_id integer NOT NULL,
    FOREIGN KEY (cms_auth_id) REFERENCES cms_auth(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (cms_role_id) REFERENCES cms_role(id) ON UPDATE RESTRICT ON DELETE RESTRICT
);

CREATE INDEX fki_cms_auth_role_cms_auth_id ON cms_auth_role (cms_auth_id);
CREATE INDEX fki_cms_auth_role_cms_role_id ON cms_auth_role (cms_role_id);

CREATE TABLE cms_comment (
    id INTEGER PRIMARY KEY,
    cms_auth_id integer,
    parent_id integer DEFAULT 0,
    "dateAdd" DATETIME NOT NULL,
    title character varying(128),
    text text NOT NULL,
    signature character varying(64),
    ip character varying(16),
    stars real DEFAULT 0,
    object character varying(32) NOT NULL,
    "objectId" integer NOT NULL,
	FOREIGN KEY (cms_auth_id) REFERENCES cms_auth(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE INDEX "cms_comment_dateAdd_idx" ON cms_comment ("dateAdd");
CREATE INDEX "cms_comment_object_objectId_idx" ON cms_comment (object, "objectId");
CREATE INDEX cms_comment_parent_id_idx ON cms_comment (parent_id);
CREATE INDEX cms_comment_stars_idx ON cms_comment (stars);
CREATE INDEX fki_cms_comment_cms_auth_id_fkey ON cms_comment (cms_auth_id);

CREATE TABLE cms_contact (
    id INTEGER PRIMARY KEY,
    cms_contact_option_id integer NOT NULL,
    "dateAdd" DATETIME,
    text text,
    reply text,
    cms_auth_id_reply integer,
    uri character varying(255),
	"name" character varying(255),
	phone character varying(255),
    email character varying(128) NOT NULL,
    ip character varying(16),
    cms_auth_id integer,
    active smallint DEFAULT 0 NOT NULL,
	FOREIGN KEY (cms_contact_option_id) REFERENCES cms_contact_option(id),
	FOREIGN KEY (cms_auth_id) REFERENCES cms_auth(id) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (cms_auth_id_reply) REFERENCES cms_auth(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE INDEX cms_contact_active_idx ON cms_contact (active);
CREATE INDEX "cms_contact_dateAdd_idx" ON cms_contact ("dateAdd");
CREATE INDEX cms_contact_email_idx ON cms_contact (email);
CREATE INDEX cms_contact_uri_idx ON cms_contact (uri);
CREATE INDEX fki_cms_contact_cms_auth_id_fkey ON cms_contact (cms_auth_id);
CREATE INDEX fki_cms_contact_cms_auth_id_reply_fkey ON cms_contact (cms_auth_id_reply);
CREATE INDEX fki_cms_contact_cms_contact_option_id_fkey ON cms_contact (cms_contact_option_id);

CREATE TABLE cms_contact_option (
    id INTEGER PRIMARY KEY,
	sendTo uri character varying(255),
    name character varying(64) NOT NULL
);

CREATE TABLE cms_file (
    id INTEGER PRIMARY KEY,
    class character varying(32),
    "mimeType" character varying(32),
    name character varying(45),
    original character varying(255),
    title character varying(255),
    author character varying(255),
    source character varying(255),
    size bigint,
    "dateAdd" DATETIME,
    "dateModify" DATETIME,
    "order" integer,
    sticky smallint,
    object character varying(32),
    "objectId" integer,
    cms_auth_id integer,
    active smallint,
	FOREIGN KEY (cms_auth_id) REFERENCES cms_auth(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE INDEX cms_file_active_idx ON cms_file (active);
CREATE INDEX cms_file_author_idx ON cms_file (author);
CREATE INDEX cms_file_class_idx ON cms_file ("class");
CREATE INDEX "cms_file_dateAdd_idx" ON cms_file ("dateAdd");
CREATE INDEX "cms_file_dateModify_idx" ON cms_file ("dateModify");
CREATE INDEX cms_file_name_idx ON cms_file ("name");
CREATE INDEX "cms_file_object_objectId_idx" ON cms_file ("object", "objectId");
CREATE INDEX cms_file_order_idx ON cms_file ("order");
CREATE INDEX cms_file_sticky_idx ON cms_file (sticky);
CREATE INDEX cms_file_title_idx ON cms_file (title);
CREATE INDEX fki_cms_file_cms_auth_id_fkey ON cms_file (cms_auth_id);

CREATE TABLE cms_log (
    id INTEGER PRIMARY KEY,
    url character varying(255),
    ip character varying(16),
    browser character varying(255),
    operation character varying(32),
    object character varying(32),
    "objectId" integer,
    data text,
    success smallint DEFAULT 0 NOT NULL,
    cms_auth_id integer,
    "dateTime" DATETIME,
	FOREIGN KEY (cms_auth_id) REFERENCES cms_auth(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE INDEX "cms_log_dateTime_idx" ON cms_log ("dateTime");
CREATE INDEX cms_log_ip_idx ON cms_log (ip);
CREATE INDEX "cms_log_objectId_idx" ON cms_log ("objectId");
CREATE INDEX cms_log_object_idx ON cms_log ("object");
CREATE INDEX cms_log_operation_idx ON cms_log ("operation");
CREATE INDEX cms_log_url_idx ON cms_log (url);
CREATE INDEX fki_cms_log_cms_auth_id_fkey ON cms_log (cms_auth_id);

CREATE TABLE cms_navigation (
    id INTEGER PRIMARY KEY,
    lang character varying(2),
    parent_id integer DEFAULT 0 NOT NULL,
    "order" integer DEFAULT 0 NOT NULL,
    module character varying(64),
    controller character varying(64),
    action character varying(64),
    params text,
    label character varying(64),
    title character varying(64),
    keywords text,
    description text,
    uri text,
    visible smallint DEFAULT 0 NOT NULL,
	"dateStart" DATETIME,
	"dateEnd" DATETIME,
    "absolute" smallint DEFAULT 1 NOT NULL,
    "independent" smallint DEFAULT 0 NOT NULL,
    "nofollow" smallint DEFAULT 0 NOT NULL,
	"blank" smallint DEFAULT 0 NOT NULL,
	https smallint DEFAULT 1 NOT NULL,
	active smallint DEFAULT 1 NOT NULL
);

CREATE INDEX cms_navigation_order_idx ON cms_navigation ("order");
CREATE INDEX cms_navigation_parent_id_idx ON cms_navigation (parent_id);
CREATE INDEX cms_navigation_visible_idx ON cms_navigation (visible);
CREATE INDEX "cms_navigation_dateStart_idx" ON cms_navigation ("dateStart");
CREATE INDEX "cms_navigation_dateEnd_idx" ON cms_navigation ("dateEnd");
CREATE INDEX cms_navigation_active_idx ON cms_navigation (active);

CREATE TABLE cms_role (
    id INTEGER PRIMARY KEY,
    name character varying(32) NOT NULL
);

CREATE INDEX cms_role_name_idx ON cms_role ("name");

CREATE TABLE cms_route
(
   id INTEGER PRIMARY KEY,
   pattern character varying(255),
   replace text,
   "default" text,
   "order" integer NOT NULL DEFAULT 0,
   active smallint NOT NULL DEFAULT 0
);

CREATE INDEX cms_route_active_idx ON cms_route (active);
CREATE INDEX cms_route_order_idx ON cms_route ("order");

CREATE TABLE cms_tag
(
  id INTEGER PRIMARY KEY,
  tag character varying(64) NOT NULL
);

CREATE INDEX cms_tag_tag_idx ON cms_tag ("tag");

CREATE TABLE cms_tag_link
(
  id INTEGER PRIMARY KEY,
  cms_tag_id integer NOT NULL,
  "object" character varying(32) NOT NULL,
  "objectId" integer NOT NULL,
  FOREIGN KEY (cms_tag_id) REFERENCES cms_tag(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE INDEX fki_cms_tag_link_cms_tag_id_fkey ON cms_tag_link (cms_tag_id);
CREATE INDEX cms_tag_link_object_objectId_idx ON cms_tag_link ("object", "objectId");

CREATE TABLE cms_text
(
  id INTEGER PRIMARY KEY,
  lang character varying(2),
  key character varying(32),
  content text,
  "dateModify" DATETIME
);

CREATE INDEX "cms_text_dateModify_idx" ON cms_text ("dateModify");
CREATE UNIQUE INDEX cms_text_lang_key_idx ON cms_text (lang, "key");

CREATE TABLE cms_cron (
    id INTEGER PRIMARY KEY,
    active smallint DEFAULT 0 NOT NULL,
    minute character varying(50) NOT NULL,
    hour character varying(50) NOT NULL,
    "dayOfMonth" character varying(50) NOT NULL,
    month character varying(50) NOT NULL,
    "dayOfWeek" character varying(50) NOT NULL,
    name character varying(50) NOT NULL,
    description text,
    module character varying(32) NOT NULL,
    controller character varying(32) NOT NULL,
    action character varying(32) NOT NULL,
    "dateAdd" DATETIME,
    "dateModified" DATETIME,
    "dateLastExecute" DATETIME
);

CREATE INDEX cms_cron_active_idx ON cms_cron (active);

CREATE TABLE cms_mail (
    id INTEGER PRIMARY KEY,
    cms_mail_definition_id integer NOT NULL,
    "fromName" character varying(64),
    "to" character varying,
    "replyTo" character varying(64),
    subject character varying(200),
    message text,
    attachements text,
    type smallint DEFAULT 1 NOT NULL,
    "dateAdd" DATETIME,
    "dateSent" DATETIME,
    "dateSendAfter" DATETIME,
    active smallint DEFAULT 0 NOT NULL,
	FOREIGN KEY (cms_mail_definition_id) REFERENCES cms_mail_definition(id)
);

CREATE INDEX fki_cms_mail_cms_mail_definition_id_fkey ON cms_mail (cms_mail_definition_id);
CREATE INDEX cms_mail_active_idx ON cms_mail (active);
CREATE INDEX cms_mail_type_idx ON cms_mail ("type");

CREATE TABLE cms_mail_definition (
    id INTEGER PRIMARY KEY,
    lang character varying(2) NOT NULL DEFAULT 'pl',
    cms_mail_server_id integer NOT NULL,
    name character varying(32),
    "replyTo" character varying(64),
    "fromName" character varying(64),
    subject character varying(200),
    message text,
    html smallint DEFAULT 0 NOT NULL,
    "dateAdd" DATETIME,
    "dateModify" DATETIME,
    active smallint DEFAULT 0 NOT NULL,
	FOREIGN KEY (cms_mail_server_id) REFERENCES cms_mail_server(id)
);

CREATE INDEX fki_cms_mail_definition_cms_mail_server_id_fkey ON cms_mail_definition (cms_mail_server_id);
CREATE INDEX cms_mail_definition_lang_name_idx ON cms_mail_definition (lang, "name");

CREATE TABLE cms_mail_server (
    id INTEGER PRIMARY KEY,
    address character varying(64) NOT NULL,
    port smallint DEFAULT 25 NOT NULL,
    username character varying(64),
    password character varying(64),
    "from" character varying(200),
    "dateAdd" DATETIME,
    "dateModify" DATETIME,
    active smallint DEFAULT 1 NOT NULL,
    ssl character varying(16) DEFAULT 'tls'
);

CREATE TABLE cms_news (
    id INTEGER PRIMARY KEY,
    lang character varying(2),
    title character varying(255) NOT NULL,
    lead text,
    text text,
    "dateAdd" DATETIME,
    "dateModify" DATETIME,
    uri character varying(255),
	internal smallint DEFAULT 1 NOT NULL,
    visible smallint DEFAULT 1 NOT NULL
);

CREATE INDEX cms_news_uri_idx ON cms_news (uri);

CREATE TABLE cms_stat
(
  id INTEGER PRIMARY KEY,
  object character varying(50) NOT NULL,
  "objectId" integer,
  "dateTime" DATETIME NOT NULL
);

CREATE TABLE cms_stat_date
(
  id INTEGER PRIMARY KEY,
  hour smallint,
  day smallint,
  month smallint,
  year smallint,
  object character varying(32),
  "objectId" integer,
  count integer NOT NULL DEFAULT 0
);

CREATE INDEX cms_stat_date_hour_day_month_year_idx ON cms_stat_date ("hour", "day", "month", "year");
CREATE INDEX "cms_stat_date_object_objectId_idx" ON cms_stat_date ("object");

CREATE TABLE cms_stat_label
(
  id INTEGER PRIMARY KEY,
  lang character varying(2),
  object character varying(32) NOT NULL,
  label character varying(48) NOT NULL,
  description text
);

CREATE UNIQUE INDEX cms_stat_label_lang_object_idx ON cms_stat_label (lang, "object");

INSERT INTO cms_role (id, name) VALUES (1, 'guest');
INSERT INTO cms_role (id, name) VALUES (2, 'member');
INSERT INTO cms_role (id, name) VALUES (3, 'admin');

INSERT INTO cms_acl (id, cms_role_id, module, controller, action, access) VALUES (1, 3, NULL, NULL, NULL, 'allow');
INSERT INTO cms_acl (id, cms_role_id, module, controller, action, access) VALUES (2, 1, 'mmi', NULL, NULL, 'allow');
INSERT INTO cms_acl (id, cms_role_id, module, controller, action, access) VALUES (3, 1, 'cmsAdmin', 'index', 'login', 'allow');
INSERT INTO cms_acl (id, cms_role_id, module, controller, action, access) VALUES (4, 1, 'cms', NULL, NULL, 'allow');

INSERT INTO cms_auth (id, lang, username, email, password, "lastIp", "lastLog", "lastFailIp", "lastFailLog", "failLogCount", logged, active) VALUES (1, 'pl', 'admin', 'admin@example.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', '127.0.0.1', '2012-02-23 15:41:12', '89.231.108.27', '2011-12-20 19:42:01', 8, 0, 1);

INSERT INTO cms_auth_role (id, cms_auth_id, cms_role_id) VALUES (1, 1, 3);

INSERT INTO cms_contact_option (id, name) VALUES (1, 'Inne');
INSERT INTO cms_contact_option (id, name) VALUES (2, 'Propozycje zmian');

INSERT INTO "cms_article" ("id", "lang", "title", "uri", "dateAdd", "dateModify", "text", "noindex") VALUES (1,	NULL,	'Hello admin',	'hello-admin',	'2014-03-20 12:06:56',	'2014-03-20 12:33:47',	'<h4>Witaj!</h4>
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
<li>CMS<ol style="list-style-type: lower-alpha;">
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

INSERT INTO "cms_navigation" ("id", "lang", "parent_id", "order", "module", "controller", "action", "params", "label", "title", "keywords", "description", "uri", "independent", "nofollow", "blank", "visible", "dateStart", "dateEnd", "active") VALUES (1,	'pl',	'0',	'0',	NULL,	NULL,	NULL,	'',	'Górne menu',	'Demo',	'',	'',	NULL,	'0',	'0',	'0',	'0',	NULL,	NULL,	1);
INSERT INTO "cms_navigation" ("id", "lang", "parent_id", "order", "module", "controller", "action", "params", "label", "title", "keywords", "description", "uri", "independent", "nofollow", "blank", "visible", "dateStart", "dateEnd", "active") VALUES (2,	'pl',	1,	'0',	'mmi',	'index',	'index',	'',	'Strona główna',	'',	'',	'',	NULL,	'0',	'0',	'0',	1,	NULL,	NULL,	1);
INSERT INTO "cms_navigation" ("id", "lang", "parent_id", "order", "module", "controller", "action", "params", "label", "title", "keywords", "description", "uri", "independent", "nofollow", "blank", "visible", "dateStart", "dateEnd", "active") VALUES (3,	'pl',	1,	1,	'cms',	'news',	'index',	'',	'Aktualności',	NULL,	'',	'',	NULL,	'0',	'0',	'0',	1,	NULL,	NULL,	1);
INSERT INTO "cms_navigation" ("id", "lang", "parent_id", "order", "module", "controller", "action", "params", "label", "title", "keywords", "description", "uri", "independent", "nofollow", "blank", "visible", "dateStart", "dateEnd", "active") VALUES (4,	'pl',	3,	'0',	'cms',	'news',	'display',	'',	'Artykuł',	NULL,	'',	'',	NULL,	'0',	'0',	'0',	'0',	NULL,	NULL,	1);
INSERT INTO "cms_navigation" ("id", "lang", "parent_id", "order", "module", "controller", "action", "params", "label", "title", "keywords", "description", "uri", "independent", "nofollow", "blank", "visible", "dateStart", "dateEnd", "active") VALUES (5,	'pl',	1,	2,	'cms',	'user',	'register',	'',	'Rejestracja',	NULL,	'',	'',	NULL,	'0',	'0',	'0',	1,	NULL,	NULL,	1);
INSERT INTO "cms_navigation" ("id", "lang", "parent_id", "order", "module", "controller", "action", "params", "label", "title", "keywords", "description", "uri", "independent", "nofollow", "blank", "visible", "dateStart", "dateEnd", "active") VALUES (6,	'pl',	1,	3,	'cms',	'contact',	'index',	'',	'Kontakt',	'Strona kontaktu',	'',	'',	NULL,	'0',	'0',	'0',	1,	NULL,	NULL,	1);

INSERT INTO "cms_text" ("id", "lang", "key", "content", "dateModify") VALUES (1,	NULL,	'footer-copyright',	'© 2011-2014 Powered by MMi CMS',	'2014-03-19 16:59:43');

INSERT INTO "cms_cron" ("id", "active", "minute", "hour", "dayOfMonth", "month", "dayOfWeek", "name", "description", "module", "controller", "action", "dateAdd", "dateModified", "dateLastExecute") VALUES (1,	1,	'*',	'*',	'*',	'*',	'*',	'Wysyłka maili',	'Wysyła maile z kolejki',	'cms',	'cron',	'sendMail',	'2012-03-14 10:35:57',	'2014-03-21 21:31:02',	'2014-03-21 21:31:02');
INSERT INTO "cms_cron" ("id", "active", "minute", "hour", "dayOfMonth", "month", "dayOfWeek", "name", "description", "module", "controller", "action", "dateAdd", "dateModified", "dateLastExecute") VALUES (2,	1,	'*',	'*',	'*',	'*',	'*',	'Agregator statystyk',	'Zlicza statystyki z serwisu',	'cms',	'cron',	'agregate',	'2014-03-20 09:48:29',	'2014-03-21 21:31:02',	'2014-03-21 21:31:02');
INSERT INTO "cms_cron" ("id", "active", "minute", "hour", "dayOfMonth", "month", "dayOfWeek", "name", "description", "module", "controller", "action", "dateAdd", "dateModified", "dateLastExecute") VALUES (3,	1,	'30',	'4',	'1',	'*/2',	'*',	'Czyszczenie logów',	'Czyści archiwalne logi aplikacji',	'cms',	'cron',	'clean',	'2014-03-20 09:49:37',	'2014-03-20 09:49:37',	NULL);

