-- введение хранилища для плагинов
begin;

create table partner(
id serial,
name character varying(255),
description text,
uri character varying(255),
active boolean,
primary key (id));
alter table partner owner to wwwrynda;

create table partner_manager(
id serial,
name character varying(100),
description text,
active boolean,
primary key (id));
alter table partner_manager owner to wwwrynda;

create table partner_parameter(
id serial,
partner_id integer,
manager_id integer,
name character varying(100),
value character varying(255),
primary key (id),
foreign key (partner_id) references partner on update cascade on delete cascade,
foreign key (manager_id) references partner_manager on update cascade on delete cascade
);
alter table partner_parameter owner to wwwrynda;

update config set value='0.35' where key='db_version';
commit;
