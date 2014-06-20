--Создания дополнительной таблицы для хранения пользовательских сессий
begin;
CREATE TABLE "security"."ci_sessions" (
session_id character varying (40) not null DEFAULT '0',
ip_address character varying (16) not null DEFAULT '0',
user_agent character varying (50) NOT NULL,
last_activity integer not null DEFAULT 0,
user_data text not null DEFAULT '',
PRIMARY KEY (session_id)
);
alter table security.ci_sessions owner to wwwrynda;
update config set value ='0.10' where key='db_version';
commit;

