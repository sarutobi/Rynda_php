begin;
-- Добавление сущности для организации субдоменов
create table subdomain(
id serial,
url character varying(50),
title character varying(50),
is_current boolean default false,
status smallint default 0,
"order" integer not null,
constraint subdomain_pkey primary key(id)
);
create unique index pki_subdomain on subdomain using btree(id);
alter table subdomain cluster on pki_subdomain;
alter table subdomain owner to wwwrynda;
comment on table subdomain is 'Таблица поддоменов для организации страниц атласа';
comment on column subdomain.title is 'Эта надпись будет добавлена к заголовку сайта';
comment on column subdomain.status is '0-неактивно, 1 -активно';
--insert into subdomain values (1, '', '', false, 1,);

alter table "Category" add column subdomain_id integer NULL;
alter table "Category" add constraint cat_subdomain foreign key (subdomain_id)
references subdomain(id) match simple on update no action on delete set null;
alter table "Message" add column subdomain_id integer NULL;
alter table "Message" add constraint cat_subdomain foreign key (subdomain_id)
references subdomain(id) match simple on update no action on delete set null;

update config set value='0.5' where key='db_version';
commit;
