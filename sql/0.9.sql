-- Модификация для поддержки библиотек авторизации
begin;
drop table security.ci_sessions;
drop table security.login_attempts;
drop table security.permissions;
drop table security.roles;
drop table security.user_autologin;
drop table security.user_profile;
drop table security.user_temp;
drop table security.users;

CREATE TABLE "security"."users" (
"id" SERIAL NOT NULL,
"group_id" int NOT NULL,
"ip_address" char(16) NOT NULL,
"username" varchar(15) NOT NULL,
"password" varchar(40) NOT NULL,
"salt" varchar(40),
"email" varchar(100) NOT NULL,
"activation_code" varchar(40),
"forgotten_password_code" varchar(40),
"remember_code" varchar(40),
"created_on" int NOT NULL,
"last_login" int,
"active" int4,
PRIMARY KEY("id"),
CONSTRAINT "check_id" CHECK(id >= 0),
CONSTRAINT "check_group_id" CHECK(group_id >= 0),
CONSTRAINT "check_active" CHECK(active >= 0)
);
alter table security.users owner to wwwrynda;

CREATE TABLE "security"."meta" (
"id" SERIAL NOT NULL,
"user_id" int,
"first_name" varchar(50),
"last_name" varchar(50),
"company" varchar(100),
"phone" varchar(20),
PRIMARY KEY("id"),
CONSTRAINT "check_id" CHECK(id >= 0),
CONSTRAINT "check_user_id" CHECK(user_id >= 0)
);
alter table security.meta owner to wwwrynda;

CREATE TABLE "security"."groups" (
"id" SERIAL NOT NULL,
"name" varchar(20) NOT NULL,
"description" varchar(100) NOT NULL,
PRIMARY KEY("id"),
CONSTRAINT "check_id" CHECK(id >= 0)
);
alter table security.groups owner to wwwrynda;

INSERT INTO security.groups (id, name, description) VALUES
(1,'admin','Administrator'),
(2,'members','General User');

INSERT INTO security.meta (id, user_id, first_name, last_name, company, phone) VALUES
('1','1','Admin','istrator','ADMIN','0');

INSERT INTO security.users (id, group_id, ip_address, username, password, salt, email, activation_code, forgotten_password_code, created_on, last_login, active) VALUES
('1','1','127.0.0.1','administrator','59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4','9462e8eee0','admin@admin.com','',NULL,'1268889823','1268889823','1');

update config set value='0.9' where key='db_version';
end;
